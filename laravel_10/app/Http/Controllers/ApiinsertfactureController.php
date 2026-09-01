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

// use App\Models\assurance;
// use App\Models\taux;
// use App\Models\societe;
// use App\Models\patient;
// use App\Models\chambre;
// use App\Models\lit;
// use App\Models\acte;
// use App\Models\typeacte;
// use App\Models\typemedecin;
// use App\Models\user;
// use App\Models\role;
// use App\Models\consultation;
// use App\Models\detailconsultation;
// use App\Models\typeadmission;
// use App\Models\natureadmission;
// use App\Models\detailhopital;
// use App\Models\facture;
// use App\Models\produit;
// use App\Models\soinshopital;
// use App\Models\soinsinfirmier;
// use App\Models\typesoins;
// use App\Models\soinspatient;
// use App\Models\sp_produit;
// use App\Models\sp_soins;
// use App\Models\examenpatient;
// use App\Models\examen;
// use App\Models\prelevement;
// use App\Models\caisse;
// use App\Models\historiquecaisse;

class ApiinsertfactureController extends Controller
{
    private function generateUniqueMatriculeNumRecu()
    {
        do {
            // Génère une chaîne aléatoire de 6 caractères (majuscule, minuscule, chiffres)
            $matricule = Str::random(6);
        } while (DB::table('journal')->where('numrecu', '=', "RCE".$matricule)->exists());

        return $matricule;
    }







    public function facture_payer(Request $request, $numfac)
    {

        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        try {

            
            // table consultation
            $updateData_consultation =[
                'regle' => 1,
                'updated_at' => now(),
            ];

            $consultationUpdate = DB::table('consultation')
                                ->where('numfac', '=', $numfac)
                                ->update($updateData_consultation);

            if ($consultationUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table consultation');
            }


            $montant_recu = str_replace('.', '', $request->montant_verser) - str_replace('.', '', $request->montant_remis);

            $op = '';
            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $op = ' (faveur)';

            }
            
            // table caisse
            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $numfac,
                'type' => 'entree',
                'libelle' => 'Encaissement facture consultation '. $op,
                'montant' => $montant_recu,
                'dateop' => now(),
                'datecreat' => now(),
                'login' => Auth::user()->login,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            // table journal
            $recu = $this->generateUniqueMatriculeNumRecu();
            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => $request->matricule,
                'date' => now(),
                'numrecu' => 'REC'.$recu,
                'montant_recu' => $montant_recu,
                'numjournal' => $recu,
                'numfac' => $numfac,
                'type_action' => 0,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }
            
            
            $fac = DB::table('factures')->select('montant_pat','montantregle_pat')->where('numfac', '=', $numfac)->first();

            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $regle = $fac->montant_pat;

                $reste = 0;

            } else {
                
                $regle = str_replace('.', '', $montant_recu) + str_replace('.', '', $fac->montantregle_pat);

                $reste = str_replace('.', '', $fac->montant_pat) - $regle;

            }

            $updateData_factures =[
                'montantregle_pat' => $montant_recu,
                'montantpat_verser' => str_replace('.', '', $request->montant_verser),
                'montantpat_remis' => str_replace('.', '', $request->montant_remis),
                'montantreste_pat' => $reste,
                'montantregle_pat' => $regle,
                'datereglt_pat' => now(),
                'numrecu' => 'REC'.$recu,
                'updated_at' => now(),
            ];

            $facturesUpdate = DB::table('factures')
                                ->where('numfac', '=', $numfac)
                                ->update($updateData_factures);

            if ($facturesUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table factures');
            }


            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $montant,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }

            // table imprime recu
            $facture = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
                ->leftJoin('societeassure', 'consultation.codesocieteassure', '=', 'societeassure.codesocieteassure')
                ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
                ->leftJoin('assurance', 'consultation.codeassurance', '=', 'assurance.codeassurance')
                ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
                ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
                ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
                ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
                ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
                ->where('consultation.idconsexterne', '=', $request->id)
                ->select(
                    'consultation.idconsexterne as idconsexterne',
                    'consultation.idenregistremetpatient as idenregistremetpatient',
                    'consultation.montant as montant',
                    'consultation.date as date',
                    'consultation.numfac as numfac',
                    'consultation.numbon as numbon',
                    'consultation.ticketmod as partpatient',
                    'consultation.partassurance as partassurance',
                    'dossierpatient.numdossier as numdossier',
                    'patient.nomprenomspatient as nom_patient',
                    'patient.telpatient as tel_patient',
                    'patient.assure as assure',
                    'patient.datenaispatient as datenais',
                    'patient.telpatient as telpatient',
                    'patient.matriculeassure as matriculeassure',
                    'medecin.nomprenomsmed as nom_medecin',
                    'specialitemed.nomspecialite as specialite',
                    'factures.remise as remise',
                    'societeassure.nomsocieteassure as societe',
                    'assurance.libelleassurance as assurance',
                    'tauxcouvertureassure.valeurtaux as taux',
                    'filiation.libellefiliation as filiation',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.montantregle_pat as part_patient_regler',
                    'factures.numrecu as numrecu',
                    'factures.datereglt_pat as datereglt_pat',
                    'factures.montantpat_verser as montant_verser',
                    'factures.montantpat_remis as montant_remis',
                    'factures.montantreste_pat as montant_restant',
                )
                ->first();

            DB::commit();
            return response()->json(['success' => true, 'facture' => $facture]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function facture_payer_hos(Request $request, $numfac)
    {

        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        try {

            $montant_recu = abs(str_replace('.', '', $request->montant_verser) - str_replace('.', '', $request->montant_remis));

            $op = '';
            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $op = ' (faveur)';

            }

            // table caisse
            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $numfac,
                'type' => 'entree',
                'libelle' => 'Encaissement facture hospitalisation'. $op,
                'montant' => $montant_recu,
                'dateop' => now(),
                'datecreat' => now(),
                'login' => Auth::user()->login,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            
            // table journal
            $recu = $this->generateUniqueMatriculeNumRecu();
            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => $request->matricule,
                'date' => now(),
                'numrecu' => 'REC'.$recu,
                'montant_recu' => $montant_recu,
                'numjournal' => $recu,
                'numfac' => $numfac,
                'type_action' => 0,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }

            // table factures
            $fac = DB::table('factures')->select('montant_pat','montantregle_pat')->where('numfac', '=', $numfac)->first();

            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $regle = $fac->montant_pat;

                $reste = 0;

            } else {
                
                $regle = str_replace('.', '', $montant_recu) + str_replace('.', '', $fac->montantregle_pat);

                $reste = str_replace('.', '', $fac->montant_pat) - $regle;

            }

            $updateData_factures =[
                'montantregle_pat' => $montant_recu,
                'montantpat_verser' => str_replace('.', '', $request->montant_verser),
                'montantpat_remis' => str_replace('.', '', $request->montant_remis),
                'montantreste_pat' => $reste,
                'montantregle_pat' => $regle,
                'datereglt_pat' => now(),
                'numrecu' => 'REC'.$recu,
                'updated_at' => now(),
            ];

            $facturesUpdate = DB::table('factures')
                                ->where('numfac', '=', $numfac)
                                ->update($updateData_factures);

            if ($facturesUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table factures');
            }


            if ($reste == 0) {

                $updateData_payer =[
                    'statut' => 'sortie',
                    'updated_at' => now(),
                ];

                $payerUpdate = DB::table('admission')
                                    ->where('numfachospit', '=', $numfac)
                                    ->update($updateData_payer);

                if ($payerUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table lits');
                }
            
                // statut disponibilite
                $admission = DB::table('admission')->where('numfachospit', '=', $numfac)->select('idtypelit')->first();

                $updateData_statut_lit =[
                    'statut' => 'disponible',
                    'updated_at' => now(),
                ];

                $statutLitUpdate = DB::table('lits')
                                    ->where('id', '=', $admission->idtypelit)
                                    ->update($updateData_statut_lit);

                if ($statutLitUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table lits');
                }

                $lit = DB::table('lits')->where('id', '=', $admission->idtypelit)->select('lits.*')->first();
                $chambre = DB::table('chambres')->where('id', '=', $lit->chambre_id)->select('chambres.*')->first();

                $nbre_lit = DB::table('lits')
                    ->where('chambre_id', '=', $lit->chambre_id)
                    ->where('statut', '=', 'disponible')
                    ->count();


                if ($chambre->nbre_lit == $nbre_lit) {
                    
                    $updateData_statut_chambre =[
                        'statut' => 'disponible',
                        'updated_at' => now(),
                    ];

                    $statutChambreUpdate = DB::table('chambres')
                                    ->where('id', '=', $lit->chambre_id)
                                    ->update($updateData_statut_chambre);

                    if ($statutChambreUpdate == 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table chambres');
                    }
                }

            }


            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $montant,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }

            // table factures
            $hopital = DB::table('admission')
                ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
                ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
                ->leftJoin('assurance', 'admission.codeassurance', '=', 'assurance.codeassurance')
                ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
                ->leftJoin('societeassure', 'admission.codesocieteassure', '=', 'societeassure.codesocieteassure')
                ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
                ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
                ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
                ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
                ->join('chambres', 'chambres.id', '=', 'admission.codechbre')
                ->join('lits', 'lits.id', '=', 'admission.idtypelit')
                ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
                ->where('dossierpatient.codetypedossier', '=', 'DH')
                ->where('admission.numhospit', '=', $request->id)
                // ->whereBetween('admission.dateentree', [$date1, $date2])
                ->select(
                    'admission.*',
                    'patient.nomprenomspatient as patient',
                    'patient.assure as assure',
                    'patient.datenaispatient as datenais',
                    'patient.telpatient as telpatient',
                    'patient.codeassurance as codeassurance',
                    'patient.matriculeassure as matriculeassure',
                    'assurance.libelleassurance as assurance',
                    'tauxcouvertureassure.valeurtaux as taux',
                    'dossierpatient.numdossier as numdossier',
                    'societeassure.nomsocieteassure as societe',
                    'medecin.nomprenomsmed as medecin',
                    'specialitemed.nomspecialite as specialite',
                    'typehospitalsation.nomtypehospit as type_hospit',
                    'naturehospit.nomnaturehospit as nature_hospit',
                    'chambres.code as chambre_code',
                    'chambres.prix as chambre_prix',
                    'lits.code as lit_code',
                    'lits.type as lit_type',
                    'factures.montanttotal as montant_total',
                    'factures.montant_ass as montant_ass',
                    'factures.montant_pat as montant_pat',
                    'factures.montantregle_pat as part_patient_regler',
                    'factures.numrecu as numrecu',
                    'factures.datereglt_pat as datereglt_pat',
                    'factures.montantpat_verser as montant_verser',
                    'factures.montantpat_remis as montant_remis',
                    'factures.montantreste_pat as montant_restant',
                    'factures.remise as remise',
                )
                ->first();

            $hopital->taux = $hopital->taux ?? 0;

            $prestation = DB::table('facturation_hospit')
                ->join('garanties_hospit', 'garanties_hospit.id', '=', 'facturation_hospit.idgarhospit')
                ->where('facturation_hospit.numpchr', '=', $request->id)
                ->select(
                    'garanties_hospit.libelle as name',
                    'facturation_hospit.pu as prix',
                    'facturation_hospit.montaccorde as prix_ass',
                    'facturation_hospit.montrefus as prix_pat',
                )
                ->get();

            DB::commit();
            return response()->json([
                'success' => true,
                'hopital' => $hopital,
                'prestation' => $prestation,
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function facture_payer_soinsam(Request $request,$numfac)
    {

        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        try {

            
            $updateData_soinsmedicaux =[
                'paid_status' => 1,
                'updated_at' => now(),
            ];

            $soinsmedicauxUpdate = DB::table('soins_medicaux')
                                ->where('numfac_soins', '=', $numfac)
                                ->update($updateData_soinsmedicaux);

            if ($soinsmedicauxUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table soins_medicaux');
            }


            $montant_recu = str_replace('.', '', $request->montant_verser) - str_replace('.', '', $request->montant_remis);

            $op = '';
            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $op = ' (faveur)';

            }
            
            // table caisse
            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $numfac,
                'type' => 'entree',
                'libelle' => 'Encaissement facture soins infirmier'. $op,
                'montant' => $montant_recu,
                'dateop' => now(),
                'datecreat' => now(),
                'login' => Auth::user()->login,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            
            // table journal
            $recu = $this->generateUniqueMatriculeNumRecu();
            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => $request->matricule,
                'date' => now(),
                'numrecu' => 'REC'.$recu,
                'montant_recu' => $montant_recu,
                'numjournal' => $recu,
                'numfac' => $numfac,
                'type_action' => 0,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }

            // table factures
            $fac = DB::table('factures')->select('montant_pat','montantregle_pat')->where('numfac', '=', $numfac)->first();

            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $regle = $fac->montant_pat;

                $reste = 0;

            } else {
                
                $regle = str_replace('.', '', $montant_recu) + str_replace('.', '', $fac->montantregle_pat);

                $reste = str_replace('.', '', $fac->montant_pat) - $regle;

            }

            $updateData_factures =[
                'montantregle_pat' => $montant_recu,
                'montantpat_verser' => str_replace('.', '', $request->montant_verser),
                'montantpat_remis' => str_replace('.', '', $request->montant_remis),
                'montantreste_pat' => $reste,
                'montantregle_pat' => $regle,
                'datereglt_pat' => now(),
                'numrecu' => 'REC'.$recu,
                'updated_at' => now(),
            ];

            $facturesUpdate = DB::table('factures')
                                ->where('numfac', '=', $numfac)
                                ->update($updateData_factures);

            if ($facturesUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table factures');
            }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $montant,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }

            // table imprime recu
            $search = DB::table('soins_medicaux')->where('numfac_soins', '=', $numfac)->select('id_soins')->first();
            $id = $search->id_soins;

            if ($id) {
                
                $produittotal = DB::table('soins_medicaux_itemmedics')
                    ->where('id_soins', '=', $id)
                    ->select(DB::raw('COALESCE(SUM(REPLACE(price, ".", "") + 0), 0) as total'))
                    ->first();

                // Total des soins
                $soinstotal = DB::table('soins_medicaux_itemsoins')
                    ->where('id_soins', '=', $id)
                    ->select(DB::raw('COALESCE(SUM(REPLACE(price, ".", "") + 0), 0) as total'))
                    ->first();

                $soins = DB::table('soins_medicaux_itemsoins')
                    ->where('id_soins', '=', $id)
                    ->select('soins_medicaux_itemsoins.*')
                    ->get();

                $produit = DB::table('soins_medicaux_itemmedics')
                    ->join('medicine', 'medicine.medicine_id', '=', 'soins_medicaux_itemmedics.medicine_id')
                    ->where('id_soins', '=', $id)
                    ->select('soins_medicaux_itemmedics.*','medicine.price as priceu')
                    ->get();

                $patient = DB::table('soins_medicaux')
                    ->Join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
                    ->Join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
                    ->leftjoin('dossierpatient', 'dossierpatient.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                    ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
                    ->leftJoin('assurance', 'soins_medicaux.codeassurance', '=', 'assurance.codeassurance')
                    ->leftJoin('societeassure', 'soins_medicaux.codesocieteassure', '=', 'societeassure.codesocieteassure')
                    ->where('soins_medicaux.id_soins', '=', $id)
                    ->select(
                        'soins_medicaux.*',
                        'dossierpatient.numdossier as numdossier',
                        'patient.nomprenomspatient as nom_patient',
                        'patient.telpatient as tel_patient',
                        'patient.assure as assure',
                        'patient.datenaispatient as datenais',
                        'patient.telpatient as telpatient',
                        'patient.matriculeassure as matriculeassure',
                        'assurance.libelleassurance as assurance',
                        'societeassure.nomsocieteassure as societe',
                        'tauxcouvertureassure.valeurtaux as taux',
                        'factures.montant_ass as part_assurance',
                        'factures.montant_pat as part_patient',
                        'factures.montantregle_pat as part_patient_regler',
                        'factures.numrecu as numrecu',
                        'factures.datereglt_pat as datereglt_pat',
                        'factures.montantpat_verser as montant_verser',
                        'factures.montantpat_remis as montant_remis',
                        'factures.montantreste_pat as montant_restant',
                        'factures.remise as remise',
                    )
                    ->first();

                $patient->nbre_soins = DB::table('soins_medicaux_itemsoins')
                    ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

                $patient->nbre_produit = DB::table('soins_medicaux_itemmedics')
                    ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

                $patient->prototal = $produittotal->total ?? 0;
                $patient->stotal = $soinstotal->total ?? 0;

                DB::commit();
                return response()->json([
                    'success' => true,
                    'patient' =>$patient,
                    'soins' => $soins,
                    'produit' => $produit,
                ]);

            } else {
                throw new Exception('Impossible de retrouver la facture');
            }

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function facture_payer_examen(Request $request, $numfac)
    {

        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }
        
        DB::beginTransaction();

        try {

            $montant_recu = str_replace('.', '', $request->montant_verser) - str_replace('.', '', $request->montant_remis);

            $op = '';
            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $op = ' (faveur)';

            }
            
            // table caisse
            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $numfac,
                'type' => 'entree',
                'libelle' => 'Encaissement facture biologie/imagerie'. $op,
                'montant' => $montant_recu,
                'dateop' => now(),
                'datecreat' => now(),
                'login' => Auth::user()->login,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            
            // table journal
            $recu = $this->generateUniqueMatriculeNumRecu();
            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => $request->matricule,
                'date' => now(),
                'numrecu' => 'REC'.$recu,
                'montant_recu' => $montant_recu,
                'numjournal' => $recu,
                'numfac' => $numfac,
                'type_action' => 0,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }

            // table factures
            $fac = DB::table('factures')->select('montant_pat','montantregle_pat')->where('numfac', '=', $numfac)->first();

            if (str_replace('.', '', $request->montant) == 0 ) {
                
                $regle = $fac->montant_pat;

                $reste = 0;

            } else {
                
                $regle = str_replace('.', '', $montant_recu) + str_replace('.', '', $fac->montantregle_pat);

                $reste = str_replace('.', '', $fac->montant_pat) - $regle;

            }

            $updateData_factures =[
                'montantregle_pat' => $montant_recu,
                'montantpat_verser' => str_replace('.', '', $request->montant_verser),
                'montantpat_remis' => str_replace('.', '', $request->montant_remis),
                'montantreste_pat' => $reste,
                'montantregle_pat' => $regle,
                'datereglt_pat' => now(),
                'numrecu' => 'REC'.$recu,
                'updated_at' => now(),
            ];

            $facturesUpdate = DB::table('factures')
                                ->where('numfac', '=', $numfac)
                                ->update($updateData_factures);

            if ($facturesUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table factures');
            }


            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $montant,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }


            // table imprime recu
            $search = DB::table('testlaboimagerie')->where('numfacbul', '=', $numfac)->select('idtestlaboimagerie')->first();
            $id = $search->idtestlaboimagerie;

            if ($id) {
                
                $facture = DB::table('testlaboimagerie')
                    ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                    ->leftjoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
                    ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
                    ->leftJoin('assurance', 'testlaboimagerie.codeassurance', '=', 'assurance.codeassurance')
                    ->leftJoin('societeassure', 'testlaboimagerie.codesocieteassure', '=', 'societeassure.codesocieteassure')
                    ->leftjoin('medecin', 'testlaboimagerie.codemedecin', '=', 'medecin.codemedecin')
                    ->join('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
                    ->where('testlaboimagerie.idtestlaboimagerie', '=', $id)
                    ->select(
                        'testlaboimagerie.*',
                        'dossierpatient.numdossier as numdossier',
                        'patient.nomprenomspatient as nom_patient',
                        'patient.telpatient as telpatient',
                        'patient.assure as assure',
                        'patient.datenaispatient as datenais',
                        'patient.matriculeassure as matriculeassure',
                        'medecin.nomprenomsmed as nom_medecin',
                        'factures.remise as remise',
                        'assurance.libelleassurance as assurance',
                        'societeassure.nomsocieteassure as societe',
                        // 'tauxcouvertureassure.valeurtaux as taux',
                        'factures.montant_ass as part_assurance',
                        'factures.montant_pat as part_patient',
                        'factures.montanttotal as montant',
                        'factures.montantregle_pat as part_patient_regler',
                        'factures.numrecu as numrecu',
                        'factures.datereglt_pat as datereglt_pat',
                        'factures.montantpat_verser as montant_verser',
                        'factures.montantpat_remis as montant_remis',
                        'factures.montantreste_pat as montant_restant',
                        'factures.taux_applique as taux',
                        'factures.remise as remise',
                    )
                    ->first();

                $examen = DB::table('detailtestlaboimagerie')
                    ->where('idtestlaboimagerie', '=', $id)
                    ->select(
                        'detailtestlaboimagerie.denomination as examen',
                        'detailtestlaboimagerie.resultat as resultat',
                        'detailtestlaboimagerie.prix as prix',
                    )
                    ->get();

                $sumMontantEx = $examen->sum(function ($item) {
                    $montantEx = str_replace('.', '', $item->prix);
                    return (int) $montantEx;
                });

                DB::commit();
                return response()->json([
                    'success' => true,
                    'facture' => $facture,
                    'examen' => $examen,
                    'sumMontantEx' => $sumMontantEx,
                ]);

            } else {
                throw new Exception('Impossible de retrouver la facture');
            }
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }
    
}
