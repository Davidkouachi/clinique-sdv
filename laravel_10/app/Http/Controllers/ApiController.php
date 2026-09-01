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

// use App\Models\assurance;
// use App\Models\taux;
// use App\Models\societe;
// use App\Models\user;
// use App\Models\role;

class ApiController extends Controller
{
    public function taux_select_patient_new()
    {
        $taux = DB::table('tauxcouvertureassure')->select('idtauxcouv','valeurtaux')->orderBy('valeurtaux', 'asc')->get();

        return response()->json(['taux' => $taux]); 
    }

    public function societe_select_patient_new()
    {
        $societe = DB::table('societeassure')->select('codesocieteassure','nomsocieteassure')->get();

        return response()->json(['societe' => $societe]); 
    }

    public function assurance_select_patient_new()
    {
        $assurance = DB::table('assurance')->where('codeassurance', '!=', 'NONAS')->select('codeassurance','libelleassurance')->get();

        return response()->json(['assurance' => $assurance]); 
    }

    public function filiation_select_patient_new()
    {
        $filiation = DB::table('filiation')->select('codefiliation','libellefiliation')->get();

        return response()->json(['filiation' => $filiation]); 
    }

    // public function select_medecin()
    // {
    //     $role = role::where('nom', '=', 'MEDECIN')->first();

    //     $medecin = user::where('users.role_id', '=', $role->id)->select('id','name')->get();

    //     return response()->json($medecin);
    // }

    public function select_assureur()
    {
        $assureur = DB::table('assureur')->select('codeassureur','libelle_assureur')->get();

        return response()->json(['assureur' => $assureur]); 
    }

    public function select_typegarantie()
    {
        $type = DB::table('typgarantie')->select('codtypgar','libtypgar')->get();

        return response()->json(['type' => $type]); 
    }

    public function select_garantie()
    {
        $garantie = DB::table('garantie')->select('codgaran','libgaran')->get();

        return response()->json(['garantie' => $garantie]); 
    }

    public function select_typesoins()
    {
        $typesoins = DB::table('typesoinsinfirmiers')
            ->select('typesoinsinfirmiers.*')
            ->orderBy('libelle_typesoins', 'asc')
            ->get();

        return response()->json(['typesoins' => $typesoins]);
    }

    public function select_category_medicine()
    {
        $categorie = DB::table('medicine_category')->select('medicine_category_id','name')->get();

        return response()->json(['categorie' => $categorie]);
    }

    public function select_produit()
    {
        $produit = DB::table('medicine')
            ->select('medicine_id','name','price','status')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json(['produit' => $produit]);
    }

    public function select_type_examen()
    {
        $type = DB::table('famille_examen')->select('famille_examen.*')->get();

        return response()->json(['type' => $type]);
    }

    public function select_type_examend()
    {
        $type = DB::table('famille_examen')->where('codfamexam', '!=', 'D')->select('famille_examen.*')->get();

        return response()->json(['type' => $type]);
    }

    public function prix_examen($id)
    {
        $examen = DB::table('examen')
            ->where('numexam', '=', $id)
            ->select('examen.*')
            ->first();

        if ($examen->codfamexam != null || $examen->codfamexam != '') {
            
            if ($examen->codfamexam == 'Y') {

                if ($examen->codgaran != null || $examen->codgaran != '') {
                    
                    $prix = DB::table('tarifs')
                        ->join('assurance', 'assurance.codeassurance', '=', 'tarifs.codeassurance')
                        ->where('tarifs.codgaran', '=', $examen->codgaran)
                        ->select('tarifs.*', 'assurance.libelleassurance as assurance')
                        ->get();

                    return response()->json(['success' => true, 'prix' => $prix]);

                } else {

                    return response()->json(['existep' => true]);
                }
    
            } else if ( $examen->codfamexam == 'B' || $examen->codfamexam == 'Z' ) {

                if ($examen->cot != null || $examen->cot != '' || $examen->cot != 0) {
                    
                    $prix = DB::table('tarifs')
                        ->join('assurance', 'assurance.codeassurance', '=', 'tarifs.codeassurance')
                        ->where('tarifs.codgaran', '=', $examen->codfamexam)
                        ->select('tarifs.*', 'assurance.libelleassurance as assurance')
                        ->get();

                    foreach ($prix as $value) {
                        
                        $value->montjour = $examen->cot * $value->montjour;
                        $value->montnuit = $examen->cot * $value->montnuit;
                        $value->montferie = $examen->cot * $value->montferie;
                    }

                    return response()->json(['success' => true, 'prix' => $prix]);

                } else {

                    return response()->json(['existep' => true]);
                }

            } else {

                return response()->json(['existep' => true]);
            }

        } else {

            return response()->json(['existep' => true]);
        }
    }

    public function select_garantie_hos()
    {
        $hos = DB::table('garanties_hospit')
            ->whereNotIn('id', [2, 3, 4, 5, 7])
            ->get();

        return response()->json(['hos' => $hos]);
    }

}
