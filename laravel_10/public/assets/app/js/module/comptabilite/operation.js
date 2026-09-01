$(document).ready(function() {

	initCaisseEvents();
    caisse_verf();

    window.initMontantInput([
        'montant_ope',
    ]);

    $("#btn_eng_ope").on("click", eng_ope);

    $("#btn_ouvert_C").on("click", caisse_ouvert);
    $("#btn_fermer_C").on("click", caisse_fermer);

    function caisse_verf() {

        const $contenu = $('#contenu_caisse');

        // Loader
        $contenu.html(`
            <div class="d-flex justify-content-center align-items-center py-4">
                <div class="spinner-border text-warning me-2" role="status"></div>
                <strong>Chargement des données...</strong>
            </div>
        `);

        $.ajax({
            url: $('#url').attr('content') + '/api/verf_caisse',
            method: 'GET',

            success: function (data) {

                const caisse = data.caisse;
                const ouverte = caisse.statut === 'ouvert';

                $contenu.html(`
                    <div class="p-4">

                        <!-- SOLDE -->
                        <div class="d-flex align-items-center justify-content-between mb-3">

                            <div>
                                <div class="text-muted small fw-semibold mb-1">
                                    SOLDE ACTUEL
                                </div>

                                <div class="fs-3 fw-bold text-primary" id="soldeCaisse">
                                    ${formatPrice(caisse.montant)} Fcfa
                                </div>
                            </div>

                            <button type="button"
                                    id="btn_refresh_soldCaisse"
                                    class="btn btn-warning border rounded-circle
                                           d-flex align-items-center justify-content-center"
                                    style="width:42px;height:42px;"
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

                                <button type="button"
                                        id="btn_ouvert_C"
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

                                <button type="button"
                                        id="btn_fermer_C"
                                        class="btn btn-danger rounded-3 px-3">

                                    <i class="ri-door-close-line me-1"></i>
                                    Fermer

                                </button>

                            </div>

                        </div>

                    </div>
                `);

                // Gérer tous les affichages
                updateCaisseDisplay(ouverte);
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

                // En cas d'erreur, on masque les éléments dépendants
                updateCaisseDisplay(false);
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

                    // Recharger l'état réel depuis le serveur
                    updateCaisseDisplay(true);
                    table_Ofc.ajax.reload(null, false);

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

                    // Recharger l'état réel depuis le serveur
                    updateCaisseDisplay(false);
                    table_Ofc.ajax.reload(null, false);

                } else {

                    showAlert(
                        'Alert',
                        "Une erreur est survenue lors de la fermeture de la caisse.",
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

    function updateCaisseDisplay(ouverte) {

        // Onglet "Nouvelle opération"
        $('#tab_caisse_operation').toggle(ouverte);

        // Formulaire nouvelle opération
        $('#div_caisse').toggle(ouverte);

        // Bloc caisse fermée
        $('#btn_ouvert').toggle(!ouverte);

        // Bloc caisse ouverte
        $('#btn_fermer').toggle(ouverte);


	    const $tabOperation = $('#tabOperation');
	    const $tabOperationLink = $('#tabOperationLink');

	    const $tabHistoriqueLink = $('#tabHistoriqueLink');

	    const $operation = $('#operation');
	    const $historique = $('#historique');

	    // Détecter l'onglet actuellement actif
	    const operationActive = $tabOperationLink.hasClass('active');
	    const historiqueActive = $tabHistoriqueLink.hasClass('active');


	    // ==========================================================
	    // CAISSE OUVERTE
	    // ==========================================================

	    if (ouverte) {

	        // L'onglet Opération devient disponible
	        $tabOperation.show();

	        // Réinitialiser le formulaire
            $('#type_ope').val('');
            $('#montant_ope').val('');
            $('#bene_ope').val('');
            $('#libelle_ope').val('');
            $('#date_ope').val(getDateToday());


	        // Si on était déjà sur Opération
	        if (operationActive) {

	            $tabOperationLink
	                .addClass('active')
	                .attr('aria-selected', 'true');

	            $tabHistoriqueLink
	                .removeClass('active')
	                .attr('aria-selected', 'false');

	            $operation.addClass('show active');
	            $historique.removeClass('show active');

	        }

	        // Si on était sur Historique,
	        // on reste sur Historique
	        else if (historiqueActive) {

	            $tabHistoriqueLink
	                .addClass('active')
	                .attr('aria-selected', 'true');

	            $tabOperationLink
	                .removeClass('active')
	                .attr('aria-selected', 'false');

	            $historique.addClass('show active');
	            $operation.removeClass('show active');
	        }

	    }


	    // ==========================================================
	    // CAISSE FERMÉE
	    // ==========================================================

	    else {

	        // L'onglet Opération devient indisponible
	        $tabOperation.hide();


	        // Si on était sur Opération,
	        // on bascule automatiquement vers Historique
	        if (operationActive) {

	            $tabOperationLink
	                .removeClass('active')
	                .attr('aria-selected', 'false');

	            $tabHistoriqueLink
	                .addClass('active')
	                .attr('aria-selected', 'true');

	            $operation.removeClass('show active');
	            $historique.addClass('show active');
	        }


	        // Si on était déjà sur Historique,
	        // on ne change rien
	        else if (historiqueActive) {

	            $tabHistoriqueLink
	                .addClass('active')
	                .attr('aria-selected', 'true');

	            $historique.addClass('show active');
	        }
	    }
    }

    function initCaisseEvents() {

        $(document)
            .off('click.caisse', '#btn_refresh_soldCaisse')
            .on('click.caisse', '#btn_refresh_soldCaisse', function () {

                caisse_verf();

            });


        $(document)
            .off('click.caisse', '#btn_ouvert_C')
            .on('click.caisse', '#btn_ouvert_C', function () {

                caisse_ouvert();

            });


        $(document)
            .off('click.caisse', '#btn_fermer_C')
            .on('click.caisse', '#btn_fermer_C', function () {

                caisse_fermer();

            });
    }

    function eng_ope() {
        const type_ope = $('#type_ope');
        const montant_ope = $('#montant_ope');
        const bene_ope = $('#bene_ope');
        const libelle_ope = $('#libelle_ope');
        const date_ope = $('#date_ope');

        // Vérification des champs obligatoires
        if (
            !type_ope.val().trim() ||
            !montant_ope.val().trim() ||
            !libelle_ope.val().trim() ||
            !date_ope.val().trim()
        ) {
            showAlert(
                'Alert',
                'Veuillez remplir tous les champs Obligatoire SVP.',
                'warning'
            );
            return false;
        }

        window.showPreloader();

        $.ajax({
            url: $('#url').attr('content') + '/api/ope_caisse_new',
            method: 'GET',
            data: {
                type_ope: type_ope.val(),
                montant_ope: montant_ope.val(),
                libelle_ope: libelle_ope.val(),
                date_ope: date_ope.val(),
                bene_ope: bene_ope.val()
            },

            success: function(response) {

                window.hidePreloader();

                if (response.success) {

                    // Réinitialisation des champs
                    type_ope.val('');
                    montant_ope.val('');
                    bene_ope.val('');
                    libelle_ope.val('');
                    date_ope.val(getDateToday());

                    // Mise à jour du solde
                    $('#soldeCaisse').html(
                        formatPrice(response.solde) + ' Fcfa'
                    );

                    // Rechargement du DataTable
                    table_OpC.ajax.reload(null, false);

                    showAlert(
                        'Succès',
                        'Opération effectuée.',
                        'success'
                    );

                } else if (response.error) {

                    showAlert(
                        'Alert',
                        "Échec de l'opération.",
                        'error'
                    );

                } else if (response.solde_negatif) {

                    showAlert(
                        'Alert',
                        'Le montant de l\'opération est supérieur au montant actuel de la caisse.',
                        'warning'
                    );

                } else if (response.caisse_fermer) {

                    updateCaisseDisplay(false);

                    showAlert(
                        'Alert',
                        "La caisse est actuellement fermée, veuillez ouvrir la caisse avant d'effectuer une opération.",
                        'info'
                    );

                } else if (response.caisse_inferieur) {

                    showAlert(
                        'Alert',
                        'Le montant de l\'opération est supérieur au solde de la caisse.',
                        'info'
                    );
                }
            },

            error: function(xhr) {

                window.hidePreloader();

                showAlert(
                    'Alert',
                    "Une erreur est survenue lors de l'enregistrement.",
                    'error'
                );
            }
        });

        return false;
    }

    const table_OpC = $('.Table_OpC').DataTable({

        processing: false,
        serverSide: false,
        ajax: function(data, callback) {

            const date1 = $('#searchDate1').val();
            const date2 = $('#searchDate2').val();

            if (!date1.trim() || !date2.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
            }

            const startDate = new Date(date1);
            const endDate = new Date(date2);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            // const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
            // if (endDate - startDate > oneYearInMs) {
            //     showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
            //     return false;
            // }
            
            $.ajax({
                url: $('#url').attr('content') +`/api/trace_operation/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });

                    document.querySelector("#stat_bord_total").innerHTML = '';

                    var stat = `
                        <div class="d-flex flex-wrap gap-1 justify-content-center align-items-center">
                            <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-success text-white">
                                <i class="ri-pie-chart-2-fill text-white fs-4 me-2"></i>
                                <span class="fw-semibold">Entrées : </span>
                                <span class="me-1 text-white ps-1">+ ${formatPrice(response.montant.entrer)} Fcfa</span>
                            </div>
                            <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-danger text-white">
                                <i class="ri-pie-chart-2-fill text-white fs-4 me-2"></i>
                                <span class="fw-semibold">Sorties : </span>
                                <span class="me-1 text-white ps-1">- ${formatPrice(response.montant.sortie)} Fcfa</span>
                            </div>
                            <div class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 bg-primary text-white">
                                <i class="ri-pie-chart-2-fill text-white fs-4 me-2"></i>
                                <span class="fw-semibold">Total : </span>
                                <span class="me-1 text-white ps-1">${formatPrice(response.montant.total)} Fcfa</span>
                            </div>
                        </div>
                    `;
                    document.querySelector("#stat_bord_total").innerHTML = stat;

                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network trace_operation.');
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
                data: 'login',
                render: function (data, type, row) { return `${data}` },
                searchable: true, 
            },
            { 
                data: 'libelle',
                searchable: true,
            },
            {
                data: 'type',
                searchable: true,
                render: function (data, type, row) {
                    if (data === 'entree') {
                        return `<span class="fs-6 badge bg-success-subtle text-success">
                                    Entrer
                                </span>`;
                    } else {
                        return `<span class="fs-6 badge bg-danger-subtle text-danger">
                                    Sortie
                                </span>`;
                    }
                }
            },
            {
                data: 'type',
                searchable: true,
                render: function (data, type, row) {
                    if (data === 'entree') {
                        return `<span class="fs-6 badge bg-success-subtle text-success">
                                    + ${formatPrice(row.montant)} Fcfa
                                </span>`;
                    } else {
                        return `<span class="fs-6 badge bg-danger-subtle text-danger">
                                    - ${formatPrice(row.montant)} Fcfa
                                </span>`;
                    }
                }
            },
            {
                data: 'dateop',
                searchable: false,
                render: function (data, type, row) {
                    return `<td>${formatDate(data)}</td>`;
                }
            },
            { 
                data: 'datecreat', 
                render: formatDateHeure,
                searchable: true, 
            },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-inline-flex gap-1" style="font-size:10px;">
                        <a class="btn btn-outline-warning btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#Detail" 
                            id="detail"
                            data-type="${row.type}"
                            data-montant="${row.montant}"
                            data-motif="${row.libelle}"
                            data-dateop="${row.dateop}"
                            data-datecreat="${row.datecreat}"
                            data-login="${row.login}"
                            data-login_recu="${row.beneficiaire}"
                            data-reference="${row.nopiece}"
                        >
                            <i class="ri-eye-line"></i>
                        </a>
                    </div>
                `,
                searchable: false,
                orderable: false,
            }
        ],
        ...dataTableConfig,
        initComplete: function(settings, json) {
            initializeRowEventListenersTable_OpC();
        },
    });

    function initializeRowEventListenersTable_OpC() {

        $('.Table_OpC').on('click', '#detail', function() {
            const type = $(this).data('type');
            const montant = formatPrice($(this).data('montant'));
            const motif = $(this).data('motif');

            const dateop = $(this).data('dateop');
            const datecreat = $(this).data('datecreat');

            const login = $(this).data('login');
            const login_recu = $(this).data('login_recu');
            const reference = $(this).data('reference');
            
            const modal = document.getElementById('modal_detail');
            modal.innerHTML = '';

            const div = document.createElement('div');
            div.innerHTML = `
                   <div class="row gx-3">
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active text-center" aria-current="true">
                                            Informations de l'operation
                                        </li>
                                        ${reference !== null ? 
                                            `
                                            <li class="list-group-item">
                                                Référence : ${reference}
                                            </li>
                                            ` : '' }
                                        <li class="list-group-item">
                                            Type de Mouvement : ${type == 'entree' ? 'Entrer de Caisse' : 'Sortie de Caisse' }
                                        </li>
                                        <li class="list-group-item ${type == 'entree' ? 'text-success' : 'text-danger' }">
                                            Montant : ${type == 'entree' ? '+ '+montant : '- '+montant } Fcfa
                                        </li>
                                        <li class="list-group-item">
                                            Motif : ${motif}
                                        </li>
                                        <li class="list-group-item">
                                            Créer par : ${login} 
                                        </li>
                                        ${login_recu !== null ? 
                                            `
                                            <li class="list-group-item">
                                                Montant remis à : ${login_recu}
                                            </li>
                                            ` : '' }
                                        <li class="list-group-item">
                                            Date de l'opération : ${formatDate(dateop)}
                                        </li>
                                        <li class="list-group-item">
                                            Date de création : ${formatDate(datecreat)}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>     
            `;

            modal.appendChild(div);
        });

    }

    $('#btn_search_trace').on('click', function() {
        table_OpC.ajax.reload(null, false); 
    });

    const table_Ofc = $('.Table_Ofc').DataTable({

        processing: false,
        serverSide: false,
        ajax: function(data, callback) {

            const date1 = $('#searchDate1_ofc').val();
            const date2 = $('#searchDate2_ofc').val();

            if (!date1.trim() || !date2.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.','warning');
                return false; 
            }

            const startDate = new Date(date1);
            const endDate = new Date(date2);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
                return false;
            }

            // const oneYearInMs = 365 * 24 * 60 * 60 * 1000;
            // if (endDate - startDate > oneYearInMs) {
            //     showAlert('Erreur', 'La plage de dates ne peut pas dépasser un an.', 'error');
            //     return false;
            // }
            
            $.ajax({
                url: $('#url').attr('content') +`/api/trace_ouvert_fermer/${date1}/${date2}`,
                type: 'GET',
                success: function(response) {
                    callback({ data: response.data });
                },
                error: function() {
                    console.log('Error fetching data. Please check your API or network trace_ouvert_fermer.');
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
                data: 'action',
                render: function(data, type, row) {
                    if (data === 0) {
                        return `<span class="fs-6 badge bg-success-subtle text-success">
                            Ouverture de la caisse
                            </span>`;
                    } else if (data === 1) {
                        return `<span class="fs-6 badge bg-info-subtle text-info">Opération journalière</span>`;
                    } else if (data === 2) {
                        return `<span class="fs-6 badge bg-danger-subtle text-danger">Fermeture de la caisse</span>`;
                    }
                },
                searchable: true
            },
            {
                data: 'mtcaisse',
                render: function(data, type, row) {
                    return `${formatPrice(data)} Fcfa`;
                },
                searchable: true
            },
            {
                data: 'user', // Combine `user_sexe` and `user` fields
                render: function(data, type, row) {
                    return `${data}`;
                },
                searchable: false
            },
            {
                data: 'datecaisse',
                render: function(data, type, row) {
                    return `${formatDateHeure(data)}`; // Utilise votre fonction de formatage
                },
                searchable: false
            }
        ],
        ...dataTableConfig,
    });

    $('#btn_search_trace_ofc').on('click', function() {
        table_Ofc.ajax.reload(null, false); 
    });

});