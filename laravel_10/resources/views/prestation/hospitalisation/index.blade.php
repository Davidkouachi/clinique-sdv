@extends('app')

@section('titre', 'Acceuil')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Accueil
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 cadreTitle">
                <div class="card-body">
                    <div class="py-4 px-3 text-white">
                        <h5>Les statistiques d'aujourd'hui.</h5>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-info rounded-5 me-3">
                                    <i class="ri-walk-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_hos"></h2>
                                    <p class="m-0">Hospitalisation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-header" hidden >
                    <h5 class="card-title">Hospitalisation</h5>
                </div>
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-hotel-bed-line me-2"></i>
                                    Nouvelle admission
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-threeAAA" data-bs-toggle="tab" href="#threeAAA" role="tab" aria-controls="threeAAA" aria-selected="true">
                                    <i class="ri-list-settings-fill me-2"></i>
                                    Disponibilité Chambre & Lit
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="alert bg-warning text-white alert-dismissible d-flex align-items-center fade show fade-in-out" role="alert">
                                <i class="ri-alert-line fs-3 me-2 lh-1"></i>
                                <div>                                
                                    <h6>ATTENTION : </h6> 
                                    Il est recommender d'ajouter le prix total de la chambre occupé (si le besoin se presente).
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="tab-pane active show fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Nouvelle admission</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/hospitalisation.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Patient</label>
                                            <select class="form-select select2" id="patient_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">N° prise en charge</label>
                                            <div class="input-group">
                                                <span class="input-group-text">N°</span>
                                                <input type="text" class="form-control" id="numcode" placeholder="Facultatif">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Medecin</label>
                                            <select class="form-select select2" id="medecin_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Type admission</label>
                                            <select class="form-select select2" id="id_typeadmission"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nature Admission</label>
                                            <select class="form-select select2" id="id_natureadmission">
                                                <option value="">Selectioner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Chambre à occuper</label>
                                            <select class="form-select select2" id="id_chambre"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Lit à occuper</label>
                                            <select class="form-select select2" id="id_lit">
                                                <option value="">Selectioner</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date d'entrée
                                            </label>
                                            <input type="date" class="form-control" id="date_entrer" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date de sortie probable
                                            </label>
                                            <input type="date" class="form-control" id="date_sortie" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Nombre de jours
                                            </label>
                                            <input readonly type="tel" class="form-control" id="nbre_jour" value="0">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Motif
                                            </label>
                                            <input type="text" class="form-control" id="motif" placeholder="Saisie Obligatoire">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button id="btn_eng_hosp" class="btn btn-success">
                                                Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade " id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des hospitalisations
                                                </h5>
                                            </div>
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Du</span>
                                                        <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">au</span>
                                                        <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">Statut</span>
                                                        <select class="form-select me-1" id="statut">
                                                            <option selected value="tous">Tous</option>
                                                            <option value="en cours">Hospitaliser</option>
                                                            <option value="sortie">Liberé</option>
                                                        </select>
                                                        <a id="btn_search_table" class="btn btn-outline-success ms-auto me-1">
                                                            <i class="ri-search-2-line"></i>
                                                        </a>
                                                        <a id="btn_impr_table" class="btn btn-outline-warning ms-auto" style="display: none;">
                                                            <i class="ri-printer-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="">
                                                        <table id="Table_day" class="table Table_hos">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Type</th>
                                                                    <th scope="col">Nature</th>
                                                                    <th scope="col">N° identifiant</th>
                                                                    <th scope="col">Patient</th>
                                                                    <th scope="col">Date Entrée</th>
                                                                    <th scope="col">Date Sortie</th>
                                                                    <th scope="col">Nbre jours</th>
                                                                    <th scope="col">Médecin</th>
                                                                    <th scope="col">Statut</th>
                                                                    <th scope="col">Montant Total</th>
                                                                    <th scope="col">N° Facture</th>
                                                                    <th scope="col"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="threeAAA" role="tabpanel" aria-labelledby="tab-threeAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Disponibilité des Lits</h5>
                                </div>
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                                    <i class="ri-loop-left-line"></i>
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table table-hover table-sm Table_lit">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">N° Lit</th>
                                                                    <th scope="col">Statut</th>
                                                                    <th scope="col">N° Chambre</th>
                                                                    <th scope="col">Catégorie</th>
                                                                    <th scope="col">Prix</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Add" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Pharmacie
                </h5>
                <button type="button" id="close_modal_produit" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_add">
                <div class="row gx-3 justify-content-center align-items-center">
                    <div class="col-12 mb-3 text-center">
                        <button type="button" id="add_select" class="btn btn-info">
                            <i class="ri-sticky-note-add-line"></i>
                            Ajouter un Produit
                        </button>
                    </div>
                    <div class="col-12" id="div_utilise_assu_produit" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Utiliser l'assurance</label>
                            <select class="form-select" id="utlise_assu_produit">
                                <option selected value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12" id="contenu">

                    </div>
                    <div class="col-12 row" id="div_btn_pro">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Taux de couverture</span>
                                <input readonly type="tel" class="form-control" id="taux_produit" placeholder="Montant Total">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Montant Total</span>
                                <input readonly type="tel" class="form-control" id="montant_total_produit" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Part Assurance</span>
                                <input readonly type="tel" class="form-control" id="part_assurance_produit" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Part patient</span>
                                <input readonly type="tel" class="form-control" id="part_patient_produit" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <input type="hidden" id="id_hos_produit">
                        <div class="col-12">
                            <button type="button" id="btn_eng_produit" class="btn btn-outline-success">
                                Enregistrer
                                <i class="ri-send-plane-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="AddGaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Prestation
                </h5>
                <button type="button" id="close_modal_garantie" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_add">
                <div class="row gx-3 justify-content-center align-items-center">
                    <div class="col-12 mb-3 text-center">
                        <button type="button" id="add_select_garantie" class="btn btn-info">
                            <i class="ri-sticky-note-add-line"></i>
                            Ajouter une garantie
                        </button>
                    </div>
                    <div class="col-12" id="div_utilise_assu_garantie" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Utiliser l'assurance</label>
                            <select class="form-select" id="utlise_assu_garantie">
                                <option selected value="non">Non</option>
                                <option value="oui">Oui</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12" id="contenu_garantie">

                    </div>
                    <div class="col-12 row" id="div_btn_garantie">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Taux de couverture</span>
                                <input readonly type="tel" class="form-control" id="taux_garantie" placeholder="Montant Total">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Montant Total</span>
                                <input readonly type="tel" class="form-control" id="montant_total_garantie" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Part Assurance</span>
                                <input readonly type="tel" class="form-control" id="part_assurance_garantie" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <span class="input-group-text w-25">Part patient</span>
                                <input readonly type="tel" class="form-control" id="part_patient_garantie" placeholder="Montant Total">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                        <input type="hidden" id="id_hos_garantie">
                        <div class="col-12">
                            <button type="button" id="btn_eng_garantie" class="btn btn-outline-success">
                                Enregistrer
                                <i class="ri-send-plane-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_produit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Produit Pharmacie Utilisé
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableP" style="display: none;">
                                            <table class="table table-bordered" id="TableP">
                                                <thead>
                                                    <tr>
                                                        <th>Produit utilisé</th>
                                                        <th style="width: 150px;" >
                                                        Assurance</th>
                                                        <th style="width: 150px;" >Prix unitaire</th>
                                                        <th style="width: 50px;" >Quantité</th>
                                                        <th style="width: 150px;" >Montant Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableP" style="display: none;">
                                            <p class="text-center" >
                                                Aucun Produit utilisé pour le moment
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_garantie" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Prestations
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="div_TableP_garantie" style="display: none;">
                                            <table class="table table-bordered" id="TableP_garantie">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Prestation</th>
                                                        <th>Montant Total</th>
                                                        <th>Part Assurance</th>
                                                        <th>Part Patient</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="message_TableP_garantie" style="display: none;">
                                            <p class="text-center" >
                                                Aucunes données n'a été trouvé
                                            </p>
                                        </div>
                                        <div id="div_Table_loaderP_garantie" style="display: none;">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                <strong>Chargement des données...</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="updateChambreForm">
                    <input type="hidden" id="IdModif">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Du</label>
                            <input readonly type="date" class="form-control" id="date1M" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Au</label>
                            <input type="date" class="form-control" id="date2M" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>                  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="MdeleteHospit" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette Hospitalisation ?
                <input type="hidden" id="IddeleteHospit">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtnHospit" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/hospitalisation.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        let cachedProduitsHos = {};
        let cachedGaranHos = {};
        let cacheddataHos = [];
        let cacheddataHosDate1 = '';
        let cacheddataHosDate2 = '';

        Statistique();
        select_patient();
        select_medecin();
        select_chambre();
        select_typeadmission();
        select_natureadmission();
        select_produit();
        select_garantie_hos();

        $("#id_chambre").on("change", select_lit);
        $('#btn_eng_hosp').on('click', eng_hosp);
        $("#updateBtn").on("click", updatee);

        $('#patient_id').on('change', function() {
            rech_dosier($(this).val()); 
        });

        var inputs = ['numcode'];
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id); // Get each element by its ID

            // Allow only numeric input (and optionally some special keys like backspace or delete)
            inputElement.addEventListener('keypress', function(event) {
                const key = event.key;
                // Allow numeric keys, backspace, and delete
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });
            // Alternatively, for more comprehensive input validation, use input event listener
            inputElement.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        $('#prix_pres').on('input', function() {
            $('#div_calcul').hide();
        });

        $('#date_entrer').on('change', function() {
            const date1 = $(this).val(); // Récupérer la valeur de date_entrer
            
            if (date1) {
                // Mettre à jour la valeur et le min de date_sortie
                $('#date_sortie').val(date1);
                $('#date_sortie').attr('min', date1);
            }

            calculerJours();
        });

        $('#date_sortie').on('change', function() {
            const date2 = $(this).val(); // Récupérer la valeur de date_sortie
            const date1 = $('#date_entrer').val(); // Récupérer la valeur de date_entrer

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1); // Réinitialiser la date_sortie à date_entrer
            }

            calculerJours();
        });

        $('#searchDate1').on('change', function() {
            const date1 = $(this).val(); // Récupérer la valeur de date_entrer
            
            if (date1) {
                // Mettre à jour la valeur et le min de date_sortie
                $('#searchDate2').val(date1);
                $('#searchDate2').attr('min', date1);
            }
        });

        $('#searchDate2').on('change', function() {
            const date2 = $(this).val(); // Récupérer la valeur de date_sortie
            const date1 = $('#searchDate1').val(); // Récupérer la valeur de date_entrer

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1); // Réinitialiser la date_sortie à date_entrer
            }
        });

        $("#deleteBtnHospit").on("click", delete_hospit);

        const table_lit = $('.Table_lit').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_lit`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network lit_hopital.');
                    }
                });
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'code', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/lit.avif') }}"  class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'statut',
                    render: function(data) {
                        return data === 'indisponible' 
                            ? `<span class="badge bg-danger">${data}</span>` 
                            : `<span class="badge bg-success">${data}</span>`;
                    },
                    searchable: true
                },
                { 
                    data: 'code_ch', 
                    render: (data) => `CH-${data}`,
                    searchable: true, 
                },
                { 
                    data: 'type',
                    searchable: true, 
                },
                { 
                    data: 'prix', 
                    render: (data) => `${data} Fcfa`,
                    searchable: true, 
                },
            ],
            ...dataTableConfig,
        });

        $('#btn_refresh_table').on('click', function() {
            table_lit.ajax.reload(null, false);
        });

        $('#btn_impr_table').on('click', function() {
            pdfFacturehosList(cacheddataHos, cacheddataHosDate1, cacheddataHosDate2)
        });

        const table_hos = $('.Table_hos').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {

                $('#btn_impr_table').hide();

                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                const statut = $('#statut').val();
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_hopital/${date1}/${date2}/${statut}`,
                    type: 'GET',
                    success: function(response) {

                        const donnee = response.data;

                        if (donnee.length > 0) {
                            $('#btn_impr_table').show();
                        }

                        cacheddataHos = [];
                        cacheddataHos = donnee;
                        cacheddataHosDate1 = formatDate(date1);
                        cacheddataHosDate2 = formatDate(date2);

                        callback({ data: donnee });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network list_hopital.');
                    }
                });
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'type_hospit', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/hospitalisation.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                // { 
                //     data: 'nature',
                //     searchable: true, 
                // },
                { 
                    data: 'nature_hospit',
                    searchable: true,
                },
                { 
                    data: 'numhospit',
                    searchable: true,
                },
                { 
                    data: 'patient',
                    searchable: true,
                },
                { 
                    data: 'dateentree', 
                    render: (data) => `${formatDate(data)}`,
                    searchable: true, 
                },
                { 
                    data: 'datesortie', 
                    render: (data) => `${formatDate(data)}`,
                    searchable: true, 
                },
                { 
                    data: 'nbredejrs', 
                    render: (data) => `${data}`,
                    searchable: true, 
                },
                { 
                    data: 'medecin',
                    // render: (data) => {
                    //     if (data) {
                    //         // Diviser les mots et récupérer les deux premiers
                    //         const words = data.split(' ');
                    //         return `${words.slice(0, 1).join(' ')}`;
                    //     }
                    //     return ''; // Si `data` est vide ou invalide
                    // },
                    searchable: true, 
                },
                { 
                    data: 'statut',
                    render: function(data) {
                        return data === 'en cours' 
                            ? `<span class="badge bg-warning">en cours</span>` 
                            : `<span class="badge bg-success">sortie</span>`;
                    },
                    searchable: true
                },
                { 
                    data: 'montant_total', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'numfachospit', 
                    render: (data) => `${data}`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                ${row.statut === 'en cours' ? 
                                `<li>
                                    <a href="#" class="dropdown-item text-success" id="add" data-bs-toggle="modal" data-bs-target="#Add"
                                        data-numhospit="${row.numhospit}"
                                        data-taux="${row.taux}"
                                    >
                                        <i class="ri-medicine-bottle-line"></i>
                                        Nouveau produit(s)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-success" id="addGaran" data-bs-toggle="modal" data-bs-target="#AddGaran"
                                        data-numhospit="${row.numhospit}"
                                        data-taux="${row.taux}"
                                    >
                                        <i class="ri-shopping-cart-2-line"></i>
                                        Nouvelle prestation
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="modifDate" data-bs-toggle="modal" data-bs-target="#Mmodif"
                                        data-numhospit="${row.numhospit}"
                                        data-date1="${row.dateentree}"
                                        data-date2="${row.datesortie}"
                                    >
                                        <i class="ri-alarm-line"></i>
                                        Modifier date
                                    </a>
                                </li>` : ''}
                                <li>
                                    <a href="#" class="dropdown-item text-warning" id="detail_produit" data-bs-toggle="modal" data-bs-target="#Detail_produit"
                                        data-numhospit="${row.numhospit}"
                                    >
                                        <i class="ri-health-book-line"></i>
                                        Produit(s) utilisé(s)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-warning" id="detail_garantie" data-bs-toggle="modal" data-bs-target="#Detail_garantie"
                                        data-numhospit="${row.numhospit}"
                                    >
                                        <i class="ri-briefcase-4-line"></i>
                                        Prestations demandée(s)
                                    </a>
                                </li>
                                ${row.montant_total > 0 ? 
                                `<li>
                                    <a href="#" class="dropdown-item text-info" id="fiche"
                                        data-numhospit="${row.numhospit}"
                                    >
                                        <i class="ri-printer-line"></i>
                                        Imprimer facture
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="fiche_medocs"
                                        data-numhospit="${row.numhospit}"
                                    >
                                        <i class="ri-printer-line"></i>
                                        Imprimer facture produit
                                    </a>
                                </li>` : `` }
                                ${row.statut === 'en cours' ? 
                                    `<li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#MdeleteHospit" id="deleteHospit" data-numhospit="${row.numhospit}" 
                                        >
                                            <i class="ri-delete-bin-line"></i>
                                            Supprimer
                                        </a>
                                    </li>` : 
                                ''}
                            </ul>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig,
            initComplete: function(settings, json) {
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() {

            $('.Table_hos').on('click', '#add', function() {
                const numhospit = $(this).data('numhospit');
                const taux = $(this).data('taux');
                        
                document.getElementById('id_hos_produit').value = numhospit;
                document.getElementById('montant_total_produit').value = "0";
                document.getElementById('taux_produit').value = taux;
                document.getElementById('part_assurance_produit').value = "0";
                document.getElementById('part_patient_produit').value = "0";

                if (taux > 0) {
                    $('#div_utilise_assu_produit').show();
                    $('#utlise_assu_produit').find('option[value="oui"]').show();
                } else {
                    $('#div_utilise_assu_produit').hide();
                    $('#utlise_assu_produit').find('option[value="oui"]').hide();
                }

                const contenuDiv = document.getElementById('contenu');
                contenuDiv.innerHTML = '';
                
                addSelect(contenuDiv, cachedProduitsHos);
            });

            $('.Table_hos').on('click', '#addGaran', function() {
                const numhospit = $(this).data('numhospit');
                const taux = $(this).data('taux');
                        
                document.getElementById('id_hos_garantie').value = numhospit;
                document.getElementById('montant_total_garantie').value = "0";
                document.getElementById('taux_garantie').value = taux;
                document.getElementById('part_assurance_garantie').value = "0";
                document.getElementById('part_patient_garantie').value = "0";

                if (taux > 0) {
                    $('#div_utilise_assu_garantie').show();
                    $('#utlise_assu_garantie').find('option[value="oui"]').show();
                } else {
                    $('#div_utilise_assu_garantie').hide();
                    $('#utlise_assu_garantie').find('option[value="oui"]').hide();
                }

                const contenuDiv = document.getElementById('contenu_garantie');
                contenuDiv.innerHTML = '';

                addSelectGarantie(contenuDiv, cachedGaranHos)
            });

            $('.Table_hos').on('click', '#modifDate', function() {
                const id = $(this).data('numhospit');
                const date1 = $(this).data('date1');
                const date2 = $(this).data('date1');

                document.getElementById('IdModif').value = `${id}`;
                document.getElementById('date1M').value = `${date1}`;
                document.getElementById('date2M').value = `${date2}`;
            });

            $('.Table_hos').on('click', '#detail_produit', function() {

                const numhospit = $(this).data('numhospit');

                const tableBodyP = document.querySelector('#TableP tbody');
                const messageDivP = document.getElementById('message_TableP');
                const tableDivP = document.getElementById('div_TableP');
                const loaderDivP = document.getElementById('div_Table_loaderP');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch($('#url').attr('content') +`/api/list_facture_hos_d/${numhospit}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        const factureds = data.factured;

                        tableBodyP.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';

                            // Loop through each item in the chambre array
                            let prix = 0;
                            let qte = 0;
                            let total = 0;

                            factureds.forEach((item, index) => {

                                const prixTotal = parseFloat(item.prix_t) || 0;
                                const prixUnitaire = parseFloat(item.prix_u) || 0;
                                const quantite = parseInt(item.quantite) || 0;

                                prix += prixUnitaire;
                                qte += quantite;
                                total += prixTotal;
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.name}</h6>
                                    </td>
                                    <td>
                                        <h6>
                                            ${item.partassurance > 0 ? `Oui` : `Non` }
                                        </h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixUnitaire)} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${quantite}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixTotal)} Fcfa</h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyP.appendChild(row);

                            }); 

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="2">
                                    <h5 class="mt-4 text-success">
                                        TOTAL
                                    </h5>
                                </td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${formatPriceT(prix)} Fcfa
                                    </h5>
                                </td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${qte}
                                    </h5>
                                </td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${formatPriceT(total)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="4">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Le Montant Total des produits utilisés
                                        seront ajouter au montant total de la
                                        facture.
                                    </p>
                                </td>
                            `;

                            tableBodyP.appendChild(row3);

                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivD.style.display = 'none';
                        messageDivD.style.display = 'block';
                        tableDivD.style.display = 'none';
                    });
            });

            $('.Table_hos').on('click', '#detail_garantie', function() {

                const numhospit = $(this).data('numhospit');

                const tableBodyP = document.querySelector('#TableP_garantie tbody');
                const messageDivP = document.getElementById('message_TableP_garantie');
                const tableDivP = document.getElementById('div_TableP_garantie');
                const loaderDivP = document.getElementById('div_Table_loaderP_garantie');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch($('#url').attr('content') +`/api/list_facture_hos_d2/${numhospit}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        const factureds = data.factured;

                        tableBodyP.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';

                            // Loop through each item in the chambre array
                            let total = 0;
                            let totalAssurance = 0;
                            let totalPatient = 0;

                            factureds.forEach((item, index) => {

                                const prixTotal = parseFloat(item.prix) || 0;
                                const prixAssurance = parseFloat(item.prix_ass) || 0;
                                const prixPatient = parseFloat(item.prix_pat) || 0;

                                total += prixTotal;
                                totalAssurance += prixAssurance;
                                totalPatient += prixPatient;
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td>
                                        <a href="#" data-id_fachosp=${item.id} class="btn btn-outline-danger" id="delete_fachospit">
                                            <i class="ri-delete-bin-line"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <h6>${item.name}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixTotal)} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixAssurance)} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixPatient)} Fcfa</h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyP.appendChild(row);

                            }); 

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="2">&nbsp;</td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${formatPriceT(total)} Fcfa
                                    </h5>
                                </td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${totalAssurance}
                                    </h5>
                                </td>
                                <td colspan="1" >
                                    <h5 class="mt-4 text-success">
                                        ${formatPriceT(totalPatient)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="4">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Le Montant Total des produits utilisés
                                        seront ajouter au montant total de la
                                        facture.
                                    </p>
                                </td>
                            `;

                            tableBodyP.appendChild(row3);

                            $('#TableP_garantie').off('click', '#delete_fachospit').on('click', '#delete_fachospit', function(event) {
                                event.preventDefault();

                                const id = $(this).data('id_fachosp');

                                var modal = bootstrap.Modal.getInstance(document.getElementById('Detail_garantie'));
                                modal.hide();

                                var preloader_ch = `
                                    <div id="preloader_ch">
                                        <div class="spinner_preloader_ch"></div>
                                    </div>
                                `;
                                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                                $.ajax({
                                    url: $('#url').attr('content') +'/api/delete_fachosp/' + id,
                                    method: 'GET',
                                    success: function(response) {

                                        $('#preloader_ch').remove();

                                        if (response.success) {
                                            $('.Table_hos').DataTable().ajax.reload(null, true);
                                            Swal.fire("Succès!", "Opération effectuée.", "success");
                                        } 
                                        else if (response.introuvable || response.impossible || response.info) {
                                            showAlert("Alert", response.message, "warning");
                                        } 
                                        else {
                                            showAlert("Alert", "Une erreur est survenue", "error");
                                        }
                                    },
                                    error: function() {
                                        $('#preloader_ch').remove();
                                        showAlert("Erreur", "Une erreur est survenue", "error");
                                    }
                                });
                            });


                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivD.style.display = 'none';
                        messageDivD.style.display = 'block';
                        tableDivD.style.display = 'none';
                    });
            });

            $('.Table_hos').on('click', '#fiche', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const numhospit = $(this).data('numhospit');

                fetch($('#url').attr('content') +`/api/detail_hos/${numhospit}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const hopital = data.hopital;
                        const prestation = data.prestation;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        pdfFacturehos(hopital, prestation);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_hos').on('click', '#fiche_medocs', function() {

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const numhospit = $(this).data('numhospit');

                fetch($('#url').attr('content') +`/api/list_facture_hos_d/${numhospit}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        const hopital = data.hopital;
                        const factureds = data.factured;

                        if (factureds.length > 0) {

                            pdfFacturehosdetailProd(hopital, factureds);

                        } else {
                            showAlert('Alert', 'Aucun produit n\'a été trouvé.', 'warning');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        showAlert('Alert', 'Erreur lors du chargement des données.', 'error');
                    });
            });

            $('.Table_hos').on('click', '#deleteHospit', function() {
                const numhospit = $(this).data('numhospit');

                $('#IddeleteHospit').val(numhospit);
            });
        }

        $('#btn_search_table').on('click', function() {
            table_hos.ajax.reload(null, false);
        });

        function delete_hospit() {

            const numhospit = document.getElementById('IddeleteHospit').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('MdeleteHospit'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_Hospit/'+numhospit,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('.Table_hos').DataTable().ajax.reload(null, true);
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Echec de l\'opération', "error");
                    } else if (response.introuvable) {
                        showAlert("Alert", 'La facture liée à cette hospitalisation est introuvable', "info");
                    } else if (response.impossible) {
                        showAlert("Alert", 'Impossible de supprimé cette hospitalisation', "warning");
                    }
                
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la suppression.','error');
                }
            });
        }

        function select_patient()
        {
            const selectElement = $('#patient_id');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/name_patient_reception',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.name.forEach(item => {
                        const option = $('<option>', {
                            value: item.idenregistremetpatient,
                            text: `${item.idenregistremetpatient} | ` + `${item.nomprenomspatient}`
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des patients');
                }
            });
        }

        function select_garantie_hos()
        {
            $.ajax({
                url: $('#url').attr('content') +'/api/select_garantie_hos',
                method: 'GET',
                success: function (data) {
                    cachedGaranHos = data.hos;
                },
                error: function () {
                    console.error('Erreur lors du chargement des produits.');
                }
            });
        }

        function rech_dosier(id) {

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/rech_patient',
                method: 'GET',
                data: { id: id },
                success: function(response) {

                    if (response.existep) {
                        showAlert('Alert', 'Ce patient n\'existe pas.', 'error');
                    } else if (response.success) {

                        const item = response.patient;

                        const $btn_eng_hosp = $('#btn_eng_hosp');
                        const $div_numcode = $('#div_numcode');

                        const url = $('#url').attr('content') +'/api/rech_hos_patient/' + item.idenregistremetpatient;

                        $.ajax({
                            url: url,
                            method: 'GET',
                            success: function(data) {

                                $("#preloader_ch").remove();

                                if (data.existe) {
                                    
                                    $('#patient_id').val(null).trigger('change.select2');
                                    $('#numcode').val('');
                                    showAlert('Alert', 'Ce patient est déjà hospitalisé.', 'info');
                                    return false;
                                }

                                $('#numcode').val('');

                                if (item.assure == 1) {
                                    $div_numcode.show(); // Afficher la section pour numéro d'assurance
                                } else {
                                    $div_numcode.hide(); // Masquer la section pour numéro d'assurance
                                }

                            },
                            error: function() {
                                console.error('Erreur lors de la recherche d\'hospitalisation.');
                            }
                        });
                    }
                },
                error: function() {
                    showAlert('Alert', 'Une erreur est survenue lors de la recherche.', 'error');
                }
            });
        }

        function select_medecin() {

            const $selectElement = $('#medecin_id');
            $selectElement.empty(); // Clear existing options

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_list_medecin',
                method: 'GET',
                success: function(data) {

                    const medecin = data.medecin;

                    medecin.forEach(item => {
                        const option = $('<option>', {
                            value: item.codemedecin,
                            text: item.nomprenomsmed
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des médecins');
                }
            });
        }

        function select_typeadmission() {

            const $selectElement = $('#id_typeadmission');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_typeadmission',
                method: 'GET',
                success: function(data) {
                    data.typeadmission.forEach(item => {
                        const option = $('<option>', {
                            value: item.idtypehospit,
                            text: item.nomtypehospit
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des types d\'admission');
                }
            });
        }

        function select_chambre() {
            const $selectElement = $('#id_chambre');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_chambre',
                method: 'GET',
                success: function(data) {
                    data.chambre.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: `CH-${item.code}`
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des chambres');
                }
            });
        }

        function select_lit() {
            const $selectElement = $('#id_lit');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            const chambreId = $('#id_chambre').val();
            if (chambreId) {
                $.ajax({
                    url: $('#url').attr('content') +'/api/lit_select/' + chambreId,
                    method: 'GET',
                    success: function(response) {
                        const data = response.lit;
                        data.forEach(item => {
                            const option = $('<option>', {
                                value: item.id,
                                text: `Lit-${item.code}/${item.type}`,
                                'data-prix': item.prix
                            });
                            $selectElement.append(option);
                        });
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des lits');
                    }
                });
            }
        }

        function select_natureadmission() {
            const $selectElement = $('#id_natureadmission');
            $selectElement.empty();

            const defaultOption = $('<option>', { value: '', text: 'Selectionner' });
            $selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/natureadmission_select',
                method: 'GET',
                success: function(data) {
                    data.natureadmission.forEach(item => {
                        const option = $('<option>', {
                            value: item.idnathospit,
                            text: item.nomnaturehospit
                        });
                        $selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des natures d\'admission');
                }
            });
        }

        function calculerJours() {
            // Sélectionner les éléments des champs date
            const dateEntree = $('#date_entrer').val();
            const dateSortie = $('#date_sortie').val();
            const joursInput = $('#nbre_jour');

            const entreeValue = new Date(dateEntree);
            const sortieValue = new Date(dateSortie);

            // Vérifier si les deux dates sont valides
            if (!isNaN(entreeValue) && !isNaN(sortieValue)) {
                // Calcul de la différence en millisecondes
                const difference = sortieValue - entreeValue;
                // Convertir en jours (1 jour = 24*60*60*1000 millisecondes)
                let jours = difference / (1000 * 60 * 60 * 24);
                
                // Si jours est égal à 0, alors définir jours à 1
                jours = jours === 0 ? 0 : jours;

                // Mise à jour de la valeur du champ input
                joursInput.val(jours);
            }
        }

        function eng_hosp() {

            const login = @json(Auth::user()->login);

            const patient_id = $('#patient_id').val()?.trim();
            const medecin_id = $('#medecin_id').val()?.trim();
            const id_typeadmission = $('#id_typeadmission').val()?.trim();
            const id_natureadmission = $('#id_natureadmission').val()?.trim();
            const id_chambre = $('#id_chambre').val()?.trim();
            const id_lit = $('#id_lit').val()?.trim();
            const date_entrer = $('#date_entrer').val()?.trim();
            const date_sortie = $('#date_sortie').val()?.trim();
            const numcode = $('#numcode').val()?.trim();
            const nbre_jour = $('#nbre_jour').val()?.trim() || 0;
            const motif = $('#motif').val()?.trim();

            if (!patient_id || 
                !medecin_id || 
                !id_typeadmission || 
                !id_natureadmission || 
                !id_chambre || 
                !id_lit || 
                !date_entrer || 
                !date_sortie || 
                !nbre_jour || 
                !motif) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            // Validation des champs monétaires
            if (isNaN(nbre_jour)) {
                showAlert('Alert', 'Veuillez vérifier les dates saisies.', 'warning');
                return false;
            }

            // Ajouter un préchargeur
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>`;
            $('body').append(preloader_ch);

            // Requête AJAX
            $.ajax({
                url: $('#url').attr('content') +'/api/hosp_new',
                method: 'GET', // Changer selon l'API
                data: {
                    patient_id,
                    medecin_id,
                    id_typeadmission,
                    id_natureadmission,
                    id_chambre,
                    id_lit,
                    date_entrer,
                    date_sortie,
                    nbre_jour,
                    numcode,
                    login,
                    motif,
                },
                success: function(response) {
                    // Supprimer le préchargeur
                    $('#preloader_ch').remove();

                    if (response.success) {

                        reset(); // Réinitialiser les champs
                        const newConsultationTab = new bootstrap.Tab($('#tab-oneAAA')[0]);
                        newConsultationTab.show();
                        showAlert('Succès', 'Opération éffectué.', 'success');
                        table_hos.ajax.reload(null, false);
                        table_lit.ajax.reload(null, false);
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue.', 'error');
                    }
                },
                error: function() {
                    // Supprimer le préchargeur en cas d'erreur
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue lors de l\'hospitalisation.', 'error');
                }
            });
        }

        function reset() {

            $('#medecin_id').val(null).trigger('change.select2');
            $('#patient_id').val(null).trigger('change.select2');
            $('#id_typeadmission').val(null).trigger('change.select2');
            $('#id_natureadmission').val(null).trigger('change.select2');
            $('#id_chambre').val(null).trigger('change.select2');
            $('#id_lit').val(null).trigger('change.select2');

            // Réinitialiser les dates à aujourd'hui
            const today = new Date().toISOString().split('T')[0];
            $('#date_entrer').val(today);
            $('#date_sortie').val(today);

            // Réinitialiser les champs numériques et de montants
            $('#nbre_jour').val('');
            $('#numcode').val('');
            $('#nbre_jour').val('');
            $('#motif').val('');

            // Réinitialiser la visibilité des divs et boutons
            $('#div_numcode').hide();

            $('#nbre_jour').val('0');

            Statistique();
        }

        function updatee()
        {
            const id = document.getElementById('IdModif').value;
            const date1 = document.getElementById('date1M');
            const date2 = document.getElementById('date2M');

            if (!date1.value.trim() || !date2.value.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
            }

            const startDate = new Date(date1.value);
            const endDate = new Date(date2.value);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/update_date_hos/'+id,
                method: 'GET',
                data: {
                    date1: date1.value, 
                    date2: date2.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        table_hos.ajax.reload(null, false);

                        showAlert('Succès', 'Opération éffectuée','success');

                    } else if (response.error) {

                        showAlert('Informations', 'Echec de l\'opération','info');

                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', ' Une erreur est survenue.','error');
                }
            });
        }

        function select_produit()
        {
           $.ajax({
                url: $('#url').attr('content') +'/api/select_produit',
                method: 'GET',
                success: function (data) {
                    cachedProduitsHos = data.produit;
                },
                error: function () {
                    console.error('Erreur lors du chargement des produits.');
                }
            }); 
        }

        function addSelect(parentDiv, produits) {
            const div = document.createElement('div');
            div.className = 'mb-3';

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            div.innerHTML = `
                <div class="input-group">
                    <select class="form-select produit-select w-50">
                        <option value="">Sélectionner</option>
                        ${produits.map(produit => `<option value="${produit.medicine_id}" data-prix="${produit.price}" data-quantite="${produit.status}">${produit.name} / ${produit.status} / ${formatPriceT(produit.price)} Fcfa</option>`).join('')}
                    </select>
                    <input type="tel" id="quantite_demande" class="form-control" placeholder="Quantité" value="0" maxlength="2">
                    <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                </div>
            `;

            // Ajouter l'élément dans le parent (contenu div)
            parentDiv.appendChild(div);

            checkContenu(); // Vérifier le contenu et gérer la visibilité du bouton enregistrer

            // Ajouter un event listener pour le bouton supprimer
            div.querySelector('.suppr-btn').addEventListener('click', () => {
                div.remove(); // Supprimer l'élément div parent
                checkContenu(); // Re-vérifier le contenu
                updateMontantTotal(); // Mettre à jour le montant total après la suppression
            });

            const quantiteInput = div.querySelector('#quantite_demande');
            const produitSelect = div.querySelector('.produit-select');
            const tauxProduit = $('#taux_produit').val();

            // Validation pour n'accepter que des valeurs numériques
            quantiteInput.addEventListener('keypress', function(event) {
                const key = event.key;
                if (isNaN(key)) {
                    event.preventDefault();
                }
            });

            quantiteInput.addEventListener('input', function() {
                let value = this.value;

                // Retirer tout sauf les chiffres
                value = value.replace(/[^0-9]/g, '');

                // Si vide, mettre 0
                if (value === '') {
                    value = '0';
                }

                // Empêcher "000" → transforme en "0"
                value = String(parseInt(value, 10));

                this.value = value;
            });


            // Fonction pour mettre à jour le montant total
            function updateMontantTotal() {
                let montantTotal = 0;
                const selects = document.querySelectorAll('.produit-select');

                // selects.forEach(select => {
                //     const selectedOption = select.options[select.selectedIndex];
                //     const prix = parseInt(selectedOption.dataset.prix);
                //     const quantite = parseInt(select.parentElement.querySelector('#quantite_demande').value);
                //     montantTotal += prix * quantite;
                // });

                selects.forEach(select => {
                    const selectedOption = select.options[select.selectedIndex];

                    // Vérifier si une option est sélectionnée et si elle contient un prix
                    const prix = selectedOption?.dataset.prix ? parseInt(selectedOption.dataset.prix) : 0;
                    
                    // Récupérer la quantité, ou mettre 0 si aucune valeur valide
                    const quantiteInput = select.parentElement.querySelector('#quantite_demande');
                    const quantite = quantiteInput?.value ? parseInt(quantiteInput.value) : 0;

                    // Ajouter au montant total
                    montantTotal += prix * quantite;
                });
                
                // Formater le montant total avec des points
                const montantTotalFormatted = montantTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                document.getElementById('montant_total_produit').value = montantTotalFormatted;

                if ($('#utlise_assu_produit').val() == 'non') {

                    document.getElementById('part_assurance_produit').value = 0;
                    document.getElementById('part_patient_produit').value = montantTotalFormatted;
                } else {

                    if (tauxProduit > 0 ) {

                        let partAssurance = (montantTotal * tauxProduit) / 100;
                        let partPatient = montantTotal - partAssurance;

                        let partAssuranceFormtted = partAssurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        let partPatientFormtted = partPatient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                        document.getElementById('part_assurance_produit').value = partAssuranceFormtted;
                        document.getElementById('part_patient_produit').value = partPatientFormtted;

                    } else {

                        document.getElementById('part_assurance_produit').value = 0;
                        document.getElementById('part_patient_produit').value = montantTotalFormatted;
                    }
                }
            }

            // Validation de la quantité saisie pour ne pas dépasser la quantité disponible
            produitSelect.addEventListener('change', function() {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                const quantiteDisponible = parseInt(selectedOption.dataset.quantite);
                
                // Réinitialiser la quantité demandée à 1
                quantiteInput.value = 1;

                // Si la quantité est supérieure à la quantité disponible, ajuster
                if (quantiteDisponible < 1) {

                    $(produitSelect).val(null).trigger('change'); // Convertir produitSelect en instance jQuery
                    showAlert("Alert", 'Ce produit n\'est plus disponible en stock', "info");
                    quantiteInput.value = 1;
                    return;
                }


                updateMontantTotal(); // Mettre à jour le montant total après changement de produit
            });

            // Vérification lors de la perte de focus
            quantiteInput.addEventListener('blur', function() {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                const quantiteDisponible = parseInt(selectedOption.dataset.quantite);

                if (parseInt(quantiteInput.value) <= 0) {

                    showAlert("Alert", 'La quantité doit etre supérieur à 0', "info");
                    quantiteInput.value = 1;
                    return;
                }
                
                if (parseInt(quantiteInput.value) > quantiteDisponible) {
                    showAlert('Alert', `La quantité demandée ne peut pas dépasser ${quantiteDisponible}.`,'warning');
                    quantiteInput.value = quantiteDisponible;
                }else if(quantiteInput.value == ''){
                    quantiteInput.value = 1;
                }

                if(!selectedOption.value == ''){
                    updateMontantTotal();
                } // Mettre à jour le montant total lors de la perte de focus
            });

            $('#utlise_assu_produit').on('change', function() {
                updateMontantTotal();
            });   
        }

        document.getElementById('add_select').addEventListener('click', () => {
            const contenuDiv = document.getElementById('contenu');

            addSelect(contenuDiv, cachedProduitsHos);
        });

        function checkContenu() {
            const contenuDiv = document.getElementById('contenu');
            const divBtnPro = document.getElementById('div_btn_pro');
            
            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.innerHTML.trim() !== "") {
                divBtnPro.style.display = "block"; // Afficher le bouton
            } else {
                divBtnPro.style.display = "none";  // Cacher le bouton
            }
        }

        document.getElementById('btn_eng_produit').addEventListener('click', () => {

            const selections = [];
            const selects = document.querySelectorAll('.produit-select');
            let formIsValid = true;

            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                const idProduit = selectedOption.value; // ID du produit sélectionné
                const quantiteDemande = parseInt(select.parentElement.querySelector('#quantite_demande').value);
                const prix = parseInt(selectedOption.dataset.prix); // Prix du produit

                // Validation du produit et de la quantité
                if (!idProduit) {  // Si aucun produit n'est sélectionné
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez sélectionner un produit.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                if (isNaN(quantiteDemande) || quantiteDemande <= 0) { // Si la quantité n'est pas valide
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez entrer une quantité valide pour chaque produit.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                // Si un produit est sélectionné, ajoutez-le au tableau
                if (idProduit) {
                    selections.push({
                        id: idProduit,
                        quantite: quantiteDemande,
                        montant: prix * quantiteDemande // Calculer le montant
                    });
                }
            });

            if (!Array.isArray(selections) || selections.length === 0) {
                showAlert('Alert', 'Veuillez selectionner un produit.','warning');
                return;
            }

            if (!formIsValid) {
                showAlert('Alert', 'Veuillez selectionner un ou des produit(s).','warning');
                return; // Sortir de la fonction pour éviter le calcul
            }


            const login = @json(Auth::user()->login);

            const taux = document.getElementById('taux_produit').value;
            const montantTotal = document.getElementById('montant_total_produit').value;
            const montantAssurance = document.getElementById('part_assurance_produit').value;
            const montantPatient = document.getElementById('part_patient_produit').value;

            const id = document.getElementById('id_hos_produit').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Add'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/add_soinshopital/'+ id,
                method: 'GET',
                data:{
                    login: login,
                    selections: selections,
                    montantTotal: montantTotal,
                    montantAssurance: montantAssurance,
                    montantPatient: montantPatient,
                    taux: taux,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {
                        table_hos.ajax.reload(null, false);
                        showAlert('Succès', 'Produit Pharmacie ajouter.','success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue','error');
                    } else if (response.json) {
                        showAlert('Alert', 'Invalid selections format','error');
                    }

                },
                error: function() {
                    
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement','error');
                }
            });
        });
    
        function addSelectGarantie(parentDiv, garanties) {
            const div = document.createElement('div');
            div.className = 'mb-3';

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            div.innerHTML = `
                <div class="input-group">
                    <select class="form-select garantie-select w-50">
                        <option value="">Sélectionner</option>
                        ${garanties.map(item => `
                            <option value="${item.id}">
                                ${item.libelle}
                            </option>`).join('')}
                    </select>
                    <input type="tel" id="prix_garantie" class="form-control" placeholder="Prix" value="0">
                    <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                </div>
            `;

            // Ajouter l'élément dans le parent (contenu div)
            parentDiv.appendChild(div);

            checkContenuGarantie(); // Vérifier le contenu et gérer la visibilité du bouton enregistrer

            // Ajouter un event listener pour le bouton supprimer
            div.querySelector('.suppr-btn').addEventListener('click', () => {
                div.remove(); // Supprimer l'élément div parent
                checkContenuGarantie(); // Re-vérifier le contenu
                updateMontantTotalGarantie(); // Mettre à jour le montant total après la suppression
            });

            const prixInput = div.querySelector('#prix_garantie');
            const garantieSelect = div.querySelector('.garantie-select');
            const tauxGarantie = $('#taux_garantie').val();

            // Validation pour n'accepter que des valeurs numériques
            prixInput.addEventListener('keypress', function(event) {
                const key = event.key;
                if (isNaN(key)) {
                    event.preventDefault();
                }
            });

            prixInput.addEventListener('input', function() {
                let value = this.value;

                // Retirer tout sauf les chiffres
                value = value.replace(/[^0-9]/g, '');

                // Si vide, mettre 0
                if (value === '') {
                    value = '0';
                }

                // Empêcher "000" → transforme en "0"
                value = String(parseInt(value, 10));

                this.value = value;
            });

            prixInput.addEventListener('input', function() {
                this.value = formatPriceT(this.value.replace(/[^0-9]/g, '')); // Allow only numbers
                updateMontantTotalGarantie();
            });

            // Fonction pour mettre à jour le montant total
            function updateMontantTotalGarantie() {
                let montantTotal = 0;
                const selects = document.querySelectorAll('.garantie-select');

                selects.forEach(select => {
                    const selectedOption = select.options[select.selectedIndex];
                    
                    // Récupérer la quantité, ou mettre 0 si aucune valeur valide
                    const prixSelect = select.parentElement.querySelector('#prix_garantie');
                    const prixSelectFormat = prixSelect?.value ? parseInt(prixSelect.value.replace(/[^0-9]/g, '')) : 0;

                    // Ajouter au montant total
                    montantTotal += prixSelectFormat;
                });
                
                // Formater le montant total avec des points
                const montantTotalFormatted = montantTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                document.getElementById('montant_total_garantie').value = montantTotalFormatted;

                if ($('#utlise_assu_garantie').val() == 'non') {

                    document.getElementById('part_assurance_garantie').value = 0;
                    document.getElementById('part_patient_garantie').value = montantTotalFormatted;
                } else {

                    if (tauxGarantie > 0 ) {

                        let partAssurance = (montantTotal * tauxGarantie) / 100;
                        let partPatient = montantTotal - partAssurance;

                        let partAssuranceFormtted = partAssurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        let partPatientFormtted = partPatient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                        document.getElementById('part_assurance_garantie').value = partAssuranceFormtted;
                        document.getElementById('part_patient_garantie').value = partPatientFormtted;

                    } else {

                        document.getElementById('part_assurance_garantie').value = 0;
                        document.getElementById('part_patient_garantie').value = montantTotalFormatted;
                    }
                }
            }

            // Validation de la quantité saisie pour ne pas dépasser la quantité disponible
            garantieSelect.addEventListener('change', function() {

                updateMontantTotalGarantie(); // Mettre à jour le montant total après changement de produit
            });

            $('#utlise_assu_garantie').on('change', function() {
                updateMontantTotalGarantie();
            });   
        }

        document.getElementById('add_select_garantie').addEventListener('click', () => {
            const contenuDiv = document.getElementById('contenu_garantie');

            addSelectGarantie(contenuDiv, cachedGaranHos);
        });

        function checkContenuGarantie() {
            const contenuDiv = document.getElementById('contenu_garantie');
            const divBtn = document.getElementById('div_btn_garantie');
            
            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.innerHTML.trim() !== "") {
                divBtn.style.display = "block"; // Afficher le bouton
            } else {
                divBtn.style.display = "none";  // Cacher le bouton
            }
        }

        document.getElementById('btn_eng_garantie').addEventListener('click', () => {

            const selections = [];
            const selects = document.querySelectorAll('.garantie-select');
            let formIsValid = true;

            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                const idGarantie = selectedOption.value; // ID du produit sélectionné
                const prix = parseInt(select.parentElement.querySelector('#prix_garantie').value.replace(/[^0-9]/g, ''));

                // Validation du produit et de la quantité
                if (!idGarantie) {  // Si aucun produit n'est sélectionné
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez sélectionner une garantie.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                if (isNaN(prix)) { // Si la quantité n'est pas valide
                    formIsValid = false;
                    showAlert('Alert', 'Veuillez entrer le prix pour chaque garantie.','warning');
                    return false;  // Stopper l'exécution si une erreur est trouvée
                }

                // Si un produit est sélectionné, ajoutez-le au tableau
                if (idGarantie) {
                    selections.push({
                        id: idGarantie,
                        montant: prix,
                    });
                }
            });

            if (!Array.isArray(selections) || selections.length === 0) {
                showAlert('Alert', 'Veuillez selectionner une garantie.','warning');
                return;
            }

            if (!formIsValid) {
                showAlert('Alert', 'Veuillez selectionner une ou plusieurs garantie(s).','warning');
                return; // Sortir de la fonction pour éviter le calcul
            }


            const login = @json(Auth::user()->login);

            const taux = document.getElementById('taux_garantie').value;
            const montantTotal = document.getElementById('montant_total_garantie').value;
            const montantAssurance = document.getElementById('part_assurance_garantie').value;
            const montantPatient = document.getElementById('part_patient_garantie').value;

            const utiliseAss = document.getElementById('utlise_assu_garantie').value;

            const id = document.getElementById('id_hos_garantie').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('AddGaran'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/add_garantiehopital/'+ id,
                method: 'GET',
                data:{
                    login: login,
                    selections: selections,
                    montantTotal: montantTotal,
                    montantAssurance: montantAssurance,
                    montantPatient: montantPatient,
                    taux: taux,
                    utiliseAss: utiliseAss,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {
                        table_hos.ajax.reload(null, false);
                        showAlert('Succès', 'Garantie(s) ajouter.','success');
                    } else if (response.error) {
                        showAlert('Alert', 'Une erreur est survenue','error');
                    } else if (response.json) {
                        showAlert('Alert', 'Invalid selections format','error');
                    }

                },
                error: function() {
                    
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement','error');
                }
            });
        });

        function calculateDaysBetween(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            
            // Calcul de la différence en millisecondes
            const diffInMilliseconds = end - start;

            // Conversion en jours (millisecondes en secondes, minutes, heures, jours)
            let diffInDays = diffInMilliseconds / (1000 * 60 * 60 * 24);

            // Si la différence est inférieure ou égale à 0, on retourne 1 jour minimum
            return diffInDays <= 0 ? 0 : Math.round(diffInDays); 
        }

        function updatePaginationControls(pagination) {
            const paginationDiv = document.getElementById('pagination-controls-hos');
            paginationDiv.innerHTML = '';

            // Bootstrap pagination wrapper
            const paginationWrapper = document.createElement('ul');
            paginationWrapper.className = 'pagination justify-content-center';

            // Previous button
            if (pagination.current_page > 1) {
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                prevButton.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(pagination.current_page - 1);
                };
                paginationWrapper.appendChild(prevButton);
            } else {
                // Disable the previous button if on the first page
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item disabled';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                paginationWrapper.appendChild(prevButton);
            }

            // Page number links (show a few around the current page)
            const totalPages = pagination.last_page;
            const currentPage = pagination.current_page;
            const maxVisiblePages = 5; // Max number of page links to display

            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if end page exceeds the total pages
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Loop through pages and create page links
            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement('li');
                pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageItem.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(i);
                };
                paginationWrapper.appendChild(pageItem);
            }

            // Ellipsis (...) if not all pages are shown
            if (endPage < totalPages) {
                const ellipsis = document.createElement('li');
                ellipsis.className = 'page-item disabled';
                ellipsis.innerHTML = `<a class="page-link" href="#">...</a>`;
                paginationWrapper.appendChild(ellipsis);

                // Add the last page link
                const lastPageItem = document.createElement('li');
                lastPageItem.className = `page-item`;
                lastPageItem.innerHTML = `<a class="page-link" href="#">${totalPages}</a>`;
                lastPageItem.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(totalPages);
                };
                paginationWrapper.appendChild(lastPageItem);
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                nextButton.onclick = (event) => {
                    event.preventDefault(); // Empêche le défilement en haut de la page
                    list_hos(pagination.current_page + 1);
                };
                paginationWrapper.appendChild(nextButton);
            } else {
                // Disable the next button if on the last page
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item disabled';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                paginationWrapper.appendChild(nextButton);
            }

            // Append pagination controls to the DOM
            paginationDiv.appendChild(paginationWrapper);
        }

        function Statistique() {

            const $nbreDay = $('#nbre_hos');

            $.ajax({
                url: $('#url').attr('content') +'/api/statistique_hos',
                method: 'GET',
                success: function(response) {
                    // Mettre à jour le contenu texte de l'élément
                    $nbreDay.text(response.stat_hos_day);
                },
                error: function() {
                    // Mettre une valeur par défaut en cas d'erreur
                    $nbreDay.text('0');
                }
            });
        }

    });
</script>


@endsection