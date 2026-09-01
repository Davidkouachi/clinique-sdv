@extends('app')

@section('titre', 'Nouvel Acte')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Type d'admission
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'TYPE D\'ADMISSION',
        'subtitle' => 'Configurations / Hospitalisations / Type d\'Admission'
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
                                <div class="card-header text-center">
                                    <h5 class="card-title">Formulaire Nouveau type d'admission</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/type_admission.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
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
                                                    Nom du type
                                                </label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                                <button id="btn_eng" class="btn btn-success">
                                                    Enregistrer
                                                </button>
                                            </div>
                                        </div>  
                                    </div>
                                    <!-- Row ends -->
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des types d'admission
                                    </h5>
                                    <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-outer" id="div_Table" style="display: none;" >
                                        <div class="table-responsive">
                                            <table class="table align-middle table-hover m-0 truncate" id="Table_day">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nom</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="message_Table" style="display: none;">
                                        <p class="text-center" >
                                            Aucun type d'admission n'a été trouvé
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
                Voulez-vous vraiment supprimé cet Type d'acte ?
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
 --}}
<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="Id">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'acte</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="nomModif" oninput="this.value = this.value.toUpperCase()">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        list();

        document.getElementById("btn_eng").addEventListener("click", eng);
        document.getElementById("btn_refresh_table").addEventListener("click", list);
        document.getElementById("updateBtn").addEventListener("click", updatee);
        // document.getElementById("deleteBtn").addEventListener("click", deletee);

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function eng() 
        {

            const nom = document.getElementById("nom");
            const code = document.getElementById("code");

            if(!nom.value.trim() || !code.value.trim()){
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.','warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/new_typeadmission',
                method: 'GET',
                data: { 
                    nom: nom.value,
                    code: code.value,
                },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.existe) {
                        showAlert('Alert', 'Cet Type existe déjà.','warning');
                    } else if (response.success) {
                        nom.value = '';
                        code.value = '';
                        list();
                        showAlert('Succès', 'Operation éffectué.','success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                    }

                },
                error: function(xhr, status, error) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    // You can log the entire error object to inspect it if needed
                    console.log(xhr, status, error);

                    // Display a more specific error message
                    if (xhr.responseText) {
                        showAlert('Erreur', `Erreur: ${xhr.responseText}`,'error');
                    } else {
                        showAlert('Erreur', `Erreur: ${status} - ${error}`,'error');
                    }

                }
            });
        }

        function list() {

            const tableBody = document.querySelector('#Table_day tbody'); // Target the specific table by id
            const messageDiv = document.getElementById('message_Table');
            const tableDiv = document.getElementById('div_Table'); // The message div
            const loaderDiv = document.getElementById('div_Table_loader');

            messageDiv.style.display = 'none';
            tableDiv.style.display = 'none';
            loaderDiv.style.display = 'block';

            // Fetch data from the API
            fetch($('#url').attr('content') +'/api/list_typeadmission') // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const typeadmissions = data.typeadmission;

                    // Clear any existing rows in the table body
                    tableBody.innerHTML = '';

                    if (typeadmissions.length > 0) {

                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'none';
                        tableDiv.style.display = 'block';

                        // Loop through each item in the chambre array
                        typeadmissions.forEach((item, index) => {
                            // Create a new row
                            const row = document.createElement('tr');
                            // Create and append cells to the row based on your table's structure
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center ">
                                        <a class="d-flex align-items-center flex-column me-2">
                                            <img src="{{asset('assets/images/type_admission.jpg')}}" class="img-2x rounded-circle border border-1">
                                        </a>
                                        ${item.nomtypehospit}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-outline-info btn-sm " data-bs-toggle="modal" data-bs-target="#Mmodif" id="edit-${item.idtypehospit}">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                    </div>
                                </td>
                            `;


// <a class="btn btn-outline-danger btn-sm " data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete-${item.idtypehospit}">
//                                             <i class="ri-delete-bin-line"></i>
//                                         </a>
                                        
                            tableBody.appendChild(row);

                            document.getElementById(`edit-${item.idtypehospit}`).addEventListener('click', () => {
                                // Set the values in the modal form
                                document.getElementById('Id').value = item.idtypehospit;
                                document.getElementById('nomModif').value = item.nomtypehospit;
                            });

                            const deleteButton = document.getElementById(`delete-${item.idtypehospit}`);
                            if (deleteButton) {
                                deleteButton.addEventListener('click', () => {
                                    // Set the values in the modal form
                                    document.getElementById('Iddelete').value = item.idtypehospit;
                                });
                            }

                        });
                    } else {
                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'block';
                        tableDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    // Hide the table and show the error message in case of failure
                    loaderDiv.style.display = 'none';
                    messageDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                });
        }

        function updatee() {

            const id = document.getElementById('Id').value;
            const nom = document.getElementById('nomModif').value;

            if(!nom.trim()){
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
                url: $('#url').attr('content') +'/api/update_typeadmission/'+id,
                method: 'GET',  // Use 'POST' for data creation
                data: { nom: nom},
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                        showAlert('Succès', 'Opération éffectuée.','success');
                        list();

                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue.','error');

                    }  

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la mise à jour du type d\'admission.','error');
                }
            });
        }

        // function deletee() {

        //     const id = document.getElementById('Iddelete').value;

        //     var modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
        //     modal.hide();

        //     var preloader_ch = `
        //         <div id="preloader_ch">
        //             <div class="spinner_preloader_ch"></div>
        //         </div>
        //     `;
        //     // Add the preloader to the body
        //     document.body.insertAdjacentHTML('beforeend', preloader_ch);

        //     $.ajax({
        //         url: $('#url').attr('content') +'/api/delete_typeadmission/'+id,
        //         method: 'GET',  // Use 'POST' for data creation
        //         success: function(response) {
        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             showAlert('Succès', 'Type admission supprimer avec succès.','success');
        //             // Reload the list or update the table row without a full refresh
        //             list(); // Call your function to reload the table
        //             // Close the modal
        //         },
        //         error: function() {
        //             var preloader = document.getElementById('preloader_ch');
        //             if (preloader) {
        //                 preloader.remove();
        //             }

        //             showAlert('Erreur', 'Erreur lors de la suppression du type d\'admission.','error');
        //         }
        //     });
        // }
    });
</script>

@endsection


