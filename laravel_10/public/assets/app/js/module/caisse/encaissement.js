$(document).ready(function() {

	caisse_verf();

    // ============================================================
    // VALIDATION
    // ============================================================

    $('#btn_valider').on('click', function () {
        payer($('#voir_recu').is(':checked'));
    });

    // ============================================================
    // MONTANT À PAYER
    // ============================================================

    $('#input_montant_payer').on('input', function () {

        let value = $(this).val().replace(/\D/g, '') || '0';
        let formatted = formatPriceT(value);

        $(this).val(formatted);

        $('#input_montant_verser').val('0');
        $('#input_montant_remis').val('0');
        $('#input_montant_restant').val(formatted);
    });


    // ============================================================
    // MONTANT VERSÉ
    // ============================================================

    $('#input_montant_verser').on('input', function () {

        let rawValue = $(this).val().replace(/\D/g, '') || '0';

        $(this).val(formatPriceT(rawValue));

        const montantPayer =
            parseInt($('#input_montant_payer').val().replace(/\./g, ''), 10) || 0;

        const montantVerser =
            parseInt(rawValue, 10) || 0;


        // Montant remis
        const montantRemis = Math.max(
            montantVerser - montantPayer,
            0
        );


        // Montant restant
        const montantRestant = Math.max(
            montantPayer - montantVerser,
            0
        );


        $('#input_montant_remis').val(
            formatPriceT(montantRemis)
        );

        $('#input_montant_restant').val(
            formatPriceT(montantRestant)
        );
    });


    // ============================================================
    // AUTORISER UNIQUEMENT LES CHIFFRES
    // ============================================================

    $('#input_montant_verser').on('keypress', function (event) {

        if (!/[0-9]/.test(event.key)) {
            event.preventDefault();
        }
    });


    // Si le champ est vide
    $('#input_montant_verser').on('blur', function () {

        if ($(this).val().trim() === '') {
            $(this).val('0');
        }
    });


    // ============================================================
    // REMISE
    // ============================================================

    $('#montant_remise').on('input', function () {

        let value = $(this).val().replace(/\D/g, '') || '0';

        $(this).val(formatPriceT(value));
    });


    // ============================================================
    // ENREGISTRER LA REMISE
    // ============================================================

    $('#btn_eng_remise').on('click', att_Remise);

    function caisse_verf() {

        const $contenu = $('#contenu_caisse');

        // Loader
        $contenu.html(`
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-warning me-2 my-4"
                     role="status">
                </div>
                <strong>Chargement des données...</strong>
            </div>
        `);

        $.ajax({
            url: $('#url').attr('content') + '/api/verf_caisse',
            method: 'GET',

            success: function (data) {

                $contenu.html(`
                    <div class="p-4">

                        <!-- SOLDE -->
                        <div class="d-flex align-items-center justify-content-between mb-3">

                            <div>
                                <div class="text-muted small fw-semibold mb-1">
                                    SOLDE ACTUEL
                                </div>

                                <div class="d-flex align-items-baseline gap-2">
                                    <span id="h_solde" class="fs-3 fw-bold text-primary"></span>
                                </div>
                            </div>

                            <!-- Actualiser -->
                            <button id="btn_refresh_soldCaisse"
                                    type="button"
                                    class="btn btn-warning border rounded-circle d-flex
                                           align-items-center justify-content-center"
                                    style="width: 42px; height: 42px;"
                                    title="Actualiser le solde">

                                <i class="ri-loop-left-line fs-5 text-white"></i>

                            </button>

                        </div>

                        <div class="border-top my-3"></div>


                        <!-- CAISSE FERMÉE -->
                        <div id="btn_ouvert">

                            <div class="d-flex flex-column flex-sm-row
                                        align-items-sm-center
                                        justify-content-sm-between
                                        rounded-3 bg-light p-3 gap-3">

                                <div class="d-flex align-items-center">

                                    <div class="rounded-circle bg-danger
                                                d-flex align-items-center
                                                justify-content-center me-3"
                                         style="width:42px;height:42px;">

                                        <i class="ri-lock-line fs-5 text-white"></i>

                                    </div>

                                    <div>
                                        <div class="fw-semibold">
                                            Caisse fermée
                                        </div>

                                        <div class="text-muted small">
                                            Aucune opération en cours
                                        </div>
                                    </div>

                                </div>

                                <button id="btn_ouvert_C"
                                        type="button"
                                        class="btn btn-success rounded-3 px-3">

                                    <i class="ri-door-open-line me-1"></i>
                                    Ouvrir

                                </button>

                            </div>

                        </div>


                        <!-- CAISSE OUVERTE -->
                        <div id="btn_fermer">

                            <div class="d-flex flex-column flex-sm-row
                                        align-items-sm-center
                                        justify-content-sm-between
                                        rounded-3 bg-light p-3 gap-3">

                                <div class="d-flex align-items-center">

                                    <div class="rounded-circle bg-success
                                                d-flex align-items-center
                                                justify-content-center me-3"
                                         style="width:42px;height:42px;">

                                        <i class="ri-lock-unlock-line fs-5 text-white"></i>

                                    </div>

                                    <div>
                                        <div class="fw-semibold">
                                            Caisse ouverte
                                        </div>

                                        <div class="text-muted small">
                                            Les opérations sont autorisées
                                        </div>
                                    </div>

                                </div>

                                <button id="btn_fermer_C"
                                        type="button"
                                        class="btn btn-danger rounded-3 px-3">

                                    <i class="ri-door-close-line me-1"></i>
                                    Fermer

                                </button>

                            </div>

                        </div>

                    </div>
                `);


                // État de la caisse
                const ouverte = data.caisse.statut === 'ouvert';

                $('#h_solde').html(
                    formatPrice(data.caisse.montant) + ' Fcfa'
                );

                $('#btn_ouvert').toggle(!ouverte);
                $('#btn_fermer').toggle(ouverte);
                $('#div_caisse').toggle(ouverte);


                // Événements
                $(document).on('click', '#btn_refresh_soldCaisse', caisse_verf);
                $(document).on('click', '#btn_ouvert_C', caisse_ouvert);
                $(document).on('click', '#btn_fermer_C', caisse_fermer);

            },

            error: function (xhr, status, error) {

                console.error(
                    'Erreur lors du chargement de la caisse :',
                    error
                );

                $contenu.html(`
                    <div class="text-center text-muted p-4">
                        <i class="ri-error-warning-line fs-2 text-danger"></i>
                        <p class="mt-2 mb-0">
                            Impossible de charger les données de la caisse.
                        </p>
                    </div>
                `);

            }
        });
    }

    function caisse_ouvert() {

        window.showPreloader();

        $.ajax({

            url: $('#url').attr('content') + '/api/caisse_ouvert',

            method: 'GET',

            success: function (response) {

                window.hidePreloader();

                if (response.success) {

                    domCaisseOuvert();

                } else {

                    showAlert(
                        'Alert',
                        "Une erreur est survenue lors de l'ouverture de la caisse.",
                        'error'
                    );
                }

            },

            error: function () {

                window.hidePreloader();

                showAlert(
                    'Alert',
                    'Une erreur est survenue.',
                    'error'
                );
            }
        });
    }
    function caisse_fermer() {

        window.showPreloader();

        $.ajax({

            url: $('#url').attr('content') + '/api/caisse_fermer',

            method: 'GET',

            success: function (response) {

                window.hidePreloader();

                if (response.success) {

                    domCaisseFermer();

                } else {

                    showAlert(
                        'Alert',
                        'Une erreur est survenue lors de la fermeture de la caisse.',
                        'error'
                    );
                }

            },

            error: function () {

                window.hidePreloader();

                showAlert(
                    'Alert',
                    'Une erreur est survenue.',
                    'error'
                );
            }
        });
    }

    function domCaisseOuvert() {
        $('#div_caisse').show();
        $('#btn_ouvert').hide();
        $('#btn_fermer').show();
    }
    function domCaisseFermer(){
        $('#div_caisse').hide();
        $('#btn_ouvert').show();
        $('#btn_fermer').hide();
    }
    //-----------------------------------------------------------------------

    function att_Remise()
    {
        const acte = $("#acte_remise").val().trim();
        const numfac = $("#numfac_remise").val().trim();
        const montant = $("#montant_remise").val().replace(/[^0-9]/g, '');

        if(!acte || !numfac){
            showAlert('Alert', 'Veuillez remplir tous les champs','warning');
            return false;
        }

        if (numfac.startsWith("FCE")) {
            if (acte !== 'cons') {
                showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                return false;
            }
        } else if (numfac.startsWith("FCS")) {
            if (acte !== 'soins') {
                showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                return false;
            }
        } else if (numfac.startsWith("FCB")) {
            if (acte !== 'exam') {
                showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                return false;
            }
        } else if (numfac.startsWith("FCH")) {
            if (acte !== 'hosp') {
                showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                return false;
            }
        }else {
            showAlert('Alert', 'Veuillez vérifier le numéro de facture et l\'acte.', 'warning');
            return false;
        }


        if(montant <= 0){
            showAlert('Alert', 'Veuillez saisir le montant de la remise s\'il vous plaît !!!','warning');
            return false;
        }

        window.showPreloader();

        $.ajax({
            url: $('#url').attr('content') +'/api/attribution_remise/' + numfac,
            method: 'GET',
            data: { 
                montant: montant, 
                acte: acte
            },
            success: function(response) {

                window.hidePreloader();

                if (response.success) {

                    $("#acte_remise").val(null);
                    $("#numfac_remise").val(null);
                    $("#montant_remise").val(0);

                    showAlert('Succès', 'Remise éffectuée.','success');
                } else if (response.introuvable) {
                    showAlert('Alert', response.message,'info');
                } else if (response.impossible) {
                    showAlert('Alert', response.message,'info');
                } else if (response.error) {
                    showAlert('Alert', response.message,'warning');
                }

            },
            error: function(xhr, status, error) {
                window.hidePreloader();
                showAlert('Alert', 'Une erreur est survenue lors de l\'attribution.','error');
            }
        });
    }

    //-----------------------------------------------------------------------
    function initBtnPayer() {

        $('.Table_Cons, .Table_Exam, .Table_Hos, .Table_Soinsam')
            .off('click.payer', '#paye')
            .on('click.payer', '#paye', function () {

                const $btn = $(this);
                const reste = $btn.data('reste') || 0;

                $('#input_montant_payer').val(formatPriceT(reste));
                $('#input_montant_verser').val(0);
                $('#input_montant_remis').val(0);
                $('#input_montant_restant').val(formatPriceT(reste));

                $('#id_code_fac').val($btn.data('numfac'));
                $('#id').val($btn.data('id'));
                $('#matricule').val($btn.data('matricule'));
                $('#voir_recu').prop('checked', false);
            });
    }

    function payer(mode = false)
    {
        const id = $("#id").val();
        const numfac = $("#id_code_fac").val();
        const matricule = $("#matricule").val();
        const montant_verser = $("#input_montant_verser");
        const montant_remis = $("#input_montant_remis");
        const montant_restant = $("#input_montant_restant");
        const montant = $("#input_montant_payer");

        let url;

        if (numfac.startsWith("FCE")) {

            url = $('#url').attr('content') +'/api/facture_payer/' + numfac

        } else if (numfac.startsWith("FCS")) {
            
            url = $('#url').attr('content') +'/api/facture_payer_soinsam/' + numfac

        } else if (numfac.startsWith("FCB")) {
            
            url = $('#url').attr('content') +'/api/facture_payer_examen/' + numfac

        } else if (numfac.startsWith("FCH")) {
            
            url = $('#url').attr('content') +'/api/facture_payer_hos/' + numfac

        } else {
            showAlert('Alert', 'Impossible de récuperer le numéro de facture pour le paiement.', 'warning');
            return false;
        }

        if(!montant_verser.val().trim() || !montant_remis.val().trim() || !montant_restant.val().trim() || !montant.val().trim()){
            showAlert('Alert', 'Impossible d\'éffectuée le paiement.','error');
            return false;
        }

        if(montant_verser.val() <= 0){
            showAlert('Alert', 'Veuillez saisir un montant verser s\'il vous plaît !!!','warning');
            return false;
        }

        window.showPreloader();

        $.ajax({
            url: url,
            method: 'GET',
            data: { 
                id: id, 
                matricule: matricule, 
                montant: montant.val(), 
                montant_verser: montant_verser.val(), 
                montant_remis: montant_remis.val(),
                montant_restant: montant_restant.val(),
            },
            success: function(response) {

                window.hidePreloader();

                if (response.success) {

                    if (numfac.startsWith("FCE")) {

                        table_cons.ajax.reload(null, false);

                        const facture = response.facture;

                        if (mode) {pdfFactureRecuConsultation(facture);} 

                    } else if (numfac.startsWith("FCS")) {
                        
                        table_soinsam.ajax.reload(null, false);

                        const patient = response.patient;
                        const soins = response.soins;
                        const produit = response.produit;

                        if (mode) {pdfFactureRecuSoins(patient, soins, produit);}

                    } else if (numfac.startsWith("FCB")) {
                        
                        table_exam.ajax.reload(null, false);

                        const facture = response.facture;

                        const examen = response.examen;
                        const sumMontantEx = response.sumMontantEx;

                        if (mode) {pdfFactureRecuExamen(examen, facture, sumMontantEx)};

                    } else if (numfac.startsWith("FCH")) {
                        
                        table_hos.ajax.reload(null, false);

                        const hopital = response.hopital;
                        const prestation = response.prestation;

                        if (mode) {pdfFactureRecuhos(hopital, prestation)};
                    }

                    showAlert('Succès', 'Paiement éffectuée.','success');

                } else if (response.error) {
                    showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
                } else if (response.caisse_fermer) {
                    domCaisseFermer()
                    showAlert('Alert', 'La caisse est actuellement fermer, Veuillez ouvrir la caisse avant d\'éffectuer un encaissement.','info');
                }

            },
            error: function(xhr, status, error) {
                window.hidePreloader();
                showAlert('Alert', 'Une erreur est survenue lors du paiement.','error');
            }
        });
    }

    console.log('urlBase =', window.urlBase);

    const table_cons = $('.Table_Cons').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list/cons/impayer`,
                type: 'GET',

                success: function(response) {
                    callback({ data: response.data ?? [] });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network consultation.');
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
                data: 'numfac', 
                render: (data, type, row) => `
                <div class="d-flex align-items-center">
                    ${data}
                </div>`,
                searchable: true, 
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-warning';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-success';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient_reste',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            { 
                data: 'date',
                render: (data, type, row) => {
                    return data ? `${formatDateHeure(data)}` : 'Néant';
                },
                searchable: true,
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-inline-flex gap-1" style="font-size:10px;">
                        <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse" id="paye"
                            data-id="${row.idconsexterne}"
                            data-numfac="${row.numfac}"
                            data-matricule="${row.matricule_patient}"
                            data-reste="${row.part_patient_reste}"
                        >
                            <i class="ri-hand-coin-line"></i>
                        </a>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        searching: true,
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initBtnPayer();
        },
    });

    $('#btn_refresh_table_Cons').on('click', function () {
        table_cons.ajax.reload(); 
    });

    //-----------------------------------------------------------------------

    const table_exam = $('.Table_Exam').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list/examen/impayer`,
                type: 'GET',

                success: function(response) {
                    callback({ data: response.data ?? [] });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network Examen.');
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
                data: 'numfac', 
                render: (data, type, row) => `
                <div class="d-flex align-items-center">
                    ${data}
                </div>`,
                searchable: true, 
            },
            { 
                data: 'typedemande',
                render: (data, type, row) => `
                    <span class="badge ${data === 'analyse' ? 'bg-danger' : 'bg-primary'}">
                        ${data}
                    </span> `,
                searchable: true,
            },
            {
                data: 'prelevement',
                render: (data, type, row) => {
                    const value = data ? data : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'montant_examen',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'montant_total',
                render: (data, type, row) => {
                    const value = data ? data : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? data : 0;
                    const color = 'text-warning';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-success';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient_reste',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            { 
                data: 'date',
                render: (data, type, row) => {
                    return data ? `${formatDate(data)} à ${row.heure}` : 'Néant';
                },
                searchable: true,
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-inline-flex gap-1" style="font-size:10px;">
                        <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse" id="paye"
                            data-id="${row.id}"
                            data-numfac="${row.numfac}"
                            data-reste="${row.part_patient_reste}"
                            data-matricule="${row.matricule}"
                        >
                            <i class="ri-hand-coin-line"></i>
                        </a>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        searching: true,
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initBtnPayer();
        },
    });

    $('#btn_refresh_table_Exam').on('click', function () {
        table_exam.ajax.reload(null, false); 
    });

    //-----------------------------------------------------------------------

    const table_hos = $('.Table_Hos').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list/hos/impayer`,
                type: 'GET',

                success: function(response) {
                    callback({ data: response.data ?? [] });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network hospitalisation.');
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
                data: 'numfachospit', 
                render: (data, type, row) => `
                <div class="d-flex align-items-center">
                    ${data}
                </div>`,
                searchable: true, 
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-warning';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-success';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient_reste',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            { 
                data: 'date',
                render: (data, type, row) => {
                    return data ? `${formatDateHeure(data)}` : 'Néant';
                },
                searchable: true,
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-inline-flex gap-1" style="font-size:10px;">
                        <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse" 
                            id="paye"
                            data-id="${row.numhospit}"
                            data-numfac="${row.numfachospit}"
                            data-matricule="${row.matricule_patient}"
                            data-reste="${row.part_patient_reste}"
                        >
                            <i class="ri-hand-coin-line"></i>
                        </a>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        searching: true,
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initBtnPayer();
        },
    });

    $('#btn_refresh_table_Hos').on('click', function () {
        table_hos.ajax.reload(null, false); 
    });

    //-----------------------------------------------------------------------

    const table_soinsam = $('.Table_Soinsam').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list/soinsam/impayer`,
                type: 'GET',

                success: function(response) {
                    callback({ data: response.data ?? [] });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network Soins ambulatoire.');
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
                data: 'numfac', 
                render: (data, type, row) => `
                <div class="d-flex align-items-center">
                    ${data}
                </div>`,
                searchable: true, 
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'prototal',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'stotal',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-primary';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-success';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-warning';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient_reste',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-danger';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            { 
                data: 'date',
                render: (data, type, row) => {
                    return data ? `${formatDateHeure(data)}` : 'Néant';
                },
                searchable: true,
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-inline-flex gap-1" style="font-size:10px;">
                        <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#Caisse" 
                            id="paye"
                            data-id="${row.id_soins}"
                            data-numfac="${row.numfac}"
                            data-matricule="${row.matricule_patient}"
                            data-reste="${row.part_patient_reste}"
                        >
                            <i class="ri-hand-coin-line"></i>
                        </a>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        searching: true,
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initBtnPayer();
        },
    });

    $('#btn_refresh_table_Soinsam').on('click', function () {
        table_soinsam.ajax.reload(null, false); 
    });

});