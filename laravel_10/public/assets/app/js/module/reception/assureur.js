$(document).ready(function () {

    OffClick('#btn_eng', eng_assureur);
    OffClick('#updateBtn', updatee);
    OffClick('#deleteBtn', deletee);

    OffClick('#btn_refresh_tableP', function () {
        $('#Table_day').DataTable().ajax.reload(null, false);
    });

    function eng_assureur() {
        const nom = $('#nom').val().trim();

        if (!nom) {
            showAlert('Alert', 'Veuillez remplir le champ.', 'warning');
            return;
        }

        const label = $('#btn_eng').text();
        const idBtn = '#btn_eng';
        spinerButton(1, idBtn, 'Vérification');

        $.ajax({
            url: $('#url').attr('content') + '/api/assureur_new',
            method: 'GET',
            data: { nom },
            success(response) {
                spinerButton(0, idBtn, label);

                if (response.nom_existe) {
                    showAlert('Alert', 'Cet assureur existe déjà.', 'warning');
                } else if (response.success) {
                    $('#nom').val('');
                    $('#Table_day').DataTable().ajax.reload(null, false);
                    showAlert('Succès', response.message, 'success');
                } else {
                    showAlert('Erreur', 'Une erreur est survenue.', 'error');
                }
            },
            error() {
                spinerButton(0, idBtn, label);
                showAlert('Erreur', 'Une erreur est survenue.', 'error');
            }
        });
    }

    /* =========================
       DATATABLE
    ========================= */
    const table = $('#Table_day').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: $('#url').attr('content') + '/api/list_assureur_all',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                render: (d, t, r, m) => m.row + 1,
                orderable: false
            },
            {
                data: 'libelle_assureur',
                render: data => `
                    <div class="d-flex align-items-center">
                        <img src="/assets/images/assureur.avif"
                             class="img-2x rounded-circle border me-2">
                        ${data}
                    </div>
                `
            },
            {
                data: null,
                orderable: false,
                render: row => `
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" data-bs-toggle="dropdown">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="#" class="dropdown-item text-info btn-modif"
                                   data-id="${row.codeassureur}"
                                   data-nom="${row.libelle_assureur}">
                                    <i class="ri-edit-box-line"></i> Modifier
                                </a>
                            </li>
                        </ul>
                    </div>
                `
            }
        ],
        ...dataTableConfig
    });

    /* =========================
       TABLE ACTIONS
    ========================= */
    $('#Table_day')
        .on('click', '.btn-modif', function () {
            $('#Id').val($(this).data('id'));
            $('#nomModif').val($(this).data('nom'));
            $('#Mmodif').modal('show');
        });

    /* =========================
       UPDATE
    ========================= */
    function updatee() {
        const id = $('#Id').val();
        const nom = $('#nomModif').val().trim();

        if (!nom) {
            showAlert('Alert', 'Veuillez remplir le champ.', 'warning');
            return;
        }

        const label = $('#updateBtn').text();
        const idBtn = '#updateBtn';
        spinerButton(1, idBtn, 'Vérification');

        $.ajax({
            url: $('#url').attr('content') + '/api/update_assureur/' + id,
            method: 'GET',
            data: { nom },
            success(response) {
                spinerButton(0, idBtn, label);

                if (response.nom_existe) {
                    $('#Mmodif').modal('hide');
                    showAlert('Alert', 'Cet assureur existe déjà.', 'warning');
                } else if (response.success) {
                    table.ajax.reload(null, false);
                    showAlert('Succès', 'Opération effectuée.', 'success');
                } else {
                    showAlert('Erreur', 'Une erreur est survenue.', 'error');
                }
            },
            error() {
                spinerButton(0, idBtn, label);
                showAlert('Erreur', 'Erreur lors de la mise à jour.', 'error');
            }
        });
    }

    /* =========================
       DELETE
    ========================= */
    function deletee() {
        const id = $('#Id_delete').val();

        $('#Mdelete').modal('hide');
        showPreloader();

        $.ajax({
            url: $('#url').attr('content') + '/api/delete_assureur/' + id,
            method: 'GET',
            success(response) {
                hidePreloader();

                if (response.success) {
                    table.ajax.reload(null, false);
                    showAlert('Succès', response.message, 'success');
                } else {
                    showAlert('Erreur', response.message, 'error');
                }
            },
            error() {
                hidePreloader();
                showAlert('Erreur', 'Erreur lors de la suppression.', 'error');
            }
        });
    }

});
