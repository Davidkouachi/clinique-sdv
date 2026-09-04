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

class SoinsController extends Controller
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

	    $query = DB::table('soins_medicaux')

	        ->join(
	            'factures',
	            'soins_medicaux.numfac_soins',
	            '=',
	            'factures.numfac'
	        )

	        ->join(
	            'patient',
	            'soins_medicaux.idenregistremetpatient',
	            '=',
	            'patient.idenregistremetpatient'
	        );


	    /*
	    |--------------------------------------------------------------------------
	    | Filtre dates
	    |--------------------------------------------------------------------------
	    */

	    if (!empty($date1)) {

	        if (!empty($date2)) {

	            $query->whereBetween(
	                'soins_medicaux.date_soin',
	                [
	                    $date1,
	                    $date2
	                ]
	            );

	        } else {

	            $query->where(
	                'soins_medicaux.date_soin',
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
	                'soins_medicaux.numfac_soins',
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
	            );
	        });
	    }


	    /*
	    |--------------------------------------------------------------------------
	    | SELECT
	    |--------------------------------------------------------------------------
	    */

	    $query->select([

	        'soins_medicaux.id_soins',

	        'soins_medicaux.montant_total',

	        'soins_medicaux.date_soin',

	        'soins_medicaux.numfac_soins',

	        'soins_medicaux.part_assurance as partassurance',

	        'soins_medicaux.ticket_moderateur as partpatient',

	        'soins_medicaux.taux_couverture',

	        'factures.remise',

	        'patient.numdossier',

	        'patient.nomprenomspatient as nom_patient',

	        'patient.telpatient as tel_patient',

	        'patient.assure',

	        'factures.montantregle_pat as montant_regle',

	        DB::raw('(
		        SELECT COUNT(*)
		        FROM soins_medicaux_itemsoins
		        WHERE soins_medicaux_itemsoins.id_soins = soins_medicaux.id_soins
		    ) as nbre_soins'),

		    DB::raw('(
		        SELECT COUNT(*)
		        FROM soins_medicaux_itemmedics
		        WHERE soins_medicaux_itemmedics.id_soins = soins_medicaux.id_soins
		    ) as nbre_produits'),

	    ]);


	    /*
	    |--------------------------------------------------------------------------
	    | Pagination Laravel
	    |--------------------------------------------------------------------------
	    */

	    $result = $query
	        ->orderBy(
	            'soins_medicaux.date_soin',
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
        $facture = DB::table('soins_medicaux')
            ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
            ->leftJoin('societeassure', 'patient.codesocieteassure', '=', 'societeassure.codesocieteassure')
            ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
            ->leftJoin('assurance', 'patient.codeassurance', '=', 'assurance.codeassurance')
            ->leftJoin('filiation', 'patient.codefiliation', '=', 'filiation.codefiliation')
            ->join('factures', 'soins_medicaux.numfac_soins', '=', 'factures.numfac')
            ->where('soins_medicaux.id_soins', '=', $code)
            ->select([
                'soins_medicaux.id_soins as id_soins',
                'soins_medicaux.montant_total as montant',
                'soins_medicaux.date_soin as date',
                'soins_medicaux.numfac_soins as numfac',
                'soins_medicaux.numbon',
                'soins_medicaux.numhospit',
                'soins_medicaux.renseignement_clinique as renseigclini',
                'patient.idenregistremetpatient as idenregistremetpatient',
                'patient.numdossier as numdossier',
                'patient.nomprenomspatient as nom_patient',
                'patient.telpatient as tel_patient',
                'patient.assure as assure',
                'patient.datenaispatient as datenais',
                'patient.telpatient as telpatient',
                'patient.matriculeassure as matriculeassure',
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

                DB::raw('(
		        SELECT COUNT(*)
			        FROM soins_medicaux_itemsoins
			        WHERE soins_medicaux_itemsoins.id_soins = soins_medicaux.id_soins
			    ) as nbre_soins'),

			    DB::raw('(
			        SELECT COUNT(*)
			        FROM soins_medicaux_itemmedics
			        WHERE soins_medicaux_itemmedics.id_soins = soins_medicaux.id_soins
			    ) as nbre_produits'),
            ])
            ->first();

        $soins = collect();
        $produits = collect();

	    if ($facture) {

	        $produits = DB::table('soins_medicaux_itemmedics')

	            ->where(
	                'id_soins',
	                $facture->id_soins
	            )

	            ->select([

	            	'id_detail_medics as id',

	                'name',

	                'price',

	                'qte',

	                'total',

	                'assure',

	            ])

	            ->orderBy('id')

	            ->get();

	        $soins = DB::table('soins_medicaux_itemsoins')

	            ->where(
	                'id_soins',
	                $facture->id_soins
	            )

	            ->select([

	            	'id_detail_soins as id',

	            	'code_soins',

	                'libelle_soins as name',

	                'price',

	                'qte',

	                'total',

	                'assure',

	            ])

	            ->orderBy('id')

	            ->get();

	    }

        return response()->json([
        	'facture' => $facture,
        	'soins' => $soins,
        	'produits' => $produits

        ]);
    }

	public function delete($id)
	{
	    DB::beginTransaction();

	    try {

	        // Vérifier que le soin existe
	        $soins = DB::table('soins_medicaux')
	            ->where('id_soins', $id)
	            ->first();

	        if (!$soins) {

	            DB::rollBack();

	            return response()->json([
	                'warn' => true,
	                'message' => 'Soins ambulatoires introuvable.'
	            ]);
	        }


	        // Vérifier que la facture existe
	        $facture = DB::table('factures')
	            ->where('numfac', $soins->numfac_soins)
	            ->first();

	        if (!$facture) {

	            DB::rollBack();

	            return response()->json([
	                'warn' => true,
	                'message' => 'Facture introuvable.'
	            ]);
	        }


	        // Supprimer les soins
	        DB::table('soins_medicaux')
	            ->where('id_soins', $id)
	            ->delete();


	        // Supprimer les détails des soins
	        DB::table('soins_medicaux_itemsoins')
	            ->where('id_soins', $id)
	            ->delete();


	        // Supprimer les produits / médicaments
	        DB::table('soins_medicaux_itemmedics')
	            ->where('id_soins', $id)
	            ->delete();


	        // Supprimer la facture
	        DB::table('factures')
	            ->where('numfac', $soins->numfac_soins)
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
    $selectionsSoins = $request->input('selectionsSoins');
    $selectionsProduits = $request->input('selectionsProduits');


    /*
    |--------------------------------------------------------------------------
    | Vérification des sélections
    |--------------------------------------------------------------------------
    */

    if (
        (!is_array($selectionsSoins) || empty($selectionsSoins)) &&
        (!is_array($selectionsProduits) || empty($selectionsProduits))
    ) {
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

        if (
            $verf->idenregistremetpatient !=
            $request->patient_id
        ) {

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
        | Numéro facture
        |--------------------------------------------------------------------------
        */

        $numFacture = $this->matriculeService->generate(
            table: 'soins_medicaux',
            column: 'numfac_soins',
            prefix: 'FCS',
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
        | Création des soins
        |--------------------------------------------------------------------------
        */

        $soinsId = DB::table('soins_medicaux')
            ->insertGetId([

                'idenregistremetpatient' =>
                    $request->patient_id,

                'codeassurance' =>
                    $patient->codeassurance,

                'codesocieteassure' =>
                    $patient->codesocieteassure,

                'numfac_soins' =>
                    $numFacture,

                'medecin' =>
                    $request->medecin,

                'montant_total' =>
                    $montantTotal,

                'ticket_moderateur' =>
                    $montantPatient,

                'part_assurance' =>
                    $montantAssurance,

                'taux_couverture' =>
                    $request->taux ?? 0,

                'numbon' =>
                    $request->numcode,

                'numhospit' =>
                    $request->numhosp,

                'renseignement_clinique' =>
                    $request->rensg,

                'created_at' =>
                    now(),

                'updated_at' =>
                    now(),
            ]);


        if (!$soinsId) {

            throw new \Exception(
                'Erreur lors de la création des soins.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Détails des soins
        |--------------------------------------------------------------------------
        */

        if (is_array($selectionsSoins)) {

            foreach ($selectionsSoins as $value) {

                $prix = (int) str_replace(
                    '.',
                    '',
                    $value['prix'] ?? 0
                );

                $quantite = max(
                    1,
                    (int) ($value['quantite'] ?? 1)
                );

                $montant = (int) str_replace(
                    '.',
                    '',
                    $value['montant'] ?? ($prix * $quantite)
                );


                $detailInsert =
                    DB::table('soins_medicaux_itemsoins')
                        ->insert([

                            'id_soins' =>
                                $soinsId,

                            'code_soins' =>
                                $value['id'] ?? null,

                            'libelle_soins' =>
                                $value['soins'] ?? '',

                            'price' =>
                                $prix,

                            'qte' =>
                                $quantite,

                            'total' =>
                                $montant,

                            'assure' =>
                                ($value['assurance'] ?? 'non') === 'oui',

                            'created_at' =>
                                now(),

                            'updated_at' =>
                                now(),
                        ]);


                if ($detailInsert === 0) {

                    throw new \Exception(
                        'Erreur lors de l\'insertion du détail soin.'
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Détails des produits
        |--------------------------------------------------------------------------
        */

        if (is_array($selectionsProduits)) {

            foreach ($selectionsProduits as $value) {

                $prix = (int) str_replace(
                    '.',
                    '',
                    $value['prix'] ?? 0
                );

                $quantite = max(
                    1,
                    (int) ($value['quantite'] ?? 1)
                );

                $montant = (int) str_replace(
                    '.',
                    '',
                    $value['montant'] ?? ($prix * $quantite)
                );

                $montantAssuranceProduit =
                    (int) str_replace(
                        '.',
                        '',
                        $value['montant_assurance'] ?? 0
                    );

                $montantPatientProduit =
                    (int) str_replace(
                        '.',
                        '',
                        $value['montant_patient'] ?? $montant
                    );


                $detailInsert =
                    DB::table('soins_medicaux_itemmedics')
                        ->insert([

                            'id_soins' =>
                                $soinsId,

                            'medicine_id' =>
                                $value['medicine_id'] ?? null,

                            'name' =>
                                $value['produit'] ?? '',

                            'price' =>
                                $prix,

                            'qte' =>
                                $quantite,

                            'total' =>
                                $montant,

                            'assure' =>
                                ($value['assurance'] ?? 'non') === 'oui',

                            'created_at' =>
                                now(),

                            'updated_at' =>
                                now(),
                        ]);


                if ($detailInsert === 0) {

                    throw new \Exception(
                        'Erreur lors de insertion du produit.'
                    );
                }
            }
        }


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
                    5,

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

            throw new \Exception(
                'Erreur lors de l\'insertion dans la table factures.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Facturation hospitalisation
        |--------------------------------------------------------------------------
        */

        if ($request->numhosp !== null) {

            $facHosInsert =
                DB::table('facturation_hospit')
                    ->insert([

                        'numpchr' =>
                            $request->numhosp,

                        'numfac' =>
                            $numFacture,

                        /*
                         * Type à adapter selon ton référentiel.
                         * Ici 5 = soins médicaux.
                         */
                        'idgarhospit' =>
                            5,

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

                throw new \Exception(
                    'Erreur lors de l\'insertion dans la table facturation_hospit.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Facture principale hospitalisation
            |--------------------------------------------------------------------------
            */

            $factureHosp =
                DB::table('admission')
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

                throw new \Exception(
                    'Facture hospitalisation introuvable.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Recalcul hospitalisation
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


            $factureUpdate =
                DB::table('factures')
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

                throw new \Exception(
                    'Erreur lors de la mise à jour de la facture hospitalisation.'
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validation finale
        |--------------------------------------------------------------------------
        */

        DB::commit();


        return response()->json([

            'success' =>
                true,

            'soins_id' =>
                $soinsId,

            'numfac' =>
                $numFacture,

        ]);


    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([

            'error' =>
                true,

            'message' =>
                $e->getMessage(),

        ], 500);
    }
}

}