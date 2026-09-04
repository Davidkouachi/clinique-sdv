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
use App\Models\User;
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
use App\Models\prelevement;
// use App\Models\caisse;
// use App\Models\historiquecaisse;
use App\Models\rdvpatient;

// getStatFacDay getWeeklyConsultations getWeeklyDashbord


class ApistatController extends Controller
{
    public function statistique_reception($date)
    {
        // $today = Carbon::today();

        $prefixes = ['FCE', 'FCS', 'FCB', 'FCH'];

        $today = $date;

        $nbre_fac = DB::table('factures')
            ->whereDate('datefacture', $today)
            ->where(function ($query) use ($prefixes) {
                    foreach ($prefixes as $prefix) {
                        $query->orWhere('numfac', 'like', $prefix . '%');
                    }
                })
            ->count() ?? 0;

        $baseQuery = function ($field) use ($today, $prefixes) {
            return DB::table('factures')
                ->whereDate('datefacture', $today)
                ->where(function ($query) use ($prefixes) {
                    foreach ($prefixes as $prefix) {
                        $query->orWhere('numfac', 'like', $prefix . '%');
                    }
                })
                ->select(DB::raw("COALESCE(SUM(REPLACE(factures.$field, '.', '') + 0), 0) as montant"))
                ->first()
                ->montant ?? 0;
        };

        $montant_fac_r = $baseQuery('montantregle_pat');
        $montant_fac_nr = $baseQuery('montantreste_pat');
        $total_fac = $baseQuery('montant_pat');


        // Fonction interne pour récupérer le montant d'une source pour une date
        $getTable = function ($table, $factureKey, $date) {
            return DB::table($table)
                ->join('factures', 'factures.numfac', '=', "$table.$factureKey")
                ->whereDate('factures.datefacture', $date)
                ->count() ?? 0;
        };

        // Montants d'aujourd'hui
        $stat_cons = $getTable('consultation', 'numfac', $today);
        $stat_exam = $getTable('laboratoires', 'numfac', $today);
        $stat_soins = $getTable('soins_medicaux', 'numfac_soins', $today);
        $stat_hosp = $getTable('admission', 'numfachospit', $today);

        return response()->json([
            'nbre_fac' => $nbre_fac,
            'montant_fac_r' => $montant_fac_r,
            'montant_fac_nr' => $montant_fac_nr,
            'total_fac' => $total_fac,
            'stat_cons' => $stat_cons,
            'stat_exam' => $stat_exam,
            'stat_soins' => $stat_soins,
            'stat_hosp' => $stat_hosp,
        ]);
    }

    // public function statistique_caisse()
    // {
    //     // Combine the queries into a single query to improve performance
    //     $stats = detailconsultation::select(DB::raw('
    //         COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
    //         COALESCE(SUM(REPLACE(part_patient, ".", "") + 0), 0) as part_patient,
    //         COALESCE(SUM(REPLACE(part_assurance, ".", "") + 0), 0) as part_assurance
    //     '))->first();

    //     $payer = facture::where('statut', '=', 'payer')->count();
    //     $impayer = facture::where('statut', '=', 'impayer')->count();

    //     // Return the results as JSON
    //     return response()->json([
    //         'part_total' => $stats->part_total ?? 0,
    //         'part_patient' => $stats->part_patient ?? 0,
    //         'part_assurance' => $stats->part_assurance ?? 0,
    //         'payer' => $payer ?? 0,
    //         'impayer' => $impayer ?? 0,
    //     ]);
    // }

    public function statistique_reception_cons($date1, $date2)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $date1)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $date2)->endOfDay(); 

        $typeacte = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->select('garantie.codgaran as codgaran','garantie.libgaran as libgaran')
            ->distinct()
            // ->groupBy('garantie.codgaran', 'garantie.libgaran')
            ->get();

        foreach ($typeacte as $value) {
            $stats = DB::table('consultation')
                ->where('codeacte', '=', $value->codgaran)
                ->whereBetween('consultation.date', [$startDate, $endDate])
                ->select(DB::raw('
                    COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
                    COALESCE(SUM(REPLACE(ticketmod, ".", "") + 0), 0) as part_patient,
                    COALESCE(SUM(REPLACE(partassurance, ".", "") + 0), 0) as part_assurance
                '))
                ->first();

            $nbre = DB::table('consultation')
                ->where('codeacte', '=', $value->codgaran)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            $value->part_patient = $stats->part_patient;
            $value->part_assurance = $stats->part_assurance;
            $value->total = $stats->part_total;
            $value->nbre = $nbre;

        }

        return response()->json(['typeacte' => $typeacte]);
    }

    public function statistique_cons()
    {
        $typeacte = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->select('garantie.codgaran as codgaran','garantie.libgaran as libgaran')
            ->distinct()
            // ->groupBy('garantie.codgaran', 'garantie.libgaran')
            ->get();

        foreach ($typeacte as $value) {
            $stats = DB::table('consultation')
                ->where('codeacte', '=', $value->codgaran)
                ->select(DB::raw('
                    COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as part_total,
                    COALESCE(SUM(REPLACE(ticketmod, ".", "") + 0), 0) as part_patient,
                    COALESCE(SUM(REPLACE(partassurance, ".", "") + 0), 0) as part_assurance
                '))
                ->first();

            $nbre = DB::table('consultation')->where('codeacte', '=', $value->codgaran)->count();

            $value->part_patient = $stats->part_patient;
            $value->part_assurance = $stats->part_assurance;
            $value->total = $stats->part_total;
            $value->nbre = $nbre;

        }

        return response()->json(['typeacte' => $typeacte]);
    }

    public function getWeeklyDashbord()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // ==========================================================
        // SEMAINE ACTUELLE
        // ==========================================================

        $consultations = DB::table('consultation')
            ->selectRaw('DATE(date) as date, COUNT(*) as total')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->groupByRaw('DATE(date)')
            ->pluck('total', 'date');

        $admissions = DB::table('admission')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date');

        $soins = DB::table('soins_medicaux')
            ->selectRaw('DATE(date_soin) as date, COUNT(*) as total')
            ->whereBetween('date_soin', [$startOfWeek, $endOfWeek])
            ->groupByRaw('DATE(date_soin)')
            ->pluck('total', 'date');

        $examens = DB::table('laboratoires')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date');

        // ==========================================================
        // TOTAL PAR JOUR
        // ==========================================================

        $weeklyCounts = [];

        for ($i = 0; $i < 7; $i++) {

            $date = $startOfWeek->copy()->addDays($i);
            $dateKey = $date->format('Y-m-d');

            $total =
                (int) ($consultations[$dateKey] ?? 0)
                + (int) ($admissions[$dateKey] ?? 0)
                + (int) ($soins[$dateKey] ?? 0)
                + (int) ($examens[$dateKey] ?? 0);

            $weeklyCounts[] = [
                'date' => $dateKey,
                'total' => $total,
            ];
        }


        // ==========================================================
        // TOTAL SEMAINE ACTUELLE
        // ==========================================================

        $totalCurrentWeek = collect($weeklyCounts)->sum('total');


        // ==========================================================
        // SEMAINE PRÉCÉDENTE
        // ==========================================================

        $startOfLastWeek = now()->subWeek()->startOfWeek();
        $endOfLastWeek = now()->subWeek()->endOfWeek();


        $lastWeekConsultations = DB::table('consultation')
            ->whereBetween('date', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        $lastWeekAdmissions = DB::table('admission')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        $lastWeekSoins = DB::table('soins_medicaux')
            ->whereBetween('date_soin', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        $lastWeekExamens = DB::table('laboratoires')
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();


        // ==========================================================
        // TOTAL UNIQUE DE LA SEMAINE PRÉCÉDENTE
        // ==========================================================

        $lastWeekCount =
            $lastWeekConsultations
            + $lastWeekAdmissions
            + $lastWeekSoins
            + $lastWeekExamens;


        // ==========================================================
        // POURCENTAGE D'ÉVOLUTION
        // ==========================================================

        if ($lastWeekCount > 0) {

            $percentageIncrease =
                (($totalCurrentWeek - $lastWeekCount) / $lastWeekCount) * 100;

        } else {

            $percentageIncrease =
                $totalCurrentWeek > 0 ? 100 : 0;
        }


        // ==========================================================
        // RESPONSE
        // ==========================================================

        return response()->json([
            'weeklyCounts' => $weeklyCounts,
            'currentWeek' => $totalCurrentWeek,
            'lastWeek' => $lastWeekCount,
            'percentage' => round($percentageIncrease, 2),
        ]);
    }

    public function statistique_hos()
    {
        $today = Carbon::today();
        // Combine the queries into a single query to improve performance
        $stat_hos_day = DB::table('admission')->whereDate('dateentree', '=', $today)->count();

        return response()->json([
            'stat_hos_day' => $stat_hos_day ?? 0,
        ]);
    }

    public function statistique_soinsam()
    {
        $today = Carbon::today();
        // Combine the queries into a single query to improve performance
        $stat_soinsam_day = DB::table('soins_medicaux')->whereDate('date_soin', '=', $today)->count();

        // Return the results as JSON
        return response()->json([
            'stat_soinsam_day' => $stat_soinsam_day ?? 0,
        ]);
    }

    public function statistique_examen()
    {
        $today = Carbon::today();

        $ida = DB::table('laboratoires')
            ->where('typedemande', '=', 'analyse')
            ->whereDate('date', '=', $today)
            ->count();

        $idi = DB::table('laboratoires')
            ->where('typedemande', '=', 'imagerie')
            ->whereDate('date', '=', $today)
            ->count();

        // Return the results as JSON
        return response()->json([
            'nbre_analyse_day' => $ida ?? 0,
            'nbre_imagerie_day' => $idi ?? 0,
        ]);
    }

    public function stat_comp_acte($yearSelect)
    {
        $monthlyStats = [
            'consultations' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'hospitalisations' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'examens' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'soins_ambulatoires' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ]
        ];

        // 1. Consultations
        $consultations = DB::table('consultation')->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('date', $yearSelect)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($consultations as $consultation) {
            $monthIndex = intval($consultation->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['consultations'][$monthName] = $consultation->count;
        }

        // 2. Hospitalisations
        $hospitalisations = DB::table('admission')->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $yearSelect)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        foreach ($hospitalisations as $hospitalisation) {
            $monthIndex = intval($hospitalisation->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['hospitalisations'][$monthName] = $hospitalisation->count;
        }

        // 3. Examens
        $examens = DB::table('laboratoires')->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('date', $yearSelect)
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        foreach ($examens as $examen) {
            $monthIndex = intval($examen->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['examens'][$monthName] = $examen->count;
        }

        // 4. Soins Ambulatoires
        $soins = DB::table('soins_medicaux')->select(
                DB::raw('MONTH(date_soin) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('date_soin', $yearSelect)
            ->groupBy(DB::raw('MONTH(date_soin)'))
            ->get();

        foreach ($soins as $soin) {
            $monthIndex = intval($soin->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['soins_ambulatoires'][$monthName] = $soin->count;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json(['monthlyStats' => $monthlyStats]);
    }

    public function stat_acte_mois($date1, $date2)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $date1)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $date2)->endOfDay(); 

        if (!$startDate || !$endDate) {
            return response()->json(['date_invalide' => 'Dates invalides']);
        }

        $consultations = DB::table('consultation')->whereBetween('date', [$startDate, $endDate])->count();
        $hospitalisations = DB::table('admission')->whereBetween('created_at',[$startDate, $endDate])->count();
        $examens = DB::table('laboratoires')->whereBetween('date', [$startDate, $endDate])->count();
        $soinsAmbulatoires = DB::table('soins_medicaux')->whereBetween('date_soin',[$startDate, $endDate])->count();

        $nbre_fac = $consultations + $hospitalisations + $examens + $soinsAmbulatoires;

        $m_cons = DB::table('consultation')
        ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
        ->select(DB::raw('
            COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as total_payer,
            COALESCE(SUM(REPLACE(factures.montantreste_pat, ".", "") + 0), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
        '))
        ->whereBetween('consultation.date', [$startDate, $endDate])
        ->first();


        $m_hos = DB::table('admission')
        ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
        ->select(DB::raw('
            COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as total_payer,
            COALESCE(SUM(REPLACE(factures.montantreste_pat, ".", "") + 0), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
        '))
        ->whereBetween('admission.created_at', [$startDate, $endDate])
        ->first();

        $m_exam = DB::table('laboratoires')
        ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
        ->select(DB::raw('
            COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as total_payer,
            COALESCE(SUM(REPLACE(factures.montantreste_pat, ".", "") + 0), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
        '))
        ->whereBetween('laboratoires.date', [$startDate, $endDate])
        ->first();

        $m_soinsam = DB::table('soins_medicaux')
        ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
        ->select(DB::raw('
            COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as total_payer,
            COALESCE(SUM(REPLACE(factures.montantreste_pat, ".", "") + 0), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
        '))
        ->whereBetween('soins_medicaux.date_soin', [$startDate, $endDate])
        ->first();

        $data = [
            'cons' => $consultations ?? 0,
            'hos' => $hospitalisations ?? 0,
            'exam' => $examens ?? 0,
            'soinsam' => $soinsAmbulatoires ?? 0,
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        // -------------------------------------------------

        // $typeacte = DB::table('garantie')
        //     ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
        //     ->where('garantie.codtypgar', '=', 'CONS')
        //     ->select(
        //         'garantie.codgaran as codgaran',
        //         'garantie.libgaran as libgaran',
        //         'garantie.codtypgar as codtypgar',
        //     )
        //     ->get();

        $typeacte = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->select(
                'garantie.codgaran as codgaran',
                'garantie.libgaran as libgaran',
                'garantie.codtypgar as codtypgar',
            )
            ->distinct()
            ->get();


        foreach ($typeacte as $value) {

            $stats = DB::table('consultation')
                ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
                ->join('medecin', 'consultation.codemedecin', '=', 'medecin.codemedecin')
                ->join('specialitemed', 'medecin.codespecialitemed', '=', 'specialitemed.codespecialitemed')
                ->where('consultation.codeacte', '=', $value->codgaran)
                ->whereBetween('consultation.date', [$startDate, $endDate])
                ->select(DB::raw('
                    COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as part_total,
                    COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                    COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                    COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
                '))
                ->first();

            $nbre = DB::table('consultation')
                ->where('codeacte', '=', $value->codgaran)
                ->whereBetween('date', [$startDate, $endDate])
                ->count();

            if ($stats) {
                $value->part_patient = $stats->part_patient ?? 0;
                $value->part_assurance = $stats->part_assurance ?? 0;
                $value->total = $stats->part_total ?? 0;
                $value->remise = $stats->remise ?? 0;
                $value->nbre = $nbre ?? 0;
            } else {
                $value->part_patient = 0;
                $value->part_assurance = 0;
                $value->total = 0;
                $value->remise = 0;
                $value->nbre = 0;
            }

        }

        $type_exam = [
            ['nom' => 'analyse'],
            ['nom' => 'imagerie']
        ];

        foreach ($type_exam as &$exam) {

            $value = $exam['nom'];
            
            $examen = DB::table('laboratoires')
                ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
                ->where('laboratoires.typedemande', '=', $value)
                ->whereBetween('laboratoires.date', [$startDate, $endDate])
                ->select(DB::raw('
                    COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as part_total,
                    COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                    COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                    COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise
                '))
                ->first();

            $nbre = DB::table('laboratoires')
                ->where('typedemande', '=', $value)
                ->whereBetween('date', [$startDate, $endDate])
                ->count() ?? 0;

            if ($examen) {

                $exam['part_patient'] = $examen->part_patient;
                $exam['part_assurance'] = $examen->part_assurance;
                $exam['total'] = $examen->part_total;
                $exam['remise'] = $examen->remise;
                $exam['nbre'] = $nbre;

            } else {

                $exam['part_patient'] = 0;
                $exam['part_assurance'] = 0;
                $exam['total'] = 0;
                $exam['remise'] = 0;
                $exam['nbre'] = 0;

            }
            
        }

        // -------------------------------------------------

        // $fac_nbre = DB::table('factures')
        //     ->where(function ($query) {
        //         $query->where(DB::raw('factures.type_facture', '=', 1))
        //               ->Orwhere(DB::raw('factures.type_facture', '=', 2))
        //               ->Orwhere(DB::raw('factures.type_facture', '=', 3))
        //               ->Orwhere(DB::raw('factures.type_facture', '=', 4))
        //     })
        //     ->whereBetween('datefacture', [$startDate, $endDate])
        //     ->count();

        $fac_total = $m_cons->total_general + $m_hos->total_general + $m_exam->total_general + $m_soinsam->total_general ;

        $fac_remise = $m_cons->remise + $m_hos->remise + $m_soinsam->remise ;

        $fac_impayer = $m_cons->total_impayer + $m_hos->total_impayer + $m_exam->total_impayer + $m_soinsam->total_impayer ;

        $fac_payer = $m_cons->total_payer + $m_hos->total_payer + $m_exam->total_payer + $m_soinsam->total_payer ;

        $fac_assurance = $m_cons->part_assurance + $m_hos->part_assurance + $m_exam->part_assurance + $m_soinsam->part_assurance ;

        $fac_patient = $m_cons->part_patient + $m_hos->part_patient + $m_exam->part_patient + $m_soinsam->part_patient ;

        $dataCaisse = [
            'fac_nbre' => $nbre_fac ?? 0,
            'fac_total' => $fac_total ?? 0,
            'fac_impayer' => $fac_impayer ?? 0,
            'fac_payer' => $fac_payer ?? 0,
            'fac_assurance' => $fac_assurance ?? 0,
            'fac_patient' => $fac_patient ?? 0,
            'fac_remise' => $fac_remise ?? 0,
        ];

        return response()->json([
            'data' => $data,
            'dataCaisse' => $dataCaisse,
            'typeacte' => $typeacte,
            'type_exam' => $type_exam,
        ]);
    }

    public function statistique_patient(Request $request)
    {
        $stat_h = DB::table('patient')->where('sexe', '=', 'M')->count();
        $stat_f = DB::table('patient')->where('sexe', '=', 'F')->count();
        $stat_a = DB::table('patient')->where('assure', '=', '1')->count();
        $stat_an = DB::table('patient')->where('assure', '=', '0')->count();

        return response()->json([
            'stat_h' => $stat_h ?? 0,
            'stat_f' => $stat_f ?? 0,
            'stat_a' => $stat_a ?? 0,
            'stat_an' => $stat_an ?? 0,
        ]);
    }

    public function patient_stat($id)
    {
        $nbre_cons = DB::table('consultation')->where('idenregistremetpatient', '=', $id)->count();
        $nbre_hos = DB::table('admission')->where('idenregistremetpatient', '=', $id)->count();
        $nbre_exam = DB::table('laboratoires')->where('idenregistremetpatient', '=', $id)->count();
        $nbre_soinsam = DB::table('soins_medicaux')->where('idenregistremetpatient', '=', $id)->count();

        $m_cons = DB::table('consultation')
        ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
        ->where('consultation.idenregistremetpatient', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();


        $m_hos = DB::table('admission')
        ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
        ->where('admission.idenregistremetpatient', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_exam = DB::table('laboratoires')
        ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
        ->where('laboratoires.idenregistremetpatient', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $m_soinsam = DB::table('soins_medicaux')
        ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
        ->where('soins_medicaux.idenregistremetpatient', '=', $id)
        ->select(DB::raw('
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
            COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
            COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
            COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
            COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
            COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
        '))
        ->first();

        $data = [
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        $fac_patient_payer = $m_cons->part_patient_payer + $m_hos->part_patient_payer + $m_exam->part_patient_payer + $m_soinsam->part_patient_payer ;

        $fac_patient_impayer = $m_cons->part_patient_impayer + $m_hos->part_patient_impayer + $m_exam->part_patient_impayer + $m_soinsam->part_patient_impayer ;

        $fac_patient_total = $fac_patient_impayer + $fac_patient_payer;

        return response()->json([
            'data' => $data,
            'nbre_cons' => $nbre_cons ?? 0,
            'nbre_hos' => $nbre_hos ?? 0,
            'nbre_exam' => $nbre_exam ?? 0,
            'nbre_soinsam' => $nbre_soinsam ?? 0,
            'fac_patient_payer' => $fac_patient_payer ?? 0,
            'fac_patient_impayer' => $fac_patient_impayer ?? 0,
            'fac_patient_total' => $fac_patient_total ?? 0,
        ]);
    }

    public function assurance_stat($id)
    {
        $nbre_cons = DB::table('consultation')
            ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
            ->where('factures.codeassurance', '=', $id)
            ->count();

        $nbre_hos = DB::table('admission')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('factures.codeassurance', '=', $id)
            ->count();

        $nbre_exam = DB::table('laboratoires')
            ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
            ->where('factures.codeassurance', '=', $id)
            ->count();

        $nbre_soinsam = DB::table('soins_medicaux')
            ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
            ->where('factures.codeassurance', '=', $id)
            ->count();

        $m_cons = DB::table('consultation')
            ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
            ->where('factures.codeassurance', '=', $id)
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
                COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
                COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
            '))
            ->first();

        $m_hos = DB::table('admission')
            ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
            ->where('factures.codeassurance', '=', $id)
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
                COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
                COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
            '))
            ->first();

        $m_exam = DB::table('laboratoires')
            ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
            ->where('factures.codeassurance', '=', $id)
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
                COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
                COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
            '))
            ->first();

        $m_soinsam = DB::table('soins_medicaux')
            ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
            ->where('factures.codeassurance', '=', $id)
            ->select(DB::raw('
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montanttotal, ".", "") + 0 ELSE 0 END), 0) as total_impayer,
                COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as total_general,
                COALESCE(SUM(REPLACE(factures.montant_ass, ".", "") + 0), 0) as part_assurance,
                COALESCE(SUM(REPLACE(factures.montant_pat, ".", "") + 0), 0) as part_patient,
                COALESCE(SUM(REPLACE(factures.remise, ".", "") + 0), 0) as remise,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat = 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_payer,
                COALESCE(SUM(CASE WHEN factures.montantreste_pat > 0 THEN REPLACE(factures.montant_pat, ".", "") + 0 ELSE 0 END), 0) as part_patient_impayer
            '))
            ->first();

        $data = [
            'm_cons' => $m_cons,
            'm_hos' => $m_hos,
            'm_exam' => $m_exam,
            'm_soinsam' => $m_soinsam,
        ];

        return response()->json([
            'data' => $data,
            'nbre_cons' => $nbre_cons ?? 0,
            'nbre_hos' => $nbre_hos ?? 0,
            'nbre_exam' => $nbre_exam ?? 0,
            'nbre_soinsam' => $nbre_soinsam ?? 0,
        ]);
    }

    public function stat_comp_ope($yearSelect)
    {
        $monthlyStats = [
            'entrer' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'sortie' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'total' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ]
        ];

        $totalG = 0;
        $total_entrer = 0;
        $total_sortie = 0;

        // 1. Consultations
        $entrer = DB::table('caisse')->select(
                DB::raw('MONTH(dateop) as month'),
                DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as montant')
            )
            ->where('type', '=', 'entree')
            ->whereYear('dateop', $yearSelect)
            ->groupBy(DB::raw('MONTH(dateop)'))
            ->get();

        foreach ($entrer as $entre) {
            $monthIndex = intval($entre->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['entrer'][$monthName] = $entre->montant;
            $total_entrer += $entre->montant;
        }

        // 2. Hospitalisations
        $sortie = DB::table('caisse')->select(
                DB::raw('MONTH(dateop) as month'),
                DB::raw('COALESCE(SUM(REPLACE(montant, ".", "") + 0), 0) as montant')
            )
            ->where('type', '=', 'sortie')
            ->whereYear('dateop', $yearSelect)
            ->groupBy(DB::raw('MONTH(dateop)'))
            ->get();

        foreach ($sortie as $sorti) {
            $monthIndex = intval($sorti->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['sortie'][$monthName] = $sorti->montant;
            $total_sortie += $sorti->montant;
        }

        $total = DB::table('caisse')->whereYear('dateop', $yearSelect)
            ->groupBy(DB::raw('MONTH(dateop)'))
            ->select(
                DB::raw('MONTH(dateop) as month'),
                DB::raw('
                    COALESCE(SUM(IF(type = "entree", REPLACE(montant, ".", "") + 0, 0)), 0) as total_entrer,
                    COALESCE(SUM(IF(type = "sortie", REPLACE(montant, ".", "") + 0, 0)), 0) as total_sortie
                ')
            )
            ->get();

        foreach ($total as $value) {
            $Gtotal = (int)$value->total_entrer - (int)$value->total_sortie;
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['total'][$monthName] = $Gtotal;
            $totalG += $Gtotal;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'monthlyStats' => $monthlyStats,
            'total_entrer' => $total_entrer,
            'total_sortie' => $total_sortie,
            'total' => $totalG,
        ]);
    }

    public function stat_new_pat($yearSelect)
    {
        // Initialiser les statistiques mensuelles
        $monthlyStats = [
            'new' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
        ];

        $total = 0;
        $homme = 0;
        $femme = 0;

        // Requête pour compter les patients enregistrés par mois
        $patients = DB::table('patient')
            ->select(
                DB::raw('MONTH(dateenregistrement) as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('COUNT(CASE WHEN sexe = "M" THEN 1 END) as M_count'),
                DB::raw('COUNT(CASE WHEN sexe = "F" THEN 1 END) as F_count')
            )
            ->whereYear('dateenregistrement', $yearSelect)
            ->groupBy(DB::raw('MONTH(dateenregistrement)'))
            ->get();

        // Parcourir les résultats et remplir les statistiques mensuelles
        foreach ($patients as $patient) {
            $monthIndex = intval($patient->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['new'][$monthName] = $patient->count;
            $total += $patient->count;
            $homme += $patient->M_count;
            $femme += $patient->F_count;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'monthlyStats' => $monthlyStats,
            'total' => $total,
            'homme' => $homme,
            'femme' => $femme,
        ]);
    }


    public function count_rdv_two_day()
    {
        $twoDaysLater = Carbon::today()->addDays(2);

        $nbre = DB::table('rdvpatients')->whereDate('date', '=', $twoDaysLater)->count();

        return response()->json([
            'nbre' => $nbre,
        ]);
    }

    public function stat_chiff_acte($yearSelect)
    {
        $monthlyStats = [
            'consultation' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'examen' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'hospitalisation' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
            'soins ambulatoire' => [
                'Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0,
                'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0,
            ],
        ];

        $consultation = DB::table('consultation')
        ->join('factures', 'factures.numfac', '=', 'consultation.numfac')
        // ->where('factures.montantreste_pat', '=', 0)
        ->whereYear('consultation.date', $yearSelect)
        ->select(
            DB::raw('MONTH(consultation.date) as month'),
            DB::raw('COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as montant')
        )
        ->groupBy(DB::raw('MONTH(consultation.date)'))
        ->get();


        foreach ($consultation as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['consultation'][$monthName] = $value->montant;
        }

        $examen = DB::table('laboratoires')
        ->join('factures', 'factures.numfac', '=', 'laboratoires.numfac')
        // ->where('factures.montantreste_pat', '=', 0)
        ->groupBy(DB::raw('MONTH(laboratoires.date)'))
        ->whereYear('laboratoires.date', $yearSelect)
        ->select(
            DB::raw('MONTH(laboratoires.date) as month'),
            DB::raw('COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($examen as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['examen'][$monthName] = $value->montant;
        }

        $hos = DB::table('admission')
        ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
        // ->where('factures.montantreste_pat', '=', 0)
        ->groupBy(DB::raw('MONTH(admission.created_at)'))
        ->whereYear('admission.created_at', $yearSelect)
        ->select(
            DB::raw('MONTH(admission.created_at) as month'),
            DB::raw('COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($hos as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['hospitalisation'][$monthName] = $value->montant;
        }

        $soinsam = DB::table('soins_medicaux')
        ->join('factures', 'factures.numfac', '=', 'soins_medicaux.numfac_soins')
        // ->where('factures.montantreste_pat', '=', 0)
        ->groupBy(DB::raw('MONTH(soins_medicaux.date_soin)'))
        ->whereYear('soins_medicaux.date_soin', $yearSelect)
        ->select(
            DB::raw('MONTH(soins_medicaux.date_soin) as month'),
            DB::raw('COALESCE(SUM(REPLACE(factures.montantregle_pat, ".", "") + 0), 0) as montant')
        )
        ->get();

        foreach ($soinsam as $value) {
            $monthIndex = intval($value->month);
            $monthName = date('M', mktime(0, 0, 0, $monthIndex, 10));
            $monthlyStats['soins ambulatoire'][$monthName] = $value->montant;
        }

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'monthlyStats' => $monthlyStats,
        ]);
    }

    public function stat_nombre()
    {
        $totalPatients = DB::table('patient')->count();
        $totalAssurances = DB::table('assurance')->where('codeassurance', '!=', 'NONAS')->count();
        $totalSocietes = DB::table('societeassure')->count();
        $totalAssureurs = DB::table('assureur')->count();
        $totalProduitsPharmacie = DB::table('medicine')->count();
        $totalMedecin = DB::table('medecin')->count();
        $totalSoinsInf = DB::table('actes_as')->count();
        $totalEmploye = DB::table('employes')->count();
        $totalUser = DB::table('users')->count();
        $totalBiologie = DB::table('examen')->where('codfamexam', '=', 'B')->count();
        $totalImgsc = DB::table('examen')->where('codfamexam', '=', 'Z')->count();
        $totalImgac = DB::table('examen')->where('codfamexam', '=', 'Y')->count();

        // Structurer les données dans un tableau
        $data = [
            [
                'nom' => 'Patients',
                'nombre' => $totalPatients,
            ],
            [
                'nom' => 'Assurances',
                'nombre' => $totalAssurances,
            ],
            [
                'nom' => 'Sociétés',
                'nombre' => $totalSocietes,
            ],
            [
                'nom' => 'Assureurs',
                'nombre' => $totalAssureurs,
            ],
            [
                'nom' => 'Produits de Pharmacie',
                'nombre' => $totalProduitsPharmacie,
            ],
            [
                'nom' => 'Médécins',
                'nombre' => $totalMedecin,
            ],
            [
                'nom' => 'Soins Infirmiers',
                'nombre' => $totalSoinsInf,
            ],
            [
                'nom' => 'Employés',
                'nombre' => $totalEmploye,
            ],
            [
                'nom' => 'Utilisateurs',
                'nombre' => $totalUser,
            ],
            [
                'nom' => 'Analyses',
                'nombre' => $totalBiologie,
            ],
            [
                'nom' => 'Imagerie sans carton',
                'nombre' => $totalImgsc,
            ],
            [
                'nom' => 'Imagerie avec carton',
                'nombre' => $totalImgac,
            ],
        ];

        // Retourner les résultats sous forme de réponse JSON
        return response()->json([
            'data' => $data,
        ]);
    }

    public function getStatFacDay()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Fonction interne pour récupérer le montant d'une source pour une date
        $getMontant = function ($table, $factureKey, $date) {
            return DB::table($table)
                ->join('factures', 'factures.numfac', '=', "$table.$factureKey")
                ->whereDate('factures.datefacture', $date)
                ->select(DB::raw('COALESCE(SUM(REPLACE(factures.montanttotal, ".", "") + 0), 0) as montant'))
                ->first()->montant;
        };

        // Montants d'aujourd'hui
        $cons_today = $getMontant('consultation', 'numfac', $today);
        $exam_today = $getMontant('laboratoires', 'numfac', $today);
        $soins_today = $getMontant('soins_medicaux', 'numfac_soins', $today);
        $hosp_today = $getMontant('admission', 'numfachospit', $today);

        $data = [
            [
                'id' => '1',
                'nom' => 'Consultations',
                'montant' => $cons_today,
            ],
            [
                'id' => '2',
                'nom' => 'Examens',
                'montant' => $exam_today,
            ],
            [
                'id' => '3',
                'nom' => 'Soins Ambulatoires',
                'montant' => $soins_today,
            ],
            [
                'id' => '4',
                'nom' => 'Hospitalisations',
                'montant' => $hosp_today,
            ],
        ];

        return response()->json([
            'data' => $data,
        ]);
    }


}
