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
// use App\Models\typemedecin;
// use App\Models\user;
// use App\Models\role;
// use App\Models\consultation;
// use App\Models\detailconsultation;
// use App\Models\typeadmission;
// use App\Models\natureadmission;
// use App\Models\detailhopital;
// use App\Models\facture;
// use App\Models\produit;
// use App\Models\soinshopital;
// use App\Models\soinsinfirmier;
// use App\Models\typesoins;
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


class ApideleteController extends Controller
{
    public function delete_chambre($id)
    {
        $put = chambre::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    public function delete_lit($id)
    {
        $put = lit::find($id);

        if ($put) {
            if ($put->delete()) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    // public function delete_acte($id)
    // {
    //     $put = acte::find($id);

    //     if ($put) {
    //         if ($put->delete()) {
    //             return response()->json(['success' => true]);
    //         }else{
    //             return response()->json(['error' => true]);
    //         }
    //     }

    //     return response()->json(['error' => true]);
    // }

    // public function delete_typeacte($id)
    // {
    //     $put = typeacte::find($id);

    //     if ($put) {
    //         if ($put->delete()) {
    //             return response()->json(['success' => true]);
    //         }else{
    //             return response()->json(['error' => true]);
    //         }
    //     }

    //     return response()->json(['error' => true]);
    // }

    public function delete_medecin($matricule)
    {
        DB::beginTransaction();

            try {

                $medecinDelete = DB::table('medecin')
                                ->where('codemedecin', '=', $matricule)
                                ->delete();

                if (!$medecinDelete === 0) {
                    throw new \Exception('Erreur lors de la suppression dans la table medecin');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    // public function delete_typeadmission($id)
    // {
    //     $put = typeadmission::find($id);

    //     if ($put) {
    //         if ($put->delete()) {
    //             return response()->json(['success' => true]);
    //         }else{
    //             return response()->json(['error' => true]);
    //         }
    //     }

    //     return response()->json(['error' => true]);
    // }

    // public function delete_natureadmission($id)
    // {
    //     $put = natureadmission::find($id);

    //     if ($put) {
    //         if ($put->delete()) {
    //             return response()->json(['success' => true]);
    //         }else{
    //             return response()->json(['error' => true]);
    //         }
    //     }

    //     return response()->json(['error' => true]);
    // }

    public function delete_typesoins($id)
    {
        $put = DB::table('typesoinsinfirmiers')->where('code_typesoins', '=', $id)->first();

        if ($put) {

            $delete = DB::table('typesoinsinfirmiers')->where('code_typesoins', '=', $id)->delete();

            if ($delete == 1) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]);
    }

    // public function delete_soinsIn($id)
    // {
    //     $put = soinsinfirmier::find($id);

    //     if ($put) {
    //         if ($put->delete()) {
    //             return response()->json(['success' => true]);
    //         }else{
    //             return response()->json(['error' => true]);
    //         }
    //     }

    //     return response()->json(['error' => true]);
    // }

    public function delete_societe($id)
    {
        DB::beginTransaction();

            try {

                $societeDelete = DB::table('societeassure')
                                ->where('codesocieteassure', '=', $id)
                                ->delete();

                if (!$societeDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table societeassure');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_rdv($id)
    {
        $put = DB::table('rdvpatients')->where('id', '=', $id)->first();

        if ($put) {

            $delete = DB::table('rdvpatients')->where('id', '=', $id)->delete();

            if ($delete == 1) {
                return response()->json(['success' => true]);
            }else{
                return response()->json(['error' => true]);
            }
        }

        return response()->json(['error' => true]); 
    }

    public function delete_specialite($id)
    {
        DB::beginTransaction();

            try {

                $specialiteDelete = DB::table('specialitemed')
                                ->where('codespecialitemed', '=', $id)
                                ->delete();

                if (!$specialiteDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table specialitemed');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_depotfacture($id)
    {

        DB::beginTransaction();

            try {

                $depotFacDelete = DB::table('depotfactures')
                                ->where('id', '=', $id)
                                ->delete();

                if (!$depotFacDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table depotfactures');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);

            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_Cons($numfac)
    {
        $put = DB::table('consultation')->where('numfac', '=', $numfac)->exists();

        if (!$put) {
            return response()->json(['error' => true, 'message' => 'Consultation non trouvé']);
        }

        DB::beginTransaction();

        try {
            // Trouver la consultation associée
            $id_cons = DB::table('consultation')->where('numfac', '=', $numfac)->delete();
            if ($id_cons === 0) {
                return response()->json(['error' => true, 'message' => 'Consultation non trouvée']);
            }

            // Trouver la facture associée à la consultation
            $id_facture = DB::table('factures')->where('numfac', '=', $numfac)->delete();
            if ($id_facture === 0) {
                return response()->json(['error' => true, 'message' => 'Facture non trouvée']);
            }

            DB::commit(); // Validation de la transaction si tout s'est bien passé
            return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);
        } catch (\Exception $e) {
            DB::rollBack(); // Annulation en cas d'\exception
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function delete_user($matricule)
    {
        DB::beginTransaction();

            try {

                $userDelete = DB::table('users')
                                ->where('code_personnel', '=', $matricule)
                                ->delete();

                if (!$userDelete === 0) {
                    throw new \Exception('Erreur lors de l\'insertion dans la table users');
                }

                $employeDelete = DB::table('employes')
                                    ->where('matricule', '=', $matricule)
                                    ->delete();

                if ($employeDelete === 0) {
                    throw new \Exception('Erreur lors de la suppression dans la table employes');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_user_login($id)
    {
        DB::beginTransaction();

            try {

                $userDelete = DB::table('users')
                                ->where('id', '=', $id)
                                ->delete();

                if (!$userDelete === 0) {
                    throw new \Exception('Erreur lors de la mise a jour dans la table users');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_assurance($id)
    {
        DB::beginTransaction();

            try {

                $assuranceDelete = DB::table('assurance')
                                ->where('idassurance', '=', $id)
                                ->delete();

                if (!$assuranceDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table assurance');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_assureur($id)
    {
        DB::beginTransaction();

            try {

                $assureurDelete = DB::table('assureur')
                                ->where('codeassureur', '=', $id)
                                ->delete();

                if (!$assureurDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table assureur');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_tarif($id)
    {
        DB::beginTransaction();

            try {

                $tarifDelete = DB::table('tarifs')
                                ->where('idtarif', '=', $id)
                                ->delete();

                if (!$tarifDelete === 0) {
                    throw new \Exception('Erreur lors de la suppresion dans la table tarifs');
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_Exd($id)
    {
        $rech = DB::table('testlaboimagerie')
                        ->where('idtestlaboimagerie', '=', $id)
                        ->select('numhospit','numfacbul')
                        ->first();

        if (!$rech) {
            return response()->json(['existe_p' => true]);
        }

        DB::beginTransaction();

            try {

                $delete1 = DB::table('factures')
                                ->where('numfac', '=', $rech->numfacbul)
                                ->delete();

                if (!$delete1) {
                    throw new \Exception('Erreur lors de la suppresion dans la table factures');
                }

                // $rech_testd = DB::table('detailtestlaboimagerie')
                //                 ->where('idtestlaboimagerie', '=', $id)
                //                 ->get();

                // foreach ($rech_testd as $value) {
                    
                //     $delete2 = DB::table('detailtestlaboimagerie')
                //                 ->where('idtestlaboimagerie', '=', $id)
                //                 ->delete();

                //     if (!$delete2) {
                //         throw new \Exception('Erreur lors de la suppresion dans la table detailtestlaboimagerie');
                //     }

                // }

                $delete2 = DB::table('detailtestlaboimagerie')
                            ->where('idtestlaboimagerie', '=', $id)
                            ->delete();

                if (!$delete2) {
                    throw new \Exception('Erreur lors de la suppression dans la table detailtestlaboimagerie');
                }

                $delete3 = DB::table('testlaboimagerie')
                                ->where('idtestlaboimagerie', '=', $id)
                                ->delete();

                if (!$delete3) {
                    throw new \Exception('Erreur lors de la suppresion dans la table testlaboimagerie');
                }


                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_Soinsam($id)
    {
        $rech = DB::table('soins_medicaux')
                        ->where('id_soins', '=', $id)
                        ->select('numhospit','numfac_soins')
                        ->first();

        if (!$rech) {
            return response()->json(['existe_p' => true]);
        }

        DB::beginTransaction();

            try {

                $delete1 = DB::table('factures')
                                ->where('numfac', '=', $rech->numfac_soins)
                                ->delete();

                if (!$delete1) {
                    throw new \Exception('Erreur lors de la suppresion dans la table factures');
                }

                // $rech_testd = DB::table('soins_medicaux_itemsoins')
                //                 ->where('id_soins', '=', $id)
                //                 ->get();

                // foreach ($rech_testd as $value) {
                    
                //     $delete2 = DB::table('soins_medicaux_itemsoins')
                //                 ->where('id_soins', '=', $id)
                //                 ->delete();

                //     if (!$delete2) {
                //         throw new \Exception('Erreur lors de la suppresion dans la table soins_medicaux_itemsoins');
                //     }

                // }

                $rech_testd = DB::table('soins_medicaux_itemsoins')
                                ->where('id_soins', '=', $id)
                                ->get();

                if ($rech_testd->isNotEmpty()) {
                    
                    $delete2 = DB::table('soins_medicaux_itemsoins')
                                ->where('id_soins', '=', $id)
                                ->delete();

                    if (!$delete2) {
                        throw new \Exception('Erreur lors de la suppresion dans la table soins_medicaux_itemsoins');
                    }
                }
                    

                // $rech_testd2 = DB::table('soins_medicaux_itemmedics')
                //                 ->where('id_soins', '=', $id)
                //                 ->get();

                // foreach ($rech_testd2 as $value) {
                    
                //     $delete3 = DB::table('soins_medicaux_itemmedics')
                //                 ->where('id_soins', '=', $id)
                //                 ->delete();

                //     if (!$delete3) {
                //         throw new \Exception('Erreur lors de la suppresion dans la table soins_medicaux_itemmedics');
                //     }

                // }

                $rech_testd2 = DB::table('soins_medicaux_itemmedics')
                                ->where('id_soins', '=', $id)
                                ->get();

                if ($rech_testd2->isNotEmpty()) {
                    
                    $delete3 = DB::table('soins_medicaux_itemmedics')
                                ->where('id_soins', '=', $id)
                                ->delete();

                    if (!$delete3) {
                        throw new \Exception('Erreur lors de la suppresion dans la table soins_medicaux_itemmedics');
                    }

                }

                $delete4 = DB::table('soins_medicaux')
                                ->where('id_soins', '=', $id)
                                ->delete();

                if (!$delete4) {
                    throw new \Exception('Erreur lors de la suppresion dans la table testlaboimagerie');
                }


                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_Pat($matricule)
    {
        $rech = DB::table('patient')
                        ->where('idenregistremetpatient', '=', $matricule)
                        ->first();

        if (!$rech) {
            return response()->json(['existe_p' => true]);
        }

        DB::beginTransaction();

            try {

                $rech_dossier = DB::table('dossierpatient')
                                ->where('idenregistremetpatient', '=', $matricule)
                                ->get();

                if ($rech_dossier->isNotEmpty()) {
                    
                    $delete1 = DB::table('dossierpatient')
                                ->where('idenregistremetpatient', '=', $matricule)
                                ->delete();

                    if (!$delete1) {
                        throw new \Exception('Erreur lors de la suppresion dans la table dossierpatient');
                    }
                }
                    

                $delete2 = DB::table('patient')
                                ->where('idenregistremetpatient', '=', $matricule)
                                ->delete();

                if (!$delete2) {
                    throw new \Exception('Erreur lors de la suppresion dans la table patient');
                }


                DB::commit();
                return response()->json(['success' => true, 'message' => 'Opération éffectuée']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['error' => true, 'message' => $e->getMessage()]);
            }
    }

    public function delete_Hospit($numhospit)
    {
        $admission = DB::table('admission')->where('numhospit', $numhospit)->first();

        if (!$admission) {
            return response()->json(['error' => true, 'message' => 'Hospitalisation non trouvée']);
        }

        // Vérifier si la facture existe
        $fac = DB::table('factures')->where('numfac', '=', $admission->numfachospit)->first();

        if (!$fac) {
            return response()->json(['introuvable' => true, 'message' => 'La facture est introuvable']);
        }

        // Vérifier les conditions avant de poursuivre
        if ($fac->montantpat_verser > 0 || $admission->statut === 'sortie') {
            return response()->json(['impossible' => true,'message' => 'Impossible d\'éffectuée un remise sur cette facture']);
        }

        DB::beginTransaction();

        try {
            // Suppression de la facture liée à l'admission
            $deletedFacture1 = DB::table('factures')->where('numfac', $admission->numfachospit)->delete();
            if ($deletedFacture1 === 0) {
                DB::rollBack();
                return response()->json(['error' => true, 'message' => 'Facture 1 non trouvée']);
            }

            // Récupération de la facturation hospitalisation
            $facturationHospit = DB::table('facturation_hospit')->where('numpchr', $numhospit)->first();
            if ($facturationHospit) {
                if ($facturationHospit->numfac) {
                    $deletedFacture2 = DB::table('factures')->where('numfac', $facturationHospit->numfac)->delete();
                    if ($deletedFacture2 === 0) {
                        DB::rollBack();
                        return response()->json(['error' => true, 'message' => 'Facture 2 non trouvée']);
                    }
                }

                $deletedHospitFacturation = DB::table('facturation_hospit')->where('numpchr', $numhospit)->delete();
                if ($deletedHospitFacturation === 0) {
                    DB::rollBack();
                    return response()->json(['error' => true, 'message' => 'Facturation hospitalisation non supprimée']);
                }
            }

            // statut disponibilite
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

            // Suppression finale de l'admission
            $deletedAdmission = DB::table('admission')->where('numhospit', $numhospit)->delete();
            if ($deletedAdmission === 0) {
                DB::rollBack();
                return response()->json(['error' => true, 'message' => 'Hospitalisation non supprimée']);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    public function delete_fachosp($id)
    {
        $factured = DB::table('facturation_hospit')
            ->join('admission', 'admission.numhospit', '=', 'facturation_hospit.numpchr')
            ->where('facturation_hospit.id_fachosp', '=', $id)
            ->select(
                'facturation_hospit.id_fachosp as id_fachosp',
                'facturation_hospit.idgarhospit as idgarhospit',
                'facturation_hospit.pu as fac_pu',
                'facturation_hospit.montaccorde as fac_montaccorde',
                'facturation_hospit.montrefus as fac_montrefus',
                'admission.numhospit as numhospit',
                'admission.numfachospit as numfac',
                'admission.statut as statut_hospit',
            )
            ->first();

        if (!$factured) {
            return response()->json(['introuvable' => true, 'message' => 'Hospitalisation non trouvée']);
        }

        // Vérifier si la facture existe
        $fac = DB::table('factures')->where('numfac', '=', $factured->numfac)->first();

        if (!$fac) {
            return response()->json(['info' => true, 'message' => "Facture introuvable"]);
        }

        // Vérifier les conditions avant suppression
        if ($fac->montantpat_verser > 0 || $factured->statut_hospit === 'sortie') {
            return response()->json([
                'impossible' => true,
                'message' => "Impossible d'effectuer cette opération car la part patient est supérieur à 0 ou le patient est déjà sortie."
            ]);
        }

        DB::beginTransaction();

        try {

            // Si c'est une prestation pharmacie
            if ($factured->idgarhospit == 2) {

                $medocs = DB::table('orders')
                    ->join('orders_detail', 'orders_detail.order_id', '=', 'orders.id')
                    ->join('medicine', 'medicine.medicine_id', '=', 'orders_detail.product_id')
                    ->where('orders.num_hospit', '=', $factured->numhospit)
                    ->select('orders_detail.id as orderdetail_id')
                    ->get();

                if ($medocs->count() > 0) {

                    foreach ($medocs as $value) {
                        DB::table('orders_detail')
                            ->where('id', $value->orderdetail_id)
                            ->delete();
                    }
                }
            }

            // Suppression de la prestation
            $deletedFacture1 = DB::table('facturation_hospit')->where('id_fachosp', $id)->delete();

            if ($deletedFacture1 === 0) {
                throw new \Exception("Une erreur s'est produite lors de la suppression de la prestation");
            }

            // Nouveaux montants
            $totalNew = $fac->montanttotal - $factured->fac_pu;
            $assuranceNew = $fac->montant_ass - $factured->fac_montaccorde;
            $patientNew = $fac->montant_pat - $factured->fac_montrefus;
            $resteNew = $patientNew - $fac->montantregle_pat;

            if ($totalNew < 0 || $assuranceNew < 0 || $patientNew < 0) {
                throw new \Exception("Impossible de supprimer cette prestation, car les montants deviendraient négatifs");
            }

            // Mise à jour facture
            $updateData = [
                'montanttotal' => $totalNew,
                'montant_ass' => $assuranceNew,
                'montant_pat' => $patientNew,
                'montantreste_pat' => $resteNew,
                'updated_at' => now(),
            ];

            $Update = DB::table('factures')
                ->where('numfac', '=', $factured->numfac)
                ->update($updateData);

            if ($Update === 0) {
                throw new \Exception("Une erreur est survenue lors de la mise à jour des montants");
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Suppression effectuée avec succès']);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['info' => true, 'message' => $e->getMessage()]);
        }
    }


}
