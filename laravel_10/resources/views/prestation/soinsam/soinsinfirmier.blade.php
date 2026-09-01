@extends('app')

@section('titre', 'Nouveau Type acte')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Soins Infirmier
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'TYPE DE SOINS',
        'subtitle' => 'Configurations / Infirmerie / Soins Infirmier'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-add-line me-2"></i>
                                    Nouveau
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Soins Infirmier</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/soinsam.webp')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <!-- Row starts -->
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Type de Soins
                                                </label>
                                                <select class="form-select select2" id="typesoins_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Soins Infirmier
                                                </label>
                                                <input type="text" class="form-control" id="nom_soins" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prix</label>
                                                <div class="input-group">
                                                    <input type="tel" class="form-control" id="prix" placeholder="Saisie Obligatoire">
                                                    <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button id="btn_eng" class="btn btn-success">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Row ends -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Soins Infirmiers
                                    </h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table table-hover table-sm" >
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Soins</th>
                                                        <th scope="col">Type</th>
                                                        <th scope="col">Prix</th>
                                                        <th scope="col">Actions</th>
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
                Voulez-vous vraiment supprimé ce Soins Infirmier ?
                <input type="hidden" id="Iddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">

                    <input type="hidden" id="Id">
                    <div class="mb-3">
                        <label class="form-label">Soins Infirmier</label>
                        <input type="text" class="form-control" id="nomModif" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Acte</label>
                        <select class="form-select select2" id="typesoins_id_modif"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="prixModif" placeholder="Saisie Obligatoire">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        $('#typesoins_id_modif').select2({
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
            dropdownParent: $('#Mmodif'),
        });
    });
</script>

<script>
    $(document).ready(function() {

        select();

        $("#btn_eng").on("click", eng);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        $("#prix").on("input", function() {
            $(this).val(formatPrice($(this).val()));
        });
        $("#prix").on("keypress", function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        $("#prixModif").on("input", function() {
            $(this).val(formatPrice($(this).val()));
        });
        $("#prixModif").on("keypress", function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        function formatPrice(input) {
            // Supprimer tous les points existants
            input = input.replace(/\./g, '');
            // Formater le prix avec des points
            return input.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) 
        {
            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function select() {
            const $selectElement = $('#typesoins_id');
            $selectElement.empty();
            $selectElement.append('<option value="">Selectionner</option>');

             const $selectElement2 = $('#typesoins_id_modif');

            $.ajax({
                url: $('#url').attr('content') +'/api/select_typesoins',
                method: 'GET',
                success: function(response) {
                    const data = response.typesoins;
                    data.forEach(item => {
                        // Add each item as an option
                        $selectElement.append(`<option value="${item.code_typesoins}">${item.libelle_typesoins}</option>`);
                        $selectElement2.append(`<option value="${item.code_typesoins}">${item.libelle_typesoins}</option>`);
                    });
                },
                error: function() {
                    // Optionally handle the error
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

        function eng() {
            const $typesoins_id = $("#typesoins_id");
            const $nom_soins = $("#nom_soins");
            const $prix = $("#prix");

            if (!$typesoins_id.val().trim() || !$nom_soins.val().trim() || !$prix.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            $("body").append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/new_soinsIn',
                method: 'GET',  // Changed to 'POST' for data creation
                data: {
                    typesoins: $typesoins_id.val(),
                    nom: $nom_soins.val(),
                    prix: $prix.val()
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Erreur', 'Ce soins existe déjà.', 'error');
                    } else if (response.success) {
                        $typesoins_id.val('').trigger('change');
                        $nom_soins.val('');
                        $prix.val('');
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }

                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_soinsIn`,
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
                    data: 'libelle_soins', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/soinsam.webp') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'typesoins',
                    searchable: true,
                },
                { 
                    data: 'price',
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-code_soins="${row.code_soins}" data-libelle_soins="${row.libelle_soins}" data-price="${row.price}" data-code_typesoins="${row.code_typesoins}" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif">
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                }
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() {

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('code_soins');
                const nom = $(this).data('libelle_soins');
                const prix = $(this).data('price');
                const typesoins_id = $(this).data('code_typesoins');

                $('#Id').val(id);
                $('#nomModif').val(nom);
                $('#prixModif').val(prix);

                $('#typesoins_id_modif').val(null).trigger('change');
                $('#typesoins_id_modif').val(typesoins_id).trigger('change');
            });
        }

        function updatee() {

            const id = document.getElementById('Id').value;
            const nomModif = document.getElementById('nomModif').value;
            const typesoins_id_modif = document.getElementById('typesoins_id_modif').value;
            const prixModif = document.getElementById('prixModif').value;

            if(!nomModif.trim() || !typesoins_id_modif.trim() || !prixModif.trim()){
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/update_soinIn/'+id,
                method: 'GET',
                data: { 
                    nom: nomModif, 
                    typesoins: typesoins_id_modif, 
                    prix: prixModif,
                },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                

                    if (response.existe) {
                        showAlert('Erreur', 'Ce soins existe déjà.', 'error');
                    } else if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de la mise à jour.', 'error');
                    }
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                }
            });
        }

        function deletee() {

            const id = document.getElementById('Iddelete').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_soinsIn/'+id,
                method: 'GET',  // Use 'POST' for data creation
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // Include CSRF token if required
                //     'Content-Type': 'application/json',  // Ensure JSON request
                // },
                // data: { nbre_lit: nbreLit, prix: prix},
                // data: JSON.stringify({
                //     nbre_lit: nbreLit,
                //     prix: prix,
                // }),
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Succès', 'Soins Infirmier supprimer avec succès.','success');
                    
                    $('#Table_day').DataTable().ajax.reload();
                    select();
                    select_modif();
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la suppression de la chambre.','error');
                }
            });
        }

    });
</script>


@endsection