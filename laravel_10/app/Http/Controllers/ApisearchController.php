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
// use App\Models\role;
// use App\Models\typeadmission;
// use App\Models\natureadmission;
// use App\Models\detailhopital;
// use App\Models\facture;
// use App\Models\soinsinfirmier;
// use App\Models\typesoins;
// use App\Models\examenpatient;
// use App\Models\examen;
use App\Models\prelevement;
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
// use App\Models\caisse;
// use App\Models\historiquecaisse;
use App\Models\user;
use App\Models\porte_caisse;

class ApisearchController extends Controller
{

    public function rech_patient(Request $request)
    {

        $patient = DB::table('patient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
            ->leftJoin('dossierpatient', 'patient.idenregistremetpatient', '=', 'dossierpatient.idenregistremetpatient')
            ->where('patient.idenregistremetpatient', '=', $request->id)
            ->select(
                'patient.*', 
                'societeassure.nomsocieteassure as societe',
                'assurance.libelleassurance as assurance',
                'tauxcouvertureassure.valeurtaux as taux',
                'filiation.libellefiliation as filiation',
                'dossierpatient.numdossier as numdossier',
            )
            ->orderBy('patient.dateenregistrement','desc')
            ->first();

        if ($patient) {

            return response()->json(['success' => true, 'patient' => $patient]);
        }else{
            return response()->json(['existep' => true]);
        }
    }

    public function rech_patient_hos($code)
    {

        $verf = DB::table('admission')->where('idenregistremetpatient', '=', $code)->select('statut')->first();

        if ($verf->statut == 'en cours') {
            return response()->json(['existe' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function refresh_num_chambre()
    {
        do {
            // Generate a random 9-digit number
            $code = random_int(100, 999); // Generates a number between 100000000 and 999999999
        } while (chambre::where('code', $code)->exists()); // Ensure uniqueness

        if ($code) {
            return response()->json(['success' => true, 'code' => $code]);
        }else{
            return response()->json(['error' => true]);
        }   
    }

    public function refresh_num_lit()
    {
        do {
            // Generate a random 9-digit number
            $code = random_int(100, 999); // Generates a number between 100000000 and 999999999
        } while (chambre::where('code', $code)->exists()); // Ensure uniqueness

        if ($code) {
            return response()->json(['success' => true, 'code' => $code]);
        }else{
            return response()->json(['error' => true]);
        }  
    }

    public function list_chambre_select()
    {
        $list = chambre::all(); // Fetch all chambres
        $availableList = [];    // Array to hold filtered chambres

        foreach ($list as $value) {
            $nbre = lit::where('chambre_id', '=', $value->id)->count(); // Count the number of lits 
            
            if ($nbre < $value->nbre_lit) {
                $availableList[] = $value;
            }
        }

        // Return either the filtered list or all chambres if none were available
        return response()->json(['list' => $availableList]);
    }

    // public function select_acte()
    // {
    //     $acte = acte::all();

    //     return response()->json(['acte' => $acte]); 
    // }

    public function select_specialite()
    {
        $specialite = DB::table('specialitemed')->get();

        return response()->json(['specialite' => $specialite]); 
    }

    public function select_typeacte($codeassurance)
    {

        $typeacte = DB::table('tarifs')
            ->join('garantie', 'tarifs.codgaran', '=', 'garantie.codgaran')
            ->where('garantie.codtypgar', '=', 'CONS')
            ->where('tarifs.codeassurance', '=', $codeassurance)
            ->where(function ($query) {
                $query->where('tarifs.montjour', '!=', 0)
                      ->orWhere('tarifs.montnuit', '!=', 0)
                      ->orWhere('tarifs.montferie', '!=', 0);
            })
            ->select(
                'garantie.codgaran as codgaran',
                'garantie.libgaran as libgaran',
                'tarifs.montjour as prixj',
                'tarifs.montnuit as prixn',
                'tarifs.montferie as prixf',
                'tarifs.codeassurance as codeassurance'
            )
            // ->distinct()
            // ->groupBy('garantie.codgaran', 'garantie.libgaran')
            ->get();


        return response()->json(['typeacte' => $typeacte]); 
    }

    // public function name_patient()
    // {
    //     $name = patient::leftJoin('assurances', 'assurances.id', '=', 'patients.assurance_id')
    //                    ->leftJoin('tauxes', 'tauxes.id', '=', 'patients.taux_id')
    //                    ->leftJoin('societes', 'societes.id', '=', 'patients.societe_id')
    //                    ->select(
    //                         'patients.*', 
    //                         'assurances.nom as assurance', 
    //                         'tauxes.taux as taux', 
    //                         'societes.nom as societe')
    //                    ->get();
                       
    //     return response()->json(['name' => $name]);
    // }

    public function name_patient_reception()
    {
        $name = DB::table('patient')->select('idenregistremetpatient', 'nomprenomspatient')->get();
                       
        return response()->json(['name' => $name]);
    }

    public function name_patient_examen()
    {
       $name = DB::table('patient')
        ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
        ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
        ->select(
            'patient.idenregistremetpatient as id',
            'patient.nomprenomspatient as np',
            'patient.assure as assure',
            'tauxcouvertureassure.valeurtaux as taux',
            'assurance.codeassurance as codeassurance',
        )->get();
                       
        return response()->json(['name' => $name]); 
    }

    public function lit_select($id)
    {
        $lit = DB::table('lits')
            ->join('chambres', 'chambres.id', '=', 'lits.chambre_id')
            ->where('lits.chambre_id', '=', $id)
            ->where('lits.statut', '=', 'disponible')
            ->select('lits.id as id','lits.code as code','lits.type as type')
            ->get();

        return response()->json(['lit' => $lit]); 
    }

    public function select_soinsIn($id)
    {
        $soinsin = DB::table('soins_infirmier')
            ->where('code_typesoins', '=', $id)
            ->select('soins_infirmier.*')
            ->orderBy('libelle_soins', 'asc')
            ->get();

        return response()->json(['soinsin' => $soinsin]); 
    }

    // public function list_acte_ex()
    // {
    //     $acte = acte::where('nom', '=', 'ANALYSE')->Orwhere('nom', '=', 'IMAGERIE')->get();

    //     return response()->json(['acte' => $acte]); 
    // }

    public function montant_prelevement()
    {
        $prelevement = DB::table('prelevements')->where('code', '=', '1')->select('prelevements.*')->first();

        return response()->json(['prelevement' => $prelevement]); 
    }

    public function select_examen($id, $codeassurance, $periode)
    {
        $examens = DB::table('examen')
            ->where('codfamexam', '=', $id)
            ->select(
                'examen.numexam as numexam',
                'examen.cot as cot',
                'examen.denomination as denomination',
                'examen.codfamexam as codfamexam',
                'examen.codgaran as codgaran',
                'examen.prix as prix',
            )
            ->orderBy('examen.denomination', 'asc')
            ->get();

        if ($examens->isEmpty()) {
            return response()->json(['existep' => true]);
        }

        foreach ($examens as $examen) {

            $prix = $examen->prix ;

            if ($id == 'Y') {
                
                if ($examen->codgaran != null || $examen->codgaran != '') {
                        
                    $prix = DB::table('tarifs')
                        ->where('tarifs.codgaran', '=', $examen->codgaran)
                        ->where('tarifs.codeassurance', '=', $codeassurance)
                        ->select('tarifs.*')
                        ->first();

                    if ($prix) {
                        if ($periode == 0) {
                            $examen->tarif = $prix->montjour ?? 0;
                            $examen->valeur = $prix->montjour ?? 0;
                        } else if ($periode == 1) {
                            $examen->tarif = $prix->montnuit ?? 0;
                            $examen->valeur = $prix->montnuit ?? 0;
                        } else if ($periode == 2) {
                            $examen->tarif = $prix->montferie ?? 0;
                            $examen->valeur = $prix->montferie ?? 0;
                        }
                    } else {
                        $examen->tarif = 0;
                        $examen->valeur = 0;
                    }


                    $prix_non_as = DB::table('tarifs')
                        ->where('tarifs.codgaran', '=', $examen->codgaran)
                        ->where('tarifs.codeassurance', '=', 'NONAS')
                        ->select('tarifs.*')
                        ->first();

                    if ($prix_non_as) {
                        if ($periode == 0) {
                            $examen->tarif_non_as = $prix_non_as->montjour ?? 0;
                            $examen->valeur_non_as = $prix_non_as->montjour ?? 0;
                        } else if ($periode == 1) {
                            $examen->tarif_non_as = $prix_non_as->montnuit ?? 0;
                            $examen->valeur_non_as = $prix_non_as->montnuit ?? 0;
                        } else if ($periode == 2) {
                            $examen->tarif_non_as = $prix_non_as->montferie ?? 0;
                            $examen->valeur_non_as = $prix_non_as->montferie ?? 0;
                        }
                    } else {
                        $examen->tarif_non_as = 0;
                        $examen->valeur_non_as = 0;
                    }

                } else {

                    $examen->tarif = 0;
                    $examen->valeur = 0;
                    $examen->tarif_non_as = 0;
                    $examen->valeur_non_as = 0;
                }

            } else if ($id == 'Z' || $id == 'B') {

                if ($examen->cot == null || $examen->cot == '') {
                    $examen->cot = 1;
                }

                if ($examen->cot != null || $examen->cot != '') {

                    $prix = DB::table('tarifs')
                        ->where('tarifs.codgaran', '=', $examen->codfamexam)
                        ->where('tarifs.codeassurance', '=', $codeassurance)
                        ->select('tarifs.*')
                        ->first();

                    if ($prix) {
                        $prix->montj = $examen->cot * ($prix->montjour ?? 0);
                        $prix->montn = $examen->cot * ($prix->montnuit ?? 0);
                        $prix->montf = $examen->cot * ($prix->montferie ?? 0);

                        if ($periode == 0) {
                            $examen->tarif = $prix->montj;
                            $examen->valeur = $prix->montjour;
                        } else if ($periode == 1) {
                            $examen->tarif = $prix->montn;
                            $examen->valeur = $prix->montnuit;
                        } else if ($periode == 2) {
                            $examen->tarif = $prix->montf;
                            $examen->valeur = $prix->montferie;
                        }
                    } else {
                        $examen->tarif = 0;
                        $examen->valeur = 0;
                    }


                    $prix_non_as = DB::table('tarifs')
                        ->where('tarifs.codgaran', '=', $examen->codfamexam)
                        ->where('tarifs.codeassurance', '=', 'NONAS')
                        ->select('tarifs.*')
                        ->first();

                    if ($prix_non_as) {
                        $prix_non_as->montj = $examen->cot * ($prix_non_as->montjour ?? 0);
                        $prix_non_as->montn = $examen->cot * ($prix_non_as->montnuit ?? 0);
                        $prix_non_as->montf = $examen->cot * ($prix_non_as->montferie ?? 0);

                        if ($periode == 0) {
                            $examen->tarif_non_as = $prix_non_as->montj;
                            $examen->valeur_non_as = $prix_non_as->montjour;
                        } else if ($periode == 1) {
                            $examen->tarif_non_as = $prix_non_as->montn;
                            $examen->valeur_non_as = $prix_non_as->montnuit;
                        } else if ($periode == 2) {
                            $examen->tarif_non_as = $prix_non_as->montf;
                            $examen->valeur_non_as = $prix_non_as->montferie;
                        }
                    } else {
                        $examen->tarif_non_as = 0;
                        $examen->valeur_non_as = 0;
                    }

                } else {

                    $examen->tarif_non_as = 0;
                    $examen->tarif = 0;
                    $examen->valeur = 1;
                    $examen->valeur_non_as = 1;
                }

            } else {

                $examen->tarif = 0;
                $examen->valeur = 1;
                $examen->tarif_non_as = 0;
                $examen->valeur_non_as = 1;
            }

        }

        // foreach ($examens as $examen) {

        //     $prix = $examen->prix ;

        //     if ($id == 'Y') {

        //         if ($examen->codgaran != null || $examen->codgaran != '') {

        //             $prix = DB::table('tarifs')
        //                 ->where('tarifs.codgaran', '=', $examen->codgaran)
        //                 ->where('tarifs.codeassurance', '=', $codeassurance)
        //                 ->select('tarifs.*')
        //                 ->first();

        //             if ($periode == 0) {
        //                 $examen->tarif = $prix->montjour ?? 0;
        //             } else if ($periode == 1) {
        //                 $examen->tarif = $prix->montnuit ?? 0;
        //             } else if ($periode == 2) {
        //                 $examen->tarif = $prix->montferie ?? 0;
        //             }


        //             $prix_non_as = DB::table('tarifs')
        //                 ->where('tarifs.codgaran', '=', $examen->codgaran)
        //                 ->where('tarifs.codeassurance', '=', 'NONAS')
        //                 ->select('tarifs.*')
        //                 ->first();

        //             if ($periode == 0) {
        //                 $examen->tarif_non_as = $prix_non_as->montjour ?? 0;
        //             } else if ($periode == 1) {
        //                 $examen->tarif_non_as = $prix_non_as->montnuit ?? 0;
        //             } else if ($periode == 2) {
        //                 $examen->tarif_non_as = $prix_non_as->montferie ?? 0;
        //             }

        //             $examen->valeur = 0;
        //             $examen->valeur_non_as = 0;

        //         } else {

        //             // $examen->tarif = 0;
        //             // $examen->valeur = 0;

        //             // $examen->tarif_non_as = 0;
        //             // $examen->valeur_non_as = 0;

        //             $examen->tarif_non_as = $prix ?? 0;
        //             $examen->tarif = $prix ?? 0;
        //             $examen->valeur = $examen->cot ?? 0;
        //             $examen->valeur_non_as = $examen->cot ?? 0;
        //         }

        //     } else if ($id == 'Z' || $id == 'B') {

        //         if ($examen->cot == null || $examen->cot == '') {
        //             $examen->cot = 1;
        //         }

        //         if ($examen->cot != null || $examen->cot != '') {

        //             $prix = DB::table('tarifs')
        //                 ->where('tarifs.codgaran', '=', $examen->codfamexam)
        //                 ->where('tarifs.codeassurance', '=', $codeassurance)
        //                 ->select('tarifs.*')
        //                 ->first();

        //             $prix->montj = $examen->cot * $prix->montjour ?? 0;
        //             $prix->montn = $examen->cot * $prix->montnuit ?? 0;
        //             $prix->montf = $examen->cot * $prix->montferie ?? 0;

        //             if ($periode == 0) {
        //                 $examen->tarif = $prix->montj;
        //                 $examen->valeur = $prix->montjour;
        //             } else if ($periode == 1) {
        //                 $examen->tarif = $prix->montn;
        //                 $examen->valeur = $prix->montnuit;
        //             } else if ($periode == 2) {
        //                 $examen->tarif = $prix->montf;
        //                 $examen->valeur = $prix->montferie;
        //             }


        //             $prix_non_as = DB::table('tarifs')
        //                 ->where('tarifs.codgaran', '=', $examen->codfamexam)
        //                 ->where('tarifs.codeassurance', '=', 'NONAS')
        //                 ->select('tarifs.*')
        //                 ->first();

        //             $prix_non_as->montj = $examen->cot * $prix_non_as->montjour ?? 0;
        //             $prix_non_as->montn = $examen->cot * $prix_non_as->montnuit ?? 0;
        //             $prix_non_as->montf = $examen->cot * $prix_non_as->montferie ?? 0;

        //             if ($periode == 0) {
        //                 $examen->tarif_non_as = $prix_non_as->montj;
        //                 $examen->valeur_non_as = $prix_non_as->montjour;
        //             } else if ($periode == 1) {
        //                 $examen->tarif_non_as = $prix_non_as->montn;
        //                 $examen->valeur_non_as = $prix_non_as->montnuit;
        //             } else if ($periode == 2) {
        //                 $examen->tarif_non_as = $prix_non_as->montf;
        //                 $examen->valeur_non_as = $prix_non_as->montferie;
        //             }

        //         } else {

        //             // $examen->tarif = 0;
        //             // $examen->valeur = 0;

        //             // $examen->tarif_non_as = 0;
        //             // $examen->valeur_non_as = 0;

        //             $examen->tarif_non_as = $prix ?? 0;
        //             $examen->tarif = $prix ?? 0;
        //             $examen->valeur = $examen->cot ?? 0;
        //             $examen->valeur_non_as = $examen->cot ?? 0;
        //         }

        //     } else {

        //         $examen->tarif = 0;
        //         $examen->valeur = 0;
        //         $examen->tarif_non_as = 0;
        //         $examen->valeur_non_as = 0;
        //     }

        // }

        return response()->json(['success' => true, 'examens' => $examens]);

    }

    public function select_jour()
    {
        $rech = DB::table('joursemaines')->select('id','jour')->get();

        return response()->json(['rech' => $rech]); 
    }

    public function montant_solde()
    {
        // $solde = caisse::find('1');

        // return response()->json(['solde' => $solde]);

        // $montant = 0;

        // $solde = DB::table('caisse')->select('type','montant')->get();

        // foreach ($solde as $value) {
        //     if ($value->type === 'entree') {
        //         $montant += $value->montant;
        //     } else if ($value->type === 'sortie') {
        //         $montant -= $value->montant;
        //     }
        // }

        // return response()->json(['montant' => $montant]); 

        $montant = DB::table('caisse')
            ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
            ->value('solde');

        // $caisse = DB::table('porte_caisses')->where('id', '=', 1)->select('montant')->first();

        return response()->json(['montant' => $montant]);

    }

    // public function list_caissier()
    // {
    //     $caissier = user::select('id','name','sexe')->get();

    //     return response()->json(['caissier' => $caissier]); 
    // }

    public function select_profil()
    {
        $profil = DB::table('profile')
                ->where('libprofile', '!=', 'MEDECIN')
                ->get();

        return response()->json(['profil' => $profil]); 
    }

    public function rech_hos_patient($id)
    {
        $verf = DB::table('admission')->where('idenregistremetpatient', '=', $id)->select('statut')->first();

        if ($verf && $verf->statut == 'en cours') {
            return response()->json(['existe' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function verf_caisse()
    {
        $caisse = DB::table('porte_caisses')->where('id', '=', 1)->first();

        return response()->json(['caisse' => $caisse]);
    }

    public function select_list_medecin()
    {

        $medecin = DB::table('medecin')->select('codemedecin','nomprenomsmed')->get();

        return response()->json(['medecin' => $medecin]);
    }

    public function select_typeadmission()
    {
        $typeadmission = DB::table('typehospitalsation')->select('idtypehospit','nomtypehospit')->get();

        return response()->json(['typeadmission' => $typeadmission]);
    }

    public function natureadmission_select()
    {
        $natureadmission = DB::table('naturehospit')->select('idnathospit','nomnaturehospit')->get();

        return response()->json(['natureadmission' => $natureadmission]); 
    }

    public function select_chambre()
    {
        $chambre = DB::table('chambres')->where('statut', '=', 'disponible')->select('code','id')->get();

        return response()->json(['chambre' => $chambre]);
    }

    public function select_civilite()
    {
        $civilite = DB::table('civilite')->get();

        return response()->json(['civilite' => $civilite]); 
    }

    public function select_service()
    {
        $service = DB::table('service')->get();

        return response()->json(['service' => $service]); 
    }

    public function select_contrat()
    {
        $contrat = DB::table('contrat')->get();

        return response()->json(['contrat' => $contrat]); 
    }

}
