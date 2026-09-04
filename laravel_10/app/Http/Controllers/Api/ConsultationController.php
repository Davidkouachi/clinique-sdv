<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

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
use Illuminate\Pagination\LengthAwarePaginator;

use App\Services\PaginationService;
use App\Services\MatriculeService;

class ConsultationController extends Controller
{

	protected $paginationService;
	protected $matriculeService;

    public function __construct(
        PaginationService $paginationService,
        MatriculeService $matriculeService,
    )
    {
        $this->paginationService = $paginationService;
        $this->matriculeService = $matriculeService;
    }

	public function listAll(Request $request)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Paramètres
	    |--------------------------------------------------------------------------
	    */

	    $date1 = $request->input('date1');
	    $date2 = $request->input('date2');

	    $perPage = min(
	        max(
	            (int) $request->input('per_page', 15),
	            1
	        ),
	        100
	    );

	    $search = trim(
	        $request->input('search', '')
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Query
	    |--------------------------------------------------------------------------
	    */

	    $query = DB::table('consultation')

	        ->join(
	            'factures',
	            'consultation.numfac',
	            '=',
	            'factures.numfac'
	        )

	        ->join(
	            'patient',
	            'consultation.idenregistremetpatient',
	            '=',
	            'patient.idenregistremetpatient'
	        )

	        ->join(
	            'medecin',
	            'consultation.codemedecin',
	            '=',
	            'medecin.codemedecin'
	        )

	        ->join(
	            'specialitemed',
	            'medecin.codespecialitemed',
	            '=',
	            'specialitemed.codespecialitemed'
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | Filtre dates
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($date1)) {

	        if (!empty($date2)) {

	            $query->whereBetween(
	                'consultation.date',
	                [
	                    $date1,
	                    $date2
	                ]
	            );

	        } else {

	            $query->where(
	                'consultation.date',
	                '>=',
	                $date1
	            );
	        }
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Recherche
	    |--------------------------------------------------------------------------
	    */

	    if ($search !== '') {

	        $query->where(function ($q) use ($search) {

	            $q->Where(
	                'consultation.numfac',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'patient.numdossier',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'patient.nomprenomspatient',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'medecin.nomprenomsmed',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'specialitemed.nomspecialite',
	                'LIKE',
	                "%{$search}%"
	            );
	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | SELECT
	    |--------------------------------------------------------------------------
	    */

	    $query->select([

	        'consultation.idconsexterne',

	        'consultation.montant',

	        'consultation.date',

	        'consultation.numfac',

	        'consultation.partassurance',

	        'consultation.ticketmod as partpatient',

	        'consultation.taux',

	        'factures.remise',

	        'patient.numdossier',

	        'patient.nomprenomspatient as nom_patient',

	        'patient.telpatient as tel_patient',

	        'patient.assure',

	        'medecin.nomprenomsmed as medecin',

	        'specialitemed.nomspecialite as specialite',

	        'factures.montantregle_pat as montant_regle',

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination Laravel
	    |--------------------------------------------------------------------------
	    */

	    $result = $query
	        ->orderBy(
	            'consultation.date',
	            'desc'
	        )
	        ->paginate(
	            $perPage,
	            ['*'],
	            'page',
	            $request->input('page', 1)
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | Réponse
	    |--------------------------------------------------------------------------
	    */

	    return response()->json([

	        'success' => true,

	        'data' => $result->items(),

	        'meta' => [

	            'current_page' => $result->currentPage(),

	            'per_page' => $result->perPage(),

	            'total' => $result->total(),

	            'last_page' => $result->lastPage(),

	            'from' => $result->firstItem(),

	            'to' => $result->lastItem(),

	            'has_more_pages' => $result->hasMorePages(),

	        ],

	    ]);
	}

	public function detailComplet(Request $request, $code)
	{
        $facture = DB::table('consultation')
            ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
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
                'patient.numdossier as numdossier',
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

	public function delete($numfac)
	{
	    DB::beginTransaction();

	    try {

	        // Vérifier que la consultation existe
	        $consultation = DB::table('consultation')
	            ->where('numfac', $numfac)
	            ->first();

	        if (!$consultation) {
	            DB::rollBack();

	            return response()->json([
	                'warn' => true,
	                'message' => 'Consultation introuvable.'
	            ]);
	        }

	        // Vérifier que la facture existe
	        $facture = DB::table('factures')
	            ->where('numfac', $numfac)
	            ->first();

	        if (!$facture) {
	            DB::rollBack();

	            return response()->json([
	                'warn' => true,
	                'message' => 'Facture introuvable.'
	            ]);
	        }

	        // Supprimer la consultation
	        DB::table('consultation')
	            ->where('numfac', $numfac)
	            ->delete();

	        // Supprimer la facture
	        DB::table('factures')
	            ->where('numfac', $numfac)
	            ->delete();

	        DB::commit();

	        return response()->json([
	            'success' => true,
	            'message' => 'Suppression effectuée avec succès.'
	        ]);

	    } catch (\Throwable $e) {

	        DB::rollBack();

	        return response()->json([
	            'error' => true,
	            'message' => 'Une erreur est survenue lors de la suppression.'
	        ], 500);
	    }
	}

    public function create(Request $request)
    {

        DB::beginTransaction();

            try {

                $numfac = $this->matriculeService->generate( 
                	table: 'consultation', 
                	column: 'numfac', 
                	prefix: 'FCE', 
                	length: 6 
                );

                $codeassurance = DB::table('patient')
                    ->where('idenregistremetpatient', '=', $request->id_patient)
                    ->select('codeassurance','codesocieteassure')
                    ->first();

                $consultationInserted = DB::table('consultation')->insert([
                    'idenregistremetpatient' => $request->id_patient,
                    'codeassurance' => $codeassurance->codeassurance,
                    'codesocieteassure' => $codeassurance->codesocieteassure,
                    'numbon' => $request->mumcode ?? null,
                    'montant' => str_replace('.', '', $request->total),
                    'taux' => $request->patient_taux,
                    'ticketmod' => str_replace('.', '', $request->montant_patient),
                    'partassurance' => str_replace('.', '', $request->montant_assurance),
                    'codemedecin' => $request->user_id,
                    'codeacte' => $request->typeacte_id,
                    'regle' => str_replace('.', '', $request->montant_patient) === 0 ? 1 : 0,
                    'date' => now(),
                    'facimprime' => 0,
                    'numfac' => $numfac,
                ]);

                if ($consultationInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table consultation');
                }

                $montant_patient = (int) str_replace('.', '', $request->montant_patient);

                $facturesInserted = DB::table('factures')->insert([
                    'numfac' => $numfac,
                    'idenregistremetpatient' => $request->id_patient,
                    'montanttotal' => str_replace('.', '', $request->total),
                    'remise' => str_replace('.', '', $request->taux_remise),
                    'type_remise' => 0,
                    'calcul_applique' => 0,
                    'taux_applique' => $request->patient_taux,
                    'montant_ass' => str_replace('.', '', $request->montant_assurance),
                    'montant_pat' => str_replace('.', '', $request->montant_patient),
                    'montantregle_ass' => 0,
                    'montantregle_pat' => 0,
                    'montantpat_verser' => 0,
                    'montantpat_remis' => 0,
                    'montantreste_ass' => str_replace('.', '', $request->montant_assurance),
                    'montantreste_pat' => str_replace('.', '', $request->montant_patient),
                    'solde_ass' => 0,
                    'solde_pat' => 0,
                    'codeassurance' => $request->codeassurance,
                    'datefacture' => now(),
                    'type_facture' => 1,
                    'timbre_fiscal' => 0,
                    'a_encaisser' => 0,
                    'datereglt_pat' => null,
                    'numrecu' => null,
                ]);

                if ($facturesInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table factures');
                }

                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

}