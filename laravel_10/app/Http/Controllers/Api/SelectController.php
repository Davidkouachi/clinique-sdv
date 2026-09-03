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

class SelectController extends Controller
{

    // ------------------------------------------------------------

    public function patient(Request $request)
    {
        $query = DB::table('patient')
            ->select(
                'patient.idenregistremetpatient as id',
                'patient.nomprenomspatient as nom'
            )
            ->orderBy(
                'patient.nomprenomspatient',
                'asc'
            );


        /*
         * Filtre patient
         */
        if (!empty($request->id)) {

            $query->where(
                'patient.idenregistremetpatient',
                $request->id
            );
        }


        /*
         * Filtre type de dossier
         */
        if (!empty($request->typedossier)) {

            $query->whereExists(function ($subQuery) use ($request) {

                $subQuery->select(DB::raw(1))
                    ->from('dossierpatient')
                    ->whereColumn(
                        'dossierpatient.idenregistremetpatient',
                        'patient.idenregistremetpatient'
                    )
                    ->where(
                        'dossierpatient.codetypdossier',
                        $request->typedossier
                    );

            });
        }


        return response()->json([
            'results' => $query->get()
        ]);
    }

    public function medecin(Request $request)
    {
        $medecin = DB::table('medecin')->select('codemedecin as code','nomprenomsmed as nom')->get();

        return response()->json([
            'results' => $medecin
        ]);
    }

    public function typeacte(Request $request)
    {
        $query = DB::table('tarifs')
            ->join(
                'garantie',
                'tarifs.codgaran',
                '=',
                'garantie.codgaran'
            )
            ->where(function ($query) {

                $query
                    ->where('tarifs.montjour', '!=', 0)
                    ->orWhere('tarifs.montnuit', '!=', 0)
                    ->orWhere('tarifs.montferie', '!=', 0);

            })
            ->select(
                'garantie.codgaran as codgaran',
                'garantie.libgaran as libgaran',
                'tarifs.montjour as prixj',
                'tarifs.montnuit as prixn',
                'tarifs.montferie as prixf'
            );

        if (!empty($request->typegar)) {

            $query->where(
                'garantie.codtypgar',
                '=',
                $request->typegar
            );
        }

        if (!empty($request->codeassurance)) {

            $query->where(
                'tarifs.codeassurance',
                '=',
                $request->codeassurance
            );
        }

        $typeacte = $query->get();

        return response()->json([
            'results' => $typeacte ?? []
        ]);
    }

    public function typexamen(Request $request)
    {
        $type = DB::table('famille_examen')->select('codfamexam','nomfamexam')->get();

        return response()->json(['results' => $type]);
    }

    public function examens(Request $request)
    {
        Log::info($request->all());
        $query = DB::table('examen')
            ->select(
                'examen.numexam as numexam',
                'examen.cot as cot',
                'examen.denomination as denomination',
                'examen.codfamexam as codfamexam',
                'examen.codgaran as codgaran',
                'examen.prix as prix'
            )
            ->orderBy('examen.denomination', 'asc');

        if (!empty($request->id)) {

            $query->where(
                'examen.codfamexam',
                '=',
                $request->id
            );
        }

        $examens = $query->get();

        if ($examens->isEmpty()) {

            return response()->json([
                'results' => []
            ]);
        }

        foreach ($examens as $examen) {

            $examen->tarif = 0;
            $examen->valeur = 0;
            $examen->tarif_non_as = 0;
            $examen->valeur_non_as = 0;

            if ($request->id == 'Y') {

                if (!empty($examen->codgaran)) {

                    $prix = DB::table('tarifs')
                        ->where(
                            'tarifs.codgaran',
                            '=',
                            $examen->codgaran
                        )
                        ->where(
                            'tarifs.codeassurance',
                            '=',
                            $request->codeassurance
                        )
                        ->first();


                    if ($prix) {

                        if ($request->periode == 0) {

                            $examen->tarif = $prix->montjour ?? 0;
                            $examen->valeur = $prix->montjour ?? 0;

                        } elseif ($request->periode == 1) {

                            $examen->tarif = $prix->montnuit ?? 0;
                            $examen->valeur = $prix->montnuit ?? 0;

                        } elseif ($request->periode == 2) {

                            $examen->tarif = $prix->montferie ?? 0;
                            $examen->valeur = $prix->montferie ?? 0;
                        }
                    }

                    $prixNonAssure = DB::table('tarifs')
                        ->where(
                            'tarifs.codgaran',
                            '=',
                            $examen->codgaran
                        )
                        ->where(
                            'tarifs.codeassurance',
                            '=',
                            'NONAS'
                        )
                        ->first();


                    if ($prixNonAssure) {

                        if ($request->periode == 0) {

                            $examen->tarif_non_as =
                                $prixNonAssure->montjour ?? 0;

                            $examen->valeur_non_as =
                                $prixNonAssure->montjour ?? 0;

                        } elseif ($request->periode == 1) {

                            $examen->tarif_non_as =
                                $prixNonAssure->montnuit ?? 0;

                            $examen->valeur_non_as =
                                $prixNonAssure->montnuit ?? 0;

                        } elseif ($request->periode == 2) {

                            $examen->tarif_non_as =
                                $prixNonAssure->montferie ?? 0;

                            $examen->valeur_non_as =
                                $prixNonAssure->montferie ?? 0;
                        }
                    }
                }
            }

            elseif ($request->id == 'Z' || $request->id == 'B') {

                if (empty($examen->cot)) {
                    $examen->cot = 1;
                }

                $prix = DB::table('tarifs')
                    ->where(
                        'tarifs.codgaran',
                        '=',
                        $examen->codfamexam
                    )
                    ->where(
                        'tarifs.codeassurance',
                        '=',
                        $request->codeassurance
                    )
                    ->first();


                if ($prix) {

                    $montJour =
                        $examen->cot * ($prix->montjour ?? 0);

                    $montNuit =
                        $examen->cot * ($prix->montnuit ?? 0);

                    $montFerie =
                        $examen->cot * ($prix->montferie ?? 0);


                    if ($request->periode == 0) {

                        $examen->tarif = $montJour;
                        $examen->valeur = $prix->montjour ?? 0;

                    } elseif ($request->periode == 1) {

                        $examen->tarif = $montNuit;
                        $examen->valeur = $prix->montnuit ?? 0;

                    } elseif ($request->periode == 2) {

                        $examen->tarif = $montFerie;
                        $examen->valeur = $prix->montferie ?? 0;
                    }
                }

                $prixNonAssure = DB::table('tarifs')
                    ->where(
                        'tarifs.codgaran',
                        '=',
                        $examen->codfamexam
                    )
                    ->where(
                        'tarifs.codeassurance',
                        '=',
                        'NONAS'
                    )
                    ->first();


                if ($prixNonAssure) {

                    $montJour =
                        $examen->cot * ($prixNonAssure->montjour ?? 0);

                    $montNuit =
                        $examen->cot * ($prixNonAssure->montnuit ?? 0);

                    $montFerie =
                        $examen->cot * ($prixNonAssure->montferie ?? 0);


                    if ($request->periode == 0) {

                        $examen->tarif_non_as = $montJour;
                        $examen->valeur_non_as =
                            $prixNonAssure->montjour ?? 0;

                    } elseif ($request->periode == 1) {

                        $examen->tarif_non_as = $montNuit;
                        $examen->valeur_non_as =
                            $prixNonAssure->montnuit ?? 0;

                    } elseif ($request->periode == 2) {

                        $examen->tarif_non_as = $montFerie;
                        $examen->valeur_non_as =
                            $prixNonAssure->montferie ?? 0;
                    }
                }

            }

            else {

                $examen->tarif = 0;
                $examen->valeur = 1;

                $examen->tarif_non_as = 0;
                $examen->valeur_non_as = 1;
            }
        }

        return response()->json([
            'results' => $examens
        ]);
    }

}
