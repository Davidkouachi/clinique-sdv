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
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3 h-100">
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom border-secondary">
                    <h5 class="fw-bold mb-0" style="color: #087f5b;">
                        Statistiques des actes de cette semaine
                    </h5>
                    <a id="btn_refresh_soldCaisse" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
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
    </div>
    <div class="row g-3" style="margin-top: 20px;">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -32px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-file-user-line me-2"></i>
                                    Nouveau patient
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-oneAAA" data-bs-toggle="tab" href="#oneAAA" role="tab" aria-controls="oneAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-first-aid-kit-line me-2"></i>
                                    Nouvelle consultation
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " id="tab-threeAAAL" data-bs-toggle="tab" href="#threeAAAL" role="tab" aria-controls="threeAAAL" aria-selected="true">
                                    <i class="ri-calendar-check-line me-2"></i>
                                    Rendez-Vous
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " id="tab-threeAAA" data-bs-toggle="tab" href="#threeAAA" role="tab" aria-controls="threeAAA" aria-selected="true">
                                    <i class="ri-sticky-note-add-line me-2"></i>
                                    Nouvelle societe
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " id="tab-frewAAA" data-bs-toggle="tab" href="#frewAAA" role="tab" aria-controls="frewAAA" aria-selected="true">
                                    <i class="ri-folder-add-line me-2"></i>
                                    Nouvelle Assurance
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="oneAAA" role="tabpanel" aria-labelledby="tab-oneAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Recherche du Patient</h5>
                                </div>
                                <div class="row gx-3">
                                    <div class="row gx-3 justify-content-center align-items-center" >
                                        <div class="col-12">
                                            <div class=" mb-0">
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <a class="d-flex align-items-center flex-column">
                                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-3">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-5 col-lg-5 col-sm-6 col-12">
                                            <div class="mb-3 text-center">
                                                <label class="form-label">
                                                    Nom du patient
                                                </label>
                                                <select class="form-select select2" id="id_patient"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" id="div_info_patient">
                                    </div>
                                    <div class="col-sm-12">
                                        <div id="div_alert_consultation" class="mb-3"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Patient</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">Informations personnelles</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Sexe</label>
                                                <select class="form-select select2" id="patient_sexe_new">
                                                    <option value=""></option>
                                                    <option value="M">Masculin</option>
                                                    <option value="F">Féminin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="patient_nom_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prénoms</label>
                                                <input type="text" class="form-control" id="patient_prenom_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Date de naissance
                                                </label>
                                                <input type="date" class="form-control" placeholder="Selectionner une date" id="patient_datenaiss_new" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 1</label>
                                                <input type="tel" class="form-control" id="patient_tel_new" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 2</label>
                                                <input type="tel" class="form-control" id="patient_tel2_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Résidence</label>
                                                <input type="text" class="form-control" id="patient_residence_new" placeholder="Saisie obligatoire">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">Informations Assurance</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assurer</label>
                                                <select class="form-select" id="assure">
                                                    <option value="">Selectionner</option>
                                                    <option value="0">Non</option>
                                                    <option value="1">Oui</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row gx-3" id="div_assurer" style="display: none;">
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Filiation</label>
                                                    <select class="form-select select2" id="patient_codefiliation_new">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Assurance</label>
                                                    <select class="form-select select2" id="patient_codeassurance_new">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Matricule assurance</label>
                                                    <input type="text" class="form-control" id="patient_matriculeA_new" placeholder="Saisie Obligatoire">
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Taux</label>
                                                    <select class="form-select select2" id="patient_idtauxcouv_new">
                                                        <option value="">Sélectionner un taux</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Société</label>
                                                    <select class="form-select select2" id="patient_codesocieteassure_new">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body border border-1 rounded-2 mb-3">
                                    <div class="row gx-3">
                                        <div class="card-header">
                                            <h5 class="card-title text-center">En Cas d'urgence</h5>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="patient_nomu_new" placeholder="facultatif" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 1</label>
                                                <input type="tel" class="form-control" id="patient_telu_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact 2</label>
                                                <input type="tel" class="form-control" id="patient_telu2_new" placeholder="facultatif" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body mb-3">
                                    <div class="row gx-3">
                                        <div class="col-sm-12 mb-3">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_patient" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="threeAAAL" role="tabpanel" aria-labelledby="tab-threeAAAL">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">Listes de Rendez-Vous du jour</h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table align-middle table-hover m-0 truncate Table_day_rdv">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Patient</th>
                                                        <th>Contact</th>
                                                        <th>Médecin</th>
                                                        <th>Spécialité</th>
                                                        <th>Rdv prévu</th>
                                                        <th>Statut</th>
                                                        <th>Date de création</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="threeAAA" role="tabpanel" aria-labelledby="tab-threeAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouvelle Societe</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/batiment.avif')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3 alig-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom de la société</label>
                                                <input type="text" class="form-control" id="nom_societe" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assurance</label>
                                                <select class="form-select select2" id="codeassurance_societe">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assureur</label>
                                                <select class="form-select select2" id="assureur_id_societe">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-3 ">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_societe" class="btn btn-outline-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="frewAAA" role="tabpanel" aria-labelledby="tab-frewAAA">
                                <div class="card-header">
                                    <h5 class="card-title text-center">
                                        Formulaire Nouvelle Assurance
                                    </h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/assurance3.jpg')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom_assurance_new" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input required type="email" class="form-control" id="email_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="tel" class="form-control" id="tel_assurance_new" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" >Fax</label>
                                                <input type="text" class="form-control" id="fax_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <input type="text" class="form-control" id="adresse_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Localisation</label>
                                                <input type="text" class="form-control" id="carte_assurance_new" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" id="desc_assurance_new" placeholder="Facultatif">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_assurance" class="btn btn-success">
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
    <div class="row gx-3" >
        <div class="col-12">
            <div class="card mb-3">
                <div class="p-2 d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="card-title text-center mb-0">
                        Consultation d'aujourd'hui
                    </h5>
                    <a id="btn_refresh_table" class="btn btn-warning">
                        <i class="ri-loop-left-line"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="">
                            <table id="Table_day" class="table Table_day_cons my-5">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        {{-- <th scope="col">N° Consultation</th> --}}
                                        <th scope="col">N° dossier</th>
                                        <th scope="col">Nom et Prénoms</th>
                                        {{-- <th scope="col">Contact</th> --}}
                                        <th scope="col">Médecin Consultant</th>
                                        <th scope="col">Motif</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">N° Facture</th>
                                        <th scope="col">Date</th>
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

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
    </div>
</div>

<div class="modal fade" id="MdeleteCons" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette consultation ?
                <input type="hidden" id="IddeleteCons">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtnCons" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
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

@include('select2')

@endsection
