@extends('app')

@section('titre', 'Acceuil')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a class="d-sm-flex d-none" href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
    </ol>
    <div class="ms-auto flex-row">
        <div class="d-flex flex-row gap-1 day-sorting">

            <button
                type="button"
                class="btn btn-sm btn-primary"
                id="btn_toggle_stat"
            >
                <i class="ri-bar-chart-line me-1"></i>
                Afficher les statistiques
            </button>

        </div>
    </div>
    <div class="ms-auto d-md-flex d-none flex-row">
        <div class="d-flex flex-row gap-1 day-sorting">
            <button class="btn btn-sm btn-success">Consultation</button>
            <button class="btn btn-sm btn-info">Examen</button>
            <button class="btn btn-sm btn-danger">Hospitalisation</button>
            <button class="btn btn-sm btn-warning">Soins</button>
        </div>
    </div>

</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3 mb-5" id="stat_consultation_date" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="w-100">
                        <div class="input-group">
                            <span class="input-group-text">Du</span>
                            <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">au</span>
                            <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <a id="btn_search_stat_const_date" class="btn btn-outline-success ms-auto">
                                <i class="ri-search-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mt-0 mb-5" id="stat_consultation" style="display: none;"></div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-3 h-100 dashboard-welcome">
                <div class="card-body p-4">
                    <!-- =====================================================
                         HEADER
                         ===================================================== -->
                    <div class="d-flex flex-column flex-lg-row
                                align-items-lg-center
                                justify-content-between
                                gap-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="dashboard-status">
                                    <span></span>
                                    Tableau de bord
                                </span>
                            </div>
                            <h3 class="dashboard-title mb-1">
                                Bienvenue,
                                <span>
                                    {{Auth::user()->user_last_name}}
                                </span>
                            </h3>
                            <p class="dashboard-subtitle mb-0">
                                Voici un aperçu de l'activité de votre établissement
                                pour le
                                <strong id="date_bord_text">
                                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                </strong>.
                            </p>
                        </div>
                        <!-- Date -->
                        <div class="dashboard-date">
                            <div class="dashboard-date-icon">
                                <i class="ri-calendar-2-line"></i>
                            </div>
                            <div>
                                <small>
                                    Date des statistiques
                                </small>
                                <input type="date" class="form-control border-0 form-control-sm" id="stat_bord_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" max="{{ \Carbon\Carbon::now()->toDateString() }}">
                            </div>
                        </div>
                    </div>
                    <!-- =====================================================
                         STATISTIQUES FINANCIÈRES
                         ===================================================== -->
                    <div class="dashboard-section-title">
                        <div class="dashboard-section-icon finance">
                            <i class="ri-money-dollar-circle-line"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">
                                Activité financière
                            </h6>
                            <small>
                                Situation des factures et règlements
                            </small>
                        </div>
                    </div>
                    <div class="row g-3 mb-4">
                        <!-- Factures -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-blue">
                                    <i class="ri-archive-stack-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Factures
                                    </span>
                                    <strong id="nbre_fac">
                                        0
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Montant réglé -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-green">
                                    <i class="ri-checkbox-circle-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Montant réglé
                                    </span>
                                    <strong id="montant_fac_r">
                                        0 Fcfa
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Montant non réglé -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-red">
                                    <i class="ri-error-warning-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Montant non réglé
                                    </span>
                                    <strong id="montant_fac_nr">
                                        0 Fcfa
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Total -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card dashboard-stat-total">
                                <div class="dashboard-stat-icon" >
                                    <i class="ri-wallet-3-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span class="text-white" >
                                        Montant total
                                    </span>
                                    <strong id="total_fac">
                                        0 Fcfa
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- =====================================================
                         STATISTIQUES MÉDICALES
                         ===================================================== -->
                    <div class="dashboard-section-title">
                        <div class="dashboard-section-icon medical">
                            <i class="ri-stethoscope-line"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">
                                Activité médicale
                            </h6>
                            <small>
                                Nombre d'actes réalisés
                            </small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <!-- Consultations -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-purple">
                                    <i class="ri-stethoscope-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Consultations
                                    </span>
                                    <strong id="stat_cons">
                                        0
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Examens -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-orange">
                                    <i class="ri-microscope-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Examens
                                    </span>
                                    <strong id="stat_exam">
                                        0
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Soins -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-cyan">
                                    <i class="ri-heart-pulse-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Soins ambulatoires
                                    </span>
                                    <strong id="stat_soins">
                                        0
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <!-- Hospitalisations -->
                        <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="dashboard-stat-card">
                                <div class="dashboard-stat-icon icon-dark">
                                    <i class="ri-hospital-line"></i>
                                </div>
                                <div class="dashboard-stat-content">
                                    <span>
                                        Hospitalisations
                                    </span>
                                    <strong id="stat_hosp">
                                        0
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
<!-- Raccourcis -->
<div class="dashboard-section-title mt-4">
    <div class="dashboard-section-icon shortcut">
        <i class="ri-flashlight-line"></i>
    </div>
    <div>
        <h6 class="mb-0">
            Accès rapides
        </h6>
        <small>
            Accéder rapidement aux principales fonctionnalités
        </small>
    </div>
</div>

<div class="row g-3">

    <!-- Nouveau patient -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_nouveau_patient"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-user-add-line fs-4 d-block"></i>
            <span>Nouveau patient</span>
        </a>
    </div>

    <!-- Consultation -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_consultation"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-stethoscope-line fs-4 d-block"></i>
            <span>Consultation</span>
        </a>
    </div>

    <!-- Examen -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_examen"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-microscope-line fs-4 d-block"></i>
            <span>Examen</span>
        </a>
    </div>

    <!-- Soins -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_soins"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-heart-pulse-line fs-4 d-block"></i>
            <span>Soins ambulatoires</span>
        </a>
    </div>

    <!-- Hospitalisation -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_hospitalisation"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-hospital-line fs-4 d-block"></i>
            <span>Hospitalisation</span>
        </a>
    </div>

    <!-- Dossier médical -->
    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-3 col-sm-4 col-6">
        <a id="btn_dossier_medical"
           class="btn btn-info w-100 h-100 py-2"
           role="button">
            <i class="ri-folder-user-line fs-4 d-block"></i>
            <span>Dossiers médicaux</span>
        </a>
    </div>

</div>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3 h-100">
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom border-secondary">
                    <h5 class="fw-bold mb-0" style="color: #087f5b;">
                        Statistiques des actes de cette semaine
                    </h5>
                    <a id="btn_refresh_statActivity" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                        <i class="ri-loop-left-line"></i>
                    </a>
                </div>
                <div class="px-2">
                    <div id="docActivity"></div>
                    <div id="consultationComparison" class="card-header"></div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-lg-6 col-md-12 col-12">
            <div class="card h-100" >
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="card-title fw-bold mb-0" style="color: #087f5b;">
                        Solde des actes d'aujourd'hui.
                    </h5>

                    <a id="btn_refresh_stat_fac" class="btn btn-outline-secondary">
                        <i class="ri-loop-left-line"></i>
                    </a>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center h-100" id="content_stat_fac"></div>
            </div>
        </div>
        <div class="col-xxl-6 col-lg-6 col-md-12 col-12">
            <div class="card h-100" >
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="card-title fw-bold mb-0" style="color: #087f5b;">
                        CAISSE
                    </h5>

                    <a id="btn_refresh_soldCaisse" class="btn btn-outline-secondary">
                        <i class="ri-loop-left-line"></i>
                    </a>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center h-100" id="contenu_caisse"></div>
            </div>
        </div>
        <div class="col-xxl-6 col-lg-6 col-md-12 col-12">
            <div class="card h-100">

                <!-- Header -->
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">

                    <div>
                        <h5 class="card-title fw-bold mb-0" style="color: #087f5b;">
                            Rendez-vous du jour
                        </h5>

                        <small class="text-muted">
                            Planning des consultations
                        </small>
                    </div>

                    <button
                        type="button"
                        id="btn_refresh_rdv_day"
                        class="btn btn-light border rounded-2"
                        title="Actualiser"
                    >
                        <i class="ri-loop-left-line"></i>
                    </button>

                </div>

                <!-- Timeline -->
                <div
                    id="contenu_rdv"
                    class="p-3"
                    style="height: 420px; overflow-y: auto;"
                >
                </div>

            </div>
        </div>
        <div class="col-xxl-6 col-lg-6 col-md-12 col-12">
            <div class="card h-100 overflow-hidden">
                <!-- HEADER -->
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-1">
                            Bilan journalier
                        </h5>
                        <small class="text-muted">
                            Historique des mouvements de caisse
                        </small>
                    </div>
                    <div class="rounded-2 bg-success-subtle p-2">
                        <i class="ri-safe-2-line fs-4 text-success"></i>
                    </div>
                </div>
                <!-- RECHERCHE -->
                <div class="px-3 pb-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="ri-calendar-line"></i>
                        </span>
                        <input type="date" id="searchDate1_bj" class="form-control border-start-0" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                        <button type="button" id="btn_search_trace_bj" class="btn btn-success px-3">
                            <i class="ri-search-2-line"></i>
                        </button>
                    </div>
                </div>
                <!-- CONTENU -->
                <div class="card-body ">
                    <div id="historique_contenu" class="d-flex flex-column align-items-start justify-content-start gap-2 p-2" style="height: 380px; overflow-y: auto; box-sizing: border-box;">
                    </div>
                    <!-- TOTAL -->
                    <div id="historique_total" class="mt-3">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>
<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/consultation.js')}}"></script>
<script src="{{asset('assets/app/js/module/reception/tableauBord.js')}}"></script>

@endsection
