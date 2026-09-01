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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// use App\Models\assurance;
// use App\Models\taux;
// use App\Models\societe;
// use App\Models\patient;
use App\Models\chambre;
use App\Models\lit;
// use App\Models\acte;
// use App\Models\typeacte;
use App\Models\User;
use App\Models\role;
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
use App\Models\prelevement;
use App\Models\joursemaine;
use App\Models\rdvpatient;
use App\Models\programmemedecin;
use App\Models\depotfacture;
// use App\Models\caisse;
// use App\Models\historiquecaisse type_garantie_new ope_caisse_new;
use App\Models\portecaisse;

class ApiinsertController extends Controller
{

    private function formatWithPeriods($number) {
        return number_format($number, 0, '', '.');
    }
    private function generateUniqueMatriculeEmploye()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('employes')->where('matricule', '=', 'P'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeMedecin()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('medecin')->where('codemedecin', '=', 'MED'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeAssurance()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('assurance')->where('codeassurance', '=', 'ASS'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculePatient()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('patient')->where('idenregistremetpatient', '=', 'P'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeDossierDC()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('dossierpatient')->where('numdossier', '=', 'DC'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeDossierDH()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('dossierpatient')->where('numdossier', '=', 'DH'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeSpecialite()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('specialitemed')->where('codespecialitemed', '=', 'SP'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeCaisseEntrerSortie()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('caisse')->where('nopiece', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueMatriculeNumRecu()
    {
        do {
            // Génère une chaîne aléatoire de 6 caractères (majuscule, minuscule, chiffres)
            $matricule = Str::random(6);
        } while (DB::table('journal')->where('numrecu', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureCons()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('consultation')->where('numfac', '=', 'FCE'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureSoinsAmbulatoire()
    {
        do {
            $matricule = random_int(100000, 999999);
        } while (DB::table('soins_medicaux')->where('numfac_soins', '=', 'FCS'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueNumExamen()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('examen')->where('numexam', '=', $matricule)->exists());

        return $matricule;
    }
    private function generateUniqueIdBiologie()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('idtestlaboimagerie', '=', 'BIO-'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueIdImagerie()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('idtestlaboimagerie', '=', 'IMG-'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureExamen()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('testlaboimagerie')->where('numfacbul', '=', 'FCB'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureHos()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('admission')->where('numfachospit', '=', 'FCH'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueIdHos()
    {
        do {
            $matricule = random_int(1000000, 9999999);
        } while (DB::table('admission')->where('numfachospit', '=', 'HOSPIT'.$matricule)->exists());

        return $matricule;
    }
    private function generateUniqueFactureOrders()
    {
        do {
            $matricule = random_int(10000, 99999);
        } while (DB::table('orders')->where('numfac', '=', 'FPH-'.$matricule)->exists());

        return $matricule;
    }















// Auth::user()->login









    public function societe_new(Request $request)
    {

        $verf = DB::table('societeassure')->where('nomsocieteassure', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        DB::beginTransaction();

            try {

                $societeInserted = DB::table('societeassure')->insert([
                    'nomsocieteassure' => $request->nom,
                    'codeassurance' => $request->codeassurance,
                    'codeassureur' => $request->assureur_id,
                ]);

                if ($societeInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table societeassure');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }  
    }

    public function assurance_new(Request $request)
    {

        $verifications = [
            'nom' => $request->nom,
            'tel' => $request->tel,
            'email' => $request->email,
            'fax' => $request->fax ?? null,
        ];

        $Exist = DB::table('assurance')->where(function ($query) use ($verifications) {
            $query->where('libelleassurance', $verifications['nom'])
                    ->where('telassurance', $verifications['tel'])
                    ->where('emailassurance', $verifications['email'])
                    ->orWhere(function ($query) use ($verifications) {
                        if (!is_null($verifications['fax'])) {
                            $query->where('faxassurance', $verifications['fax']);
                        }
                    });
        })->first();

        if ($Exist) {
            if ($Exist->libelleassurance === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            } elseif ($Exist->emailassurance === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            } elseif ($Exist->telassurance === $verifications['tel']) {
                return response()->json(['tel_existe' => true]);
            } elseif (!is_null($verifications['fax']) && $Exist->faxassurance === $verifications['fax']) {
                return response()->json(['fax_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeAssurance();

                $assuranceInserted = DB::table('assurance')->insert([
                    'codeassurance' => 'ASS'.$matricule,
                    'libelleassurance' => $request->nom,
                    'telassurance' => $request->tel,
                    'faxassurance' => $request->fax,
                    'emailassurance' => $request->email,
                    'adrassurance' => $request->adresse,
                    'situationgeo' => $request->carte,
                    'description' => $request->desc,
                ]);

                if ($assuranceInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table assurance');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function assureur_new(Request $request)
    {

        $verifications = [
            'nom' => $request->nom,
        ];

        $Exist = DB::table('assureur')->where(function ($query) use ($verifications) {
            $query->where('libelle_assureur', $verifications['nom']);
        })->first();

        if ($Exist) {
            if ($Exist->libelle_assureur === $verifications['nom']) {
                return response()->json(['nom_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $assureurInserted = DB::table('assureur')->insert([
                    'libelle_assureur' => $request->nom,
                ]);

                if ($assureurInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table assureur');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function patient_new(Request $request)
    {

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculePatient();

                if ($request->assurer === '0') {
                    $codeassurance = 'NONAS';
                    $codefiliation = 0;
                    $matriculeassure = null;
                    $codesocieteassure = 0;
                    $idtauxcouv = 0;
                } else if ($request->assurer === '1') {
                    $codeassurance = $request->assurance_id ?? 'NONAS';
                    $codefiliation = $request->filiation;
                    $matriculeassure = $request->matricule_assurance;
                    $codesocieteassure = $request->societe_id;
                    $idtauxcouv = $request->taux_id;
                }

                $insertData_patient = [
                    'idenregistremetpatient' => 'P'.$matricule,
                    'idenregistrementhopital' => '1',
                    'numeroregistre' => '1',
                    'dateenregistrement' => now(),
                    'civilite' => '0',
                    'nompatient' => $request->nom,
                    'prenomspatient' => $request->prenom,
                    'nomprenomspatient' => $request->nom.' '.$request->prenom,
                    'datenaispatient' => $request->datenais,
                    'sexe' => $request->sexe,
                    'adressepatient' => null,
                    'assure' => $request->assurer,
                    'telpatient' => $request->tel,
                    'telpatient_2' => $request->tel2,
                    'telurgence_1' => $request->telu,
                    'telurgence_2' => $request->telu2,
                    'nomurgence' => $request->nomu,
                    'lieuderesidencepat' => $request->residence,
                    'codeassurance' => $codeassurance,
                    'codefiliation' => $codefiliation,
                    'matriculeassure' => $matriculeassure,
                    'codesocieteassure' => $codesocieteassure,
                    'idtauxcouv' => $idtauxcouv,
                ];

                $patientInserted = DB::table('patient')->insert($insertData_patient);

                if ($patientInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table patient');
                }

                $verf_dossierDC = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', 'P'.$matricule)
                ->where('codetypedossier', '=', 'DC')
                ->exists();

                if (!$verf_dossierDC) {

                    $numdossier_new = $this->generateUniqueMatriculeDossierDC();

                    $dossierPatientInserted = DB::table('dossierpatient')->insert([
                        'numdossier' => 'DC'.$numdossier_new,
                        'idenregistremetpatient' => 'P'.$matricule,
                        'datecrea' => now(),
                        'codetypedossier' => 'DC',
                    ]);

                    if ($dossierPatientInserted === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table dossierpatient');
                    }
                }

                $verf_dossierDH = DB::table('dossierpatient')
                ->where('idenregistremetpatient', '=', 'P'.$matricule)
                ->where('codetypedossier', '=', 'DH')
                ->exists();

                if (!$verf_dossierDH) {

                    $numdossierH_new = $this->generateUniqueMatriculeDossierDH();

                    $dossierPatientInsertedH = DB::table('dossierpatient')->insert([
                        'numdossier' => 'DH'.$numdossierH_new,
                        'idenregistremetpatient' => 'P'.$matricule,
                        'datecrea' => now(),
                        'codetypedossier' => 'DH',
                    ]);

                    if ($dossierPatientInsertedH === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table dossierpatient');
                    }
                }

                $assuranceChange = DB::table('historique_assurance')->insert([
                    'idenregistremetpatient' => 'P'.$matricule,
                    'assure' => $request->assurer,
                    'codefiliation' => $codefiliation,
                    'codeassurance' => $codeassurance,
                    'matriculeassure' => $matriculeassure,
                    'idtauxcouv' => $idtauxcouv,
                    'codesocieteassure' => $codesocieteassure,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($assuranceChange === 0) {
                    throw new Exception('Erreur lors de la mise à jour dans la table historique_assurance');
                }

                
                DB::commit();
                return response()->json(['success' => true, 'id' => 'P'.$matricule]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function api_chambre_new(Request $request)
    {
        $verf = DB::table('chambres')->where('code', '=', $request->num_chambre)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $chambreInsert = DB::table('chambres')->insert([
            'code' => $request->num_chambre,
            'nbre_lit' => $request->nbre_lit,
            'prix' => str_replace('.', '', $request->prix),
            'statut' => 'indisponible',
            'created_at' => now(),
        ]);

        if ($chambreInsert == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);
    }

    public function api_lit_new(Request $request)
    {
        $verf = DB::table('lits')->where('code', '=', $request->num_lit)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $nbre = DB::table('chambres')->where('id', '=', $request->chambre_id)->first();
        $count = DB::table('lits')->where('chambre_id', '=', $request->chambre_id)->count();

        if ($nbre->nbre_lit <= $count) {
            return response()->json(['nbre' => true]);
        }

        DB::beginTransaction();

        try {

            $litInsert = DB::table('lits')->insert([
                'code' => $request->num_lit,
                'type' => $request->type,
                'chambre_id' => $request->chambre_id,
                'statut' => 'disponible',
                'created_at' => now(),
            ]);

            if ($litInsert == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table lits');
            }

            $updateData_chambre =[
                'statut' => 'disponible',
                'updated_at' => now(),
            ];

            $chambreUpdate = DB::table('chambres')
                                ->where('id', '=', $request->chambre_id)
                                ->update($updateData_chambre);

            if ($chambreUpdate == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table chambres');
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_medecin(Request $request)
    {
        $verifications = [
            'tel' => $request->tel ?? null,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('medecin')->where(function ($query) use ($verifications) {
            $query->where('contact', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->first();


        if ($Exist) {
            if ($Exist->tel === $verifications['tel']) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeMedecin();

                $specialite = DB::table('specialitemed')->where('codespecialitemed', '=', $request->specialite_id)->first();

                if (!$specialite) {
                    throw new Exception('Spécialité introuvable');
                }

                $medecinInserted = DB::table('medecin')->insert([
                    'codemedecin' => 'MED'.$matricule,
                    'titremed' => 'Dr',
                    'nommedecin' => $request->nom,
                    'prenomsmedecin' => $request->prenom,
                    'nomprenomsmed' => 'Dr '.$request->nom.' '.$request->prenom ,
                    'codespecialitemed' => $specialite->codespecialitemed,
                    'numordremed' => $request->num,
                    'contact' => $request->tel,
                    'dateservice' => $request->dateservice,
                    'email' => $request->email,
                ]);

                if ($medecinInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table employes');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_consultation(Request $request)
    {

        DB::beginTransaction();

            try {

                $numfac = $this->generateUniqueFactureCons();
                $recu = $this->generateUniqueMatriculeNumRecu();

                $codeassurance = DB::table('patient')
                    ->where('idenregistremetpatient', '=', $request->id_patient)
                    ->select('codeassurance','codesocieteassure')
                    ->first();

                $consultationInserted = DB::table('consultation')->insert([
                    'idenregistremetpatient' => $request->id_patient,
                    'codeassurance' => $codeassurance->codeassurance,
                    'codesocieteassure' => $codeassurance->codesocieteassure,
                    'numbon' => $request->mumcode,
                    'montant' => str_replace('.', '', $request->total),
                    'taux' => $request->patient_taux,
                    'ticketmod' => str_replace('.', '', $request->montant_patient),
                    'partassurance' => str_replace('.', '', $request->montant_assurance),
                    'codemedecin' => $request->user_id,
                    'codeacte' => $request->typeacte_id,
                    'regle' => 0,
                    'date' => now(),
                    'facimprime' => 0,
                    'numfac' => 'FCE'.$numfac,
                ]);

                if ($consultationInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table consultation');
                }

                // if ($request->appliq_remise == 'patient') {
                //     $type_remise = 1
                // }

                $timbre = 0;
                $montant_patient = (int) str_replace('.', '', $request->montant_patient);

                // if ($montant_patient >= 5001 && $montant_patient <= 100000) {
                //     $timbre += 100;
                //     $montant_patient += 100;
                // } elseif ($montant_patient > 100000 && $montant_patient <= 500000) {
                //     $timbre += 500;
                //     $montant_patient += 500;
                // } elseif ($montant_patient > 500000 && $montant_patient <= 1000000) {
                //     $timbre += 1000;
                //     $montant_patient += 1000;
                // } elseif ($montant_patient > 1000000 && $montant_patient <= 5000000) {
                //     $timbre += 2000;
                //     $montant_patient += 2000;
                // } elseif ($montant_patient > 5000000) {
                //     $timbre += 2000;
                //     $montant_patient += 2000;
                // }

                if ($montant_patient == 0) {

                    $date_regle = now();
                    $numrecu_regle = 'REC'.$recu;
                } else if ($montant_patient > 0) {
                    $date_regle = null;
                    $numrecu_regle = null;
                }

                $facturesInserted = DB::table('factures')->insert([
                    'numfac' => 'FCE'.$numfac,
                    'idenregistremetpatient' => $request->id_patient,
                    'montanttotal' => str_replace('.', '', $request->total),
                    'remise' => str_replace('.', '', $request->taux_remise),
                    'type_remise' => 0,
                    'calcul_applique' => 0,
                    'taux_applique' => $request->patient_taux,
                    'montant_ass' => str_replace('.', '', $request->montant_assurance),
                    'montant_pat' => $montant_patient,
                    'montantregle_ass' => 0,
                    'montantregle_pat' => 0,
                    'montantpat_verser' => 0,
                    'montantpat_remis' => 0,
                    'montantreste_ass' => str_replace('.', '', $request->montant_assurance),
                    'montantreste_pat' => $montant_patient,
                    'solde_ass' => 0,
                    'solde_pat' => 0,
                    'codeassurance' => $request->codeassurance,
                    'datefacture' => now(),
                    'type_facture' => 1,
                    'timbre_fiscal' => $timbre,
                    'a_encaisser' => 0,
                    'datereglt_pat' => $date_regle,
                    'numrecu' => $numrecu_regle,
                ]);

                if ($facturesInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table factures');
                }

                if ($montant_patient == 0) {

                    // table caisse
                    $caisseInserted = DB::table('caisse')->insert([
                        'nopiece' => 'FCE'.$numfac,
                        'type' => 'entree',
                        'libelle' => 'Encaissement facture consultation ',
                        'montant' => 0,
                        'dateop' => now(),
                        'datecreat' => now(),
                        'login' => Auth::user()->login,
                        'annule' => 0,
                        'mail' => 0,
                    ]);

                    if ($caisseInserted === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table caisse');
                    }

                    $journalInserted = DB::table('journal')->insert([
                        'idenregistremetpatient' => $request->id_patient,
                        'date' => now(),
                        'numrecu' => 'REC'.$recu,
                        'montant_recu' => 0,
                        'numjournal' => $recu,
                        'numfac' => 'FCE'.$numfac,
                        'type_action' => 0,
                    ]);

                    if ($journalInserted === 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table journal');
                    }
                }

                // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_typeadmission(Request $request)
    {

        $verf = DB::table('typehospitalsation')
            ->where('idtypehospit', '=', $request->code)
            ->Orwhere('nomtypehospit', '=', $request->nom)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $typehospitInserted = DB::table('typehospitalsation')->insert([
            'idtypehospit' => $request->code,
            'nomtypehospit' => $request->nom,
        ]);

        if ($typehospitInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function new_natureadmission(Request $request)
    {
        $verf = DB::table('naturehospit')
            ->where('idnathospit', '=', $request->code)
            ->Orwhere('nomnaturehospit', '=', $request->nom)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $typehospitInserted = DB::table('naturehospit')->insert([
            'idnathospit' => $request->code,
            'nomnaturehospit' => $request->nom,
        ]);

        if ($typehospitInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function hosp_new(Request $request)
    {

        $numfac = 'FCH'.$this->generateUniqueFactureHos();

        $numId = 'HOSPIT'.$this->generateUniqueIdHos();

        $codeassurance = DB::table('patient')
            ->where('idenregistremetpatient', '=', $request->patient_id)
            ->select('codeassurance','codesocieteassure')
            ->first();

        DB::beginTransaction();

        try {

            $admissionInserted = DB::table('admission')->insert([
                'numhospit' => $numId,
                'idenregistremetpatient' => $request->patient_id,
                'codeassurance' => $codeassurance->codeassurance,
                'codesocieteassure' => $codeassurance->codesocieteassure,
                'codemedecin' => $request->medecin_id,
                'codetypehospit' => $request->id_typeadmission,
                'codenaturehospit' => $request->id_natureadmission,
                'dateentree' => $request->date_entrer,
                'datesortie' => $request->date_sortie,
                'nbredejrs' => $request->nbre_jour,
                'motifhospit' => $request->motif,
                'codechbre' => $request->id_chambre,
                'idtypelit' => $request->id_lit,
                'numfachospit' => $numfac,
                'numbon' => $request->numcode,
                'statut' => 'en cours',
                'created_at' => now(),
            ]);

            if ($admissionInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table admission');
            }

            $patient = DB::table('patient')
                ->leftJoin('tauxcouvertureassure', 'patient.idtauxcouv', '=', 'tauxcouvertureassure.idtauxcouv')
                ->where('patient.idenregistremetpatient', '=', $request->patient_id)
                ->select('patient.*','tauxcouvertureassure.valeurtaux as taux',)
                ->first();

            $patient->taux = $patient->taux ?? 0;
            
            $facturesInserted = DB::table('factures')->insert([
                'numfac' => $numfac,
                'idenregistremetpatient' => $request->patient_id,
                'montanttotal' => 0,
                'remise' => 0,
                'type_remise' => 0,
                'calcul_applique' => 0,
                'taux_applique' => $patient->taux,
                'montant_ass' => 0,
                'montant_pat' => 0,
                'montantregle_ass' => 0,
                'montantregle_pat' => 0,
                'montantpat_verser' => 0,
                'montantpat_remis' => 0,
                'montantreste_ass' => 0,
                'montantreste_pat' => 0,
                'solde_ass' => 0,
                'solde_pat' => 0,
                'codeassurance' => $patient->codeassurance,
                'datefacture' => now(),
                'type_facture' => 3,
                'timbre_fiscal' => 0,
                'a_encaisser' => 0,
            ]);

            if ($facturesInserted == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }   

            $updateData_statut_lit =[
                'statut' => 'indisponible',
                'updated_at' => now(),
            ];

            $statutLitUpdate = DB::table('lits')
                                ->where('id', '=', $request->id_lit)
                                ->update($updateData_statut_lit);

            if ($statutLitUpdate == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table lits');
            }


            // gestion disponibilite chambre
            $lit = DB::table('lits')->where('id', '=', $request->id_lit)->select('lits.*')->first();
            $chambre = DB::table('chambres')->where('id', '=', $lit->chambre_id)->select('chambres.*')->first();

            $nbre_lit = DB::table('lits')
                ->where('chambre_id', '=', $lit->chambre_id)
                ->where('statut', '=', 'indisponible')
                ->count();


            if ($chambre->nbre_lit == $nbre_lit) {
                
                $updateData_statut_chambre =[
                    'statut' => 'indisponible',
                    'updated_at' => now(),
                ];

                $statutChambreUpdate = DB::table('chambres')
                                ->where('id', '=', $lit->chambre_id)
                                ->update($updateData_statut_chambre);

                if ($statutChambreUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table chambres');
                }
            }



            DB::commit();
            return response()->json(['success' => true,]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_produit(Request $request)
    {
        $verf = DB::table('medicine')->where('name', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $produitInserted = DB::table('medicine')->insert([
            'name' => $request->nom,
            'medicine_category_id' => $request->categorieid,
            'price' => str_replace('.', '', $request->prix),
            'status' => $request->quantite,
        ]);

        if ($produitInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function add_soinshopital(Request $request, $id)
    {

        $selections = $request->input('selections');

        // Vérifier si les sélections sont bien un tableau
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        DB::beginTransaction();

        try {

            $facHosInsert = DB::table('facturation_hospit')->insert([
                'numpchr' => $id,
                'idgarhospit' => 2,
                'qte' => 1,
                'pu' => str_replace('.', '', $request->montantTotal),
                'montgaran' => str_replace('.', '', $request->montantTotal),
                'montextra' => 0,
                'montaccorde' => str_replace('.', '', $request->montantAssurance),
                'montrefus' => str_replace('.', '', $request->montantPatient),
                'traiter' => 0,
            ]);

            if ($facHosInsert == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table facturation_hospit');
            }

            $numfac_orders = 'FPH-'.$this->generateUniqueFactureOrders();

            $ordersID = DB::table('orders')->insertGetId([
                'numfac' => $numfac_orders,
                'idenregistremetpatient' => null,
                'date' => now(),
                'montant' => str_replace('.', '', $request->montantTotal),
                'taux' => str_replace('.', '', $request->taux),
                'ticketmod' => str_replace('.', '', $request->montantPatient),
                'partassurance' => str_replace('.', '', $request->montantAssurance),
                'remise' => 0,
                'user_id' => Auth::user()->login,
                'num_hospit' => $id,
            ]);

            if (!$ordersID) {
                throw new Exception('Erreur lors de l\'insertion dans la table orders');
            }

            foreach ($selections as $value) {

                $produit = DB::table('medicine')
                    ->where('medicine_id','=',$value['id'])
                    ->select('medicine.*')
                    ->first();

                $produitInsert = DB::table('orders_detail')->insert([
                    'order_id' => $ordersID,
                    'product_id' => $value['id'],
                    'qty' => $value['quantite'],
                    'rate' => $produit->price,
                    'amount' => str_replace('.', '', $value['montant']),
                    'date' => now(),
                ]);

                if ($produitInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table orders_detail');
                }

                $qte_new = (int) $produit->status - (int) $value['quantite'];

                $updateData_produit =[
                    'status' => $qte_new,
                    'updated_at' => now(),
                ];

                $produitUpdate = DB::table('medicine')
                                    ->where('medicine_id', '=', $value['id'])
                                    ->update($updateData_produit);

                if ($produitUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table medicine');
                }
            }

            $factures = DB::table('admission')
                ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
                ->where('admission.numhospit','=',$id)
                ->select(
                    'factures.numfac as numfac',
                    'factures.montanttotal as montanttotal',
                    'factures.montant_ass as montant_ass',
                    'factures.montant_pat as montant_pat',
                    'factures.montantreste_ass as montantreste_ass',
                    'factures.montantreste_pat as montantreste_pat',
                )
                ->first();

            $total_new = (int) str_replace('.', '', $request->montantTotal) + (int) $factures->montanttotal;
            $part_assurance_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montant_ass;
            $part_patient_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montant_pat;
            $part_assurance_reste_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montantreste_ass;
            $part_patient_reste_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montantreste_pat;

            $updateData_facture =[
                'montanttotal' => $total_new,
                'montant_ass' => $part_assurance_new,
                'montant_pat' => $part_patient_new,
                'montantreste_ass' => $part_assurance_reste_new,
                'montantreste_pat' => $part_patient_reste_new,
                'updated_at' => now(),
            ];

            $factureUpdate = DB::table('factures')
                                ->where('numfac', '=', $factures->numfac)
                                ->update($updateData_facture);

            if ($factureUpdate == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            // Si tout s'est bien passé, on commit les changements
            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function add_garantiehopital(Request $request, $id)
    {

        $selections = $request->input('selections');

        // Vérifier si les sélections sont bien un tableau
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        DB::beginTransaction();

        try {

            foreach ($selections as $value) {

                if ($request->utiliseAss == 'oui') {
                    
                    $montantT = $value['montant'];

                    $montantA = ($value['montant'] * $request->taux) / 100;

                    $montantP = $montantT - $montantA;

                } else {

                    $montantT = $value['montant'];

                    $montantA = 0;

                    $montantP = $value['montant'];

                }

               $facHosInsert = DB::table('facturation_hospit')->insert([
                    'numpchr' => $id,
                    'idgarhospit' => $value['id'],
                    'qte' => 1,
                    'pu' => $montantT,
                    'montgaran' => $montantT,
                    'montextra' => 0,
                    'montaccorde' => $montantA,
                    'montrefus' => $montantP,
                    'traiter' => 0,
                ]);

                if ($facHosInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table facturation_hospit');
                } 
            }

            $factures = DB::table('admission')
                ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
                ->where('admission.numhospit','=',$id)
                ->select(
                    'factures.numfac as numfac',
                    'factures.montanttotal as montanttotal',
                    'factures.montant_ass as montant_ass',
                    'factures.montant_pat as montant_pat',
                    'factures.montantreste_ass as montantreste_ass',
                    'factures.montantreste_pat as montantreste_pat',
                )
                ->first();

            $total_new = (int) str_replace('.', '', $request->montantTotal) + (int) $factures->montanttotal;
            $part_assurance_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montant_ass;
            $part_patient_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montant_pat;
            $part_assurance_reste_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montantreste_ass;
            $part_patient_reste_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montantreste_pat;

            $updateData_facture =[
                'montanttotal' => $total_new,
                'montant_ass' => $part_assurance_new,
                'montant_pat' => $part_patient_new,
                'montantreste_ass' => $part_assurance_reste_new,
                'montantreste_pat' => $part_patient_reste_new,
                'updated_at' => now(),
            ];

            $factureUpdate = DB::table('factures')
                                ->where('numfac', '=', $factures->numfac)
                                ->update($updateData_facture);

            if ($factureUpdate == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            // Si tout s'est bien passé, on commit les changements
            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_typesoins(Request $request)
    {
        $verf = DB::table('typesoinsinfirmiers')->where('libelle_typesoins', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $typesoinsInserted = DB::table('typesoinsinfirmiers')->insert([
            'libelle_typesoins' => $request->nom,
        ]);

        if ($typesoinsInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function new_soinsIn(Request $request)
    {
        $verf = DB::table('soins_infirmier')->where('libelle_soins', '=', $request->nom)->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $soinsInserted = DB::table('soins_infirmier')->insert([
            'price' => str_replace('.', '', $request->prix),
            'libelle_soins' => $request->nom,
            'code_typesoins' => $request->typesoins,
        ]);

        if ($soinsInserted == 1) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function new_soinsam(Request $request)
    {

        $selectionsSoins = $request->input('selectionsSoins');
        if (!is_array($selectionsSoins) || empty($selectionsSoins)) {
            return response()->json(['json' => true]);
        }

        $selectionsProduits = $request->input('selectionsProduits');
        // if (!is_array($selectionsProduits) || empty($selectionsProduits)) {
        //     return response()->json(['json' => true]);
        // }

        if ($request->numhosp != null) {
            
            $verf = DB::table('admission')
                ->where('admission.numhospit', '=', $request->numhosp)
                ->select(
                    'admission.*'
                )
                ->first();

            if ($verf) {

                if ($verf->statut == 'sortie') {
                    
                    return response()->json(['num_hosp_liberer' => true]);

                } else if ($verf->idenregistremetpatient != $request->patient_id ) {

                     return response()->json(['matricule_hosp_error' => true]);

                }
            } else {

                return response()->json(['num_hosp_introuvable' => true]);

            }

        }

        $numfac = $this->generateUniqueFactureSoinsAmbulatoire();
        $recu = $this->generateUniqueMatriculeNumRecu();

        $codeassurance = DB::table('patient')
            ->where('idenregistremetpatient', '=', $request->patient_id)
            ->select('codeassurance','codesocieteassure')
            ->first();

        DB::beginTransaction();

        try {

            $taux = (str_replace('.', '', $request->montantAssurance) / str_replace('.', '', $request->montantTotal)) * 100;
            $tauxEntier = intval($taux);

            $soinsId = DB::table('soins_medicaux')->insertGetId([
                'idenregistremetpatient' => $request->patient_id,
                'codeassurance' => $codeassurance->codeassurance,
                'codesocieteassure' => $codeassurance->codesocieteassure,
                'taux_couverture' => $tauxEntier,
                'date_soin' => now(),
                'montant_total' => str_replace('.', '', $request->montantTotal),
                'ticket_moderateur' => str_replace('.', '', $request->montantPatient),
                'part_assurance' => str_replace('.', '', $request->montantAssurance),
                'numfac_soins' => 'FCS'.$numfac,
                'paid_status' => 0,
                'numhospit' => $request->numhosp,
                'numbon' => $request->numcode,
            ]);

            if (!$soinsId) {
                throw new Exception('Erreur lors de l\'insertion dans la table tarifs');
            }

            $patient = DB::table('patient')
                ->where('patient.idenregistremetpatient', '=', $request->patient_id)
                ->select('patient.codeassurance as codeassurance')
                ->first();

            $timbre = 0;
            $montant_patient = (int) str_replace('.', '', $request->montantPatient);

            if ($montant_patient == 0) {

                $date_regle = now();
                $numrecu_regle = 'REC'.$recu;
            } else if ($montant_patient > 0) {
                $date_regle = null;
                $numrecu_regle = null;
            }

            // if ($montant_patient >= 5001 && $montant_patient <= 100000) {
            //     $timbre += 100;
            //     $montant_patient += 100;
            // } elseif ($montant_patient > 100000 && $montant_patient <= 500000) {
            //     $timbre += 500;
            //     $montant_patient += 500;
            // } elseif ($montant_patient > 500000 && $montant_patient <= 1000000) {
            //     $timbre += 1000;
            //     $montant_patient += 1000;
            // } elseif ($montant_patient > 1000000 && $montant_patient <= 5000000) {
            //     $timbre += 2000;
            //     $montant_patient += 2000;
            // } elseif ($montant_patient > 5000000) {
            //     $timbre += 2000;
            //     $montant_patient += 2000;
            // }

            $facturesInserted = DB::table('factures')->insert([
                'numfac' => 'FCS'.$numfac,
                'numhospit' => $request->numhosp,
                'idenregistremetpatient' => $request->patient_id,
                'montanttotal' => str_replace('.', '', $request->montantTotal),
                'remise' => str_replace('.', '', $request->montantRemise),
                'type_remise' => 0,
                'calcul_applique' => 0,
                'taux_applique' => $tauxEntier,
                'montant_ass' => str_replace('.', '', $request->montantAssurance),
                'montant_pat' => $montant_patient,
                'montantregle_ass' => 0,
                'montantregle_pat' => 0,
                'montantpat_verser' => 0,
                'montantpat_remis' => 0,
                'montantreste_ass' => str_replace('.', '', $request->montantAssurance),
                'montantreste_pat' => $montant_patient,
                'solde_ass' => 0,
                'solde_pat' => 0,
                'codeassurance' => $patient->codeassurance,
                'datefacture' => now(),
                'type_facture' => 4,
                'timbre_fiscal' => $timbre,
                'a_encaisser' => 0,
                'datereglt_pat' => $date_regle,
                'numrecu' => $numrecu_regle,
            ]);

            if ($facturesInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            if ($montant_patient == 0) {

                // table caisse
                $caisseInserted = DB::table('caisse')->insert([
                    'nopiece' => 'FCS'.$numfac,
                    'type' => 'entree',
                    'libelle' => 'Encaissement facture soins infirmier ',
                    'montant' => 0,
                    'dateop' => now(),
                    'datecreat' => now(),
                    'login' => Auth::user()->login,
                    'annule' => 0,
                    'mail' => 0,
                ]);

                if ($caisseInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table caisse');
                }

                $journalInserted = DB::table('journal')->insert([
                    'idenregistremetpatient' => $request->patient_id,
                    'date' => now(),
                    'numrecu' => 'REC'.$recu,
                    'montant_recu' => 0,
                    'numjournal' => $recu,
                    'numfac' => 'FCS'.$numfac,
                    'type_action' => 0,
                ]);

                if ($journalInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table journal');
                }
            }

            if ($request->numhosp !== null) {

                $facHosInsert = DB::table('facturation_hospit')->insert([
                    'numpchr' => $request->numhosp,
                    'numfac' => 'FCS'.$numfac,
                    'idgarhospit' => 5,
                    'qte' => 1,
                    'pu' => str_replace('.', '', $request->montantTotal),
                    'montgaran' => str_replace('.', '', $request->montantTotal),
                    'montextra' => 0,
                    'montaccorde' => str_replace('.', '', $request->montantAssurance),
                    'montrefus' => str_replace('.', '', $request->montantPatient),
                    'traiter' => 0,
                ]);

                if ($facHosInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table facturation_hospit');
                }

                $factures = DB::table('admission')
                    ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
                    ->where('admission.numhospit', '=', $request->numhosp)
                    ->select(
                        'factures.numfac as numfac',
                        'factures.montanttotal as montanttotal',
                        'factures.montant_ass as montant_ass',
                        'factures.montant_pat as montant_pat',
                        'factures.montantreste_ass as montantreste_ass',
                        'factures.montantreste_pat as montantreste_pat',
                    )
                    ->first();

                $total_new = (int) str_replace('.', '', $request->montantTotal) + (int) $factures->montanttotal;
                $part_assurance_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montant_ass;
                $part_patient_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montant_pat;
                $part_assurance_reste_new = (int) str_replace('.', '', $request->montantAssurance) + (int) $factures->montantreste_ass;
                $part_patient_reste_new = (int) str_replace('.', '', $request->montantPatient) + (int) $factures->montantreste_pat;

                $updateData_facture =[
                    'montanttotal' => $total_new,
                    'montant_ass' => $part_assurance_new,
                    'montant_pat' => $part_patient_new,
                    'montantreste_ass' => $part_assurance_reste_new,
                    'montantreste_pat' => $part_patient_reste_new,
                    'updated_at' => now(),
                ];

                $factureUpdate = DB::table('factures')
                                    ->where('numfac', '=', $factures->numfac)
                                    ->update($updateData_facture);

                if ($factureUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table factures');
                }

            }

            foreach ($selectionsSoins as $value) {

                $soins = DB::table('soins_infirmier')
                    ->where('code_soins','=',$value['id'])
                    ->select('soins_infirmier.*')
                    ->first();

                $soinsInsert = DB::table('soins_medicaux_itemsoins')->insert([
                    'id_soins' => $soinsId,
                    'code_soins' => $value['id'],
                    'qte' => 1,
                    'libelle_soins' => $soins->libelle_soins,
                    'price' => str_replace('.', '', $value['montant']),
                ]);

                if ($soinsInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table soins_medicaux_itemsoins');
                }

            }

            if (!empty($selectionsProduits) && is_array($selectionsProduits) && count($selectionsProduits) > 0) {
            
                foreach ($selectionsProduits as $value) {

                    $produit = DB::table('medicine')
                        ->where('medicine_id','=',$value['id'])
                        ->select('medicine.*')
                        ->first();

                    $produitInsert = DB::table('soins_medicaux_itemmedics')->insert([
                        'id_soins' => $soinsId,
                        'medicine_id' => $value['id'],
                        'qte' => $value['quantite'],
                        'name' => $produit->name,
                        'price' => str_replace('.', '', $value['prix']),
                    ]);

                    if ($produitInsert == 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table soins_medicaux_itemmedics');
                    }

                    $qte_new = (int) $produit->status - (int) $value['quantite'];

                    $updateData_produit =[
                        'status' => $qte_new,
                        'updated_at' => now(),
                    ];

                    $produitUpdate = DB::table('medicine')
                                        ->where('medicine_id', '=', $value['id'])
                                        ->update($updateData_produit);

                    if ($produitUpdate == 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table medicine');
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function new_examend(Request $request)
    {

        $selections = $request->input('selectionsExamen');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        if ($request->numhosp != null) {
            
            $verf = DB::table('admission')
                ->where('admission.numhospit', '=', $request->numhosp)
                ->select(
                    'admission.*'
                )
                ->first();

            if ($verf) {

                if ($verf->statut == 'sortie') {
                    
                    return response()->json(['num_hosp_liberer' => true]);

                } else if ($verf->idenregistremetpatient != $request->patient_id ) {

                     return response()->json(['matricule_hosp_error' => true]);

                }
            } else {

                return response()->json(['num_hosp_introuvable' => true]);

            }

        }
        
        $codeassurance = DB::table('patient')
            ->where('idenregistremetpatient', '=', $request->patient_id)
            ->select('codeassurance','codesocieteassure')
            ->first();

        DB::beginTransaction();

        try {

            // $taux = (str_replace('.', '', $request->montantA) / str_replace('.', '', $request->montantT)) * 100;
            // $tauxEntier = intval($taux);

            $numfac = $this->generateUniqueFactureExamen();
            $recu = $this->generateUniqueMatriculeNumRecu();

            if ($request->acte_id == 'B') {
                $id = 'BIO-'.$this->generateUniqueIdBiologie();
                $type = 'analyse';
            } else {
                $id = 'IMG-'.$this->generateUniqueIdImagerie();
                $type = 'imagerie';
            }

            if (str_replace('.', '', $request->montantA) > 0) {
                $mode = 1;
            } else {
                $mode = 0;
            }

            $examenInsert = DB::table('testlaboimagerie')->insert([
                'idtestlaboimagerie' => $id,
                'idenregistremetpatient' => $request->patient_id,
                'codeassurance' => $codeassurance->codeassurance,
                'codesocieteassure' => $codeassurance->codesocieteassure,
                'codemedecin' => null,
                'typedemande' => $type,
                'renseigclini' => $request->rensg,
                'date' => now(),
                'heure' => now()->format('H:i:s'),
                'numfacbul' => 'FCB'.$numfac,
                'numbon' => $request->numcode,
                'medicin_traitant' => $request->medecin,
                'numhospit' => $request->numhosp,
                'prelevement' => str_replace('.', '', $request->montant_pre),
                'mode_patient' => $mode,
            ]);

            if ($examenInsert == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table testlaboimagerie');
            }

            foreach ($selections as $value) {

                $detailInsert = DB::table('detailtestlaboimagerie')->insert([
                    'idtestlaboimagerie' => $id,
                    'numexam' => $value['id'],
                    'denomination' =>  $value['examen'],
                    'cotation' => $value['cotation'],
                    'resultat' => $value['accepte'],
                    'prix' => str_replace('.', '', $value['montant']),
                ]);

                if ($detailInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table detailtestlaboimagerie');
                }

            }

            $patient = DB::table('patient')
                ->where('patient.idenregistremetpatient', '=', $request->patient_id)
                ->select('patient.codeassurance as codeassurance')
                ->first();

            $timbre = 0;
            $montant_patient = (int) str_replace('.', '', $request->montantP);

            // $reste_patient = (int) $montant_patient - (int) str_replace('.', '', $request->remise);

            // if ($montant_patient >= 5001 && $montant_patient <= 100000) {
            //     $timbre += 100;
            //     $montant_patient += 100;
            // } elseif ($montant_patient > 100000 && $montant_patient <= 500000) {
            //     $timbre += 500;
            //     $montant_patient += 500;
            // } elseif ($montant_patient > 500000 && $montant_patient <= 1000000) {
            //     $timbre += 1000;
            //     $montant_patient += 1000;
            // } elseif ($montant_patient > 1000000 && $montant_patient <= 5000000) {
            //     $timbre += 2000;
            //     $montant_patient += 2000;
            // } elseif ($montant_patient > 5000000) {
            //     $timbre += 2000;
            //     $montant_patient += 2000;
            // }

            if ($montant_patient == 0) {

                $date_regle = now();
                $numrecu_regle = 'REC'.$recu;
            } else if ($montant_patient > 0) {
                $date_regle = null;
                $numrecu_regle = null;
            }


            $facturesInserted = DB::table('factures')->insert([
                'numfac' => 'FCB'.$numfac,
                'numhospit' => $request->numhosp,
                'idenregistremetpatient' => $request->patient_id,
                'montanttotal' => str_replace('.', '', $request->montantT),
                'remise' => str_replace('.', '', $request->remise),
                'type_remise' => 0,
                'calcul_applique' => 0,
                'taux_applique' => $request->taux,
                'montant_ass' => str_replace('.', '', $request->montantA),
                'montant_pat' => $montant_patient,
                'montantregle_ass' => 0,
                'montantregle_pat' => 0,
                'montantpat_verser' => 0,
                'montantpat_remis' => 0,
                'montantreste_ass' => str_replace('.', '', $request->montantA),
                'montantreste_pat' => $montant_patient,
                'solde_ass' => 0,
                'solde_pat' => 0,
                'codeassurance' => $patient->codeassurance,
                'datefacture' => now(),
                'type_facture' => 4,
                'timbre_fiscal' => $timbre,
                'a_encaisser' => 0,
                'datereglt_pat' => $date_regle,
                'numrecu' => $numrecu_regle,
            ]);

            if ($facturesInserted == 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table factures');
            }

            if ($montant_patient == 0) {

                // table caisse
                $caisseInserted = DB::table('caisse')->insert([
                    'nopiece' => 'FCB'.$numfac,
                    'type' => 'entree',
                    'libelle' => 'Encaissement facture biologie/imagerie ',
                    'montant' => 0,
                    'dateop' => now(),
                    'datecreat' => now(),
                    'login' => Auth::user()->login,
                    'annule' => 0,
                    'mail' => 0,
                ]);

                if ($caisseInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table caisse');
                }

                $journalInserted = DB::table('journal')->insert([
                    'idenregistremetpatient' => $request->patient_id,
                    'date' => now(),
                    'numrecu' => 'REC'.$recu,
                    'montant_recu' => 0,
                    'numjournal' => $recu,
                    'numfac' => 'FCB'.$numfac,
                    'type_action' => 0,
                ]);

                if ($journalInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table journal');
                }
            }

            if ($request->numhosp !== null) {

                if ($request->acte_id == 'B') {
                    $type = 3;
                } else {
                    $type = 4;
                }

                $facHosInsert = DB::table('facturation_hospit')->insert([
                    'numpchr' => $request->numhosp,
                    'numfac' => 'FCB'.$numfac,
                    'idgarhospit' => $type,
                    'qte' => 1,
                    'pu' => str_replace('.', '', $request->montantT),
                    'montgaran' => str_replace('.', '', $request->montantT),
                    'montextra' => 0,
                    'montaccorde' => str_replace('.', '', $request->montantA),
                    'montrefus' => str_replace('.', '', $request->montantP),
                    'traiter' => 0,
                ]);

                if ($facHosInsert == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table facturation_hospit');
                }

                $factures = DB::table('admission')
                    ->join('factures', 'factures.numfac', '=', 'admission.numfachospit')
                    ->where('admission.numhospit', '=', $request->numhosp)
                    ->select(
                        'factures.numfac as numfac',
                        'factures.montanttotal as montanttotal',
                        'factures.montant_ass as montant_ass',
                        'factures.montant_pat as montant_pat',
                        'factures.montantreste_ass as montantreste_ass',
                        'factures.montantreste_pat as montantreste_pat',
                    )
                    ->first();

                $total_new = (int) str_replace('.', '', $request->montantT) + (int) $factures->montanttotal;
                $part_assurance_new = (int) str_replace('.', '', $request->montantA) + (int) $factures->montant_ass;
                $part_patient_new = (int) str_replace('.', '', $request->montantP) + (int) $factures->montant_pat;
                $part_assurance_reste_new = (int) str_replace('.', '', $request->montantA) + (int) $factures->montantreste_ass;
                $part_patient_reste_new = (int) str_replace('.', '', $request->montantP) + (int) $factures->montantreste_pat;

                $updateData_facture =[
                    'montanttotal' => $total_new,
                    'montant_ass' => $part_assurance_new,
                    'montant_pat' => $part_patient_new,
                    'montantreste_ass' => $part_assurance_reste_new,
                    'montantreste_pat' => $part_patient_reste_new,
                    'updated_at' => now(),
                ];

                $factureUpdate = DB::table('factures')
                                    ->where('numfac', '=', $factures->numfac)
                                    ->update($updateData_facture);

                if ($factureUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table factures');
                }

            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function new_horaire(Request $request)
    {
        $selections = $request->input('selections');
        if (!is_array($selections) || empty($selections)) {
            return response()->json(['json' => true]);
        }

        $medecin = DB::table('medecin')->where('codemedecin', '=', $request->medecin_id)->first();

        if (!$medecin) {
            return response()->json(['error' => true]);
        }

        DB::beginTransaction();

        try {

            foreach ($selections as $value) {

                $horaireInserted = DB::table('programmemedecins')->insert([
                    'periode' => $value['periode'],
                    'statut' => 'oui',
                    'heure_debut' => $value['heure_debut'],
                    'heure_fin' => $value['heure_fin'],
                    'jour_id' => $value['jour_id'],
                    'codemedecin' => $medecin->codemedecin,
                    'created_at' => now(),
                ]);

                if ($horaireInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table programmemedecins');
                }

            }

            DB::commit();
            return response()->json(['success' => true]);
            
        } catch (Exception $e) {

            DB::rollback();
            return response()->json(['error' => true]);
        }
    }

    public function new_rdv(Request $request)
    {
        $medecin = DB::table('medecin')->where('codemedecin', '=', $request->medecin_id)->first();
        if (!$medecin) {
            return response()->json(['error' => true]);
        }

        $patient = DB::table('patient')->where('idenregistremetpatient', '=', $request->patient_id)->first();
        if (!$patient) {
            return response()->json(['error' => true]);
        }

        $verf = DB::table('rdvpatients')->where('patient_id', '=', $patient->idenregistremetpatient)
                        ->where('codemedecin', '=', $medecin->codemedecin)
                        ->where('date','=', $request->date)
                        ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $rdvInserted = DB::table('rdvpatients')->insert([
            'codemedecin' => $medecin->codemedecin,
            'patient_id' => $patient->idenregistremetpatient,
            'date' => $request->date,
            'tel' => $request->tel,
            'motif' => $request->motif,
            'statut' => 'en attente',
            'created_at' => now(),
        ]);

        if ($rdvInserted == 1) {
            return response()->json([
                'success' => true,
                'tel' => $request->tel,
                'date' => $request->date
            ]);
        }

        return response()->json(['error' => true]);
    }

    public function specialite_new(Request $request)
    {

        $Exist = DB::table('specialitemed')
            ->where('nomspecialite', '=', $request->nom)
            ->orWhere('abrspecialite', '=', $request->abr)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'Cette spécialité existe déjà']);
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeSpecialite();

                $specialiteInserted = DB::table('specialitemed')->insert([
                    'codespecialitemed' => 'SP'.$matricule,
                    'nomspecialite' => $request->nom,
                    'abrspecialite' => $request->abr,
                    'libellespecialite' => $request->nom,
                    'dateenregistre' => now(),
                ]);

                if ($specialiteInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table specialitemed');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_depot_fac(Request $request)
    {
        set_time_limit(300);

        $periode = $request->periode;
        list($year, $month) = explode('-', $periode);

        $date_depot = $request->date_depot;
        $assurance_id = $request->assurance_id;

        $verf = DB::table('depotfactures')
            ->where('periode_annee', '=', $year)
            ->where('periode_mois', '=', $month)
            ->where('assurance_id', '=', $assurance_id)
            ->exists();

        if ($verf) {
            return response()->json(['existe' => true]);
        }

        $societes = DB::table('societeassure')
            ->where('codeassurance', '=', $request->assurance_id)
            ->select('societeassure.*')
            ->get();

        $total_patient = 0;
        $total_assurance = 0;
        $total_montant = 0;

        foreach ($societes as $societe) {
            $codesocieteassure = $societe->codesocieteassure;

            $fac_cons = DB::table('consultation')
                ->join('patient', 'consultation.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'consultation.numfac')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('consultation.numbon')
                ->where('consultation.numbon', '!=', '')
                ->whereYear('consultation.date', $year)
                ->whereMonth('consultation.date', $month)
                ->where('patient.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_cons as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_exam = DB::table('testlaboimagerie')
                ->join('patient', 'testlaboimagerie.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'testlaboimagerie.numfacbul')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('testlaboimagerie.numbon')
                ->where('testlaboimagerie.numbon', '!=', '')
                ->whereYear('testlaboimagerie.date', $year)
                ->whereMonth('testlaboimagerie.date', $month)
                ->where('patient.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_exam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_soinsam = DB::table('soins_medicaux')
                ->join('patient', 'soins_medicaux.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'soins_medicaux.numfac_soins')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('soins_medicaux.numbon')
                ->where('soins_medicaux.numbon', '!=', '')
                ->whereYear('soins_medicaux.date_soin', $year)
                ->whereMonth('soins_medicaux.date_soin', $month)
                ->where('patient.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_soinsam as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }

            $fac_hopital = DB::table('admission')
                ->join('patient', 'admission.idenregistremetpatient', '=', 'patient.idenregistremetpatient')
                ->join('factures', 'factures.numfac','=', 'admission.numfachospit')
                ->where('patient.assure', '=', 1)
                ->whereNotNull('admission.numbon')
                ->where('admission.numbon', '!=', '')
                ->whereYear('admission.created_at', $year)
                ->whereMonth('admission.created_at', $month)
                ->where('patient.codesocieteassure', $codesocieteassure)
                ->select(
                    'factures.montant_ass as part_assurance',
                    'factures.montant_pat as part_patient',
                    'factures.remise as remise',
                    'factures.montanttotal as montant',
                )
                ->get();

            foreach ($fac_hopital as $value) {
                $total_patient += $value->part_patient;
                $total_assurance += $value->part_assurance;
                $total_montant += $value->montant;
            }
            
        }

        if ($total_montant <= 0) {
            return response()->json(['montant_inferieur' => true]);
        }

        $depotFacInserted = DB::table('depotfactures')->insert([
            'periode_mois' => $month,
            'periode_annee' => $year,
            'date_depot' => $date_depot,
            'montant' => $total_assurance,
            'type_paiement' => null,
            'num_cheque' => null,
            'date_payer' => null,
            'statut' => 0,
            'assurance_id' => $assurance_id,
            'creer_id' => Auth::user()->login,
            'encaisser_id' => null,
            'montant_accepte' => 0,
            'montant_rejet' => 0,
            'motif_rejet' => null,
            'created_at' => now(),
        ]);

        if ($depotFacInserted == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);

    }

    public function paiement_depot_fac(Request $request, $id)
    {
        $depot = DB::table('depotfactures')
            ->where('id', '=', $id)
            ->select(
                'depotfactures.*',
            )
            ->exists();

        if (!$depot) {
            return response()->json(['non_touve' => true]);
        }

        if ($request->montant_rejet != null && str_replace('.', '', $request->montant_rejet) > 0) {

            $montant_accepte = (int) str_replace('.', '', $request->montant) - (int) str_replace('.', '', $request->montant_rejet);

            $montant_rejet = str_replace('.', '', $request->montant_rejet);
        } else {

            $montant_accepte = str_replace('.', '', $request->montant);

            $montant_rejet = 0;
        }

        $updateData_depot = [
            'statut' => 1,
            'date_payer' => $request->date,
            'type_paiement' => $request->type,
            'num_cheque' => $request->cheque,
            'encaisser_id' => Auth::user()->login,
            'montant_accepte' => $montant_accepte,
            'montant_rejet' => $montant_rejet,
            'motif_rejet' => $request->motif_rejet,
            'updated_at' => now(),
        ];

        $depotFacUpdate = DB::table('depotfactures')
                        ->where('id', '=', $id)
                        ->update($updateData_depot);

        if ($depotFacUpdate == 1) {
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => true]);

    }

    public function ope_caisse_new(Request $request)
    {
        $verf = DB::table('porte_caisses')->select('statut','montant')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['caisse_fermer' => true]);
        }

        DB::beginTransaction();

        try {

            if ($request->type_ope == 'sortie' && str_replace('.', '', $request->montant_ope) > $verf->montant) {
                
                return response()->json(['caisse_inferieur' => true]);

            }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            // $montant_formate = number_format($montant, 0, ',', '.');

            $nopiece = $this->generateUniqueMatriculeCaisseEntrerSortie();

            $caisseInserted = DB::table('caisse')->insert([
                'nopiece' => $nopiece,
                'type' => $request->type_ope,
                'libelle' => $request->libelle_ope,
                'montant' => str_replace('.', '', $request->montant_ope),
                'dateop' => $request->date_ope,
                'datecreat' => now(),
                'login' => Auth::user()->login,
                'beneficiaire' => $request->bene_ope,
                'annule' => 0,
                'mail' => 0,
            ]);

            if ($caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse');
            }

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $numfac = substr(str_shuffle($characters), 0, 3);

            $num = $this->generateUniqueMatriculeNumRecu();

            if ($request->type_ope === 'entree') {
                $type_action = 0;
            } else if ($request->type_ope === 'sortie') {
                $type_action = 1;
            }

            $journalInserted = DB::table('journal')->insert([
                'idenregistremetpatient' => "Fac N°".$numfac,
                'date' => now(),
                'numrecu' => $num,
                'montant_recu' => str_replace('.', '', $request->montant_ope),
                'numjournal' => $numfac,
                'type_action' => $type_action,
            ]);

            if ($journalInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table journal');
            }

            $solde = $montant + str_replace('.', '', $request->montant_ope);

            $soldecaisseUpdated = DB::table('porte_caisses')
                ->where('id', '=', 1)
                ->update([
                    'montant' => $solde,
                    'updated_at' => now(),
                ]);

            if ($soldecaisseUpdated === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table porte_caisses');
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'solde' => $solde,
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function new_user(Request $request)
    {
        $verifications = [
            'tel' => $request->tel,
            'tel2' => $request->tel2,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('employes')->where(function ($query) use ($verifications) {
            $query->where('cel', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['tel2'])) {
                          $query->where('contacturgence', $verifications['tel2']);
                      }
                  })
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->first();


        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] || (!is_null($verifications['tel2']) && $Exist->tel2 === $verifications['tel2'])) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $matricule = $this->generateUniqueMatriculeEmploye();

                $profil = DB::table('profile')->where('idprofile', '=', $request->profil_id)->first();
                $service = DB::table('service')->where('code', '=', $request->service_id)->first();

                if (!$profil || !$service) {
                    throw new Exception('Profil ou Service introuvable');
                }

                $employeInserted = DB::table('employes')->insert([
                    'matricule' => 'P'.$matricule,
                    'typepiece' => $request->typepiece,
                    'nopiece' => null,
                    'civilite' => $request->civilite,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'nomprenom' => $request->nom.' '.$request->prenom,
                    'datenais' => $request->datenais,
                    'profession' => $service->libelle,
                    'niveau' => $request->niveau,
                    'diplome' => $request->diplome,
                    'residence' => $request->residence,
                    'dateenregistre' => now()->format('Y-m-d'),
                    'cel' => $request->tel,
                    'contacturgence' => $request->tel2,
                    'email' => $request->email,
                    'service' => $request->service_id,
                    'typecontrat' => $request->contrat_id,
                    'datecontrat' => $request->date_debut,
                    'datefincontrat' => $request->date_fin,
                    'paye' => '0',

                ]);

                if ($employeInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table employes');
                }

                $userInserted = DB::table('users')->insert([
                    'api_token' => Null, 
                    'login' => $request->login,  //Pour le login
                    'user_first_name' => $request->nom,
                    'user_last_name' => $request->prenom,
                    'tel' => $request->tel,
                    'user_profil_id' => $request->profil_id,
                    'email' => $request->email,
                    'password' => password_hash($request->password, PASSWORD_BCRYPT),
                    'user_rights' => Null,
                    'user_make_date' => Null,
                    'user_revised_date' => Null,
                    'user_ip' => Null,
                    'user_history' => Null,
                    'user_logs' => Null,
                    'user_lang' => Null,
                    'user_photo' => Null,
                    'user_actif' => Null,
                    'user_actions' => Null,
                    'code_personnel' => 'P'.$matricule,
                    'photo' => Null,
                ]);

                if (!$userInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table users');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function new_user_login(Request $request)
    {
        $verifications = [
            'tel' => $request->tel,
            'email' => $request->email ?? null,
        ];

        $Exist = DB::table('users')->where(function ($query) use ($verifications) {
            $query->where('tel', $verifications['tel'])
                  ->orWhere(function ($query) use ($verifications) {
                      if (!is_null($verifications['email'])) {
                          $query->where('email', $verifications['email']);
                      }
                  });
        })->first();


        if ($Exist) {
            if ($Exist->tel === $verifications['tel'] ) {
                return response()->json(['tel_existe' => true]);
            } elseif ($Exist->email === $verifications['email']) {
                return response()->json(['email_existe' => true]);
            }
        }

        DB::beginTransaction();

            try {

                $profil = DB::table('profile')->where('idprofile', '=', $request->profil_id)->first();

                if (!$profil) {
                    throw new Exception('Profil introuvable');
                }

                $userInserted = DB::table('users')->insert([
                    'api_token' => Null, 
                    'login' => $request->login,  //Pour le login
                    'user_first_name' => $request->nom,
                    'user_last_name' => $request->prenom,
                    'tel' => $request->tel,
                    'user_profil_id' => $request->profil_id,
                    'email' => $request->email,
                    'password' => password_hash($request->password, PASSWORD_BCRYPT),
                    'user_rights' => Null,
                    'user_make_date' => Null,
                    'user_revised_date' => Null,
                    'user_ip' => Null,
                    'user_history' => Null,
                    'user_logs' => Null,
                    'user_lang' => Null,
                    'user_photo' => Null,
                    'user_actif' => Null,
                    'user_actions' => Null,
                    'code_personnel' => Null,
                    'photo' => Null,
                ]);

                if (!$userInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table users');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function caisse_ouvert(Request $request)
    {
        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'ouvert') {
            return response()->json(['deja' => true]);
        }

        DB::beginTransaction();

        try {

            $updateData_staut_caisse =[
                'statut' => 'ouvert',
                'updated_at' => now(),
            ];

            $statut_caisseUpdate = DB::table('porte_caisses')
                                ->where('id', '=', 1)
                                ->update($updateData_staut_caisse);

            if ($statut_caisseUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $montant_formate = number_format($montant, 0, ',', '.');

            $op_caisseInserted = DB::table('caisse_resume')->insert([
                'datecaisse' => now(),
                'mtcaisse' => $montant,
                'action' => 0,
                'user' => Auth::user()->login,
                'heurecaisse' => date('H:i:s'),
            ]);

            if ($op_caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse_resume');
            }

            $mail = new PHPMailer(true);

            try {
               
                $mail->isHTML(true);
                $mail->isSMTP();

                // $mail->CharSet = 'UTF-8';
                // $mail->SMTPDebug = 2; // ou 3 pour plus de détails
                // $mail->Debugoutput = function($str, $level) {
                //     Log::info("SMTP Debug level $level: $str");
                // };

                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'notificationMail2000@gmail.com';
                $mail->Password = 'trav mpmj shqj nyhl';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('notificationMail2000@gmail.com', 'ESPACE MEDICO-SOCIAL LA PYRAMIDE');

                // $recipients = [
                //     'lukdio@hotmail.fr',
                //     'myghislainyao@gmail.com',
                // ];   

                $recipients = [
                    'davidkouachi01@gmail.com',
                ];

                foreach ($recipients as $recipient) {
                    $mail->addAddress($recipient);
                }

                $mail->Subject = 'ALERT !';
                $mail->Body = 'OUVERTURE DE LA CAISSE, Solde caisse : '. $montant_formate .' Fcfa';
                $mail->send();

            } catch (Exception $e) {
                // \Log::error('Erreur email caisse_ouvert', [
                //     'message' => $e->getMessage(),
                //     'trace' => $e->getTraceAsString()
                // ]); 
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function caisse_fermer(Request $request)
    {
        $verf = DB::table('porte_caisses')->select('statut')->where('id', '=', 1)->first();

        if ($verf->statut === 'fermer') {
            return response()->json(['deja' => true]);
        }

        DB::beginTransaction();

        try {

            $updateData_staut_caisse =[
                'statut' => 'fermer',
                'updated_at' => now(),
            ];

            $statut_caisseUpdate = DB::table('porte_caisses')
                                ->where('id', '=', 1)
                                ->update($updateData_staut_caisse);

            if ($statut_caisseUpdate === 0) {
                throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            }

            $today = Carbon::today();
            $total = 0;
            $entries = 0;
            $exits = 0;

            $transactions = DB::table('caisse')->where('mail', '=', 0)->get();
            
            foreach ($transactions as $value) {
                if ($value->type === 'entree') {
                    $total += str_replace('.', '', $value->montant);
                    $entries += str_replace('.', '', $value->montant);
                } else {
                    $total -= str_replace('.', '', $value->montant);
                    $exits += str_replace('.', '', $value->montant);
                }
            }

            // -----------------------------------------

            $currentDateTime = Carbon::now()->format('d/m/Y');
            $totalFormatted = number_format($total, 0, ',', '.');
            $entriesFormatted = number_format($entries, 0, ',', '.');
            $exitsFormatted = number_format($exits, 0, ',', '.');

            $tableRows ="";

            foreach ($transactions as $transaction) {

                $color = ($transaction->type === 'entree') ? 'green' : 'red';
                $montant = number_format($transaction->montant, 0, '.', '.');
                $date = Carbon::parse($transaction->datecreat)->format('d/m/Y'.' à '.'H:m:s');

                $montantFormatted = $transaction->type === 'entree' ? "+ {$montant} Fcfa" : "- {$montant} Fcfa";

                $entryCell = $transaction->type === 'entree' ? "<td style='color: {$color};'>{$montantFormatted}</td><td></td>" : "<td></td><td style='color: {$color};'>{$montantFormatted}</td>";

                $tableRows .= "<tr>
                    <td>{$transaction->libelle}</td>
                    {$entryCell}
                    <td>{$date}</td>
                </tr>";
            }

            $totaux ="
                <tr>
                    <th>TOTAUX</th>
                    <th style='color:green;' >+ {$entriesFormatted} Fcfa</th>
                    <th style='color:red;' >- {$exitsFormatted} Fcfa</th>
                    <th></th>
                </tr>
            ";

            $bilan = '
                <th style="padding: 10px; text-align: center;">BILAN DES OPERATIONS</th>
                <th colspan="2" style="padding: 10px; text-align: center;">' . $totalFormatted . ' Fcfa</th>
                <th></th>
            ';

            // -----------------------------------------

            // envoi de email
            $mail = new PHPMailer(true);

            try {

                $mail->isHTML(true);
                $mail->isSMTP();

                // $mail->CharSet = 'UTF-8';
                // $mail->SMTPDebug = 2; // ou 3 pour plus de détails
                // $mail->Debugoutput = function($str, $level) {
                //     Log::info("SMTP Debug level $level: $str");
                // };

                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'notificationMail2000@gmail.com';
                $mail->Password = 'trav mpmj shqj nyhl';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('notificationMail2000@gmail.com', 'ESPACE MEDICO-SOCIAL LA PYRAMIDE');
                
                // $recipients = [
                //     'lukdio@hotmail.fr',
                //     'myghislainyao@gmail.com',
                // ];

                $recipients = [
                    'davidkouachi01@gmail.com',
                ];

                foreach ($recipients as $recipient) {
                    $mail->addAddress($recipient);
                }

                $mail->Subject = 'ALERT !';

                $mail->Body = "
                    <h2>Fermeture de la caisse du {$currentDateTime} : {$totalFormatted} Fcfa</h2>
                    <h3>Ci-dessous toutes les opérations de la journée</h3>
                    <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                        <thead style='background-color: #116aef; color: white;'>
                            <tr>
                                <th style='padding: 10px; text-align: center;'>OPERATION</th>
                                <th style='padding: 10px; text-align: center;'>ENTREES</th>
                                <th style='padding: 10px; text-align: center;'>SORTIES</th>
                                <th style='padding: 10px; text-align: center;'>DATE & HEURE</th>
                            </tr>
                        </thead>
                        <tbody>
                            {$tableRows}
                        </tbody>
                        <tfoot>
                            {$totaux}
                            <tr style='background-color: #116aef; color: white;'>
                                {$bilan}
                            </tr>
                        </tfoot>
                    </table>
                ";

                // 👉 ICI il faut déclencher l’envoi
                $mail->send();

            } catch (Exception $e) {
               // \Log::error('Erreur email caisse_ouvert', [
               //      'message' => $e->getMessage(),
               //      'trace' => $e->getTraceAsString()
               //  ]); 
            }

            $updateData_mail_envoyer =[
                'mail' => 1,
                'updated_at' => now(),
            ];

            $mail_envoyerUpdate = DB::table('caisse')
                                ->where('mail', '=', 0)
                                ->update($updateData_mail_envoyer);

            // if ($mail_envoyerUpdate === 0) {
            //     throw new Exception('Erreur lors de la mise à jour dans la table porte_caisses');
            // }

            $montant = DB::table('caisse')
                ->selectRaw("SUM(CASE WHEN type = 'entree' THEN montant ELSE -montant END) as solde")
                ->value('solde');

            $op_caisseInserted = DB::table('caisse_resume')->insert([
                'datecaisse' => now(),
                'mtcaisse' => $montant,
                'action' => 2,
                'user' => Auth::user()->login,
                'heurecaisse' => date('H:i:s'),
            ]);

            if ($op_caisseInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table caisse_resume');
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true,'message' => $e->getMessage()]);
        }
    }

    public function type_garantie_new(Request $request)
    {
        $Exist = DB::table('typgarantie')
            ->where('codtypgar', '=', $request->code)
            ->orWhere('libtypgar', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le Code ou le Type existe dèjà']);
        }

        DB::beginTransaction();

        try {

            $typegarantieInserted = DB::table('typgarantie')->insert([
                'codtypgar' => $request->code,
                'libtypgar' => $request->nom,
            ]);

            if ($typegarantieInserted === 0) {
                throw new Exception('Erreur lors de l\'insertion dans la table typgarantie');
            }

             // Valider la transaction
            DB::commit();
            return response()->json(['success' => true]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function garantie_new(Request $request)
    {
        $Exist = DB::table('garantie')
            ->where('codgaran', '=', $request->code)
            ->orWhere('libgaran', '=', $request->nom)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le Code ou cette garantie existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $numExamen = $this->generateUniqueNumExamen();

                if ($request->code_type == 'EXAM') {

                    if ($request->type_examen == 'Y') {

                        $examnYInserted = DB::table('examen')->insert([
                            'numexam' => 'Y'.$numExamen,
                            'cot' => 0,
                            'denomination' => $request->nom,
                            'codgaran' => $request->code,
                            'codfamexam' => 'Y',
                            'fam_acte_bio' => null,
                            'prix' => 0,
                        ]);

                        if ($examnYInserted === 0) {
                            throw new Exception('Erreur lors de l\'insertion dans la table examenY');
                        }

                    } else if ($request->type_examen == 'Z' || $request->type_examen == 'B') {

                        if ($request->type_examen == 'Z') {
                            $num = 'Z'.$numExamen;
                            $cod = 'Z';
                        } else if ($request->type_examen == 'B') {
                            $num = 'B'.$numExamen;
                            $cod = 'B';
                        }
                        
                        $examnZBInserted = DB::table('examen')->insert([
                            'numexam' => $num,
                            'cot' => $request->cotation,
                            'denomination' => $request->nom,
                            'codgaran' => null,
                            'codfamexam' => $cod,
                            'fam_acte_bio' => null,
                            'prix' => 0,
                        ]);

                        if ($examnZBInserted === 0) {
                            throw new Exception('Erreur lors de l\'insertion dans la table examenY');
                        }
                    }
                }

                $garantieInserted = DB::table('garantie')->insert([
                    'codgaran' => $request->code,
                    'libgaran' => $request->nom,
                    'codtypgar' => $request->code_type,
                ]);

                if ($garantieInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table garantie');
                }

                 // Valider la transaction
                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function tarif_new(Request $request)
    {

        if ($request->assurer == 0) {
            $codeassurance = 'NONAS';
        } else {
            $codeassurance = $request->assurance;
        }

        $Exist = DB::table('tarifs')
            ->where('codgaran', '=', $request->garantie)
            ->Where('codeassurance', '=', $codeassurance)
            ->exists();

        if ($Exist) {
            return response()->json(['existe' => true,'message' => 'le tarif de cette garantie existe dèjà']);
        }

        DB::beginTransaction();

            try {

                $tarifInserted = DB::table('tarifs')->insert([
                    'codgaran' => $request->garantie,
                    'montjour' => str_replace('.', '', $request->prixj),
                    'montnuit' => str_replace('.', '', $request->prixn),
                    'montferie' => str_replace('.', '', $request->prixf),
                    'codeassurance' => $codeassurance,
                ]);

                if ($tarifInserted === 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table tarifs');
                }

                DB::commit();
                return response()->json(['success' => true]);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function attribution_remise(Request $request, $numfac)
    {
        // Vérifier si la facture existe
        $fac = DB::table('factures')->where('numfac', '=', $numfac)->first();

        if (!$fac) {
            return response()->json(['introuvable' => true, 'message' => 'La facture est introuvable']);
        }

        // Vérifier les conditions avant de poursuivre
        if ($fac->montantreste_pat == 0 || $fac->montant_pat == 0 || $fac->montantpat_verser > 0 ) {
            return response()->json(['impossible' => true,'message' => 'Impossible d\'éffectuée une remise sur cette facture']);
        }

        if ($request->montant > $fac->montant_pat) {
            return response()->json(['impossible' => true,'message' => 'Le montant saisi est supérieur a celui du patient']);
        }

        DB::beginTransaction();
        try {
            $recu = $this->generateUniqueMatriculeNumRecu();

            $ancien_partClient = intval($fac->montant_pat);
            $ancien_remise = intval($fac->remise);
            $ancien_reste = intval($fac->montantreste_pat);

            $new_partClient = $ancien_partClient - intval($request->montant);
            $new_remise = $ancien_remise + intval($request->montant);
            $new_reste = $ancien_reste - intval($request->montant);

            // Vérification logique des montants
            if ($new_partClient < 0 || $new_reste < 0) {
                throw new Exception('Le nouveau montant du patient est négatif.');
            }

            // Définir les valeurs de mise à jour en fonction du nouveau montant du patient
            $date_regle = ($new_partClient == 0) ? now() : null;
            $numrecu_regle = ($new_partClient == 0) ? 'REC' . $recu : null;

            // Mise à jour de la facture
            $facturesUpdated = [
                'remise' => $new_remise,
                'montant_pat' => $new_partClient,
                'montantreste_pat' => $new_reste,
                'datereglt_pat' => $date_regle,
                'numrecu' => $numrecu_regle,
            ];

            $fUpdated = DB::table('factures')->where('numfac', '=', $numfac)->update($facturesUpdated);

            if (!$fUpdated) {
                throw new Exception('Erreur lors de la mise à jour factures.');
            }

            if ($request->acte == 'hosp') {
                
                $updateData_payer =[
                    'statut' => 'sortie',
                    'updated_at' => now(),
                ];

                $payerUpdate = DB::table('admission')
                                    ->where('numfachospit', '=', $numfac)
                                    ->update($updateData_payer);

                if ($payerUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table lits');
                }
            
                // statut disponibilite
                $admission = DB::table('admission')->where('numfachospit', '=', $numfac)->select('idtypelit')->first();

                $updateData_statut_lit =[
                    'statut' => 'disponible',
                    'updated_at' => now(),
                ];

                $statutLitUpdate = DB::table('lits')
                                    ->where('id', '=', $admission->idtypelit)
                                    ->update($updateData_statut_lit);

                if ($statutLitUpdate == 0) {
                    throw new Exception('Erreur lors de l\'insertion dans la table lits');
                }

                $lit = DB::table('lits')->where('id', '=', $admission->idtypelit)->select('lits.*')->first();
                $chambre = DB::table('chambres')->where('id', '=', $lit->chambre_id)->select('chambres.*')->first();

                $nbre_lit = DB::table('lits')
                    ->where('chambre_id', '=', $lit->chambre_id)
                    ->where('statut', '=', 'disponible')
                    ->count();


                if ($chambre->nbre_lit == $nbre_lit) {
                    
                    $updateData_statut_chambre =[
                        'statut' => 'disponible',
                        'updated_at' => now(),
                    ];

                    $statutChambreUpdate = DB::table('chambres')
                                    ->where('id', '=', $lit->chambre_id)
                                    ->update($updateData_statut_chambre);

                    if ($statutChambreUpdate == 0) {
                        throw new Exception('Erreur lors de l\'insertion dans la table chambres');
                    }
                }
                
            }

            // Valider la transaction
            DB::commit();
            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

}
