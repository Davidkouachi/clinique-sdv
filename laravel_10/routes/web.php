<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Import des contrôleurs
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Controller;
use App\Http\Controllers\authController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\SocieteController;
use App\Http\Controllers\ReceptionController;

/*
|--------------------------------------------------------------------------
| Routes publiques (sans authentification)
|--------------------------------------------------------------------------
*/

// $prefix = 'amitie/public';

// Route::prefix($prefix)->group(function () {

    // Authentification
    Route::get('/Login', [AuthController::class, 'login'])->name('login');
    Route::post('/trait_login', [AuthController::class, 'trait_login'])->name('trait_login');

    // Rafraîchissement CSRF pour AJAX
    Route::get('/refresh-csrf', function () {
        return response()->json(['csrf_token' => csrf_token()]);
    });
    
// });

/*
|--------------------------------------------------------------------------
| Routes protégées par authentification
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
// Route::prefix($prefix)->middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Accueil & pages générales
    |--------------------------------------------------------------------------
    */
    Route::get('/', [Controller::class, 'index_accueil'])->name('index_accueil');
    Route::get('/A propos', [Controller::class, 'index_propos'])->name('index_propos');

    /*
    |--------------------------------------------------------------------------
    | Authentification & session utilisateur
    |--------------------------------------------------------------------------
    */
    Route::post('/trait_auth', [authController::class, 'trait_auth'])->name('trait_auth');
    Route::get('/deconnecter', [authController::class, 'deconnecter'])->name('deconnecter');
    Route::get('/Mot de passe oublié', [authController::class, 'mdp_oublie_email'])->name('mdp_oublie_email');

    /*
    |--------------------------------------------------------------------------
    | Gestion des assurances
    |--------------------------------------------------------------------------
    */
    Route::get('/Assurances', [Controller::class, 'assurance_liste'])->name('assurance_liste');

    /*
    |--------------------------------------------------------------------------
    | Gestion des assureurs
    |--------------------------------------------------------------------------
    */
    Route::get('/Assureur', [Controller::class, 'assureur_liste'])->name('assureur_liste');

    /*
    |--------------------------------------------------------------------------
    | Sociétés
    |--------------------------------------------------------------------------
    */
    Route::get('/Société', [Controller::class, 'societe_liste'])->name('societe_liste');

    /*
    |--------------------------------------------------------------------------
    | Taux & types de produits
    |--------------------------------------------------------------------------
    */
    Route::get('/Garanties&Tarifs', [Controller::class, 'garantie_tarif'])->name('garantie_tarif');
    /*
    |--------------------------------------------------------------------------
    | Chambres & lits
    |--------------------------------------------------------------------------
    */
    Route::get('/Nouvel Chambre', [Controller::class, 'chambre_new'])->name('chambre_new');
    Route::get('/Nouvel Lit', [Controller::class, 'lit_new'])->name('lit_new');
    /*
    |--------------------------------------------------------------------------
    | Utilisateurs & personnel médical
    |--------------------------------------------------------------------------
    */
    Route::get('/Nouvel Medecin', [Controller::class, 'medecin_new'])->name('medecin_new');
    Route::get('/Spécialité', [Controller::class, 'specialite'])->name('specialite');
    Route::get('/Employés', [Controller::class, 'utilisateur'])->name('utilisateur');
    Route::get('/Utilisateurs', [Controller::class, 'user_login'])->name('user_login');

    /*
    |--------------------------------------------------------------------------
    | Patients & consultations
    |--------------------------------------------------------------------------
    */
    Route::get('/Patient', [Controller::class, 'patient_liste'])->name('patient_liste');
    Route::get('/Consultation', [Controller::class,'consultation_liste'])->name('consultation_liste');
    Route::get('/Rendez-Vous', [Controller::class, 'rdv_two_day'])->name('rdv_two_day');

    /*
    |--------------------------------------------------------------------------
    | Admissions & hospitalisation
    |--------------------------------------------------------------------------
    */
    Route::get('/Type Admission', [Controller::class, 'typeadmission_new'])->name('typeadmission_new');
    Route::get('/Nature Admission', [Controller::class, 'natureadmission_new'])->name('natureadmission_new');
    Route::get('/Hospitalisation', [Controller::class, 'hospitalisation'])->name('hospitalisation');

    /*
    |--------------------------------------------------------------------------
    | Actes & soins
    |--------------------------------------------------------------------------
    */
    Route::get('/Acte', [Controller::class, 'acte_new'])->name('acte_new');
    Route::get('/Type Acte', [Controller::class, 'typeacte_new'])->name('typeacte_new');
    Route::get('/Soins Ambulantoires', [Controller::class, 'soinsam'])->name('soinsam');
    Route::get('/Type de soins',[Controller::class,'typesoins_new'])->name('typesoins_new');
    Route::get('/Soins Infirmiers', [Controller::class, 'soinsinfirmier_new'])->name('soinsinfirmier_new');
    Route::get('/Examens', [Controller::class, 'examen'])->name('examen');

    /*
    |--------------------------------------------------------------------------
    | Produits pharmacie
    |--------------------------------------------------------------------------
    */
    Route::get('/Produit Pharmacie', [Controller::class, 'produit_new'])->name('produit_new');

    /*
    |--------------------------------------------------------------------------
    | Facturation & comptabilité
    |--------------------------------------------------------------------------
    */
    Route::get('/Factures Emises', [Controller::class, 'facture_emise'])->name('facture_emise');
    Route::get('/Depôts de factures', [Controller::class, 'facture_depot'])->name('facture_depot');
    Route::get('/Factures', [Controller::class, 'facture_deposer'])->name('facture_deposer');
    Route::get('/Facture Impayer', [Controller::class, 'facture_impayer'])->name('facture_impayer');
    Route::get('/Liste Facture', [Controller::class, 'facture_liste'])->name('facture_liste');

    Route::get('/Caisse', [Controller::class, 'caisse'])->name('caisse');
    Route::get('/Opération de Caisse', [Controller::class, 'operation_caisse'])->name('operation_caisse');
    Route::get('/Tableau de Bord Comptabilité', [Controller::class, 'comptable'])->name('comptable');

    /*
    |--------------------------------------------------------------------------
    | États & rapports
    |--------------------------------------------------------------------------
    */
    Route::get('/Etats Actes', [Controller::class, 'etat_acte'])->name('etat_acte');
    Route::get('/Etats Factures', [Controller::class, 'etat_facture'])->name('etat_facture');
    Route::get('/Etats Caisses', [Controller::class, 'etat_caisse'])->name('etat_caisse');
    Route::get('/Etats Produits Utilisés', [Controller::class, 'etat_prod_pharmacie'])->name('etat_prod_pharmacie');
    Route::get('/UD Factures', [Controller::class, 'ud_facture'])->name('ud_facture');

    /*
    |--------------------------------------------------------------------------
    | Planning & horaires
    |--------------------------------------------------------------------------
    */
    Route::get('/Horaires Médecin', [Controller::class, 'horaire_medecin'])->name('horaire_medecin');

});
