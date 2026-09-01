$(document).ready(function() {

	const table_cons = $('.Table_Cons').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {

            const date1 = $('#searchDate1').val();
            const date2 = $('#searchDate2').val();
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list_facture/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network Consultation.');
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
                render: (data, type, row) => {
                    return `
                        <span class="">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'statut_regle',
                render: (data, type, row) => {
                    const badgeClass = row.statut_regle == 'Oui' ? 'bg-success' : 'bg-danger';
                    return `
                        <span class="badge ${badgeClass}">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
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
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-info" id="Cfacture" 
                                    data-id="${row.idconsexterne}" data-numfac="${row.numfac}"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer facture
                                </a>
                            </li>
                            ${ row.countRecu > 0 ?
                            `<li>
                                <a href="#" class="dropdown-item text-info" id="printer_recu"
                                    data-recus='${JSON.stringify(row.recus)}' data-id="${row.idconsexterne}" data-numfac="${row.numfac}" data-bs-toggle="modal" data-bs-target="#Detail_recu"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer recu
                                </a>
                            </li>` : ``}
                        </ul>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            init();
        },
    });

    const table_exam = $('.Table_Exam').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {

            const date1 = $('#searchDate1').val();
            const date2 = $('#searchDate2').val();
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list_facture_examen_all/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });
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
                render: (data, type, row) => {
                    return `
                        <span class="">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'statut_regle',
                render: (data, type, row) => {
                    const badgeClass = row.statut_regle == 'Oui' ? 'bg-success' : 'bg-danger';
                    return `
                        <span class="badge ${badgeClass}">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'montant_examen',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'prelevement',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
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
                    return data ? `${formatDate(data)} à ${row.heure}` : 'Néant';
                },
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
                                    data-id="${row.id}" data-numfac="${row.numfac}"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer facture
                                </a>
                            </li>
                            ${ row.countRecu > 0 ?
                            `<li>
                                <a href="#" class="dropdown-item text-info" id="printer_recu"
                                    data-recus='${JSON.stringify(row.recus)}' data-id="${row.id}" data-numfac="${row.numfac}" data-bs-toggle="modal" data-bs-target="#Detail_recu"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer recu
                                </a>
                            </li>` : ``}
                        </ul>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            init();
        },
    });

    const table_hos = $('.Table_Hos').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {
            const date1 = $('#searchDate1').val();
            const date2 = $('#searchDate2').val();
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list_facture_hos_all/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network Hos.');
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
                render: (data, type, row) => {
                    return `
                        <span class="">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'statut_regle',
                render: (data, type, row) => {
                    const badgeClass = row.statut_regle == 'Oui' ? 'bg-success' : 'bg-danger';
                    return `
                        <span class="badge ${badgeClass}">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
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
                    ${ row.montant > 0 ?
                    `<div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-info" id="Cfacture" 
                                    data-id="${row.numhospit}" data-numfac="${row.numfachospit}"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer facture
                                </a>
                            </li>
                            ${ row.countRecu > 0 ?
                            `<li>
                                <a href="#" class="dropdown-item text-info" id="printer_recu"
                                    data-recus='${JSON.stringify(row.recus)}' data-id="${row.numhospit}" data-numfac="${row.numfachospit}" data-bs-toggle="modal" data-bs-target="#Detail_recu"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer recu
                                </a>
                            </li>` : ``}
                        </ul>
                    </div>` : ``}
                `,
                searchable: false,
                orderable: false,
            },
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            init();
        },
    });

    const table_soinsam = $('.Table_Soinsam').DataTable({

        processing: true,
        serverSide: false,
        ajax: function(data, callback) {

            const date1 = $('#searchDate1').val();
            const date2 = $('#searchDate2').val();
            
            $.ajax({
                url: $('#url').attr('content') +`/api/list_facture_soinsam_all/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network Soinsam.');
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
                render: (data, type, row) => {
                    return `
                        <span class="">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'statut_regle',
                render: (data, type, row) => {
                    const badgeClass = row.statut_regle == 'Oui' ? 'bg-success' : 'bg-danger';
                    return `
                        <span class="badge ${badgeClass}">
                            ${data}
                        </span>
                    `;
                },
                searchable: true,
            },
            {
                data: 'montant',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'prototal',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'stotal',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'remise',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_assurance',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
                    return `<span class="${color}">${value} Fcfa</span>`;
                },
                searchable: true,
            },
            {
                data: 'part_patient',
                render: (data, type, row) => {
                    const value = data ? formatPriceT(data) : 0;
                    const color = 'text-dark';
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
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-info" id="Cfacture" 
                                    data-id="${row.id_soins}" data-numfac="${row.numfac}"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer facture
                                </a>
                            </li>
                            ${ row.countRecu > 0 ?
                            `<li>
                                <a href="#" class="dropdown-item text-info" id="printer_recu"
                                    data-recus='${JSON.stringify(row.recus)}' data-id="${row.id_soins}" data-numfac="${row.numfac}" data-bs-toggle="modal" data-bs-target="#Detail_recu"
                                >
                                    <i class="ri-printer-line"></i>
                                    Réimprimer recu
                                </a>
                            </li>` : ``}
                        </ul>
                    </div>
                `,
                searchable: false,
                orderable: false,
            },
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            init();
        },
    });

    //-----------------------------------------------------------------------

    $('#searchDate1').on('change', function() {
        const date1 = $(this).val();
        
        if (date1) {
            $('#searchDate2').val(date1);
            $('#searchDate2').attr('min', date1);
        }
    });

    $('#searchDate2').on('change', function() {
        const date2 = $(this).val();
        const date1 = $('#searchDate1').val();

        if (date2 && date1 && new Date(date2) < new Date(date1)) {
            alert('La deuxiéme date ne peut pas être supérieure à la premiére date.');
            $(this).val(date1);
        }
    });

    //-----------------------------------------------------------------------

    function formatDateSearch(date)
    {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    //-----------------------------------------------------------------------

    function init() {

        const $tables = $('.Table_Cons, .Table_Exam, .Table_Hos, .Table_Soinsam');
        const baseUrl = $('#url').attr('content');


        // =========================================================
        // CONFIGURATION DES FACTURES
        // =========================================================

        const factureConfig = {

            FCE: {
                detailUrl: id => `${baseUrl}/api/fiche_consultation/${id}`,
                facture: data => pdfFactureConsultation(data.facture),
                recu: data => pdfFactureRecuConsultation(data.facture)
            },

            FCS: {
                detailUrl: id => `${baseUrl}/api/imp_fac_soinam/${id}`,
                recuUrl: id => `${baseUrl}/api/detail_soinam/${id}`,

                facture: data => pdfFactureSoins(
                    data.patient,
                    data.soins,
                    data.produit
                ),

                recu: data => pdfFactureRecuSoins(
                    data.patient,
                    data.soins,
                    data.produit
                )
            },

            FCB: {
                detailUrl: id => `${baseUrl}/api/detail_examen/${id}`,

                facture: data => pdfFactureExamen(
                    data.examen,
                    data.facture,
                    data.sumMontantEx
                ),

                recu: data => pdfFactureRecuExamen(
                    data.examen,
                    data.facture,
                    data.sumMontantEx
                )
            },

            FCH: {
                detailUrl: id => `${baseUrl}/api/detail_hos/${id}`,
                recuUrl: id => `${baseUrl}/api/detail_hos_recu/${id}`,

                facture: data => pdfFacturehos(
                    data.hopital,
                    data.prestation
                ),

                recu: data => pdfFactureRecuhos(
                    data.hopital,
                    data.prestation
                )
            }
        };


        // =========================================================
        // RÉCUPÉRER LE TYPE DE FACTURE
        // =========================================================

        function getFactureType(numfac) {

            if (!numfac) {
                return null;
            }

            return Object.keys(factureConfig)
                .find(type => numfac.startsWith(type)) || null;
        }


        // =========================================================
        // AFFICHER UNE FACTURE
        // =========================================================

        $tables
            .off('click', '#Cfacture')
            .on('click', '#Cfacture', function (e) {

                e.preventDefault();

                window.showPreloader();

                const $this = $(this);

                const id = $this.data('id');
                const numfac = String($this.data('numfac') || '');

                const type = getFactureType(numfac);
                const config = factureConfig[type];

                if (!config) {

                    window.hidePreloader();

                    console.error('Type de facture inconnu :', numfac);

                    return;
                }

                $.ajax({

                    url: config.detailUrl(id),
                    method: 'GET',

                    success: function (data) {

                        config.facture(data);

                    },

                    error: function (xhr, status, error) {

                        console.error(
                            'Erreur lors du chargement de la facture :',
                            error
                        );

                        showAlert(
                            'Erreur',
                            'Impossible de charger les données de la facture.',
                            'error'
                        );
                    },

                    complete: function () {

                        window.hidePreloader();

                    }
                });

            });


        // =========================================================
        // AFFICHER LA LISTE DES REÇUS
        // =========================================================

        $tables
            .off('click', '#printer_recu')
            .on('click', '#printer_recu', function (e) {

                e.preventDefault();

                const $this = $(this);

                const id = $this.data('id');
                const numfac = String($this.data('numfac') || '');

                let recus = $this.data('recus') || [];

                /*
                 * Si les reçus sont stockés sous forme JSON
                 * dans data-recus, on les convertit.
                 */
                if (typeof recus === 'string') {

                    try {
                        recus = JSON.parse(recus);

                    } catch (error) {

                        console.error(
                            'Impossible de convertir les reçus :',
                            error
                        );

                        recus = [];
                    }
                }

                const $tableBody = $('#TableRecu tbody');

                $tableBody.empty();


                // Aucun reçu
                if (!Array.isArray(recus) || recus.length === 0) {

                    $tableBody.append(`
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Aucun reçu disponible.
                            </td>
                        </tr>
                    `);

                    return;
                }


                // Liste des reçus
                $.each(recus, function (index, item) {

                    const isLast = index === recus.length - 1;

                    $tableBody.append(`

                        <tr>

                            <td>
                                <h6 class="mb-0">
                                    ${index + 1}
                                </h6>
                            </td>

                            <td>
                                <h6 class="mb-0">
                                    ${item.numrecu}
                                </h6>
                            </td>

                            <td>
                                <h6 class="mb-0">
                                    ${formatPriceT(item.montant)} Fcfa
                                </h6>
                            </td>

                            <td>
                                <h6 class="mb-0">
                                    ${formatDate(item.date)}
                                </h6>
                            </td>

                            <td>
                                ${
                                    isLast
                                        ? `
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary"
                                                id="imprimeRecu"
                                                data-id="${id}"
                                                data-numfac="${numfac}"
                                                title="Réimprimer le dernier reçu"
                                                data-bs-dismiss="modal">

                                                <i class="ri-printer-line"></i>

                                            </button>
                                        `
                                        : ''
                                }
                            </td>

                        </tr>

                    `);

                });


                // =====================================================
                // NOTE
                // =====================================================

                $tableBody.append(`

                    <tr>

                        <td colspan="5">

                            <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">

                                <i class="ri-information-line fs-5"></i>

                                <div>

                                    <strong>NOTE</strong>

                                    <div class="small mt-1">
                                        Les reçus ci-dessus correspondent aux
                                        paiements déjà enregistrés pour cette facture.
                                        Seul le dernier reçu peut être réimprimé.
                                    </div>

                                </div>

                            </div>

                        </td>

                    </tr>

                `);

            });


        // =========================================================
        // RÉIMPRIMER LE DERNIER REÇU
        // =========================================================

        $('#TableRecu')
            .off('click', '#imprimeRecu')
            .on('click', '#imprimeRecu', function (e) {

                e.preventDefault();

                window.showPreloader();

                const $this = $(this);

                const id = $this.data('id');
                const numfac = String($this.data('numfac') || '');

                const type = getFactureType(numfac);
                const config = factureConfig[type];

                if (!config) {

                    window.hidePreloader();

                    console.error(
                        'Type de reçu inconnu :',
                        numfac
                    );

                    return;
                }


                /*
                 * Pour FCE, detailUrl = fiche_consultation
                 * Pour les autres, on utilise recuUrl.
                 */
                const url = config.recuUrl
                    ? config.recuUrl(id)
                    : config.detailUrl(id);


                $.ajax({

                    url: url,
                    method: 'GET',

                    success: function (data) {

                        config.recu(data);

                    },

                    error: function (xhr, status, error) {

                        console.error(
                            'Erreur lors du chargement du reçu :',
                            error
                        );

                        showAlert(
                            'Erreur',
                            'Impossible de charger les données du reçu.',
                            'error'
                        );
                    },

                    complete: function () {

                        window.hidePreloader();

                    }

                });

            });

    }

    const tables = {
        cons: table_cons,
        exam: table_exam,
        hos: table_hos,
        soinsam: table_soinsam
    };

    $('[id^="btn_refresh_table_"]').on('click', function () {

        const $btn = $(this);
        const tableName = $btn.data('table');
        const months = Number($btn.data('months')) || 1;

        const table = tables[tableName];

        if (!table) return;

        const today = new Date();
        const startDate = new Date(today);

        startDate.setMonth(today.getMonth() - months);

        $('#searchDate1').val(formatDateSearch(startDate));
        $('#searchDate2').val(formatDateSearch(today));

        table.ajax.reload(null, false);
    });

    const searchTables = {
        Cons: table_cons,
        Exam: table_exam,
        Hos: table_hos,
        Soinsam: table_soinsam
    };

    $('[id^="btn_search_"]').on('click', function () {

        const type = this.id.replace('btn_search_', '');
        const table = searchTables[type];

        if (table) {
            table.ajax.reload(null, false);
        }

    });

});