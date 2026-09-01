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
            Societes
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'SOCIETES',
        'subtitle' => 'Réception / Sociétés'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-home-7-line me-2"></i>
                                    Nouvelle Société
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-building-4-line me-2"></i>
                                    Liste des Sociétés
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouvelle Societe</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/batiment.avif')}}" class="img-7x rounded-circle border border-3">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3 alig-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom de la société</label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assurance</label>
                                                <select class="form-select select2" id="codeassurance">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Assureur</label>
                                                <select class="form-select select2" id="assureur_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 mb-3 ">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button id="btn_eng" class="btn btn-outline-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Sociétés
                                    </h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nom</th>
                                                        <th scope="col">Assurance</th>
                                                        <th scope="col">Assureur</th>
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

<div class="modal fade" id="Mmodif" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nom de la société</label>
                                <input type="text" class="form-control" id="nomModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Assurance</label>
                                <select class="form-select select2" id="codeassuranceModif">
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Assureur</label>
                                <select class="form-select select2" id="assureur_idModif">
                                </select>
                            </div>
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
                Voulez-vous vraiment supprimé cette Société
                <input type="hidden" id="Id_delete">
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

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        var select = ['#codeassuranceModif', '#codeassurance', '#assureur_idModif', '#assureur_id'];
        select.forEach(function(id) {
            $(id).select2({
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
    });
</script>

<script>
    $(document).ready(function() {

        select_assureur();
        select_assurance();

        $("#btn_eng").on("click", eng);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function select_assurance() 
        {
            const selectElement2 = $('#codeassurance');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#codeassuranceModif');
            selectElement3.empty();

            $.ajax({
                url: $('#url').attr('content') +'/api/assurance_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.assurance;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        }));

                        selectElement3.append($('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_assureur() 
        {
            const selectElement2 = $('#assureur_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#assureur_idModif');
            selectElement3.empty();

            $.ajax({
                url: $('#url').attr('content') +'/api/select_assureur',
                method: 'GET',
                success: function(response) {
                    const data = response.assureur;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codeassureur,
                            text: item.libelle_assureur,
                        }));

                        selectElement3.append($('<option>', {
                            value: item.codeassureur,
                            text: item.libelle_assureur,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function eng() 
        {
            const nom = $("#nom");
            const codeassurance= $("#codeassurance");
            const assureur_id = $("#assureur_id");

            if (!codeassurance.val().trim() || !nom.val().trim() || !assureur_id.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

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
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', 'Cette société existe déjà', 'warning');
                    } else if (response.success) {

                        nom.val('');
                        codeassurance.val('').trigger('change');
                        assureur_id.val('').trigger('change');

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_societe_all`,
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
                    data: 'nomsocieteassure', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/batiment.avif') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'assurance',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'assureur',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
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
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif" 
                                        data-id="${row.codesocieteassure}"
                                        data-nom="${row.nomsocieteassure}"
                                        data-codeassurance="${row.codeassurance}"
                                        data-assurance_id="${row.codeassureur}"
                                    >
                                        <i class="ri-edit-box-line"></i>
                                        Modifier
                                    </a>
                                </li>
                            </ul>
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

        // <a class="btn btn-outline-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete"
        //                         data-id="${row.codesocieteassure}"
        //                     >
        //                         <i class="ri-delete-bin-line"></i>
        //                     </a>

        function initializeRowEventListeners() {

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const nom = $(this).data('nom');
                const code = $(this).data('codeassurance');
                const assuranceid = $(this).data('assurance_id');

                $('#Id').val(id);
                $('#nomModif').val(nom);
                $('#codeassuranceModif').val(code).trigger('change');
                $('#assureur_idModif').val(assuranceid).trigger('change');
            });

            // $('#Table_day').on('click', '#delete', function() {
            //     const id = $(this).data('id');
            //     $('#Id_delete').val(id);
            // });
        }

        function updatee() 
        {
            const id = document.getElementById('Id').value;
            const nom = $("#nomModif");
            const codeassurance= $("#codeassuranceModif");
            const assureur_id = $("#assureur_idModif");

            if (!codeassurance.val().trim() || !nom.val().trim() || !assureur_id.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            // Show preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            // AJAX request to create a new user
            $.ajax({
                url: $('#url').attr('content') +'/api/update_societe/'+id,
                method: 'GET',
                data: {
                    codeassurance: codeassurance.val(),
                    nom: nom.val(),
                    assureur_id: assureur_id.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.existe) {
                        showAlert('Alert', 'Cette société existe déjà', 'warning');
                    } else if (response.success) {

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', reponse.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Une erreur est survenue', 'error');
                }
            });
        }

        function deletee() {

            const id = document.getElementById('Id_delete').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_societe/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la suppression .','error');
                }
            });
        }

    });
</script>

@endsection
