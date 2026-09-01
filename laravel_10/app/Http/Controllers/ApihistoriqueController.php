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
// use App\Models\portecaisse;

class ApihistoriqueController extends Controller
{
    // public function historique_caisse($date)
    // {
    //     $total = 0;

    //     $trace = historiquecaisse::whereDate('created_at', '=', $date)->get();

    //     $ofc = portecaisse::whereDate('created_at', '=', $date)->get();

    //     foreach ($trace as $value) {
    //         if ($value->typemvt === 'Entrer de Caisse') {
    //             $total += str_replace('.', '', $value->montant);
    //         }else{
    //             $total -= str_replace('.', '', $value->montant);
    //         }
    //     }

    //     return response()->json(['trace' => $trace, 'ofc' => $ofc, 'total' => $total]);

    // }
}
