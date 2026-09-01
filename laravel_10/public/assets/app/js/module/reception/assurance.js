$(function () {

    select_assurance('#assurance_id');

    numberTel('#tel_assurance_new');
    numberTel('#telModif');

    OffClick('#btn_eng_assurance', eng_assurance);
    OffClick('#updateBtn', updatee);
    OffClick('#deleteBtn', deletee);

    OffClick('#btn_refresh_tableP', function () {
        $('#Table_day').DataTable().ajax.reload();
    });

    OffChange('#assurance_id', function () {

        const $dynamicFields = $('#div_info_patient');
        $dynamicFields.empty();

        if ($(this).val() === '') {
            showAlert('Alert', 'Veuillez selectionner une assurance s\'il vous plaît.', 'warning');
            return false;
        }

        const assurance_id = $(this).val();

        // Affichage du loader
        $dynamicFields.html(`
            <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                <strong>Chargement des données...</strong>
            </div>
        `);

        const url = $('#url').attr('content') + `/api/assurance_stat/${assurance_id}`;

        $.getJSON(url)
            .done(function(data) {

                $('#laoder_stat').remove();

                const nbre_cons = data.nbre_cons;
                const nbre_hos = data.nbre_hos;
                const nbre_exam = data.nbre_exam;
                const nbre_soinsam = data.nbre_soinsam;
                const stats = data.data;

                // Titre statistiques
                const $groupe1 = $(`
                    <div class="row gx-3 mb-0">
                        <div class="card-body">
                            <div class="card-header d-flex flex-column justify-content-center align-items-center">
                                <h5 class="card-title mb-3">Statistique des actes éffectués</h5>
                            </div>
                        </div>
                    </div>
                `);
                $dynamicFields.append($groupe1);

                // Conteneur des cartes
                const $groupe01 = $('<div class="row gx-3 stat_acte"></div>');
                $dynamicFields.append($groupe01);

                const cardData_acte = [
                    { 
                        label: "Consultations", 
                        count: nbre_cons, 
                        icon: "ri-lungs-line", 
                        colorClass: "text-success", 
                        borderColor: "border-success", 
                        bgColor: "bg-success", 
                        mTotal : formatPriceT(stats.m_cons.total_general), 
                        pTotal : formatPriceT(stats.m_cons.total_payer), 
                        ipTotal : formatPriceT(stats.m_cons.total_impayer), 
                        assurance : formatPriceT(stats.m_cons.part_assurance), 
                        patient : formatPriceT(stats.m_cons.part_patient)},
                    { 
                        label: "Examens", 
                        count: nbre_exam, 
                        icon: "ri-medicine-bottle-line", 
                        colorClass: "text-danger", 
                        borderColor: "border-danger", 
                        bgColor: "bg-danger", 
                        mTotal : formatPriceT(stats.m_exam.total_general), 
                        pTotal : formatPriceT(stats.m_exam.total_payer), 
                        ipTotal : formatPriceT(stats.m_exam.total_impayer), 
                        assurance : formatPriceT(stats.m_exam.part_assurance), 
                        patient : formatPriceT(stats.m_exam.part_patient)},
                    { 
                        label: "Hospitalisations", 
                        count: nbre_hos, 
                        icon: "ri-hotel-bed-line", 
                        colorClass: "text-primary", 
                        borderColor: "border-primary", 
                        bgColor: "bg-primary", 
                        mTotal : formatPriceT(stats.m_hos.total_general), 
                        pTotal : formatPriceT(stats.m_hos.total_payer), 
                        ipTotal : formatPriceT(stats.m_hos.total_impayer), 
                        assurance : formatPriceT(stats.m_hos.part_assurance), 
                        patient : formatPriceT(stats.m_hos.part_patient)},
                    { 
                        label: "Soins Ambulatoires", 
                        count: nbre_soinsam, 
                        icon: "ri-dossier-line", 
                        colorClass: "text-warning", 
                        borderColor: "border-warning", 
                        bgColor: "bg-warning", 
                        mTotal : formatPriceT(stats.m_soinsam.total_general), 
                        pTotal : formatPriceT(stats.m_soinsam.total_payer), 
                        ipTotal : formatPriceT(stats.m_soinsam.total_impayer), 
                        assurance : formatPriceT(stats.m_soinsam.part_assurance), 
                        patient : formatPriceT(stats.m_soinsam.part_patient)},
                ];

                // Génération des cartes
                $.each(cardData_acte, function(index, card) {
                    const $div = $(`
                        <div class="col-xxl-3 col-xl-6 col-sm-6 col-12">
                            <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="p-2 ${card.borderColor} rounded-circle me-3">
                                            <div class="icon-box md ${card.bgColor} rounded-5">
                                                <i class="${card.icon} fs-4 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h2 class="lh-1">${card.count}</h2>
                                            <p class="m-0">${card.label}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Montant Total</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.mTotal} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Part Assurance</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.assurance} Fcfa</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between mt-1">
                                        <a class="${card.colorClass}" href="javascript:void(0);">
                                            <span>Part Patient</span>
                                            <i class="ri-arrow-right-line ${card.colorClass} ms-1"></i>
                                        </a>
                                        <div class="text-end">
                                            <p class="mb-0 ${card.colorClass}">${card.patient} Fcfa</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    $groupe01.append($div);
                });

            })
            .fail(function(error) {
                console.error('Erreur lors du chargement des données:', error);
            });
    });

    function eng_assurance() {

        const nom = $('#nom_assurance_new');
        const email = $('#email_assurance_new');
        const tel = $('#tel_assurance_new');
        const adresse = $('#adresse_assurance_new');
        const fax = $('#fax_assurance_new');
        const carte = $('#carte_assurance_new');
        const desc = $('#desc_assurance_new');

        if (!nom.val().trim() || !email.val().trim() || !tel.val().trim() || !adresse.val().trim() || !carte.val().trim()) {
            showAlert('Alert', 'Tous les champs obligatoires n\'ont pas été rempli.', 'warning');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.val())) {
            showAlert('Alert', 'Email incorrect.', 'warning');
            return;
        }

        if (tel.val().length !== 10) {
            showAlert('Alert', 'Contact incomplet.', 'warning');
            return;
        }

        const label = $('#btn_eng_assurance').text();
        const idBtn = '#btn_eng_assurance';
        spinerButton(1, idBtn, 'Vérification');

        $.get($('#url').attr('content') +'/api/assurance_new', {
            nom: nom.val(),
            email: email.val(),
            tel: tel.val(),
            adresse: adresse.val(),
            fax: fax.val() || null,
            carte: carte.val(),
            desc: desc.val() || null
        }).done(function (res) {

            spinerButton(0, idBtn, label);

            if (res.tel_existe) {
                showAlert('Alert', 'Ce numéro de contact appartient déjà à une assurance.', 'warning');
            } else if (res.email_existe) {
                showAlert('Alert', 'Cet email appartient déjà à une assurance.', 'warning');
            } else if (res.nom_existe) {
                showAlert('Alert', 'Cette assurance existe déjà.', 'warning');
            } else if (res.fax_existe) {
                showAlert('Alert', 'Ce fax appartient déjà à une assurance.', 'warning');
            } else if (res.success) {
                // Réinitialiser les champs du formulaire
                $('#nom_assurance_new').val('');
                $('#email_assurance_new').val('');
                $('#tel_assurance_new').val('');
                $('#desc_assurance_new').val('');
                $('#fax_assurance_new').val('');
                $('#adresse_assurance_new').val('');
                $('#carte_assurance_new').val('');

                select_assurance('#assurance_id');

                // Recharger la DataTable
                $('#Table_day').DataTable().ajax.reload();

                // Afficher le message de succès
                showAlert('Succès', res.message, 'success');
            } else if (res.error) {
                showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
            }

        }).fail(function () {
            spinerButton(0, idBtn, label);
            showAlert('Alert', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
        });
    }

    function updatee() {

        const id = $('#Id').val();

        if (!$('#nomModif').val().trim() || !$('#emailModif').val().trim() || !$('#telModif').val().trim()) {
            showAlert('Alert', 'Champs obligatoires manquants.', 'warning');
            return;
        }

        const label = $('#updateBtn').text();
        const idBtn = '#updateBtn';
        spinerButton(1, idBtn, 'Vérification');

        $.get($('#url').attr('content') +'/api/update_assurance/' + id, {
            nom: $('#nomModif').val(),
            email: $('#emailModif').val(),
            tel: $('#telModif').val(),
            adresse: $('#adresseModif').val(),
            fax: $('#faxModif').val(),
            carte: $('#carteModif').val(),
            desc: $('#descModif').val()
        }).done(function (res) {

            spinerButton(0, idBtn, label);

            if (res.success) {
                $('#Mmodif').modal('hide');
                $('#Table_day').DataTable().ajax.reload();
                showAlert('Succès', 'Opération effectuée.', 'success');
            }
        }).fail(function () {
            spinerButton(0, idBtn, label);
            showAlert('Erreur', 'Erreur de mise à jour.', 'error');
        });
    }

    function deletee() {
        const id = $('#Id_delete').val().trim();

        // Masquer le modal
        $('#Mdelete').modal('hide');

        // Préloader
        showPreloader();

        $.ajax({
            url: $('#url').attr('content') +'/api/delete_assurance/' + id,
            method: 'GET', // conservé GET comme dans ton original
            success: function(response) {
                hidePreloader();

                if (response.success) {
                    $('#Table_day').DataTable().ajax.reload();
                    showAlert('Succès', response.message, 'success');
                } else if (response.error) {
                    showAlert('Erreur', response.message, 'error');
                }
            },
            error: function() {
                hidePreloader();
                showAlert('Erreur', 'Erreur lors de la suppression.', 'error');
            }
        });
    }

    $('#Table_day').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: $('#url').attr('content') + '/api/list_assurance_all',
            type: 'GET',
            dataSrc: 'data',
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
                searchable: false,
                orderable: false
            },
            {
                data: 'libelleassurance',
                render: function(data) {
                    return `
                        <div class="d-flex align-items-center">
                            <a class="d-flex align-items-center flex-column me-2">
                                <img src="assets/images/assurance3.jpg" class="img-2x rounded-circle border border-1">
                            </a>
                            ${data}
                        </div>
                    `;
                },
                searchable: true
            },
            {
                data: 'emailassurance',
                render: function(data) { return data ? data : 'Néant'; },
                searchable: true
            },
            {
                data: 'telassurance',
                render: function(data) { return data ? data : 'Néant'; },
                searchable: true
            },
            {
                data: 'faxassurance',
                render: function(data) { return data ? data : 'Néant'; },
                searchable: true
            },
            {
                data: 'adrassurance',
                render: function(data) { return data ? data : 'Néant'; },
                searchable: true
            },
            {
                data: 'situationgeo',
                render: function(data) { return data ? (data.length > 15 ? data.substring(0,15) + '...' : data) : 'Néant'; },
                searchable: true
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#Detail" id="detail" 
                                        data-id="${row.idassurance}" 
                                        data-code="${row.codeassurance}" 
                                        data-nom="${row.libelleassurance}" 
                                        data-email="${row.emailassurance}" 
                                        data-tel="${row.telassurance}"
                                        data-adresse="${row.adrassurance}" 
                                        data-fax="${row.faxassurance}"
                                        data-carte="${row.situationgeo}"
                                        data-description="${row.description}"
                                    >
                                        <i class="ri-eye-line"></i> Détail
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif" 
                                        data-id="${row.idassurance}" 
                                        data-code="${row.codeassurance}" 
                                        data-nom="${row.libelleassurance}" 
                                        data-email="${row.emailassurance}" 
                                        data-tel="${row.telassurance}"
                                        data-adresse="${row.adrassurance}" 
                                        data-fax="${row.faxassurance}"
                                        data-carte="${row.situationgeo}"
                                        data-description="${row.description == null || row.description == '' ? 'Néant' : row.description}"
                                    >
                                        <i class="ri-edit-box-line"></i> Modifier
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `;
                },
                searchable: false,
                orderable: false
            }
        ],
        ...dataTableConfig,
        initComplete: function() {
            initializeRowEventListeners();
        }
    });

    function initializeRowEventListeners() {

        /* ===== DETAIL ===== */
        $('#Table_day').off('click', '#detail').on('click', '#detail', function() {
            const row = {
                id: $(this).data('id'),
                code: $(this).data('code'),
                nom: $(this).data('nom'),
                email: $(this).data('email'),
                tel: $(this).data('tel'),
                adresse: $(this).data('adresse'),
                fax: $(this).data('fax'),
                carte: $(this).data('carte'),
                description: $(this).data('description')
            };

            const $modal = $('#modal_detail').html('');
            const $div = $(`
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="card-body text-center">
                                <a class="d-flex align-items-center flex-column">
                                    <img src="assets/images/assurance3.jpg" class="img-7x rounded-circle mb-3 border border-3">
                                    <h5>${row.nom}</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item active text-center" aria-current="true">Informations</li>
                                    <li class="list-group-item">Code : ${row.code}</li>
                                    <li class="list-group-item">Nom : ${row.nom}</li>
                                    <li class="list-group-item">Email : ${row.email ? row.email : 'Néant'}</li>
                                    <li class="list-group-item">Téléphone : ${row.tel ? row.tel : 'Néant'}</li>
                                    <li class="list-group-item">Fax : ${row.fax ? row.fax : 'Néant'}</li>
                                    <li class="list-group-item">Adresse : ${row.adresse ? row.adresse : 'Néant'}</li>
                                    <li class="list-group-item">Situation Géographique : ${row.carte ? row.carte : 'Néant'}</li>
                                    <li class="list-group-item">Description : ${row.description ? row.description : 'Néant'}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $modal.append($div);
        });

        /* ===== MODIFIER ===== */
        $('#Table_day').off('click', '#modif').on('click', '#modif', function() {
            $('#Id').val($(this).data('id'));
            $('#nomModif').val($(this).data('nom'));
            $('#emailModif').val($(this).data('email'));
            $('#adresseModif').val($(this).data('adresse'));
            $('#telModif').val($(this).data('tel'));
            $('#descModif').val($(this).data('description'));
            $('#faxModif').val($(this).data('fax'));
            $('#carteModif').val($(this).data('carte'));
        });

        // DELETE button (si besoin)
        // $('#Table_day').on('click', '#delete', function() {
        //     $('#Id_delete').val($(this).data('id'));
        // });
    }
   
});
