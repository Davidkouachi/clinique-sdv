$(document).ready(function() {

    caisse_verf()

    Statistique();
    Activity_cons();
    stat_fac_day();
    rdv_day();
    historique_journal();
    datesearch();

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

    OffClick('#btn_refresh_statActivity', function () {
        Activity_cons();
    });

    OffClick('#btn_refresh_rdv_day', function () {
        rdv_day();
    });

    OffClick('#btn_search_trace_bj', function () {
        historique_journal();
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

    function datesearch() {
        const date1Value = $('#searchDate1').val();
        $('#searchDate2').attr('min', date1Value);
    }

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

    function rdv_day() {

        const $page = $('#contenu_rdv');

        const loader = `
            <div class="d-flex flex-column justify-content-center align-items-center h-100">

                <div class="spinner-border text-success mb-2"
                     role="status"
                     aria-hidden="true">
                </div>

                <small class="text-muted">
                    Chargement des rendez-vous...
                </small>

            </div>
        `;

        $page.html(loader);

        $.ajax({

            url: $('#url').attr('content') + '/api/list_rdv_day',

            method: 'GET',

            dataType: 'json',

            success: function(data) {

                const rdv = data.data || [];

                $page.empty();

                /* ================================================
                   AUCUN RENDEZ-VOUS
                   ================================================= */

                if (!rdv.length) {

                    $page.html(`
                        <div class="rdv-empty">

                            <div class="rdv-empty-icon">
                                <i class="ri-calendar-check-line"></i>
                            </div>

                            <div class="fw-semibold text-dark">
                                Aucun rendez-vous aujourd'hui
                            </div>

                            <small>
                                Votre planning est libre pour le moment.
                            </small>

                        </div>
                    `);

                    return;
                }


                /* ================================================
                   TIMELINE
                   ================================================= */

                const $timeline = $(`
                    <div class="rdv-timeline w-100"></div>
                `);


                $.each(rdv, function(index, item) {

                    /* ============================================
                       HEURE
                       ============================================ */

                    let heure = '--:--';

                    if (item.date) {

                        const date = new Date(
                            item.date.replace(' ', 'T')
                        );

                        if (!isNaN(date)) {

                            heure = date.toLocaleTimeString(
                                'fr-FR',
                                {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }
                            );

                        }

                    }


                    /* ============================================
                       INFORMATIONS
                       ============================================ */

                    const patient = item.patient
                        || 'Patient non renseigné';

                    const telephone = item.tel
                        || item.patient_tel
                        || 'Non renseigné';

                    const medecin = item.medecin
                        ? `Dr. ${item.medecin}`
                        : 'Médecin non renseigné';

                    const specialite = item.specialite
                        || 'Spécialité non renseignée';

                    const motif = item.motif
                        || 'Motif non renseigné';


                    /* ============================================
                       ITEM
                       ============================================ */

                    const $item = $(`
                        <div class="rdv-item">

                            <!-- HEURE -->
                            <div class="rdv-time">
                                ${heure}
                            </div>


                            <!-- POINT -->
                            <div class="rdv-marker-wrapper">

                                <div class="rdv-marker"></div>

                            </div>


                            <!-- CONTENU -->
                            <div class="rdv-content">

                                <!-- PATIENT -->
                                <div class="rdv-patient">

                                    <div class="rdv-patient-icon">
                                        <i class="ri-user-line"></i>
                                    </div>

                                    <span>
                                        ${patient}
                                    </span>

                                </div>


                                <!-- MOTIF -->
                                <div class="mt-2">

                                    <div class="text-muted small mb-1">
                                        Motif
                                    </div>

                                    <div class="fw-semibold text-dark small">

                                        <i class="ri-chat-quote-line text-primary me-1"></i>

                                        ${motif}

                                    </div>

                                </div>


                                <!-- INFORMATIONS -->
                                <div class="rdv-info mt-2">

                                    <span class="rdv-info-item">

                                        <i class="ri-stethoscope-line"></i>

                                        ${medecin}

                                    </span>


                                    <span class="rdv-info-item">

                                        <i class="ri-phone-line"></i>

                                        <span class="rdv-phone">
                                            ${telephone}
                                        </span>

                                    </span>

                                </div>


                                <!-- SPECIALITE -->
                                <div>

                                    <span class="rdv-specialite">

                                        <i class="ri-hospital-line me-1"></i>

                                        ${specialite}

                                    </span>

                                </div>

                            </div>

                        </div>
                    `);


                    $timeline.append($item);

                });


                $page.append($timeline);

            },


            error: function(xhr) {

                console.error(
                    'Erreur lors du chargement des rendez-vous :',
                    xhr
                );

                $page.html(`

                    <div class="rdv-empty">

                        <div class="rdv-empty-icon text-danger bg-danger-subtle">

                            <i class="ri-error-warning-line"></i>

                        </div>

                        <div class="fw-semibold text-danger">
                            Impossible de charger les rendez-vous
                        </div>

                        <small>
                            Veuillez réessayer.
                        </small>

                    </div>

                `);

            }

        });
    }

    function historique_journal() {

        const $contenu = $('#historique_contenu');
        const $total = $('#historique_total');
        const date = $('#searchDate1_bj').val();


        // ==========================================================
        // LOADING
        // ==========================================================

        $contenu.html(`
            <div class="d-flex flex-column align-items-center justify-content-center w-100 py-5">

                <div 
                    class="spinner-border text-success mb-2" 
                    role="status" 
                    aria-hidden="true">
                </div>

                <span class="text-muted small">
                    Chargement de l'historique...
                </span>

            </div>
        `);

        $total.empty();


        // ==========================================================
        // AJAX
        // ==========================================================

        $.ajax({

            url: `/api/historique_caisse/${date}`,

            method: 'GET',

            dataType: 'json',


            // ======================================================
            // SUCCESS
            // ======================================================

            success: function(data) {

                const traces = data.trace || [];
                const total = Number(data.total) || 0;


                // ==================================================
                // TRI PAR DATE
                // ==================================================

                traces.sort(function(a, b) {

                    return new Date(a.date) - new Date(b.date);

                });


                $contenu.empty();


                // ==================================================
                // AUCUNE DONNÉE
                // ==================================================

                if (traces.length === 0) {

                    $contenu.html(`
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">

                            <div
                                class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3"
                                style="width:60px;height:60px;"
                            >
                                <i class="ri-inbox-line fs-3 text-muted"></i>
                            </div>

                            <strong class="text-muted">
                                Aucun mouvement
                            </strong>

                            <small class="text-muted mt-1">
                                Aucun mouvement enregistré pour cette date.
                            </small>

                        </div>
                    `);

                    $total.empty();

                    return;
                }


                // ==================================================
                // AFFICHAGE DES MOUVEMENTS
                // ==================================================

                $.each(traces, function(index, item) {

                    const type = item.type || 'Mouvement';


                    // ==================================================
                    // CONFIGURATION PAR TYPE
                    // ==================================================

                    let icon = 'ri-exchange-line';

                    let iconBg = 'bg-secondary-subtle';

                    let iconColor = 'text-secondary';

                    let amountColor = 'text-dark';

                    let amountPrefix = '';

                    let badgeClass =
                        'bg-secondary-subtle text-secondary';


                    // ==================================================
                    // OUVERTURE
                    // ==================================================

                    if (type === 'OUVERTURE') {

                        icon = 'ri-door-open-line';

                        iconBg = 'bg-primary';

                        iconColor = 'text-white';

                        amountColor = 'text-primary';

                        badgeClass =
                            'bg-primary text-white';
                    }


                    // ==================================================
                    // ENTRÉE
                    // ==================================================

                    else if (type === 'entrer') {

                        icon = 'ri-arrow-right-up-line';

                        iconBg = 'bg-success';

                        iconColor = 'text-white';

                        amountColor = 'text-success';

                        amountPrefix = '+';

                        badgeClass =
                            'bg-success text-white';
                    }


                    // ==================================================
                    // SORTIE
                    // ==================================================

                    else if (type === 'sortie') {

                        icon = 'ri-arrow-right-down-line';

                        iconBg = 'bg-danger';

                        iconColor = 'text-white';

                        amountColor = 'text-danger';

                        amountPrefix = '-';

                        badgeClass =
                            'bg-danger text-white';
                    }


                    // ==================================================
                    // FERMETURE
                    // ==================================================

                    else if (type === 'FERMETURE') {

                        icon = 'ri-door-closed-line';

                        iconBg = 'bg-warning';

                        iconColor = 'text-white';

                        amountColor = 'text-warning';

                        badgeClass =
                            'bg-warning text-white';
                    }


                    // ==================================================
                    // MONTANT
                    // ==================================================

                    const montant = Number(item.montant) || 0;

                    const montantFormate = formatPrice(
                        montant.toString()
                    );


                    // ==================================================
                    // DATE / HEURE
                    // ==================================================

                    const createdAt = new Date(item.date);


                    const heure = createdAt.toLocaleTimeString(
                        'fr-FR',
                        {
                            hour: '2-digit',
                            minute: '2-digit'
                        }
                    );


                    const dateFormatee =
                        createdAt.toLocaleDateString(
                            'fr-FR',
                            {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            }
                        );


                    // ==================================================
                    // AUTEUR
                    // ==================================================

                    const auteur =
                        item.auteur || 'Auteur inconnu';


                    // ==================================================
                    // HTML
                    // ==================================================

                    const html = `

                        <div
                            class="border rounded-3 p-3 bg-white shadow-sm w-100"
                        >

                            <div class="d-flex align-items-start">


                                <!-- ============================= -->
                                <!-- ICONE -->
                                <!-- ============================= -->

                                <div class="me-3">

                                    <div
                                        class="${iconBg} ${iconColor}
                                               rounded-3
                                               d-flex
                                               align-items-center
                                               justify-content-center"
                                        style="width:44px;height:44px;"
                                    >

                                        <i class="${icon} fs-4"></i>

                                    </div>

                                </div>


                                <!-- ============================= -->
                                <!-- CONTENU -->
                                <!-- ============================= -->

                                <div class="flex-grow-1 min-width-0">


                                    <div
                                        class="d-flex
                                               align-items-start
                                               justify-content-between
                                               gap-2"
                                    >


                                        <div>


                                            <!-- MONTANT -->

                                            <strong
                                                class="${amountColor}"
                                                style="font-size:1rem;"
                                            >

                                                ${amountPrefix}
                                                ${montantFormate} Fcfa

                                            </strong>


                                            <!-- TYPE -->

                                            <div>

                                                <span
                                                    class="badge ${badgeClass}
                                                           rounded-pill
                                                           mt-1"
                                                    style="font-size:.65rem;"
                                                >

                                                    ${type}

                                                </span>

                                            </div>

                                        </div>


                                        <!-- ========================= -->
                                        <!-- DATE -->
                                        <!-- ========================= -->

                                        <div
                                            class="text-end
                                                   text-muted
                                                   flex-shrink-0"
                                        >

                                            <div class="fw-semibold small">
                                                ${heure}
                                            </div>

                                            <div style="font-size:.65rem;">
                                                ${dateFormatee}
                                            </div>

                                        </div>

                                    </div>


                                    <!-- ============================= -->
                                    <!-- AUTEUR -->
                                    <!-- ============================= -->

                                    <div class="mt-2">

                                        <div
                                            class="text-dark small"
                                            style="line-height:1.35;"
                                        >

                                            <i
                                                class="ri-user-line
                                                       me-1
                                                       text-muted"
                                            ></i>

                                            ${auteur}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    `;


                    $contenu.append(html);

                });


                // ==================================================
                // TOTAL
                // ==================================================

                const totalClass =
                    total >= 0
                        ? 'text-success'
                        : 'text-danger';


                const totalIcon =
                    total >= 0
                        ? 'ri-wallet-3-line'
                        : 'ri-wallet-2-line';


                $total.html(`

                    <div class="rounded-3 bg-light border p-3">

                        <div
                            class="d-flex
                                   align-items-center
                                   justify-content-between"
                        >


                            <!-- ============================== -->
                            <!-- SOLDE -->
                            <!-- ============================== -->

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-3
                                           bg-white
                                           d-flex
                                           align-items-center
                                           justify-content-center
                                           me-2"
                                    style="width:38px;height:38px;"
                                >

                                    <i
                                        class="${totalIcon}
                                               fs-5
                                               ${totalClass}"
                                    ></i>

                                </div>


                                <div>

                                    <div
                                        class="text-muted fw-bold"
                                        style="font-size:.7rem;"
                                    >
                                        Solde de la journée
                                    </div>

                                    <strong
                                        class="${totalClass}"
                                        style="font-size:1rem;"
                                    >
                                        ${formatPrice(total.toString())}
                                        Fcfa
                                    </strong>

                                </div>

                            </div>


                            <!-- ============================== -->
                            <!-- NOMBRE -->
                            <!-- ============================== -->

                            <div class="text-end">

                                <div
                                    class="text-muted fw-bold"
                                    style="font-size:.65rem;"
                                >
                                    ${traces.length} mouvement(s)
                                </div>

                            </div>

                        </div>

                    </div>

                `);

            },


            // ======================================================
            // ERROR
            // ======================================================

            error: function(xhr) {

                console.error('Status :', xhr.status);

                console.error(
                    'Réponse serveur :',
                    xhr.responseText
                );

                console.error(
                    'Erreur JSON :',
                    xhr.responseJSON
                );


                $contenu.html(`

                    <div
                        class="d-flex
                               flex-column
                               align-items-center
                               justify-content-center
                               py-5"
                    >

                        <div
                            class="rounded-circle
                                   bg-danger-subtle
                                   d-flex
                                   align-items-center
                                   justify-content-center
                                   mb-3"
                            style="width:60px;height:60px;"
                        >

                            <i
                                class="ri-error-warning-line
                                       fs-3
                                       text-danger"
                            ></i>

                        </div>


                        <strong class="text-danger">
                            Impossible de charger l'historique
                        </strong>


                        <small class="text-muted mt-1">
                            Veuillez réessayer.
                        </small>

                    </div>

                `);


                $total.empty();

            }

        });
    }

});