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
            Employés
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'EMPLOYES',
        'subtitle' => 'Configurations / Employés'
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
                                    Nouvel Employés
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
                                                <label class="form-label">Civilité</label>
                                                <select class="form-select select2" id="civilite_id">
                                                </select>
                                            </div>
                                        </div>
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
                                                <label class="form-label">Contact en cas d'urgence</label>
                                                <input type="tel" class="form-control" id="tel2" placeholder="Saisie Obligatoire" maxlength="10">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date de naissance</label>
                                                <input type="date" class="form-control" id="datenais" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type de pièce</label>
                                                <select class="form-select select2" id="typepiece">
                                                    <option value=""></option>
                                                    <option value="CNI">
                                                        Carte Nationale d'identité
                                                    </option>
                                                    <option value="PASSEPORT">
                                                        Passport
                                                    </option>
                                                    <option value="EXTRAIT_NAISSANCE">
                                                        Extrait de naissance
                                                    </option>
                                                    <option value="CERTIFICAT_NATIONAL">
                                                        Certificat de nationalité
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Niveau d'étude</label>
                                                <select class="form-select select2" id="niveau">
                                                    <option value=""></option>
                                                    <option value="N">Néant</option>
                                                    <option value="BEPC">BEPC</option>
                                                    <option value="BAC">BAC</option>
                                                    <option value="BAC +1">BAC +1</option>
                                                    <option value="BAC +2">BAC +2</option>
                                                    <option value="BAC +3">BAC +3</option>
                                                    <option value="BAC +4">BAC +4</option>
                                                    <option value="BAC +5">BAC +5</option>
                                                    <option value="BAC +6">BAC +6</option>
                                                    <option value="BAC +7">BAC +7</option>
                                                    <option value="BAC +8">BAC +8</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Diplôme</label>
                                                <select class="form-select select2" id="diplome">
                                                    <option value=""></option>
                                                    <option value="N">Néant</option>
                                                    <option value="BEPC">BEPC</option>
                                                    <option value="BAC">BAC</option>
                                                    <option value="DUT">DUT</option>
                                                    <option value="BTS">BTS</option>
                                                    <option value="LICENCE">LICENCE</option>
                                                    <option value="MASTER">MASTER</option>
                                                    <option value="DOCTORAT">DOCTORAT</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Résidence</label>
                                                <input type="text" class="form-control" id="residence" placeholder="Saisie Obligatoire">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Profession</label>
                                                <select class="form-select select2" id="service_id">
                                                </select>
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
                                                <label class="form-label">Type de contrat</label>
                                                <select class="form-select select2" id="contrat_id">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date début contrat</label>
                                                <input type="date" class="form-control" id="date_debut">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date fin contrat</label>
                                                <input disabled type="date" class="form-control" id="date_fin">
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
                                                        <th scope="col">Nom et Prénoms</th>
                                                        <th scope="col">Matricule</th>
                                                        <th scope="col">Profession</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Téléphone</th>
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
                Voulez-vous vraiment supprimé cet Employés ?
                <input type="hidden" id="Matricule_del">
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
                    <input type="hidden" id="Matricule_up">
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Civilité</label>
                                <select class="form-select select2" id="civilite_idModif">
                                </select>
                            </div>
                        </div>
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
                                <label class="form-label">Contact en cas d'urgence</label>
                                <input type="tel" class="form-control" id="tel2Modif" placeholder="Saisie Obligatoire" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="datenaisModif" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Type de pièce</label>
                                <select class="form-select select2" id="typepieceModif">
                                    <option value=""></option>
                                    <option value="CNI">
                                        Carte Nationale d'identité
                                    </option>
                                    <option value="PASSEPORT">
                                        Passport
                                    </option>
                                    <option value="EXTRAIT_NAISSANCE">
                                        Extrait de naissance
                                    </option>
                                    <option value="CERTIFICAT_NATIONAL">
                                        Certificat de nationalité
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Niveau d'étude</label>
                                <select class="form-select select2" id="niveauModif">
                                    <option value=""></option>
                                    <option value="N">Néant</option>
                                    <option value="BEPC">BEPC</option>
                                    <option value="BAC">BAC</option>
                                    <option value="BAC +1">BAC +1</option>
                                    <option value="BAC +2">BAC +2</option>
                                    <option value="BAC +3">BAC +3</option>
                                    <option value="BAC +4">BAC +4</option>
                                    <option value="BAC +5">BAC +5</option>
                                    <option value="BAC +6">BAC +6</option>
                                    <option value="BAC +7">BAC +7</option>
                                    <option value="BAC +8">BAC +8</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Diplôme</label>
                                <select class="form-select select2" id="diplomeModif">
                                    <option value=""></option>
                                    <option value="N">Néant</option>
                                    <option value="BEPC">BEPC</option>
                                    <option value="BAC">BAC</option>
                                    <option value="DUT">DUT</option>
                                    <option value="BTS">BTS</option>
                                    <option value="LICENCE">LICENCE</option>
                                    <option value="MASTER">MASTER</option>
                                    <option value="DOCTORAT">DOCTORAT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Résidence</label>
                                <input type="text" class="form-control" id="residenceModif" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Profession</label>
                                <select class="form-select select2" id="service_idModif">
                                </select>
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
                                <label class="form-label">Type de contrat</label>
                                <select class="form-select select2" id="contrat_idModif">
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date début contrat</label>
                                <input type="date" class="form-control" id="date_debutModif">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date fin contrat</label>
                                <input type="date" class="form-control" id="date_finModif">
                            </div>
                        </div>
                        {{-- <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Login</label>
                                <input type="text" class="form-control" id="loginModif" placeholder="Saisie Obligatoire" oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div> --}}
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Nouveau Mot de passe</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="passwordModif" placeholder="Facultatif">
                                    <a class="btn btn-white" id="btn_hidden_mpdModif">
                                        <i id="toggleIconModif" class="ri-eye-line text-primary"></i>
                                    </a>
                                </div>
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
        var select = ['#profil_idModif', '#civilite_idModif', '#typepieceModif', '#niveauModif', '#diplomeModif', '#service_idModif', '#contrat_idModif'];
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
        select_civilite();
        select_service();
        select_contrat();

        $("#btn_eng").on("click", eng);
        $("#updateBtn").on("click", updatee);
        $("#deleteBtn").on("click", deletee);

        $('#btn_refresh_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        var inputs = ['tel', 'tel2', 'telModif', 'tel2Modif'];
        inputs.forEach(function(id) {
            $("#" + id).on("input", function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

        $('#contrat_id').on('change', function() {

            console.log($('#contrat_id option:selected').text());

            if ($('#contrat_id option:selected').text() === 'CDI') {
                $('#date_fin').val('');
                $('#date_fin').prop('disabled', true);
            } else{
                if ($('#date_debut').val() !== null ) {
                    $('#date_fin').val('');
                    $('#date_fin').prop('disabled', false);
                }
            }
        });

        $('#date_debut').on('change', function() {
            const date1 = $(this).val();

            if ($('#contrat_id option:selected').text() === 'CDI') {
                $('#date_fin').val('');
                $('#date_fin').prop('disabled', true);
            } else {
                if (date1) {
                    $('#date_fin').prop('disabled', false);
                    $('#date_fin').attr('min', date1);
                }else{
                    $('#date_fin').val('');
                    $('#date_fin').prop('disabled', true);
                }
            }
        });

        $('#date_debutModif').on('change', function() {
            const date1 = $(this).val();
            
            if ($('#contrat_idModif option:selected').text() === 'CDI') {
                $('#date_finModif').val('');
                $('#date_finModif').prop('disabled', true);
            } else {
                if (date1) {
                    $('#date_finModif').prop('disabled', false);
                    $('#date_finModif').attr('min', date1);
                }else{
                    $('#date_finModif').val('');
                    $('#date_finModif').prop('disabled', true);
                }
            }
        });

        $('#contrat_idModif').on('change', function() {
            
            if ($('#contrat_idModif option:selected').text() === 'CDI') {
                $('#date_finModif').val('');
                $('#date_finModif').prop('disabled', true);
            } else{
                if ($('#date_debutModif').val() !== null ) {
                    $('#date_finModif').val('');
                    $('#date_finModif').prop('disabled', false);
                }
            } 
        });

        $("#btn_hidden_mpd").on("click", function(event) 
        {
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

        $("#btn_hidden_mpdModif").on("click", function(event) 
        {
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

        function select_civilite() 
        {
            const selectElement2 = $('#civilite_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#civilite_idModif');
            selectElement3.empty();

            $.ajax({
                url: $('#url').attr('content') +'/api/select_civilite',
                method: 'GET',
                success: function(response) {
                    const data = response.civilite;

                    data.forEach(function(item) {
                        if (item.abreviation == 'Mr' || item.abreviation == 'Mme' || item.abreviation == 'Mlle') {
                            selectElement2.append($('<option>', {
                                value: item.abreviation,
                                text: item.libelle_civilite,
                            }));

                            selectElement3.append($('<option>', {
                                value: item.abreviation,
                                text: item.libelle_civilite,
                            }));
                        }
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_service() 
        {
            const selectElement2 = $('#service_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#service_idModif');
            selectElement3.empty();

            $.ajax({
                url: $('#url').attr('content') +'/api/select_service',
                method: 'GET',
                success: function(response) {
                    const data = response.service;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.code,
                            text: item.libelle,
                        }));

                        selectElement3.append($('<option>', {
                            value: item.code,
                            text: item.libelle,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function select_contrat() 
        {
            const selectElement2 = $('#contrat_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: '',
                text: 'Selectionner',
            }));

            const selectElement3 = $('#contrat_idModif');
            selectElement3.empty();

            $.ajax({
                url: $('#url').attr('content') +'/api/select_contrat',
                method: 'GET',
                success: function(response) {
                    const data = response.contrat;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.code,
                            text: item.libelle,
                        }));

                        selectElement3.append($('<option>', {
                            value: item.code,
                            text: item.libelle,
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

        function calculateDuration(date_debut) {
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
            const civilite = $("#civilite_id");
            const nom = $("#nom");
            const prenom = $("#prenom");
            const email = $("#email");
            const tel = $("#tel");
            const tel2 = $("#tel2");
            const datenais = $("#datenais");
            const typepiece = $("#typepiece");
            const niveau = $("#niveau");
            const diplome = $("#diplome");
            const residence = $("#residence");
            const service_id = $("#service_id");
            const profil_id = $("#profil_id");
            const contrat_id = $("#contrat_id");
            const date_debut = $("#date_debut");
            const date_fin = $("#date_fin");
            const login = $("#login");
            const password = $("#password");

            if (!civilite.val().trim() || !nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !tel2.val().trim() || !datenais.val().trim() || !typepiece.val().trim() || !niveau.val().trim() || !diplome.val().trim() || !residence.val().trim() || !service_id.val().trim() || !profil_id.val().trim() || !contrat_id.val().trim() || !date_debut.val().trim() || !contrat_id.val().trim() || !login.val().trim() || !password.val().trim()) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.val().trim() && !emailRegex.test(email.val().trim())) { 
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10 || tel2.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            if ($('#contrat_id option:selected').text() !== 'CDI' && !date_fin.val().trim()) {
                showAlert('Alert', 'Veuillez vérifier les dates du contrat.', 'warning');
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
                url: $('#url').attr('content') +'/api/new_user',
                method: 'GET',
                data: {
                    civilite: civilite.val(),
                    nom: nom.val(),
                    prenom: prenom.val(),
                    email: email.val(),
                    tel: tel.val(),
                    tel2: tel2.val(),
                    datenais: datenais.val(),
                    typepiece: typepiece.val(),
                    niveau: niveau.val(),
                    diplome: diplome.val(),
                    residence: residence.val(),
                    service_id: service_id.val(),
                    profil_id: profil_id.val(),
                    contrat_id: contrat_id.val(),
                    date_debut: date_debut.val(),
                    date_fin: date_fin.val(),
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

                        civilite.val('').trigger('change');
                        nom.val('');
                        prenom.val('');
                        email.val('');
                        tel.val('');
                        tel2.val('');
                        datenais.val('');
                        typepiece.val('').trigger('change');
                        niveau.val('').trigger('change');
                        diplome.val('').trigger('change');
                        residence.val('');
                        service_id.val('').trigger('change');
                        profil_id.val('').trigger('change');
                        contrat_id.val('').trigger('change');
                        date_debut.val('');
                        date_fin.val('');
                        login.val('');
                        password.val('0000');

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
                url: $('#url').attr('content') +`/api/list_user`,
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
                    data: 'nomprenom', 
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
                    data: 'matricule',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Néant';
                    },
                    searchable: true,
                },
                { 
                    data: 'profession',
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
                    data: 'cel',
                    render: (data, type, row) => {
                        return data ? `+225 ${data}` : 'Néant';
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
                                        data-dateenregistre="${row.dateenregistre}"
                                        data-civilite="${row.civilite}"
                                        data-nomprenom="${row.nomprenom}"
                                        data-cel="${row.cel}"
                                        data-matricule="${row.matricule}"

                                        data-email="${row.email}"
                                        data-tel="${row.cel}"
                                        data-tel2="${row.contacturgence}"
                                        data-datenais="${row.datenais}"
                                        data-residence="${row.residence}"
                                        data-profil="${row.profil}"

                                        data-niveau="${row.niveau}"
                                        data-diplome="${row.diplome}"
                                        data-profession="${row.profession}"

                                        data-contrat="${row.contrat}"
                                        data-date_debut="${row.datecontrat}"
                                        data-date_fin="${row.datefincontrat}"

                                        data-login="${row.login}"
                                    >
                                        <i class="ri-eye-line"></i>
                                        Détail
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Mmodif" id="modif" 
                                        data-matricule="${row.matricule}"
                                        data-civilite="${row.civilite}"
                                        data-nom="${row.nom}"
                                        data-prenom="${row.prenom}"
                                        data-email="${row.email}"
                                        data-tel="${row.cel}"
                                        data-tel2="${row.contacturgence}"
                                        data-datenais="${row.datenais}"
                                        data-typepiece="${row.typepiece}"
                                        data-niveau="${row.niveau}"
                                        data-diplome="${row.diplome}"
                                        data-residence="${row.residence}"
                                        data-service_id="${row.service}"
                                        data-profil_id="${row.profil_id}"
                                        data-contrat_id="${row.typecontrat}"
                                        data-date_debut="${row.datecontrat}"
                                        data-date_fin="${row.datefincontrat}"
                                        data-login="${row.login}"
                                    >
                                        <i class="ri-edit-box-line"></i>
                                        Modifier
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete"
                                        data-matricule="${row.matricule}"
                                    >
                                        <i class="ri-delete-bin-line"></i>
                                        Supprimer
                                    </a>
                                </li>
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
                    dateenregistre: $(this).data('dateenregistre'),
                    civilite: $(this).data('civilite'),
                    nomprenom: $(this).data('nomprenom'),
                    cel: $(this).data('cel'),
                    matricule: $(this).data('matricule'),

                    email: $(this).data('email'),
                    tel: $(this).data('tel'),
                    tel2: $(this).data('tel2'),
                    datenais: $(this).data('datenais'),
                    residence: $(this).data('residence'),
                    profil: $(this).data('profil'),

                    niveau: $(this).data('niveau'),
                    diplome: $(this).data('diplome'),
                    profession: $(this).data('profession'),

                    contrat: $(this).data('contrat'),
                    date_debut: $(this).data('date_debut'),
                    date_fin: $(this).data('date_fin'),

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
                                            <h5>${row.civilite}. ${row.nomprenom}</h5>
                                            <h6 class="text-truncate">+225 ${row.cel}</h6>
                                            <p>Date création : ${formatDate(row.dateenregistre)} </p>
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
                                            Nom et Prénoms : ${row.nomprenom}
                                        </li>
                                        <li class="list-group-item">
                                            Email : ${row.email}
                                        </li>
                                        <li class="list-group-item">
                                            Téléphone : ${row.tel}
                                        </li>
                                        <li class="list-group-item text-danger">
                                            Téléphone en cas d'urgence : ${row.tel2}
                                        </li>
                                        <li class="list-group-item">
                                            Date de Naissance : ${formatDate(row.datenais)}
                                        </li>
                                        <li class="list-group-item">
                                            Âge : ${calculateAge(row.datenais)} ans
                                        </li>
                                        <li class="list-group-item">
                                            Résidence : ${row.residence}
                                        </li>
                                        <li class="list-group-item">
                                            Profil : ${row.profil ?? 'Aucun'}
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
                                            Parcours académique
                                        </li>
                                        <li class="list-group-item">
                                            Niveau d'étude : ${row.niveau}
                                        </li>
                                        <li class="list-group-item">
                                            Diplôme : ${row.diplome}
                                        </li>
                                        <li class="list-group-item">
                                            Profession : ${row.profession}
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
                                            Informations Contract
                                        </li>
                                        <li class="list-group-item">
                                            Type de contrat : ${row.contrat}
                                        </li>
                                        ${row.date_fin != null ? `
                                            <li class="list-group-item">
                                                Du : ${formatDate(row.date_debut)}
                                            </li>
                                            <li class="list-group-item">
                                                Au :  ${formatDate(row.date_fin)}
                                            </li>
                                        ` : 
                                        `
                                            <li class="list-group-item">
                                                Depuis Le : ${formatDate(row.date_debut)}
                                            </li>
                                        ` }
                                        <li class="list-group-item">
                                            Temps passés : ${calculateDuration(row.date_debut)}
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
                                            Informations Sécurité
                                        </li>
                                        <li class="list-group-item">
                                            Login : ${row.login ?? 'Néant'}
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

                const matricule = $(this).data('matricule');
                const civilite = $(this).data('civilite');
                const nom = $(this).data('nom');
                const prenom = $(this).data('prenom');
                const email = $(this).data('email');
                const tel = $(this).data('tel');
                const tel2 = $(this).data('tel2');
                const datenais = $(this).data('datenais');
                const typepiece = $(this).data('typepiece');
                const niveau = $(this).data('niveau');
                const diplome = $(this).data('diplome');
                const residence = $(this).data('residence');
                const service_id = $(this).data('service_id');
                const profil_id = $(this).data('profil_id');
                const contrat_id = $(this).data('contrat_id');
                const date_debut = $(this).data('date_debut');
                const date_fin = $(this).data('date_fin');
                const login = $(this).data('login');

                $('#Matricule_up').val(matricule);
                $('#nomModif').val(nom);
                $('#prenomModif').val(prenom);
                $('#emailModif').val(email);
                $('#telModif').val(tel);
                $('#tel2Modif').val(tel2);
                $('#datenaisModif').val(datenais);
                $('#residenceModif').val(residence);
                $('#date_debutModif').val(date_debut);
                $('#date_finModif').val(date_fin);
                $('#loginModif').val(login);
                $('#passwordModif').val('');

                if (date_fin == null) {
                    $('#date_finModif').val('');
                    $('#date_finModif').prop('disabled', true);
                }

                $('#civilite_idModif').val(civilite).trigger('change');
                $('#typepieceModif').val(typepiece).trigger('change');
                $('#profil_idModif').val(profil_id).trigger('change');
                $('#niveauModif').val(niveau).trigger('change');
                $('#diplomeModif').val(diplome).trigger('change');
                $('#service_idModif').val(service_id).trigger('change');
                $('#contrat_idModif').val(contrat_id).trigger('change');
            });

            $('#Table_day').on('click', '#delete', function() {
                const matricule = $(this).data('matricule');
                // Handle the 'Delete' button click
                $('#Matricule_del').val(matricule);
            });
        }

        function updatee() 
        {

            const matricule = $('#Matricule_up').val().trim();
            const civilite = $("#civilite_idModif");
            const nom = $("#nomModif");
            const prenom = $("#prenomModif");
            const email = $("#emailModif");
            const tel = $("#telModif");
            const tel2 = $("#tel2Modif");
            const datenais = $("#datenaisModif");
            const typepiece = $("#typepieceModif");
            const niveau = $("#niveauModif");
            const diplome = $("#diplomeModif");
            const residence = $("#residenceModif");
            const service_id = $("#service_idModif");
            const profil_id = $("#profil_idModif");
            const contrat_id = $("#contrat_idModif");
            const date_debut = $("#date_debutModif");
            const date_fin = $("#date_finModif");
            // const login = $("#loginModif");
            const password = $("#passwordModif");

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!civilite.val().trim() || !nom.val().trim() || !prenom.val().trim() || !email.val().trim() || !tel.val().trim() || !tel2.val().trim() || !datenais.val().trim() || !typepiece.val().trim() || !niveau.val().trim() || !diplome.val().trim() || !residence.val().trim() || !service_id.val().trim() || !profil_id.val().trim() || !contrat_id.val().trim() || !date_debut.val().trim() || !contrat_id.val().trim() ) {
                showAlert('Alert', 'Veuillez remplir tous les champs SVP.', 'warning');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email.val().trim() && !emailRegex.test(email.val().trim())) { 
                showAlert('Alert', 'Email incorrect.', 'warning');
                return false;
            }

            if (tel.val().length !== 10 || tel2.val().length !== 10) {
                showAlert('Alert', 'Contact incomplet.', 'warning');
                return false;
            }

            if ($('#contrat_idModif option:selected').text() !== 'CDI' && !date_fin.val().trim()) {
                showAlert('Alert', 'Veuillez vérifier les dates du contrat.', 'warning');
                return false;
            }

            if (password.value != null && password.value.length < 5) {
                showAlert('Alerte', 'Le mot de passe doit contenir au moins 5 caractères.', 'warning');
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
                        url: $('#url').attr('content') +'/api/update_user/' + matricule,
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': response_crsf.csrf_token,
                        },
                        data: {
                            civilite: civilite.val(),
                            nom: nom.val(),
                            prenom: prenom.val(),
                            email: email.val(),
                            tel: tel.val(),
                            tel2: tel2.val(),
                            datenais: datenais.val(),
                            typepiece: typepiece.val(),
                            niveau: niveau.val(),
                            diplome: diplome.val(),
                            residence: residence.val(),
                            service_id: service_id.val(),
                            profil_id: profil_id.val(),
                            contrat_id: contrat_id.val(),
                            date_debut: date_debut.val(),
                            date_fin: date_fin.val() || null,
                            // login: login.val(),
                            password: password.val() || null,
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

        function deletee() 
        {

            const matricule = $('#Matricule_del').val();

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
                url: $('#url').attr('content') +'/api/delete_user/' + matricule,
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