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
            Chambre
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'CHAMBRES',
        'subtitle' => 'Configurations / Hospitalisations / Chambres'
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
                                    Nouvelle Chambre
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-archive-drawer-line me-2"></i>
                                    Liste des Chambres
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouvelle Chambre</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/hospitalisation.jpg')}}" class="img-7x rounded-circle border border-1">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="row gx-3 align-items-center justify-content-center">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Numero de la Chambre
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">CH-</span>
                                                    <input type="text" class="form-control" id="num_chambre" placeholder="Saisie Obligatoire" maxlength="6">
                                                    <a id="btn_search_num" class="btn btn-success">
                                                        <i class="ri-loop-left-line"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Numero de lit
                                                </label>
                                                <select class="form-select" id="nbre_lit">
                                                    <option value="">Selectionner</option>
                                                    @for($i = 1; $i <= 20; $i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="prix">Prix</label>
                                                <div class="input-group">
                                                <input type="tel" class="form-control" id="prix" placeholder="Saisie Obligatoire">
                                                <span class="input-group-text">Fcfa</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                                <button id="btn_eng_chambre" class="btn btn-success">
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
                                        Chambre enregistrées Aujourd'hui
                                    </h5>
                                    <a id="btn_refresh_table_day" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-outer" id="div_Table_ch_day" style="display: none;" >
                                        <div class="table-responsive">
                                            <table class="table align-middle table-hover m-0 truncate" id="Table_ch_day">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Code Chambre</th>
                                                        <th scope="col">Nombre de lit</th>
                                                        <th scope="col">Prix</th>
                                                        <th scope="col">Statut</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="message_Table_ch_day" style="display: none;">
                                        <p class="text-center" >
                                            Aucune chambre n'a été enregistrer aujourd'hui
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
                Voulez-vous vraiment supprimé cette chambre ?
                <input type="hidden" id="chambreIddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteChambreBtn" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mmodif" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Chambre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateChambreForm">
                    <input type="hidden" id="chambreId"> <!-- Hidden field for the room's ID -->
                    <div class="mb-3">
                        <label for="chambreCode" class="form-label">Numéro</label>
                        <div class="input-group">
                            <span class="input-group-text">CH-</span>
                            <input type="text" class="form-control" id="chambreCode" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numero de lit</label>
                        <select class="form-select" id="chambreLit">
                            <option value="">Selectionner</option>
                            @for($i = 1; $i <= 20; $i++)
                            <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="chambrePrix" class="form-label">Prix</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="chambrePrix">
                            <span class="input-group-text">Fcfa</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateChambreBtn">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        refresh_num();
        list_chambre();

        document.getElementById("btn_search_num").addEventListener("click", refresh_num);
        document.getElementById("btn_eng_chambre").addEventListener("click", eng_chambre);
        document.getElementById("btn_refresh_table_day").addEventListener("click", list_chambre);
        document.getElementById("updateChambreBtn").addEventListener("click", update_chambre);
        document.getElementById("deleteChambreBtn").addEventListener("click", delete_chambre);

        document.getElementById('prix').addEventListener('input', function() {
            this.value = formatPrice(this.value);
        });
        document.getElementById('prix').addEventListener('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        document.getElementById('chambrePrix').addEventListener('input', function() {
            this.value = formatPrice(this.value);
        });
        document.getElementById('chambrePrix').addEventListener('keypress', function(event) {
            const key = event.key;
            if (isNaN(key)) {
                event.preventDefault();
            }
        });

        function refresh_num(){

            var num_chambre = document.getElementById('num_chambre');

            $.ajax({
                url: $('#url').attr('content') +'/api/refresh_num_chambre',
                method: 'GET',
                success: function(response) {
                    // showAlert('success', 'Code générer avec succès');
                    num_chambre.value = response.code;
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

        function eng_chambre() {

            const num_chambre = document.getElementById("num_chambre");
            const nbre_lit = document.getElementById("nbre_lit");
            const prix = document.getElementById("prix");

            if(!num_chambre.value.trim() || !nbre_lit.value.trim() || !prix.value.trim()){
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
                url: $('#url').attr('content') +'/api/api_chambre_new',
                method: 'GET',  // Use 'POST' for data creation
                data: { num_chambre: num_chambre.value, nbre_lit: nbre_lit.value, prix: prix.value },
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.existe) {

                        showAlert('Alert', 'Cette chambre Existe déjà.','warning');

                    }else if (response.success) {

                        num_chambre.value = '';
                        nbre_lit.value = '';
                        prix.value = '';

                        refresh_num();
                        list_chambre();

                        showAlert('Succès', 'Opération éffectuée.','success');

                    } else if (response.error) {

                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');

                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');
                }
            });

        }

        function list_chambre() {

            const tableBody = document.querySelector('#Table_ch_day tbody'); // Target the specific table by id
            const messageDiv = document.getElementById('message_Table_ch_day');
            const tableDiv = document.getElementById('div_Table_ch_day'); // The message div
            const loaderDiv = document.getElementById('div_Table_loader');

            messageDiv.style.display = 'none';
            tableDiv.style.display = 'none';
            loaderDiv.style.display = 'block';

            // Fetch data from the API
            fetch($('#url').attr('content') +'/api/list_chambre') // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const chambres = data.chambre;

                    // Clear any existing rows in the table body
                    tableBody.innerHTML = '';

                    if (chambres.length > 0) {

                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'none';
                        tableDiv.style.display = 'block';

                        // Loop through each item in the chambre array
                        chambres.forEach((item, index) => {
                            // Create a new row
                            const row = document.createElement('tr');
                            // Create and append cells to the row based on your table's structure
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center ">
                                        <a class="d-flex align-items-center flex-column me-2">
                                            <img src="{{asset('assets/images/hospitalisation.jpg')}}" class="img-2x rounded-circle border border-1">
                                        </a>
                                        ${item.code}
                                    </div>
                                </td>
                                <td>${item.nbre_lit}</td>
                                <td>${formatPrice(item.prix)} Fcfa</td>
                                <td>
                                    ${item.statut === 'indisponible' ? 
                                        `<span class="badge bg-danger">${item.statut}</span>` : 
                                        `<span class="badge bg-success">${item.statut}</span>`}
                                </td>
                                <td>
                                    <div class="d-inline-flex gap-1">
                                        <a class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#Mmodif" id="edit-${item.id}">
                                            <i class="ri-edit-box-line"></i>
                                        </a>
                                    </div>
                                </td>
                            `;

                            // ${item.statut === 'disponible' ?  
                            //             `<a class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete-${item.id}">
                            //                 <i class="ri-delete-bin-line"></i>
                            //             </a>` : ``}

                            // Append the row to the table body
                            tableBody.appendChild(row);

                            // Add event listener to the edit button to open the modal with pre-filled data
                            document.getElementById(`edit-${item.id}`).addEventListener('click', () => {
                                // Set the values in the modal form
                                document.getElementById('chambreId').value = item.id;
                                document.getElementById('chambreCode').value = item.code;

                                // Set the correct value in the 'chambreLit' select dropdown
                                const chambreLitSelect = document.getElementById('chambreLit');
                                const options = chambreLitSelect.options;
                                for (let i = 1; i <= options.length; i++) {
                                    if (options[i].value == item.nbre_lit) {
                                        options[i].selected = true;
                                        break;
                                    }
                                }

                                // Set the price value
                                document.getElementById('chambrePrix').value = item.prix;
                            });

                            // Add event listener to the edit button to open the modal with pre-filled data
                            const deleteButton = document.getElementById(`delete-${item.id}`);
                            if (deleteButton) {
                                deleteButton.addEventListener('click', () => {
                                    // Set the values in the modal form
                                    document.getElementById('chambreIddelete').value = item.id;
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

        function update_chambre() {

            const id = document.getElementById('chambreId').value;
            const nbreLit = document.getElementById('chambreLit').value;
            const prix = document.getElementById('chambrePrix').value;

            if(!nbreLit.trim() || !prix.trim()){
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
                url: $('#url').attr('content') +'/api/update_chambre/'+id,
                method: 'GET',
                data: { nbre_lit: nbreLit, prix: prix},
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {

                       showAlert('Succès', 'Opération éffectuée.','success');
                        list_chambre(); 
                    } else if (response.info) {
                        showAlert('Alerte', response.message,'info');
                    } else {
                       showAlert('Alerte', `Impossible d'éffectuée la mise à jour`,'warning'); 
                    }
                    
                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la mise à jour de la chambre.','error');
                }
            });
        }

        function delete_chambre() {

            const id = document.getElementById('chambreIddelete').value;

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
                url: $('#url').attr('content') +'/api/delete_chambre/'+id,
                method: 'GET',
                success: function(response) {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Succès', 'Opération éffectuée.','success');

                    list_chambre();
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