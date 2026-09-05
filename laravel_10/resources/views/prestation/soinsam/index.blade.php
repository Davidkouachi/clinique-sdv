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
            Soins médicaux
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'SOINS MEDICAUX',
        'subtitle' => 'Services / Soins'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body">
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
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Historique
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
<div class="row gx-3 justify-content-center align-items-center">
    {{-- Patient --}} 
    <div class="col-12">
        <div class="d-flex flex-column
                   align-items-center
                   justify-content-center
                   text-center">
            <!-- Avatar -->
            <div class="position-relative" style="margin-bottom: -40px; z-index: 2;">
                <div class="d-flex align-items-center justify-content-center
                           bg-white rounded-circle shadow-sm
                           border border-3 border-primary" style="width: 90px; height: 90px;">
                    <img src="{{ asset('assets/images/user8.png') }}" class="rounded-circle" style="
                            width: 78px;
                            height: 78px;
                            object-fit: cover;
                        " alt="Patient">
                </div>
            </div>
            <!-- Bloc sélection -->
            <div class="w-100" style="max-width: 500px;">
                <div class="bg-light rounded-3
                           border px-4 pt-5 pb-3">
                    <h6 class="fw-semibold mb-1">
                        Sélection du patient
                    </h6>
                    <p class="text-muted small mb-3">
                        Recherchez et sélectionnez le patient
                    </p>
                    <select class="form-select select2" id="patient_id"></select>
                </div>
            </div>
        </div>
    </div>      
</div>
{{-- =========================================================
INFORMATIONS PATIENT
Affiché après sélection du patient
========================================================== --}}
<div class="row gx-3 justify-content-center mb-4" id="div_info_patient" style="display: none;"></div>
{{-- =========================================================
PERIODE
========================================================== --}}
<div id="select_periode_div" style="display: none;" class="row gx-3 justify-content-center align-items-center">
    <div class="col-12">
        <div class="mb-4">
            <div class="card-header py-2">

                <div class="d-flex align-items-center border-top pt-3">

                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-center
                            bg-primary
                            text-white
                            rounded-circle
                            me-3
                        "
                        style="
                            width:40px;
                            height:40px;
                        "
                    >
                        <i class="ri-stethoscope-line fs-5"></i>
                    </div>

                    <div>

                        <h6 class="mb-1 fw-semibold">
                            Information du patient
                        </h6>

                        <p class="text-muted mb-0 small">
                            Informations générales du dossier
                        </p>

                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="row gx-3 gy-2">
                    {{-- Médecin --}}
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Médecin
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    Dr
                                </span>
                                <input type="text" class="form-control" id="medecin" autocomplete="off" placeholder="Saisie obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                    </div>
                    {{-- N° prise en charge --}}
                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">
                                N° prise en charge
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    N°
                                </span>
                                <input type="text" class="form-control" id="numcode" autocomplete="off" placeholder="Facultatif">
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">
                                N° hospitalisation
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    N°
                                </span>
                                <input type="text" class="form-control" id="numhosp" autocomplete="off" placeholder="Facultatif">
                            </div>
                        </div>
                    </div>
                    {{-- Renseignement clinique --}}
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Renseignement clinique
                            </label>
                            <input type="text" class="form-control" id="rensg" autocomplete="off" placeholder="Facultatif" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- =========================================================
INFORMATIONS DE LA DEMANDE
========================================================== --}}
<div id="select_soins_div" style="display: none;">
    <div class="row gx-3 justify-content-center align-items-center">
        <div class="col-12">
            <div class="mt-2">
                <div class="card border">
                    <div class="card-header">
                        <h5 class="card-title text-center mb-1">
                            <i class="ri-capsule-line me-1"></i>
                            Soins
                        </h5>
                        <p class="text-muted text-center small mb-0">
                            Soins à réaliser
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row gx-3" id="contenu_soins">
                        </div>
                        <div class="row gx-3 justify-content-center">
                            <div class="col-12 mb-3 text-center">
                                <button type="button" id="add_select_soins" class="btn btn-outline-info">
                                    <i class="ri-add-line"></i>
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="card border">
                    <div class="card-header">
                        <h5 class="card-title text-center mb-1">
                            <i class="ri-capsule-line me-1"></i>
                            Produits
                        </h5>
                        <p class="text-muted text-center small mb-0">
                            Produits / Médicaments utiliser
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row gx-3" id="contenu_produit">
                        </div>
                        <div class="row gx-3 justify-content-center">
                            <div class="col-12 mb-3 text-center">
                                <button type="button" id="add_select_produit" class="btn btn-outline-info">
                                    <i class="ri-add-line"></i>
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =================================================
            TOTALS
            ================================================== --}}
            <div class="row gx-3 mt-3">                
                {{-- Taux --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Taux
                        </span>
                        <input readonly type="tel" class="form-control" id="patient_taux" value="0" placeholder="Taux de couverture">
                        <span class="input-group-text w-25">
                            %
                        </span>
                    </div>
                </div>
                {{-- Total --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Total
                        </span>
                        <input readonly type="tel" class="form-control" id="montant_total" value="0" placeholder="Montant Total">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- Total soins --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Total Soins
                        </span>
                        <input readonly type="tel" class="form-control" id="montant_total_soins" value="0" placeholder="Montant Total">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- Total produit --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Total Produit
                        </span>
                        <input readonly type="tel" class="form-control" id="montant_total_produit" value="0" placeholder="Montant Total">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- Assurance --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Assurance
                        </span>
                        <input readonly type="tel" class="form-control" id="montant_assurance_soins" value="0" placeholder="Part Assurance">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- Patient --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Patient
                        </span>
                        <input readonly type="tel" class="form-control" id="montant_patient_soins" value="0" placeholder="Part Patient">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- Remise --}}
                <div class="col-xxl-5 col-lg-6 col-sm-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text w-25">
                            Remise
                        </span>
                        <input type="tel" class="form-control" id="taux_remise" value="0" placeholder="Remise">
                        <span class="input-group-text w-25">
                            Fcfa
                        </span>
                    </div>
                </div>
                {{-- =================================================
                BOUTONS
                ================================================== --}}
                <div class="col-12 mb-3 text-center"> 
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
                            <div class="tab-pane fade " id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des soins médicaux
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="soinsTable" ></div>
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

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/soins.js')}}"></script>
@include('select2')
<script src="{{asset('assets/app/js/module/actes/soins.js')}}"></script>

@endsection
