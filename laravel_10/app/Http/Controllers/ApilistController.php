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
use App\Models\chambre;
use App\Models\lit;
// use App\Models\acte;
// use App\Models\typeacte;
// use App\Models\typemedecin;
use App\Models\User;
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
use App\Models\prelevement;
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;
// use App\Models\caisse;
// use App\Models\historiquecaisse;
use App\Models\portecaisse;

class ApilistController extends Controller
{
    private function formatWithPeriods($number) 
    {
        return number_format($number, 0, '', '.');
    }








    public function list_chambre()
    {
        $chambre = DB::table('chambres')->select('chambres.*')->orderBy('chambres.created_at', 'desc')->get();

        return response()->json(['chambre' => $chambre]);
    }

    public function list_lit()
    {
        $lit = DB::table('lits')
            ->Join('chambres', 'chambres.id', '=', 'lits.chambre_id')
            ->orderBy('lits.created_at', 'desc')
            ->select('lits.*', 'chambres.prix as prix', 'chambres.code as code_ch')
            ->get();

        return response()->json(['data' => $lit]);
    }

    public function list_medecin()
    {

        $medecins = DB::table('medecin')
            ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
            ->select('medecin.*','specialitemed.codespecialitemed as specialite_id','specialitemed.nomspecialite as specialite')
            ->orderBy('medecin.dateservice', 'desc')
            ->get();

        return response()->json([
            'data' => $medecins,
        ]);
    }

    public function list_cons_day()
    {
        $today = Carbon::today();

        $consultation = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->whereDate('consultation.date', $today)
            ->where('dossierpatient.codetypedossier', '=', 'DC')
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'consultation.regle as regle',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as nom_medecin',
                'garantie.libgaran as garantie',
            )
            ->orderBy('consultation.date', 'desc')
            ->get();

        return response()->json([
            'data' => $consultation,
        ]);
    }

    public function list_typeadmission()
    {
        $typeadmission = DB::table('typehospitalsation')->select('typehospitalsation.*')->get();

        return response()->json(['typeadmission' => $typeadmission]);
    }

    public function list_natureadmission()
    {
        $natureadmission = DB::table('naturehospit')->select('naturehospit.*')->get();

        return response()->json([
            'data' => $natureadmission,
        ]);
    }

    public function list_hopital($date1, $date2, $statut)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();
        
        $hopital = DB::table('admission')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
            ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
            ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
            ->join('chambres', 'chambres.id', '=', 'admission.codechbre')
            ->join('lits', 'lits.id', '=', 'admission.idtypelit')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('dossierpatient.codetypedossier', '=', 'DH')
            ->where('admission.controle', '=', 0)
            ->whereBetween('admission.dateentree', [$date1, $date2]);

        if ($statut !== 'tous') {
            $hopital->where('admission.statut', '=', $statut);
        }

        $hopital = $hopital->select(
            'admission.*',
            'patient.nomprenomspatient as patient',
            'patient.assure as assure',
            'patient.codeassurance as codeassurance',
            'assurance.libelleassurance as assurance',
            'tauxcouvertureassure.valeurtaux as taux',
            'dossierpatient.numdossier as numdossier',
            'medecin.nomprenomsmed as medecin',
            'typehospitalsation.nomtypehospit as type_hospit',
            'naturehospit.nomnaturehospit as nature_hospit',
            'chambres.code as chambre_code',
            'lits.code as lit_code',
            'factures.montanttotal as montant_total',
            'factures.montantregle_pat as montant_regle',
            'factures.montantreste_pat as montantreste_pat',
            'factures.montant_pat as montant_pat',
        )
        ->orderBy('admission.dateentree', 'desc')
        ->get();

        foreach ($hopital as $value) {
            $value->taux = $value->taux ?? 0;

            // if ($value->montant_regle == $value->montant_pat && $value->montant_total > 0 ) {

            //     DB::beginTransaction();
            //     try {
                
            //         $updateData_payer =[
            //             'statut' => 'sortie',
            //             'updated_at' => now(),
            //         ];

            //         $payerUpdate = DB::table('admission')
            //                             ->where('numfachospit', '=', $value->numfachospit)
            //                             ->update($updateData_payer);

            //         if ($payerUpdate == 0) {
            //             throw new Exception('Erreur lors de l\'insertion dans la table lits');
            //         }

            //         $updateData_statut_lit =[
            //             'statut' => 'disponible',
            //             'updated_at' => now(),
            //         ];

            //         $statutLitUpdate = DB::table('lits')
            //                             ->where('id', '=', $value->idtypelit)
            //                             ->update($updateData_statut_lit);

            //         if ($statutLitUpdate == 0) {
            //             throw new Exception('Erreur lors de l\'insertion dans la table lits');
            //         }

            //         $lit = DB::table('lits')->where('id', '=', $value->idtypelit)->select('lits.*')->first();
            //         $chambre = DB::table('chambres')->where('id', '=', $lit->chambre_id)->select('chambres.*')->first();

            //         $nbre_lit = DB::table('lits')
            //             ->where('chambre_id', '=', $lit->chambre_id)
            //             ->where('statut', '=', 'disponible')
            //             ->count();


            //         if ($chambre->nbre_lit == $nbre_lit) {
                        
            //             $updateData_statut_chambre =[
            //                 'statut' => 'disponible',
            //                 'updated_at' => now(),
            //             ];

            //             $statutChambreUpdate = DB::table('chambres')
            //                             ->where('id', '=', $lit->chambre_id)
            //                             ->update($updateData_statut_chambre);

            //             if ($statutChambreUpdate == 0) {
            //                 throw new Exception('Erreur lors de l\'insertion dans la table chambres');
            //             }
            //         }
            //         // Valider la transaction
            //         DB::commit();

            //     } catch (Exception $e) {
            //         DB::rollback();
            //     }
            // }
            
        }

        return response()->json([
            'data' => $hopital,
        ]);
    }

    public function detail_hos($numhospit)
    {
        $hopital = DB::table('admission')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'admission.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
            ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
            ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
            ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
            ->join('chambres', 'chambres.id', '=', 'admission.codechbre')
            ->join('lits', 'lits.id', '=', 'admission.idtypelit')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('dossierpatient.codetypedossier', '=', 'DH')
            ->where('admission.numhospit', '=', $numhospit)
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
            ->where('facturation_hospit.numpchr', '=', $numhospit)
            ->select(
                'garanties_hospit.libelle as name',
                'facturation_hospit.pu as prix',
                'facturation_hospit.montaccorde as prix_ass',
                'facturation_hospit.montrefus as prix_pat',
            )
            ->get();
        
        return response()->json([
            'hopital' => $hopital,
            'prestation' => $prestation,
        ]);
    }

    public function detail_hos_recu($numhospit)
    {
        $hopital = DB::table('admission')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'admission.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
            ->join('specialitemed', 'specialitemed.codespecialitemed', '=', 'medecin.codespecialitemed')
            ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
            ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
            ->join('chambres', 'chambres.id', '=', 'admission.codechbre')
            ->join('lits', 'lits.id', '=', 'admission.idtypelit')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('dossierpatient.codetypedossier', '=', 'DH')
            ->where('admission.numhospit', '=', $numhospit)
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
            )
            ->first();

        $hopital->taux = $hopital->taux ?? 0;

        $prestation = DB::table('facturation_hospit')
            ->join('garanties_hospit', 'garanties_hospit.id', '=', 'facturation_hospit.idgarhospit')
            ->where('facturation_hospit.numpchr', '=', $numhospit)
            ->select(
                'garanties_hospit.libelle as name',
                'facturation_hospit.pu as prix',
                'facturation_hospit.montaccorde as prix_ass',
                'facturation_hospit.montrefus as prix_pat',
            )
            ->get();

        return response()->json([
            'hopital' => $hopital,
            'prestation' => $prestation,
        ]);
    }

    public function list_produit()
    {
        // $add = DB::table('medicine')->select('medicine.*')->get();

        // foreach ($add as $value) {
        //     if ($value->status == null || $value->status == '' ) {
        //         $updateData_soins =[
        //             'status' => 0,
        //             'updated_at' => now(),
        //         ];

        //         $soinsUpdate = DB::table('medicine')
        //                             ->where('medicine_id', '=', $value->medicine_id)
        //                             ->update($updateData_soins);
        //     }
        // }

        $produits = DB::table('medicine')
            ->Join('medicine_category', 'medicine_category.medicine_category_id', '=', 'medicine.medicine_category_id')
            ->select('medicine.*', 'medicine_category.name as categorie')
            ->get();

        if ($produits->isNotEmpty()) {
            // Cela évite la boucle et met à jour toutes les lignes en une seule requête SQL.
            DB::table('medicine')
                ->whereIn('medicine_id', $produits->where('status', '<', 0)->pluck('medicine_id'))
                ->update(['status' => 0, 'updated_at' => now()]);
        }

        return response()->json([
            'data' => $produits,
        ]);
    }

    // public function list_produit_all()
    // {
    //     $produit = produit::orderBy('nom', 'asc')->get();

    //     return response()->json(['produit' => $produit]);
    // }

    // public function list_patient_all($date1, $date2, $statut)
    public function list_patient_all($statut)
    {
        // $date1 = Carbon::parse($date1)->startOfDay();
        // $date2 = Carbon::parse($date2)->endOfDay();

        $patients = DB::table('patient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation');
            // ->whereBetween('patient.dateenregistrement', [$date1, $date2]);

        if ($statut !== 'tous') {
            $patients->where('patient.assure', '=', $statut);
        }

        $patients = $patients->select(
            'patient.*', 
            'societeassure.nomsocieteassure as societe',
            'assurance.libelleassurance as assurance',
            'tauxcouvertureassure.valeurtaux as taux',
            'filiation.libellefiliation as filiation',
        )
        ->orderBy('patient.dateenregistrement','desc')
        ->get();

        foreach ($patients as $value) {

            $dossierC = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->where('codetypedossier','=', 'DC')
                ->select(
                    'numdossier'
                )
                ->first();

            $dossierH = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->where('codetypedossier','=', 'DH')
                ->select(
                    'numdossier'
                )
                ->first();

            $nbre_cons = DB::table('consultation')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->count() ?? 0;

            $nbre_exam = DB::table('testlaboimagerie')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->count() ?? 0;

            $nbre_hospit = DB::table('admission')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->count() ?? 0;

            $nbre_soins = DB::table('soins_medicaux')
                ->where('idenregistremetpatient', '=', $value->idenregistremetpatient)
                ->count() ?? 0;

            $value->nbre_acte = $nbre_exam + $nbre_cons + $nbre_hospit + $nbre_soins;

            $value->numdossierC = $dossierC->numdossier ?? null;
            $value->numdossierH = $dossierH->numdossier ?? null;

        }

        return response()->json([
            'data' => $patients,
        ]);
    }

    public function list_cons_all($date1,$date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $consultation = DB::table('consultation')
            ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->where('dossierpatient.codetypedossier', '=', 'DC')
            ->whereBetween('consultation.date', [$date1, $date2])
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'consultation.regle as regle',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as nom_medecin',
                'garantie.libgaran as garantie',
                'factures.montantregle_pat as montant_regle',
            )
            ->orderBy('consultation.date', 'desc')
            ->get();

        return response()->json([
            'data' => $consultation,
        ]);
    }

    public function list_typesoins()
    {
        $typesoins = DB::table('typesoinsinfirmiers')->select('typesoinsinfirmiers.*')->get();

        return response()->json(['typesoins' => $typesoins]);
    }

    public function list_soinsIn()
    {
        $soinsinQuery = DB::table('soins_infirmier')
            ->Join('typesoinsinfirmiers', 'typesoinsinfirmiers.code_typesoins', '=', 'soins_infirmier.code_typesoins')
            ->select('soins_infirmier.*', 'typesoinsinfirmiers.libelle_typesoins as typesoins')
            ->get();

        return response()->json([
            'data' => $soinsinQuery,
        ]);
    }

    public function list_soinsam_all($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $spatient = DB::table('soins_medicaux')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
            ->join('factures', 'soins_medicaux.numfac_soins', '=', 'factures.numfac')
            ->whereBetween('soins_medicaux.date_soin', [$date1, $date2])
            // ->where(function ($query) {
            //     $query->whereNull('soins_medicaux.numhospit')
            //           ->orWhere('soins_medicaux.numhospit', '=', '');
            // })
            ->select(
                'soins_medicaux.*', 
                'patient.nomprenomspatient as patient',
                'factures.montantregle_pat as montant_regle',
            )
            ->orderBy('soins_medicaux.date_soin', 'desc')
            ->get();

        foreach ($spatient as $value) {
            $value->nbre_soins = DB::table('soins_medicaux_itemsoins')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;

            $value->nbre_produit = DB::table('soins_medicaux_itemmedics')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;
        }

        return response()->json([
            'data' => $spatient,
        ]);
    }

    public function detail_soinam($id)
    {
            
        $produittotal = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', '=', $id)
            ->select(DB::raw('COALESCE(SUM(qte * REPLACE(price, ".", "") + 0), 0) as total'))
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
            ->where('id_soins', '=', $id)
            ->select('soins_medicaux_itemmedics.*')
            ->get();

        $patient = DB::table('soins_medicaux')
            ->Join('factures', 'soins_medicaux.numfac_soins', '=', 'factures.numfac')
            ->Join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('societeassure', 'soins_medicaux.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->where('soins_medicaux.id_soins', '=', $id)
            ->select(
                'soins_medicaux.*',
                'dossierpatient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
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

        if (!$patient) {

            return response()->json([
                'existep' => true,
            ]);
        }

        $patient->nbre_soins = DB::table('soins_medicaux_itemsoins')
            ->where('id_soins', '=', $id)->count() ?: 0;

        $patient->nbre_produit = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', '=', $id)->count() ?: 0;

        $patient->prototal = $produittotal->total ?? 0;
        $patient->stotal = $soinstotal->total ?? 0;

        return response()->json([
            'patient' =>$patient,
            'soins' => $soins,
            'produit' => $produit,
        ]);
    }

    public function list_societe_all()
    {
        $societe = DB::table('societeassure')
            ->join('assurance', 'assurance.codeassurance', '=', 'societeassure.codeassurance')
            ->join('assureur', 'assureur.codeassureur', '=', 'societeassure.codeassureur')
            ->select(
                'societeassure.*',
                'assurance.codeassurance as codeassurance',
                'assurance.libelleassurance as assurance',
                'assureur.codeassureur as codeassureur',
                'assureur.libelle_assureur as assureur',
            )
            ->get();

        return response()->json([
            'data' => $societe,
        ]);
    }

    public function list_examen_all()
    {
        $examen = DB::table('examen')
            ->leftJoin('famille_examen', 'examen.codfamexam', '=', 'famille_examen.codfamexam')
            ->select(
                'examen.*',
                'famille_examen.nomfamexam as type',
            )
            ->orderBy('denomination', 'asc')
            ->get();

        return response()->json([
            'data' => $examen,
        ]);
    }

    public function list_examend_all($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $examen = DB::table('testlaboimagerie')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'testlaboimagerie.idenregistremetpatient')
            ->leftjoin('medecin', 'testlaboimagerie.codemedecin', '=', 'medecin.codemedecin')
            ->leftjoin('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
            ->whereBetween('testlaboimagerie.date', [$date1, $date2])
            // ->where(function ($query) {
            //     $query->whereNull('testlaboimagerie.numhospit')
            //           ->orWhere('testlaboimagerie.numhospit', '=', '');
            // })
            ->select(
                'testlaboimagerie.*',
                'patient.nomprenomspatient as patient',
                'patient.assure as assure',
                'medecin.nomprenomsmed as medecin',
                'factures.montanttotal as montant',
                'factures.montantregle_pat as montant_regle',
            )
            ->orderBy('testlaboimagerie.date', 'desc')
            ->get();

        foreach ($examen as $value) {

            $nbre = DB::table('detailtestlaboimagerie')
                    ->where('idtestlaboimagerie', '=', $value->idtestlaboimagerie)
                    ->count();

            $value->nbre =  $nbre ?? 0;
        }

        return response()->json([
            'data' => $examen,
        ]);
    }

    public function detail_examen($id)
    {

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
                'factures.montantreste_pat as reste_patient',
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

        return response()->json([
            'facture' => $facture,
            'examen' => $examen,
            'sumMontantEx' => $sumMontantEx,
        ]);
    }

    public function select_jours()
    {
        $jour = joursemaine::all();
        
        return response()->json([
            'jour' => $jour,
        ]);
    }

    public function list_horaire($medecin, $specialite, $jour, $periode)
    {
        $query = DB::table('medecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed');

        if ($medecin !== 'tout') {
            $query->where('medecin.codemedecin', '=', $medecin);
        }

        if ($specialite !== 'tout') {
            $query->where('specialitemed.codespecialitemed', '=', $specialite);
        }

        $medecins = $query->select('medecin.*', 'specialitemed.libellespecialite as specialité')->get();

        foreach ($medecins as $value) {
            $horairesQuery = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui');

            // Filtrage par jour
            if ($jour !== 'tout') {
                $horairesQuery->where('joursemaines.id', '=', $jour);
            }

            // Filtrage par période (Matin/Soir)
            if ($periode !== 'tout') {
                $horairesQuery->where('programmemedecins.periode', '=', $periode);
            }

            $horaires = $horairesQuery->select('programmemedecins.*', 'joursemaines.jour as jour')->get();
            $value->horaires = $horaires;
        }

        return response()->json([
            'medecins' => $medecins,
        ]);
    }

    public function list_rdv()
    {
        $rdv = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'patient.telpatient as patient_tel',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc')
            ->get();

        foreach ($rdv as $value) {
            $horaires = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui')
                ->select('programmemedecins.*', 'joursemaines.jour as jour')
                ->get();

            $value->horaires = $horaires;
        }

        return response()->json([
            'data' => $rdv,
        ]);
    }

    public function list_rdv_day()
    {
        $today = Carbon::today();

        $rdv = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->whereDate('rdvpatients.date', '=', $today)
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'patient.telpatient as patient_tel',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc')
            ->get();

        foreach ($rdv as $value) {
            $horaires = DB::table('programmemedecins')
                ->join('joursemaines', 'joursemaines.id', '=', 'programmemedecins.jour_id')
                ->where('programmemedecins.codemedecin', '=', $value->codemedecin)
                ->where('programmemedecins.statut', '=', 'oui')
                ->select('programmemedecins.*', 'joursemaines.jour as jour')
                ->get();

            $value->horaires = $horaires;
        }

        return response()->json([
            'data' => $rdv,
        ]);

    }

    public function list_specialite()
    {

        $specialite = DB::table('specialitemed')
            ->select('codespecialitemed','nomspecialite', 'abrspecialite')
            ->get();

        return response()->json([
            'data' => $specialite,
        ]);
    }

    public function list_depotfacture(Request $request)
    {
        $depot = DB::table('depotfactures')
            ->join('assurance', 'assurance.codeassurance', '=', 'depotfactures.assurance_id')
            ->select(
                'depotfactures.*',
                'assurance.libelleassurance as assurance',
            )
            ->get();

        return response()->json([
            'data' => $depot,
        ]);
    }

    public function list_cons_patient($id)
    {
        $consultationQuery = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->where('consultation.idenregistremetpatient', '=', $id)
            ->select(
                'consultation.idconsexterne as idconsexterne',
                'consultation.montant as montant',
                'consultation.date as date',
                'consultation.numfac as numfac',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite',
            )
            ->orderBy('consultation.date', 'desc');

        $consultation = $consultationQuery->paginate(15);

        return response()->json([
            'consultation' => $consultation->items(), // Paginated data
            'pagination' => [
                'current_page' => $consultation->currentPage(),
                'last_page' => $consultation->lastPage(),
                'per_page' => $consultation->perPage(),
                'total' => $consultation->total(),
            ]
        ]);
    }

    public function list_examend_patient($id)
    {
        $examenQuery = DB::table('testlaboimagerie')
            ->join('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
            ->where('testlaboimagerie.idenregistremetpatient', '=', $id)
            ->select(
                'testlaboimagerie.idtestlaboimagerie as idtestlaboimagerie',
                'testlaboimagerie.typedemande as typedemande',
                'testlaboimagerie.date as date',
                'factures.montant_pat as montant',
            )
            ->orderBy('testlaboimagerie.date', 'desc');

        $examen = $examenQuery->paginate(15);

        foreach ($examen->items() as $value) {
            $nbre = DB::table('detailtestlaboimagerie')
                ->where('idtestlaboimagerie', '=', $value->idtestlaboimagerie)
                ->count();
            $value->nbre =  $nbre ?? 0;
        }

        return response()->json([
            'examen' => $examen->items(),
            'pagination' => [
                'current_page' => $examen->currentPage(),
                'last_page' => $examen->lastPage(),
                'per_page' => $examen->perPage(),
                'total' => $examen->total(),
            ]
        ]);
    }

    public function list_hopital_patient($id)
    {

        $hopitalQuery = DB::table('admission')
            ->join('medecin', 'medecin.codemedecin', '=', 'admission.codemedecin')
            ->join('typehospitalsation', 'typehospitalsation.idtypehospit', '=', 'admission.codetypehospit')
            ->join('naturehospit', 'naturehospit.idnathospit', '=', 'admission.codenaturehospit')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('admission.idenregistremetpatient', '=', $id)
            ->select(
                'admission.numhospit as numhospit',
                'admission.dateentree as dateentree',
                'admission.datesortie as datesortie',
                'admission.statut as statut',
                'admission.created_at as created_at',
                'medecin.nomprenomsmed as medecin',
                'typehospitalsation.nomtypehospit as type_hospit',
                'naturehospit.nomnaturehospit as nature_hospit',
                'factures.montanttotal as montant',
            )
            ->orderBy('admission.created_at', 'desc');

        $hopital = $hopitalQuery->paginate(15);

        return response()->json([
            'hopital' => $hopital->items(), // Paginated data
            'pagination' => [
                'current_page' => $hopital->currentPage(),
                'last_page' => $hopital->lastPage(),
                'per_page' => $hopital->perPage(),
                'total' => $hopital->total(),
            ]
        ]);
    }

    public function list_soinsam_patient($id)
    {

        $spatientQuery = DB::table('soins_medicaux')
            ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
            ->where('soins_medicaux.idenregistremetpatient', '=', $id)
            ->select(
                'soins_medicaux.id_soins as id_soins',
                'soins_medicaux.date_soin as date_soin',
                'factures.montanttotal as montant',
            )
            ->orderBy('soins_medicaux.date_soin', 'desc');

        $spatient = $spatientQuery->paginate(15);

        foreach ($spatient->items() as $value) {
            $value->nbre_soins = DB::table('soins_medicaux_itemsoins')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;

            $value->nbre_produit = DB::table('soins_medicaux_itemmedics')
                ->where('id_soins', '=', $value->id_soins)->count() ?: 0;
        }

        return response()->json([
            'spatient' => $spatient->items(), // Paginated data
            'pagination' => [
                'current_page' => $spatient->currentPage(),
                'last_page' => $spatient->lastPage(),
                'per_page' => $spatient->perPage(),
                'total' => $spatient->total(),
            ]
        ]);
    }

    public function list_assureur_all()
    {
        $assureur = DB::table('assureur')->get();

        return response()->json([
            'data' => $assureur,
        ]);
    }

    public function list_assurance_all()
    {
        $assurance = DB::table('assurance')->where('codeassurance', '!=', 'NONAS')->get();

        return response()->json([
            'data' => $assurance,
        ]);
    }

    public function trace_operation($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $trace = DB::table('caisse')
            ->whereBetween('datecreat', [$date1, $date2])
            ->orderBy('datecreat', 'desc')
            ->get();

        $caisse = DB::table('caisse')
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN caisse.type = "entree" THEN REPLACE(caisse.montant, ".", "") + 0 ELSE 0 END), 0) as entrer,
                COALESCE(SUM(CASE WHEN caisse.type = "sortie" THEN REPLACE(caisse.montant, ".", "") + 0 ELSE 0 END), 0) as sortie,
                COALESCE(
                    SUM(CASE WHEN caisse.type = "entree" THEN REPLACE(caisse.montant, ".", "") + 0 ELSE 0 END) -
                    SUM(CASE WHEN caisse.type = "sortie" THEN REPLACE(caisse.montant, ".", "") + 0 ELSE 0 END), 
                    0
                ) as total
            '))
            ->whereBetween('caisse.datecreat', [$date1, $date2])
            ->first();

        return response()->json([
            'data' => $trace,
            'montant' => $caisse,
        ]);
    }

    public function list_user(Request $request)
    {
        $users = DB::table('employes')
            ->leftjoin('users', 'users.code_personnel', '=', 'employes.matricule')
            ->join('contrat', 'contrat.code', '=', 'employes.typecontrat')
            ->select('employes.*','contrat.libelle as contrat','users.user_profil_id as user_profil_id','users.login as login')
            ->orderBy('employes.dateenregistre', 'desc')
            ->get();

        foreach ($users as $value) {
            $verf = DB::table('profile')->where('idprofile', '=', $value->user_profil_id)->first();

            if ($verf) {
                $value->profil = $verf->libprofile;
                $value->profil_id = $verf->idprofile;
            } else {
                $value->profil = Null;
                $value->profil_id = Null;
            }
        }

        return response()->json([
            'data' => $users,
        ]);
    }

    public function list_user_login(Request $request)
    {
        $users = DB::table('users')
            ->select('users.*')
            ->get();

        foreach ($users as $value) {
            $verf = DB::table('profile')->where('idprofile', '=', $value->user_profil_id)->first();

            if ($verf) {
                $value->profil = $verf->libprofile;
                $value->profil_id = $verf->idprofile;
            } else {
                $value->profil = Null;
                $value->profil_id = Null;
            }
        }

        return response()->json([
            'data' => $users,
        ]);
    }

    public function list_rdv_two_days()
    {
        $twoDaysLater = Carbon::today()->addDays(2);

        // $today = Carbon::today();
        // $twoDaysLater = Carbon::today()->addDays(2);

        $rdvQuery = DB::table('rdvpatients')
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'rdvpatients.patient_id')
            ->Join('medecin', 'medecin.codemedecin', '=', 'rdvpatients.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->whereDate('rdvpatients.date', '=', $twoDaysLater)
            ->select(
                'rdvpatients.*', 
                'patient.nomprenomspatient as patient',
                'medecin.nomprenomsmed as medecin',
                'specialitemed.nomspecialite as specialite'
            )
            ->orderBy('rdvpatients.created_at', 'desc');

        $rdv = $rdvQuery->paginate(15);

        return response()->json([
            'rdv' => $rdv->items(),
            'pagination' => [
                'current_page' => $rdv->currentPage(),
                'last_page' => $rdv->lastPage(),
                'per_page' => $rdv->perPage(),
                'total' => $rdv->total(),
            ]
        ]);
    }

    public function trace_ouvert_fermer($date1, $date2)
    {
        $date1 = Carbon::parse($date1)->startOfDay();
        $date2 = Carbon::parse($date2)->endOfDay();

        $trace = DB::table('caisse_resume')
            ->whereBetween('datecaisse', [$date1, $date2])
            ->orderBy('datecaisse', 'desc')
            ->get();

        return response()->json([
            'data' => $trace,
        ]);
    }

    public function list_type_garantie()
    {
        $type = DB::table('typgarantie')
            ->select('typgarantie.*')
            ->get();

        return response()->json([
            'data' => $type,
        ]);
    }

    public function list_garantie()
    {
        $garantie = DB::table('garantie')
            ->leftJoin('typgarantie', 'garantie.codtypgar', '=', 'typgarantie.codtypgar')
            ->select('garantie.*','typgarantie.libtypgar as type_garantie')
            ->get();

        return response()->json([
            'data' => $garantie,
        ]);
    }

    public function list_tarif(Request $request)
    {
        $tarifs = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->join('assurance', 'tarifs.codeassurance', '=', 'assurance.codeassurance')
            ->select(
                'tarifs.*',
                'garantie.libgaran as garantie',
                'assurance.libelleassurance as asurance',
            )
            ->orderBy('garantie.codgaran', 'asc')
            ->get();

        return response()->json([
            'data' => $tarifs,
        ]);
    }

}
