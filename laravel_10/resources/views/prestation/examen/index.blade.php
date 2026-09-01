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
                        <h3>EXAMENS</h3>
                        <h6>Services / Examens</h6>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-danger rounded-5 me-3">
                                    <i class="ri-syringe-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_analyse" ></h2>
                                    <p class="m-0">Analyse</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-warning rounded-5 me-3">
                                    <i class="ri-microscope-line fs-1"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1" id="nbre_imagerie" ></h2>
                                    <p class="m-0">Imagerie</p>
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
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Demande d'examen
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneAAAD" data-bs-toggle="tab" href="#oneAAAD" role="tab" aria-controls="oneAAAD" aria-selected="false" tabindex="-1">
                                    <i class="ri-health-book-line me-2"></i>
                                    Liste des demandes d'examens
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-folder-open-line me-2"></i>
                                    Liste des Examens
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-oneAAAP" data-bs-toggle="tab" href="#oneAAAP" role="tab" aria-controls="oneAAAP" aria-selected="false" tabindex="-1">
                                    <i class="ri-syringe-line me-"></i>
                                    Prélévement
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN" style="padding-bottom: 120px;" >
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Nouvelle Demande d'examen
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center">
                                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Patient</label>
                                            <select class="form-select select2" id="patient_id"></select>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">N° hospitalisation</label>
                                            <div class="input-group">
                                                <span class="input-group-text">N°</span>
                                                <input type="text" class="form-control" id="numhosp" autocomplete="off" placeholder="facultatif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="select_periode_div" style="display: none;" class="row gx-3 justify-content-center align-items-center mb-5">
                                    <div class="col-xxl-4 col-lg-4 col-sm-6">
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Période</label>
                                            <select class="form-select select2" id="periode">
                                                <option value=""></option>
                                                <option value="0">Jour</option>
                                                <option value="1">Nuit</option>
                                                <option value="2">Férier</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="select_examen_div" style="display: none;">
                                    <div class="row gx-3 justify-content-center align-items-center mb-4">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type d'examen</label>
                                                <select class="form-select select2" id="typeacte_id_exd"></select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Medecin</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">Dr</span>
                                                    <input type="text" class="form-control" id="medecin" autocomplete="off" placeholder="saisie obligatoire" oninput="this.value = this.value.toUpperCase()">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label">N° prise en charge</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">N°</span>
                                                    <input type="text" class="form-control" id="numcode" autocomplete="off" placeholder="facultatif">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Renseignement Clinique</label>
                                                <input type="text" class="form-control" id="rensg" autocomplete="off" placeholder="Facultatif" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="div_Examen" class="mb-3 p-2" style="display: none;" >
                                        <div class="card-header">
                                            <h5 class="card-title text-center">
                                                Choix des Examens
                                            </h5>
                                        </div>
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-12">
                                                <div class="row gx-3 justify-content-center align-items-center">
                                                    <div class="col-12 mb-3 text-center">
                                                        <button type="button" id="add_select_examen" class="btn btn-info">
                                                            <i class="ri-sticky-note-add-line"></i>
                                                            Ajouter un Examen
                                                        </button>
                                                    </div>
                                                    <div class="col-12" id="contenu_examen">

                                                    </div>
                                                    <div class="row gx-3" id="div_btn_examen">
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6" id="div_preleve" style="display: none;">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Prélevement
                                                                </span>
                                                                <select class="form-select" id="charge_prelev">
                                                                    <option value="patient">Charge patient</option>
                                                                    <option value="assurance">charge assurance</option>
                                                                </select>
                                                                <input type="tel" class="form-control" id="montant_pre_examen" placeholder="Taux de Couverture">
                                                                <span class="input-group-text">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Taux
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="patient_taux" placeholder="Taux de Couverture">
                                                                <span class="input-group-text w-25">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Assurance
                                                                </span>
                                                                <input type="tel" class="form-control" id="montant_assurance_examen" placeholder="Part Assurance">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Patient
                                                                </span>
                                                                <input type="tel" class="form-control" id="montant_patient_examen" placeholder="Part Patient">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Remise
                                                                </span>
                                                                <input type="tel" class="form-control" id="taux_remise" value="0">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text w-25">
                                                                    Total
                                                                </span>
                                                                <input readonly type="tel" class="form-control" id="montant_total_examen" placeholder="Montant Total">
                                                                <span class="input-group-text w-25">Fcfa</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 mb-3 text-center">
                                                            <button type="button" id="btn_recal" class="btn btn-warning">
                                                                Vérification
                                                            </button>
                                                            <button type="button" id="btn_eng_exd" class="btn btn-success">
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
                            </div>
                            <div class="tab-pane fade " id="oneAAAD" role="tabpanel" aria-labelledby="tab-oneAAAD">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Examens Demandées
                                                </h5>
                                                <div class="d-flex" >
                                                    <input type="text" id="searchInputd" placeholder="Recherche" class="form-control me-1">
                                                </div>
                                            </div>
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="w-100">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Du</span>
                                                        <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-2 months')) }}" max="{{ date('Y-m-d') }}">
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
                                                        <table id="Table_day" class="table Table_examend">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Type d'examen</th>
                                                                    <th scope="col">Patient</th>
                                                                    <th scope="col">Assurer ?</th>
                                                                    <th scope="col">Médecin</th>
                                                                    <th scope="col">Nombre d'examen</th>
                                                                    <th scope="col">Prélevement</th>
                                                                    <th scope="col">Montant Total</th>
                                                                    <th scope="col">N° facture</th>
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
                            <div class="tab-pane fade " id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Examens
                                                </h5>
                                                <div class="d-flex" >
                                                    <a id="btn_refresh_tableE" class="btn btn-outline-info ms-auto">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_examen">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Examen</th>
                                                                    <th scope="col">Type</th>
                                                                    <th scope="col">Code</th>
                                                                    <th scope="col">Valeur</th>
                                                                    <th scope="col">Cotation</th>
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
                            <div class="tab-pane fade" id="oneAAAP" role="tabpanel" aria-labelledby="tab-oneAAAP">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Prélévement
                                    </h5>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center">
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Prix</label>
                                            <div class="input-group">
                                                <input type="tel" class="form-control" id="prix_preleve" placeholder="Saisie Obligatoire">
                                                <span class="input-group-text">Fcfa</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_pre" class="btn btn-success">
                                                    Enregistrer
                                                </button>
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

<div class="modal fade" id="PRIXexamen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détail Prix
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-3 mt-3" id="modal_Prix" style="display: none;" ></div>
            <div id="message_loader" style="display: none;" class="mb-3 mt-3">
                <p class="text-center" >
                    Aucune donnée n'a été trouvé
                </p>
            </div>
            <div id="div_loader" style="display: none;" class="mb-3 mt-3">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">
                    Examens Demandés
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
                                                        <th>Examen</th>
                                                        <th>Montant</th>
                                                        <th>Accepté ?</th>
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
                Voulez-vous vraiment supprimé cet examen ?
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
<script src="{{asset('assets/app/js/pdf/examen.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        Statistique();
        select_patient();
        montant_prelevement();
        select_type_examen();

        $("#btn_eng_pre").on("click", eng_pre);
        $("#add_select_examen").on("click", add_select);
        $("#btn_eng_exd").on("click", eng_exd);
        $("#btn_recal").on("click", updateMontantTotalExamen);
        $("#deleteBtn").on("click", delete_exd);

        $('#searchDate1').on('change', function() {
            const date1 = $(this).val();
            
            if (date1) {
                $('#searchDate2').val(date1);
                $('#searchDate2').attr('min', date1);
            }
        });

        $('#searchDate2').on('change', function() {
            const date2 = $(this).val();
            const date1 = $('#searchDate1').val();

            if (date2 && date1 && new Date(date2) < new Date(date1)) {
                alert('La date de sortie probable ne peut pas être antérieure à la date d\'entrée.');
                $(this).val(date1);
            }
        });

        function allowOnlyLetters(event) {
            const key = event.key;
            if (!/^[a-zA-Z]+$/.test(key)) {
                event.preventDefault();
            }
        }

        function allowOnlyNumbers(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        }

        function allowOnlyNumbersAndLetters(event) {
            const charCode = event.charCode || event.keyCode; // Obtenir le code de la touche pressée
            const char = String.fromCharCode(charCode);      // Convertir le code en caractère

            // Vérifier si le caractère est une lettre ou un chiffre (alphanumérique)
            const isValid = /^[a-zA-Z0-9]$/.test(char);

            // Si la touche est invalide, empêcher l'action par défaut
            if (!isValid) {
                event.preventDefault();
            }
        }

        function formatPriceInput(event) {
            this.value = formatPrice(this.value);
        }

        ['prix_preleve','taux_remise'].forEach(id => {
            document.getElementById(id).addEventListener('input', formatPriceInput);
            document.getElementById(id).addEventListener('keypress', allowOnlyNumbers);
        });

        ['numcode'].forEach(id => {
            document.getElementById(id).addEventListener('keypress', allowOnlyNumbersAndLetters);
        });

        const table_examen = $('.Table_examen').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_examen_all`,
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
                    data: 'denomination', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/examen.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true,
                },
                { 
                    data: 'type',
                    render: (data) => `${data == null ? `Néant` : `${data}` }`,
                    searchable: true,
                },
                { 
                    data: 'numexam',
                    searchable: true,
                },
                { 
                    data: 'codfamexam',
                    render: (data) => `${data == null || data == '' ? `Néant` : `${data}` }`,
                    searchable: true,
                },
                { 
                    data: 'cot',
                    render: (data) => `${data == null || data == '' ? 0 : `${data}` }`,
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#PRIXexamen" id="detail"
                                data-numexam = ${row.numexam}
                            >
                                <i class="ri-eye-line"></i>
                            </a>
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

            $('.Table_examen').on('click', '#detail', function() {
                const id = $(this).data('numexam');

                const modal_Prix = document.getElementById('modal_Prix'); // Pour les produits
                const message_Prix = document.getElementById('message_loader');
                const loader_Prix = document.getElementById('div_loader');

                modal_Prix.style.display = 'none';
                message_Prix.style.display = 'none'; // Cacher au départ
                loader_Prix.style.display = 'block';

                fetch($('#url').attr('content') +`/api/prix_examen/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {

                        if (data.success) {

                            const prix = data.prix;

                            if (prix.length > 0) {
                                const modal = document.getElementById('modal_Prix');
                                modal.innerHTML = '';

                                // Generate the soins HTML
                                let soinsHTML = prix.map(item => {
                                    return `
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <div class="card-body">
                                                    <ul class="list-group">
                                                        ${item.codeassurance == 'NONAS' ? `<li class="list-group-item text-center text-white bg-success">` : `<li class="list-group-item active text-center" aria-current="true">`}
                                                            ${item.codeassurance == 'NONAS' ? 'Patient non assuré' : `${item.assurance}`}
                                                        </li>
                                                        <li class="list-group-item">
                                                            Montant Jour : ${formatPriceT(item.montjour)} Fcfa
                                                        </li>
                                                        <li class="list-group-item">
                                                            Montant Nuit : ${formatPriceT(item.montnuit)} Fcfa
                                                        </li>
                                                        <li class="list-group-item">
                                                            Montant Férier : ${formatPriceT(item.montferie)} Fcfa
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join(''); // Combine all items into a single string

                                // Generate the full modal content
                                const div = document.createElement('div');
                                div.innerHTML = `
                                    <div class="row gx-3">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <a href="doctors-profile.html" class="d-flex align-items-center flex-column">
                                                            <img src="{{asset('assets/images/tarif.png')}}" class="img-7x rounded-circle mb-3 border border-3">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        ${soinsHTML}
                                    </div>
                                `;

                                // Append the content to the modal
                                modal.appendChild(div);

                                modal_Prix.style.display = 'block';
                                message_Prix.style.display = 'none'; // Cacher au départ
                                loader_Prix.style.display = 'none';
                            } else {

                                modal_Prix.style.display = 'none';
                                message_Prix.style.display = 'block'; // Cacher au départ
                                loader_Prix.style.display = 'none';
                            }

                        } else {
                            modal_Prix.style.display = 'none';
                            message_Prix.style.display = 'block'; // Cacher au départ
                            loader_Prix.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                        modal_Prix.style.display = 'none';
                        message_Prix.style.display = 'block'; // Cacher au départ
                        loader_Prix.style.display = 'none';
                    });

            });
        }

        $('#btn_refresh_tableE').on('click', function() {
            table_examen.ajax.reload(null, false);
        });

        const table_examend = $('.Table_examend').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const date1 = $('#searchDate1').val();
                const date2 = $('#searchDate2').val();
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_examend_all/${date1}/${date2}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network list_examend_all.');
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
                    data: 'typedemande', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/examen.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        <span class="badge ${data === 'analyse' ? 'bg-danger' : 'bg-primary'}">
                            ${data}
                        </span>
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'patient',
                    searchable: true,
                },
                { 
                    data: 'assure', 
                    render: (data, type, row) => `
                        <span class="badge ${data == 1 ? 'bg-success' : 'bg-danger'}">
                            ${data == 1 ? 'Oui' : 'Non'}
                        </span>
                    `,
                    searchable: true, 
                },
                { 
                    data: 'codemedecin', 
                    render: (data, type, row) => `${data == null ? `${row.medicin_traitant}` : `${row.medecin}`}`,
                    searchable: true, 
                },
                { 
                    data: 'nbre',
                    searchable: true,
                },
                { 
                    data: 'prelevement', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'numfacbul', 
                    render: (data) => `${data}`,
                    searchable: true, 
                },
                { 
                    data: 'date', 
                    render: (data, type, row) => `${formatDateHeure(data)}`,
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
                                    <a href="#" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#Detail" id="detail"
                                        data-id="${row.idtestlaboimagerie}"
                                    >
                                        <i class="ri-archive-2-line"></i>
                                        Examen(s) demandée(s)
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="fiche"
                                        data-id="${row.idtestlaboimagerie}"
                                    >
                                        <i class="ri-printer-line"></i>
                                        Imprimer facture
                                    </a>
                                </li>
                                ${parseFloat(row.montant_regle) === 0 && (!row.numhospit || row.numhospit === '') ? `
                                    <li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.idtestlaboimagerie}" 
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
                initializeRowEventListenersExamend();
            },
        });

        function initializeRowEventListenersExamend() {

            $('.Table_examend').on('click', '#detail', function() {
                const id = $(this).data('id');

                const tableBodyP = document.querySelector('#TableP tbody');
                const messageDivP = document.getElementById('message_TableP');
                const tableDivP = document.getElementById('div_TableP');
                const loaderDivP = document.getElementById('div_Table_loaderP');

                messageDivP.style.display = 'none';
                tableDivP.style.display = 'none';
                loaderDivP.style.display = 'block';

                fetch($('#url').attr('content') +`/api/list_facture_exam_d/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const factureds = data.factured;
                        const sumMontantEx = data.sumMontantEx;

                        // Clear any existing rows in the table body
                        tableBodyP.innerHTML = '';

                        if (factureds.length > 0) {

                            loaderDivP.style.display = 'none';
                            messageDivP.style.display = 'none';
                            tableDivP.style.display = 'block';

                            // Loop through each item in the chambre array
                            factureds.forEach((item, index) => {
                                // Create a new row
                                const row = document.createElement('tr');
                                // Create and append cells to the row based on your table's structure
                                row.innerHTML = `
                                    <td>
                                        <h6>${item.examen}</h6>
                                    </td>
                                    <td>
                                        <h6>${formatPriceT(item.prix)} Fcfa</h6>
                                    </td>
                                    <td>
                                        <h6>
                                            ${item.resultat == null || item.resultat == '' ? `Néant` : item.resultat }
                                        </h6>
                                    </td>
                                `;
                                // Append the row to the table body
                                tableBodyP.appendChild(row);

                            });

                            const row2 = document.createElement('tr');
                            row2.innerHTML = `
                                <td colspan="1">&nbsp;</td>
                                <td colspan="2" >
                                    <h5 class="mt-4 text-success">
                                        Total : ${formatPriceT(sumMontantEx)} Fcfa
                                    </h5>
                                </td>
                            `;
                            tableBodyP.appendChild(row2);

                            const row3 = document.createElement('tr');
                            row3.innerHTML = `
                                <td colspan="5">
                                    <h6 class="text-danger">NOTE</h6>
                                    <p class="small m-0">
                                        Montant total de la facture = Montant Total examens + montant du prélevement.
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

            $('.Table_examend').on('click', '#fiche', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const id = $(this).data('id');

                fetch($('#url').attr('content') +`/api/detail_examen/${id}`) // API endpoint
                    .then(response => response.json())
                    .then(data => {
                        // Access the 'chambre' array from the API response
                        const facture = data.facture;
                        const examen = data.examen;
                        const sumMontantEx = data.sumMontantEx;

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        pdfFactureExamen(examen, facture, sumMontantEx);

                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('.Table_examend').on('click', '#delete', function() {
                const id = $(this).data('id');

                $('#Iddelete').val(id);
            });
        }

        $('#btn_search_table').on('click', function() {
            table_examend.ajax.reload(null, false);
        });

        function delete_exd() {

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
                url: $('#url').attr('content') +'/api/delete_Exd/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        table_examend.ajax.reload(null, false);
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", response.message, "error");
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

        let cachedExamens = {};

        function select_type_examen() {

            const selectElementexd = $('#typeacte_id_exd');
            selectElementexd.empty();

            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner',
            });
            selectElementexd.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_type_examend',
                method: 'GET',
                dataType: 'json',
                success: function(data) {

                    data.type.forEach(item => {
                        const option = $('<option>', {
                            value: item.codfamexam,
                            text: item.nomfamexam,
                        });
                        selectElementexd.append(option);
                    });
                },
                error: function() {
                    console.error('Failed to fetch data.');
                }
            });

            selectElementexd.on('change', function() {

                if (this.value == 'B') {
                    $('#montant_pre_examen').val($('#prix_preleve').val());
                    $('#div_preleve').show();
                } else {
                    $('#montant_pre_examen').val(0);
                    $('#div_preleve').hide();
                }

                $('#charge_prelev').val('patient').trigger('change.select2');

                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const selectElement = $('#patient_id');
                const selectedOption = selectElement.find('option:selected');
                let codeassurance = selectedOption.data('codeassurance');

                let periode = $('#periode').val();

                const id = this.value;
                if (id) {
                    // Vérifier si les données sont déjà en cache
                    if (cachedExamens[id]) {

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                        afficherExamens(id); // Utiliser les données du cache
                    } else {
                        const url =$('#url').attr('content') +`/api/select_examen/${id}/${codeassurance}/${periode}`;
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                
                                var preloader = document.getElementById('preloader_ch');
                                if (preloader) {
                                    preloader.remove();
                                }

                                if (data.success) {

                                    cachedExamens[id] = data.examens;
                                    afficherExamens(id);

                                } else if (data.existep) {

                                    showAlert("ALERT", "Aucun exame n'a été trouvé.", "warning");
                                    return false;
                                } else {
                                    showAlert("ALERT", "Aucun exame n'a été trouvé.", "warning");
                                    return false;
                                }

                                
                            })
                            .catch(error => {

                                var preloader = document.getElementById('preloader_ch');
                                if (preloader) {
                                    preloader.remove();
                                }

                                console.error('Erreur lors du chargement des données:', error);
                                showAlert("ALERT", "Erreur lors du chargement des données.", "error");
                                return false;

                            });
                    }
                } else {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert("ALERT", "Erreur lors du chargement des données.", "error");

                    const contenuDiv = document.getElementById('contenu_examen');
                    contenuDiv.innerHTML = '';
                    document.getElementById('div_Examen').style.display = "none";
                }
            });
        }

        function add_select() {
            const contenuDiv = document.getElementById('contenu_examen');
            const id = $('#typeacte_id_exd').val();

            if (id === '') {
                showAlert("ALERT", "Selectionner un Type d'examen.", "warning");
                return false;
            }

            // Vérifier si les données sont en cache
            if (cachedExamens[id]) {
                // Utiliser les données en cache
                addSelectExamen(contenuDiv, cachedExamens[id], id);
            } else {
                showAlert("Erreur", "Les examens ne sont pas disponibles. Veuillez sélectionner un type valide.", "danger");
            }
        }

        function afficherExamens(id) {
            const contenuDiv = document.getElementById('contenu_examen');
            contenuDiv.innerHTML = '';
            document.getElementById('montant_total_examen').value = '';
            document.getElementById('montant_patient_examen').value = '';
            document.getElementById('montant_assurance_examen').value = '';
            let type_examen = $('#typeacte_id_exd').val();

            // Utiliser les examens en cache
            addSelectExamen(contenuDiv, cachedExamens[id], type_examen);

            document.getElementById('div_Examen').style.display = "block";

        }

        function montant_prelevement() {
            $.ajax({
                url: $('#url').attr('content') +'/api/montant_prelevement',
                method: 'GET',
                success: function(response) {
                    data = response.prelevement;
                    document.getElementById('prix_preleve').value = formatPriceT(data.prix);
                    document.getElementById('montant_pre_examen').value = formatPriceT(data.prix);
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function eng_pre()
        {
            const prix = document.getElementById("prix_preleve");

            if(!prix.value.trim()){
                showAlert('Alert', 'Saisie le montant du prélevement SVP.','warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/prelevement_Modif',
                method: 'GET',
                data: { 
                    prix: prix.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        montant_prelevement();

                        showAlert('Succès', 'Mise à jour éffectuée.','success');
                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue','error');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
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
                'data-codeassurance': 0,
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
                            'data-codeassurance': item.codeassurance,
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
            $('#periode').val(null).trigger('change.select2');
            $('#typeacte_id_exd').val(null).trigger('change.select2');
            $('#medecin').val('');
            $('#numhosp').val('');
            $('#numcode').val('');
            $('#rensg').val('');
            $('#taux_remise').val(0);
            $('#select_periode_div').show();
            $('#select_examen_div').hide();
        });

        $('#periode').on('change', function() {
            $('#select_examen_div').show();
            rech_dosier(); 
        });

        $('#charge_prelev').on('change', function() {
            updateMontantTotalExamen(); 
        });

        function rech_dosier() {
            
            const selectElement = $('#patient_id');

            if (selectElement.val() !== '') {
                const selectedOption = selectElement.find('option:selected'); // Obtenir l'option sélectionnée
                
                // Récupérer le taux depuis l'attribut data-taux
                let taux = selectedOption.data('taux') || 0;
                const inputAssurance = $('#montant_assurance_examen');
                const assuranceOption = document.querySelector("#charge_prelev option[value='assurance']");

                if (taux == 0) {

                    assuranceOption.style.display = 'none';

                    inputAssurance.prop('readonly', true);
                    inputAssurance.val('0');

                } else {

                    assuranceOption.style.display = 'block';

                    inputAssurance.prop('readonly', false);

                }

                // Mettre à jour le champ patient_taux
                $('#patient_taux').val(taux);

                // Réinitialiser d'autres champs
                $('#numcode').val('');
                if (selectedOption.val()) {
                    const assurer = selectedOption.data('assurer'); // Récupérer data-assurer
                    if (assurer === 1) {
                        $('#div_numcode').show();
                    } else {
                        $('#div_numcode').hide();
                    }
                }

                // Réinitialiser le contenu examen et les champs associés
                $('#contenu_examen').html('');
                $('#div_btn_examen').hide();
                $('#div_Examen').hide();
                // $('#typeacte_id_exd').val('');

                // Appeler la fonction pour mettre à jour le montant total
                updateMontantTotalExamen();
            }
        }

        function addSelectExamen(contenuDiv, examens, type_examen) {

            const index = contenuDiv.childElementCount + 1;

            const patientTaux = document.getElementById('patient_taux').value;

            const div = document.createElement('div');
            div.className = 'mt-3 mb-3 border border-1 p-3 rounded-2';

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            div.innerHTML = `
                <div class="input_group exam-container">
                    <div class="card-header">
                        <h5 class="card-title text-center Title_Examen">
                            EXAMEN ${index}
                        </h5>
                    </div>
                    <div class="row gx-3 mb-3 text-center ">
                        <div class="col-xxl-3 col-lg-3 col-md-3 col-sm-4">
                            <div class="mb-3">
                                <label class="form-label">Prise en Charge</label>
                                <select class="form-select examen-select-assurer">
                                    ${patientTaux == 0 ? `
                                        <option selected value="non">Non</option>
                                    ` : `
                                        <option selected value="non">Non</option>
                                        <option value="oui">Oui</option>
                                    `}
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-9 col-lg-9 col-md-9 col-sm-8">
                            <div class="mb-3">
                                <label class="form-label">Examen</label>
                                <select class="form-select examen-select select2">
                                    <option value="">Selectionner</option>
                                    ${examens.map(item => 
                                        `<option value="${item.numexam}" 
                                                data-cotation="${item.cot ?? 1}"
                                                data-codfamexam="${item.codfamexam}"
                                                data-valeur="${formatPriceT(item.valeur ?? 0)}" 
                                                data-valeur_non_as="${formatPriceT(item.valeur_non_as ?? 0)}"
                                                ${patientTaux == 0 ? `
                                                    data-assurer="non"
                                                ` : `
                                                    data-assurer="oui"
                                                `}
                                                data-tarif="${formatPriceT(item.tarif)}"
                                                data-tarif_non_as="${formatPriceT(item.tarif_non_as)}"
                                                data-tarifr="${item.tarif ?? 0}"
                                                data-tarif_non_asr="${item.tarif_non_as ?? 0}"
                                            >
                                            ${item.denomination}
                                        </option>`).join('')}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-lg-4 col-md-4 col-sm-6 div_cotation">
                            <div class="mb-3">
                                <label class="form-label">Cotation</label>
                                <input readonly type="tel" class="form-control cotation-field" placeholder="Cotation">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 div_v_cotation">
                            <div class="mb-3">
                                <label class="form-label">Valeur Cotation</label>
                                <input type="tel" class="form-control cotation-val-field" placeholder=" Valeur Cotation">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Prix</label>
                                <div class="input-group mb-3">
                                    <input type="tel" class="form-control prix-field" placeholder="Prix">
                                    <span class="input-group-text w-25">Fcfa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Montant Total</label>
                                <div class="input-group mb-3">
                                    <input readonly type="tel" class="form-control montant-field" placeholder="Montant">
                                    <span class="input-group-text w-25">Fcfa</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center" >
                            <button class="btn btn-outline-danger suppr-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            if (type_examen == 'Y') {
                div.querySelector('.div_cotation').style.display = "none";
                div.querySelector('.div_v_cotation').style.display = "none";
            } else {
                div.querySelector('.div_cotation').style.display = "block";
                div.querySelector('.div_v_cotation').style.display = "block";
            }

            // Ajouter l'élément dans le parent (contenu div)
            contenuDiv.appendChild(div);

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

            checkContenuExamen();

            // Ajouter un event listener pour le bouton supprimer
            div.querySelector('.suppr-btn').addEventListener('click', () => {
                div.remove(); // Supprimer l'élément div parent
                checkContenuExamen(); // Re-vérifier le contenu
                updateMontantTotalExamen(); // Mettre à jour le montant total après la suppression
                updateExamenIndexes(contenuDiv);
            });

            $(document).on("change", ".examen-select", function() {
                let $div = $(this).closest(".exam-container"); // Récupère le parent contenant les champs
                let $examenSelect = $(this);
                let $assurerSelect = $div.find(".examen-select-assurer");
                let $cotationSelect = $div.find(".cotation-val-field");
                let $prixSelect = $div.find(".prix-field");
                let $montantSelect = $div.find(".montant-field");

                let selectedOption = $examenSelect.find(":selected"); // Récupère l'option sélectionnée

                // console.log("Option sélectionnée :", selectedOption); // Debugging

                // Récupération des valeurs avec .attr() au lieu de .data()
                let codfamexam = selectedOption.attr("data-codfamexam") || "";
                let cotation = selectedOption.attr("data-cotation") || 0;
                let valeurNonAssure = selectedOption.attr("data-valeur_non_as") || 0;
                let tarifNonAssure = selectedOption.attr("data-tarif_non_as") || 0;

                // Mise à jour des champs
                $div.find(".cotation-field").val(codfamexam);

                if (parseInt(cotation) === 0) {
                    $cotationSelect.val(1);
                } else {
                    $cotationSelect.val(cotation);
                }

                if (typeof patientTaux !== "undefined" && patientTaux > 0) {
                    $assurerSelect.val("non");
                    $prixSelect.val(valeurNonAssure);
                    $montantSelect.val(tarifNonAssure);
                } else {
                    $prixSelect.val(valeurNonAssure);
                    $montantSelect.val(tarifNonAssure);
                }

                // console.log("Prix:", valeurNonAssure, "Montant:", tarifNonAssure); // Debugging

                updateMontantTotalExamen();
            });

            // Gestion du changement d'assurance
            $(document).on("change", ".examen-select-assurer", function() {
                let $div = $(this).closest(".exam-container");
                let $examenSelect = $div.find(".examen-select");
                let $assurerSelect = $(this);
                let selectedOption = $examenSelect.find(":selected");

                if (!$examenSelect.val()) {
                    $assurerSelect.val("non");
                    showAlert("ALERT", "Veuillez sélectionner un examen.", "warning");
                    return;
                }

                if (selectedOption.length) {
                    selectedOption.data("assurer", $assurerSelect.val());
                }

                $div.find(".cotation-field").val(selectedOption.data("codfamexam"));

                if (parseInt(selectedOption.data("cotation")) === 0) {
                    $div.find(".cotation-val-field").val(1);
                } else {
                    $div.find(".cotation-val-field").val(selectedOption.data("cotation"));
                }

                updateMontantTotalExamen();
            });

            // Gestion du changement de cotation
            $(document).on("input", ".cotation-val-field", function() {
                let $div = $(this).closest(".exam-container");
                let $cotationSelect = $(this);
                let $montantSelect = $div.find(".montant-field");
                let $prixSelect = $div.find(".prix-field");

                if (!$cotationSelect.val().trim() || $cotationSelect.val().trim() == 0) {
                    $cotationSelect.val(1);
                }

                let rawValue = parseInt($cotationSelect.val().replace(/[^0-9]/g, "")) || 1;
                $cotationSelect.val(rawValue);

                if (!$div.find(".examen-select").val()) {
                    $cotationSelect.val(null);
                    showAlert("ALERT", "Veuillez sélectionner un examen.", "warning");
                    return;
                }

                let prix = parseInt($prixSelect.val().replace(/[^0-9]/g, "")) || 0;
                let montantT = prix * rawValue;

                $montantSelect.val(montantT.toLocaleString());

                updateMontantTotalExamen();
            });

            // Gestion du changement de prix
            $(document).on("input", ".prix-field", function() {
                let $div = $(this).closest(".exam-container");
                let $prixSelect = $(this);
                let $montantSelect = $div.find(".montant-field");
                let $cotationSelect = $div.find(".cotation-val-field");

                if (!$prixSelect.val().trim() || $prixSelect.val().trim() == 0) {
                    $prixSelect.val(0);
                }

                let rawValue = parseInt($prixSelect.val().replace(/[^0-9]/g, "")) || 0;
                $prixSelect.val(rawValue.toLocaleString());

                if (!$div.find(".examen-select").val()) {
                    $prixSelect.val(null);
                    showAlert("ALERT", "Veuillez sélectionner un examen.", "warning");
                    return;
                }

                let cotation = parseInt($cotationSelect.val().replace(/[^0-9]/g, "")) || 1;
                let montantT = cotation * rawValue;

                $montantSelect.val(montantT.toLocaleString());

                updateMontantTotalExamen();
            });

        }

        $('#montant_pre_examen').on('input', function() {

            if (!$(this).val().trim()) {
                $(this).val(0);
            }

            let rawValue = 0;
            rawValue = parseInt($(this).val().replace(/[^0-9]/g, ''));
            $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

            const selects = document.querySelectorAll('.examen-select');

            for (let select of selects) {
                const selectedOption = select.value;

                if (!selectedOption) {
                    showAlert("ALERT", "Veuillez vérifier les examens selectionnées.", "warning");
                    return; // Arrête l'exécution ici
                }
            }

            updateMontantTotalExamen();

        });

        $('#taux_remise').on('input', function() {

            if (!$(this).val().trim()) {
                $(this).val(0);
            }

            updateMontantTotalExamen();

        });

        $('#montant_assurance_examen, #montant_patient_examen').on('input', function () {

            let raw = parseInt($(this).val().replace(/\D/g, '')) || 0;

            const formatMontant = (montant) =>
                montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $(this).val(formatMontant(raw));
        });

        function updateExamenIndexes(contenuDiv) {
            const headers = contenuDiv.querySelectorAll('.Title_Examen');
            headers.forEach((header, index) => {
                header.textContent = `EXAMEN ${index + 1}`;
            });
        }

        function updateMontantTotalExamen() {
            let montantTotal = 0;
            let montantPatient = 0;
            let montantAssurance = 0;

            const selects = document.querySelectorAll('.examen-select');
            
            let patientTaux = parseInt(document.getElementById('patient_taux').value) || 0;
            let preleve = parseInt(document.getElementById('montant_pre_examen').value.replace(/\./g, '')) || 0;

            selects.forEach(select => {
                let montantExamen = 0;
                const selectedOption = select.options[select.selectedIndex];
                const assurance = select.closest('.input_group').querySelector('.examen-select-assurer').value;
                montantExamen = parseInt(select.closest('.input_group').querySelector('.montant-field').value.replace(/[^0-9]/g, '')) || 0;

                if (assurance === 'non') {
                    montantPatient += montantExamen;
                } else {
                    let montantCouvert = Math.floor((montantExamen * patientTaux) / 100); // Arrondi pour éviter les décimales
                    let montantRestant = montantExamen - montantCouvert; // Garantir que la somme reste correcte

                    montantAssurance += montantCouvert;
                    montantPatient += montantRestant;
                }

                montantTotal += montantExamen; 
            });

            // Calcul du prélèvement sans décimales
            let preleve_patient = 0;
            let preleve_assurance = 0;

            if ($('#charge_prelev').val() == 'patient') {
                preleve_patient = preleve;
                preleve_assurance = 0;
            } else if ($('#charge_prelev').val() == 'assurance') {
                preleve_assurance = Math.floor((preleve * patientTaux) / 100); 
                preleve_patient = preleve - preleve_assurance;
            }

            montantPatient += preleve_patient;
            montantAssurance += preleve_assurance;
            montantTotal += preleve;

            // Formater les montants avec des points
            const formatMontant = (montant) => montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            let remise = parseInt($('#taux_remise').val().replace(/[^0-9]/g, '')) || 0;
            if (remise >= montantPatient) {
                $('#taux_remise').val(formatMontant(montantPatient));
                montantPatient = 0;
            } else {
                montantPatient -= remise;
            }

            // Mettre à jour les champs
            document.getElementById('montant_total_examen').value = formatMontant(montantTotal);
            document.getElementById('montant_patient_examen').value = formatMontant(montantPatient);
            document.getElementById('montant_assurance_examen').value = formatMontant(montantAssurance);
        }

        function checkContenuExamen() {
            const contenuDiv = document.getElementById('contenu_examen');
            const divBtn = document.getElementById('div_btn_examen');
            
            // Si la div #contenu a un contenu, on affiche le bouton, sinon on le cache
            if (contenuDiv.innerHTML.trim() !== "") {
                divBtn.style.display = "block"; // Afficher le bouton
            } else {
                divBtn.style.display = "none";  // Cacher le bouton
            }
        }

        function CalculMontant() {

            const patient_id = $('#patient_id').val();
            const typeacte_id_exd = $('#typeacte_id_exd').val();

            // 1. Vérifier si le matricule du patient est renseigné
            if (patient_id === '') {
                showAlert("ALERT", "Veuillez sélectionner un Patient.", "warning");
                return false;
            }

            // 2. Vérifier si un type de soins a été sélectionné
            if (typeacte_id_exd === '') {
                showAlert("ALERT", "Veuillez sélectionner un Type d'examen.", "warning");
                return false;
            }

            const contenuDiv = $('#contenu_examen');
            if (contenuDiv.html().trim() === "") {
                showAlert("ALERT", 'Aucun examen n\'a été sélectionné.', "warning");
                return false;
            }

            let formIsValid = true;
            const selectionsExamen = [];

            // 3. Vérifier si tous les soins infirmiers ont été sélectionnés
            const $examenSelects = $('.examen-select');
            const selectedExamenIds = new Set();

            $examenSelects.each(function() {
                const $select = $(this);
                const selectedOption = $select.find(':selected');
                const idExamen = selectedOption.val();

                if (!idExamen) {
                    showAlert("ALERT", 'Aucun Examen n\'a été sélectionné.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (selectedExamenIds.has(idExamen)) {
                    showAlert("ALERT", 'Vous avez sélectionné le même Examen plusieurs fois.', "warning");
                    formIsValid = false;
                    return false;
                }

                selectedExamenIds.add(idExamen);
                selectionsExamen.push({
                    id: idExamen,
                });
            });

            if (!formIsValid) {
                resetLoaderAndButton();
                return false;
            }

            return true;
        }

        function eng_exd() {

            try {
                const calculResult = CalculMontant();
                if (!calculResult) {
                    return false;
                }
            } catch (error) {
                showAlert("ALERT","Veuillez bien vérifier les données saisies", "info");
                return false;
            }

            const selectionsExamen = [];
            const $examenSelects = $('.examen-select');

            $examenSelects.each(function() {

                let montant = 0;
                let cotation = 0;
                let valeur = 0;

                montant = parseInt($(this).closest('.input_group').find('.montant-field').val().replace(/[^0-9]/g, '')) || 0;
                cotation = parseInt($(this).closest('.input_group').find('.cotation-val-field').val().replace(/[^0-9]/g, '')) || 0;
                valeur = parseInt($(this).closest('.input_group').find('.prix-field').val().replace(/[^0-9]/g, '')) || 0;

                const selectedOption = $(this).find(':selected');
                const idExamen = selectedOption.val();
                const examen = selectedOption.text().trim();
                const accepte = selectedOption.data('assurer');
                const code = selectedOption.data('codfamexam');

                selectionsExamen.push({
                    id: idExamen,
                    examen: examen,
                    cotation: cotation,
                    valeur: valeur,
                    montant: montant,
                    accepte: accepte,
                    code: code,
                });
            });

            // console.log(selectionsExamen);
            // return;

            const login = @json(Auth::user()->login);
            const patient_id = $('#patient_id').val();
            const typeacte_id_exd = $('#typeacte_id_exd').val();
            const medecin = $('#medecin').val();
            const rensg = $('#rensg').val();
            const numhosp = $('#numhosp').val();
            const taux = $('#patient_taux').val();
            let remise = $('#taux_remise').val();

            if (patient_id == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Patient.', "warning");
                return false;
            }

            if (typeacte_id_exd == '') {
                showAlert("ALERT", 'Veuillez sélectionner un Type d\'examen.', "warning");
                return false;
            }

            if (medecin == '') {
                showAlert("ALERT", 'Veuillez saisie le nom du médecin.', "warning");
                return false;
            }

            var montant_assurance = document.getElementById('montant_assurance_examen').value.replace(/[^0-9]/g, '');
            var montant_patient = document.getElementById('montant_patient_examen').value.replace(/[^0-9]/g, '');
            var montant_total = document.getElementById('montant_total_examen').value.replace(/[^0-9]/g, '');
            var montant_pre = document.getElementById('montant_pre_examen').value.replace(/[^0-9]/g, '');
            var numcode = document.getElementById('numcode').value;
            // Validate monetary fields
            if (!montant_assurance || 
                !montant_total || 
                !montant_patient ||
                !montant_pre) {
                
                showAlert("ALERT", 'Vérifier les montants SVP.', "warning");
                return false; 
            }

            var montantAssuranceValue = parseFloat(montant_assurance);
            var montantTotalValue = parseFloat(montant_total);
            var montantPatientValue = parseFloat(montant_patient);
            var montantPreValue = parseFloat(montant_pre);
            var taux_remise = parseFloat($('#taux_remise').val().replace(/[^0-9]/g, '')) || 0;

            if (isNaN(montantAssuranceValue) || 
                isNaN(montantTotalValue) || 
                isNaN(montantPatientValue) ||
                isNaN(montantPreValue) || 
                montantAssuranceValue < 0 || 
                montantTotalValue < 0 || 
                montantPatientValue < 0 ||
                montantPreValue < 0) {
                
                showAlert("ALERT", 'Vérifier les montants SVP (les montants ne doivent pas être négatifs).', "warning");
                return false;
            }

            if ((montantAssuranceValue + montantPatientValue + taux_remise) != montantTotalValue) {
                
                showAlert("ALERT", 'Vérifier les montants SVP.', "warning");
                return false; 
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/new_examend',
                method: 'GET',
                data:{
                    selectionsExamen: selectionsExamen,
                    montantA: montant_assurance,
                    montantT: montant_total,
                    montantP: montant_patient,
                    montant_pre: montant_pre,
                    patient_id: patient_id,
                    acte_id: typeacte_id_exd,
                    medecin: medecin,
                    numcode: numcode || null,
                    rensg: rensg || null,
                    numhosp: numhosp || null,
                    taux: taux,
                    remise: remise,
                    login: login,

                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        Statistique();

                        $('#patient_id').val(null).trigger('change.select2');
                        $('#periode').val(null).trigger('change.select2');
                        $('#typeacte_id_exd').val(null).trigger('change.select2');
                        $('#charge_prelev').val('patient').trigger('change.select2');

                        $('#medecin').val('');
                        $('#numhosp').val('');
                        $('#numcode').val('');
                        $('#rensg').val('');
                        $('#select_examen_div').hide();
                        $('#div_Examen').hide();
                        $('#div_numcode').hide();
                        $('#select_periode_div').hide();

                        table_examend.ajax.reload(null, false);   

                        showAlert("ALERT", 'Opération éffectué', "success");

                        var newTab = new bootstrap.Tab(document.getElementById('tab-oneAAAD'));
                        newTab.show();

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.json) {
                        showAlert("ERREUR", 'Invalid selections format', "error");
                    } else if (response.existe) {
                        showAlert("Alert", 'Ce numéro de prise en charge existe déjà', "warning");
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

        function Statistique() {

            const nbre_analyse = document.getElementById("nbre_analyse");
            const nbre_imagerie = document.getElementById("nbre_imagerie");

            $.ajax({
                url: $('#url').attr('content') +'/api/statistique_examen',
                method: 'GET',
                success: function(response) {
                    // Set the text content of each element
                    nbre_analyse.textContent = response.nbre_analyse_day;
                    nbre_imagerie.textContent = response.nbre_imagerie_day;
                },
                error: function() {
                    // Set default values in case of an error
                    nbre_analyse.textContent = '0';
                    nbre_imagerie.textContent = '0';
                }
            });
        }

    });
</script>


@endsection
