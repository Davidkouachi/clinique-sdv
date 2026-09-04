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
    @include('pageTitre', [
        'title' => 'EXAMENS',
        'subtitle' => 'Services / Examens'
    ])
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
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN" style="padding-bottom: 120px;">
                                {{-- =========================================================
                                PATIENT + HOSPITALISATION
                                ========================================================== --}}
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
                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                                        <div class="mb-3 text-center">
                                                            <label class="form-label">
                                                                Période
                                                            </label>
                                                            <select class="form-select select2" id="periode">
                                                                <option value="">
                                                                    Sélectionner
                                                                </option>
                                                                <option value="0">
                                                                    Jour
                                                                </option>
                                                                <option value="1">
                                                                    Nuit
                                                                </option>
                                                                <option value="2">
                                                                    Férié
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
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
                                <div id="select_examen_div" style="display: none;">
                                    {{-- =====================================================
                                    CHOIX DES EXAMENS
                                    ====================================================== --}}
                                    <div id="div_Examen" class="mb-3 p-2" style="display: none;">
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-12">
                                                <div class="mt-2">
                                                    <div class="card border">
                                                        <div class="card-header">
                                                            <h5 class="card-title text-center mb-1">
                                                                <i class="ri-capsule-line me-1"></i>
                                                                Examens
                                                            </h5>
                                                            <p class="text-muted text-center small mb-0">
                                                                Examens à réaliser
                                                            </p>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row gx-3" id="contenu_examen">
                                                            </div>
                                                            <div class="row gx-3 justify-content-center">
                                                                <div class="col-12 mb-3 text-center">
                                                                    <button type="button" id="add_select_examen" class="btn btn-outline-info">
                                                                        <i class="ri-add-line"></i>
                                                                        Ajouter un Examen
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- =================================================
                                                TOTALS
                                                ================================================== --}}
                                                <div class="row gx-3 mt-3" id="div_btn_examen">                
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
                                                    {{-- Assurance --}}
                                                    <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text w-25">
                                                                Assurance
                                                            </span>
                                                            <input readonly type="tel" class="form-control" id="montant_assurance_examen" value="0" placeholder="Part Assurance">
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
                                                            <input readonly type="tel" class="form-control" id="montant_patient_examen" value="0" placeholder="Part Patient">
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
                                                    {{-- Total --}}
                                                    <div class="col-xxl-5 col-lg-6 col-sm-6">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text w-25">
                                                                Total
                                                            </span>
                                                            <input readonly type="tel" class="form-control" id="montant_total_examen" value="0" placeholder="Montant Total">
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
                            </div>

                            <div class="tab-pane fade " id="oneAAAD" role="tabpanel" aria-labelledby="tab-oneAAAD">
                                <div class="row gx-3" >
                                    <div class="col-12">
                                        <div class=" mb-3">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h5 class="card-title">
                                                    Liste des Examens Demandées
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div id="examendTable" ></div>
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
                                            </div>
                                            <div class="card-body">
                                                <div class="" id="examenTable"></div>
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
<script src="{{asset('assets/app/js/pdf/examen.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

let examenTable = new CustomTable({

    selector: '#examenTable',

    url:
        $('#url').attr('content') + '/api/examens/garanties',

    perPage: 15,

    searchPlaceholder:
        'Numero, code, examen, valeur, cotation...',

    searchButtonText:
        'Rechercher',

    params: function () {

        return {

        };

    },

    columns: [

        {
            label: 'N°',
            data: null,

            render: function (
                value,
                row,
                index
            ) {

                return (
                    (
                        examenTable.meta
                            .from || 1
                    ) + index
                );

            }
        },


        {
            label: 'Examen',
            data: 'denomination',

            render: function (value) {

                return value
                    ? examenTable.escape(value)
                    : '<span class="text-muted">Aucun</span>';

            }
        },


        {
            label: 'Type',
            data: 'type',

            render: function (value) {

                return value
                    ? examenTable.escape(value)
                    : '<span class="text-muted">Inconnu</span>';

            }
        },


        {
            label: 'Code',
            data: 'numexam',

            render: function (value) {

                return value
                    ? examenTable.escape(value)
                    : '<span class="text-muted">Aucun</span>';

            }
        },

        {
            label: 'Valeur',
            data: 'codfamexam',

            render: function (value) {

                return `
                    <strong>
                        ${value ?? 'Aucun'}
                    </strong>
                `;

            }
        },


        {
            label: 'Cotation',
            data: 'cot',

            render: function (value) {

                return `
                    <strong>
                        ${value ?? 0}
                    </strong>
                `;

            }
        }

    ],


    actions: [

        {
            name: 'detailEx',

            label: 'Détails',

            icon: 'ri-eye-fill',

            class: 'text-warning'
        },

    ],


    onAction: function (action, row) {

        if (action === 'detailEx') {

            const code = row.numexam;

            window.openModal({

                title: 'Détails Examen',

                content: `
                    <div class="text-center py-4">

                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">
                                Chargement...
                            </span>
                        </div>

                        <div class="mt-3 text-muted">
                            Chargement des tarifs...
                        </div>

                    </div>
                `,

                size: 'modal-lg'

            });

            fetch(
                $('#url').attr('content') +
                `/api/examens/garantie/detail/${encodeURIComponent(code)}`
            )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            `Erreur HTTP ${response.status}`
                        );

                    }

                    return response.json();

                })

                .then(data => {

                    const details = data.prix ?? [];

                    const modalBody =
                        document.querySelector(
                            '#globalModal .custom-modal-body'
                        );

                    if (!modalBody) {
                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Aucun tarif
                    |--------------------------------------------------------------------------
                    */

                    if (details.length === 0) {

                        modalBody.innerHTML = `

                            <div class="text-center py-5">

                                <i class="ri-information-line fs-1 text-muted"></i>

                                <div class="mt-3 text-muted">
                                    Aucun tarif disponible pour cet examen.
                                </div>

                            </div>

                        `;

                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Génération des tarifs
                    |--------------------------------------------------------------------------
                    */

                    const prixHTML = details.map(function (item) {

                        const assurance =
                            item.codeassurance === 'NONAS'
                                ? 'Patient non assuré'
                                : item.assurance ?? 'Assurance inconnue';

                        const headerClass =
                            item.codeassurance === 'NONAS'
                                ? 'bg-success'
                                : 'bg-primary';

                        return `

                            <div class="col-12">

                                <div class="card border mb-3">

                                    <div class="card-header ${headerClass} text-white">

                                        <div class="d-flex align-items-center">

                                            <i class="ri-shield-check-line me-2"></i>

                                            <strong>
                                                ${examenTable.escape(
                                                    assurance
                                                )}
                                            </strong>

                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row g-3">

                                            <div class="col-md-4">

                                                <div class="border rounded p-3 text-center">

                                                    <div class="text-muted small mb-1">
                                                        Montant Jour
                                                    </div>

                                                    <strong>
                                                        ${formatPriceT(
                                                            item.montjour ?? 0
                                                        )}
                                                        Fcfa
                                                    </strong>

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="border rounded p-3 text-center">

                                                    <div class="text-muted small mb-1">
                                                        Montant Nuit
                                                    </div>

                                                    <strong>
                                                        ${formatPriceT(
                                                            item.montnuit ?? 0
                                                        )}
                                                        Fcfa
                                                    </strong>

                                                </div>

                                            </div>

                                            <div class="col-md-4">

                                                <div class="border rounded p-3 text-center">

                                                    <div class="text-muted small mb-1">
                                                        Montant Férié
                                                    </div>

                                                    <strong>
                                                        ${formatPriceT(
                                                            item.montferie ?? 0
                                                        )}
                                                        Fcfa
                                                    </strong>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        `;

                    }).join('');

                    /*
                    |--------------------------------------------------------------------------
                    | Contenu final
                    |--------------------------------------------------------------------------
                    */

                    modalBody.innerHTML = `

                        <div class="row gx-3">

                            <div class="col-12">

                                <div class="text-center mb-4">

                                    <img
                                        src="{{ asset('assets/images/tarif.png') }}"
                                        class="img-7x rounded-circle mb-3 border border-3"
                                        alt="Tarif"
                                    >

                                    <div class="fw-semibold">
                                        ${examenTable.escape(
                                            row.denomination ?? ''
                                        )}
                                    </div>

                                    <div class="small text-muted">
                                        ${examenTable.escape(
                                            row.numexam ?? ''
                                        )}
                                    </div>

                                </div>

                            </div>

                            ${prixHTML}

                            <div class="col-12 mt-2">

                                <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">

                                    <i class="ri-information-line fs-5"></i>

                                    <div>

                                        <strong>NOTE</strong>

                                        <div class="small mt-1">
                                            Les tarifs affichés sont présentés
                                            à titre récapitulatif.
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    `;

                })

                .catch(error => {

                    console.error(
                        'Erreur lors du chargement des tarifs :',
                        error
                    );

                    const modalBody =
                        document.querySelector(
                            '#globalModal .custom-modal-body'
                        );

                    if (!modalBody) {
                        return;
                    }

                    modalBody.innerHTML = `

                        <div class="text-center py-5">

                            <i class="ri-error-warning-line fs-1 text-danger"></i>

                            <div class="mt-3 text-danger">
                                Impossible de charger les tarifs.
                            </div>

                        </div>

                    `;

                });

        }

    }

});


let examendTable = new CustomTable({

    selector: '#examendTable',

    url:
        $('#url').attr('content') + '/api/examens',

    perPage: 15,

    searchPlaceholder:
        'N° facture, dossier, patient, médecin, examen...',

    searchButtonText:
        'Rechercher',

    params: function () {

        return {

        };

    },

    columns: [

        {
            label: 'N°',
            data: null,

            render: function (
                value,
                row,
                index
            ) {

                return (
                    (
                        examendTable.meta
                            .from || 1
                    ) + index
                );

            }
        },


        {
            label: 'N° Dossier',
            data: 'numdossier',

            render: function (value) {

                return value
                    ? examendTable.escape(value)
                    : '<span class="text-muted">Aucun</span>';

            }
        },


        {
            label: 'Nom et Prénoms',
            data: 'nom_patient',

            render: function (value) {

                return value
                    ? examendTable.escape(value)
                    : '<span class="text-muted">Inconnu</span>';

            }
        },


        {
            label: 'Médecin',
            data: 'medecin',

            render: function (value) {

                return value
                    ? examendTable.escape('Dr. '+ value)
                    : '<span class="text-muted">Aucun</span>';

            }
        },

        {
            label: 'Nb examens',
            data: 'nbre_examens',

            render: function (value) {

                return `
                    <strong class="text-primary">
                        ${value ?? 0}
                    </strong>
                `;

            }
        },


        {
            label: 'Montant',
            data: 'montant_total',

            render: function (value) {

                return `
                    <strong>
                        ${formatPriceT(value)} Fcfa
                    </strong>
                `;

            }
        },


        {
            label: 'Part patient',
            data: 'montant_patient',

            render: function (value) {

                return `
                    ${formatPriceT(value)} Fcfa
                `;

            }
        },


        {
            label: 'Part assurance',
            data: 'montant_assurance',

            render: function (value) {

                return `
                    ${formatPriceT(value)} Fcfa
                `;

            }
        },


        {
            label: 'N° Facture',
            data: 'numfac'
        },


        {
            label: 'Date',
            data: 'created_at',

            render: function (value) {

                return formatDate(value);

            }
        }

    ],


    actions: [

        {
            name: 'facture',

            label: 'Imprimer facture',

            icon: 'ri-printer-fill',

            class: 'text-info'
        },


        {
            name: 'detail',

            label: 'Détails',

            icon: 'ri-eye-fill',

            class: 'text-primary'
        },


        {
            name: 'delete',

            label: 'Supprimer',

            icon: 'ri-delete-bin-line',

            class: 'text-danger',

            visible: function (row) {

                return (
                    parseFloat(row.montant_regle) === 0
                );

            }

        }

    ],


    onAction: function (action, row) {

        if (action === 'facture') {

            window.showPreloader();

            const id = row.id;

            fetch(
                $('#url').attr('content') + `/api/examens/detail/${id}`
            )
                .then(response => response.json())

                .then(data => {

                    window.hidePreloader();

                    const facture = data.facture;
                    const details = data.details;

                    pdfFactureExamen(facture, details);

                })

                .catch(error => {

                    window.hidePreloader();

                    console.error(
                        'Erreur lors du chargement des données:',
                        error
                    );

                });

        }


        if (action === 'detail') {

            const code = row.id;

            const content = `

                <div class="table-responsive">

                    <table
                        class="table table-bordered align-middle"
                        id="TableP"
                    >

                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Examen</th>
                                <th>Montant</th>
                                <th>Prélevement</th>
                                <th>Prise en charge ?</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td
                                    colspan="5"
                                    class="text-center py-4"
                                >
                                    <div class="spinner-border text-warning me-2"
                                         role="status"
                                         aria-hidden="true">
                                    </div>

                                    <strong>
                                        Chargement des données...
                                    </strong>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>
            `;

            fetch(
                $('#url').attr('content') + `/api/examens/detail/${encodeURIComponent(code)}`
            )

                .then(response => {

                    if (!response.ok) {
                        throw new Error(
                            `Erreur HTTP ${response.status}`
                        );
                    }

                    return response.json();

                })

                .then(data => {

                    const details = data.details ?? [];

                    window.openModal({

                        title: 'Examens demandés',

                        content: content,

                        size: 'modal-lg'

                    });

                    const tbody =
                        document.querySelector(
                            '#globalModal #TableP tbody'
                        );


                    if (!tbody) {
                        return;
                    }


                    if (details.length === 0) {

                        tbody.innerHTML = `

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >

                                    Aucun examen demandé.

                                </td>

                            </tr>

                        `;

                        return;
                    }



                    tbody.innerHTML = details.map(
                        function (examen) {

                            const montant =
                                parseFloat(
                                    examen.montant ?? 0
                                );

                            const prelevement =
                                parseFloat(
                                    examen.prelevement ?? 0
                                );

                            const assure =
                                Number(examen.assurance) === 1 ||
                                examen.assurance === true;


                            return `

                                <tr>

                                    <td>
                                        <strong>
                                            ${examendTable.escape(
                                                examen.code === 'B' ? 'ANALYSE' : 'IMAGERIE'
                                            )}
                                        </strong>
                                    </td>

                                    <td>
                                        ${examendTable.escape(
                                            examen.examen ?? ''
                                        )}
                                    </td>

                                    <td class="text-start">

                                        <strong>
                                            ${formatPriceT(montant)}
                                            Fcfa
                                        </strong>

                                    </td>

                                    <td class="text-start">

                                        <strong>
                                            ${formatPriceT(prelevement)}
                                            Fcfa
                                        </strong>

                                    </td>

                                    <td class="text-start">
                                        ${
                                            assure

                                                ? `
                                                    <strong class="text-success">
                                                        Oui
                                                    </strong>
                                                `

                                                : `
                                                    <strong class="text-danger">
                                                        Non
                                                    </strong>
                                                `
                                        }

                                    </td>

                                </tr>

                            `;

                        }
                    ).join('');

                    tbody.insertAdjacentHTML(
                        'beforeend',
                        `

                            <tr>

                                <td colspan="5">

                                    <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">

                                        <i class="ri-information-line fs-5"></i>

                                        <div>

                                            <strong>NOTE</strong>

                                            <div class="small mt-1">
                                                Les informations ci-dessus sont présentées
                                                à titre récapitulatif.
                                            </div>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        `
                    );

                })

                .catch(error => {

                    console.error(
                        'Erreur lors du chargement des examens :',
                        error
                    );

                    const tbody =
                        document.querySelector(
                            '#globalModal #TableP tbody'
                        );

                    if (tbody) {

                        tbody.innerHTML = `

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-danger py-4"
                                >

                                    Impossible de charger les examens.

                                </td>

                            </tr>

                        `;

                    }

                });

        }


        if (action === 'delete') {

            window.confirmAction(
                "Confirmation requise",
                "Cette opération est irréversible. Êtes-vous sûr de vouloir effectuer cette action ?"
            ).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }

                window.showPreloader();

                $.ajax({

                    url: $('#url').attr('content') + '/api/examens/delete/' + encodeURIComponent(row.id),

                    method: 'delete',

                    success: function (response) {

                        if (response.success) {

                            examendTable.page = 1;
                            examendTable.load();

                            showAlert(
                                'Succès',
                                response.message ?? 'Opération effectuée.',
                                'success'
                            );

                        } else if (response.warn) {

                            showAlert(
                                'Alerte',
                                response.message ?? "Une erreur c'est produite, réessayer.",
                                'warning'
                            );

                        } else {

                            showAlert(
                                'Erreur',
                                response.message ?? "Échec de l'opération.",
                                'error'
                            );

                        }

                    },

                    error: function () {

                        showAlert(
                            'Erreur',
                            "Echc de l'opération",
                            'error'
                        );

                    },

                    complete: function () {

                        window.hidePreloader();
                    }

                });

            });
        }

    }

});

// ------------------------------------------------

let patientExamen = null;
let famillesExamens = [];
let cachedExamens = {};

initialiserVoletExamen();

function examenUrl(path) {

    return (
        $('#url').attr('content') +
        path
    );
}

function getNumber(value) {

    if (
        value === null ||
        value === undefined ||
        value === ''
    ) {
        return 0;
    }

    return parseInt(
        String(value).replace(/[^0-9]/g, ''),
        10
    ) || 0;
}

function formatExamenMoney(value) {

    value = Math.max(
        0,
        parseInt(value, 10) || 0
    );

    return value
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function removeExamenPreloader() {

    const preloader =
        document.getElementById('preloader_ch');

    if (preloader) {
        preloader.remove();
    }
}

function showExamenPreloader() {

    removeExamenPreloader();

    const preloader = `
        <div id="preloader_ch">
            <div class="spinner_preloader_ch"></div>
        </div>
    `;

    document.body.insertAdjacentHTML(
        'beforeend',
        preloader
    );
}

function initialiserVoletExamen() {

    chargerPatientsExamen();

    chargerFamillesExamens();

    $('#patient_id').on(
        'change',
        changementPatientExamen
    );


    $('#periode').on(
        'change',
        changementPeriodeExamen
    );

    $('#charge_prelev').on(
        'change',
        function () {

            calculerMontantsExamens();
        }
    );

    $('#montant_pre_examen').on(
        'input',
        function () {

            formaterChampMontant(this);

            calculerMontantsExamens();
        }
    );

    $('#taux_remise').on(
        'input',
        function () {

            formaterChampMontant(this);

            calculerMontantsExamens();
        }
    );

    $('#btn_eng_exd').on(
        'click',
        function () {

            enregistrerExamen();
        }
    );

    $('#add_select_examen').on(
        'click',
        function () {

            ajouterBlocExamen();
        }
    );

    $('#montant_assurance_examen, #montant_patient_examen')
        .on('input', function () {

            formaterChampMontant(this);

        });


    $('#numcode').on(
        'keypress',
        allowOnlyNumbersAndLetters
    );


    $('#medecin, #rensg').on(
        'input',
        function () {

            this.value =
                this.value.toUpperCase();

        }
    );

}

function chargerPatientsExamen() {

    const selectPatient =
        $('#patient_id');

    selectPatient.empty();

    selectPatient.append(
        $('<option>', {
            value: '',
            text: 'Sélectionner'
        })
    );


    $.ajax({

        url: examenUrl(
            '/api/select/patient'
        ),

        type: 'GET',

        dataType: 'json',

        success: function (response) {

            const patients =
                Array.isArray(response.results)
                    ? response.results
                    : [];


            patients.forEach(function (patient) {

                selectPatient.append(

                    $('<option>', {

                        value: patient.id,

                        text:
                            `ID : ${patient.id} | ${patient.nom}`

                    })

                );

            });

        },

        error: function (xhr) {

            console.error(
                'Erreur chargement patients :',
                xhr.responseText
            );

            showAlert(
                'ALERT',
                'Impossible de charger les patients.',
                'error'
            );

        }

    });

}

function changementPatientExamen() {

    const patientId =
        $(this).val();


    /*
     * Réinitialisation complète
     */
    resetVoletExamen();


    if (!patientId) {

        $('#select_periode_div').hide();

        $('#select_examen_div').hide();

        $('#div_info_patient').hide();

        return;
    }


    /*
     * Récupérer les informations du patient
     */
    chargerInformationsPatient(patientId);

}

function chargerInformationsPatient(patientId) {

    showExamenPreloader();

    $.ajax({

        url: examenUrl(
            '/api/rech/patient'
        ),

        type: 'GET',

        dataType: 'json',

        data: {
            id: patientId
        },


        success: function (response) {

            removeExamenPreloader();


            if (
                !response.success ||
                !response.patient
            ) {

                showAlert(
                    'ALERT',
                    'Les informations du patient sont introuvables.',
                    'warning'
                );

                return;
            }


            /*
             * Stockage UNE SEULE FOIS
             */
            patientExamen =
                response.patient;


            afficherInformationsPatient(
                patientExamen
            );


            /*
             * Taux
             */
            const taux =
                Math.max(
                    0,
                    Math.min(
                        100,
                        Number(
                            patientExamen.taux
                        ) || 0
                    )
                );


            $('#patient_taux')
                .val(taux);


            /*
             * Assurance
             */
            gererAssurancePatient();


            /*
             * Afficher période
             */
            $('#select_periode_div')
                .show();


            /*
             * Afficher la partie examen
             * seulement après choix période
             */
            $('#select_examen_div')
                .hide();

        },


        error: function (xhr) {

            removeExamenPreloader();

            console.error(
                'Erreur informations patient :',
                xhr.responseText
            );

            showAlert(
                'ALERT',
                'Erreur lors du chargement des informations du patient.',
                'error'
            );

        }

    });

}

function afficherInformationsPatient(patient)
{
    const assure =
        Number(patient.assure) === 1;

    const numero =
        patient.id ||
        patient.idenregistremetpatient ||
        '';

    const nom =
        patient.nomprenomspatient ||
        patient.nomprenoms ||
        patient.np ||
        '';

    const sexe =
        patient.sexepatient ||
        patient.sexe ||
        '';

    const dateNaissance =
        patient.datenaispatient ||
        patient.date_naissance ||
        '';

    const telephone =
        patient.telpatient ||
        patient.tel ||
        '';

    const taux =
        assure
            ? Number(patient.taux) || 0
            : 0;


    let html = `

        <div class="col-12 mt-2 mb-3">

            <div
                class="d-flex align-items-center
                       border-top pt-3"
            >

                <div
                    class="d-flex align-items-center justify-content-center
                           bg-warning text-white rounded-circle me-3"
                    style="width: 40px; height: 40px;"
                >
                    <i class="ri-user-line fs-5"></i>
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


        <div class="row gx-3">

            <!-- N° dossier -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        N° dossier
                    </label>

                    <input
                        id="patient_numdossier"
                        type="text"
                        value="${numero}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Nom et Prénoms -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Nom et Prénoms
                    </label>

                    <input
                        type="text"
                        value="${nom}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Sexe -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Sexe
                    </label>

                    <input
                        type="text"
                        value="${sexe}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Date naissance -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Naissance
                    </label>

                    <input
                        type="text"
                        value="${formatDate(dateNaissance)}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Contact -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Contact
                    </label>

                    <input
                        type="text"
                        value="${telephone}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Assuré -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Assuré
                    </label>

                    <input
                        type="text"
                        value="${assure ? 'Oui' : 'Non'}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Données cachées -->

            <input
                type="hidden"
                id="patient_codeassurance"
                value="${assure ? (patient.codeassurance ?? '') : ''}"
            >

            <input
                type="hidden"
                id="patient_taux"
                value="${taux}"
            >

    `;


    /*
     * Informations assurance
     */
    if (assure) {

        html += `

            <!-- Assurance -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Assurance
                    </label>

                    <input
                        type="text"
                        value="${patient.assurance ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Matricule assurance -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Matricule assurance
                    </label>

                    <input
                        type="text"
                        value="${patient.matriculeassure ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>


            <!-- Taux -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Taux
                    </label>

                    <div class="input-group">

                        <input
                            type="text"
                            value="${taux}"
                            readonly
                            class="form-control"
                        >

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                </div>
            </div>


            <!-- Société -->
            <div class="col-xxl-3 col-lg-4 col-sm-6">
                <div class="mb-3">

                    <label class="form-label">
                        Société
                    </label>

                    <input
                        type="text"
                        value="${patient.societe ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>
            </div>

        `;
    }


    html += `

        </div>

    `;


    $('#div_info_patient')
        .html(html)
        .show();
}

function gererAssurancePatient() {

    if (!patientExamen) {
        return;
    }


    const taux =
        Math.max(
            0,
            Math.min(
                100,
                Number(patientExamen.taux) || 0
            )
        );


    const assure =
        Number(patientExamen.assure) === 1;


    const divNumCode =
        $('#div_numcode');


    /*
     * Patient assuré
     */
    if (assure && taux > 0) {

        divNumCode.show();

    } else {

        divNumCode.hide();

        $('#numcode').val('');

    }

}

function changementPeriodeExamen() {

    const periode =
        $(this).val();


    if (
        !patientExamen ||
        !periode
    ) {

        $('#select_examen_div')
            .hide();

        return;

    }


    $('#select_examen_div')
        .show();


    /*
     * Charger les familles UNE SEULE FOIS
     */
    {{-- chargerFamillesExamens(); --}}


    /*
     * Créer automatiquement le premier bloc
     */
    if (
        $('#contenu_examen .examen-block')
            .length === 0
    ) {

        ajouterBlocExamen();

    }


    $('#div_Examen')
        .show();


    calculerMontantsExamens();

}

function chargerFamillesExamens() {

    /*
     * Déjà chargées
     */
    if (famillesExamens.length > 0) {

        return $.Deferred()
            .resolve()
            .promise();
    }


    {{-- showExamenPreloader(); --}}


    return $.ajax({

        url: examenUrl(
            '/api/select/typexamen'
        ),

        type: 'GET',

        dataType: 'json'

    })


    .done(function (response) {

        famillesExamens =
            Array.isArray(response.results)
                ? response.results
                : [];


        if (
            famillesExamens.length === 0
        ) {

            showAlert(
                'ALERT',
                "Aucune famille d'examen n'a été trouvée.",
                'warning'
            );

        }

    })


    .fail(function (xhr) {

        console.error(
            'Erreur familles examens :',
            xhr.responseText
        );

        showAlert(
            'ALERT',
            "Impossible de charger les familles d'examens.",
            'error'
        );

    })


    .always(function () {

        {{-- removeExamenPreloader(); --}}

    });

}

function ajouterBlocExamen() {

    if (!patientExamen) {

        showAlert(
            'ALERT',
            'Veuillez sélectionner un patient.',
            'warning'
        );

        return;

    }


    const periode =
        $('#periode').val();


    if (!periode) {

        showAlert(
            'ALERT',
            'Veuillez sélectionner une période.',
            'warning'
        );

        return;

    }


    const container =
        $('#contenu_examen');


    const index =
        container.find('.examen-block').length + 1;


    const bloc = $(`
        <div
            class="
                examen-block
                border
                rounded-3
                p-3
                mb-3
            "
        >

            <div
                class="
                    d-flex
                    align-items-center
                    justify-content-between
                    mb-3
                "
            >

                <h6 class="fw-semibold mb-0">

                    <i class="ri-test-tube-line me-1"></i>

                    EXAMEN ${index}

                </h6>


                <button
                    type="button"
                    class="
                        btn
                        btn-outline-danger
                        btn-sm
                        btn-supprimer-examen
                    "
                >

                    <i class="ri-delete-bin-line"></i>

                </button>

            </div>


            <div class="row gx-3">

                <!-- Famille -->

                <div class="col-lg-4 col-md-5 col-sm-12">

                    <div class="mb-3">

                        <label class="form-label">
                            Type d'examen
                        </label>

                        <select
                            class="
                                form-select
                                examen-famille
                                select2
                            "
                        >

                            <option value="">
                                Sélectionner
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Examen -->

                <div class="col-lg-8 col-md-7 col-sm-12">

                    <div class="mb-3">

                        <label class="form-label">
                            Examen
                        </label>

                        <select
                            class="
                                form-select
                                examen-select
                                select2
                            "
                            disabled
                        >

                            <option value="">
                                Sélectionner
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <div class="row gx-3">


                <!-- Assurance -->

                <div class="col-lg-3 col-md-6 col-sm-6">

                    <div class="mb-3">

                        <label class="form-label">
                            Prise en charge
                        </label>

                        <select
                            class="
                                form-select
                                examen-assurance
                                select2
                            "
                        >

                            <option value="non">
                                Non
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Cotation -->

                <div class="col-lg-3 col-md-6 col-sm-6">

                    <div class="mb-3">

                        <label class="form-label">
                            Cotation
                        </label>

                        <input
                            type="text"
                            class="
                                form-control
                                examen-cotation
                            "
                            readonly
                        >

                    </div>

                </div>


                <!-- Valeur cotation -->

                <div class="col-lg-3 col-md-6 col-sm-6">

                    <div class="mb-3">

                        <label class="form-label">
                            Valeur cotation
                        </label>

                        <input
                            type="tel"
                            class="
                                form-control
                                examen-valeur
                            "
                            value="1"
                        >

                    </div>

                </div>


                <!-- Prix -->

                <div class="col-lg-3 col-md-6 col-sm-6">

                    <div class="mb-3">

                        <label class="form-label">
                            Prix
                        </label>

                        <div class="input-group">

                            <input
                                type="tel"
                                class="
                                    form-control
                                    examen-prix
                                "
                            >

                            <span class="input-group-text">
                                FCFA
                            </span>

                        </div>

                    </div>

                </div>


                <!-- Montant -->

                <div class="col-lg-4 col-md-6 col-sm-6">

                    <div class="mb-3">

                        <label class="form-label">
                            Montant
                        </label>

                        <div class="input-group">

                            <input
                                type="tel"
                                class="
                                    form-control
                                    examen-montant
                                "
                                readonly
                            >

                            <span class="input-group-text">
                                FCFA
                            </span>

                        </div>

                    </div>

                </div>


            </div>

            <div class="row gx-3 div-preleve-examen"style="display: none;" >

                <div class="col-12">

                    <div class="border-top pt-3 mt-2">

                        <label class="form-label fw-semibold">
                            <i class="ri-flask-line me-1"></i>
                            Prélèvement
                        </label>

                        <div class="row gx-3">

                            {{-- Charge --}}
                            <div class="col-xxl-4 col-lg-4 col-sm-6">

                                <div class="input-group mb-3">

                                    <span class="input-group-text">
                                        Charge
                                    </span>

                                    <select
                                        class="form-select select2 charge-prelev"
                                    >

                                        <option value="patient">
                                            Patient
                                        </option>

                                        <option value="assurance">
                                            Assurance
                                        </option>

                                    </select>

                                </div>

                            </div>


                            {{-- Montant prélèvement --}}
                            <div class="col-xxl-4 col-lg-4 col-sm-6">

                                <div class="input-group mb-3">

                                    <span class="input-group-text">
                                        Montant
                                    </span>

                                    <input
                                        type="tel"
                                        class="form-control montant-pre-examen"
                                        value="0"
                                        placeholder="Montant"
                                    >

                                    <span class="input-group-text">
                                        Fcfa
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    `);


    container.append(bloc);


    /*
     * Remplir les familles
     */
    remplirFamillesBloc(bloc);


    /*
     * Initialiser Select2 document.getElementById
     */
    {{-- initialiserSelect2Bloc(bloc); --}}


    /*
     * Assurance
     */
    initialiserAssuranceBloc(bloc);


    /*
     * Événements du bloc
     */
    initialiserEvenementsBloc(bloc);


    /*
     * Afficher le bloc
     */
    $('#div_Examen').show();


    $('#div_btn_examen').show();


    updateExamenIndexes();

}

function remplirFamillesBloc(bloc) {

    const select =
        bloc.find('.examen-famille');


    select.find('option:not(:first)')
        .remove();


    famillesExamens.forEach(function (item) {

        select.append(

            $('<option>', {

                value:
                    item.codfamexam,

                text:
                    item.nomfamexam

            })

        );

    });

}

function initialiserSelect2Bloc(bloc) {

    bloc.find('.examen-famille').select2({

        theme: 'bootstrap',

        width: '100%',

        placeholder: 'Sélectionner',

        language: {

            noResults: function () {

                return 'Aucun résultat trouvé';

            }

        }

    });


    bloc.find('.examen-select').select2({

        theme: 'bootstrap',

        width: '100%',

        placeholder: 'Sélectionner',

        language: {

            noResults: function () {

                return 'Aucun résultat trouvé';

            }

        }

    });

}

function initialiserAssuranceBloc(bloc) {

    const select =
        bloc.find('.examen-assurance');


    const taux =
        Number(patientExamen?.taux) || 0;


    const assure =
        Number(patientExamen?.assure) === 1;


    if (
        assure &&
        taux > 0
    ) {

        select.append(

            $('<option>', {

                value: 'oui',

                text: 'Oui'

            })

        );

    }

}

function initialiserEvenementsBloc(bloc)
{
    /*
     * Changement famille
     */
    bloc.find('.examen-famille')
        .on('change', function () {

            const id = $(this).val();

            /*
             * Reset des informations
             */
            viderInformationsBloc(bloc);

            /*
             * Reset select examen
             */
            const examenSelect = bloc.find('.examen-select');

            examenSelect
                .empty()
                .append(
                    $('<option>', {
                        value: '',
                        text: 'Sélectionner'
                    })
                )
                .prop('disabled', true);

            /*
             * Gestion du prélèvement
             */
            afficherPrelevementSelonFamille(bloc);

            /*
             * Aucune famille
             */
            if (!id) {

                calculerMontantsExamens();

                return;
            }

            /*
             * Charger les examens
             */
            chargerExamensFamille(id, bloc);
        });


    /*
     * Changement examen
     */
    bloc.find('.examen-select')
        .on('change', function () {

            /*
             * Mettre à jour les informations
             * de l'examen sélectionné
             */
            selectionnerExamen(bloc);

            /*
             * Recalcul global immédiat
             */
            calculerMontantsExamens();
        });


    /*
     * Changement prise en charge
     */
    bloc.find('.examen-assurance')
        .on('change', function () {

            /*
             * Mettre à jour la prise en charge
             * du bloc
             */
            selectionnerAssuranceBloc(bloc);

            /*
             * Recalcul global immédiat
             */
            calculerMontantsExamens();
        });


    /*
     * Valeur cotation
     */
    bloc.find('.examen-valeur')
        .on('input', function () {

            let valeur = getNumber(this.value);

            valeur = Math.max(1, valeur);

            this.value = valeur;

            /*
             * Recalcul du bloc
             */
            calculerBlocExamen(bloc);

            /*
             * Recalcul global
             */
            calculerMontantsExamens();
        });


    /*
     * Prix
     */
    bloc.find('.examen-prix')
        .on('input', function () {

            formaterChampMontant(this);

            /*
             * Recalcul du bloc
             */
            calculerBlocExamen(bloc);

            /*
             * Recalcul global
             */
            calculerMontantsExamens();
        });


    /*
     * Montant prélèvement
     */
    bloc.find('.montant-pre-examen')
        .on('input', function () {

            let valeur = getNumber(this.value);

            valeur = Math.max(0, valeur);

            this.value = formatExamenMoney(valeur);

            /*
             * Recalcul global
             */
            calculerMontantsExamens();
        });


    /*
     * Charge prélèvement
     */
    bloc.find('.charge-prelev')
        .on('change', function () {

            /*
             * Recalcul global immédiat
             */
            calculerMontantsExamens();
        });


    /*
     * Suppression
     */
    bloc.find('.btn-supprimer-examen')
        .on('click', function () {

            const nombreBlocs =
                $('#contenu_examen .examen-block').length;

            /*
             * Toujours garder au moins
             * un bloc.
             */
            if (nombreBlocs <= 1) {

                showAlert(
                    'Alert',
                    'Au moins un examen doit être renseigné.',
                    'info'
                );

                return;
            }

            /*
             * Suppression
             */
            bloc.remove();

            /*
             * Renumérotation
             */
            updateExamenIndexes();

            /*
             * Recalcul global
             */
            calculerMontantsExamens();
        });
}

function chargerExamensFamille(
    famille,
    bloc
) {

    if (!patientExamen) {
        return;
    }


    const codeassurance =
        patientExamen.codeassurance || '';


    const periode =
        $('#periode').val() || '';


    /*
     * IMPORTANT :
     *
     * Le cache dépend de :
     *
     * famille
     * assurance
     * période
     *
     */
    const cacheKey =
        `${famille}_${codeassurance}_${periode}`;


    /*
     * EXAMENS DÉJÀ EN CACHE
     */
    if (
        Object.prototype.hasOwnProperty.call(
            cachedExamens,
            cacheKey
        )
    ) {

        remplirExamensBloc(

            bloc,

            cachedExamens[cacheKey]

        );

        return;

    }


    /*
     * REQUÊTE
     */
    showExamenPreloader();


    $.ajax({

        url: examenUrl(
            '/api/select/examen'
        ),

        type: 'GET',

        dataType: 'json',

        data: {

            id: famille,

            codeassurance:
                codeassurance,

            periode:
                periode

        },


        success: function (data) {

            console.log(
                'Examens chargés :',
                data
            );


            if (
                Array.isArray(data.results) &&
                data.results.length > 0
            ) {

                /*
                 * Mise en cache
                 */
                cachedExamens[cacheKey] =
                    data.results;


                remplirExamensBloc(

                    bloc,

                    data.results

                );

            } else {

                showAlert(

                    'ALERT',

                    "Aucun examen n'a été trouvé.",

                    'warning'

                );

            }

        },


        error: function (
            xhr,
            status,
            error
        ) {

            console.error(

                'Erreur chargement examens :',

                error

            );


            console.error(

                'Réponse serveur :',

                xhr.responseText

            );


            showAlert(

                'ALERT',

                'Erreur lors du chargement des examens.',

                'error'

            );

        },


        complete: function () {

            removeExamenPreloader();

        }

    });

}

function remplirExamensBloc(
    bloc,
    examens
) {

    const select =
        bloc.find('.examen-select');


    select
        .empty()
        .append(

            $('<option>', {

                value: '',

                text: 'Sélectionner'

            })

        );


    examens.forEach(function (item) {

        select.append(

            $('<option>', {

                value:
                    item.numexam,

                text:
                    item.denomination,

                'data-cotation':
                    item.cot ?? 1,

                'data-code':
                    item.codfamexam ?? '',

                'data-valeur':
                    item.valeur ?? 0,

                'data-valeur-non-as':
                    item.valeur_non_as ?? 0,

                'data-tarif':
                    item.tarif ?? 0,

                'data-tarif-non-as':
                    item.tarif_non_as ?? 0

            })

        );

    });


    select.prop(
        'disabled',
        false
    );


    /*
     * Reinitialiser Select2
     */
    if (
        select.hasClass(
            'select2-hidden-accessible'
        )
    ) {

        select.select2('destroy');

    }


    select.select2({

        theme: 'bootstrap',

        width: '100%',

        placeholder: 'Sélectionner',

        language: {

            noResults: function () {

                return 'Aucun résultat trouvé';

            }

        }

    });

}

function selectionnerExamen(bloc) {

    const select =
        bloc.find('.examen-select');


    const option =
        select.find(':selected');


    if (!select.val()) {

        viderInformationsBloc(
            bloc
        );

        calculerMontantsExamens();

        return;
    }

    const cotation =
        Math.max(

            1,

            getNumber(
                option.attr(
                    'data-cotation'
                )
            ) || 1

        );

    const prix =
        getPrixExamen(
            bloc,
            option
        );


    /*
     * Code famille
     */
    bloc.find('.examen-cotation')
        .val(

            option.attr(
                'data-code'
            ) || ''

        );


    /*
     * Valeur cotation
     */
    bloc.find('.examen-valeur')
        .val(cotation);


    /*
     * Prix
     */
    bloc.find('.examen-prix')
        .val(

            formatExamenMoney(
                prix
            )

        );

    /*
     * Calcul
     */
    calculerBlocExamen(
        bloc
    );

}

function getPrixExamen(
    bloc,
    option
) {

    const priseEnCharge =
        bloc.find(
            '.examen-assurance'
        ).val();


    let prix = 0;


    if (
        priseEnCharge === 'oui'
    ) {

        prix =
            getNumber(
                option.attr(
                    'data-valeur'
                )
            );

    } else {

        prix =
            getNumber(
                option.attr(
                    'data-valeur-non-as'
                )
            );

    }


    return Math.max(
        0,
        prix
    );

}

function selectionnerAssuranceBloc(bloc) {

    const selectExamen =
        bloc.find('.examen-select');


    if (!selectExamen.val()) {

        bloc.find(
            '.examen-assurance'
        ).val('non');


        showAlert(

            'ALERT',

            'Veuillez sélectionner un examen.',

            'warning'

        );

        return;

    }


    const option =
        selectExamen.find(':selected');


    const prix =
        getPrixExamen(
            bloc,
            option
        );


    bloc.find('.examen-prix')
        .val(

            formatExamenMoney(
                prix
            )

        );

    {{-- calculerMontantsExamens(); --}}

    calculerBlocExamen(
        bloc
    );

}

function calculerBlocExamen(bloc)
{
    const select =
        bloc.find('.examen-select');


    /*
     * Aucun examen sélectionné
     */
    if (!select.val()) {

        bloc.find('.examen-montant')
            .val(
                formatExamenMoney(0)
            );

        return {

            montantExamen: 0,

            montantPrelevement: 0,

            total: 0,

            assurance: 0,

            patient: 0

        };
    }


    /* ========================================================
     * COTATION
     * ======================================================== */

    let cotation =
        getNumber(
            bloc.find(
                '.examen-valeur'
            ).val()
        );


    cotation =
        Math.max(
            1,
            cotation || 1
        );


    /* ========================================================
     * PRIX
     * ======================================================== */

    let prix =
        getNumber(
            bloc.find(
                '.examen-prix'
            ).val()
        );


    prix =
        Math.max(
            0,
            prix
        );


    /* ========================================================
     * MONTANT EXAMEN
     * ======================================================== */

    let montantExamen =
        prix * cotation;


    montantExamen =
        Math.max(
            0,
            montantExamen
        );


    /*
     * Mise à jour affichage
     */
    bloc.find('.examen-valeur')
        .val(cotation);


    bloc.find('.examen-prix')
        .val(
            formatExamenMoney(
                prix
            )
        );


    bloc.find('.examen-montant')
        .val(
            formatExamenMoney(
                montantExamen
            )
        );


    /* ========================================================
     * TAUX PATIENT
     * ======================================================== */

    const taux =
        Math.max(
            0,
            Math.min(
                100,
                Number(
                    patientExamen?.taux
                ) || 0
            )
        );


    /* ========================================================
     * ASSURANCE DE L'EXAMEN
     * ======================================================== */

    const assurance =
        bloc.find(
            '.examen-assurance'
        ).val();


    let montantAssurance = 0;

    let montantPatient = 0;


    if (
        assurance === 'oui' &&
        taux > 0
    ) {

        montantAssurance =
            Math.floor(
                (
                    montantExamen *
                    taux
                ) / 100
            );


        montantAssurance =
            Math.min(
                montantExamen,
                Math.max(
                    0,
                    montantAssurance
                )
            );


        montantPatient =
            Math.max(
                0,
                montantExamen -
                montantAssurance
            );

    } else {

        montantPatient =
            montantExamen;
    }


    /* ========================================================
     * PRÉLÈVEMENT
     *
     * UNIQUEMENT SI FAMILLE = B
     * ======================================================== */

    const famille =
        bloc.find(
            '.examen-famille'
        ).val();


    let montantPrelevement = 0;


    if (famille === 'B') {

        montantPrelevement =
            Math.max(
                0,
                getNumber(
                    bloc.find(
                        '.montant-pre-examen'
                    ).val()
                )
            );


        /*
         * Affichage normalisé
         */
        bloc.find(
            '.montant-pre-examen'
        ).val(
            formatExamenMoney(
                montantPrelevement
            )
        );


        const chargePrelevement =
            bloc.find(
                '.charge-prelev'
            ).val();


        /* ----------------------------------------------------
         * Prélèvement à la charge de l'assurance
         * ---------------------------------------------------- */

        if (
            chargePrelevement === 'assurance' &&
            taux > 0
        ) {

            {{-- let prelevementAssurance =
                Math.floor(
                    (
                        montantPrelevement *
                        taux
                    ) / 100
                );


            prelevementAssurance =
                Math.min(
                    montantPrelevement,
                    Math.max(
                        0,
                        prelevementAssurance
                    )
                );


            const prelevementPatient =
                Math.max(
                    0,
                    montantPrelevement -
                    prelevementAssurance
                ); --}}


            montantAssurance +=
                montantPrelevement;


            {{-- montantPatient +=
                prelevementPatient; --}}

        }

        /* ----------------------------------------------------
         * Prélèvement à la charge du patient
         * ---------------------------------------------------- */
        else {

            montantPatient +=
                montantPrelevement;

        }

    } else {

        /*
         * Sécurité :
         * une famille différente de B ne possède
         * jamais de prélèvement.
         */
        bloc.find(
            '.montant-pre-examen'
        ).val(
            formatExamenMoney(0)
        );

    }


    /* ========================================================
     * TOTAL DU BLOC
     * ======================================================== */

    const total =
        Math.max(
            0,
            montantExamen +
            montantPrelevement
        );


    return {

        montantExamen:
            montantExamen,

        montantPrelevement:
            montantPrelevement,

        total:
            total,

        assurance:
            Math.max(
                0,
                montantAssurance
            ),

        patient:
            Math.max(
                0,
                montantPatient
            )

    };
}

function calculerMontantsExamens()
{
    let montantTotal = 0;

    let montantAssurance = 0;

    let montantPatient = 0;


    /*
     * ========================================================
     * PARCOURS DES BLOCS
     * ========================================================
     */

    $('#contenu_examen .examen-block')
        .each(function () {

            const bloc =
                $(this);


            /*
             * Calcul complet du bloc
             */
            const resultat =
                calculerBlocExamen(
                    bloc
                );


            /*
             * Total
             */
            montantTotal +=
                Math.max(
                    0,
                    resultat.total
                );


            /*
             * Assurance
             */
            montantAssurance +=
                Math.max(
                    0,
                    resultat.assurance
                );


            /*
             * Patient
             */
            montantPatient +=
                Math.max(
                    0,
                    resultat.patient
                );

        });


    /* ========================================================
     * REMISE
     * ======================================================== */

    let remise =
        Math.max(
            0,
            getNumber(
                $('#taux_remise').val()
            )
        );


    /*
     * La remise ne peut pas dépasser
     * la part patient.
     */
    remise =
        Math.min(
            remise,
            montantPatient
        );


    montantPatient =
        Math.max(
            0,
            montantPatient -
            remise
        );


    /* ========================================================
     * AFFICHAGE
     * ======================================================== */

    $('#taux_remise')
        .val(
            formatExamenMoney(
                remise
            )
        );


    $('#montant_total_examen')
        .val(
            formatExamenMoney(
                montantTotal
            )
        );


    $('#montant_assurance_examen')
        .val(
            formatExamenMoney(
                montantAssurance
            )
        );


    $('#montant_patient_examen')
        .val(
            formatExamenMoney(
                montantPatient
            )
        ); 

    $('#patient_taux')
        .val(
            Number(
                patientExamen?.taux
            ) || 0
        );


    /* ========================================================
     * RETOUR
     * ======================================================== */

    return {

        total:
            Math.max(
                0,
                montantTotal
            ),

        assurance:
            Math.max(
                0,
                montantAssurance
            ),

        patient:
            Math.max(
                0,
                montantPatient
            ),

        remise:
            Math.max(
                0,
                remise
            )

    };
}

function afficherPrelevementSelonFamille(bloc)
{

    const famille =
        bloc.find(
            '.examen-famille'
        ).val();


    const divPrelevement =
        bloc.find(
            '.div-preleve-examen'
        );

    if (famille === 'B') {

        divPrelevement.show();

        const montantActuel =
            getNumber(
                bloc.find(
                    '.montant-pre-examen'
                ).val()
            );

        if (montantActuel === 0) {

            montantPrelevement(
                bloc
            );

        }

    } else {

        divPrelevement.hide();

        bloc.find(
            '.montant-pre-examen'
        ).val(
            formatExamenMoney(0)
        );

        bloc.find(
            '.charge-prelev'
        ).val('patient');
    }

    /*
     * Recalcul global
     */
    calculerMontantsExamens();
}

function montantPrelevement(bloc)
{
    if (
        !bloc ||
        !bloc.length
    ) {
        return;
    }


    $.ajax({

        url:
            $('#url').attr('content') +
            '/api/rech/prelevement',

        type: 'GET',

        dataType: 'json',

        success: function (response) {

            if (
                response &&
                response.prelevement
            ) {

                const prix =
                    Math.max(
                        0,
                        getNumber(
                            response
                                .prelevement
                                .prix
                        )
                    );


                /*
                 * Mettre le prix dans
                 * le bloc concerné.
                 */
                bloc.find(
                    '.montant-pre-examen'
                ).val(
                    formatExamenMoney(
                        prix
                    )
                );


                /*
                 * Recalcul global
                 */
                calculerMontantsExamens();

            }

        },

        error: function (xhr) {

            console.error(
                'Erreur prélèvement :',
                xhr.responseText
            );

        }

    });
}

function updateExamenIndexes() {

    $('#contenu_examen .examen-block')
        .each(function (index) {

            $(this)
                .find('h6')
                .first()
                .html(`

                    <i class="ri-test-tube-line me-1"></i>

                    EXAMEN ${index + 1}

                `);

        });


    if (
        $('#contenu_examen .examen-block')
            .length > 0
    ) {

        $('#div_btn_examen')
            .show();

    } else {

        $('#div_btn_examen')
            .hide();

    }
}

function viderInformationsBloc(bloc) {

    bloc.find('.examen-cotation')
        .val('');


    bloc.find('.examen-valeur')
        .val(1);


    bloc.find('.examen-prix')
        .val('');


    bloc.find('.examen-montant')
        .val('');
}

function formaterChampMontant(input) {

    const valeur =
        getNumber(
            input.value
        );


    input.value =
        formatExamenMoney(
            valeur
        );
}

function validerExamen(
    afficherMessage = true
) {

    /*
     * Patient
     */
    const patientId =
        $('#patient_id').val();


    if (!patientId) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                'Veuillez sélectionner un patient.',
                'warning'
            );

        }

        return false;

    }


    /*
     * Période
     */
    const periode =
        $('#periode').val();


    if (!periode) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                'Veuillez sélectionner une période.',
                'warning'
            );

        }

        return false;

    }


    /*
     * Médecin
     */
    const medecin =
        $.trim(
            $('#medecin').val()
        );


    if (!medecin) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                'Veuillez saisir le nom du médecin.',
                'warning'
            );

        }

        return false;

    }


    /*
     * Blocs
     */
    const blocs =
        $('#contenu_examen .examen-block');


    if (blocs.length === 0) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                "Veuillez ajouter au moins un examen.",
                'warning'
            );

        }

        return false;

    }


    const examensUtilises =
        new Set();


    let valide =
        true;


    blocs.each(function () {

        if (!valide) {
            return false;
        }


        const bloc =
            $(this);


        /*
         * Famille
         */
        const famille =
            bloc.find(
                '.examen-famille'
            ).val();


        if (!famille) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "Veuillez sélectionner le type d'examen de chaque bloc.",
                    'warning'
                );

            }

            valide = false;

            return false;

        }


        /*
         * Examen
         */
        const examen =
            bloc.find(
                '.examen-select'
            ).val();


        if (!examen) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "Veuillez sélectionner un examen dans chaque bloc.",
                    'warning'
                );

            }

            valide = false;

            return false;

        }


        /*
         * Pas de doublon
         */
        if (
            examensUtilises.has(
                examen
            )
        ) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "Le même examen ne peut pas être sélectionné plusieurs fois.",
                    'warning'
                );

            }

            valide = false;

            return false;

        }


        examensUtilises.add(
            examen
        );


        /*
         * Cotation
         */
        const cotation =
            getNumber(
                bloc.find(
                    '.examen-valeur'
                ).val()
            );


        if (cotation <= 0) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "La valeur de cotation doit être supérieure à zéro.",
                    'warning'
                );

            }

            valide = false;

            return false;

        }


        /*
         * Prix
         */
        const prix =
            getNumber(
                bloc.find(
                    '.examen-prix'
                ).val()
            );


        if (prix < 0) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    'Le prix ne peut pas être négatif.',
                    'warning'
                );

            }

            valide = false;

            return false;

        }


        /*
         * Montant
         */
        const montant =
            getNumber(
                bloc.find(
                    '.examen-montant'
                ).val()
            );


        if (montant <= 0) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "Le montant de chaque examen doit être supérieur à zéro.",
                    'warning'
                );

            }

            valide = false;

            return false;

        }

    });


    if (!valide) {
        return false;
    }


    /*
     * Calcul global
     */
    const montants =
        calculerMontantsExamens();


    /*
     * Vérification identité comptable
     *
     * Total = Assurance + Patient + Remise
     */
    const total =
        montants.total;


    const assurance =
        montants.assurance;


    const patient =
        montants.patient;


    const remise =
        montants.remise;


    if (
        total !==
        assurance +
        patient +
        remise
    ) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                'Les montants sont incohérents. Veuillez vérifier les données.',
                'warning'
            );

        }

        return false;

    }


    /*
     * Aucun négatif
     */
    if (
        total < 0 ||
        assurance < 0 ||
        patient < 0 ||
        remise < 0
    ) {

        if (afficherMessage) {

            showAlert(
                'ALERT',
                'Les montants ne peuvent pas être négatifs.',
                'warning'
            );

        }

        return false;

    }


    return true;

}

function construireSelectionsExamens() {

    const selections =
        [];


    $('#contenu_examen .examen-block')
        .each(function () {

            const bloc =
                $(this);


            const option =
                bloc.find(
                    '.examen-select option:selected'
                );


            if (!option.val()) {
                return;
            }


            selections.push({

                famille:
                    bloc.find(
                        '.examen-famille'
                    ).val(),

                id:
                    option.val(),

                examen:
                    option.text().trim(),

                assurance:
                    bloc.find(
                        '.examen-assurance'
                    ).val(),

                cotation:
                    getNumber(
                        bloc.find(
                            '.examen-valeur'
                        ).val()
                    ),

                valeur:
                    getNumber(
                        bloc.find(
                            '.examen-prix'
                        ).val()
                    ),

                montant:
                    getNumber(
                        bloc.find(
                            '.examen-montant'
                        ).val()
                    ),

                prelevement:
                    getNumber(
                        bloc.find(
                            '.montant-pre-examen'
                        ).val()
                    ),

                code:
                    option.attr(
                        'data-code'
                    ) || ''

            });

        });


    return selections;

}

function enregistrerExamen() {

    /*
     * Validation complète
     */
    if (!validerExamen(true)) {
        return false;
    }


    const selectionsExamen =
        construireSelectionsExamens();


    if (
        selectionsExamen.length === 0
    ) {

        showAlert(
            'ALERT',
            "Aucun examen n'a été sélectionné.",
            'warning'
        );

        return false;

    }


    const montants =
        calculerMontantsExamens();

    /*
     * Informations générales
     */
    const patientId =
        $('#patient_id').val();


    const periode =
        $('#periode').val();


    const medecin =
        $.trim(
            $('#medecin').val()
        );


    const numhosp =
        $.trim(
            $('#numhosp').val()
        );


    const numcode =
        $.trim(
            $('#numcode').val()
        );


    const rensg =
        $.trim(
            $('#rensg').val()
        );


    const taux =
        Number(
            $('#patient_taux').val()
        ) || 0;


    const remise =
        montants.remise;


    /*
     * Vérification finale des montants
     */
    if (
        montants.total <
        0 ||
        montants.assurance <
        0 ||
        montants.patient <
        0 ||
        remise < 0
    ) {

        showAlert(
            'ALERT',
            'Les montants sont invalides.',
            'warning'
        );

        return false;

    }


    /*
     * Payload
     */
    const data = {

        selectionsExamen:
            selectionsExamen,

        patient_id:
            patientId,

        codeassurance:
            patientExamen.codeassurance,

        codesocieteassure:
            patientExamen.codesocieteassure,

        periode:
            periode,

        medecin:
            medecin,

        numhosp:
            numhosp || null,

        numcode:
            numcode || null,

        rensg:
            rensg || null,

        taux:
            taux,

        montantA:
            montants.assurance,

        montantP:
            montants.patient,

        montantT:
            montants.total,

        remise:
            remise,

    };


    console.log(
        'Données examen :',
        data
    );

    /*
     * Loader
     */
    showExamenPreloader();


    requestWithCsrf(
            'POST',
            $('#url').attr('content') + '/api/examens/create',
            data
        )
        .done(function (response) {

            removeExamenPreloader();


            if (response.success) {

                resetVoletExamen();


                showAlert(
                    'ALERT',
                    'Opération effectuée.',
                    'success'
                );

                examendTable.page = 1;
                examendTable.load();

                const tab =
                    document.getElementById(
                        'tab-oneAAAD'
                    );


                if (tab) {

                    const newTab =
                        new bootstrap.Tab(tab);

                    newTab.show();

                }


            } else if (
                response.existe
            ) {

                showAlert(
                    'ALERT',
                    'Ce numéro de prise en charge existe déjà.',
                    'warning'
                );


            } else if (
                response.num_hosp_liberer
            ) {

                showAlert(
                    'ALERT',
                    "Le patient lié à ce numéro d'hospitalisation a déjà été libéré.",
                    'warning'
                );


            } else if (
                response.matricule_hosp_error
            ) {

                showAlert(
                    'ALERT',
                    "Le patient n'est pas lié à ce numéro d'hospitalisation.",
                    'warning'
                );


            } else if (
                response.num_hosp_introuvable
            ) {

                showAlert(
                    'ALERT',
                    "Le numéro d'hospitalisation est introuvable.",
                    'warning'
                );


            } else {

                showAlert(
                    'ERREUR',
                    "Une erreur est survenue lors de l'enregistrement.",
                    'error'
                );

            }

        })
        .fail(function (xhr, textStatus, errorThrown) {

            console.error('Erreur consultation :', {
                status: xhr?.status,
                statusText: xhr?.statusText,
                responseText: xhr?.responseText,
                textStatus: textStatus,
                errorThrown: errorThrown,
                readyState: xhr?.readyState
            });

            removeExamenPreloader();

            showAlert(
                'ERREUR',
                "Une erreur est survenue lors de l'enregistrement.",
                'error'
            );
        });

}

function resetVoletExamen() {

    /*
     * Patient
     */
    patientExamen = null;


    /*
     * Champs
     */
    $('#numhosp').val('');

    $('#periode')
        .val('')
        .trigger('change.select2');

    $('#medecin').val('');

    $('#numcode').val('');

    $('#rensg').val('');

    $('#patient_taux').val(0);

    $('#montant_pre_examen').val(0);

    $('#taux_remise').val(0);

    $('#montant_total_examen').val(0);

    $('#montant_patient_examen').val(0);

    $('#montant_assurance_examen').val(0);


    /*
     * Contenu
     */
    $('#contenu_examen')
        .empty();


    /*
     * Sections
     */
    $('#div_info_patient')
        .empty()
        .hide();

    $('#select_periode_div')
        .hide();

    $('#select_examen_div')
        .hide();

    $('#div_Examen')
        .hide();

    $('#div_btn_examen')
        .hide();

    $('#div_numcode')
        .hide();

    $('#div_preleve')
        .hide();


    /*
     * Assurance
     */
    $('#charge_prelev')
        .val('patient')
        .trigger('change.select2');


    /*
     * Cache :
     *
     * IMPORTANT :
     * on vide le cache car le patient,
     * son assurance ou sa période peuvent changer.
     */
    cachedExamens = {};

}

function allowOnlyNumbersAndLetters(event) {

    const key =
        event.key;


    /*
     * Autoriser les touches de contrôle
     */
    if (
        key === 'Backspace' ||
        key === 'Delete' ||
        key === 'Tab' ||
        key === 'ArrowLeft' ||
        key === 'ArrowRight'
    ) {

        return;

    }


    if (
        !/^[a-zA-Z0-9]+$/.test(key)
    ) {

        event.preventDefault();

    }

}

function allowOnlyLetters(event) {

    const key =
        event.key;


    if (
        !/^[a-zA-ZÀ-ÿ\s]+$/.test(key)
    ) {

        event.preventDefault();

    }

}

function allowOnlyNumbers(event) {

    const key =
        event.key;


    if (
        !/^[0-9]+$/.test(key)
    ) {

        event.preventDefault();

    }

}

// -----------------------------------------------

    });
</script>


@endsection
