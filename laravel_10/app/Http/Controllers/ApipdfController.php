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
// use App\Models\user;
// use App\Models\role;
// use App\Models\typemedecin;
// use App\Models\consultation;
// use App\Models\detailconsultation;
// use App\Models\facture;
// use App\Models\typeadmission;
// use App\Models\natureadmission;
// use App\Models\detailhopital;
// use App\Models\produit;
// use App\Models\soinshopital;
// use App\Models\typesoins;
// use App\Models\soinsinfirmier;
// use App\Models\soinspatient;
// use App\Models\sp_produit;
// use App\Models\sp_soins;
// use App\Models\examenpatient;
// use App\Models\examen;
// use App\Models\prelevement;
// use App\Models\joursemaine;
// use App\Models\rdvpatient;
// use App\Models\programmemedecin;
// use App\Models\depotfacture;
// use App\Models\caisse;
// use App\Models\historiquecaisse;

class ApipdfController extends Controller
{
    public function fiche_consultation($code)
    {
        $facture = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'consultation.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->leftJoin('societeassure', 'consultation.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'consultation.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
            ->where('consultation.idconsexterne', '=', $code)
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

        return response()->json(['facture' => $facture]);
    }

    // public function facture_inpayer_cons($id)
    // {
    //     $consultation = consultation::join('detailconsultations', 'detailconsultations.consultation_id', '=', 'consultations.id')
    //     ->join('factures', 'factures.id', '=', 'consultations.facture_id')
    //     ->where('consultations.id', '=', $id)
    //     ->select(
    //         'consultations.*',
    //         'detailconsultations.typeacte_id as typeacte_id',
    //         'detailconsultations.part_assurance as part_assurance',
    //         'detailconsultations.part_patient as part_patient',
    //         'detailconsultations.remise as remise',
    //         'factures.code as code_fac',
    //         'factures.date_payer as date_paye',
    //         'factures.montant_verser as montant_verser',
    //         'factures.montant_remis as montant_remis',
    //         'factures.statut as statut_fac',
    //         'factures.date_payer as date_payer',
    //     )
    //     ->first();

    //     $total_amount = intval(str_replace('.', '', $consultation->montant_verser));
    //     $paid_amount = intval(str_replace('.', '', $consultation->part_patient));
    //     $remis_amount = intval(str_replace('.', '', $consultation->montant_remis));
    //     // Calculate the remaining amount
    //     $remaining_amount = $total_amount - ($paid_amount + $remis_amount);
    //     // Format the remaining amount with periods and assign to 'montant_restant'
    //     $consultation->montant_restant = $this->formatWithPeriods($remaining_amount);

    //     $patient = patient::leftjoin('assurances', 'assurances.id', '=', 'patients.assurance_id')->leftjoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
    //     ->where('patients.id', '=', $consultation->patient_id)
    //     ->select('patients.*', 'assurances.nom as assurance', 'tauxes.taux as taux')
    //     ->first();

    //     if ($patient) {
    //         $patient->age = Carbon::parse($patient->datenais)->age;
    //     }

    //     $user = user::find($consultation->user_id);

    //     $typeacte = typeacte::find($consultation->typeacte_id);

    //     return response()->json(['patient' => $patient, 'typeacte' => $typeacte, 'user' => $user, 'consultation' => $consultation]);
    // }

    private function formatWithPeriods($number) {
        return number_format($number, 0, '', '.');
    }

    public function imp_fac_assurance(Request $request)
    {
        // $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        // $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay(); 

        $periode = $request->periode;
        list($year, $month) = explode('-', $periode);

        $assurance = DB::table('assurance')
            ->where('codeassurance', '=', $request->assurance_id)
            ->select('assurance.*')
            ->first();

        $societes = DB::table('societeassure')
            ->where('codeassurance', '=', $request->assurance_id)
            ->select('societeassure.*')
            ->get();

        $result = [];

        foreach ($societes as $societe) {

            $codesocieteassure = $societe->codesocieteassure;

            $fac_cons = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'consultation.numfac')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('consultation.numbon')
                ->where('consultation.numbon', '!=', '')
                ->whereYear('consultation.date', $year)
                ->whereMonth('consultation.date', $month)
                ->where('consultation.codesocieteassure', $codesocieteassure)
                ->select(
                    'consultation.numbon as num_bon',
                    'consultation.date as created_at',
                    'patient.nomprenomspatient as patient',
                    'patient.codesocieteassure as codesocieteassure',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_exam = DB::table('testlaboimagerie')
                ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'testlaboimagerie.numfacbul')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('testlaboimagerie.numbon')
                ->where('testlaboimagerie.numbon', '!=', '')
                ->whereYear('testlaboimagerie.date', $year)
                ->whereMonth('testlaboimagerie.date', $month)
                ->where('testlaboimagerie.codesocieteassure', $codesocieteassure)
                ->select(
                    'testlaboimagerie.numbon as num_bon',
                    'testlaboimagerie.date as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_soinsam = DB::table('soins_medicaux')
                ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'soins_medicaux.numfac_soins')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('soins_medicaux.numbon')
                ->where('soins_medicaux.numbon', '!=', '')
                ->whereYear('soins_medicaux.date_soin', $year)
                ->whereMonth('soins_medicaux.date_soin', $month)
                ->where('soins_medicaux.codesocieteassure', $codesocieteassure)
                ->select(
                    'soins_medicaux.numbon as num_bon',
                    'soins_medicaux.date_soin as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_hopital = DB::table('admission')
                ->join('patient', 'admission.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'admission.numfachospit')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('admission.numbon')
                ->where('admission.numbon', '!=', '')
                ->whereYear('admission.created_at', $year)
                ->whereMonth('admission.created_at', $month)
                ->where('admission.codesocieteassure', $codesocieteassure)
                ->select(
                    'admission.numbon as num_bon',
                    'admission.created_at as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;
                $result[] = $societe;
            }

        }


        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function imp_fac_assurance_bordo(Request $request)
    {

        // $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        // $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay(); 

        $periode = $request->periode;
        list($year, $month) = explode('-', $periode);

        $assurance = DB::table('assurance')
            ->where('codeassurance', '=', $request->assurance_id)
            ->select('assurance.*')
            ->first();

        $societes = DB::table('societeassure')
            ->where('codeassurance', '=', $request->assurance_id)
            ->select('societeassure.*')
            ->get();

        $result = [];

        foreach ($societes as $key => $societe) {
            $codesocieteassure = $societe->codesocieteassure;

            $total_patient = 0;
            $total_assurance = 0;
            $total_montant = 0;

            $fac_cons = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'consultation.numfac')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('consultation.numbon')
                ->where('consultation.numbon', '!=', '')
                ->whereYear('consultation.date', $year)
                ->whereMonth('consultation.date', $month)
                ->where('consultation.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_exam = DB::table('testlaboimagerie')
                ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'testlaboimagerie.numfacbul')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('testlaboimagerie.numbon')
                ->where('testlaboimagerie.numbon', '!=', '')
                ->whereYear('testlaboimagerie.date', $year)
                ->whereMonth('testlaboimagerie.date', $month)
                ->where('testlaboimagerie.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_soinsam = DB::table('soins_medicaux')
                ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'soins_medicaux.numfac_soins')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('soins_medicaux.numbon')
                ->where('soins_medicaux.numbon', '!=', '')
                ->whereYear('soins_medicaux.date_soin', $year)
                ->whereMonth('soins_medicaux.date_soin', $month)
                ->where('soins_medicaux.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_hopital = DB::table('admission')
                ->join('patient', 'admission.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'admission.numfachospit')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('admission.numbon')
                ->where('admission.numbon', '!=', '')
                ->whereYear('admission.created_at', $year)
                ->whereMonth('admission.created_at', $month)
                ->where('admission.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            // Si la société a des données à afficher, on les ajoute dans le résultat
            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {

                $societe->total_patient = $total_patient;
                $societe->total_assurance = $total_assurance;
                $societe->total_montant = $total_montant;

                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function imp_fac_depot($id)
    {

        $fac = DB::table('depotfactures')
            ->where('id', '=', $id)
            ->select('depotfactures.*')
            ->first();

        $month = $fac->periode_mois;
        $year = $fac->periode_annee; 

        $assurance = DB::table('assurance')
            ->where('codeassurance', '=', $fac->assurance_id)
            ->select('assurance.*')
            ->first();

        $societes = DB::table('societeassure')
            ->where('codeassurance', '=', $fac->assurance_id)
            ->select('societeassure.*')
            ->get();

        $result = [];

        foreach ($societes as $societe) {
            $codesocieteassure = $societe->codesocieteassure;

            $fac_cons = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'consultation.numfac')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('consultation.numbon')
                ->where('consultation.numbon', '!=', '')
                ->whereYear('consultation.date', $year)
                ->whereMonth('consultation.date', $month)
                ->where('consultation.codesocieteassure', $codesocieteassure)
                ->select(
                    'consultation.numbon as num_bon',
                    'consultation.date as created_at',
                    'patient.nomprenomspatient as patient',
                    'patient.codesocieteassure as codesocieteassure',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_exam = DB::table('testlaboimagerie')
                ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'testlaboimagerie.numfacbul')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('testlaboimagerie.numbon')
                ->where('testlaboimagerie.numbon', '!=', '')
                ->whereYear('testlaboimagerie.date', $year)
                ->whereMonth('testlaboimagerie.date', $month)
                ->where('testlaboimagerie.codesocieteassure', $codesocieteassure)
                ->select(
                    'testlaboimagerie.numbon as num_bon',
                    'testlaboimagerie.date as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_soinsam = DB::table('soins_medicaux')
                ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'soins_medicaux.numfac_soins')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('soins_medicaux.numbon')
                ->where('soins_medicaux.numbon', '!=', '')
                ->whereYear('soins_medicaux.date_soin', $year)
                ->whereMonth('soins_medicaux.date_soin', $month)
                ->where('soins_medicaux.codesocieteassure', $codesocieteassure)
                ->select(
                    'soins_medicaux.numbon as num_bon',
                    'soins_medicaux.date_soin as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            $fac_hopital = DB::table('admission')
                ->join('patient', 'admission.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'admission.numfachospit')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('admission.numbon')
                ->where('admission.numbon', '!=', '')
                ->whereYear('admission.created_at', $year)
                ->whereMonth('admission.created_at', $month)
                ->where('admission.codesocieteassure', $codesocieteassure)
                ->select(
                    'admission.numbon as num_bon',
                    'admission.created_at as created_at',
                    'patient.nomprenomspatient as patient',
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {
                $societe->fac_cons = $fac_cons;
                $societe->fac_exam = $fac_exam;
                $societe->fac_soinsam = $fac_soinsam;
                $societe->fac_hopital = $fac_hopital;
                $result[] = $societe;
            }

        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function imp_fac_depot_bordo($id)
    {

        $fac = DB::table('depotfactures')
            ->where('id', '=', $id)
            ->select('depotfactures.*')
            ->first();

        $month = $fac->periode_mois;
        $year = $fac->periode_annee; 

        $assurance = DB::table('assurance')
            ->where('codeassurance', '=', $fac->assurance_id)
            ->select('assurance.*')
            ->first();

        $societes = DB::table('societeassure')
            ->where('codeassurance', '=', $fac->assurance_id)
            ->select('societeassure.*')
            ->get();

        $result = [];

        foreach ($societes as $key => $societe) {
            $codesocieteassure = $societe->codesocieteassure;

            $total_patient = 0;
            $total_assurance = 0;
            $total_montant = 0;

            $fac_cons = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'consultation.numfac')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('consultation.numbon')
                ->where('consultation.numbon', '!=', '')
                ->whereYear('consultation.date', $year)
                ->whereMonth('consultation.date', $month)
                ->where('consultation.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_exam = DB::table('testlaboimagerie')
                ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'testlaboimagerie.numfacbul')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('testlaboimagerie.numbon')
                ->where('testlaboimagerie.numbon', '!=', '')
                ->whereYear('testlaboimagerie.date', $year)
                ->whereMonth('testlaboimagerie.date', $month)
                ->where('testlaboimagerie.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_soinsam = DB::table('soins_medicaux')
                ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'soins_medicaux.numfac_soins')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('soins_medicaux.numbon')
                ->where('soins_medicaux.numbon', '!=', '')
                ->whereYear('soins_medicaux.date_soin', $year)
                ->whereMonth('soins_medicaux.date_soin', $month)
                ->where('soins_medicaux.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_hopital = DB::table('admission')
                ->join('patient', 'admission.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'admission.numfachospit')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('admission.numbon')
                ->where('admission.numbon', '!=', '')
                ->whereYear('admission.created_at', $year)
                ->whereMonth('admission.created_at', $month)
                ->where('admission.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            // Si la société a des données à afficher, on les ajoute dans le résultat
            if ($fac_cons->isNotEmpty() || $fac_exam->isNotEmpty() || $fac_soinsam->isNotEmpty() || $fac_hopital->isNotEmpty()) {

                $societe->total_patient = $total_patient;
                $societe->total_assurance = $total_assurance;
                $societe->total_montant = $total_montant;

                $result[] = $societe;
            }
        }

        return response()->json([
            'societes' => $result,
            'assurance' => $assurance,
            'fac' => $fac,
            'month' => $month,
            'year' => $year,
        ]);
    }

    // -------------------------------------------------------------------------

    public function etat_fac_assurance(Request $request)
    {

        $periode = $request->periode;

        list($annee, $mois) = explode('-', $periode);

        $year = $annee;
        $month = $mois;

        // Première date du mois
        $date1 = Carbon::createFromDate($annee, $mois, 1)->format('Y-m-d');

        // Dernière date du mois
        $date2 = Carbon::createFromDate($annee, $mois, 1)->endOfMonth()->format('Y-m-d');

        if ($request->assurance_id != 'tous' && $request->type != 'tous') {

            $depotResult = DB::table('depotfactures')
                ->where('periode_annee', '=', $year)
                ->where('periode_mois', '=', $month);

            if ($request->assurance_id != 'tous') {
                $depotResult->where('assurance_id', '=', $request->assurance_id);
            }

            if ($request->type == 'fac_deposer') {
                $depotResult->where(function($query) {
                    $query->where('statut', '=', 0)->orWhere('statut', '=', 1);
                });
            } else if ($request->type == 'fac_deposer_regler') {
                $depotResult->where('statut', '=', 1); 
            } else if ($request->type == 'fac_deposer_non_regler') {
                $depotResult->where('statut', '=', 0);
            }

            $depotResult = $depotResult->exists();

            if (!$depotResult) {

                return response()->json([
                    'facture_non_trouve' => true,
                ]); 
            }
        }

        if ($request->assurance_id == 'tous') {
            $assurance = null;
        } else {
            $assurance = DB::table('assurance')
                ->where('codeassurance', '=', $request->assurance_id)
                ->select('assurance.*')
                ->first();
        }

        if ($request->societe_id == 'tous') {
            $societes = DB::table('societeassure')->select('societeassure.*')->get();
        } else {
            $societes = collect([DB::table('societeassure')
                ->where('codesocieteassure', '=', $request->societe_id)
                ->select('societeassure.*')
                ->first()]);
        }

        $m_total = 0;
        $m_assurance = 0;
        $m_patient = 0;

        $tables = ['consultation', 'admission', 'testlaboimagerie', 'soins_medicaux'];

        foreach ($societes as $sot) {
            $societe = $sot->codesocieteassure;

            foreach ($tables as $table) {
                $data = $this->getFacturesByType_Etat_Facture($table, $societe, $date1, $date2, $request->assurance_id);

                if ($data->isNotEmpty()) {
                    $name_columns = [
                        'consultation' => 'Consultation',
                        'admission' => 'Hospitalisation',
                        'testlaboimagerie' => 'Examen',
                        'soins_medicaux' => 'Soins Ambulatoire',
                    ];

                    $name = $name_columns[$table] ?? 'Acte';

                    foreach ($data as $value) {
                        $value->acte = $name;

                        if (property_exists($value, 'montant')) {
                            $m_total += $value->montant;
                        }

                        if (property_exists($value, 'part_assurance')) {
                            $m_assurance += $value->part_assurance;
                        }

                        if (property_exists($value, 'part_patient')) {
                            $m_patient += $value->part_patient;
                        }
                    }

                    $sot->$table = $data;
                }
            }
        }

        $result = $societes;

        return response()->json([
            'success' => true,
            'societes' => $result,
            'assurance' => $assurance ?? null,
            'month' => $month,
            'year' => $year,
            'type' => $request->type,
            'm_total' => $m_total,
            'm_assurance' => $m_assurance,
            'm_patient' => $m_patient,
        ]);
    }

    public function etat_fac_acte(Request $request)
    {
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $result= [
            'patient.nomprenomspatient as patient',
            'patient.idenregistremetpatient as matricule_patient',
            'patient.datenaispatient as datenais_patient',
            'assurance.libelleassurance as assurance',
            'factures.montant_ass as part_assurance',
            'factures.montant_pat as part_patient',
            'factures.remise as remise',
            'factures.montanttotal as montant',
        ];

        $acte_cons = DB::table('consultation')
            ->join('factures', 'consultation.numfac', '=', 'factures.numfac')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'consultation.codeassurance', '=', 'assurance.codeassurance')
            ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
            ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
            ->join('garantie', 'consultation.codeacte', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->whereBetween(DB::raw('DATE(consultation.date)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_cons->where('patient.codeassurance', '=', $request->assurance_id);
            }

            if ($request->pres == 'medecin' && $request->medecin_id != 'tous') {
                $acte_cons->where('medecin.codemedecin', '=', $request->medecin_id);
            }

            if ($request->pres == 'specialite' && $request->specialite_id != 'tous') {
                $acte_cons->where('specialitemed.codespecialitemed', '=', $request->specialite_id);
            }

            // $acte_cons = $acte_cons->select(
            //     'consultation.*',
            //     $result,
            //     'medecin.nomprenomsmed as medecin',
            //     'specialitemed.nomspecialite as specialite',
            // )
            // ->get();

            $acte_cons = $acte_cons->select(array_merge(
                $result,
                [
                    'consultation.*',
                    'medecin.nomprenomsmed as medecin',
                    'specialitemed.nomspecialite as specialite',
                ]
            ))->get();

        foreach ($acte_cons as $value) {
            $value->taux = $value->taux ?? 0;
        }


        $acte_hop = DB::table('admission')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'admission.idenregistremetpatient')
            ->leftJoin('assurance', 'admission.codeassurance', '=', 'assurance.codeassurance')
            ->join('factures', 'admission.numfachospit', '=', 'factures.numfac')
            ->whereBetween(DB::raw('DATE(admission.created_at)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_hop->where('patient.codeassurance', '=', $request->assurance_id);
            }

            // $acte_hop = $acte_hop->select(
            //     'admission.*',
            //     $result,
            // )
            // ->get();

            $acte_hop = $acte_hop->select(array_merge(
                $result,
                [
                    'admission.*',
                ]
            ))->get();

        foreach ($acte_hop as $value) {
            $value->taux = $value->taux ?? 0;
        }

        $acte_exam = DB::table('testlaboimagerie')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'testlaboimagerie.idenregistremetpatient')
            ->leftJoin('assurance', 'testlaboimagerie.codeassurance', '=', 'assurance.codeassurance')
            ->join('factures', 'testlaboimagerie.numfacbul', '=', 'factures.numfac')
            ->whereBetween(DB::raw('DATE(testlaboimagerie.date)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_exam->where('patient.codeassurance', '=', $request->assurance_id);
            }

            // $acte_exam = $acte_exam->select(
            //     'testlaboimagerie.*',
            //     $result,
            // )
            // ->get();

            $acte_exam = $acte_exam->select(array_merge(
                $result,
                [
                    'testlaboimagerie.*',
                ]
            ))->get();

        foreach ($acte_exam as $value) {
            $value->taux = $value->taux ?? 0;
        }



        $acte_soinsam = DB::table('soins_medicaux')
            ->join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
            ->leftJoin('assurance', 'soins_medicaux.codeassurance', '=', 'assurance.codeassurance')
            ->join('factures', 'soins_medicaux.numfac_soins', '=', 'factures.numfac')
            ->whereBetween(DB::raw('DATE(soins_medicaux.date_soin)'), [$date1, $date2]);

            if ($request->assurance_id != 'tous') {
                $acte_soinsam->where('patient.codeassurance', '=', $request->assurance_id);
            }

            // $acte_soinsam = $acte_soinsam->select(
            //     'soins_medicaux.*',
            //     $result,
            // )
            // ->get();

            $acte_soinsam = $acte_soinsam->select(array_merge(
                $result,
                [
                    'soins_medicaux.*',
                ]
            ))->get();

        foreach ($acte_soinsam as $value) {
            $value->taux = $value->taux ?? 0;
        }



        if (!$acte_cons->count() && !$acte_hop->count() && !$acte_exam->count() && !$acte_soinsam->count() ) {
            return response()->json(['donnee_0' => true]);
        }

        if ($request->acte == 'cons') {

            if ($acte_cons->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_exam = "";
            $acte_hop = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'hos') {

            if ($acte_hop->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_exam = "";
            $acte_cons = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'exam') {

            if ($acte_exam->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_hop = "";
            $acte_cons = "";
            $acte_soinsam = "";

        } else if ($request->acte == 'soinsam') {

            if ($acte_soinsam->count() == 0 ) {
                return response()->json(['donnee_0' => true]);
            }

            $acte_hop = "";
            $acte_cons = "";
            $acte_exam = "";

        }

        return response()->json([
            'success' => true,
            'acte_exam' => $acte_exam ?? 0,
            'acte_cons' => $acte_cons ?? 0,
            'acte_hop' => $acte_hop ?? 0,
            'acte_soinsam' => $acte_soinsam ?? 0,
            'date1' => $request->date1,
            'date2' => $request->date2,
        ]);
    }

    public function etat_fac_ope_caisse(Request $request)
    {
        
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $trace = DB::table('caisse')
            // ->join('users', 'users.id', '=', 'historiquecaisses.creer_id')
            ->whereBetween('caisse.datecreat', [$date1, $date2])
            ->orderBy('caisse.datecreat', 'desc');

        if ($request->typemvt !== 'tous') {
            $trace->where('caisse.type', '=', $request->typemvt);
        }

        // if ($request->user_id !== 'tous') {
        //     $trace->where('historiquecaisses.creer_id', '=', $request->user_id);
        // }

        $trace = $trace->select(
            'caisse.*',
            // 'users.name as user',
            // 'users.sexe as user_sexe',
        )
        ->get();

        $total = 0;

        foreach ($trace as $value) {
            if ($value->type === 'entree') {
                $total += str_replace('.', '', $value->montant);
            }else{
                $total -= str_replace('.', '', $value->montant);
            }
        }

        if (!$trace->isNotEmpty()) {
            return response()->json(['donnee_0' => true]);
        }

        return response()->json([
            'success' => true,
            'trace' => $trace,
            'total' => $total,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    public function etat_prod_utilise(Request $request)
    {
        $date1 = Carbon::createFromFormat('Y-m-d', $request->date1)->startOfDay();
        $date2 = Carbon::createFromFormat('Y-m-d', $request->date2)->endOfDay();

        $data = [];

        $medocs = DB::table('medicine')
            ->select(
                'medicine.medicine_id as id',
                'medicine.name as nom',
            )
            ->get();

        foreach ($medocs as $value) {
            
            $data1 = DB::table('orders_detail')
                ->where('product_id', '=', $value->id)
                ->whereBetween('date', [$date1, $date2])
                ->select('orders_detail.*')
                ->get();

            if (count($data1) > 0) {
                
                foreach ($data1 as $value_data1) {
                    
                    $data[] = [
                        'nom' => $value->nom,
                        'prix' => $value_data1->rate,
                        'qte' => $value_data1->qty,
                        'total' => $value_data1->amount,
                        'date' => $value_data1->date,
                    ];
                }

            }

            $data2 = DB::table('soins_medicaux_itemmedics')
                ->Join('soins_medicaux', 'soins_medicaux.id_soins', '=', 'soins_medicaux_itemmedics.id_soins')
                ->where('soins_medicaux_itemmedics.medicine_id', '=', $value->id)
                ->whereBetween('soins_medicaux.date_soin', [$date1, $date2])
                ->select('soins_medicaux_itemmedics.*', 'soins_medicaux.date_soin as date_soin')
                ->get();

            if (count($data2) > 0) {
                
                foreach ($data2 as $value_data2) {

                    $prix = (int) $value_data2->price / (int) $value_data2->qte;
                    
                    $data[] = [
                        'nom' => $value->nom,
                        'prix' => $prix,
                        'qte' => $value_data2->qte,
                        'total' => $value_data2->price,
                        'date' => $value_data2->date_soin,
                    ];
                }

            }
        }

        if (count($data) === 0) {
            return response()->json(['donnee_0' => true]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'date1' => $date1,
            'date2' => $date2,
        ]);
    }

    // -------------------------------------------------------------------------

    public function imp_fac_soinam($id)
    {

        $produittotal = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', $id)
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
            ->Join('patient', 'patient.idenregistremetpatient', '=', 'soins_medicaux.idenregistremetpatient')
            ->leftjoin('dossierpatient', 'dossierpatient.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'soins_medicaux.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('societeassure', 'soins_medicaux.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->Join('factures', 'soins_medicaux.numfac_soins', '=', 'factures.numfac')
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
                'factures.remise as remise',
            )
            ->first();

        $patient->nbre_soins = DB::table('soins_medicaux_itemsoins')
            ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

        $patient->nbre_produit = DB::table('soins_medicaux_itemmedics')
            ->where('id_soins', '=', $patient->id_soins)->count() ?: 0;

        $patient->prototal = $produittotal->total ?? 0;
        $patient->stotal = $soinstotal->total ?? 0;

        return response()->json([
            'patient' =>$patient,
            'soins' => $soins,
            'produit' => $produit,
        ]);
    }



















    private function getFacturesByType_Etat_Facture($table, $societe, $date1, $date2, $assurance_id)
    {
        $date_columns = [
            'consultation' => 'date',
            'admission' => 'created_at',
            'testlaboimagerie' => 'date',
            'soins_medicaux' => 'date_soin',
        ];
        $date_name = $date_columns[$table] ?? 'date';

        $facture_columns = [
            'consultation' => 'numfac',
            'admission' => 'numfachospit',
            'testlaboimagerie' => 'numfacbul',
            'soins_medicaux' => 'numfac_soins',
        ];
        $numfac= $facture_columns[$table] ?? 'numfac';

        $query = DB::table($table)
            ->join('patient', "$table.idenregistremetpatient", '=', 'patient.idenregistremetpatient')
            ->join('assurance', 'assurance.codeassurance', '=', "$table.codeassurance")
            ->join('societeassure', 'societeassure.codesocieteassure', '=', "$table.codesocieteassure")
            ->join('factures', 'factures.numfac', '=', "$table.$numfac")
            ->where('societeassure.codesocieteassure', '=', $societe)
            ->whereBetween(DB::raw("DATE($table.$date_name)"), [$date1, $date2])
            ->select(
                "$table.numbon as num_bon",
                "$table.$date_name as created_at",
                "patient.nomprenomspatient as patient",
                "patient.codesocieteassure as codesocieteassure",
                "assurance.libelleassurance as assurance",
                "societeassure.nomsocieteassure as societe",
                "factures.montant_ass as part_assurance",
                "factures.montant_pat as part_patient",
                "factures.remise as remise",
                "factures.montanttotal as montant"
            );

        if ($assurance_id !== 'tous') {
            $query->where("$table.codeassurance", '=', $assurance_id);
        }

        return $query->get();
    }

}
