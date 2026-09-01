@extends('app')

@section('titre', 'Garantie & Tarifs')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Garantie & Tarifs
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'GARANTIES & TARIFS',
        'subtitle' => 'Configurations / Garanties & Tarifs'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoG" data-bs-toggle="tab" href="#twoG" role="tab" aria-controls="twoG" aria-selected="false" tabindex="-1">
                                    <i class="ri-home-7-line me-2"></i>
                                    Garanties
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoT" data-bs-toggle="tab" href="#twoT" role="tab" aria-controls="twoT" aria-selected="false" tabindex="-1">
                                    <i class="ri-home-7-line me-2"></i>
                                    Tarifs
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoG" role="tabpanel" aria-labelledby="tab-twoG">
                                <div class="custom-tabs-container">
                                    <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="tab-two1" data-bs-toggle="tab" href="#two1" role="tab" aria-controls="two1" aria-selected="false" tabindex="-1">
                                                <i class="ri-home-7-line me-2"></i>
                                                Nouveau Type de Garantie
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-two3" data-bs-toggle="tab" href="#two3" role="tab" aria-controls="two3" aria-selected="false" tabindex="-1">
                                                <i class="ri-home-7-line me-2"></i>
                                                Nouvelle Garantie
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-two2" data-bs-toggle="tab" href="#two2" role="tab" aria-controls="two2" aria-selected="false" tabindex="-1">
                                                <i class="ri-building-4-line me-2"></i>
                                                Liste des Types Garanties
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-two5" data-bs-toggle="tab" href="#two5" role="tab" aria-controls="two5" aria-selected="false" tabindex="-1">
                                                <i class="ri-building-4-line me-2"></i>
                                                Liste des Garanties
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="customTabContent">
                                        <div class="tab-pane active show fade" id="two1" role="tabpanel" aria-labelledby="tab-two1">
                                            <div class="card-header">
                                                <h5 class="card-title text-center">Formulaire Type Garantie</h5>
                                            </div>
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/type_garantie.jpg')}}" class="img-7x rounded-circle border border-3">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body" >
                                                <div class="row gx-3 alig-items-center justify-content-center">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Type</label>
                                                            <input type="text" class="form-control" id="nom_typegarantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Code</label>
                                                            <input type="text" class="form-control" id="code_typegarantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="10">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-3 ">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button id="btn_eng_typegarantie" class="btn btn-outline-success">
                                                                Enregistrer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="two3" role="tabpanel" aria-labelledby="tab-two3">
                                            <div class="card-header">
                                                <h5 class="card-title text-center">Formulaire Garantie</h5>
                                            </div>
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/type_garantie.jpg')}}" class="img-7x rounded-circle border border-3">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body" >
                                                <div class="row gx-3 alig-items-center justify-content-center">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Garantie</label>
                                                            <input type="text" class="form-control" id="nom_garantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="80">
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Code</label>
                                                            <input type="text" class="form-control" id="code_garantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="10">
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Type Garantie</label>
                                                            <select class="form-select select2" id="codtypgar_garantie">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_type_examen" style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label">Type d'examen</label>
                                                            <select class="form-select select2" id="type_examen">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_cotation" style="display: none;">
                                                        <div class="mb-3">
                                                            <label class="form-label">Cotation</label>
                                                            <input type="text" class="form-control" id="cotation" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="3">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-3 ">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button id="btn_eng_garantie" class="btn btn-outline-success">
                                                                Enregistrer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="two2" role="tabpanel" aria-labelledby="tab-two2">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Types garanties
                                                </h5>
                                                <div class="d-flex">
                                                    <a id="btn_refresh_table_typegarantie" class="btn btn-outline-info ms-auto">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_typegarantie">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Nom</th>
                                                                    <th scope="col">Code</th>
                                                                    <th scope="col">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="two5" role="tabpanel" aria-labelledby="tab-two5">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Garanties
                                                </h5>
                                                <div class="d-flex">
                                                    <a id="btn_refresh_table_garantie" class="btn btn-outline-info ms-auto">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_garantie">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Garantie</th>
                                                                    <th scope="col">Code</th>
                                                                    <th scope="col">Type</th>
                                                                    <th scope="col">Actions</th>
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
                            <div class="tab-pane fade" id="twoT" role="tabpanel" aria-labelledby="tab-twoT">
                                <div class="custom-tabs-container">
                                    <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="tab-two4" data-bs-toggle="tab" href="#two4" role="tab" aria-controls="two4" aria-selected="false" tabindex="-1">
                                                <i class="ri-home-7-line me-2"></i>
                                                Nouveau Tarif
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="tab-two6" data-bs-toggle="tab" href="#two6" role="tab" aria-controls="two6" aria-selected="false" tabindex="-1">
                                                <i class="ri-building-4-line me-2"></i>
                                                Liste des Tarifs
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="customTabContent">
                                        <div class="tab-pane active show fade" id="two4" role="tabpanel" aria-labelledby="tab-two4">
                                            <div class="card-header">
                                                <h5 class="card-title text-center">Formulaire Tarif</h5>
                                            </div>
                                            <div class="card-header">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/tarif.png')}}" class="img-7x rounded-circle border border-3">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body" >
                                                <div class="row gx-3 alig-items-center justify-content-center">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Garantie</label>
                                                            <select class="form-select select2" id="codgaran">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 alig-items-center justify-content-center" id="div_assurer" style="display: none;">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Assurer ?</label>
                                                            <select class="form-select select2" id="assurer">
                                                                <option value=""></option>
                                                                <option value="1">Oui</option>
                                                                <option value="0">Non</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 alig-items-center justify-content-center" id="div_assurance" style="display: none;">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Assurance</label>
                                                            <select class="form-select select2" id="codeassurance">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3 alig-items-center justify-content-center" id="div_prix" style="display: none;">
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Prix Jour</label>
                                                            <div class="input-group">
                                                                <input type="tel" class="form-control" id="prixj" placeholder="Saisie Obligatoire">
                                                                <span class="input-group-text">Fcfa</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Prix Nuit</label>
                                                            <div class="input-group">
                                                                <input type="tel" class="form-control" id="prixn" placeholder="Saisie Obligatoire">
                                                                <span class="input-group-text">Fcfa</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Prix Férier</label>
                                                            <div class="input-group">
                                                                <input type="tel" class="form-control" id="prixf" placeholder="Saisie Obligatoire">
                                                                <span class="input-group-text">Fcfa</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mb-3 ">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <button id="btn_eng_tarif" class="btn btn-outline-success">
                                                                Enregistrer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="two6" role="tabpanel" aria-labelledby="tab-two6">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Tarifs
                                                </h5>
                                                <div class="d-flex">
                                                    <a id="btn_refresh_table_tarif" class="btn btn-outline-info ms-auto">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="">
                                                    <div class="table-responsive">
                                                        <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_tarif">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">N°</th>
                                                                    <th scope="col">Garantie</th>
                                                                    <th scope="col">Assurer ?</th>
                                                                    <th scope="col">Assurance</th>
                                                                    <th scope="col">Montant Jour</th>
                                                                    <th scope="col">Montant Nuit</th>
                                                                    <th scope="col">Montant Férier</th>
                                                                    <th scope="col">Actions</th>
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

<div class="modal fade" id="Mmodif_typegarantie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id_typegarantie">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" class="form-control" id="nomModif_typegarantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn_typegarantie">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif_garantie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id_garantie">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Garantie</label>
                            <input type="text" class="form-control" id="nomModif_garantie" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn_garantie">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif_tarif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id_tarif">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Prix Jour</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="prixjModif" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Prix Nuit</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="prixnModif" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Prix Férier</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="prixfModif" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn_tarif">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mdelete_tarif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé ce tarif ?
                <input type="hidden" id="Id_delete_tarif">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtn_tarif" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        var select = ['#codeassuranceModif', '#codeassurance', '#assureur_idModif', '#assureur_id'];
        select.forEach(function(id) {
            $(id).select2({
                theme: 'bootstrap',
                placeholder: 'Selectionner',
                language: {
                    noResults: function() {
                        return "Aucun résultat trouvé";
                    }
                },
                width: '100%',
                dropdownParent: $('#Mmodif'),
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        select_typegarantie();
        select_assurance();
        select_garantie();
        select_type_examen();

        $("#btn_eng_typegarantie").on("click", eng_typegarantie);
        $("#updateBtn_typegarantie").on("click", updatee_typegarantie);

        $("#btn_eng_garantie").on("click", eng_garantie);
        $("#updateBtn_garantie").on("click", updatee_garantie);

        $("#btn_eng_tarif").on("click", eng_tarif);
        $("#updateBtn_tarif").on("click", updatee_tarif);
        $("#deleteBtn_tarif").on("click", deletee_tarif);

        var inputs = ['#prixj', '#prixn', '#prixf','#prixjModif', '#prixnModif', '#prixfModif','#cotation'];
        inputs.forEach(function (id) {
            var inputElement = $(id); // Sélectionner l'élément avec jQuery

            // Écoute de l'événement "keypress" pour autoriser uniquement les chiffres
            inputElement.on('keypress', function (event) {
                const key = event.key;

                // Autoriser uniquement les chiffres, la touche retour arrière et supprimer
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });

            // Écoute de l'événement "input" pour formater la valeur
            inputElement.on('input', function () {
                this.value = formatPrice(this.value.replace(/[^0-9]/g, ''));
            });
        });

        $('#codgaran').on('change', function() {

            if (this.value == null) {
                $('#div_assurer').hide();
            } else {
                $('#div_assurer').show();
            }
        });

        $('#assurer').on('change', function() {

            $('#codeassurance').val('').trigger('change');
            $('#prixj').val('');
            $('#prixn').val('');
            $('#prixf').val('');
            $('#div_prix').show();

            if (this.value == 0) {
                $('#div_assurance').hide();
            } else if (this.value == 1) {
                $('#div_assurance').show();
            }
        });

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatPrice(price) 
        {

            let number = Math.round(parseFloat(price));
            if (isNaN(number)) {
                return '';
            }

            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) 
        {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatDate(dateString) 
        {

            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = date.getFullYear();

            return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
        }

        function formatDateHeure(dateString) 
        {

            const date = new Date(dateString);
                
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
        }

        // -----------------------------------------------------------

        function select_typegarantie() 
        {
            console.log('bceh');
            const selectElement2 = $('#codtypgar_garantie');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/select_typegarantie',
                method: 'GET',
                success: function(response) {
                    const data = response.type;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codtypgar,
                            text: item.libtypgar,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });

            selectElement2.on('change', function() {

                $('#type_examen').val('').trigger('change');
                $('#cotation').val('');

                $('#div_type_examen').hide();
                $('#div_cotation').hide();

                if (this.value == 'EXAM') {
                    $('#div_type_examen').show();
                }
            });

        }

        function select_type_examen() 
        {
            const selectElement2 = $('#type_examen');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/select_type_examen',
                method: 'GET',
                success: function(response) {
                    const data = response.type;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codfamexam,
                            text: item.nomfamexam,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });

            selectElement2.on('change', function() {

                $('#div_cotation').hide();
                $('#cotation').val('');

                if (this.value == 'Z' || this.value == 'B') {
                    $('#div_cotation').show();
                }
            });

        }

        const table_typegarantie = $('.Table_typegarantie').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_type_garantie`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'libtypgar', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/type_garantie.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'codtypgar',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#Mmodif_typegarantie" id="modif"
                                data-code="${row.codtypgar}"
                                data-nom="${row.libtypgar}"
                            >
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners_typegarantie();
            },
        });

        function initializeRowEventListeners_typegarantie() {

            $('.Table_typegarantie').on('click', '#modif', function() {
                const id = $(this).data('code');
                const nom = $(this).data('nom');

                $('#Id_typegarantie').val(id);
                $('#nomModif_typegarantie').val(nom);
            });
        }

        $('#btn_refresh_table_typegarantie').on('click', function () {
            table_typegarantie.ajax.reload(null, false);
        });

        function eng_typegarantie() 
        {
            const code = $("#code_typegarantie");
            const nom = $("#nom_typegarantie");

            if (!nom.val().trim() || !code.val().trim()) {
                showAlert('Alert', 'Veuillez saisir un code et un type de garantie.', 'warning');
                return false;
            }

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/type_garantie_new',
                method: 'GET',
                data: {
                    nom: nom.val(),
                    code: code.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', response.message , 'warning');
                    } else if (response.success) {

                        nom.val('');
                        code.val('');
                        table_typegarantie.ajax.reload(null, false);
                        select_typegarantie();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function updatee_typegarantie() 
        {
            const code = $('#Id_typegarantie').val();
            const nom = $("#nomModif_typegarantie");

            if (!nom.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif_typegarantie'));
            modal.hide();

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/update_type_garantie/'+code,
                method: 'GET',
                data: {
                    nom: nom.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', response.message , 'warning');
                    } else if (response.success) {

                        table_typegarantie.ajax.reload(null, false);
                        showAlert('Succès', response.message , 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        // -----------------------------------------------------------

        const table_garantie = $('.Table_garantie').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_garantie`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'libgaran', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/type_garantie.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'codgaran',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'type_garantie',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#Mmodif_garantie" id="modif"
                                data-code="${row.codgaran}"
                                data-nom="${row.libgaran}"
                            >
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners_garantie();
            },
        });

        function initializeRowEventListeners_garantie() {

            $('.Table_garantie').on('click', '#modif', function() {
                const id = $(this).data('code');
                const nom = $(this).data('nom');

                $('#Id_garantie').val(id);
                $('#nomModif_garantie').val(nom);
            });
        }

        $('#btn_refresh_table_garantie').on('click', function () {
            table_garantie.ajax.reload(null, false);
        });

        function eng_garantie() 
        {
            const code = $("#code_garantie");
            const nom = $("#nom_garantie");
            const code_type = $("#codtypgar_garantie");

            const type_examen = $("#type_examen");
            const cotation = $("#cotation");

            if (!nom.val().trim() || !code.val().trim() || !code_type.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs.', 'warning');
                return false;
            }

            if (code_type.val() == 'EXAM') {

                if (!type_examen.val().trim()) {
                    showAlert('Alert', 'Veuillez remplir tous les champs.', 'warning');
                    return false;
                }

                if (type_examen.val() == 'Z' || type_examen.val() == 'B') {

                    if (!cotation.val().trim()) {
                        showAlert('Alert', 'Veuillez remplir tous les champs.', 'warning');
                        return false;
                    }
                }
            }

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/garantie_new',
                method: 'GET',
                data: {
                    nom: nom.val(),
                    code: code.val(),
                    code_type: code_type.val(),
                    type_examen: type_examen.val(),
                    cotation: cotation.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', response.message , 'warning');
                    } else if (response.success) {

                        $('#div_type_examen').hide();
                        $('#div_cotation').hide();

                        nom.val('');
                        code.val('');
                        code_type.val('').trigger('change');

                        type_examen.val('').trigger('change');
                        cotation.val('');

                        table_garantie.ajax.reload(null, false);
                        select_garantie();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        function updatee_garantie() 
        {
            const code = $('#Id_garantie').val();
            const nom = $("#nomModif_garantie");

            if (!nom.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif_garantie'));
            modal.hide();

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/update_garantie/'+code,
                method: 'GET',
                data: {
                    nom: nom.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', response.message , 'warning');
                    } else if (response.success) {

                        table_garantie.ajax.reload(null, false);
                        showAlert('Succès', response.message , 'success');
                    } else if (response.error) {
                        showAlert('Erreur', reponse.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        // -----------------------------------------------------------

        function select_assurance() 
        {
            const selectElement2 = $('#codeassurance');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/assurance_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.assurance;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_garantie() 
        {
            const selectElement2 = $('#codgaran');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/select_garantie',
                method: 'GET',
                success: function(response) {
                    const data = response.garantie;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codgaran,
                            text: item.libgaran,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        const table_tarif = $('.Table_tarif').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_tarif`,
                type: 'GET',
                dataSrc: 'data',
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'garantie', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/tarif.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <span class="badge ${row.codeassurance === 'NONAS' ? 'bg-danger' : 'bg-success'}">
                            ${row.codeassurance === 'NONAS' ? `Non-assurer` : 'Assurer'}
                        </span>
                    `,
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => {
                        return row.codeassurance == 'NONAS' ? `Néant` : `${row.asurance}`;
                    },
                    searchable: true,
                },
                {
                    data: 'montjour',
                    render: (data, type, row) => {
                        return  `${formatPrice(data)} Fcfa` ;
                    },
                    searchable: true,
                },
                {
                    data: 'montnuit',
                    render: (data, type, row) => {
                        return  `${formatPrice(data)} Fcfa` ;
                    },
                    searchable: true,
                },
                {
                    data: 'montferie',
                    render: (data, type, row) => {
                        return  `${formatPrice(data)} Fcfa` ;
                    },
                    searchable: true,
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#Mmodif_tarif" id="modif"
                                data-id="${row.idtarif}"
                                data-prixj="${row.montjour}"
                                data-prixn="${row.montnuit}"
                                data-prixf="${row.montferie}"
                            >
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners_tarif();
            },
        });

        function initializeRowEventListeners_tarif() {

            $('.Table_tarif').on('click', '#modif', function() {
                const id = $(this).data('id');
                const prixj = $(this).data('prixj');
                const prixn = $(this).data('prixn');
                const prixf = $(this).data('prixf');

                $('#Id_tarif').val(id);
                $('#prixjModif').val(formatPrice(prixj));
                $('#prixnModif').val(formatPrice(prixn));
                $('#prixfModif').val(formatPrice(prixf));
            });

            $('.Table_tarif').on('click', '#delete', function() {
                const id = $(this).data('id');

                $('#Id_delete_tarif').val(id);
            });
        }

        $('#btn_refresh_table_tarif').on('click', function () {
            table_tarif.ajax.reload(null, false);
        });

        function eng_tarif() 
        {
            const garantie = $("#codgaran");
            const assurer = $("#assurer");
            const assurance = $("#codeassurance");
            const prixj = $("#prixj");
            const prixn = $("#prixn");
            const prixf = $("#prixf");

            if (!garantie.val().trim() || !assurer.val().trim() || !prixf.val().trim() || !prixj.val().trim() || !prixn.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs.', 'warning');
                return false;
            }

            if (assurer.val().trim() == 1 && !assurance.val().trim()) {
                showAlert('Alert', 'Veuillez selectionner une assurance.', 'warning');
                return false;
            }

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/tarif_new',
                method: 'GET',
                data: {
                    garantie: garantie.val(),
                    assurer: assurer.val(),
                    assurance: assurance.val(),
                    prixj: prixj.val(),
                    prixn: prixn.val(),
                    prixf: prixf.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', response.message , 'warning');
                    } else if (response.success) {

                        garantie.val('').trigger('change');
                        assurer.val('').trigger('change');
                        assurance.val('').trigger('change');
                        prixj.val('');
                        prixn.val('');
                        prixf.val('');

                        $("#div_assurer").hide();
                        $("#div_assurance").hide();
                        $("#div_prix").hide();

                        table_tarif.ajax.reload(null, false);
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        function updatee_tarif() 
        {
            const id = $('#Id_tarif').val();
            const prixj = $("#prixjModif");
            const prixn = $("#prixnModif");
            const prixf = $("#prixfModif");

            if (!prixj.val().trim() || !prixn.val().trim() || !prixf.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif_tarif'));
            modal.hide();

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/update_tarif/'+id,
                method: 'GET',
                data: {
                    prixj: prixj.val(),
                    prixn: prixn.val(),
                    prixf: prixf.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.success) {

                        table_tarif.ajax.reload(null, false);
                        showAlert('Succès', response.message , 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        function deletee_tarif() {

            const id = document.getElementById('Id_delete_tarif').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete_tarif'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_tarif/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        table_tarif.ajax.reload(null, false);
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlertList('Erreur', 'Erreur lors de la suppression.','error');
                }
            });
        }

    });
</script>

@endsection
