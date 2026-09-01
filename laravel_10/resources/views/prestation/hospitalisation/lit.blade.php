@extends('app')

@section('titre', 'Nouvelle Assurance')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Nouveau Lit
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'LITS',
        'subtitle' => 'Configurations / Hospitalisations / Lits'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-add-line me-2"></i>
                                    Nouveau Lit
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Liste des Lits
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Lit</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/lit.avif')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <!-- Row starts -->
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Numéro du lit
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Lit-</span>
                                                    <input type="text" class="form-control" id="num_lit" placeholder="Saisie Obligatoire" maxlength="6">
                                                    <a id="btn_search_num" class="btn btn-success">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Type
                                                </label>
                                                <select class="form-select" id="type">
                                                    <option value="">Selectionner</option>
                                                    <option value="Enfant">Enfant</option>
                                                    <option value="Adulte">Adulte</option>
                                                    <option value="Berceau">Berceau</option>
                                                    <option value="Autre">Autre</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Chambre
                                                </label>
                                                <select class="form-select select2" id="chambre_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button id="btn_eng_lit" class="btn btn-success">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3" id="div_alert">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row ends -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des lits
                                    </h5>
                                    <a id="btn_refresh_table_day" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-outer" id="div_Table_lit_day" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-hover m-0 truncate" id="Table_lit_day">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Code Lit</th>
                                                        <th scope="col">Statut</th>
                                                        <th scope="col">N° chambre</th>
                                                        <th scope="col">Catégorie</th>
                                                        <th scope="col">Prix</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="message_Table_lit_day" style="display: none;">
                                        <p class="text-center" >
                                            Aucun Lit n'a été enregistrer aujourd'hui
                                        </p>
                                    </div>
                                    <div id="div_Table_loader" style="display: none;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                            <strong>Chargement des données...</strong>
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

<div class="modal fade" id="Mdelete" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette chambre
                <input type="hidden" id="litIddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteLitBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Lit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateLitForm">
                    <input type="hidden" id="litId"> <!-- Hidden field for the room's ID -->
                    <div class="mb-3">
                        <label for="chambreCode" class="form-label">Numéro</label>
                        <div class="input-group">
                            <span class="input-group-text">Lit-</span>
                            <input type="text" class="form-control" id="litCode" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-select" id="typeLit">
                            <option value="">Selectionner</option>
                            <option value="Enfant">Enfant</option>
                            <option value="Adulte">Adulte</option>
                            <option value="Berceau">Berceau</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateLitBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

@include('select2')

<script>
    $(document).ready(function() {

        refresh_num();
        select_lit();
        list_lit();

        $("#btn_search_num").on("click", refresh_num);
        $("#btn_eng_lit").on("click", eng_lit);

        $("#btn_refresh_table_day").on("click", list_lit);
        $("#updateLitBtn").on("click", update_lit);
        $("#deleteLitBtn").on("click", delete_lit);

        function refresh_num() {
            const num_lit = $('#num_lit');

            $.ajax({
                url: $('#url').attr('content') +'/api/refresh_num_lit',
                method: 'GET',
                success: function(response) {
                    num_lit.val(response.code);
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_lit() {
            const selectElement = $('#chambre_id');
            
            // Clear existing options
            selectElement.empty();
            
            // Add default option
            selectElement.append($('<option>', {
                value: '',
                text: 'Sélectionner une Chambre'
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/list_chambre_select',
                method: 'GET',
                success: function(response) {
                    const data = response.list;
                    $.each(data, function(index, list) {
                        selectElement.append($('<option>', {
                            value: list.id, // Ensure 'id' is the correct key
                            text: 'CH-' + list.code // Ensure 'nom' is the correct key
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatPrice(input) {
            // Supprimer tous les points existants
            input = input.replace(/\./g, '');
            // Formater le prix avec des points
            return input.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function eng_lit() {

            const num_lit = $('#num_lit').val().trim();
            const type = $('#type').val().trim();
            const chambre_id = $('#chambre_id').val().trim();

            if (!num_lit || !type || !chambre_id) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/api_lit_new',
                method: 'GET', // Use 'POST' for data creation
                data: { num_lit, type, chambre_id },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.existe) {
                        select_lit();
                        showAlert('Alert', 'Cet Lit Existe déjà.', 'warning');
                    } else if (response.nbre) {
                        select_lit();
                        showAlert('Alert', 'Cette Chambre a atteint sa limite, Veuillez selectionner une autre chambre.', 'warning');
                    } else if (response.success) {
                        $('#num_lit, #type').val('');
                        $('#chambre_id').val('').trigger('change');
                        refresh_num();
                        select_lit();
                        list_lit();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        select_lit();
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    select_lit();
                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        function list_lit() {

            const tableBody = $('#Table_lit_day tbody');
            const messageDiv = $('#message_Table_lit_day');
            const tableDiv = $('#div_Table_lit_day');
            const loaderDiv = $('#div_Table_loader');

            messageDiv.hide();
            tableDiv.hide();
            loaderDiv.show();

            $.ajax({
                url: $('#url').attr('content') +'/api/list_lit',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const lits = data.data;
                    tableBody.empty();

                    if (lits.length > 0) {
                        loaderDiv.hide();
                        messageDiv.hide();
                        tableDiv.show();

                        $.each(lits, function(index, item) {
                            const row = $(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="d-flex align-items-center flex-column me-2">
                                                <img src="{{asset('assets/images/lit.avif')}}" class="img-2x rounded-circle border border-1">
                                            </a>
                                            ${item.code}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge ${item.statut === 'indisponible' ? 'bg-danger' : 'bg-success'}">${item.statut}</span>
                                    </td>
                                    <td>CH-${item.code_ch}</td>
                                    <td>${item.type}</td>
                                    <td>${formatPrice(item.prix)} Fcfa</td>
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#Mmodif" id="edit-${item.id}">
                                                <i class="ri-edit-box-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            `);

                            // ${item.statut === 'disponible' ? 
                            //                     `<a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete-${item.id}">
                            //                         <i class="ri-delete-bin-line"></i>
                            //                     </a>` : ''}

                            tableBody.append(row);

                            $(`#edit-${item.id}`).on('click', function() {
                                $('#litId').val(item.id);
                                $('#litCode').val(item.code);
                                $('#typeLit').val(item.type);
                            });

                            $(`#delete-${item.id}`).on('click', function() {
                                $('#litIddelete').val(item.id);
                            });
                        });
                    } else {
                        loaderDiv.hide();
                        messageDiv.show();
                        tableDiv.hide();
                    }
                },
                error: function() {
                    loaderDiv.hide();
                    messageDiv.show();
                    tableDiv.hide();
                }
            });
        }

        function update_lit() {
            const id = $('#litId').val();
            const typeLit = $('#typeLit').val().trim();

            if (!typeLit) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/update_lit/' + id,
                method: 'GET', // Use 'POST' if updating data
                data: { typeLit },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        list_lit();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour de la chambre.', 'error');
                }
            });
        }

        function delete_lit() {
            const id = $('#litIddelete').val();

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_lit/' + id,
                method: 'GET', // Use 'DELETE' for deletion
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        list_lit();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la suppression de la chambre.', 'error');
                }
            });
        }

    });
</script>


@endsection