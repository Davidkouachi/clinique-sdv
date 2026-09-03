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

class RechController extends Controller
{

	public function prelevement()
    {
        $prelevement = DB::table('prelevements')->where('code', '=', '1')->select('prelevements.*')->first();

        return response()->json(['prelevement' => $prelevement]); 
    }

    public function patient(Request $request)
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
            ->first();

        if ($patient) {

            return response()->json(['success' => true, 'patient' => $patient]);
        }else{
            return response()->json(['existep' => true]);
        }
    }

}