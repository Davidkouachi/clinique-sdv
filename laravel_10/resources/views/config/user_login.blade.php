@extends('app')

@section('titre', 'Nouvel Utilisateur')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Utilisateur
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'UTILISATEURS',
        'subtitle' => 'Configurations / Utilisateurs'
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
                                    Nouvel Utilisateur
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
                                    <h5 class="card-title">Formulaire Nouvel Employé</h5>
                                </div>
                                <div class="card-body" >
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
                                                <label class="form-label">Profil</label>
                                                <select class="form-select select2" id="profil_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Login</label>
                                                <input type="text" class="form-control" id="login" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mot de passe</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password" placeholder="Saisie Obligatoire" value="00000">
                                                    <a class="btn btn-white" id="btn_hidden_mpd">
                                                        <i id="toggleIcon" class="ri-eye-line text-primary"></i>
                                                    </a>
                                                </div>
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
                                        Liste des Employés
                                    </h5>
                                    <a id="btn_refresh_table" class="btn btn-outline-info ms-auto">
                                        <i class="ri-loop-left-line"></i>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="">
                                            <table id="Table_day" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">N°</th>
                                                        <th scope="col">Nom</th>
                                                        <th scope="col">Prénoms</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Téléphone</th>
                                                        <th scope="col">Login</th>
                                                        <th scope="col">Profil</th>
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
                    Détail Utilisateur
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
                Voulez-vous vraiment supprimé cet Utilisateur ?
                <input type="hidden" id="id_del">
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

<div class="modal fade" id="Mmodif" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden"id="id_up">
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
                                <label class="form-label">Profil</label>
                                <select class="form-select select2" id="profil_idModif">
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Login</label>
                                <input type="text" class="form-control" id="loginModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
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

<div class="modal fade" id="Mmodifpassword" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateFormc">
                    <input type="hidden"id="id_up_password">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nouveau Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="passwordModif" placeholder="Saisie Obligatoire">
                                    <a class="btn btn-white" id="btn_hidden_mpdModif">
                                        <i id="toggleIconModif" class="ri-eye-line text-primary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Confirmer le nouveau Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="passwordModifc" placeholder="Saisie Obligatoire">
                                    <a class="btn btn-white" id="btn_hidden_mpdModifc">
                                        <i id="toggleIconModifc" class="ri-eye-line text-primary"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="updateBtn_password">Mettre à jour</button>
            </div>
        </div>
    </div>
</div>

@include('select2')

<script>
    $('#Mmodif').on('shown.bs.modal', function () {
        var select = ['#profil_idModif'];
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

        select_profil();

        $("#btn_eng").on("click", eng);
        $("#updateBtn").on("click", updatee);
        $("#updateBtn_password").on("click", updatee_password);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        var inputs = ['tel', 'telModif'];
        inputs.forEach(function(id) {
            $("#" + id).on("input", function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        $("#btn_hidden_mpd").on("click", function(event) {
            event.preventDefault();
            const passwordField = $('#password');
            const toggleIcon = $('#toggleIcon');

            if (passwordField.attr("type") === 'password') {
                passwordField.attr("type", "text");
                toggleIcon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                passwordField.attr("type", "password");
                toggleIcon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });

        $("#btn_hidden_mpdModif").on("click", function(event) {
            event.preventDefault();
            const passwordField = $('#passwordModif');
            const toggleIcon = $('#toggleIconModif');

            if (passwordField.attr("type") === 'password') {
                passwordField.attr("type", "text");
                toggleIcon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                passwordField.attr("type", "password");
                toggleIcon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });

        $("#btn_hidden_mpdModifc").on("click", function(event) {
            event.preventDefault();
            const passwordField = $('#passwordModifc');
            const toggleIcon = $('#toggleIconModifc');

            if (passwordField.attr("type") === 'password') {
                passwordField.attr("type", "text");
                toggleIcon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
            } else {
                passwordField.attr("type", "password");
                toggleIcon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
            }
        });

        function select_profil() 
        {
            const selectElement = $('#profil_idModif');
            selectElement.empty();

            const selectElement2 = $('#profil_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/select_profil',
                method: 'GET',
                success: function(response) {
                    const data = response.profil;

                    data.forEach(function(item) {
                        selectElement.append($('<option>', {
                            value: item.idprofile,
                            text: item.libprofile,
                        }));

                        selectElement2.append($('<option>', {
                            value: item.idprofile,
                            text: item.libprofile,
                        }));
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

        function formatDate(dateString) {
            // Vérifie si la chaîne est une date invalide
            if (dateString === "0000-00-00" || !dateString) {
                return "00-00-0000";
            }

            const date = new Date(dateString);

            // Vérifie si l'objet Date est invalide
            if (isNaN(date.getTime())) {
                return "Date invalide";
            }

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

        function eng() 
        {
            const nom = $("#nom");
            const prenom = $("#prenom");
            const email = $("#email");
            const tel = $("#tel");
            const profil_id = $("#profil_id");
            const login = $("#login");
            const password = $("#password");

            if (!nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !profil_id.val().trim() || !login.val().trim() || !password.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.val().trim() && !emailRegex.test(email.val().trim())) { 
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            if (password.val().length < 5) {
                showAlert('Alerte', 'Le mot de passe doit contenir au moins 5 caractères.', 'warning');
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
                url: $('#url').attr('content') +'/api/new_user_login',
                method: 'GET',
                data: {
                    nom: nom.val(),
                    prenom: prenom.val(),
                    email: email.val(),
                    tel: tel.val(),
                    profil_id: profil_id.val(),
                    login: login.val(),
                    password: password.val(),
                },
                success: function(response) {
                    $("#preloader_ch").remove();

                    if (response.tel_existe) {
                        showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît', 'warning');
                    } else if (response.email_existe) {
                        showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît', 'warning');
                    } else if (response.success) {

                        nom.val('');
                        prenom.val('');
                        email.val('');
                        tel.val('');
                        profil_id.val('').trigger('change');
                        login.val('');
                        password.val('00000');

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

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_user_login`,
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
                    data: 'user_first_name', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/user8.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
                    searchable: true, 
                },
                {
                    data: 'user_last_name',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'email',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'tel',
                    render: (data, type, row) => {
                        return data ? `+225 ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                {
                    data: 'login',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                { 
                    data: 'profil',
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
                                        data-nomprenom="${row.user_first_name} ${row.user_last_name}"
                                        data-tel="${row.tel}"

                                        data-email="${row.email}"
                                        data-profil="${row.profil}"

                                        data-login="${row.login}"
                                    >
                                        <i class="ri-eye-line"></i>
                                        Détail
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif" 
                                        data-nom="${row.user_first_name}"
                                        data-prenom="${row.user_last_name}"
                                        data-email="${row.email}"
                                        data-tel="${row.tel}"
                                        data-profil_id="${row.profil_id}"
                                        data-login="${row.login}"
                                        data-id="${row.id}"
                                    >
                                        <i class="ri-edit-box-line"></i>
                                        Modifier
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodifpassword" id="modifpassword" 
                                        data-id="${row.login}"
                                    >
                                        <i class="ri-edit-box-line"></i>
                                        Modifier le mot de passe
                                    </a>
                                </li>
                                ${row.login == @json(Auth::user()->login) || row.code_personnel != null ? `` : 
                                `
                                <li>
                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete"
                                        data-id="${row.id}"
                                    >
                                        <i class="ri-delete-bin-line"></i>
                                        Supprimer
                                    </a>
                                </li>
                                `}
                            </ul>
                        </div>
                    `,
                    searchable: false,
                    orderable: false,
                },
            ],
            ...dataTableConfig, 
            initComplete: function(settings, json) {
                initializeRowEventListeners();
            },
        });

        function initializeRowEventListeners() 
        {
            $('#Table_day').on('click', '#detail', function() {
                const row = {
                    nomprenom: $(this).data('nomprenom'),
                    tel: $(this).data('tel'),

                    email: $(this).data('email'),
                    tel: $(this).data('tel'),
                    profil: $(this).data('profil'),

                    login: $(this).data('login'),
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
                                            <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle mb-3 border border-3">
                                            <h5>${row.nomprenom}</h5>
                                            <h6 class="text-truncate">+225 ${row.tel}</h6>
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
                                            Nom et Prénoms : ${row.nomprenom}
                                        </li>
                                        <li class="list-group-item">
                                            Email : +225 ${row.email}
                                        </li>
                                        <li class="list-group-item">
                                            Téléphone : ${row.tel}
                                        </li>
                                        <li class="list-group-item">
                                            Profil : ${row.profil ?? 'Aucun'}
                                        </li>
                                        <li class="list-group-item">
                                            Login : ${row.login ?? 'Aucun'}
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

                const nom = $(this).data('nom');
                const prenom = $(this).data('prenom');
                const email = $(this).data('email');
                const tel = $(this).data('tel');
                const profil_id = $(this).data('profil_id');
                const login = $(this).data('login');
                const id = $(this).data('id');

                $('#id_up').val(id);
                $('#nomModif').val(nom);
                $('#prenomModif').val(prenom);
                $('#emailModif').val(email);
                $('#telModif').val(tel);
                $('#loginModif').val(login);

                $('#profil_idModif').val(profil_id).trigger('change');
            });

            $('#Table_day').on('click', '#modifpassword', function() {

                const id = $(this).data('id');

                $('#id_up_password').val(id);
                $('#passwordModif').val(null);
                $('#passwordModifc').val(null);
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                // Handle the 'Delete' button click
                $('#id_del').val(id);
            });
        }

        function updatee() 
        {
            const id = $("#id_up").val().trim();
            const nom = $("#nomModif");
            const prenom = $("#prenomModif");
            const email = $("#emailModif");
            const tel = $("#telModif");
            const profil_id = $("#profil_idModif");
            const login = $("#loginModif");

            if (!nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !profil_id.val().trim() || !login.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.val().trim() && !emailRegex.test(email.val().trim())) { 
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodif'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/refresh-csrf',
                method: 'GET',
                success: function(response_crsf) {
                    // Met à jour la balise <meta> avec le nouveau token
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', response_crsf.csrf_token);
                    
                    // console.log("Nouveau token CSRF:", response_crsf.csrf_token);

                    $.ajax({
                        url: $('#url').attr('content') +'/api/update_user_login/' + id,
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': response_crsf.csrf_token,
                        },
                        data: {
                            nom: nom.val(),
                            prenom: prenom.val(),
                            email: email.val(),
                            tel: tel.val(),
                            profil_id: profil_id.val(),
                            login: login.val(),
                        },
                        success: function(response) {

                            document.getElementById('preloader_ch').remove();

                            if (response.tel_existe) {

                                showAlert('Alert', 'Veuillez saisir autre numéro de téléphone s\'il vous plaît','warning');

                            }else if (response.email_existe) {

                                showAlert('Alert', 'Veuillez saisir autre email s\'il vous plaît','warning');

                            }  else if (response.success) {

                                $('#Table_day').DataTable().ajax.reload();

                                showAlert('Succès', 'Opération éffectuée.', 'success');

                            } else if (response.error) {

                                showAlert('Erreur', 'Une erreur est survenue lors de l\'enregistrement.','error');

                            }
                        },
                        error: function() {
                            document.getElementById('preloader_ch').remove();
                            showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                        }
                    });

                },
                error: function() {
                    console.log("Erreur lors du rafraîchissement du token CSRF");
                    document.getElementById('preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                }
            });
        }

        function updatee_password() 
        {
            const id = $("#id_up_password").val().trim();
            const password = $("#passwordModif");
            const passwordc = $("#passwordModifc");

            if (!password.val().trim() || !passwordc.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            if (password.val().length < 5 || passwordc.val().length < 5) {
                showAlert('Alerte', 'Le mot de passe doit contenir au moins 5 caractères.', 'warning');
                return false;
            }

            if (password.val().trim() != passwordc.val().trim()) {
                showAlert('Alerte', 'Les deux mots de passe ne sont pas identiques.', 'warning');
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Mmodifpassword'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: '/refresh-csrf',
                method: 'GET',
                success: function(response_crsf) {
                    // Met à jour la balise <meta> avec le nouveau token
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', response_crsf.csrf_token);
                    
                    // console.log("Nouveau token CSRF:", response_crsf.csrf_token);

                    $.ajax({
                        url: $('#url').attr('content') +'/api/update_mdp/' + id,
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': response_crsf.csrf_token,
                        },
                        data: {
                            mdp1: password.val(),
                        },
                        success: function(response) {

                            document.getElementById('preloader_ch').remove();

                            if (response.success) {

                                let timerInterval;
                                Swal.fire({
                                    title: "Opération éffectuée, Veuillez patienter un instant s'il vous plaît",
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                            timer.textContent = `${Swal.getTimerLeft()}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        location.reload(); // Rafraîchir la page après le timer
                                    }
                                });

                            } else if (response.error) {

                                showAlert('Erreur', 'Une erreur est survenue lors de l\'operation.','error');

                            }
                        },
                        error: function() {
                            document.getElementById('preloader_ch').remove();
                            showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                        }
                    });

                },
                error: function() {
                    console.log("Erreur lors du rafraîchissement du token CSRF");
                    document.getElementById('preloader_ch').remove();
                    showAlert('Erreur', 'Erreur lors de la mise à jour.','error');
                }
            });
        }

        function deletee() 
        {

            const id = $('#id_del').val();

            var modal = bootstrap.Modal.getInstance($('#Mdelete')[0]);
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_user_login/' + id,
                method: 'GET',  // Use 'POST' for data creation
                success: function(response) {
                    $('#preloader_ch').remove(); // Remove preloader

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', response.message, 'success');
                    } else if (response.error) {
                        showAlert('Erreur', response.message, 'error');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove(); // Remove preloader

                    showAlert('Erreur', 'Erreur lors de la suppression.', 'error');
                }
            });
        }

    });
</script>

@endsection