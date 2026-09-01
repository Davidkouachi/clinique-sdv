$(document).ready(function() {

    // content_stat_fac
    // div_info_consul  urlBase content_stat_fac nbre_fac stat_bord_date stat_consultation
    // docActivity

    caisse_verf()

    Statistique();
    Activity_cons();
    stat_fac_day();
    datesearch();
    select_patient('#id_patient');
    select_assureur('#assureur_id_societe');
    assurance();
    select_taux('#patient_idtauxcouv_new');
    select_societe('#patient_codesocieteassure_new');
    select_filiation('#patient_codefiliation_new');

    // ------------------------------------------------------------------

    OffClick('#btn_eng_consultation', eng_consultation);
    OffClick('#btn_remiseForm', Reset);
    OffChange('#acte_id', select_list_typeacte);
    OffClick('#btn_eng_societe', eng_societe);
    OffClick('#btn_eng_assurance', eng_assurance);
    OffClick('#btn_eng_patient', eng_patient);
    OffClick('#deleteBtnCons', delete_cons);
    OffClick('#btn_refresh_stat_fac', stat_fac_day);
    OffClick('#btn_refresh_table', stat_fac_day);

    // ------------------------------------------------------------------

    OffChange('#stat_bord_date', function () {
        // Afficher le préchargeur
        showPreloader();

        $('#date_bord_text').text(formatDate($(this).val()));

        Statistique();
    });

    OffClick('#btn_toggle_stat', function (e) {

        e.preventDefault();

        const $btn = $(this);

        const $statDate = $('#stat_consultation_date');
        const $stat = $('#stat_consultation');

        const isVisible = $stat.is(':visible');

        if (!isVisible) {

            // Afficher
            $statDate.show();

            $stat
                .empty()
                .show()
                .css({
                    height: 'auto',
                    overflowY: 'hidden'
                });

            $btn
                .removeClass('btn-primary')
                .addClass('btn-danger')
                .html(`
                    <i class="ri-eye-off-line me-1"></i>
                    Cacher les statistiques
                `);

        } else {

            // Cacher
            $statDate.hide();

            $stat
                .empty()
                .hide()
                .css({
                    height: 'auto',
                    overflowY: 'hidden'
                });

            $btn
                .removeClass('btn-danger')
                .addClass('btn-primary')
                .html(`
                    <i class="ri-bar-chart-line me-1"></i>
                    Afficher les statistiques
                `);
        }

    });

    OffClick('#btn_search_stat_const_date', function () {
        $("#stat_consultation").show();
        Statistique_cons();
    });

    OffChange('#searchDate1', function () {
        const date1Value = $(this).val();
        $('#searchDate2')
            .attr('min', date1Value)
            .val(date1Value);
    });

    OffChange('#id_patient', function () {
        rech_dosier();
    });

    function datesearch() {
        const date1Value = $('#searchDate1').val();
        $('#searchDate2').attr('min', date1Value);
    }

    // ------------------------------------------------------------------

    var numberInput = [
        '#patient_tel_new',
        '#patient_tel2_new',
        '#patient_telu_new',
        '#patient_telu2_new',
        '#tel_assurance_new',
        '#mumcode'
    ];

    numberInput.forEach(function (id) {
        numberTel(id);
    });

    OffChange('#assure', function () {
        if ($(this).val() === '1') {
            $('#div_assurer').css('display', 'flex');
        } else {
            $('#div_assurer').css('display', 'none');
        }
    });

    OffInput('#taux_remise', function () {
        let montant_total = $('#montant_total').val();

        if (montant_total < 0) {
            showAlert('Alert', 'Veuillez vérifier le montant Total.', 'warning');
            return false;
        }

        $(this).val(formatPrice($(this).val()));

        $('#div_remise_appliq').css('display', 'none');
    });

    OffChange('#typeacte_idS', function () {
        if ($(this).val() !== '') {
            $('#div_remise').css('display', 'block');
        } else {
            $('#div_remise').css('display', 'none');
        }
    });

    OffChange('#assurance_utiliser', function () {
        if ($(this).val() === 'non') {
            $('#div_numcode').css('display', 'none');
            $('#mumcode').val('');
        } else {
            $('#div_numcode').css('display', 'block');
            $('#mumcode').val('');
        }
    });

    OffInput('#taux_remise', function () {
        const rawValue = $(this).val().replace(/[^0-9]/g, '');
        const formattedValue = formatPrice(rawValue);
        $(this).val(formattedValue);

        const appliq_remise = $('#appliq_remise').val();
        const assuranceUtiliser = $('#assurance_utiliser').val();

        if (appliq_remise === 'patient' || assuranceUtiliser === 'non') {

            const montant_patient = parseInt($('#montant_patient_hidden').val().replace(/\./g, '')) || 0;
            const remise = parseInt(rawValue) || 0;
            const montantRemis = montant_patient - remise;

            $('#montant_patient').val(formatPriceT(montantRemis));

        } else if (assuranceUtiliser === 'oui') {

            const montant_assurance = parseInt($('#montant_assurance_hidden').val().replace(/\./g, '')) || 0;
            const remise = parseInt(rawValue) || 0;
            const montantRemis = montant_assurance - remise;

            $('#montant_assurance').val(formatPriceT(montantRemis));
        }

        let assurance = parseInt($('#montant_assurance').val().replace(/[^0-9]/g, '')) || 0;
        let patient = parseInt($('#montant_patient').val().replace(/[^0-9]/g, '')) || 0;
        let total = assurance + patient;

        $('#montant_total_acte').val(formatPriceT(total));
    });

    OffChange('#appliq_remise', function () {
        $('#montant_assurance').val(formatPrice($('#montant_assurance_hidden').val()));
        $('#montant_patient').val(formatPrice($('#montant_patient_hidden').val()));

        const rawValue = $('#taux_remise').val().replace(/[^0-9]/g, '');
        const assuranceUtiliser = $('#assurance_utiliser').val();

        if ($(this).val() === 'patient' || assuranceUtiliser === 'non') {

            const montant_patient = parseFloat($('#montant_patient_hidden').val().replace(/\./g, '')) || 0;
            const remise = parseFloat(rawValue) || 0;
            const montantRemis = montant_patient - remise;

            $('#montant_patient').val(formatPriceT(montantRemis));

        } else if (assuranceUtiliser === 'oui') {

            const montant_assurance = parseFloat($('#montant_assurance_hidden').val().replace(/\./g, '')) || 0;
            const remise = parseFloat(rawValue) || 0;
            const montantRemis = montant_assurance - remise;

            $('#montant_assurance').val(formatPriceT(montantRemis));
        }
    });

    // ------------------------------------------------------------------

    function caisse_verf() {

        const $contenu = $('#contenu_caisse');

        $contenu.html('');

        const contenu0 = `
            <div class="p-0 w-100 h-100">

                <!-- ================= SOLDE ================= -->
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div>

                        <!-- Libellé -->
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 7px;
                            margin-bottom: 6px;
                            color: #7c8984;
                            font-size: .78rem;
                            font-weight: 500;
                        ">
                            <i class="ri-wallet-3-line"
                               style="
                                   color: #087f5b;
                                   font-size: 15px;
                               ">
                            </i>

                            <span>Solde actuel</span>
                        </div>

                        <!-- Montant -->
                        <div style="
                            display: flex;
                            align-items: baseline;
                            gap: 7px;
                        ">
                            <span id="h_solde"
                                  style="
                                      color: #263630;
                                      font-size: 1.75rem;
                                      font-weight: 700;
                                      line-height: 1.1;
                                      letter-spacing: -.5px;
                                  ">
                            </span>

                            <span style="
                                color: #087f5b;
                                font-size: .8rem;
                                font-weight: 600;
                            ">
                                Fcfa
                            </span>
                        </div>

                    </div>


                    <!-- Icône solde -->
                    <div style="
                        width: 52px;
                        height: 52px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 15px;
                        background: #e9f6f1;
                        border: 1px solid #d5ebe2;
                    ">
                        <i class="ri-money-dollar-circle-line"
                           style="
                               color: #087f5b;
                               font-size: 25px;
                           ">
                        </i>
                    </div>

                </div>


                <!-- ================= SÉPARATEUR ================= -->
                <div style="
                    height: 1px;
                    background: #e8efec;
                    margin: 0 0 18px 0;
                ">
                </div>


                <!-- ================= ÉTAT CAISSE ================= -->

                <!-- Caisse fermée -->
                <div id="btn_ouvert" style="display: none;">

                    <div style="
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 15px;
                        padding: 14px 16px;
                        border: 1px solid #f1dada;
                        border-radius: 14px;
                        background: #fff8f8;
                    ">

                        <!-- État -->
                        <div style="
                            display: flex;
                            align-items: center;
                            min-width: 0;
                        ">

                            <div style="
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 12px;
                                border-radius: 12px;
                                background: #feecec;
                                color: #dc5c5c;
                            ">
                                <i class="ri-lock-line" style="font-size: 20px;"></i>
                            </div>

                            <div style="min-width: 0;">

                                <div style="
                                    color: #263630;
                                    font-size: .84rem;
                                    font-weight: 700;
                                    margin-bottom: 3px;
                                ">
                                    Caisse fermée
                                </div>

                                <div style="
                                    color: #929d98;
                                    font-size: .72rem;
                                ">
                                    Aucune opération en cours
                                </div>

                            </div>

                        </div>


                        <!-- Bouton -->
                        <button id="btn_ouvert_C"
                                type="button"
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 5px;
                                    padding: 9px 15px;
                                    border: 0;
                                    border-radius: 9px;
                                    background: #087f5b;
                                    color: #ffffff;
                                    font-size: .76rem;
                                    font-weight: 600;
                                    white-space: nowrap;
                                ">

                            <i class="ri-door-open-line"></i>
                            Ouvrir

                        </button>

                    </div>

                </div>


                <!-- Caisse ouverte -->
                <div id="btn_fermer" style="display: none;">

                    <div style="
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 15px;
                        padding: 14px 16px;
                        border: 1px solid #d5ebe2;
                        border-radius: 14px;
                        background: #f4fbf8;
                    ">

                        <!-- État -->
                        <div style="
                            display: flex;
                            align-items: center;
                            min-width: 0;
                        ">

                            <div style="
                                width: 44px;
                                height: 44px;
                                min-width: 44px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin-right: 12px;
                                border-radius: 12px;
                                background: #e2f4ed;
                                color: #087f5b;
                            ">
                                <i class="ri-lock-unlock-line" style="font-size: 20px;"></i>
                            </div>

                            <div style="min-width: 0;">

                                <div style="
                                    display: flex;
                                    align-items: center;
                                    gap: 7px;
                                    color: #263630;
                                    font-size: .84rem;
                                    font-weight: 700;
                                    margin-bottom: 3px;
                                ">

                                    <span>Caisse ouverte</span>

                                    <span style="
                                        width: 6px;
                                        height: 6px;
                                        border-radius: 50%;
                                        background: #087f5b;
                                        display: inline-block;
                                    ">
                                    </span>

                                </div>

                                <div style="
                                    color: #7c8984;
                                    font-size: .72rem;
                                ">
                                    Les opérations sont autorisées
                                </div>

                            </div>

                        </div>


                        <!-- Bouton -->
                        <button id="btn_fermer_C"
                                type="button"
                                style="
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 5px;
                                    padding: 9px 15px;
                                    border: 0;
                                    border-radius: 9px;
                                    background: #dc5c5c;
                                    color: #ffffff;
                                    font-size: .76rem;
                                    font-weight: 600;
                                    white-space: nowrap;
                                ">

                            <i class="ri-door-close-line"></i>
                            Fermer

                        </button>

                    </div>

                </div>

            </div>
        `;


        const message = `
            <div id="message_stat_acte">
                <p class="text-center">
                    Aucune donnée n'a été trouvée
                </p>
            </div>
        `;


        const loader = `
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-warning me-2"
                     role="status"
                     aria-hidden="true">
                </div>

                <strong>Chargement des données...</strong>
            </div>
        `;


        // Afficher le loader
        $contenu.html(loader);


        // =========================
        // RÉCUPÉRATION DE LA CAISSE
        // =========================

        $.ajax({

            url: $('#url').attr('content') + '/api/verf_caisse',

            method: 'GET',

            dataType: 'json',

            success: function(data) {

                // Injecter le contenu
                $contenu.html(contenu0);


                // Afficher le solde
                $('#h_solde').html(
                    formatPrice(data.caisse.montant) + ' Fcfa'
                );


                // =========================
                // ÉTAT DE LA CAISSE
                // =========================

                if (data.caisse.statut === 'ouvert') {

                    // Caisse ouverte
                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();

                } else {

                    // Caisse fermée
                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();
                }


                // =========================
                // ÉVÉNEMENTS
                // =========================

                $('#btn_ouvert_C')
                    .off('click')
                    .on('click', caisse_ouvert);


                $('#btn_fermer_C')
                    .off('click')
                    .on('click', caisse_fermer);


                $('#btn_refresh_soldCaisse')
                    .off('click')
                    .on('click', caisse_verf);

            },

            error: function(xhr, status, error) {

                console.error(
                    'Erreur lors du chargement des données caisse :',
                    error
                );

                $contenu.html(message);
            }

        });
    }

    function caisse_ouvert() {

        window.preloader('start');

        $.ajax({

            url: $('#url').attr('content') + '/api/caisse_ouvert',

            method: 'GET',

            dataType: 'json',

            success: function(response) {

                window.preloader('end');


                if (response.success) {

                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();

                    caisse_verf();

                    showAlert(
                        'Succès',
                        'La caisse a été ouverte.',
                        'success'
                    );

                }

                else if (response.deja) {

                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();

                    caisse_verf();

                    showAlert(
                        'Alert',
                        'La caisse est déjà ouverte.',
                        'info'
                    );

                }

                else if (response.error) {

                    showAlert(
                        'Alert',
                        "Une erreur est survenue lors de l'ouverture de la caisse.",
                        'error'
                    );

                    console.log(
                        'Message erreur contrôleur : ' + response.message
                    );
                }

            },

            error: function(xhr, status, error) {

                window.preloader('end');

                showAlert(
                    'Alert',
                    'Une erreur est survenue.',
                    'error'
                );

                const errorMessage =
                    xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Une erreur est survenue.';

                console.log(
                    'Message erreur contrôleur : ' + errorMessage
                );
            }

        });
    }

    function caisse_fermer() {

        window.preloader('start');

        $.ajax({

            url: $('#url').attr('content') + '/api/caisse_fermer',

            method: 'GET',

            dataType: 'json',

            success: function(response) {

                window.preloader('end');


                if (response.success) {

                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();

                    caisse_verf();

                    showAlert(
                        'Succès',
                        'La caisse a été fermée.',
                        'success'
                    );

                }

                else if (response.deja) {

                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();

                    caisse_verf();

                    showAlert(
                        'Alert',
                        'La caisse est déjà fermée.',
                        'info'
                    );

                }

                else if (response.error) {

                    showAlert(
                        'Alert',
                        'Une erreur est survenue lors de la fermeture de la caisse.',
                        'error'
                    );

                    console.log(
                        'Message erreur contrôleur : ' + response.message
                    );
                }

            },

            error: function(xhr, status, error) {

                window.preloader('end');

                showAlert(
                    'Alert',
                    'Une erreur est survenue.',
                    'error'
                );

                const errorMessage =
                    xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Une erreur est survenue.';

                console.log(
                    'Message erreur contrôleur : ' + errorMessage
                );
            }

        });
    }

    function rech_dosier()
    {
        $('#div_typeacteS, #div_medecin').show();

        $('#montant_assurance').val('0');
        $('#taux_remise').val('0');
        $('#montant_total').val('0');
        $('#montant_patient').val('0');

        const id_patient = $('#id_patient').val();

        if (!id_patient || !id_patient.trim()) {
            showAlert('Alert', 'Veuillez saisie le nom d\'un du patient.', 'warning');
            return false;
        }

        showPreloader();

        window.api_rech_dossier(
            id_patient,
            function (response) {

                hidePreloader();

                if (response.existep) {
                    showAlert('Alert', 'Ce patient n\'existe pas.', 'error');
                    Reset();
                    return;
                }

                if (response.success) {

                    $('#medecin_id').val('').trigger('change');

                    addGroup(response.patient);

                    select_list_medecin('#medecin_id')

                    if (response.patient.assure == 1) {
                        $('#input_part_assurance, #div_assurance_utiliser, #div_numcode').show();
                    } else {
                        $('#input_part_assurance, #div_assurance_utiliser, #div_numcode').hide();
                    }

                    select_list_typeacte();
                }
            },
            function () {
                hidePreloader();
                showAlert('Alert', 'Une erreur est survenue lors de la recherche.', 'error');
            }
        );
    }

    function addGroup(data)
    {
        const $dynamicFields = $('#div_info_patient');
        $dynamicFields.empty();

        let groupe = `
            <div class="col-12 mb-3">
                <h5 class="card-title">Information du patient</h5>
            </div>
            <div class="row gx-3">
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">N° dossier</label>
                        <input id="patient_numdossier" value="${data.numdossier}" readonly class="form-control">
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Nom et Prénoms</label>
                        <input value="${data.nomprenomspatient}" readonly class="form-control">
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input value="${data.telpatient}" readonly class="form-control">
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Assurer</label>
                        <input value="${data.assure == 1 ? 'Oui' : 'Non'}" readonly class="form-control">
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6 d-none">
                    <input id="patient_codeassurance" value="${data.codeassurance}" readonly class="form-control">
                </div>
        `;

        if (data.assure == 1) {
            groupe += `
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <label class="form-label">Assurance</label>
                    <input value="${data.assurance}" readonly class="form-control">
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <label class="form-label">Matricule assurance</label>
                    <input value="${data.matriculeassure}" readonly class="form-control">
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <label class="form-label">Taux</label>
                    <div class="input-group">
                        <input id="patient_taux" value="${data.taux}" readonly class="form-control">
                        <span class="input-group-text">%</span>
                    </div>
                </div>

                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <label class="form-label">Société</label>
                    <input value="${data.societe}" readonly class="form-control">
                </div>
            `;
        } else {
            groupe += `
                <div class="d-none">
                    <input id="patient_taux" value="0" readonly>
                </div>
            `;
        }

        groupe += `</div>`;

        groupe += ` 
            <div class="mb-3 mt-4">
                <h5 class="card-title">
                    ACTE A EFFECTUER
                </h5>
            </div>
            <div class="row gx-3 mb-4">
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Période</label>
                        <select class="form-select select2" id="periode">
                            <option>Selectionner</option>
                            <option value="0">Jour</option>
                            <option value="1">Nuit</option>
                            <option value="2">Férier</option>
                        </select>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_typeacteS" style="display: block;">
                    <div class="mb-3">
                        <label class="form-label">Acte</label>
                        <select class="form-select select2" id="typeacte_idS">
                        </select>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_medecin" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Medecin</label>
                        <select class="form-select select2" id="medecin_id">
                        </select>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_numcode" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Numéro de bon</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                N°
                            </span>
                            <input type="tel" class="form-control" id="mumcode" placeholder="Facultatif">
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_assurance_utiliser" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Utilisé l'assurance</label>
                        <select class="form-select" id="assurance_utiliser">
                            <option selected value="oui">Oui</option>
                            <option value="non">Non</option>
                        </select>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Montant Total</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="montant_total">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        groupe += ` 
            <div class="mb-3">
                <h5 class="card-title">Information Caisse</h5>
            </div>
            <div class="row gx-3 mb-4">
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="input_part_assurance" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Part Assurance</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="montant_assurance">
                            <input type="hidden" class="form-control" id="montant_assurance_hidden">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Part Patient</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="montant_patient">
                            <input type="hidden" class="form-control" id="montant_patient_hidden">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">
                        <label class="form-label">Montant Total</label>
                        <div class="input-group">
                            <input readonly="" type="tel" class="form-control" id="montant_total_acte">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_remise" style="display: block;">
                    <div class="mb-3">
                        <label class="form-label">Remise</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="taux_remise" value="0">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_remise_appliq" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label">Application de la remise</label>
                        <select class="form-select" id="appliq_remise">
                            <option selected value="patient">Patient</option>
                            {{-- <option value="assurance">Assurance</option> --}}
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="d-flex gap-2 justify-content-center">
                        <a id="btn_remiseForm" class="btn btn-outline-danger">
                            Rémise à zéro
                        </a>
                        <button id="btn_eng_consultation" class="btn btn-success">
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        `;

        $dynamicFields.append(groupe);
    }

    function Reset()
    {
        $('#div_info_patient').empty();

        $('#periode').val('').trigger('change.select2');
        $('#typeacte_id').val('').trigger('change.select2');
        $('#id_patient').val('').trigger('change.select2');
        $('#medecin_id').val('').trigger('change.select2');
        $('#appliq_remise').val('patient').trigger('change.select2');
        $('#taux_remise').val(0);

        // select_patient('#id_patient');
    }

    // ------------------------------------------------------------------

    function assurance() 
    {
        select_assurance('#codeassurance_societe');
        select_assurance('#patient_codeassurance_new');
    }

    function select_list_typeacte() {

        const divTypeActe = $('#div_typeacteS'); 
        const divMedecin = $('#div_medecin');
        const typeActeSelect = $('#typeacte_idS');

        const montant_assurance = $('#montant_assurance');
        const taux_remise = $('#taux_remise');
        const montant_total = $('#montant_total');
        const montant_patient = $('#montant_patient');

        const montant_total_acte = $('#montant_total_acte');

        const montant_patient_hidden = $('#montant_patient_hidden');
        const montant_assurance_hidden = $('#montant_assurance_hidden');

        // Reset des champs
        montant_assurance.val('');
        montant_total.val('');
        montant_patient.val('');

        const codeassurance = $('#patient_codeassurance').val();
        const patient_taux = $('#patient_taux');

        typeActeSelect.empty();
        divTypeActe.hide();
        divMedecin.hide();

        // Ajouter option par défaut
        typeActeSelect.append($('<option>', { value: '', text: 'Sélectionner' }));

        // Appel API séparé
        api_select_list_typeacte(
            codeassurance,
            function (response) {
                const data = response.typeacte;

                if (data && data.length > 0) {
                    data.forEach(function (item) {
                        typeActeSelect.append($('<option>', {
                            value: item.codgaran,
                            text: item.libgaran,
                            'data-prixj': item.prixj,
                            'data-prixn': item.prixn,
                            'data-prixf': item.prixf
                        }));
                    });

                    divTypeActe.show();
                    divMedecin.show();
                } else {
                    typeActeSelect.append($('<option>', {
                        value: '',
                        text: 'Aucun données disponible'
                    }));
                    divTypeActe.hide();
                }
            },
            function () {
                console.error("Erreur lors du chargement des types d'actes");
            }
        );

        // Gestion des événements (on utilise OffChange si tu veux)
        const periode = $('#periode');
        const appliq_remise = $('#appliq_remise');
        const auS = $('#assurance_utiliser');

        // Lorsque l'utilisateur sélectionne un type d'acte
        OffChange('#typeacte_idS', function () {

            if (periode.val() === '') {
                showAlert('Alert', 'Veuillez selectionner la période.', 'info');
                return;
            }

            const selectedOption = $(this).find('option:selected');
            let prix;

            if (periode.val() == 0) prix = selectedOption.data('prixj');
            else if (periode.val() == 1) prix = selectedOption.data('prixn');
            else if (periode.val() == 2) prix = selectedOption.data('prixf');

            if (prix) {
                calculateAndFormatAmounts(prix, patient_taux.val());
            } else {
                montant_total.val('');
                montant_assurance.val('');
                montant_patient.val('');
            }
        });

        // Lorsque l'utilisateur change l'assurance
        OffChange('#assurance_utiliser', function () {
            if (periode.val() === '' || typeActeSelect.val() === '') {
                showAlert('Alert', 'Veuillez selectionner la période et l\'acte.', 'info');
                return;
            }

            let prix = $('#montant_total').val().replace(/[^0-9]/g, '');
            taux_remise.val(0);

            if (prix) {
                if (this.value === 'oui') {
                    appliq_remise.find('option[value="assurance"]').show();
                    calculateAndFormatAmounts(prix, patient_taux.val());
                } else {
                    appliq_remise.val('patient');
                    appliq_remise.find('option[value="assurance"]').hide();
                    calculateAndFormatAmounts(prix, 0);
                }
            } else {
                montant_total.val('');
                montant_assurance.val('');
                montant_patient.val('');
            }
        });

        // Lorsque l'utilisateur change la période
        OffChange('#periode', function () {
            const selectedOption = typeActeSelect.find('option:selected');
            let prix;

            if (this.value == 0) prix = selectedOption.data('prixj');
            else if (this.value == 1) prix = selectedOption.data('prixn');
            else if (this.value == 2) prix = selectedOption.data('prixf');

            taux_remise.val(0);

            if (prix) {
                if (auS.val() === 'oui') {
                    appliq_remise.find('option[value="assurance"]').show();
                    calculateAndFormatAmounts(prix, patient_taux.val());
                } else {
                    appliq_remise.val('patient');
                    appliq_remise.find('option[value="assurance"]').hide();
                    calculateAndFormatAmounts(prix, 0);
                }
            } else {
                montant_total.val('');
                montant_assurance.val('');
                montant_patient.val('');
            }
        });

        // Montant total input
        OffInput('#montant_total', function () {
            const rawValue = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatPrice(rawValue));

            if (periode.val() === '' || typeActeSelect.val() === '') return;

            let prix = rawValue;
            taux_remise.val(0);

            if ($('#assurance_utiliser').val() === 'oui') {
                appliq_remise.find('option[value="assurance"]').show();
                calculateAndFormatAmounts(prix, patient_taux.val());
            } else {
                appliq_remise.val('patient');
                appliq_remise.find('option[value="assurance"]').hide();
                calculateAndFormatAmounts(prix, 0);
            }
        });

        // Montant patient input
        OffInput('#montant_patient', function () {
            const rawValue = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatPrice(rawValue));

            if (!rawValue) {
                $(this).val(0);
                montant_patient_hidden.val(0);
                return;
            }

            let assurance = parseInt(montant_assurance.val().replace(/[^0-9]/g, '')) || 0;
            let patient = parseInt(rawValue);
            montant_total_acte.val(formatPriceT(assurance + patient));
            montant_patient_hidden.val(formatPriceT(patient));
        });

        // Montant assurance input
        OffInput('#montant_assurance', function () {
            const rawValue = $(this).val().replace(/[^0-9]/g, '');
            $(this).val(formatPrice(rawValue));

            if (!rawValue) {
                $(this).val(0);
                montant_assurance_hidden.val(0);
                return;
            }

            let patient = parseInt(montant_patient.val().replace(/[^0-9]/g, '')) || 0;
            let assurance = parseInt(rawValue);
            montant_total_acte.val(formatPriceT(patient + assurance));
            montant_assurance_hidden.val(formatPriceT(assurance));
        });
    }

    function calculateAndFormatAmounts(prix, patient_taux) {
        // Vérifiez si le prix est défini et non null
        if (prix) {
            // console.log('Prix:', prix);
            // Assurez-vous que prix est une chaîne
            if (typeof prix !== 'string') {
                prix = prix.toString();
            }

            // Supprimez les séparateurs (ex : 1.000,00 => 100000)
            let prixFloat = parseFloat(prix.replace(/[.,]/g, ''));

            // Vérifiez si la conversion est valide
            if (isNaN(prixFloat)) {
                // console.error('Invalid price value:', prix);
                $('#montant_total').val(''); // Vider le champ si le prix est invalide
                return;
            }

            // Formater et afficher le prix total
            $('#montant_total').val(formatPrice(prixFloat.toString()));

            $('#montant_total_acte').val(formatPriceT(prixFloat));

            // Vérifiez si l'assurance est utilisée
            const au = $('#assurance_utiliser');
            let tauxFloat = parseFloat(patient_taux);

            if (au.val() === 'non') {
                tauxFloat = 0; // Pas d'assurance utilisée
            } else if (isNaN(tauxFloat) || tauxFloat < 0 || tauxFloat > 100) {
                // console.warn('Invalid patient_taux value:', patient_taux);
                tauxFloat = 0; // Défaut : pas de taux
            }

            // Calcul des montants
            let montantAssurance = 0;
            let montantPatient = 0;

            if (tauxFloat === 0) {
                montantPatient = prixFloat;
            } else {
                montantAssurance = Math.round((tauxFloat / 100) * prixFloat);
                montantPatient = Math.round(prixFloat - montantAssurance);
            }

            // Mettez à jour les champs correspondants
            $('#montant_assurance').val(formatPrice(montantAssurance.toString()));
            $('#montant_patient').val(formatPrice(montantPatient.toString()));
            $('#montant_patient_hidden').val(formatPrice(montantPatient.toString()));
            $('#montant_assurance_hidden').val(formatPrice(montantAssurance.toString()));
        } else {
            // Si aucun prix n'est défini, vider les champs
            $('#montant_total').val('');
            $('#montant_assurance').val('');
            $('#montant_patient').val('');
            $('#montant_patient_hidden').val('');
            $('#montant_assurance_hidden').val('');
        }
    }

    // ------------------------------------------------------------------

    function eng_societe() 
    {
        const nom = $("#nom_societe");
        const codeassurance= $("#codeassurance_societe");
        const assureur_id = $("#assureur_id_societe");

        if (!codeassurance.val().trim() || !nom.val().trim() || !assureur_id.val().trim()) {
            showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
            return false;
        }

        showPreloader();

        // AJAX request to create a new user
        $.ajax({
            url: $('#url').attr('content') +'/api/societe_new',
            method: 'GET',
            data: {
                codeassurance: codeassurance.val(),
                nom: nom.val(),
                assureur_id: assureur_id.val(),
            },
            success: function(response) {
                hidePreloader();

                if (response.existe) {
                    showAlert('Alert', 'Cette société existe déjà', 'warning');
                } else if (response.success) {

                    nom.val('');
                    codeassurance.val('').trigger('change');
                    assureur_id.val('').trigger('change');

                    select_societe('#patient_codesocieteassure_new');

                    showAlert('Succès', 'Opération éffectuée.', 'success');
                } else if (response.error) {
                    showAlert('Erreur', response.message, 'error');
                }
            },
            error: function() {
                hidePreloader();
                showAlert('Erreur', 'Une erreur est survenue', 'error');
            }
        });
    }

    function eng_assurance() {
        const nom = $("#nom_assurance_new");
        const email = $("#email_assurance_new");
        const phone = $("#tel_assurance_new");
        const adresse = $("#adresse_assurance_new");
        const fax = $("#fax_assurance_new");
        const carte = $("#carte_assurance_new");
        const desc = $("#desc_assurance_new");

        // Vérification des champs obligatoires
        if (!nom.val().trim() || !email.val().trim() || !phone.val().trim() || !carte.val().trim() || !adresse.val().trim()) {
            showAlert('Alert', 'Tous les champs obligatoires n\'ont pas été remplis.', 'warning');
            return false;
        }

        // Vérification de l'email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.val().trim())) {
            showAlert('Alert', 'Email incorrect.', 'warning');
            return false;
        }

        // Vérification du téléphone
        if (phone.val().length !== 10) {
            showAlert('Alert', 'Contact incomplet.', 'warning');
            return false;
        }

        showPreloader();

        // Requête AJAX
        $.ajax({
            url: $('#url').attr('content') +'/api/assurance_new',
            method: 'GET',
            data: {
                nom: nom.val(),
                email: email.val(),
                tel: phone.val(),
                desc: desc.val() || null,
                fax: fax.val() || null,
                adresse: adresse.val(),
                carte: carte.val()
            },
            success: function(response) {
                hidePreloader();

                if (response.tel_existe) {
                    showAlert('Alert', 'Ce numéro de contact appartient déjà à une assurance.', 'warning');
                } else if (response.email_existe) {
                    showAlert('Alert', 'Cet email appartient déjà à une assurance.', 'warning');
                } else if (response.nom_existe) {
                    showAlert('Alert', 'Cette assurance existe déjà.', 'warning');
                } else if (response.fax_existe) {
                    showAlert('Alert', 'Ce fax appartient déjà à une assurance.', 'warning');
                } else if (response.success) {

                    nom.val('');
                    email.val('');
                    phone.val('');
                    desc.val('');
                    fax.val('');
                    adresse.val('');
                    carte.val('');

                    assurance(); // rafraîchir la liste
                    showAlert('Succès', response.message, 'success');
                } else if (response.error) {
                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            },
            error: function() {
                hidePreloader();
                showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
            }
        });
    }

    function eng_patient() {
        const divAssurer = $("#div_assurer");

        let nom = $("#patient_nom_new");
        let prenom = $("#patient_prenom_new");
        let sexe = $("#patient_sexe_new");
        let datenais = $("#patient_datenaiss_new");
        let phone = $("#patient_tel_new");
        let phone2 = $("#patient_tel2_new");
        let residence = $("#patient_residence_new");
        let assurer = $("#assure");

        let filiation = $("#patient_codefiliation_new");
        let matricule_assurance = $("#patient_matriculeA_new");
        let assurance_id = $("#patient_codeassurance_new");
        let taux_id = $("#patient_idtauxcouv_new");
        let societe_id = $("#patient_codesocieteassure_new");

        let nomu = $("#patient_nomu_new");
        let telu = $("#patient_telu_new");
        let telu2 = $("#patient_telu2_new");

        // Validation des champs obligatoires
        if (!nom.val().trim() || !prenom.val().trim() || !phone.val().trim() || !datenais.val().trim() || !sexe.val().trim() || !residence.val().trim() || !assurer.val().trim()) {
            showAlert("Alert", "Veuillez remplir tous les champs obligatoires.", "warning");
            return false;
        }

        // Validation des numéros de téléphone
        if (phone.val().length !== 10 ) {
            showAlert("Alert", "Contact 1 incomplet.", "warning");
            return false;
        }

        if (phone2.val() && phone2.val().length !== 10) {
            showAlert("Alert", "Contact 2 incomplet.", "warning");
            return false;
        }

        if (telu.val() && telu.val().length !== 10) {
            showAlert("Alert", "Contact 1 en cas d'urgence incomplet.", "warning");
            return false;
        }

        if (telu2.val() && telu2.val().length !== 10) {
            showAlert("Alert", "Contact 2 en cas d'urgence incomplet.", "warning");
            return false;
        }

        // Validation des champs relatifs à l'assurance
        if (assurer.val() === "1") {
            if (!assurance_id.val() || !taux_id.val() || !societe_id.val() || !filiation.val() || !matricule_assurance.val()) {
                showAlert("Alert", "Veuillez remplir tous les champs relatifs à l'assurance.", "warning");
                return false;
            }
        }

        showPreloader();

        // Envoi AJAX
        $.ajax({
            url: $('#url').attr('content') +"/api/patient_new",
            method: "GET", // POST pour créer les données
            data: {
                nom: nom.val(),
                prenom: prenom.val(),
                tel: phone.val(),
                tel2: phone2.val() || null,
                residence: residence.val(),
                assurer: assurer.val(),
                assurance_id: assurance_id.val() || 'NONAS',
                taux_id: taux_id.val() || null,
                societe_id: societe_id.val() || null,
                datenais: datenais.val(),
                sexe: sexe.val(),
                filiation: filiation.val() || null,
                matricule_assurance: matricule_assurance.val() || null,
                nomu: nomu.val() || null,
                telu: telu.val() || null,
                telu2: telu2.val() || null,
            },
            success: function (response) {
                // Supprimer le préchargement

                if (response.success) {

                    var newTab = new bootstrap.Tab(document.getElementById('tab-oneAAA'));
                    newTab.show();

                    // Réinitialisation des champs
                    nom.val("");
                    prenom.val("");
                    phone.val("");
                    phone2.val("");
                    residence.val("");
                    datenais.val("");
                    sexe.val("").trigger('change');

                    nomu.val("");
                    telu.val("");
                    telu2.val("");

                    filiation.val("").trigger('change');
                    matricule_assurance.val("");
                    assurance_id.val("").trigger('change');
                    taux_id.val("").trigger('change');
                    societe_id.val("").trigger('change');
                    assurer.val("").trigger('change');

                    divAssurer.hide();

                    const selectElement = $('#id_patient');
                    selectElement.empty();

                    // Ajouter l'option par défaut
                    const defaultOption = $('<option>', {
                        value: '',
                        text: 'Selectionner'
                    });
                    selectElement.append(defaultOption);

                    $.ajax({
                        url: $('#url').attr('content') +'/api/name_patient_reception',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {

                            data.name.forEach(item => {
                                const option = $('<option>', {
                                    value: item.idenregistremetpatient,
                                    text: item.nomprenomspatient
                                });
                                selectElement.append(option);
                            });

                            hidePreloader();

                            $('#id_patient').val(response.id).trigger('change');

                        },
                        error: function() {
                            console.error('Erreur lors du chargement des patients');
                        }
                    });

                    // showAlert("Succès", response.message, "success");
                } else if (response.error) {
                    showAlert("Alert", response.message, "error");
                }
            },
            error: function () {

                hidePreloader();
                showAlert("Alert", "Une erreur est survenue lors de l'enregistrement.", "error");
            }
        });
    }

    // ------------------------------------------------------------------

    const table_cons = $('.Table_day_cons').DataTable({

        processing: true,
        serverSide: false,
        ajax: {
            url: $('#url').attr('content') +`/api/list_cons_day`,
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
                data: 'numdossier',
                render: (data, type, row) => {
                    return data ? `${data}` : 'Aucun';
                },
                searchable: true,
            },
            { 
                data: 'nom_patient',
                searchable: true, 
            },
            { 
                data: 'nom_medecin',
                searchable: true, 
            },
            { 
                data: 'garantie',
                searchable: true, 
            },
            { 
                data: 'montant', 
                render: (data) => `${formatPriceT(data)} Fcfa`,
                searchable: true, 
            },
            { 
                data: 'numfac', 
                render: (data) => `${data}`,
                searchable: true, 
            },
            { 
                data: 'date', 
                render: (data) => `${formatDate(data)}`,
                searchable: true, 
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-info" id="Cfacture" 
                                data-idconsexterne="${row.idconsexterne}">
                                    <i class="ri-printer-fill"></i>
                                    Imprimer Facture
                                </a>
                            </li>
                            <li>
                                <a href="#" class="dropdown-item text-info" id="Cfiche" 
                                data-idconsexterne="${row.idconsexterne}">
                                    <i class="ri-printer-fill"></i>
                                    Imprimer Fiche
                                </a>
                            </li>
                            ${row.regle == 0 ? `
                                <li>
                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#MdeleteCons" id="deleteCons" data-numfac="${row.numfac}" 
                                    >
                                        <i class="ri-delete-bin-line"></i>
                                        Supprimer
                                    </a>
                                </li>` : 
                            ''}
                        </ul>
                    </div>
                `,
                searchable: false,
                orderable: false,
            }
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initializeRowEventListenersCons();
        },
    });

    OffClick('#btn_refresh_table', function () {
        table_cons.ajax.reload(null, false); 
    });

    function initializeRowEventListenersCons() {

        // Clique sur "Cfacture"
        $('.Table_day_cons').on('click', '#Cfacture', function() {
            showPreloader();

            const code = $(this).data('idconsexterne');

            $.ajax({
                url: $('#url').attr('content') +`/api/fiche_consultation/${code}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    hidePreloader();
                    const facture = data.facture;
                    pdfFactureConsultation(facture);
                },
                error: function(err) {
                    hidePreloader();
                    console.error('Erreur lors du chargement des données:', err);
                    showAlert('Erreur', 'Impossible de charger la facture.', 'error');
                }
            });
        });

        // Clique sur "Cfiche"
        $('.Table_day_cons').on('click', '#Cfiche', function() {
            showPreloader();

            const code = $(this).data('idconsexterne');

            $.ajax({
                url: $('#url').attr('content') +`/api/fiche_consultation/${code}`,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    hidePreloader();
                    const facture = data.facture;
                    pdfFicheConsultation(facture);
                },
                error: function(err) {
                    hidePreloader();
                    console.error('Erreur lors du chargement des données:', err);
                    showAlert('Erreur', 'Impossible de charger la fiche.', 'error');
                }
            });
        });

        // Clique sur "deleteCons"
        $('.Table_day_cons').on('click', '#deleteCons', function(e) {
            e.preventDefault();
            const numfac = $(this).data('numfac');
            $('#IddeleteCons').val(numfac);
        });
    }

    function eng_consultation() {

        const login = user.login;
        const id_patient = $('#id_patient').val();
        const assurance_utiliser = $('#assurance_utiliser').val();
        const typeacte_idS = $('#typeacte_idS').val();
        const medecin_id = $('#medecin_id').val();
        const periode = $('#periode').val();
        const montant_assurance = $('#montant_assurance').val();
        const montant_patient = $('#montant_patient').val();
        const taux_remise = $('#taux_remise').val() || 0;
        const montant_total = $('#montant_total').val();
        const mumcode = $('#mumcode').val() || null;

        const codeassurance = $('#patient_codeassurance').val() || null;
        const patient_numdossier = $('#patient_numdossier').val() || null;
        const patient_taux = $('#patient_taux').val();

        // Validation des champs obligatoires
        if (!typeacte_idS || !medecin_id ) {
            showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
            return false;
        }

        if (montant_assurance < 0 || montant_patient < 0 || taux_remise < 0) {
            showAlert('Alert', 'Veuillez vérifier le montant de la remise.', 'warning');
            return false;
        }

        let assurance = parseInt($('#montant_assurance').val().replace(/[^0-9]/g, '')) || 0;
        let patient = parseInt($('#montant_patient').val().replace(/[^0-9]/g, '')) || 0;
        let remise = parseInt($('#taux_remise').val().replace(/[^0-9]/g, '')) || 0;
        let total = assurance + patient + remise;

        if (total !== parseInt(montant_total.replace(/[^0-9]/g, ''))) {
            showAlert('Alert', 'Veuillez vérifier les différents montants.', 'warning');
            return false;
        }

        showPreloader();

        $.ajax({
            url: $('#url').attr('content') +'/api/new_consultation',
            method: 'GET', // Utiliser 'POST' pour créer des données
            data: {
                id_patient: id_patient,
                typeacte_id: typeacte_idS,
                user_id: medecin_id,
                periode: periode,
                montant_assurance: montant_assurance,
                montant_patient: montant_patient,
                taux_remise: taux_remise,
                total: montant_total,
                appliq_remise: $('#appliq_remise').val(),
                mumcode: mumcode,
                assurance_utiliser: assurance_utiliser,
                login: login,
                codeassurance: codeassurance,
                patient_numdossier: patient_numdossier,
                patient_taux: patient_taux,
            },
            success: function(response) {
                hidePreloader();
                
                if (response.success) {

                    $('#div_info_patient').empty();
                    $('#mumcode').val('');

                    if ($('#stat_consultation').html().trim() !== "") {
                        Statistique_cons();
                    }

                    table_cons.ajax.reload(null, false);

                    Statistique();
                    Reset();
                    stat_fac_day();
                    Activity_cons();
                    Activity_cons_count();

                    showAlert('Succès', 'Patient Enregistrée.', 'success');

                } else if (response.error) {
                    showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            },
            error: function() {
                hidePreloader();
                showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
            }
        });
    }

    function delete_cons() {
        // Récupérer le numéro de facture
        const numfac = $('#IddeleteCons').val();

        // Masquer le modal Bootstrap
        $('#MdeleteCons').modal('hide');

        showPreloader();

        // Requête AJAX
        $.ajax({
            url: $('#url').attr('content') +'/api/delete_Cons/' + numfac,
            method: 'GET',
            success: function(response) {
                hidePreloader(); // Retirer le préchargeur

                if (response.success) {
                    table_cons.ajax.reload(null, false); // Rafraîchir la table
                    showAlert('Succès', 'Opération éffectuée.', 'success');
                } else if (response.error) {
                    showAlert('Erreur', 'Échec de l\'opération.', 'error');
                }
            },
            error: function() {
                hidePreloader();
                showAlert('Erreur', 'Erreur lors de la suppression.', 'error');
            }
        });
    }

    //-------------------------------------------------------------------

    function Statistique() {
        const nbre_fac = $('#nbre_fac');
        const montant_fac_r = $('#montant_fac_r');
        const montant_fac_nr = $('#montant_fac_nr');
        const total_fac = $('#total_fac');

        const stat_cons = $('#stat_cons');
        const stat_exam = $('#stat_exam');
        const stat_soins = $('#stat_soins');
        const stat_hosp = $('#stat_hosp');

        $.ajax({
            url: $('#url').attr('content') +'/api/statistique_reception/' + $('#stat_bord_date').val(),
            method: 'GET',
            success: function(response) {
                hidePreloader();

                nbre_fac.text(response.nbre_fac);
                montant_fac_r.text(formatPrice(response.montant_fac_r.toString()) + ' Fcfa');
                montant_fac_nr.text(formatPrice(response.montant_fac_nr.toString()) + ' Fcfa');
                total_fac.text(formatPrice(response.total_fac.toString()) + ' Fcfa');
                stat_cons.text(response.stat_cons);
                stat_exam.text(response.stat_exam);
                stat_soins.text(response.stat_soins);
                stat_hosp.text(response.stat_hosp);
            },
            error: function() {
                hidePreloader();
            }
        });
    }

    function Statistique_cons() {
        const stat_consultation = $('#stat_consultation');

        stat_consultation.html(`
            <div class="d-flex justify-content-center align-items-center mb-3">
                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                <strong>Chargement des données...</strong>
            </div>
        `);

        const date1 = $('#searchDate1').val();
        const date2 = $('#searchDate2').val();

        $('#div_btn_affiche_stat, #div_btn_cache_stat').hide();

        $.ajax({
            url: $('#url').attr('content') +`/api/statistique_reception_cons/${date1}/${date2}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const typeactes = data.typeacte;
                stat_consultation.empty();

                $('#div_btn_affiche_stat').hide();
                $('#div_btn_cache_stat').show();

                if (typeactes.length > 0) {
                    stat_consultation.show();

                    $.each(typeactes, function(index, item) {
                        const row = $(`
                            <div class="col-xxl-3 col-xl-4 col-md-6 col-12">
                                <div class="card mb-3"
                                    style="
                                        border: 1px solid #e8efec;
                                        border-radius: 16px;
                                        background: #ffffff;
                                        overflow: hidden;
                                        box-shadow: 0 4px 16px rgba(8, 127, 91, 0.05);
                                    ">

                                    <div class="card-body" style="padding: 20px;">

                                        <!-- =========================
                                             EN-TÊTE
                                        ========================== -->
                                        <div class="d-flex align-items-center mb-4">

                                            <!-- Icône -->
                                            <div style="
                                                width: 52px;
                                                height: 52px;
                                                min-width: 52px;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                border-radius: 14px;
                                                background: #e9f6f1;
                                                color: #087f5b;
                                                margin-right: 14px;
                                            ">
                                                <i class="ri-stethoscope-line" style="font-size: 24px;"></i>
                                            </div>

                                            <!-- Informations -->
                                            <div style="
                                                min-width: 0;
                                                flex: 1;
                                            ">
                                                <div style="
                                                    color: #263630;
                                                    font-size: 0.95rem;
                                                    font-weight: 700;
                                                    margin-bottom: 5px;
                                                    white-space: nowrap;
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                ">
                                                    ${item.libgaran}
                                                </div>

                                                <div style="
                                                    display: flex;
                                                    align-items: center;
                                                    gap: 6px;
                                                    color: #7c8984;
                                                    font-size: 0.75rem;
                                                ">
                                                    <i class="ri-stethoscope-line" style="
                                                        color: #087f5b;
                                                        font-size: 14px;
                                                    "></i>

                                                    <span>${item.nbre} Consultation(s)</span>
                                                </div>
                                            </div>

                                        </div>


                                        <!-- =========================
                                             RÉCAPITULATIF FINANCIER
                                        ========================== -->
                                        <div style="
                                            background: #f8faf9;
                                            border: 1px solid #edf2ef;
                                            border-radius: 12px;
                                            padding: 13px 14px;
                                        ">

                                            <!-- Part Assurance -->
                                            <div style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: space-between;
                                                padding-bottom: 10px;
                                                margin-bottom: 10px;
                                                border-bottom: 1px dashed #dce7e2;
                                            ">

                                                <div style="
                                                    display: flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    color: #6f7e78;
                                                    font-size: 0.76rem;
                                                ">
                                                    <span style="
                                                        width: 7px;
                                                        height: 7px;
                                                        border-radius: 50%;
                                                        background: #087f5b;
                                                        display: inline-block;
                                                    "></span>

                                                    <span>Part Assurance</span>
                                                </div>

                                                <strong style="
                                                    color: #087f5b;
                                                    font-size: 0.82rem;
                                                    font-weight: 700;
                                                ">
                                                    ${formatPrice(item.part_assurance.toString())} Fcfa
                                                </strong>

                                            </div>


                                            <!-- Part Patient -->
                                            <div style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: space-between;
                                                padding-bottom: 10px;
                                                margin-bottom: 10px;
                                                border-bottom: 1px dashed #dce7e2;
                                            ">

                                                <div style="
                                                    display: flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    color: #6f7e78;
                                                    font-size: 0.76rem;
                                                ">
                                                    <span style="
                                                        width: 7px;
                                                        height: 7px;
                                                        border-radius: 50%;
                                                        background: #3978d6;
                                                        display: inline-block;
                                                    "></span>

                                                    <span>Part Patient</span>
                                                </div>

                                                <strong style="
                                                    color: #3978d6;
                                                    font-size: 0.82rem;
                                                    font-weight: 700;
                                                ">
                                                    ${formatPrice(item.part_patient.toString())} Fcfa
                                                </strong>

                                            </div>


                                            <!-- Total -->
                                            <div style="
                                                display: flex;
                                                align-items: center;
                                                justify-content: space-between;
                                                padding: 10px 12px;
                                                margin: 0 -2px -2px -2px;
                                                border-radius: 9px;
                                                background: #087f5b;
                                            ">

                                                <div style="
                                                    display: flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    color: #ffffff;
                                                    font-size: 0.78rem;
                                                    font-weight: 600;
                                                ">
                                                    <i class="ri-wallet-3-line" style="
                                                        font-size: 16px;
                                                    "></i>

                                                    <span>Montant Total</span>
                                                </div>

                                                <strong style="
                                                    color: #ffffff;
                                                    font-size: 0.9rem;
                                                    font-weight: 700;
                                                    white-space: nowrap;
                                                ">
                                                    ${formatPrice(item.total.toString())} Fcfa
                                                </strong>

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        `);
                        stat_consultation.append(row);
                    });

                    stat_consultation.css({ height: "550px", overflowY: "auto" });

                } else {
                    stat_consultation.html(`
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <strong class="text-danger">Aucune données n'a été trouvées</strong>
                        </div>
                    `);
                }
            },
            error: function() {
                stat_consultation.html(`
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <strong class="text-danger">Erreur lors du chargement des données</strong>
                    </div>
                `);
            }
        });
    }

    function Activity_cons() {

        const $contenu = $('#docActivity');
        const $comparison = $('#consultationComparison');

        // ==========================================================
        // LOADER
        // ==========================================================

        const loader = `
            <div class="d-flex flex-column justify-content-center align-items-center h-100 py-4">
                
                <div class="spinner-border text-warning mb-2"
                     role="status"
                     style="width: 1.5rem; height: 1.5rem;">
                </div>

                <span class="opacity-75 small">
                    Chargement des données...
                </span>

            </div>
        `;

        $contenu.html(loader);
        $comparison.empty();


        // ==========================================================
        // REQUÊTE
        // ==========================================================

        $.ajax({

            url: $('#url').attr('content') + '/api/getWeeklyDashbord',

            method: 'GET',

            dataType: 'json',

            success: function(data) {

                // --------------------------------------------------
                // Nettoyage
                // --------------------------------------------------

                $contenu.empty();


                // --------------------------------------------------
                // DONNÉES
                // --------------------------------------------------

                const weeklyCounts = Array.isArray(data.weeklyCounts)
                    ? data.weeklyCounts
                    : [];


                const categories = weeklyCounts.map(item => {

                    const date = new Date(item.date + 'T00:00:00');

                    return date
                        .toLocaleDateString('fr-FR', {
                            weekday: 'short'
                        })
                        .replace('.', '')
                        .replace(/^./, char => char.toUpperCase());

                });


                const totals = weeklyCounts.map(item => {

                    return Number(item.total) || 0;

                });


                // --------------------------------------------------
                // VALEUR MAXIMALE
                // --------------------------------------------------

                const maxValue = Math.max(...totals, 0);

                const yMax = maxValue > 0
                    ? Math.ceil(maxValue * 1.25)
                    : 5;


                // ==================================================
                // GRAPHIQUE
                // ==================================================

                const options = {

                    chart: {
                        type: 'area',
                        height: 160,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                        parentHeightOffset: 0,
                        offsetY: -15
                    },

                    series: [{

                        name: 'Actes',

                        data: totals

                    }],


                    // ==================================================
                    // COURBE
                    // ==================================================

                    stroke: {

                        curve: 'smooth',

                        width: 3

                    },


                    // ==================================================
                    // ZONE SOUS LA COURBE
                    // ==================================================

                    fill: {

                        type: 'gradient',

                        gradient: {

                            shadeIntensity: 1,

                            opacityFrom: 0.35,

                            opacityTo: 0.03,

                            stops: [0, 90, 100]

                        }

                    },


                    // ==================================================
                    // COULEUR
                    // ==================================================

                    colors: ['#087f5b'],


                    // ==================================================
                    // POINTS
                    // ==================================================

                    markers: {

                        size: 4,

                        strokeWidth: 2,

                        strokeColors: '#087f5b',

                        hover: {

                            size: 6

                        }

                    },


                    // ==================================================
                    // VALEURS
                    // ==================================================

                    dataLabels: {

                        enabled: true,

                        offsetY: -8,

                        style: {

                            fontSize: '10px',

                            fontWeight: 600,

                            colors: ['#087f5b']

                        },

                        background: {

                            enabled: false

                        },

                        formatter: function(val) {

                            return val;

                        }

                    },


                    // ==================================================
                    // LÉGENDE
                    // ==================================================

                    legend: {

                        show: false

                    },


                    // ==================================================
                    // AXE X
                    // ==================================================

                    xaxis: {

                        categories: categories,

                        axisBorder: {

                            show: false

                        },

                        axisTicks: {

                            show: false

                        },

                        labels: {

                            show: true,

                            offsetY: 0,

                            style: {

                                colors: '#087f5b',

                                fontSize: '11px',

                                fontWeight: 500

                            }

                        }

                    },


                    // ==================================================
                    // AXE Y
                    // ==================================================

                    yaxis: {

                        show: false,

                        min: 0,

                        max: yMax

                    },


                    // ==================================================
                    // GRILLE
                    // ==================================================

                    grid: {

                        show: false,

                        padding: {

                            top: -6,

                            right: 10,

                            bottom: 0,

                            left: 10

                        }

                    },


                    // ==================================================
                    // TOOLTIP
                    // ==================================================

                    tooltip: {

                        theme: 'dark',

                        x: {

                            show: true

                        },

                        y: {

                            formatter: function(val) {

                                return val + ' acte(s)';

                            }

                        }

                    }

                };


                // ==================================================
                // RENDU
                // ==================================================

                const chart = new ApexCharts(

                    document.querySelector('#docActivity'),

                    options

                );

                chart.render();


                // ==================================================
                // COMPARAISON
                // ==================================================

                const percentage = Number(data.percentage) || 0;

                const currentWeek = Number(data.currentWeek) || 0;

                const lastWeek = Number(data.lastWeek) || 0;


                let badgeClass = 'bg-secondary';

                if (percentage > 0) {

                    badgeClass = 'bg-success';

                } else if (percentage < 0) {

                    badgeClass = 'bg-danger';

                }


                const signe = percentage > 0 ? '+' : '';


                // ==================================================
                // TEXTE SOUS LE GRAPHIQUE
                // ==================================================

                $comparison.html(`

                    <div class="d-flex flex-wrap gap-1 justify-content-center align-items-center">

                        <!-- Cette semaine -->
                        <div class="
                            d-flex
                            align-items-center
                            box-shadow
                            px-3
                            py-1
                            rounded-2
                            me-1
                            mb-1
                            bg-primary
                            text-white
                        ">

                            <i class="ri-bar-chart-2-fill text-white fs-5"></i>

                            <span class="text-white ps-1 me-1">
                                ${currentWeek}
                            </span>

                            <span class="fw-semibold">
                                Cette semaine
                            </span>

                        </div>


                        <!-- Semaine dernière -->
                        <div class="
                            d-flex
                            align-items-center
                            box-shadow
                            px-3
                            py-1
                            rounded-2
                            me-1
                            mb-1
                            bg-warning
                            text-white
                        ">

                            <i class="ri-history-line text-white fs-5"></i>

                            <span class="text-white ps-1 me-1">
                                ${lastWeek}
                            </span>

                            <span class="fw-semibold">
                                Semaine dernière
                            </span>

                        </div>


                        <!-- Évolution -->
                        <div class="
                            d-flex
                            align-items-center
                            box-shadow
                            px-3
                            py-1
                            rounded-2
                            me-1
                            mb-1
                            ${badgeClass}
                            text-white
                        ">

                            <i class="
                                ${percentage > 0
                                    ? 'ri-arrow-up-line'
                                    : percentage < 0
                                        ? 'ri-arrow-down-line'
                                        : 'ri-subtract-line'
                                }
                                text-white
                                fs-5
                            "></i>

                            <span class="text-white ps-1 me-1">
                                ${signe}${percentage.toFixed(2)}%
                            </span>

                            <span class="fw-semibold">
                                Évolution
                            </span>

                        </div>

                    </div>

                `);

            },


            // ======================================================
            // ERREUR
            // ======================================================

            error: function(xhr) {

                console.error(
                    'Erreur lors du chargement des statistiques :',
                    xhr
                );


                $contenu.html(`

                    <div class="
                        d-flex
                        justify-content-center
                        align-items-center
                        text-center
                        h-100
                        py-4
                    ">

                        <div class="text-white opacity-75">

                            <i class="ri-error-warning-line fs-3 d-block mb-1"></i>

                            <span class="small">
                                Impossible de charger les statistiques.
                            </span>

                        </div>

                    </div>

                `);


                $comparison.empty();

            }

        });

    }

    function stat_fac_day() {
        const page = $('#content_stat_fac');
        page.html(`
            <div class="d-flex justify-content-center align-items-center mb-3">
                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                <strong class="text-dark">Chargement des données...</strong>
            </div>
        `);

        $.ajax({
            url: $('#url').attr('content') +'/api/getStatFacDay',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const table = data.data;

                page.html('<div class="row g-3" id="content_stat_fac2"></div>');

                $.each(table, function(index, item) {
                    const row = $(`
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="d-flex align-items-center"
                                style="
                                    padding: 16px 18px;
                                    border: 1px solid #e6eeea;
                                    border-radius: 14px;
                                    background: #ffffff;
                                    box-shadow: 0 4px 14px rgba(8, 127, 91, 0.05);
                                    transition: all .2s ease;
                                ">

                                <!-- Icône -->
                                <div style="
                                    width: 50px;
                                    height: 50px;
                                    min-width: 50px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 14px;
                                    border-radius: 14px;
                                    background: #e9f6f1;
                                    border: 1px solid #d5ebe2;
                                ">
                                    <i class="ri-money-euro-circle-line"
                                        style="
                                            font-size: 25px;
                                            line-height: 1;
                                            color: #087f5b;
                                        ">
                                    </i>
                                </div>

                                <!-- Informations -->
                                <div style="
                                    min-width: 0;
                                    flex: 1;
                                ">

                                    <!-- Montant -->
                                    <div style="
                                        display: flex;
                                        align-items: baseline;
                                        gap: 6px;
                                        margin-bottom: 4px;
                                    ">
                                        <h5 style="
                                            margin: 0;
                                            color: #263630;
                                            font-size: 1.05rem;
                                            font-weight: 700;
                                            line-height: 1.2;
                                            white-space: nowrap;
                                        ">
                                            ${formatPrice(item.montant.toString())}
                                        </h5>

                                        <span style="
                                            color: #087f5b;
                                            font-size: .72rem;
                                            font-weight: 600;
                                        ">
                                            Fcfa
                                        </span>
                                    </div>

                                    <!-- Nom -->
                                    <div style="
                                        display: flex;
                                        align-items: center;
                                        gap: 6px;
                                        color: #7c8984;
                                        font-size: .78rem;
                                        font-weight: 500;
                                        white-space: nowrap;
                                        overflow: hidden;
                                        text-overflow: ellipsis;
                                    ">
                                        <i class="ri-price-tag-3-line"
                                            style="
                                                color: #087f5b;
                                                font-size: 13px;
                                            ">
                                        </i>

                                        <span style="
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">
                                            ${item.nom}
                                        </span>
                                    </div>

                                </div>

                                <!-- Indicateur -->
                                <div style="
                                    width: 8px;
                                    height: 8px;
                                    min-width: 8px;
                                    margin-left: 10px;
                                    border-radius: 50%;
                                    background: #087f5b;
                                    box-shadow: 0 0 0 4px #e9f6f1;
                                ">
                                </div>

                            </div>
                        </div>
                    `);

                    $('#content_stat_fac2').append(row);
                });
            },
            error: function(err) { console.error('Error fetching data:', err); }
        });
    }

    // -----------------------------------------------------------------

    const table_rdv = $('.Table_day_rdv').DataTable({

        processing: true,
        serverSide: false,
        ajax: {
            url: $('#url').attr('content') +`/api/list_rdv_day`,
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
                data: 'patient', 
                render: (data, type, row) => `
                <div class="d-flex align-items-center">
                    <a class="d-flex align-items-center flex-column me-2">
                        <img src="/assets/images/rdv1.png" class="img-2x rounded-circle border border-1">
                    </a>
                    ${data}
                </div>`,
                searchable: true, 
            },
            {
                data: 'patient_tel',
                render: (data, type, row) => {
                    return data ? `+225 ${data}` : 'Néant';
                },
                searchable: true,
            },
            {
                data: 'medecin',
                render: (data, type, row) => {
                    return data ? `Dr. ${data}` : 'Néant';
                },
                searchable: true,
            },
            { 
                data: 'specialite',
                searchable: true, 
            },
            { 
                data: 'date',
                render: formatDate,
                searchable: true, 
            },
            {
                data: 'statut',
                render: (data, type, row) => `
                    <span class="badge ${data === 'en attente' ? 'bg-danger' : 'bg-success'}">
                        ${data === 'en attente' ? 'En Attente' : 'Terminer'}
                    </span>
                `,
                searchable: true,
            },
            { 
                data: 'created_at',
                render: formatDateHeure,
                searchable: true, 
            }
        ],
        ...dataTableConfig,
    });

    OffClick('#btn_refresh_table_rdv', function () {
        table_rdv.ajax.reload(null, false); 
    });

});