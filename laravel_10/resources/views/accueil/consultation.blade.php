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
            Consultations
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'CONSULTATIONS',
        'subtitle' => 'Services / Consultations'
    ])
    <div class="row g-3 mt-1">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -32px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-create" data-bs-toggle="tab" href="#create" role="tab" aria-controls="create" aria-selected="false" tabindex="-1">
                                    <i class="ri-first-aid-kit-line me-2"></i>
                                    Nouvelle consultation
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-list" data-bs-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="false" tabindex="-1">
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Historique
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="create" role="tabpanel" aria-labelledby="tab-create">
                                <div class="row gx-3">
                                    <!-- Zone sélection patient -->
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
                                                    <select class="form-select select2" id="id_patient"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Informations du patient -->
                                    <div class="col-sm-12 mt-4" id="div_info_patient">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="list" role="tabpanel" aria-labelledby="tab-list">
                                <div class="card-body">
                                    <div id="consultationTable"></div>
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
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/consultation.js')}}"></script>
@include('select2')
<script src="{{asset('assets/app/js/module/actes/consultation.js')}}"></script>

@endsection
