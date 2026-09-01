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
            Opération de Caisse
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-3" id="contenu_caisse"></div>
        </div>
    </div>

    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tabHistoriqueLink" data-bs-toggle="tab" href="#historique" role="tab" aria-controls="historique" aria-selected="false" tabindex="-1">
                                    <i class="ri-upload-cloud-line me-2"></i>
                                    Historique Caisse
                                </a>
                            </li>
                            <li class="nav-item" role="presentation" id="tabOperation" style="display: none;">
                                <a class="nav-link" id="tabOperationLink" data-bs-toggle="tab" href="#operation" role="tab" aria-controls="operation" aria-selected="false" tabindex="-1">
                                    <i class="ri-swap-3-line me-2"></i>
                                    Nouvelle Opération
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="historique" role="tabpanel" aria-labelledby="tabHistoriqueLink">
                                <div class="card-body" style="margin-top: -30px;">
                                    <div class="custom-tabs-container">
                                        <ul class="nav nav-tabs justify-content-left " id="customTab4" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="tab-twoA1" data-bs-toggle="tab" href="#twoA1" role="tab" aria-controls="twoA1" aria-selected="false" tabindex="-1">
                                                    <i class="ri-swap-3-line me-2"></i>
                                                    Opérations de Caisse
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="tab-twoA2" data-bs-toggle="tab" href="#twoA2" role="tab" aria-controls="twoA2" aria-selected="false" tabindex="-1">
                                                    <i class="ri-swap-3-line me-2"></i>
                                                    Ouvertures et Fermetures de Caisse
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="customTabContent">
                                            <div class="tab-pane active show fade" id="twoA1" role="tabpanel" aria-labelledby="tab-twoA1">
                                                <div class="card-header">
                                                    <h5 class="card-title">Liste des Opérations de Caisse</h5>
                                                </div>
                                                <div class="card-header">
                                                    <div class="row gx-3 mb-3">
                                                        <div class="col-12">
                                                            <div class=" mb-3">
                                                                <div class="card-body">
                                                                    <div class="row gx-3">
                                                                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Du</label>
                                                                                <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-xxl-6 col-lg-6 col-md-6 col-sm-6">
                                                                            <div class="mb-3">
                                                                                <label class="form-label">Au</label>
                                                                                <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12 text-center" >
                                                                            <a id="btn_search_trace" class="btn btn-outline-success ms-auto">
                                                                                <i class="ri-search-2-line"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12" >
                                                            <div id="stat_bord_total" class="card-header">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body mb-3">
                                                    <div class="">
                                                        <div class="table-responsive">
                                                            <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_OpC">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">N°</th>
                                                                        <th scope="col">Créer par</th>
                                                                        <th scope="col">Motif</th>
                                                                        <th scope="col">Type de mouvement</th>
                                                                        <th scope="col">Montant</th>
                                                                        <th scope="col">Date d'opération</th>
                                                                        <th scope="col">Date de création</th>
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
                                            <div class="tab-pane fade" id="twoA2" role="tabpanel" aria-labelledby="tab-twoA2">
                                                <div class="card-header">
                                                    <h5 class="card-title">Liste des ouvertures et fermetures de Caisse</h5>
                                                </div>
                                                <div class="card-header d-flex align-items-center justify-content-between">
                                                    <div class="w-100">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Du</span>
                                                            <input type="date" id="searchDate1_ofc" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                                                            <span class="input-group-text">au</span>
                                                            <input type="date" id="searchDate2_ofc" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                                            <a id="btn_search_trace_ofc" class="btn btn-outline-success ms-auto">
                                                                <i class="ri-search-2-line"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body mb-3">
                                                    <div class="">
                                                        <div class="table-responsive">
                                                            <table id="Table_day" class="table table-hover table-sm Table_Ofc">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">N°</th>
                                                                        <th scope="col">Motif</th>
                                                                        <th scope="col">Solde Caisse</th>
                                                                        <th scope="col">Auteur</th>
                                                                        <th scope="col">Date de création</th>
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
                            <div class="tab-pane fade" id="operation" role="tabpanel" aria-labelledby="tabOperationLink">
                                <div class="card-header">
                                    <h5 class="card-title">Formulaire Nouvelle Opération</h5>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type d'opération</label>
                                                <select class="form-select" id="type_ope">
                                                    <option value="">Selectionner</option>
                                                    <option value="entree">Entrer d'argent</option>
                                                    <option value="sortie">Sortie d'argent</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Montant</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="montant_ope" placeholder="Saisie Obligatoire">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Bénéficiaire
                                                </label>
                                                <input type="text" class="form-control" placeholder="Facultatif" id="bene_ope" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Date de l'opération
                                                </label>
                                                <input type="date" class="form-control" id="date_ope" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class=" mb-3">
                                                <label class="form-label" for="abc6">Motif</label>
                                                <textarea style="resize: none;" class="form-control" id="libelle_ope" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-3">
                                            <div class="d-flex gap-2 justify-content-start">
                                                <button id="btn_eng_ope" class="btn btn-success">
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

<div class="modal fade" id="Detail" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-body" id="modal_detail"></div>
    </div>
</div>

<script src="{{asset('assets/js/app/js/jspdfinvoicetemplate/dist/index.js')}}" ></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
@include('select2')
<script src="{{asset('assets/app/js/module/comptabilite/operation.js')}}"></script>

@endsection
