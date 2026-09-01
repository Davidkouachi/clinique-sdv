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
                        <h3>SOINS AMBULATOIRES</h3>
                        <h6>Services / Soins Ambulatoires</h6>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-info rounded-5 me-3">
                                    <i class="ri-first-aid-kit-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_soinsam" ></h2>
                                    <p class="m-0">Patients Traités aujourd'hui</p>
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
            <div class="card mb-3">
                <div class="card-header" hidden >
                    <h5 class="card-title">Hospitalisation</h5>
                </div>
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-medicine-bottle-line me-2"></i>
                                    Nouveau
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-health-book-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Nouveau Soins Infirmier
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Patient</label>
                                            <select class="form-select select2" id="patient_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">N° hospitalisation</label>
                                            <div class="input-group">
                                                <span class="input-group-text">N°</span>
                                                <input type="text" class="form-control" id="numhosp" autocomplete="off" placeholder="facultatif">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">N° prise en charge</label>
                                            <div class="input-group">
                                                <span class="input-group-text">N°</span>
                                                <input type="text" class="form-control" autocomplete="off" id="numcode" placeholder="facultatif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Type de Soins Infirmer</label>
                                            <select class="form-select select2" id="typesoins_id"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_selectSoins" class="border border-2 mb-3 p-2 rounded-2">
                                    <div class="card-header">
                                        <h5 class="card-title text-center">
                                            Choix des Soins Infirmiers
                                        </h5>
                                    </div>
                                    <div class="row gx-3 justify-content-center align-items-center">
                                        <div class="col-12">
                                            <div class="row gx-3 justify-content-center align-items-center">
                                                <div id="div_alert_soins" ></div>
                                                <div class="col-12 mb-3 text-center">
                                                    <button type="button" id="add_select_soins" class="btn btn-info">
                                                        <i class="ri-sticky-note-add-line"></i>
                                                        Ajouter un Soins
                                                    </button>
                                                </div>
                                                <div class="col-12" id="contenu_soins">

                                                </div>
                                                <div class="col-12" id="div_btn_soins" style="display: none;">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">
                                                            Montant Total
                                                        </span>
                                                        <input readonly type="tel" class="form-control" id="montant_total_soins" placeholder="Montant Total">
                                                        <span class="input-group-text">Fcfa</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_selectProduit" class="border border-2 mb-3 p-2 rounded-2  " >
                                    <div class="card-header">
                                        <h5 class="card-title text-center">
                                            Choix des Produits Utilisés
                                        </h5>
                                    </div>
                                    <div class="row gx-3 justify-content-center align-items-center" >
                                        <div class="col-12">
                                            <div class="row gx-3 justify-content-center align-items-center">
                                                <div class="col-12 mb-3 text-center">
                                                    <button type="button" id="add_select_produit" class="btn btn-success">
                                                        <i class="ri-sticky-note-add-line"></i>
                                                        Ajouter un Produit
                                                    </button>
                                                </div>
                                                <div class="col-12" id="contenu_produit">

                                                </div>
                                                <div class="col-12" id="div_btn_pro" style="display: none;">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">
                                                            Montant Total
                                                        </span>
                                                        <input readonly type="tel" class="form-control" id="montant_total_produit" placeholder="Montant Total">
                                                        <span class="input-group-text">Fcfa</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_btn_calcul" class="border border-2 mb-3 p-2 rounded-2 " >
                                    <div class="card-header">
                                        <h5 class="card-title text-center">
                                            Informations Montant
                                        </h5>
                                    </div>
                                    <div class="row gx-3 justify-content-center align-items-center" >
                                        <div class="col-sm-12 mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_calcul" class="btn btn-warning">
                                                    Calculer le montant final
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-3" id="div_calcul" style="display: none;">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Taux</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="patient_taux">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Part Assurance</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="montant_assurance">
                                                    <input type="hidden" class="form-control" id="montant_assurance_hidden">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Part Patient</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="montant_patient">
                                                    <input type="hidden" class="form-control" id="montant_patient_hidden">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Montant Total</label>
                                                <div class="input-group">
                                                    <input readonly type="tel" class="form-control" id="montant_total">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Remise</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="taux_remise" value="0">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Application de la remise</label>
                                                <select class="form-select" id="appliq_remise">
                                                    <option selected value="patient">Patient</option>
                                                    <option value="assurance">Assurance</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_assurance_utiliser" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">Utilisé l'assurance</label>
                                                <select class="form-select" id="assurance_utiliser">
                                                    <option selected value="oui">Oui</option>
                                                    <option value="non">Non</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_loader" style="display: none;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                            <strong>Calcul en cours...</strong>
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
                                                    Liste des Soins Ambulatoires
                                                </h5>
                                            </div>
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Du</span>
                                                        <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                                                        <span class="input-group-text">au</span>
                                                        <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                        <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                                            <i class="ri-search-2-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="">
                                                        <table id="Table_day" class="table Table_soinsam">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">N° Facture</th>
                                                                    <th scope="col">Patient</th>
                                                                    <th scope="col">Nbre Soins</th>
                                                                    <th scope="col">Nbre Produits</th>
                                                                    <th scope="col">Part Assurance</th>
                                                                    <th scope="col">Part Patient</th>
                                                                    <th scope="col">Taux de couverture</th>
                                                                    <th scope="col">Montant Total</th>
                                                                    <th scope="col">Date de création</th>
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
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
                    Détail Soins Infirmiers et Produits Utilisés
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
                                            <!-- Tableau Soins Infirmiers -->
                                            <table class="table table-bordered" id="TableP">
                                                <thead>
                                                    <tr>
                                                        <th>Soins Infirmiers</th>
                                                        <th style="width: 250px;">Prix</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="table-responsive" id="div_TableProdP" style="display: none;">
                                            <!-- Tableau Produits Utilisés -->
                                            <table class="table table-bordered" id="TableProdP">
                                                <thead>
                                                    <tr>
                                                        <th>Produits Utilisés</th>
                                                        <th style="width: 200px;">Prix Unitaire</th>
                                                        <th style="width: 50px;" >Quantité</th>
                                                        <th style="width: 200px;">Prix Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div id="message_TableP" style="display: none;">
                                            <p class="text-center" >
                                                Aucun détail pour le moment
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

<div class="modal fade" id="Mdelete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé ?
                <input type="hidden" id="Iddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/soins.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        let cachedProduits = {};

        Statistique();
        select_patient();
        select_typesoins();
        select_produit();

        $("#btn_calcul").on("click", CalculMontant);
        $("#assurance_utiliser").on("change", CalculMontant);
        $("#btn_eng").on("click", Eng_sa);
        $("#deleteBtn").on("click", delete_soinsam);

        $('#patient_id').on('change', function() {
            rech_dosier($(this).val()); 
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

        const table = $('.Table_soinsam').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_soinsam_all/${date1}/${date2}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
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
                    data: 'numfac_soins', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/soinsam.webp') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'patient',
                    searchable: true,
                },
                { 
                    data: 'nbre_soins',
                    searchable: true,
                },
                { 
                    data: 'nbre_produit',
                    searchable: true,
                },
                { 
                    data: 'part_assurance', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'ticket_moderateur', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'taux_couverture', 
                    render: (data) => `${data}%`,
                    searchable: true, 
                },
                { 
                    data: 'montant_total', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'date_soin', 
                    render: (data) => `${formatDateHeure(data)}`,
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
                                <li>
                                    <a href="#" class="dropdown-item text-warning" id="detail_produit" data-bs-toggle="modal" data-bs-target="#Detail_produit"
                                        data-id="${row.id_soins}"
                                    >
                                        <i class="ri-edit-box-line"></i>
                                        Détail Soins et Produit(s)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="fiche"
                                        data-id="${row.id_soins}"
                                    >
                                        <i class="ri-file-line"></i>
                                        Imprimer facture
                                    </a>
                                </li>
                                ${parseFloat(row.montant_regle) === 0 && (!row.numhospit || row.numhospit === '') ? `
                                    <li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id_soins}" 
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

            $('.Table_soinsam').on('click', '#detail_produit', function() {
                const id = $(this).data('id');

                const tableBodyP = document.querySelector('#TableP tbody'); // Pour les soins infirmiers
                const tableBodyProdP = document.querySelector('#TableProdP tbody'); // Pour les produits
                const messageDivP = document.getElementById('message_TableP');
                const tableDivP = document.getElementById('div_TableP');
                const tableDivProdP = document.getElementById('div_TableProdP'); // Div pour les produits
                const loaderDivP = document.getElementById('div_Table_loaderP');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                tableDivProdP.style.display = 'none'; // Cacher au départ
                loaderDivP.style.display = 'block';

                fetch($('#url').attr('content') +`/api/detail_soinam/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        if (data.existep) {

                            var modal = bootstrap.Modal.getInstance($('#Detail_produit')[0]);
                            modal.hide();

                            showAlert("ALERT", 'Une erreur s\'est produite, veuillez réessayer plutard .', "error");
                            return; 
                        }

                        const patient = data.patient;
                        const soins = data.soins;
                        const produit = data.produit;

                        let total = patient.prototal + patient.stotal;

                        // Clear existing rows
                        tableBodyP.innerHTML = '';
                        tableBodyProdP.innerHTML = ''; // Pour les produits

                        if (soins.length > 0 || produits.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';
                            tableDivProdP.style.display = 'block'; // Afficher le tableau des produits

                            // Remplir le tableau des soins
                            soins.forEach((item, index) => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.libelle_soins}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(item.price)} Fcfa</h6>
                                    </td>
                                `;
                                tableBodyP.appendChild(row);
                            });

                            const rowTotalSoin = document.createElement('tr');
                            rowTotalSoin.innerHTML = `
                                <td >&nbsp;</td>
                                <td >
                                    <h5 class="mt-4 text-success">
                                        Total Soins : ${formatPriceT(patient.stotal)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(rowTotalSoin);

                            // Remplir le tableau des produits
                            produit.forEach((item, index) => {
                                let prixT = parseInt(item.qte) * parseInt(item.price);
                                const rowProd = document.createElement('tr');
                                rowProd.innerHTML = `
                                    <td>
                                        <h6>${item.name}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(item.price)} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>${item.qte}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(prixT)} Fcfa</h6>
                                    </td>
                                `;
                                tableBodyProdP.appendChild(rowProd);
                            });

                            const rowTotalProd = document.createElement('tr');
                            rowTotalProd.innerHTML = `
                                <td colspan="2" >&nbsp;</td>
                                <td colspan="2">
                                    <h5 class="mt-4 text-success">
                                        Total Produits : ${formatPriceT(patient.prototal)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyProdP.appendChild(rowTotalProd);

                            const rowNote = document.createElement('tr');
                            rowNote.innerHTML = `
                                <td colspan="4">
                                    <h6 class="text-danger">
                                        TOTAL : ${formatPriceT(total)} Fcfa
                                    </h6>
                                    <p class="small m-0">
                                        Le Montant Total des produits utilisés
                                        seront ajoutés au montant total des soins.
                                    </p>
                                </td>
                            `;

                            tableBodyProdP.appendChild(rowNote);

                        } else {
                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'block';
                            tableDivP.style.display = 'none';
                            tableDivProdP.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        loaderDivP.style.display = 'none';
                        messageDivP.style.display = 'block';
                        tableDivP.style.display = 'none';
                        tableDivProdP.style.display = 'none';
                    });
            });

            $('.Table_soinsam').on('click', '#fiche', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch($('#url').attr('content') +`/api/imp_fac_soinam/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const patient = data.patient;
                        const soins = data.soins;
                        const produit = data.produit;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        pdfFactureSoins(patient, soins, produit);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_soinsam').on('click', '#delete', function() {
                const id = $(this).data('id');

                $('#Iddelete').val(id);
            });
        }

        $('#btn_search_table').on('click', function() {
            table.ajax.reload(null, false);
        });

        function delete_soinsam() {

            const id = document.getElementById('Iddelete').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_Soinsam/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        table.ajax.reload(null, false);
                        Statistique();
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Echec de l\'opération', "error");
                    } else if (response.existe_p) {
                        showAlert("ALERT", 'Facture non trouvée', "info");
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
                text: 'Selectionner',
                'data-taux': 0,
                'data-assurer': 0,
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/name_patient_examen',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.name.forEach(item => {
                        const option = $('<option>', {
                            value: item.id,
                            text: `${item.id} | ` + `${item.np}`,
                            'data-taux': item.taux || 0,
                            'data-assurer': item.assure,
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des patients');
                }
            });
        }

        $('#patient_id').on('change', function() {
            rech_dosier(); 
        });

        function rech_dosier() {
            const selectElement = $('#patient_id');

            if (selectElement.val() !== '') {
                const selectedOption = selectElement.find('option:selected'); // Obtenir l'option sélectionnée

                // Récupérer les données depuis les attributs de l'option sélectionnée
                const taux = selectedOption.data('taux') || 0;
                const assurer = selectedOption.data('assurer') || 0;

                // Mettre à jour le champ patient_taux
                $('#patient_taux').val(taux);

                // Réinitialiser le champ numcode
                $('#numcode').val('');

                // Afficher ou masquer div_numcode en fonction de l'assurance
                if (assurer === 1) {
                    $('#div_numcode').show();
                } else {
                    $('#div_numcode').hide();
                }

                // Afficher ou masquer div_assurance_utiliser en fonction du taux
                if (taux == 0) {
                    $('#div_assurance_utiliser').hide();
                    $('#montant_assurance').prop('readonly', true);
                    $('#montant_assurance').val(0);
                } else {
                    $('#div_assurance_utiliser').show();
                    $('#montant_assurance').prop('readonly', false);
                }

                // Cacher la div_calcul
                $('#div_calcul').hide();

            } else {
                $('#div_numcode').hide();
                $('#div_calcul').hide(); 

                $('#numcode').val('');
            }
        }

        // -----------------------------------------------------

        let cachedSoins = {};

        function select_typesoins() {
            const selectElement = $('#typesoins_id');

            // Clear existing options
            selectElement.empty();

            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_typesoins',
                method: 'GET',
                success: function(response) {
                    const data = response.typesoins;
                    data.forEach(item => {
                        const option = $('<option>', {
                            value: item.code_typesoins,
                            text: item.libelle_typesoins
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors de la récupération des types de soins.');
                }
            });

            selectElement.on('change', function() {
                const id = $(this).val();

                if (id !== '') {
                    // Vérifier si les données sont déjà en cache
                    if (cachedSoins[id]) {

                        afficherSoins(id); // Utiliser les données du cache
                    } else {

                        rech_soinsin(id);
                    }
                } else {
                    $('#contenu_soins').empty();
                    checkContenuSoins();
                }
            });
        }

        $('#add_select_soins').on('click', function() {
            const contenuDiv = $('#contenu_soins');
            const id = $('#typesoins_id').val();

            if (id === '') {

                showAlert("ALERT", "Selectionner un Type de Soins.", "warning");
                return;
            } else {

                if (cachedSoins[id]) {
                    addSelectSoins(contenuDiv, cachedSoins[id]);
                } else {

                    const url = $('#url').attr('content') +'/api/select_soinsIn/' + id;
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(data) {
                            const soinsins = data.soinsin;
                            cachedSoins[id] = data.soinsin;
                            addSelectSoins(contenuDiv, soinsins);
                        },
                        error: function() {
                            console.error('Erreur lors du chargement des données.');
                        }
                    });
                }
            }

        });

        function rech_soinsin(id)
        {
            const url = $('#url').attr('content') +'/api/select_soinsIn/' + id;
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    cachedSoins[id] = data.soinsin;
                    afficherSoins(id);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
        }

        function afficherSoins(id) {
            const contenuDiv = $('#contenu_soins');
            contenuDiv.empty();
            $('#montant_total_soins').val('');
            addSelectSoins(contenuDiv, cachedSoins[id]);
        }

        function addSelectSoins(parentDiv, soinsins) {

            const index = parentDiv.children().length + 1;

            const div = $('<div>', { class: 'mt-3 mb-3 border border-1 p-3 rounded-2 div_soins' });

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            const inputGroup = $(`
                <div class="card-header">
                    <h5 class="card-title text-center">
                        SOINS INFIRMIERS ${index}
                    </h5>
                </div>
                <div class="row gx-3 mb-3 text-center input_group">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-12">
                        <div class="mb-3">
                            <label class="form-label">Soins</label>
                            <select class="form-select soins-select select2">
                            <option value="" data-prix="0">Sélectionner</option>
                            ${soinsins.map(item => `<option value="${item.code_soins}" data-prix="${item.price}">${item.libelle_soins} / ${formatPriceT(item.price)} Fcfa</option>`).join('')}
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control prix_soins" placeholder="Prix" value="0">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center" >
                        <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                    </div>
                </div>
            `);

            div.append(inputGroup);
            parentDiv.append(div);

            $(div).find('.select2').select2({
                theme: 'bootstrap',
                placeholder: 'Selectionner',
                language: {
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    }
                },
                width: '100%',
            });

            checkContenuSoins();

            // Ajouter un event listener pour le bouton supprimer
            div.find('.suppr-btn').on('click', function() {
                div.remove(); // Supprimer l'élément div parent
                checkContenuSoins(); // Re-vérifier le contenu
                updateMontantTotalSoins(); // Mettre à jour le montant total après la suppression

                // Renuméroter les titres après suppression
                parentDiv.children('.div_soins').each(function (i) {
                    // Mettre à jour l'index dans le titre
                    $(this)
                        .find('.card-title')
                        .text(`SOINS INFIRMIERS ${i + 1}`);
                });
            });

            // Event listener pour le select
            div.find('.soins-select').on('change', function() {
                const selectedOption = $(this).find(':selected'); // Obtenir l'option actuellement sélectionnée
                const prix = selectedOption.data('prix'); // Lire l'attribut data-prix
                div.find('.prix_soins').val(prix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') || 0);
                const select = $(this).val();
                updateMontantTotalSoins();
            });

            div.find('.prix_soins').on('input', function() {

                const select = div.find('.soins-select').val();

                if (!select) {
                    $(this).val(0);
                    showAlert("ALERT", "Veuillez sélectionner un Soins.", "warning");
                    return;
                }

                const rawValue = $(this).val().replace(/[^0-9]/g, ''); 
                const formattedValue = formatPrice(rawValue);
                $(this).val(formattedValue);

                updateMontantTotalSoins();
            });

        }

        function updateMontantTotalSoins() {
            let montantTotal = 0;
            $('.prix_soins').each(function() {
                // const selectedOption = $(this).find('option:selected');
                // const prix = selectedOption.data('prix');

                let prix = $(this).val().replace(/\./g, '');

                if (prix) {
                    montantTotal += parseInt(prix); // Ajouter le prix à la somme totale
                }
            });

            // Formater le montant total avec des points
            const montantTotalFormatted = montantTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#montant_total_soins').val(montantTotalFormatted);
        }

        function checkContenuSoins() {
            const contenuDiv = $('#contenu_soins');
            const divBtnPro = $('#div_btn_soins');

            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.html().trim() !== '') {
                divBtnPro.show(); // Afficher le bouton
            } else {
                divBtnPro.hide(); // Cacher le bouton
            }
        }

        // -------------------------------------------------------

        function addSelect(parentDiv, produits) {
            const index = parentDiv.children().length + 1;

            const div = $('<div>', { class: 'mt-3 mb-3 border border-1 p-3 rounded-2 div_produit' });

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            const inputGroup = $(`
                <div class="card-header">
                    <h5 class="card-title text-center">
                        PRODUIT ${index}
                    </h5>
                </div>
                <div class="row gx-3 mb-3 text-center input_group">
                    <div class="col-lg-4 col-12">
                        <div class="mb-3">
                            <label class="form-label">Soins</label>
                            <select class="form-select produit-select select2">
                                <option value="" data-quantite="1" data-prix="0">Sélectionner</option>
                                ${produits.map(produit => `<option value="${produit.medicine_id}" data-prix="${produit.price}" data-quantite="${produit.status}">${produit.name} / Qte: ${produit.status} / Prix: ${formatPriceT(produit.price)} Fcfa</option>`).join('')}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Quantité</label>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control quantite-demande" placeholder="Quantité" value="1" maxlength="3">
                                <span class="input-group-text">Qté</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="mb-3">
                            <label class="form-label">Prix</label>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control prix_produit" placeholder="Prix" value="0">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center" >
                        <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                    </div>
                </div>
            `);

            div.append(inputGroup);
            parentDiv.append(div);

            $(div).find('.select2').select2({
                theme: 'bootstrap',
                placeholder: 'Selectionner',
                language: {
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    }
                },
                width: '100%',
            });

            // Supprime aria-hidden=true sur le conteneur Select2 après ouverture
            $('.select2').on('select2:open', function() {
                $('.select2').removeAttr('aria-hidden');
            });

            checkContenu(); // Vérifier le contenu et gérer la visibilité du bouton enregistrer

            // Ajouter un event listener pour le bouton supprimer
            div.find('.suppr-btn').on('click', function () {
                div.remove(); // Supprimer l'élément div parent
                checkContenu(); // Re-vérifier le contenu
                updateMontantTotal(); // Mettre à jour le montant total après la suppression

                // Renuméroter les titres après suppression
                parentDiv.children('.div_produit').each(function (i) {
                    // Mettre à jour l'index dans le titre
                    $(this)
                        .find('.card-title')
                        .text(`PRODUIT ${i + 1}`);
                });
            });

            const quantiteInput = div.find('.quantite-demande');
            const produitSelect = div.find('.produit-select');
            const prixSelect = div.find('.prix_produit');

            // Validation pour n'accepter que des valeurs numériques
            // quantiteInput.on('keypress', function (event) {
            //     const key = event.key;
            //     if (isNaN(key)) {
            //         event.preventDefault();
            //     }
            // });

            // Validation de la quantité saisie pour ne pas dépasser la quantité disponible
            // produitSelect.on('change', function () {
            //     const selectedOption = produitSelect.find(':selected');
            //     const quantiteDisponible = parseInt(selectedOption.data('quantite'));

            //     // Réinitialiser la quantité demandée à 1
            //     quantiteInput.val(1);

            //     if (quantiteDisponible < 1) {
            //         quantiteInput.val(1); // S'assurer que la quantité ne soit pas négative
            //     }

            //     updateMontantTotal(); // Mettre à jour le montant total après changement de produit
            // });

            produitSelect.on('change', function () {
                const selectedOption = produitSelect.find(':selected');
                const quantiteDisponible = parseInt(selectedOption.data('quantite'));
                const prix = selectedOption.data('prix');

                // Reset the requested quantity to 1
                // quantiteInput.val(1);

                if (quantiteDisponible < 1) {

                    produitSelect.val(null).trigger('change.select2');
                    div.find('.quantite-demande').val(1);
                    div.find('.prix_produit').val(0);
                    showAlert("Alert", 'Ce produit n\'est plus disponible en stock', "info");
                    return;
                }

                const select = $(this).val();

                if (!select) {
                    div.find('.quantite-demande').val(1);
                    div.find('.prix_produit').val(0);
                }

                div.find('.prix_produit').val(prix.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

                updateMontantTotal(); // Update the total amount after product change
            });

            // Vérification lors de la perte de focus
            quantiteInput.on('input', function () {

                if (!$(this).val().trim()) {
                    $(this).val(0);
                }

                const select = div.find('.produit-select').val();
                const selectedOption = produitSelect.find(':selected');
                const quantiteDisponible = parseInt(selectedOption.data('quantite'));

                if (parseInt(quantiteInput.val()) > quantiteDisponible) {
                    showAlert("ALERT", `La quantité demandée ne peut pas dépasser ${quantiteDisponible}.`, "warning");
                    quantiteInput.val(quantiteDisponible);
                }

                if (!select) {
                    $(this).val(1);
                    showAlert("ALERT", "Veuillez sélectionner un Produit.", "warning");
                    return;
                }

                const rawValue = $(this).val().replace(/[^0-9]/g, '').replace(/^0+/, '');
                $(this).val(rawValue);

                updateMontantTotal();

            });

            prixSelect.on('input', function() {

                const select = div.find('.produit-select').val();

                if (!select) {
                    $(this).val(0);
                    showAlert("ALERT", "Veuillez sélectionner un Produit.", "warning");
                    return;
                }

                if (!$(this).val().trim()) {
                    $(this).val(0);
                }

                const rawValue = $(this).val().replace(/[^0-9]/g, ''); 
                const formattedValue = formatPrice(rawValue);
                $(this).val(formattedValue);

                updateMontantTotal();
            });
        }

        function updateMontantTotal() {
            let montantTotal = 0;

            const produitsSelects = document.querySelectorAll('.produit-select');

            produitsSelects.forEach(select => {

                const quantite = select.closest('.input_group').querySelector('.quantite-demande').value;
                let prix = parseInt(select.closest('.input_group').querySelector('.prix_produit').value.replace(/[^0-9]/g, '')) || 0;

                // Vérifier si une option valide est sélectionnée
                // if (selectedOption.val()) {
                //     const prix = parseInt(selectedOption.data('prix')) || 0; // Si 'prix' est invalide ou manquant, utiliser 0
                //     const quantite = parseInt($(this).closest('.input-group').find('.quantite-demande').val()) || 1; // Si la quantité est invalide, utiliser 1 par défaut
                //     montantTotal += prix * quantite;
                // }

                montantTotal += prix * quantite;
            });

            // Formater le montant total avec des points
            const montantTotalFormatted = montantTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            $('#montant_total_produit').val(montantTotalFormatted);
        }

        function checkContenu() {
            const contenuDiv = $('#contenu_produit');
            const divBtnPro = $('#div_btn_pro');

            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if ($.trim(contenuDiv.html()) !== "") {
                divBtnPro.show(); // Afficher le bouton
            } else {
                divBtnPro.hide(); // Cacher le bouton
                $('#montant_total_produit').val('');
            }
        }

        function select_produit()
        {
            cachedProduits = {}; 
            $.ajax({
                url: $('#url').attr('content') +'/api/select_produit',
                method: 'GET',
                success: function (data) {
                    cachedProduits = data.produit;
                },
                error: function () {
                    console.error('Erreur lors du chargement des produits.');
                }
            }); 
        }

        $('#add_select_produit').on('click', function () {
            const contenuDiv = $('#contenu_produit');

            addSelect(contenuDiv, cachedProduits);
        });

        // -----------------------------------------------------

        let montantTotal = 0;
        let montantBasePatient = 0;
        let montantBaseAssurance = 0;
        let montantRemise = 0;
        let cibleRemise = 'patient';

        function CalculMontant() {

            // UI
            $('#div_loader').show();
            $('#div_calcul').hide();
            $('#btn_calcul').hide();

            const patient_id = $('#patient_id').val();
            const typesoins_id = $('#typesoins_id').val();

            if (!patient_id) {
                showAlert("ALERT", "Veuillez sélectionner un Patient.", "warning");
                resetLoaderAndButton();
                return false;
            }

            if (!typesoins_id) {
                showAlert("ALERT", "Veuillez sélectionner un Type de Soins.", "warning");
                resetLoaderAndButton();
                return false;
            }

            if ($('#contenu_soins').html().trim() === "") {
                showAlert("ALERT", "Aucun Soins Infirmier n'a été sélectionné.", "warning");
                resetLoaderAndButton();
                return false;
            }

            let formIsValid = true;
            let selectionsSoins = [];
            let selectionsProduits = [];

            // ================== SOINS ==================
            const soinsSelects = document.querySelectorAll('.soins-select');
            const selectedSoinsIds = new Set();

            soinsSelects.forEach(item => {

                const idSoins = item.value;
                const montant = parseInt(
                    item.closest('.input_group')
                        .querySelector('.prix_soins')
                        .value.replace(/\D/g, '')
                );

                if (!idSoins || isNaN(montant)) {
                    formIsValid = false;
                    return;
                }

                if (selectedSoinsIds.has(idSoins)) {
                    showAlert("ALERT", "Vous avez sélectionné le même Soins plusieurs fois.", "warning");
                    formIsValid = false;
                    return;
                }

                selectedSoinsIds.add(idSoins);
                selectionsSoins.push(montant);
            });

            if (!formIsValid) {
                resetLoaderAndButton();
                return false;
            }

            // ================== PRODUITS ==================
            document.querySelectorAll('.produit-select').forEach(select => {

                const idProduit = select.value;
                const group = select.closest('.input_group');
                const quantite = parseInt(group.querySelector('.quantite-demande').value);
                const prix = parseInt(group.querySelector('.prix_produit').value.replace(/\D/g, ''));

                if (!idProduit || quantite <= 0 || isNaN(prix)) {
                    showAlert("ALERT", "Produit ou quantité invalide.", "warning");
                    formIsValid = false;
                    return;
                }

                selectionsProduits.push(prix * quantite);
            });

            if (!formIsValid) {
                resetLoaderAndButton();
                return false;
            }

            // ================== TOTAUX ==================
            const montantTotalSoins = selectionsSoins.reduce((a, b) => a + b, 0);
            const montantTotalProduits = selectionsProduits.reduce((a, b) => a + b, 0);

            montantTotal = montantTotalSoins + montantTotalProduits;

            // ================== ASSURANCE ==================
            const assuranceUtiliser = $('#assurance_utiliser').val();
            let taux = parseInt($('#patient_taux').val()) || 0;

            if (assuranceUtiliser === 'non' || taux === 0) {

                montantBaseAssurance = 0;
                montantBasePatient = montantTotal;

                $('#appliq_remise').val('patient');
                $('#appliq_remise option[value="assurance"]').hide();

                $('#montant_assurance').prop('readonly', true);
                $('#montant_assurance').val(0);

            } else {

                $('#appliq_remise option[value="assurance"]').show();

                $('#montant_assurance').prop('readonly', false);

                montantBaseAssurance = Math.round(montantTotal * taux / 100);
                montantBasePatient = montantTotal - montantBaseAssurance;
            }

            // ================== INITIALISATION REMISE ==================
            montantRemise = 0;
            cibleRemise = 'patient';
            appliquerRemise();

            // ================== UI ==================
            $('#div_loader').hide();
            $('#div_calcul').css('display', 'flex');
            $('#btn_calcul').show();

            return true;
        }

        function appliquerCalculs() {

            const assuranceUtiliser = $('#assurance_utiliser').val();
            let taux = parseInt($('#patient_taux').val()) || 0;

            if (assuranceUtiliser === 'non' || taux === 0) {
                montantBaseAssurance = 0;
                montantBasePatient = montantTotal;
                $('#appliq_remise').val('patient');
                $('#appliq_remise option[value="assurance"]').hide();
            } else {
                $('#appliq_remise option[value="assurance"]').show();
                montantBaseAssurance = Math.round(montantTotal * taux / 100);
                montantBasePatient = montantTotal - montantBaseAssurance;
            }

            recalculerAffichage();
        }

        function recalculerAffichage() {

            const remise = parseInt($('#taux_remise').val().replace(/\D/g, '')) || 0;
            const appliq = $('#appliq_remise').val();
            const assuranceUtiliser = $('#assurance_utiliser').val();

            let patient = montantBasePatient;
            let assurance = montantBaseAssurance;

            if (assuranceUtiliser === 'non') {
                patient = Math.max(montantBasePatient - remise, 0);
            } else {
                if (appliq === 'patient') {
                    patient = Math.max(montantBasePatient - remise, 0);
                }
                if (appliq === 'assurance') {
                    assurance = Math.max(montantBaseAssurance - remise, 0);
                }
            }

            $('#montant_total').val(formatPrice(montantTotal));
            $('#montant_patient').val(formatPrice(patient));
            $('#montant_assurance').val(formatPrice(assurance));
        }

        function appliquerRemise() {

            const assuranceUtiliser = $('#assurance_utiliser').val();

            if (assuranceUtiliser === 'non') {
                cibleRemise = 'patient';
            }

            // 🔒 plafond remise
            let maxRemise = cibleRemise === 'patient'
                ? montantBasePatient
                : montantBaseAssurance;

            if (montantRemise > maxRemise) {
                montantRemise = maxRemise;
            }

            let patientFinal = montantBasePatient;
            let assuranceFinal = montantBaseAssurance;

            if (cibleRemise === 'patient') {
                patientFinal = montantBasePatient - montantRemise;
            }

            if (cibleRemise === 'assurance') {
                assuranceFinal = montantBaseAssurance - montantRemise;
            }

            $('#montant_patient').val(formatPriceM(patientFinal));
            $('#montant_assurance').val(formatPriceM(assuranceFinal));
            $('#montant_total').val(formatPriceM(montantBasePatient + montantBaseAssurance));
            $('#taux_remise').val(formatPriceM(montantRemise));
            $('#appliq_remise').val(cibleRemise);
        }

        function formatPriceM(input) {

            if (input === null || input === undefined) return '0';

            // 🔑 conversion obligatoire
            let value = input.toString().replace(/\D/g, '');

            return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        $('#montant_patient, #montant_assurance').on('input', function () {

            let raw = parseInt($(this).val().replace(/\D/g, '')) || 0;

            const formatMontant = (montant) =>
                montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $(this).val(formatMontant(raw));
        });

        $('#taux_remise').on('input', function () {

            montantRemise = parseInt($(this).val().replace(/\D/g, '')) || 0;

            let max = cibleRemise === 'patient'
                ? montantBasePatient
                : montantBaseAssurance;

            if (montantRemise > max) {
                montantRemise = max;
            }

            appliquerRemise();
        });

        $('#appliq_remise').on('change', function () {

            cibleRemise = $(this).val();

            if ($('#assurance_utiliser').val() === 'non') {
                cibleRemise = 'patient';
            }

            // 🔒 AJUSTEMENT DE LA REMISE SELON LA CIBLE
            let maxRemise = cibleRemise === 'patient'
                ? montantBasePatient
                : montantBaseAssurance;

            if (montantRemise > maxRemise) {
                montantRemise = maxRemise;
            }

            appliquerRemise();
        });

        // -----------------------------------------------------

        function Eng_sa() {
            
            const selectionsSoins = [];
            const soinsSelects = document.querySelectorAll('.soins-select');
            soinsSelects.forEach(item => {

                const selectedOption = item.options[item.selectedIndex];
                const idSoins = selectedOption.value;
                // const montant = parseInt(selectedOption.dataset.prix);
                const montant = parseInt(item.closest('.input_group').querySelector('.prix_soins').value.replace(/[^0-9]/g, ''));

                selectionsSoins.push({
                    id: idSoins,
                    montant: montant
                });
            });

            const selectionsProduits = [];
            const produitsSelects = document.querySelectorAll('.produit-select');

            if (produitsSelects) {
                produitsSelects.forEach(select => {

                    const selectedOption = select.options[select.selectedIndex];
                    const idProduit = selectedOption.value;
                    // const quantiteDemande = parseInt(select.parentElement.querySelector('.quantite-demande').value);
                    // const prix = parseInt(selectedOption.dataset.prix);
                    const quantiteDemande = parseInt(select.closest('.input_group').querySelector('.quantite-demande').value);
                    const prix = parseInt(select.closest('.input_group').querySelector('.prix_produit').value.replace(/[^0-9]/g, ''));

                    selectionsProduits.push({
                        id: idProduit,
                        quantite: quantiteDemande,
                        montant: prix * quantiteDemande,
                        prix: prix
                    });
                });
            }

            const login = @json(Auth::user()->login);
            const patient_id = $('#patient_id').val();
            const typesoins_id = $('#typesoins_id').val();
            const numhosp = $('#numhosp').val();

            if (patient_id.trim() == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Patient.', "warning");
                return false;
            }

            if (typesoins_id.trim() == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Type de Soins.', "warning");
                return false;
            }

            var montant_assurance = $('#montant_assurance').val().replace(/[^0-9]/g, '');
            var taux_remise = $('#taux_remise').val().replace(/[^0-9]/g, '');
            var montant_total = $('#montant_total').val().replace(/[^0-9]/g, '');
            var montant_patient = $('#montant_patient').val().replace(/[^0-9]/g, '');
            var assurance_utiliser = $('#assurance_utiliser').val();

            // Validate monetary fields
            if (!montant_assurance || 
                !montant_total || 
                !montant_patient) {
                
                showAlert("ALERT", 'Vérifier les montants SVP.', "warning");
                return false; 
            }

            var montantAssuranceValue = parseFloat(montant_assurance);
            var montantTotalValue = parseFloat(montant_total);
            var montantPatientValue = parseFloat(montant_patient);
            var taux_remiseValue = parseFloat(taux_remise);

            if (isNaN(montantAssuranceValue) || 
                isNaN(montantTotalValue) || 
                isNaN(montantPatientValue) || 
                montantAssuranceValue < 0 || 
                montantTotalValue < 0 || 
                montantPatientValue < 0) {
                
                showAlert("ALERT", 'Vérifier les montants SVP (les montants ne doivent pas être négatifs ou invalide).', "warning");
                return false;
            }

            if ((montantAssuranceValue + montantPatientValue + taux_remiseValue) != montantTotalValue) {
                
                showAlert("ALERT", 'Vérifier les montants SVP.', "warning");
                return false; 
            }

            // var montant_assurance = $('#montant_assurance').val();
            // var taux_remise = $('#taux_remise').val();
            // var montant_total = $('#montant_total').val();
            // var montant_patient = $('#montant_patient').val();
            // var assurance_utiliser = $('#assurance_utiliser').val();

            // // Convertir les valeurs en nombres
            // var montantAssuranceValue = parseFloat(montant_assurance || 0);
            // var montantTotalValue = parseFloat(montant_total || 0);
            // var montantPatientValue = parseFloat(montant_patient || 0);
            // var tauxRemiseValue = parseFloat(taux_remise || 0);

            // // Vérifier que tous les champs obligatoires sont remplis
            // if (!montant_assurance || !montant_total || !montant_patient) {
            //     showAlert("ALERT", 'Vérifier les montants SVP (aucun montant ne doit être vide).', "warning");
            //     return false;
            // }

            // // Vérifier si les montants sont valides (pas NaN ou inférieurs à 0)
            // if (isNaN(montantAssuranceValue) || montantAssuranceValue < 0) {
            //     showAlert("ALERT", 'Le montant de l\'assurance est invalide ou négatif.', "warning");
            //     return false;
            // }

            // if (isNaN(montantTotalValue) || montantTotalValue < 0) {
            //     showAlert("ALERT", 'Le montant total est invalide ou négatif.', "warning");
            //     return false;
            // }

            // if (isNaN(montantPatientValue) || montantPatientValue < 0) {
            //     showAlert("ALERT", 'Le montant patient est invalide ou négatif.', "warning");
            //     return false;
            // }

            // // Vérifier si le taux de remise est valide, si applicable
            // if (taux_remise && (isNaN(tauxRemiseValue) || tauxRemiseValue < 0 || tauxRemiseValue > 100)) {
            //     showAlert("ALERT", 'Le taux de remise doit être un pourcentage valide entre 0 et 100.', "warning");
            //     return false;
            // }

            // // Vérifier que le montant patient correspond au montant total moins le montant de l'assurance
            // if (montantPatientValue !== (montantTotalValue - montantAssuranceValue)) {
            //     showAlert("ALERT", 'Le montant patient ne correspond pas au montant total moins le montant de l\'assurance.', "warning");
            //     return false;
            // }

            // // Vérification d'utilisation de l'assurance
            // if (assurance_utiliser && montantAssuranceValue <= 0) {
            //     showAlert("ALERT", 'Si l\'assurance est utilisée, son montant doit être supérieur à 0.', "warning");
            //     return false;
            // }


            var numcode = $('#numcode').val();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            console.log(selectionsSoins);
            console.log(selectionsProduits);

            $.ajax({
                url: $('#url').attr('content') +'/api/new_soinsam/',
                method: 'GET',
                data:{
                    selectionsSoins: selectionsSoins,
                    selectionsProduits: selectionsProduits,
                    montantAssurance: montant_assurance,
                    montantRemise: taux_remise,
                    montantTotal: montant_total,
                    montantPatient: montant_patient,
                    patient_id: patient_id,
                    typesoins_id: typesoins_id,
                    numcode: numcode || null,
                    numhosp: numhosp || null,
                    assurance_utiliser: assurance_utiliser,
                    login: login,
                },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        document.getElementById('div_calcul').style.display = 'none';

                        $('#typesoins_id').val('').trigger('change');
                        $('#patient_id').val('').trigger('change');

                        $('#numcode').val('');
                        $('#numhosp').val('');

                        const contenuDiv = document.getElementById('contenu_soins');
                        contenuDiv.innerHTML = "";
                        document.getElementById('div_btn_soins').style.display = 'none';

                        const contenuDivPro = document.getElementById('contenu_produit');
                        contenuDivPro.innerHTML = "";
                        document.getElementById('div_btn_pro').style.display = 'none';      

                        showAlert("ALERT", 'Enregistrement éffectué', "success");

                        table.ajax.reload(null, false);
                        Statistique();
                        select_produit();

                        var newConsultationTab = new bootstrap.Tab(document.getElementById('tab-oneAAA'));
                        newConsultationTab.show();

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.json) {
                        showAlert("ERREUR", 'Invalid selections format', "error");
                    } else if (response.existe) {
                        showAlert("ALERT", 'Le numéro de bon saisie existe déjà', "warning");
                    } else if (response.num_hosp_liberer) {
                        showAlert("ALERT", 'Le patient lier a ce numéro d\'hospitalisation a déjà été libérer', "warning");
                    } else if (response.matricule_hosp_error) {
                        showAlert("ALERT", 'Le patient n\'est pas lier a ce numéro d\'hospitalisation', "warning");
                    } else if (response.num_hosp_introuvable) {
                        showAlert("ALERT", 'Le numéro d\'hospitalisation n\'a pas été trouver', "warning");
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        };

        // -----------------------------------------------------

        function Statistique() {

            const nbre_day = document.getElementById("nbre_soinsam");

            $.ajax({
                url: $('#url').attr('content') +'/api/statistique_soinsam',
                method: 'GET',
                success: function(response) {
                    // Set the text content of each element
                    nbre_day.textContent = response.stat_soinsam_day;
                },
                error: function() {
                    // Set default values in case of an error
                    nbre_day.textContent = '0';
                }
            });
        }

    });
</script>


@endsection
