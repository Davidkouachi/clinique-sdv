<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

use Carbon\Carbon;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class StatistiqueController extends Controller
{

    // Tableau de bord

	public function dashbordNbre($date)
    {

        $prefixes = ['FCE', 'FCS', 'FCB', 'FCH'];

        $nbre_fac = DB::table('factures')
            ->whereDate('datefacture', $date)
            ->where(function ($query) use ($prefixes) {
                    foreach ($prefixes as $prefix) {
                        $query->orWhere('numfac', 'like', $prefix . '%');
                    }
                })
            ->count() ?? 0;

        $baseQuery = function ($field) use ($date, $prefixes) {
            return DB::table('factures')
                ->whereDate('datefacture', $date)
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

        $getTable = function ($table, $factureKey, $date) {
            return DB::table($table)
                ->join('factures', 'factures.numfac', '=', "$table.$factureKey")
                ->whereDate('factures.datefacture', $date)
                ->count() ?? 0;
        };

        // Montants d'aujourd'hui
        $stat_cons = $getTable('consultation', 'numfac', $date);
        $stat_exam = $getTable('laboratoires', 'numfac', $date);
        $stat_soins = $getTable('soins_medicaux', 'numfac_soins', $date);
        $stat_hosp = $getTable('admission', 'numfachospit', $date);

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

    public function dashbordNbreWeekend()
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

    public function dashbordSoldeDay()
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

    public function dashbordHisCaisse($date)
    {

        // ouverture & fermeture
        $resumes = DB::table('caisse_resume')
            ->whereDate('datecaisse', $date)
            ->orderBy('datecaisse', 'asc')
            ->get();

        // operation de caisse
        $journaux = DB::table('caisse')
            ->whereDate('datecreat', $date)
            ->orderBy('datecreat', 'asc')
            ->get();

        $trace = collect();

        foreach ($resumes as $resume) {

            $type = match ((int) $resume->action) {
                0 => 'OUVERTURE',
                2 => 'FERMETURE',
                default => null,
            };

            if (!$type) {
                continue;
            }

            $montant = (float) ($resume->mtcaisse ?? 0);

            $trace->push([
                'date'    => $resume->datecaisse,
                'montant' => $montant,
                'type'    => $type,
                'auteur'  => $resume->user ?? null,
            ]);
        }

        foreach ($journaux as $journal) {

            $montant = (float) str_replace(
                ['.', ','],
                ['', '.'],
                $journal->montant ?? 0
            );

            $trace->push([
                'date'    => $journal->datecreat,
                'montant' => $montant,
                'type'    => $journal->type === 'entree'
                    ? 'entrer'
                    : 'sortie',
                'auteur'  => $journal->login ?? null,
            ]);
        }

        $trace = $trace->sortBy('date')->values();

        $total = 0;

        foreach ($trace as $operation) {

            switch ($operation['type']) {

                case 'OUVERTURE':

                    $total = $operation['montant'];

                    break;

                case 'entrer':

                    $total += $operation['montant'];

                    break;

                case 'sortie':

                    $total -= $operation['montant'];

                    break;

                case 'FERMETURE':

                    break;
            }
        }

        return response()->json([
            'trace' => $trace,
            'total' => $total,
        ]);
    }



    // Statistique

    public function consultationNbre($date1, $date2)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $date1)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $date2)->endOfDay(); 

        $typeacte = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->select('garantie.codgaran as codgaran','garantie.libgaran as libgaran')
            ->distinct()
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

}