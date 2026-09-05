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

class ApihistoriqueController extends Controller
{
    public function historique_caisse($date)
    {
        // ==========================================================
        // 1. RÉSUMÉ DE CAISSE
        //    0 = Ouverture
        //    1 = Opérations journalières
        //    2 = Fermeture historique_caisse
        // ==========================================================

        $resumes = DB::table('caisse_resume')
            ->whereDate('datecaisse', $date)
            ->orderBy('datecaisse', 'asc')
            ->get();


        // ==========================================================
        // 2. JOURNAL
        //    0 = Entrée
        //    1 = Sortie
        // ==========================================================

        $journaux = DB::table('caisse')
            ->whereDate('datecreat', $date)
            ->orderBy('datecreat', 'asc')
            ->get();


        // ==========================================================
        // 3. FUSION DES TRACES
        // ==========================================================

        $trace = collect();


        // ==========================================================
        // 4. OUVERTURE / FERMETURE
        // ==========================================================

        foreach ($resumes as $resume) {

            $type = match ((int) $resume->action) {
                0 => 'OUVERTURE',
                2 => 'FERMETURE',
                default => null,
            };

            // On ignore les opérations journalières du résumé
            // car les mouvements réels sont déjà dans journal.
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


        // ==========================================================
        // 5. ENTRÉES / SORTIES
        // ==========================================================

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


        // ==========================================================
        // 6. TRI CHRONOLOGIQUE
        // ==========================================================

        $trace = $trace
            ->sortBy('date')
            ->values();


        // ==========================================================
        // 7. CALCUL DU SOLDE
        // ==========================================================

        $total = 0;

        foreach ($trace as $operation) {

            switch ($operation['type']) {

                case 'OUVERTURE':

                    // Le montant d'ouverture est le solde initial.
                    $total = $operation['montant'];

                    break;


                case 'entrer':

                    $total += $operation['montant'];

                    break;


                case 'sortie':

                    $total -= $operation['montant'];

                    break;


                case 'FERMETURE':

                    // La fermeture ne modifie pas le solde.
                    break;
            }
        }


        // ==========================================================
        // 8. RÉPONSE
        // ==========================================================

        return response()->json([
            'trace' => $trace,
            'total' => $total,
        ]);
    }
}
