@extends('app')

@section('titre', 'Nouveau Produit')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Encaissement
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

    <div id="div_caisse" style="display: none;" >
        <div class="row gx-3" >
            <div class="col-sm-12">
                <div class="card mb-3 mt-3">
                    <div class="card-body" style="margin-top: -30px;">
                        <div class="custom-tabs-container">
                            <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="tab-twoA1" data-bs-toggle="tab" href="#twoA1" role="tab" aria-controls="twoA1" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Consulation(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-twoA2" data-bs-toggle="tab" href="#twoA2" role="tab" aria-controls="twoA2" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Examen(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-twoA3" data-bs-toggle="tab" href="#twoA3" role="tab" aria-controls="twoA3" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Hospitalisation(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-twoA4" data-bs-toggle="tab" href="#twoA4" role="tab" aria-controls="twoA4" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Soins Ambulatoire(s)
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-twoA5" data-bs-toggle="tab" href="#twoA5" role="tab" aria-controls="twoA5" aria-selected="false" tabindex="-1">
                                        <i class="ri-article-line me-2"></i>
                                        Attribution Remise
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="customTabContent">
                                <div class="tab-pane active show fade" id="twoA1" role="tabpanel" aria-labelledby="tab-twoA1">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Consultation(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Cons" class="btn btn-outline-warning ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Cons">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">N° facture</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Part Patient</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Total</th>
                                                            <th scope="col">Reste à payer</th>
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
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Examen(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Exam" class="btn btn-outline-warning ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Exam">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">N° facture</th>
                                                            <th scope="col">Type d'examen</th>
                                                            <th scope="col">Prélevement</th>
                                                            <th scope="col">Montant Examen</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Montant a payer</th>
                                                            <th scope="col">Reste à payer</th>
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
                                <div class="tab-pane fade" id="twoA3" role="tabpanel" aria-labelledby="tab-twoA3">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Hospitalisation(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Hos" class="btn btn-outline-warning ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Hos">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">Id facture</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Montant à payer</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Reste à payer</th>
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
                                <div class="tab-pane fade" id="twoA4" role="tabpanel" aria-labelledby="tab-twoA4">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h5 class="card-title">
                                            Soins Amulatoire(s)
                                        </h5>
                                        <div class="d-flex" >
                                            <a id="btn_refresh_table_Soinsam" class="btn btn-outline-warning ms-auto">
                                                <i class="ri-loop-left-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_Soinsam">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">N°</th>
                                                            <th scope="col">N° facture</th>
                                                            <th scope="col">Montant Total</th>
                                                            <th scope="col">Montant Produit</th>
                                                            <th scope="col">Montant Soins</th>
                                                            <th scope="col">Remise</th>
                                                            <th scope="col">Montant a payer</th>
                                                            <th scope="col">Part Assurance</th>
                                                            <th scope="col">Reste à payer</th>
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
                                <div class="tab-pane fade" id="twoA5" role="tabpanel" aria-labelledby="tab-twoA5">
                                    <div class="card-header d-flex align-items-center justify-content-center">
                                        <h5 class="card-title">
                                            Attribution Remise
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Acte</label>
                                                    <select class="form-select select2" id="acte_remise">
                                                        <option value="">Selectionner</option>
                                                        <option value="cons">Consultations</option>
                                                        <option value="exam">Examens</option>
                                                        <option value="soins">Soins Ambulatoires</option>
                                                        <option value="hosp">Hospitalisations</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">N° Facture</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">N°</span>
                                                        <input type="text" class="form-control" id="numfac_remise" autocomplete="off" placeholder="Saisie Obligatoire">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Remise</label>
                                                    <div class="input-group">
                                                        <input type="tel" class="form-control" id="montant_remise" value="0">
                                                        <span class="input-group-text">Fcfa</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-5 text-center">
                                                <button type="button" id="btn_eng_remise" class="btn btn-success">
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Caisse" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Caisse
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">A payer</label>
                            <div class="input-group">
                                <input readonly class="form-control" id="input_montant_payer">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant versé</label>
                            <div class="input-group">
                                <input type="tel" class="form-control" id="input_montant_verser" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant Remis</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_remis" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Reste à payer</label>
                            <div class="input-group">
                                <input readonly type="tel" class="form-control" id="input_montant_restant" placeholder="Saisie Obligatoire">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check d-flex align-items-center gap-2 p-3 bg-light rounded-3 border">
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="voir_recu"
                                   style="margin-left: 0;">
                            <label class="form-check-label fw-semibold"
                                   for="voir_recu">
                                Voir le reçu après l'enregistrement

                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="div_btn_valider">
                <input type="hidden" id="id_code_fac">
                <input type="hidden" id="id">
                <input type="hidden" id="matricule">
                <button data-bs-dismiss="modal" class="btn btn-success" id="btn_valider" >
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/consultation.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/examen.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/hospitalisation.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/soins.js')}}"></script>
<script src="{{asset('assets/app/js/module/caisse/encaissement.js')}}"></script>

@endsection