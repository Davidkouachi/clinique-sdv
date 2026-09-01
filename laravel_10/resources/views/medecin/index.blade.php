@extends('app')

@section('titre', 'Nouvel Utilisateur')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Médecin
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'MEDECIN',
        'subtitle' => 'Configurations / Médecin & Spécialité / Médecin'
    ])
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-user-add-line me-2"></i>
                                    Nouveau
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-contacts-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Formulaire Nouveau Médecin</h5>
                                </div>
                                <div class="card-header">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row gx-3">
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Prénoms</label>
                                                <input type="text" class="form-control" id="prenom" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Contact</label>
                                                <input type="tel" class="form-control" id="tel" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Spécialité</label>
                                                <select class="form-select select2" id="specialite_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Numéro d'ordre</label>
                                                <input type="tel" class="form-control" id="numodremed" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date de service</label>
                                                <input type="date" class="form-control" id="dateservice" placeholder="Saisie Obligatoire">
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
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Médecins
                                    </h5>
                                    <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table id="Table_day" class="table align-middle table-hover m-0 truncate">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nom et Prénoms</th>
                                                        <th scope="col">Matricule</th>
                                                        <th scope="col">Specialité</th>
                                                        <th scope="col">contact</th>
                                                        <th scope="col">Date de service</th>
                                                        <th scope="col">Durée de service</th>
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

<div class="modal fade" id="Detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détail Médecin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_detail"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="Mdelete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé ce Médecin
                <input type="hidden" id="Matricule_delete">
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

<div class="modal fade" id="Mmodif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="Matricule_up">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nomModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Prénoms</label>
                                <input type="text" class="form-control" id="prenomModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="tel" class="form-control" id="telModif" placeholder="Saisie Obligatoire" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Spécialité</label>
                                <select class="form-select select2" id="specialite_idModif">
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Numéro d'ordre</label>
                                <input type="tel" class="form-control" id="numodremedModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date de service</label>
                                <input type="date" class="form-control" id="dateserviceModif" placeholder="Saisie Obligatoire">
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

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        $('#specialite_idModif').select2({
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

        var inputs = ['#tel', '#telModif', '#numodremed'];
        inputs.forEach(function(selector) {
            $(selector).on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        function select() 
        {
            const $selectElement = $('#specialite_id');
            $selectElement.empty();
            $selectElement.append('<option value="">Sélectionner une spécialité</option>');

            const $selectElement2 = $('#specialite_idModif');

            $.ajax({
                url: $('#url').attr('content') + '/api/select_specialite',
                method: 'GET',
                success: function(response) {
                    const data = response.specialite;

                    data.forEach(item => {
                        $selectElement.append(`
                            <option value="${item.codespecialitemed}">${item.nomspecialite}</option>
                        `);
                        $selectElement2.append(`
                            <option value="${item.codespecialitemed}">${item.nomspecialite}</option>
                        `);
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function showAlert(title, message, type) 
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatDate(dateString) 
        {

            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = date.getFullYear();

            return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
        }

        function formatDateHeure(dateString)
        {

            const date = new Date(dateString);
                
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
        }

        function calculateAge(dateString) 
        {
            const birthDate = new Date(dateString);
            const today = new Date();

            let age = today.getFullYear() - birthDate.getFullYear();

            // Vérifie si l'anniversaire n'est pas encore passé cette année
            const monthDiff = today.getMonth() - birthDate.getMonth();
            const dayDiff = today.getDate() - birthDate.getDate();
            if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
                age--;
            }

            return age;
        }

        function calculateDuration(date_debut) 
        {
            const now = new Date();
            const startDate = new Date(date_debut);

            // Si la date de début est dans le passé par rapport à aujourd'hui, retourne 0
            if (startDate > now) {
                return '0';
            }

            const diffTime = Math.abs(now - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convertir en jours

            if (diffDays < 7) {
                return `${diffDays} jour${diffDays > 1 ? 's' : ''}`;
            } else if (diffDays < 30) {
                const weeks = Math.floor(diffDays / 7);
                return `${weeks} semaine${weeks > 1 ? 's' : ''}`;
            } else if (diffDays < 365) {
                const months = Math.floor(diffDays / 30);
                return `${months} mois`;
            } else {
                const years = Math.floor(diffDays / 365);
                return `${years} an${years > 1 ? 's' : ''}`;
            }
        }

        function eng() 
        {
            const nom = $('#nom');
            const prenom = $('#prenom');
            const email = $('#email');
            const tel = $('#tel');
            const num = $('#numodremed');
            const dateservice = $('#dateservice');
            const specialite_id = $('#specialite_id');

            if (!nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !specialite_id.val().trim() || !dateservice.val().trim() || !num.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.val().trim())) {
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            // Add preloader
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') + '/api/new_medecin',
                method: 'GET',
                data: {
                    nom: nom.val(),
                    prenom: prenom.val(),
                    email: email.val(),
                    tel: tel.val(),
                    num: num.val(),
                    dateservice: dateservice.val(),
                    specialite_id: specialite_id.val(),
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.tel_existe) {
                        showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît', 'warning');
                    } else if (response.email_existe) {
                        showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît', 'warning');
                    } else if (response.nom_existe) {
                        showAlert('Alert', 'Cet Médecin existe déjà.', 'warning');
                    } else if (response.success) {

                        nom: nom.val('');
                        prenom: prenom.val('');
                        email: email.val('');
                        tel: tel.val('');
                        num: num.val('');
                        dateservice: dateservice.val('');
                        specialite_id.val('').trigger('change');
                        
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.', 'error');
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') + `/api/list_medecin`,
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
                    data: 'nomprenomsmed', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/docteur.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                { 
                    data: 'codemedecin',
                    searchable: true,
                },
                { 
                    data: 'specialite',
                    searchable: true, 
                },
                {
                    data: 'contact',
                    render: (data, type, row) => {
                        return data ? `+225 ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                { 
                    data: 'dateservice', 
                    render: (data) => `${formatDate(data)}`,
                    searchable: true, 
                },
                { 
                    data: 'dateservice', 
                    render: (data) => `${calculateDuration(data)}`,
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
                                    <a href="#" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#Detail" id="detail" 
                                        data-dateservice="${row.dateservice}"
                                        data-nomprenom="${row.nomprenomsmed}"
                                        data-tel="${row.contact}"
                                        data-matricule="${row.codemedecin}"
                                        data-nom="${row.nommedecin}"
                                        data-prenom="${row.prenomsmedecin}"
                                        data-num="${row.numordremed}"
                                        data-specialite="${row.specialite}"
                                        data-email="${row.email}"
                                    >
                                        <i class="ri-eye-line"></i>
                                        Détail
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif"
                                        data-dateservice="${row.dateservice}"
                                        data-tel="${row.contact}"
                                        data-matricule="${row.codemedecin}"
                                        data-nom="${row.nommedecin}"
                                        data-prenom="${row.prenomsmedecin}"
                                        data-num="${row.numordremed}"
                                        data-specialite_id="${row.specialite_id}"
                                        data-email="${row.email}"
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

// <li>
//                                     <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete"
//                                         data-matricule="${row.codemedecin}"
//                                     >
//                                         <i class="ri-delete-bin-line"></i>
//                                         Supprimer
//                                     </a>
//                                 </li>

        function initializeRowEventListeners() {

            $('#Table_day').on('click', '#detail', function() {
                const row = {
                    dateservice: $(this).data('dateservice'),
                    nomprenom: $(this).data('nomprenom'),
                    tel: $(this).data('tel'),
                    matricule: $(this).data('matricule'),

                    nom: $(this).data('nom'),
                    prenom: $(this).data('prenom'),

                    num: $(this).data('num'),
                    specialite: $(this).data('specialite'),
                    email: $(this).data('email'),
                };

                const modal = document.getElementById('modal_detail');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.innerHTML = `
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <div class="text-center">
                                        <a class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle mb-3 border border-3">
                                            <h5>${row.nomprenom}</h5>
                                            ${row.tel !== null ? 
                                                `<h6 class="text-truncate"> +225 ${row.tel} </h6>` : 
                                                ``
                                            }
                                            <p>Date de service : ${formatDate(row.dateservice)} </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active text-center" aria-current="true">
                                            Informations personnelles
                                        </li>
                                        <li class="list-group-item">
                                            Matricule : ${row.matricule}
                                        </li>
                                        <li class="list-group-item">
                                            Nom : ${row.nom}
                                        </li>
                                        <li class="list-group-item">
                                            Prénoms : ${row.prenom}
                                        </li>
                                        <li class="list-group-item">
                                            Email : ${row.email !== null ? `${row.email}` : `Néant`}
                                        </li>
                                        <li class="list-group-item">
                                            Téléphone : ${row.tel !== null ? `+225 ${row.tel}` : `Néant`}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active text-center" aria-current="true">
                                            Informations Service
                                        </li>
                                        <li class="list-group-item">
                                            Spécialité : ${row.specialite}
                                        </li>
                                        <li class="list-group-item">
                                            Numéro d'ordre : ${row.num}
                                        </li>
                                        <li class="list-group-item">
                                            Date de service : ${formatDate(row.dateservice)}
                                        </li>
                                        <li class="list-group-item">
                                            Durée de service : ${calculateDuration(row.dateservice)} 
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                modal.appendChild(div);
            });

            $('#Table_day').on('click', '#modif', function() {
                const dateservice = $(this).data('dateservice');
                const tel = $(this).data('tel');
                const matricule = $(this).data('matricule');
                const nom = $(this).data('nom');
                const prenom = $(this).data('prenom');
                const num = $(this).data('num');
                const specialite_id = $(this).data('specialite_id');
                const email = $(this).data('email');
                
                $('#Matricule_up').val(matricule);
                $('#nomModif').val(nom);
                $('#prenomModif').val(prenom);
                $('#emailModif').val(email);
                $('#telModif').val(tel);
                $('#numodremedModif').val(num);
                $('#dateserviceModif').val(dateservice);

                $('#specialite_idModif').val(specialite_id).trigger('change');
            });

            $('#Table_day').on('click', '#delete', function() {
                const matricule = $(this).data('matricule');
                $('#Matricule_delete').val(matricule);
            });
        }

        function updatee() 
        {

            const matricule = $('#Matricule_up').val().trim();
            const nom = $('#nomModif');
            const prenom = $('#prenomModif');
            const email = $('#emailModif');
            const tel = $('#telModif');
            const num = $('#numodremedModif');
            const dateservice = $('#dateserviceModif');
            const specialite_id = $('#specialite_idModif');

            if (!nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !specialite_id.val().trim() || !dateservice.val().trim() || !num.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.val().trim())) {
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            const preloader = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader);

            $.ajax({
                url: $('#url').attr('content') + '/api/update_medecin/' + matricule,
                method: 'GET',
                data: {
                    nom: nom.val(),
                    prenom: prenom.val(),
                    email: email.val(),
                    tel: tel.val(),
                    num: num.val(),
                    dateservice: dateservice.val(),
                    specialite_id: specialite_id.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.tel_existe) {
                        showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît', 'warning');
                    } else if (response.email_existe) {
                        showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît', 'warning');
                    } else if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.', 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour.', 'error');
                }
            });
        }

        function deletee() 
        {
            const matricule = $("#Matricule_delete").val().trim();

            const modal = bootstrap.Modal.getInstance(document.getElementById('Mdelete'));
            modal.hide();

            const preloader = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader);

            $.ajax({
                url: $('#url').attr('content') + '/api/delete_medecin/' + matricule,
                method: 'GET',
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $("#preloader_ch").remove();
                    showAlert('Erreur', 'Erreur lors de l\'opération.', 'error');
                }
            });
        }

    });
</script>


@endsection