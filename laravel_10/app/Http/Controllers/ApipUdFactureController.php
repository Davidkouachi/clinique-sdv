<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApipUdFactureController extends Controller
{

    public function ud_facture_traitement(Request $request, $numfac)
    {

        DB::beginTransaction();

        try {

            $now = now()->format('d/m/Y H:i:s');

            $des = null;

            switch ($request->type) {
                case 'updateDate':

                    $dateTime = \Carbon\Carbon::parse($request->date)->format('Y-m-d H:i:s');
                    $dateTimedes = \Carbon\Carbon::parse($request->date)->format('d/m/Y H:i:s');

                    $des = "Modification de la date d'enregistrement de la facture N°{$numfac} au {$dateTimedes} par l'utilisateur {$request->login}, pour motif : {$request->motif}";

                    switch ($request->acte) {
                        case 'cons':
                            $cons = DB::table('consultation')->where('numfac', $numfac)->first();
                            if (!$cons) throw new \Exception("Facture consultation introuvable");

                            DB::table('consultation')->where('numfac', $numfac)
                                ->update(['date' => $dateTime, 'updated_at' => $dateTime]);

                            // Facture
                            DB::table('factures')->where('numfac', $numfac)
                                ->update(['datefacture' => $dateTime, 'updated_at' => $dateTime]);

                            break;

                        case 'exam':
                            $exam = DB::table('testlaboimagerie')->where('numfacbul', $numfac)->first();
                            if (!$exam) throw new \Exception("Facture examens introuvable");

                            DB::table('testlaboimagerie')->where('numfacbul', $numfac)
                                ->update(['date' => $dateTime, 'updated_at' => $dateTime]);

                            DB::table('factures')->where('numfac', $numfac)
                                ->update(['datefacture' => $dateTime, 'updated_at' => $dateTime]);

                            break;

                        case 'soins':
                            $soins = DB::table('soins_medicaux')->where('numfac_soins', $numfac)->first();
                            if (!$soins) throw new \Exception("Facture soins introuvable");

                            DB::table('soins_medicaux')->where('id_soins', $soins->id_soins)
                                ->update(['date_soin' => $dateTime, 'updated_at' => $dateTime]);

                            DB::table('factures')->where('numfac', $numfac)
                                ->update(['created_at' => $dateTime, 'updated_at' => $dateTime]);

                            break;

                        case 'hosp':
                            $hosp = DB::table('admission')->where('numhospit', $numfac)->first();
                            if (!$hosp) throw new \Exception("Admission introuvable");

                            DB::table('admission')->where('numhospit', $numfac)
                                ->update(['created_at' => $dateTime, 'updated_at' => $dateTime]);

                            DB::table('factures')->where('numfac', $numfac)
                                ->update(['datefacture' => $dateTime, 'updated_at' => $dateTime]);

                            break;

                        default:
                            throw new \Exception("Type de facture non reconnu");
                    }

                    break;

                case 'delete':

                    // Vérification encaissement avant suppression
                    $encaissementInfo = $this->checkEncaissement($numfac);

                    switch ($request->acte) {
                        case 'cons':
                            $rech = DB::table('consultation')
                                ->where('numfac', $numfac)
                                ->first();

                            if (!$rech) {
                                return response()->json(['introuvable' => true, 'message' => 'facture introuvable']);
                            }

                            // Suppressions
                            $this->deleteByField('consultation', 'numfac', $numfac);
                            $this->deleteByField('factures', 'numfac', $numfac);
                            $this->deleteByField('caisse', 'nopiece', $numfac, true);   // plusieurs enregistrements
                            $this->deleteByField('journal', 'numfac', $numfac, true);

                            $des = "Suppression de la facture de Consultation N°{$numfac} {$encaissementInfo} le {$now} par l'utilisateur {$request->login}, pour motif : {$request->motif}";
                            break;

                        case 'exam':
                            $rech_exam = DB::table('testlaboimagerie')
                                ->where('numfacbul', $numfac)
                                ->select('idtestlaboimagerie')
                                ->first();

                            if (!$rech_exam) {
                                return response()->json(['introuvable' => true, 'message' => 'facture introuvable']);
                            }

                            $this->deleteByField('factures', 'numfac', $numfac);
                            $this->deleteByField('detailtestlaboimagerie', 'idtestlaboimagerie', $rech_exam->idtestlaboimagerie, true);
                            $this->deleteByField('testlaboimagerie', 'numfacbul', $numfac);

                            $des = "Suppression de la facture d'Examens N°{$numfac} {$encaissementInfo} le {$now} par l'utilisateur {$request->login}, pour motif : {$request->motif}";
                            break;

                        case 'soins':
                            $rech_soins = DB::table('soins_medicaux')
                                ->where('numfac_soins', $numfac)
                                ->select('id_soins')
                                ->first();

                            if (!$rech_soins) {
                                return response()->json(['introuvable' => true, 'message' => 'facture introuvable']);
                            }

                            $this->deleteByField('factures', 'numfac', $numfac);
                            $this->deleteByField('soins_medicaux_itemsoins', 'id_soins', $rech_soins->id_soins, true);
                            $this->deleteByField('soins_medicaux_itemmedics', 'id_soins', $rech_soins->id_soins, true);
                            $this->deleteByField('soins_medicaux', 'id_soins', $rech_soins->id_soins);

                            $des = "Suppression de la facture de Soins Ambulatoires N°{$numfac} {$encaissementInfo} le {$now} par l'utilisateur {$request->login}, pour motif : {$request->motif}";
                            break;

                        case 'hosp':
                            $rech_hosp = DB::table('admission')
                                ->where('numhospit', $numfac)
                                ->select('numhospit', 'idtypelit', 'numfachospit')
                                ->first();

                            if (!$rech_hosp) {
                                return response()->json(['introuvable' => true, 'message' => 'facture introuvable']);
                            }

                            // Suppression facture admission
                            $this->deleteByField('factures', 'numfac', $rech_hosp->numfachospit);

                            // Facturation hospit
                            $facturationHospit = DB::table('facturation_hospit')
                                ->where('numpchr', $rech_hosp->numhospit)
                                ->select('numfac')
                                ->first();

                            if ($facturationHospit) {
                                $this->deleteByField('factures', 'numfac', $facturationHospit->numfac);
                                $this->deleteByField('facturation_hospit', 'numpchr', $rech_hosp->numhospit);
                            }

                            // Mise à jour lit et chambre
                            DB::table('lits')->where('id', $rech_hosp->idtypelit)->update([
                                'statut' => 'disponible',
                                'updated_at' => $dateTime,
                            ]);

                            $lit = DB::table('lits')->where('id', $rech_hosp->idtypelit)->first();
                            $chambre = DB::table('chambres')->where('id', $lit->chambre_id)->first();

                            $nbre_lit = DB::table('lits')
                                ->where('chambre_id', $lit->chambre_id)
                                ->where('statut', 'disponible')
                                ->count();

                            if ($chambre->nbre_lit == $nbre_lit) {
                                DB::table('chambres')->where('id', $lit->chambre_id)->update([
                                    'statut' => 'disponible',
                                    'updated_at' => $dateTime,
                                ]);
                            }

                            $this->deleteByField('admission', 'numhospit', $rech_hosp->numhospit);

                            $des = "Suppression de la facture d'Hospitalisation N°{$numfac} {$encaissementInfo} le {$now} par l'utilisateur {$request->login}, pour motif : {$request->motif}";

                            break;

                        default:
                            throw new \Exception("Type de facture non reconnu");
                    }

                    break;

                default:
                    throw new \Exception("Type de facture non reconnu");
            }

            // Insertion historique
            DB::table('historiqueudfac')->insert([
                'login' => $request->login,
                'description' => $des,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    private function deleteByField(string $table, string $field, $value, bool $multiple = false)
    {
        $deleted = DB::table($table)->where($field, $value)->delete();
        if ($deleted === 0 && !$multiple) {
            throw new \Exception("Erreur lors de la suppression dans la table {$table}");
        }
    }

    private function checkEncaissement($numfac): string
    {
        $existCaisse = DB::table('caisse')->where('nopiece', $numfac)->exists();
        $existJournal = DB::table('journal')->where('numfac', $numfac)->exists();
        $existfacture = DB::table('factures')->where('numfac', $numfac)->first();

        if ($existCaisse || $existJournal) {

            $données = null;

            if ($existfacture) {
                return "(Montant total : {$this->formatPrix($existfacture->montanttotal)} Fcfa, Part Assurance : {$this->formatPrix($existfacture->montant_ass)} Fcfa, Part patient : {$this->formatPrix($existfacture->montant_pat)} Fcfa, Remise : {$this->formatPrix($existfacture->remise)} Fcfa, Part patient réglé : {$this->formatPrix($existfacture->montantregle_pat)} Fcfa, Part patient restant : {$this->formatPrix($existfacture->montantreste_pat)} Fcfa, )";
            }

            return "(facture déjà encaissée ou partiellement encaissée)";
        }

        return "(facture non encaissée)";
    }

    private function formatPrix($prix)
    {
        return number_format($prix, 0, ',', '.');
    }


}
