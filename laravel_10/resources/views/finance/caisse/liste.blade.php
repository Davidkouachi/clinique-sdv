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
            Listes des factures
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'FACTURES',
        'subtitle' => 'Caisse / Liste des Factures'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -20px;">
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
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            {{-- <div class="alert bg-warning text-white alert-dismissible d-flex align-items-center fade show fade-in-out" role="alert">
                                <i class="ri-alert-line fs-3 me-2 lh-1"></i>
                                <div>                                
                                    <h6>ATTENTION : </h6> 
                                    L'expression << Réimprimer recu >>, fait référence a l'impression du dernier recu de paiement de la facture de l'acte.
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div> --}}
                            <div class="card-header" style="margin-top: -20px;margin-bottom: -40px;">
                                <div class="row gx-3">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane active show fade" id="twoA1" role="tabpanel" aria-labelledby="tab-twoA1">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Consultation(s)
                                    </h5>
                                    <div class="d-flex" >
                                        <a id="btn_search_Cons" class="btn btn-outline-success ms-auto me-2">
                                            <i class="ri-search-2-line"></i>
                                        </a>
                                        <a id="btn_refresh_table_Cons" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="Table_Cons">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">N° facture</th>
                                                        <th scope="col">Facture Réglé ?</th>
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
                                        <a id="btn_search_Exam" class="btn btn-outline-success ms-auto me-2">
                                            <i class="ri-search-2-line"></i>
                                        </a>
                                        <a id="btn_refresh_table_Exam" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="Table_Exam">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">N° facture</th>
                                                        <th scope="col">Facture réglé ?</th>
                                                        <th scope="col">Montant Total</th>
                                                        <th scope="col">Montant Examen</th>
                                                        <th scope="col">Part Assurance</th>
                                                        <th scope="col">Part Patient</th>
                                                        <th scope="col">Prélevement</th>
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
                                        <a id="btn_search_Hos" class="btn btn-outline-success ms-auto me-2">
                                            <i class="ri-search-2-line"></i>
                                        </a>
                                        <a id="btn_refresh_table_Hos" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="Table_Hos">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Id facture</th>
                                                        <th scope="col">Fcatue regle ?</th>
                                                        <th scope="col">Montant Total</th>
                                                        <th scope="col">Part Assurance</th>
                                                        <th scope="col">Montant a payer</th>
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
                                        <a id="btn_search_Soinsam" class="btn btn-outline-success ms-auto me-2">
                                            <i class="ri-search-2-line"></i>
                                        </a>
                                        <a id="btn_refresh_table_Soinsam" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="Table_Soinsam">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Id facture</th>
                                                        <th scope="col">Fcatue regle ?</th>
                                                        <th scope="col">Montant Total</th>
                                                        <th scope="col">Montant Produit</th>
                                                        <th scope="col">Montant Soins</th>
                                                        <th scope="col">Remise</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_recu" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Recus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="TableRecu">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>N° recu</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th></th>
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

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/consultation.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/examen.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/hospitalisation.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/soins.js')}}"></script>
<script src="{{asset('assets/app/js/module/caisse/listFacture.js')}}"></script>

@endsection