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
            Nouvelle Nature Admission
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'NATURE D\'ADMISSION',
        'subtitle' => 'Configurations / Hospitalisations / Nature d\'Admission'
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
                                    Nouvel nature
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
                                    <h5 class="card-title text-center">Formulaire Nouvelle Nature Admission</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/type_admission.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <!-- Row starts -->
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Code
                                                </label>
                                                <input type="text" class="form-control" id="code" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()" maxlength="5">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Nom Nautre
                                                </label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
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
                                        Liste des Nature Admissions
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
                                                        <th scope="col" colspan="2">Type</th>
                                                        <th scope="col">Code</th>
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

{{-- <div class="modal fade" id="Mdelete" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette nature d'admission ?
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
</div> --}}

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="Id">
                    <div class="mb-3">
                        <label class="form-label">Nature Admission</label>
                        <input type="text" class="form-control" id="nomModif" oninput="this.value = this.value.toUpperCase()">
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

<script>
    $(document).ready(function() {

        $('#btn_eng').on('click', eng);
        $('#updateBtn').on('click', updatee);
        // $('#deleteBtn').on('click', deletee);

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

        function eng() {

            const nom = $('#nom').val().trim();
            const code = $('#code').val().trim();

            if(!nom || !code) {
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
                url: $('#url').attr('content') +'/api/new_natureadmission',
                method: 'GET',  // Use 'POST' for data creation
                data: { nom: nom, code: code, },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {

                        $('#nom').val('');
                        $('#code').val('');
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération effectuée.', 'success');

                    } else if (response.existe) {
                        showAlert('Alert', 'Cette nature d\'admission existe déjà', 'warning');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue', 'error');
                    }
                    
                },
                error: function() {
                    $('#preloader_ch').remove();

                    showAlert('Erreur', 'Une erreur est survenue.', 'error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_natureadmission`,
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
                    data: null, 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/type_admission.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                    </div>`, 
                },
                { 
                    data: 'nomnaturehospit',
                    searchable: true, 
                },
                { 
                    data: 'idnathospit',
                    searchable: true, 
                },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-inline-flex gap-1" style="font-size:10px;">
                            <a class="btn btn-outline-info btn-sm edit-btn" data-id="${row.idnathospit}" data-nom="${row.nomnaturehospit}" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif">
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

        // <a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}">
        //                             <i class="ri-delete-bin-line"></i>
        //                         </a> 

        function initializeRowEventListeners() {

            $('#Table_day').on('click', '#modif', function() {
                const id = $(this).data('id');
                const nom = $(this).data('nom');

                $('#Id').val(id);
                $('#nomModif').val(nom);
            });

            // $('#Table_day').on('click', '#delete', function() {
            //     const id = $(this).data('id');

            //     $('#Iddelete').val(id);
            // });
        }

        function updatee() {

            const id = $('#Id').val();
            const nomModif = $('#nomModif').val();

            if (!nomModif.trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const modal = bootstrap.Modal.getInstance($('#Mmodif')[0]);
            modal.hide();

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +`/api/update_natureadmission/${id}`,
                method: 'GET', 
                data: { nom: nomModif },
                success: function(response) {
                    $('#preloader_ch').remove();
                    

                    if (response.success) {

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');

                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue','error');

                    }

                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour.', 'error');
                }
            });
        }

        // function deletee() {
        //     const id = $('#Iddelete').val();

        //     const modal = bootstrap.Modal.getInstance($('#Mdelete')[0]);
        //     modal.hide();

        //     const preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     $('body').append(preloader_ch);

        //     $.ajax({
        //         url: $('#url').attr('content') +`/api/delete_natureadmission/${id}`,
        //         method: 'GET',  // Use 'POST' for data deletion
        //         success: function(response) {
        //             $('#preloader_ch').remove();
        //             $('#Table_day').DataTable().ajax.reload();
        //             showAlert('Succès', 'Nature d\'admission supprimée avec succès.', 'success');
        //         },
        //         error: function() {
        //             $('#preloader_ch').remove();
        //             showAlert('Erreur', 'Erreur lors de la suppression de la nature d\'admission.', 'error');
        //         }
        //     });
        // }

    });
</script>


@endsection