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

class ExamenController extends Controller
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

	    $page = max(
	        (int) $request->input('page', 1),
	        1
	    );

	    $perPage = min(
	        max((int) $request->input('per_page', 15), 1),
	        100
	    );

	    $search = trim(
	        $request->input('search', '')
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Requête principale
	    |--------------------------------------------------------------------------
	    */

	    $query = DB::table('laboratoires')

	        ->join(
	            'factures',
	            'laboratoires.numfac',
	            '=',
	            'factures.numfac'
	        )

	        ->join(
	            'patient',
	            'laboratoires.patient_id',
	            '=',
	            'patient.idenregistremetpatient'
	        )

	        ->join(
	            'dossierpatient',
	            'patient.idenregistremetpatient',
	            '=',
	            'dossierpatient.idenregistremetpatient'
	        )

	        ->where(
	            'dossierpatient.codetypedossier',
	            'DC'
	        );


	    if (!empty($date1)) {

	        if (!empty($date2)) {

	            $query->whereBetween(
	                'laboratoires.created_at',
	                [
	                    $date1,
	                    $date2
	                ]
	            );

	        } else {

	            $query->where(
	                'laboratoires.created_at',
	                '>=',
	                $date1
	            );
	        }
	    }

	    if ($search !== '') {

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                'laboratoires.numfac',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'laboratoires.patient_id',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'laboratoires.medecin',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'laboratoires.numcode',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'laboratoires.numhosp',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'patient.nomprenomspatient',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'patient.telpatient',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'dossierpatient.numdossier',
	                'LIKE',
	                "%{$search}%"
	            );
	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Sélection
	    |--------------------------------------------------------------------------
	    */

	    $query->select([

	        'laboratoires.id',

	        'laboratoires.patient_id',

	        'laboratoires.numfac',

	        'laboratoires.code_assurance',

	        'laboratoires.code_societe',

	        'laboratoires.medecin',

	        'laboratoires.montant_total',

	        'laboratoires.montant_patient',

	        'laboratoires.montant_assurance',

	        'laboratoires.remise',

	        'laboratoires.taux',

	        'laboratoires.periode',

	        'laboratoires.numcode',

	        'laboratoires.numhosp',

	        'laboratoires.rensg',

	        'laboratoires.created_at',

	        'dossierpatient.numdossier',

	        'patient.nomprenomspatient as nom_patient',

	        'patient.telpatient as tel_patient',

	        'patient.assure',

	        'factures.montantregle_pat as montant_regle',

	        DB::raw('(
		        SELECT COUNT(*)
		        FROM laboratoire_details
		        WHERE laboratoire_details.laboratoire_id = laboratoires.id
		    ) as nbre_examens'),

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Tri
	    |--------------------------------------------------------------------------
	    */

	    $query->orderBy(
	        'laboratoires.created_at',
	        'desc'
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination
	    |--------------------------------------------------------------------------
	    */

	    $result = $this->paginationService->paginate(

	        query: $query,

	        countTable: 'laboratoires',

	        page: $page,

	        perPage: $perPage,

	        countColumn: 'laboratoires.id'
	    );


	    return response()->json(
	        $result
	    );
	}

	public function detailComplet(Request $request, $id)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Informations générales du laboratoire
	    |--------------------------------------------------------------------------
	    */

	    $facture = DB::table('laboratoires')

	        ->join(
	            'patient',
	            'laboratoires.patient_id',
	            '=',
	            'patient.idenregistremetpatient'
	        )

	        ->leftJoin(
	            'societeassure',
	            'laboratoires.code_societe',
	            '=',
	            'societeassure.codesocieteassure'
	        )

	        ->leftJoin(
	            'assurance',
	            'laboratoires.code_assurance',
	            '=',
	            'assurance.codeassurance'
	        )

	        ->leftJoin(
	            'filiation',
	            'patient.codefiliation',
	            '=',
	            'filiation.codefiliation'
	        )

	        ->leftJoin(
	            'tauxcouvertureassure',
	            'patient.idtauxcouv',
	            '=',
	            'tauxcouvertureassure.idtauxcouv'
	        )

	        ->leftJoin(
	            'dossierpatient',
	            'patient.idenregistremetpatient',
	            '=',
	            'dossierpatient.idenregistremetpatient'
	        )

	        ->where(
	            'dossierpatient.codetypedossier',
	            'DC'
	        )

	        ->join(
	            'factures',
	            'laboratoires.numfac',
	            '=',
	            'factures.numfac'
	        )

	        ->where(
	            'laboratoires.id',
	            '=',
	            $id
	        )

	        ->select([

	            'laboratoires.id',

	            'laboratoires.patient_id',

	            'laboratoires.numfac',

	            'laboratoires.medecin',

	            'laboratoires.montant_total',

	            'laboratoires.montant_patient',

	            'laboratoires.montant_assurance',

	            'laboratoires.remise',

	            'laboratoires.taux',

	            'laboratoires.periode',

	            'laboratoires.numcode',

	            'laboratoires.numhosp',

	            'laboratoires.rensg',

	            'laboratoires.created_at as date',

	            /*
	            | Patient
	            */

	            'patient.idenregistremetpatient as id_patient',

	            'patient.nomprenomspatient as nom_patient',

	            'patient.telpatient as telpatient',

	            'patient.assure',

	            'patient.datenaispatient as datenais',

	            'patient.matriculeassure as matricule',

	            'dossierpatient.numdossier',

	            /*
	            | Assurance
	            */

	            'societeassure.nomsocieteassure as societe',

	            'assurance.libelleassurance as assurance',

	            'tauxcouvertureassure.valeurtaux as taux',

	            'filiation.libellefiliation as filiation',

	            /*
	            | Facture
	            */

	            'factures.montant_ass as part_assurance',

	            'factures.montant_pat as part_patient',

	            'factures.montantregle_pat as part_patient_regler',

	            'factures.numrecu as numrecu',

	            'factures.datereglt_pat as datereglt_pat',

	            'factures.montantpat_verser as montant_verser',

	            'factures.montantpat_remis as montant_remis',

	            'factures.montantreste_pat as montant_restant',

	        ])

	        ->first();


	    /*
	    |--------------------------------------------------------------------------
	    | Dossier du patient
	    |--------------------------------------------------------------------------
	    */

	    if ($facture) {

	        $facture->numdossier = DB::table('dossierpatient')

	            ->where(
	                'idenregistremetpatient',
	                $facture->patient_id
	            )

	            ->where(
	                'codetypedossier',
	                'DC'
	            )

	            ->orderByDesc('idenregistremetpatient')

	            ->value('numdossier');

	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Liste des examens
	    |--------------------------------------------------------------------------
	    */

	    $details = collect();

	    if ($facture) {

	        $details = DB::table('laboratoire_details')

	            ->where(
	                'laboratoire_id',
	                $facture->id
	            )

	            ->select([

	                'id',

	                'examen_id',

	                'code',

	                'famille',

	                'examen',

	                'cotation',

	                'valeur',

	                'montant',

	                'prelevement',

	                'assurance',

	            ])

	            ->orderBy('id')

	            ->get();

	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Réponse
	    |--------------------------------------------------------------------------
	    */

	    return response()->json([

	        'facture' => $facture,

	        'details' => $details,

	    ]);
	}

	public function delete($id)
	{
	    DB::beginTransaction();

	    try {

	        // Vérifier que le laboratoire existe
	        $laboratoire = DB::table('laboratoires')
	            ->where('id', $id)
	            ->first();

	        if (!$laboratoire) {

	            DB::rollBack();

	            return response()->json([
	                'warn' => true,
	                'message' => 'Laboratoire introuvable.'
	            ]);
	        }

	        $numfac = $laboratoire->numfac;


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


	        // Supprimer le laboratoire
	        // Les laboratoire_details seront supprimés
	        // automatiquement grâce à cascadeOnDelete()
	        DB::table('laboratoires')
	            ->where('id', $id)
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
	    $selections = $request->input('selectionsExamen');

	    if (!is_array($selections) || empty($selections)) {
	        return response()->json([
	            'json' => true
	        ]);
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Vérification hospitalisation
	    |--------------------------------------------------------------------------
	    */

	    if ($request->numhosp !== null) {

	        $verf = DB::table('admission')
	            ->where('numhospit', $request->numhosp)
	            ->first();

	        if (!$verf) {
	            return response()->json([
	                'num_hosp_introuvable' => true
	            ]);
	        }

	        if ($verf->statut === 'sortie') {
	            return response()->json([
	                'num_hosp_liberer' => true
	            ]);
	        }

	        if ($verf->idenregistremetpatient != $request->patient_id) {
	            return response()->json([
	                'matricule_hosp_error' => true
	            ]);
	        }
	    }

	    /*
	    |--------------------------------------------------------------------------
	    | Informations assurance du patient
	    |--------------------------------------------------------------------------
	    */

	    $patient = DB::table('patient')
	        ->where(
	            'idenregistremetpatient',
	            $request->patient_id
	        )
	        ->select(
	            'codeassurance',
	            'codesocieteassure'
	        )
	        ->first();

	    if (!$patient) {
	        return response()->json([
	            'patient_introuvable' => true
	        ]);
	    }

	    DB::beginTransaction();

	    try {

	        /*
	        |--------------------------------------------------------------------------
	        | Numéros
	        |--------------------------------------------------------------------------
	        */

	        $numFacture = $numfac = $this->matriculeService->generate( 
                	table: 'consultation', 
                	column: 'numfac', 
                	prefix: 'FCE', 
                	length: 6 
                );


	        /*
	        |--------------------------------------------------------------------------
	        | Montants
	        |--------------------------------------------------------------------------
	        */

	        $montantTotal = (int) str_replace(
	            '.',
	            '',
	            $request->montantT ?? 0
	        );

	        $montantPatient = (int) str_replace(
	            '.',
	            '',
	            $request->montantP ?? 0
	        );

	        $montantAssurance = (int) str_replace(
	            '.',
	            '',
	            $request->montantA ?? 0
	        );

	        $remise = (int) str_replace(
	            '.',
	            '',
	            $request->remise ?? 0
	        );


	        /*
	        |--------------------------------------------------------------------------
	        | Création laboratoire
	        |--------------------------------------------------------------------------
	        */

	        $laboratoireId = DB::table('laboratoires')
	            ->insertGetId([

	                'patient_id' =>
	                    $request->patient_id,

	                'code_assurance' =>
	                    $patient->codeassurance,

	                'code_societe' =>
	                    $patient->codesocieteassure,

	                'numfac' =>
	                    $numFacture,

	                'medecin' =>
	                    $request->medecin,

	                'montant_total' =>
	                    $montantTotal,

	                'montant_patient' =>
	                    $montantPatient,

	                'montant_assurance' =>
	                    $montantAssurance,

	                'remise' =>
	                    $remise,

	                'taux' =>
	                    $request->taux ?? 0,

	                'periode' =>
	                    $request->periode,

	                'numcode' =>
	                    $request->numcode,

	                'numhosp' =>
	                    $request->numhosp,

	                'rensg' =>
	                    $request->rensg,

	                'created_at' =>
	                    now(),

	                'updated_at' =>
	                    now(),
	            ]);

	        if (!$laboratoireId) {
	            throw new Exception(
	                'Erreur lors de la création du laboratoire'
	            );
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Création des détails
	        |--------------------------------------------------------------------------
	        */

	        foreach ($selections as $value) {

	            $montant = (int) str_replace(
	                '.',
	                '',
	                $value['montant'] ?? 0
	            );

	            /*
	             * Si le prélèvement est envoyé au niveau
	             * de chaque examen.
	             */
	            $prelevement = (int) str_replace(
	                '.',
	                '',
	                $value['prelevement'] ?? 0
	            );

	            $detailInsert = DB::table('laboratoire_details')
	                ->insert([

	                    'laboratoire_id' =>
	                        $laboratoireId,

	                    'examen_id' =>
	                        $value['id'] ?? null,

	                    'code' =>
	                        $value['code'] ?? '',

	                    'famille' =>
	                        $value['famille'] ?? null,

	                    'examen' =>
	                        $value['examen'] ?? '',

	                    'cotation' =>
	                        $value['cotation'] ?? 0,

	                    'valeur' =>
	                        $value['valeur'] ?? 0,

	                    'montant' =>
	                        $montant,

	                    'prelevement' =>
	                        $prelevement,

	                    'assurance' =>
	                        ($value['assurance'] ?? 'non') === 'oui',

	                    'created_at' =>
	                        now(),

	                    'updated_at' =>
	                        now(),
	                ]);

	            if ($detailInsert === 0) {
	                throw new Exception(
	                    'Erreur lors de l\'insertion du détail laboratoire'
	                );
	            }
	        }


	        /*
	        |--------------------------------------------------------------------------
	        | Facture
	        |--------------------------------------------------------------------------
	        */

	        $facturesInserted = DB::table('factures')
	            ->insert([

	                'numfac' =>
	                    $numFacture,

	                'numhospit' =>
	                    $request->numhosp,

	                'idenregistremetpatient' =>
	                    $request->patient_id,

	                'montanttotal' =>
	                    $montantTotal,

	                'remise' =>
	                    $remise,

	                'type_remise' =>
	                    0,

	                'calcul_applique' =>
	                    0,

	                'taux_applique' =>
	                    $request->taux ?? 0,

	                'montant_ass' =>
	                    $montantAssurance,

	                'montant_pat' =>
	                    $montantPatient,

	                'montantregle_ass' =>
	                    0,

	                'montantregle_pat' =>
	                    0,

	                'montantpat_verser' =>
	                    0,

	                'montantpat_remis' =>
	                    0,

	                'montantreste_ass' =>
	                    $montantAssurance,

	                'montantreste_pat' =>
	                    $montantPatient,

	                'solde_ass' =>
	                    0,

	                'solde_pat' =>
	                    0,

	                'codeassurance' =>
	                    $patient->codeassurance,

	                'datefacture' =>
	                    now(),

	                'type_facture' =>
	                    4,

	                'timbre_fiscal' =>
	                    0,

	                'a_encaisser' =>
	                    0,

	                'datereglt_pat' =>
	                    null,

	                'numrecu' =>
	                    null,
	            ]);

	        if ($facturesInserted === 0) {
	            throw new Exception(
	                'Erreur lors de l\'insertion dans la table factures'
	            );
	        }

	        /*
	        |--------------------------------------------------------------------------
	        | Facturation hospitalisation
	        |--------------------------------------------------------------------------
	        */

	        if ($request->numhosp !== null) {

	            /*
	             * B = Biologie
	             * Z / autre = Imagerie
	             */

	            $type = $request->acte_id === 'B'
	                ? 3
	                : 4;


	            $facHosInsert = DB::table('facturation_hospit')
	                ->insert([

	                    'numpchr' =>
	                        $request->numhosp,

	                    'numfac' =>
	                        $numFacture,

	                    'idgarhospit' =>
	                        $type,

	                    'qte' =>
	                        1,

	                    'pu' =>
	                        $montantTotal,

	                    'montgaran' =>
	                        $montantTotal,

	                    'montextra' =>
	                        0,

	                    'montaccorde' =>
	                        $montantAssurance,

	                    'montrefus' =>
	                        $montantPatient,

	                    'traiter' =>
	                        0,
	                ]);

	            if ($facHosInsert === 0) {
	                throw new Exception(
	                    'Erreur lors de l\'insertion dans la table facturation_hospit'
	                );
	            }


	            /*
	            |--------------------------------------------------------------------------
	            | Facture principale de l'hospitalisation
	            |--------------------------------------------------------------------------
	            */

	            $factureHosp = DB::table('admission')
	                ->join(
	                    'factures',
	                    'factures.numfac',
	                    '=',
	                    'admission.numfachospit'
	                )
	                ->where(
	                    'admission.numhospit',
	                    $request->numhosp
	                )
	                ->select(
	                    'factures.numfac',
	                    'factures.montanttotal',
	                    'factures.montant_ass',
	                    'factures.montant_pat',
	                    'factures.montantreste_ass',
	                    'factures.montantreste_pat'
	                )
	                ->first();


	            if (!$factureHosp) {
	                throw new Exception(
	                    'Facture hospitalisation introuvable'
	                );
	            }


	            /*
	            |--------------------------------------------------------------------------
	            | Recalcul des montants hospitalisation
	            |--------------------------------------------------------------------------
	            */

	            $totalNew =
	                $montantTotal +
	                (int) $factureHosp->montanttotal;

	            $partAssuranceNew =
	                $montantAssurance +
	                (int) $factureHosp->montant_ass;

	            $partPatientNew =
	                $montantPatient +
	                (int) $factureHosp->montant_pat;

	            $partAssuranceResteNew =
	                $montantAssurance +
	                (int) $factureHosp->montantreste_ass;

	            $partPatientResteNew =
	                $montantPatient +
	                (int) $factureHosp->montantreste_pat;


	            $factureUpdate = DB::table('factures')
	                ->where(
	                    'numfac',
	                    $factureHosp->numfac
	                )
	                ->update([

	                    'montanttotal' =>
	                        $totalNew,

	                    'montant_ass' =>
	                        $partAssuranceNew,

	                    'montant_pat' =>
	                        $partPatientNew,

	                    'montantreste_ass' =>
	                        $partAssuranceResteNew,

	                    'montantreste_pat' =>
	                        $partPatientResteNew,

	                    'updated_at' =>
	                        now(),
	                ]);


	            if ($factureUpdate === 0) {
	                throw new Exception(
	                    'Erreur lors de la mise à jour de la facture hospitalisation'
	                );
	            }
	        }

	        DB::commit();

	        return response()->json([

	            'success' =>
	                true,

	            'laboratoire_id' =>
	                $laboratoireId,

	            'numfac' =>
	                $numFacture,
	        ]);

	    } catch (Exception $e) {

	        DB::rollBack();

	        return response()->json([

	            'error' =>
	                true,

	            'message' =>
	                $e->getMessage(),
	        ]);
	    }
	}

	public function garanties(Request $request)
	{
	    /*
	    |--------------------------------------------------------------------------
	    | Paramètres
	    |--------------------------------------------------------------------------
	    */

	    $page = max(
	        (int) $request->input('page', 1),
	        1
	    );

	    $perPage = min(
	        max((int) $request->input('per_page', 15), 1),
	        100
	    );

	    $search = trim(
	        $request->input('search', '')
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Requête principale
	    |--------------------------------------------------------------------------
	    */

	    $query = DB::table('examen')

	        ->leftJoin(
	            'famille_examen',
	            'examen.codfamexam',
	            '=',
	            'famille_examen.codfamexam'
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | Recherche
	    |--------------------------------------------------------------------------
	    */

	    if ($search !== '') {

	        $query->where(function ($q) use ($search) {

	            $q->where(
	                'examen.denomination',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'examen.numexam',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'examen.codfamexam',
	                'LIKE',
	                "%{$search}%"
	            )

	            ->orWhere(
	                'famille_examen.nomfamexam',
	                'LIKE',
	                "%{$search}%"
	            );

	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | Sélection
	    |--------------------------------------------------------------------------
	    */

	    $query->select([

	        'examen.*',

	        'famille_examen.nomfamexam as type',

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Tri
	    |--------------------------------------------------------------------------
	    */

	    $query->orderBy(
	        'examen.denomination',
	        'asc'
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination
	    |--------------------------------------------------------------------------
	    */

	    $result = $this->paginationService->paginate(

	        query: $query,

	        countTable: 'examen',

	        page: $page,

	        perPage: $perPage,

	        countColumn: 'examen.numexam'
	    );


	    /*
	    |--------------------------------------------------------------------------
	    | Réponse
	    |--------------------------------------------------------------------------
	    */

	    return response()->json(
	        $result
	    );
	}

	public function garantiePrix(Request $request, $id)
	{
	    $id = $request->input('id') ?? $id;

	    $examen = DB::table('examen')
	        ->where('numexam', $id)
	        ->select('examen.*')
	        ->first();

	    // Examen inexistant
	    if (!$examen) {
	        return response()->json([]);
	    }

	    $prix = collect();

	    /*
	    |--------------------------------------------------------------------------
	    | Garantie personnalisée
	    |--------------------------------------------------------------------------
	    */
	    if ($examen->codfamexam === 'Y') {

	        if (!empty($examen->codgaran)) {

	            $prix = DB::table('tarifs')
	                ->join(
	                    'assurance',
	                    'assurance.codeassurance',
	                    '=',
	                    'tarifs.codeassurance'
	                )
	                ->where('tarifs.codgaran', $examen->codgaran)
	                ->select(
	                    'tarifs.*',
	                    'assurance.libelleassurance as assurance'
	                )
	                ->get();
	        }

	    /*
	    |--------------------------------------------------------------------------
	    | Garantie B / Z
	    |--------------------------------------------------------------------------
	    */
	    } elseif (
	        $examen->codfamexam === 'B' ||
	        $examen->codfamexam === 'Z'
	    ) {

	        if (!empty($examen->cot) && $examen->cot != 0) {

	            $prix = DB::table('tarifs')
	                ->join(
	                    'assurance',
	                    'assurance.codeassurance',
	                    '=',
	                    'tarifs.codeassurance'
	                )
	                ->where('tarifs.codgaran', $examen->codfamexam)
	                ->select(
	                    'tarifs.*',
	                    'assurance.libelleassurance as assurance'
	                )
	                ->get();

	            // Application de la cotation
	            $prix->each(function ($value) use ($examen) {

	                $value->montjour =
	                    $examen->cot * $value->montjour;

	                $value->montnuit =
	                    $examen->cot * $value->montnuit;

	                $value->montferie =
	                    $examen->cot * $value->montferie;
	            });
	        }
	    }

	    return response()->json(['prix' => $prix->values()]);
	}

}