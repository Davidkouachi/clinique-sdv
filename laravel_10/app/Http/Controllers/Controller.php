<?php

namespace App\Http\Controllers;

class Controller 
{
    public function index_accueil()
    {
        return view('accueil.tableaubord');
    }

    public function acte_new()
    {
        return view('prestation.acte');
    }

    public function typeacte_new()
    {
        return view('prestation.typeacte');
    }

    // --------------------------------------------------------

    public function patient_liste()
    {
        return view('accueil.patient');
    }

    public function consultation_liste()
    {
        return view('accueil.consultation');
    }

    public function hospitalisation()
    {
        return view('prestation.hospitalisation.index');
    }

    public function societe_liste()
    {
        return view('accueil.societe');
    }

    public function assureur_liste()
    {
        return view('accueil.assureur');
    }

    public function assurance_liste()
    {
        return view('accueil.assurance');
    }

    public function typeadmission_new()
    {
        return view('prestation.hospitalisation.typeadmission');
    }

    public function natureadmission_new()
    {
        return view('prestation.hospitalisation.natureadmission');
    }

    // --------------------------------------------------------

    public function produit_new()
    {
        return view('prestation.pharmacie.produit_pharmacie');
    }

    // --------------------------------------------------------

    public function soinsam()
    {
        return view('prestation.soinsam.index');
    }

    public function typesoins_new()
    {
        return view('prestation.soinsam.typesoins');
    }

    public function soinsinfirmier_new()
    {
        return view('prestation.soinsam.soinsinfirmier');
    }

    // -----------------------------------------------------

    public function examen()
    {
        return view('prestation.examen.index');
    }

    // --------------------------------------------------------

    public function comptable()
    {
        return view('finance.comptabilite.tableaubord');
    }

    public function operation_caisse()
    {
        return view('finance.comptabilite.operation');
    }

    public function facture_impayer()
    {
        return view('finance.caisse.impayer');
    }

    public function facture_liste()
    {
        return view('finance.caisse.liste');
    }

    // ---------------------------------------------------

    public function facture_emise()
    {
        return view('finance.facture.facture_emise');
    }

    public function facture_depot()
    {
        return view('finance.facture.facture_depot');
    }

    public function facture_deposer()
    {
        return view('finance.facture.facture_deposer');
    }

    // --------------------------------------------------------

    public function etat_acte()
    {
        return view('etat.acte');
    }

    public function etat_facture()
    {
        return view('etat.facture');
    }

    public function etat_caisse()
    {
        return view('etat.caisse');
    }

    public function etat_prod_pharmacie()
    {
        return view('etat.produit');
    }

    // -----------------------------------------------------------

    public function rdv_two_day()
    {
        return view('accueil.rdv_two_day');
    }

    // ------------------------------------------------------------

    public function garantie_tarif()
    {
        return view('prestation.garantie_tarif');
    }

    // ------------------------------------------------------------

    public function chambre_new()
    {

        return view('prestation.hospitalisation.chambre');
    }

    public function lit_new()
    {
        return view('prestation.hospitalisation.lit');
    }

    // ------------------------------------------------------------

    public function medecin_new()
    {
        return view('medecin.index');
    }

    public function horaire_medecin()
    {
        return view('medecin.horaire');
    }

    public function specialite()
    {
        return view('medecin.specialite');
    }

    // ------------------------------------------------------------

    public function utilisateur()
    {
        return view('config.utilisateur');
    }

    public function user_login()
    {
        return view('config.user_login');
    }

    public function ud_facture()
    {
        return view('config.ud_facture');
    }

    // ------------------------------------------------------------

    public function index_propos()
    {
        return view('propos');
    }
}
