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
            Horaires Médécins
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'HORAIRES MEDECIN',
        'subtitle' => 'Réception / Horaires Médecin'
    ])
    <div class="row gx-3 mb-3" >
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-body" style="margin-top: -20px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-twoAAAN" data-bs-toggle="tab" href="#twoAAAN" role="tab" aria-controls="twoAAAN" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Nouvel horaire
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-twoAAAL" data-bs-toggle="tab" href="#twoAAAL" role="tab" aria-controls="twoAAAL" aria-selected="false" tabindex="-1">
                                    <i class="ri-dossier-line me-2"></i>
                                    Liste des rendez-vous
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane active show fade" id="twoAAAN" role="tabpanel" aria-labelledby="tab-twoAAAN">
                                <div class="card-header">
                                    <h5 class="card-title text-center">Nouvel Horaire</h5>
                                </div>
                                <div class="row gx-3 justify-content-center align-items-center mb-4">
                                    <div class="col-12">
                                        <div class=" mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6" id="div_medecin" >
                                        <div class="mb-3 text-center">
                                            <label class="form-label">Medecin</label>
                                            <select class="form-select text-center select2" id="medecin_id"></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="div_Horaire" style="display: none;">
                                    <div class="mb-3 p-2" >
                                        <div class="row gx-3 justify-content-center align-items-center">
                                            <div class="col-12">
                                                <div class="row gx-3 justify-content-center align-items-center">
                                                    <div class="col-12 mb-3 text-center">
                                                        <button type="button" id="add_horaire" class="btn btn-info">
                                                            <i class="ri-sticky-note-add-line"></i>
                                                            Ajouter un horaire
                                                        </button>
                                                    </div>
                                                    <div class="col-12" id="contenu_horaire">

                                                    </div>
                                                    <div class="row gx-3" id="div_btn_horaire" style="display: none;">
                                                        <div class="col-12 mb-3 text-center">
                                                            <button type="button" id="btn_eng" class="btn btn-success">
                                                                Enregistrer
                                                                <i class="ri-send-plane-fill"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="twoAAAL" role="tabpanel" aria-labelledby="tab-twoAAAL">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">
                                        Liste des Rendez-Vous
                                    </h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body row gx-3">
                                    {{-- <div class="col-12">
                                        <div class=" mb-0">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <a class="d-flex align-items-center flex-column">
                                                        <img src="{{asset('assets/images/rdv1.png')}}" class="img-7x rounded-circle border border-2">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="col-12">
                                        <div class="">
                                            <table id="Table_day" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Patient</th>
                                                        <th>Contact</th>
                                                        <th>Médecin</th>
                                                        <th>Spécialité</th>
                                                        <th>Rdv prévu</th>
                                                        <th>Statut</th>
                                                        <th>Date de création</th>
                                                        <th>Actions</th>
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
    <div class="row gx-3 mb-3">
        <div class="col-sm-12 col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-12" >
                            <div class="card-header">
                                <h5 class="card-title text-center">Recherche Horaires Médécins</h5>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Médecins</label>
                                <select class="form-select select2" id="rech_medecin">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Spécialités</label>
                                <select class="form-select select2" id="rech_specialite">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Jours</label>
                                <select class="form-select select2" id="rech_jour">
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="mb-3">
                                <label class="form-label">Périodes</label>
                                <select class="form-select" id="rech_periode">
                                    <option selected value="tout">Tout</option>
                                    <option value="Matin">Matin</option>
                                    <option value="Soir">Soir</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 text-center">
                                <button id="btn_refresh" class="btn btn-outline-primary">
                                    <i class="ri-loop-left-line" ></i>
                                    Rafraîchir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 row gx-3 d-flex mb-3" id="table" style="display: none;" >
            
        </div>
        <div id="message" style="display: none;" class="mb-3">
            <p class="text-center">Aucune donnée n'a été trouvée</p>
        </div>
        <div id="loader" style="display: none;" class="mb-3">
            <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                <strong>Chargement des données...</strong>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
    </div>
</div>

<div class="modal fade" id="Rdv_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nouveau Rendez-Vous</h5>
            </div>
            <div class="modal-body">
                <form id="updateForm mb-3 mt-3">
                    <input type="hidden" id="medecin_id_rdv">
                    <div class="mb-3">
                        <label class="form-label">Médecin</label>
                        <input readonly type="text" class="form-control" id="medecin_rdv">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spécialité</label>
                        <input readonly type="text" class="form-control" id="specialite_rdv">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <select class="form-select select2" id="patient_id_rdv"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <div class="input-group">
                            <span class="input-group-text">+225</span>
                            <input type="tel" class="form-control" id="tel_patient" maxlength="10" placeholder="Saisie Obligatoire">
                        </div>
                    </div>
                    <div class="mb-3" id="div_date_rdv" style="display: none;">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="date_rdv" placeholder="Saisie Obligatoire" min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea class="form-control" id="motif_rdv" rows="3" style="resize: none;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" id="close_M_rdv" data-bs-dismiss="modal">Fermer</button>
                <button style="display: none;" type="button" class="btn btn-success" id="btn_eng_rdv">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Modif_Rdv_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mise à jour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="id_rdvM">
                    <input type="hidden" id="patientid_rdvM">
                    <input type="hidden" id="medecinid_rdvM">
                    <div class="mb-3">
                        <label class="form-label">Médecin</label>
                        <input readonly type="text" class="form-control" id="medecin_rdvM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spécialité</label>
                        <input readonly type="text" class="form-control" id="specialite_rdvM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input readonly type="text" class="form-control" id="patient_rdvM" placeholder="Saisie Obligatoire" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <div class="input-group">
                            <span class="input-group-text">+225</span>
                            <input type="text" class="form-control" id="tel_patientModif" placeholder="Saisie Obligatoire" maxlength="10">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" id="date_rdvM" placeholder="Saisie Obligatoire" min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea class="form-control" id="motif_rdvM" rows="3" style="resize: none;"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-outline-danger" data-bs-dismiss="modal">Fermer</a>
                <button type="button" class="btn btn-success" id="btn_update_rdv">Enregistrer</button>
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
                Voulez-vous vraiment Annulé ce Rendez-Vous
                <input type="hidden" id="Iddelete">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="btn_delete_rdv" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $('#Rdv_modal').on('shown.bs.modal', function () {
        $('#patient_id_rdv').select2({
            theme: 'bootstrap',
            placeholder: 'Selectionner',
            language: {
                noResults: function() {
                    return "Aucun résultat trouvé";
                }
            },
            width: '100%',
            dropdownParent: $('#Rdv_modal'),
        });
    });
</script>

<script>
    $(document).ready(function() {

        select_medecin();
        rech_medecin();
        rech_specialite();
        rech_jour();
        list();
        select_patient();

        $("#add_horaire").on("click", add_select);
        $("#btn_eng").on("click", eng);
        $("#btn_eng_rdv").on("click", eng_rdv);
        $("#btn_update_rdv").on("click", update_rdv);
        $("#btn_refresh").on("click", refresh);
        $("#btn_delete_rdv").on("click", delete_rdv);

        $('#btn_refresh_table_rdv').on('click', function () {
            $('#Table_day').DataTable().ajax.reload(null, false);
        });

        ["#rech_medecin", "#rech_specialite", "#rech_jour", "#rech_periode"].forEach(id => {
            $(id).on("change", function() {
                // Clear the table body
                $('#table').empty();
                list();
            });
        });

        $('#patient_id_rdv').on('change', function () { 
            const selectedValue = $(this).val(); // Récupère la valeur sélectionnée

            if (selectedValue !== '') {
                $('#div_date_rdv').show(); // Affiche le div si une valeur est sélectionnée
            } else {
                $('#div_date_rdv').hide(); // Cache le div si aucune valeur n'est sélectionnée
            }
        });

        var inputs = ['tel_patient','tel_patientModif']; // Array of element IDs
        inputs.forEach(function(id) {
            var inputElement = document.getElementById(id); // Get each element by its ID

            // Allow only numeric input (and optionally some special keys like backspace or delete)
            inputElement.addEventListener('keypress', function(event) {
                const key = event.key;
                // Allow numeric keys, backspace, and delete
                if (!/[0-9]/.test(key) && key !== 'Backspace' && key !== 'Delete') {
                    event.preventDefault();
                }
            });

            // Alternatively, for more comprehensive input validation, use input event listener
            inputElement.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numbers
            });
        });

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

        function select_patient() {
            const selectElement = $('#patient_id_rdv');
            selectElement.empty(); // Vider le select avant de le remplir

            // Option par défaut
            selectElement.append(new Option('Selectionner', '', true, true));

            // Requête pour charger les patients
            fetch($('#url').attr('content') +'/api/name_patient_reception')
                .then(response => response.json())
                .then(data => {
                    if (data && Array.isArray(data.name)) {
                        data.name.forEach(item => {
                            const option = new Option(item.nomprenomspatient, item.idenregistremetpatient);
                            selectElement.append(option);
                        });
                    } else {
                        console.error('Données inattendues :', data);
                    }
                })
                .catch(error => console.error('Erreur lors du chargement des patients:', error));
        }

        function select_medecin() {
            const $selectElement = $('#medecin_id'); // Déclarez l'élément en jQuery
            // Clear existing options
            $selectElement.empty();
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            $selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/select_list_medecin',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    const medecins = data.medecin;
                    medecins.forEach((item) => {
                        const option = $('<option>', {
                            value: item.codemedecin, // Ensure 'id' is the correct key
                            text: item.nomprenomsmed, // Ensure 'name' is the correct key
                        });
                        $selectElement.append(option);
                    });
                },
                error: function(error) {
                    console.error('Erreur lors du chargement des medecins:', error);
                }
            });

            $selectElement.on('change', function() {
                const id = $(this).val();
                if (id) {
                    const url = $('#url').attr('content') +'/api/select_jours';
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            const jours = data.jour;
                            const $contenuDiv = $('#contenu_horaire');
                            $contenuDiv.empty();

                            addSelectHoraire($contenuDiv, jours);

                            $('#div_Horaire').show();
                        },
                        error: function(error) {
                            console.error('Erreur lors du chargement des horaires:', error);
                        }
                    });
                } else {
                    const $contenuDiv = $('#contenu_horaire');
                    $contenuDiv.empty();
                    $('#div_Horaire').hide();
                }
            });
        }

        function rech_medecin()
        {
            const selectElementRech = document.getElementById('rech_medecin');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch($('#url').attr('content') +'/api/select_list_medecin')
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecin;
                    medecins.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value =  `${item.codemedecin}`; // Ensure 'id' is the correct key
                        option2.text = `${item.nomprenomsmed}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des medecin rech:', error));
        }

        function rech_specialite()
        {
            const selectElementRech = document.getElementById('rech_specialite');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch($('#url').attr('content') +'/api/select_specialite')
                .then(response => response.json())
                .then(data => {
                    const rechs = data.specialite;
                    rechs.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value = `${item.codespecialitemed}`; // Ensure 'id' is the correct key
                        option2.textContent = `${item.nomspecialite}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des specialite rech:', error));
        }

        function rech_jour()
        {
            const selectElementRech = document.getElementById('rech_jour');
            // Clear existing options
            selectElementRech.innerHTML = '';
            const defaultOption2 = document.createElement('option');
            defaultOption2.value = 'tout';
            defaultOption2.textContent = 'Tout';
            selectElementRech.appendChild(defaultOption2);

            fetch($('#url').attr('content') +'/api/select_jour')
                .then(response => response.json())
                .then(data => {
                    const rechs = data.rech;
                    rechs.forEach((item, index) => {
                        const option2 = document.createElement('option');
                        option2.value = `${item.id}`; // Ensure 'id' is the correct key
                        option2.textContent = `${item.jour}`; // Ensure 'nom' is the correct key
                        selectElementRech.appendChild(option2);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function addSelectHoraire($contenuDiv, jours) {

            // Créer le groupe de contrôle contenant le select et le bouton supprimer
            const div = $('<div>', { class: 'mb-3' });

            div.html(`
                <div class="input-group">
                    <select class="form-select jour-select w-15">
                        <option value="">Jour</option>
                        ${jours.map(item => 
                            `<option value="${item.id}">${item.jour}</option>`
                        ).join('')}
                    </select>
                    <select class="form-select periode-select w-15">
                        <option value="">Période</option>
                        <option value="Matin">Matin</option>
                        <option value="Soir">Soir</option>
                    </select>
                    <span class="input-group-text">Heure debut : </span>
                    <input type="time" class="form-control heure-debut-input">
                    <span class="input-group-text">Heure Fin : </span>
                    <input type="time" class="form-control heure-fin-input">
                    <button class="btn btn-outline-danger suppr-btn">Supprimer</button>
                </div>
            `);

            // Ajouter l'élément dans le parent (contenu div)
            $contenuDiv.append(div);

            // Vérifier si le contenu horaire est présent
            checkContenuHoraire();

            // Ajouter un event listener pour le bouton supprimer
            div.find('.suppr-btn').on('click', function() {
                div.remove(); // Supprimer l'élément div parent
                checkContenuHoraire();
            });
        }

        function checkContenuHoraire() {
            const $contenuDiv = $('#contenu_horaire');
            const $divBtn = $('#div_btn_horaire');

            // Afficher le bouton si #contenu_horaire contient des éléments, sinon le cacher
            if ($contenuDiv.html().trim() !== "") {
                $divBtn.show();
            } else {
                $divBtn.hide();
            }
        }

        function add_select() {
            const $contenuDiv = $('#contenu_horaire');
            const id = $('#medecin_id').val();

            if (id === '') {
                showAlert("ALERT", "Sélectionner un médecin SVP.", "warning");
                return false;
            }

            const url = $('#url').attr('content') +'/api/select_jours';
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const jours = data.jour;
                    addSelectHoraire($contenuDiv, jours); // Ajouter le premier select
                },
                error: function(error) {
                    console.error('Erreur lors du chargement des données:', error);
                }
            });
        }

        function Verification() {

            let formIsValid = true;
            const medecin_id = document.getElementById('medecin_id').value;

            if (medecin_id === '') {
                showAlert("ALERT", "Veuillez sélectionner un médecin SVP.", "warning");
                formIsValid = false;
                return false;
            }

            const contenuDiv = document.getElementById('contenu_horaire');
            if (contenuDiv.innerHTML.trim() === "") {
                showAlert("ALERT", 'Aucun horaire n\'a été ajouté.', "warning");
                formIsValid = false;
                return false;
            }

            const jourSelects = document.querySelectorAll('.jour-select');
            const periodeSelects = document.querySelectorAll('.periode-select');
            const heureDebutInputs = document.querySelectorAll('.heure-debut-input');
            const heureFinInputs = document.querySelectorAll('.heure-fin-input');

            jourSelects.forEach((item, index) => {
                const jourValue = item.value;
                const periodeValue = periodeSelects[index].value;
                const heureDebut = heureDebutInputs[index].value;
                const heureFin = heureFinInputs[index].value;

                if (!jourValue) {
                    showAlert("ALERT", 'Veuillez sélectionner un jour pour chaque horaire.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!periodeValue) {
                    showAlert("ALERT", 'Veuillez sélectionner une période (Matin ou Soir).', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!heureDebut) {
                    showAlert("ALERT", 'Veuillez sélectionner une heure de début.', "warning");
                    formIsValid = false;
                    return false;
                }

                if (!heureFin) {
                    showAlert("ALERT", 'Veuillez sélectionner une heure de fin.', "warning");
                    formIsValid = false;
                    return false;
                }

                // Vérification que l'heure de fin est bien après l'heure de début
                if (heureDebut >= heureFin) {
                    showAlert("ALERT", 'L\'heure de fin doit être supérieure à l\'heure de début.', "warning");
                    formIsValid = false;
                    return false;
                }
            });

            return formIsValid;
        }

        function eng()
        {
            try {
                const isValid = Verification();
                if (!isValid) {
                    return false;
                }
            } catch (error) {
                showAlert("ALERT", 'Veuillez bien definir tous les horaires SVP', "warning");
                return false;
            }
            
            const selectionsHoraire = [];
            const jourSelects = document.querySelectorAll('.jour-select');
            const periodeSelects = document.querySelectorAll('.periode-select');
            const heureDebutInputs = document.querySelectorAll('.heure-debut-input');
            const heureFinInputs = document.querySelectorAll('.heure-fin-input');

            jourSelects.forEach((item, index) => {
                const selectedJourOption = item.options[item.selectedIndex];
                const jourId = selectedJourOption.value; // ID du jour sélectionné

                const selectedPeriodeOption = periodeSelects[index].options[periodeSelects[index].selectedIndex];
                const periode = selectedPeriodeOption.value; // Période sélectionnée

                const heureDebut = heureDebutInputs[index].value; // Heure de début sélectionnée
                const heureFin = heureFinInputs[index].value;     // Heure de fin sélectionnée

                selectionsHoraire.push({
                    jour_id: jourId,
                    periode: periode,
                    heure_debut: heureDebut,
                    heure_fin: heureFin,
                });
            });

            const medecin_id = $('#medecin_id').val();

            if (medecin_id == '') {
                showAlert("ALERT", 'Veuillez selectionner un médecin SVP.', "warning");
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
                url: $('#url').attr('content') +'/api/new_horaire',
                method: 'GET',
                data:{
                    selections: selectionsHoraire,
                    medecin_id: medecin_id,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        $('#medecin_id').val('').trigger('change');
                        document.getElementById('div_Horaire').style.display = "none";

                        Swal.fire({
                            title: "Opération effectuée",
                            text: "Veuillez patienter un instant s'il vous plaît",
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                refresh();
                            }
                        });

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.json) {
                        showAlert("ERREUR", 'Invalid selections format', "error");
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        };

        function refresh()
        {
            $('#rech_medecin').val('tout').trigger('change');
            $('#rech_specialite').val('tout').trigger('change');
            $('#rech_jour').val('tout').trigger('change');
            $('#rech_periode').val('tout').trigger('change');

            list();
        }

        function list() 
        {
            const tableBody = $('#table');
            const messageDiv = $('#message');
            const loaderDiv = $('#loader');

            // Récupération des filtres
            const medecin = $('#rech_medecin').val();
            const specialite = $('#rech_specialite').val();
            const jour = $('#rech_jour').val();
            const periode = $('#rech_periode').val();

            // console.log(medecin);
            // console.log(specialite);
            // console.log(jour);
            // console.log(periode);

            const url = $('#url').attr('content') +`/api/list_horaire/${medecin}/${specialite}/${jour}/${periode}`;

            // Afficher le loader
            messageDiv.hide();
            tableBody.hide();
            loaderDiv.show();

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecins;
                    tableBody.empty();

                    if (medecins.length > 0) {

                        messageDiv.hide();
                        loaderDiv.hide();
                        tableBody.show();

                        medecins.forEach((medecin) => {

                            let horairesList = '';

                            if (medecin.horaires.length > 0) {
                                horairesList = medecin.horaires.map(horaire => `
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>${horaire.jour} ${horaire.periode}</div>
                                        <div>${horaire.heure_debut} à ${horaire.heure_fin}</div>
                                        <div>
                                            <a id="deleteH-${horaire.id}" class="btn btn-sm btn-outline-danger rounded-5">
                                                <i class="ri-close-fill"></i>
                                            </a>
                                        </div>
                                    </li>
                                `).join('');
                            } else {
                                // Si aucun horaire n'est trouvé, afficher ce message
                                horairesList = `
                                    <li class="list-group-item text-center">
                                        Aucun horaire n'a été trouvé.
                                    </li>
                                `;
                            }

                            const div = document.createElement('div');
                            div.classList.add('col-xxl-3', 'col-lg-4', 'col-md-6', 'col-12', 'mb-3');

                            let buttonRdv = '';

                            if (medecin.horaires.length > 0) {
                                buttonRdv = `
                                    <a class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#Rdv_modal" id="rdv-${medecin.codemedecin}">
                                        <i class="ri-calendar-schedule-line"></i>
                                        Prendre Rendez-Vous
                                    </a>
                                `;
                            }

                            div.innerHTML = `
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <a class="d-flex align-items-center flex-column">
                                                <img src="{{asset('assets/images/docteur.png')}}" class="img-7x rounded-circle mb-3">
                                                <h6>${medecin.nomprenomsmed}</h6>
                                                <h6 class="text-primary">${medecin.specialité}</h6>
                                                ${buttonRdv}
                                                <ul class="list-group">
                                                    ${horairesList}
                                                </ul>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;

                            tableBody.append(div);

                            medecin.horaires.forEach(horaire => {
                                const boutonModif = document.getElementById(`deleteH-${horaire.id}`);
                                boutonModif.addEventListener('click', () => {

                                    var preloader_ch = `
                                        <div id="preloader_ch">
                                            <div class="spinner_preloader_ch"></div>
                                        </div>
                                    `;
                                    // Add the preloader to the body
                                    document.body.insertAdjacentHTML('beforeend', preloader_ch);
                                    
                                    $.ajax({
                                        url: $('#url').attr('content') +`/api/update_horaire/${horaire.id}`,
                                        method: 'GET',
                                        success: function(response) {

                                            var preloader = document.getElementById('preloader_ch');
                                            if (preloader) {
                                                preloader.remove();
                                            }
                                            
                                            if (response.success) {
                                                refresh();   
                                                showAlert("ALERT", 'Mise à jour éffectué', "success");

                                            } else if (response.error) {
                                                showAlert("ERREUR", 'Une erreur est survenue', "error");
                                            }

                                        },
                                        error: function() {
                                            var preloader = document.getElementById('preloader_ch');
                                            if (preloader) {
                                                preloader.remove();
                                            }

                                            showAlert("ERREUR", 'Une erreur est survenue lors de la mise à jour', "error");
                                        }
                                    });
                                });
                            });

                            const rdvButton = document.getElementById(`rdv-${medecin.codemedecin}`);
                            if (rdvButton) {
                                rdvButton.addEventListener('click', () => {

                                    // Mettre à jour les champs avec les informations du médecin sélectionné
                                    document.getElementById('medecin_id_rdv').value = `${medecin.codemedecin}`;
                                    document.getElementById('medecin_rdv').value = `Dr. ${medecin.nomprenomsmed}`;
                                    document.getElementById('specialite_rdv').value = `${medecin.specialité}`;
                                    document.getElementById('btn_eng_rdv').style.display = "none";

                                    $('#date_rdv').val('');
                                    $('#patient_id_rdv').val('').trigger('change');
                                    $('#patient_rdv').val('');
                                    $('#motif_rdv').val('');
                                    $('#tel_patient').val('');

                                    // Configurer les jours et heures autorisés pour le médecin sélectionné
                                    let allowedDays = medecin.horaires.map(horaire => horaire.jour);
                                    let heureDays = medecin.horaires.reduce((map, horaire) => {
                                        let dayMapping = {
                                            'DIMANCHE': 0,
                                            'LUNDI': 1,
                                            'MARDI': 2,
                                            'MERCREDI': 3,
                                            'JEUDI': 4,
                                            'VENDREDI': 5,
                                            'SAMEDI': 6
                                        };
                                        map[dayMapping[horaire.jour]] = horaire.heure_debut;
                                        return map;
                                    }, {});

                                    let dateInput = document.getElementById('date_rdv');

                                    // Supprimer l'écouteur précédent
                                    let newDateInput = dateInput.cloneNode(true);
                                    dateInput.parentNode.replaceChild(newDateInput, dateInput);

                                    newDateInput.addEventListener('change', function (event) {
                                        let selectedDate = new Date(event.target.value);
                                        let selectedDay = selectedDate.getDay(); // Jour numérique de la semaine

                                        // Vérifier si le jour sélectionné est valide
                                        let isValidDay = allowedDays.some(day => {
                                            let dayMapping = {
                                                'DIMANCHE': 0,
                                                'LUNDI': 1,
                                                'MARDI': 2,
                                                'MERCREDI': 3,
                                                'JEUDI': 4,
                                                'VENDREDI': 5,
                                                'SAMEDI': 6
                                            };
                                            return dayMapping[day] === selectedDay;
                                        });

                                        if (!isValidDay) {
                                            newDateInput.value = "";
                                            showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
                                            document.getElementById('btn_eng_rdv').style.display = "none";
                                        } else {
                                            // Ajouter l'heure au jour sélectionné si valide
                                            let selectedHour = heureDays[selectedDay];
                                            let dateString = selectedDate.toISOString().split('T')[0];
                                            newDateInput.value = `${dateString} ${selectedHour}`;

                                            document.getElementById('btn_eng_rdv').style.display = "block";
                                        }
                                    });
                                });
                            }


                        });

                    } else {
                        messageDiv.show();
                        tableBody.hide();
                        loaderDiv.hide();
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    messageDiv.show();
                    tableBody.hide();
                    loaderDiv.hide();
                });
        }

        const contacts = [];

        function eng_rdv() {

            const medecin_id = $('#medecin_id_rdv').val();
            const patient_id = $('#patient_id_rdv').val();
            const date_rdv = $('#date_rdv').val();
            const motif_rdv = $('#motif_rdv').val();
            const tel = $('#tel_patient').val();

            // Vérifier que tous les champs sont remplis
            if (!medecin_id.trim() || !patient_id.trim() || !date_rdv.trim() || !motif_rdv.trim() || !tel.trim()) {
                showAlert("ALERT", 'Veuillez remplir tous les champs.', "warning");
                return false;
            }

            if (tel.length !== 10 ) {
                showAlert("Alert", "Contact incomplet.", "warning");
                return false;
            }

            // Fermer le modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('Rdv_modal'));
            modal.hide();

            // Ajouter le préchargeur
            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            // Envoyer la requête AJAX
            $.ajax({
                url: $('#url').attr('content') +'/api/new_rdv',
                method: 'GET',
                data: {
                    medecin_id: medecin_id,
                    patient_id: patient_id,
                    date: date_rdv,
                    motif: motif_rdv,
                    tel: tel,
                },
                success: function(response) {

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload(null, false);
                        count_rdv_two_day();
                        // showAlert("ALERT", 'Enregistrement éffectué', "success");

                        contacts.push({
                            tel: response.tel,
                            date: formatDateHeure(response.date),
                        });

                        const message = 'Cher Patient, Vous avez un RDV au Centre Medico-Social la Pyramide le ${date}. Arrivez 15 min en avance. Merci';

                        smsSenderMultipleAsync(contacts, message);

                    } else if (response.error) {
                        $('#preloader_ch').remove();
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.existe) {
                        $('#preloader_ch').remove();
                        showAlert("Alert", 'Cet patient a déjà pris Rendez-Vous avec ce Médecin a la même Date. veuillez selectionner  une autre date SVP!!!', "info");
                    }
                },
                error: function() {
                    $('#preloader_ch').remove(); // Supprimer le préchargeur en cas d'erreur
                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            // deferLoading: true,
            ajax: {
                url: $('#url').attr('content') +`/api/list_rdv`,
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
                    data: 'patient', 
                    render: (data, type, row) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/rdv1.png') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>`,
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
                    data: 'medecin',
                    render: (data, type, row) => {
                        return data ? `Dr. ${data}` : 'Néant';
                    },
                    searchable: true,
                },
                { 
                    data: 'specialite',
                    searchable: true, 
                },
                { 
                    data: 'date',
                    render: formatDateHeure,
                    searchable: true, 
                },
                {
                    data: 'statut',
                    render: (data, type, row) => `
                        <span class="badge ${data === 'en attente' ? 'bg-danger' : 'bg-success'}">
                            ${data === 'en attente' ? 'En Attente' : 'Terminer'}
                        </span>
                    `,
                    searchable: true,
                },
                { 
                    data: 'created_at',
                    render: formatDateHeure,
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
                                    <a href="#" class="dropdown-item text-warning" data-motif="${row.motif}" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif"
                                    >
                                        <i class="ri-eye-line"></i>
                                        Détail
                                    </a>
                                </li>
                                ${row.statut == 'en attente' ? 
                                `<li>
                                    <a href="#" class="dropdown-item text-info" data-bs-toggle="modal" data-bs-target="#Modif_Rdv_modal" id="modif" 
                                        data-id="${row.id}"
                                        data-tel="${row.tel}"
                                        data-patient_id="${row.patient_id}"
                                        data-medecin_id="${row.codemedecin}"
                                        data-date="${row.date}"
                                        data-patient="${row.patient}"
                                        data-motif="${row.motif}"
                                        data-medecin="${row.medecin}"
                                        data-specialite="${row.specialite}"
                                        data-horaires='${JSON.stringify(row.horaires)}'
                                    >
                                        <i class="ri-edit-line"></i>
                                        Modifier
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}"
                                    >
                                        <i class="ri-delete-bin-line"></i>
                                        Supprimer
                                    </a>
                                </li>` :
                                `` }
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

        function initializeRowEventListeners() {

            // $('#Table_day').on('click', '#modif', function() {
            //     let id = $(this).data('id');
            //     let date = $(this).data('date');
            //     let patient = $(this).data('patient');
            //     let motif = $(this).data('motif');
            //     let medecin = $(this).data('medecin');
            //     let specialite = $(this).data('specialite');
            //     let patient_id = $(this).data('patient_id');
            //     let medecin_id = $(this).data('medecin_id');
            //     let tel = $(this).data('tel');

            //     $('#id_rdvM').val(id);
            //     $('#patientid_rdvM').val(patient_id);
            //     $('#medecinid_rdvM').val(medecin_id);

            //     let today = new Date();
            //     let formattedToday = today.toISOString().split('T')[0];
            //     $('#date_rdvM').val(date).attr('min', formattedToday);

            //     $('#patient_rdvM').val(patient);
            //     $('#motif_rdvM').val(motif);
            //     $('#medecin_rdvM').val(medecin);
            //     $('#specialite_rdvM').val(specialite);
            //     $('#tel_patientModif').val(tel);

            //     let horairesData = $(this).data('horaires') || [];
            //     let allowedDays = horairesData.map(horaire => horaire.jour);
            //     let heureDays = horairesData.reduce((map, horaire) => {
            //         let dayMapping = {
            //             'DIMANCHE': 0,
            //             'LUNDI': 1,
            //             'MARDI': 2,
            //             'MERCREDI': 3,
            //             'JEUDI': 4,
            //             'VENDREDI': 5,
            //             'SAMEDI': 6,
            //         };
            //         map[dayMapping[horaire.jour]] = horaire.heure_debut;
            //         return map;
            //     }, {});

            //     let dateInput = document.getElementById('date_rdvM');
            //     let previousDate = dateInput.value; // Track previous date

            //     $('#date_rdvM').on('change', function (event) {
            //         let selectedDate = new Date(event.target.value); // Date sélectionnée
            //         let selectedDay = selectedDate.getDay(); // Jour de la semaine

            //         let isValidDay = allowedDays.some(day => {
            //             let dayMapping = {
            //                 'DIMANCHE': 0,
            //                 'LUNDI': 1,
            //                 'MARDI': 2,
            //                 'MERCREDI': 3,
            //                 'JEUDI': 4,
            //                 'VENDREDI': 5,
            //                 'SAMEDI': 6
            //             };
            //             return dayMapping[day] === selectedDay;
            //         });

            //         if (!isValidDay) {
            //             // Restaurer une date valide
            //             $('#date_rdvM').val(date);

            //             showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
            //         } else {
            //             let selectedHour = heureDays[selectedDay];
            //             if (selectedHour) {
            //                 let formattedDate = selectedDate.toISOString().split('T')[0]; // yyyy-MM-dd
            //                 let formattedTime = selectedHour.slice(0, 5); // HH:mm
            //                 $('#date_rdvM').val(`${formattedDate}T${formattedTime}`); // yyyy-MM-ddTHH:mm
            //             } else {
            //                 showAlert("ALERT", "L'heure de début n'est pas définie pour ce jour.", "info");
            //             }
            //         }
            //     });
            // });

            $('#Table_day').on('click', '#modif', function() {
                let id = $(this).data('id');
                let date = $(this).data('date');
                let patient = $(this).data('patient');
                let motif = $(this).data('motif');
                let medecin = $(this).data('medecin');
                let specialite = $(this).data('specialite');
                let patient_id = $(this).data('patient_id');
                let medecin_id = $(this).data('medecin_id');
                let tel = $(this).data('tel');

                $('#id_rdvM').val(id);
                $('#patientid_rdvM').val(patient_id);
                $('#medecinid_rdvM').val(medecin_id);

                let today = new Date();
                let formattedToday = today.toISOString().split('T')[0];
                $('#date_rdvM').val(date).attr('min', formattedToday);

                $('#patient_rdvM').val(patient);
                $('#motif_rdvM').val(motif);
                $('#medecin_rdvM').val(medecin);
                $('#specialite_rdvM').val(specialite);
                $('#tel_patientModif').val(tel);

                let horairesData = $(this).data('horaires') || [];
                let allowedDays = horairesData.map(horaire => horaire.jour);
                let heureDays = horairesData.reduce((map, horaire) => {
                    let dayMapping = {
                        'DIMANCHE': 0,
                        'LUNDI': 1,
                        'MARDI': 2,
                        'MERCREDI': 3,
                        'JEUDI': 4,
                        'VENDREDI': 5,
                        'SAMEDI': 6,
                    };
                    map[dayMapping[horaire.jour]] = horaire.heure_debut;
                    return map;
                }, {});

                let dateInput = document.getElementById('date_rdvM');

                // Supprimer tout ancien écouteur attaché à #date_rdvM
                let newDateInput = dateInput.cloneNode(true);
                dateInput.parentNode.replaceChild(newDateInput, dateInput);

                $(newDateInput).on('change', function(event) {
                    let selectedDate = new Date(event.target.value); // Date sélectionnée
                    let selectedDay = selectedDate.getDay(); // Jour de la semaine

                    let isValidDay = allowedDays.some(day => {
                        let dayMapping = {
                            'DIMANCHE': 0,
                            'LUNDI': 1,
                            'MARDI': 2,
                            'MERCREDI': 3,
                            'JEUDI': 4,
                            'VENDREDI': 5,
                            'SAMEDI': 6
                        };
                        return dayMapping[day] === selectedDay;
                    });

                    if (!isValidDay) {
                        // Restaurer une date valide
                        $(newDateInput).val(date);
                        showAlert("ALERT", 'Veuillez sélectionner un jour valide selon les horaires du médecin.', "info");
                    } else {
                        let selectedHour = heureDays[selectedDay];
                        if (selectedHour) {
                            let formattedDate = selectedDate.toISOString().split('T')[0]; // yyyy-MM-dd
                            let formattedTime = selectedHour.slice(0, 5); // HH:mm
                            $(newDateInput).val(`${formattedDate}T${formattedTime}`); // yyyy-MM-ddTHH:mm
                        } else {
                            showAlert("ALERT", "L'heure de début n'est pas définie pour ce jour.", "info");
                        }
                    }
                });
            });

            $('#Table_day').on('click', '#motif', function() {
                const motif = $(this).data('motif');
                // Handle the 'Delete' button click
                const modal = document.getElementById('modal_Detail_motif');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.innerHTML = `
                       <div class="row gx-3">
                            <div class="col-12">
                                <div class=" mb-3">
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item active text-center" aria-current="true">
                                                Motif
                                            </li>
                                            <li class="list-group-item">
                                                ${motif} 
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>     
                `;

                modal.appendChild(div);
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                
                $('#Iddelete').val(id);
            });
        }

        function delete_rdv() 
        {

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
                url: $('#url').attr('content') +'/api/delete_rdv/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload(null, false);
                        count_rdv_two_day();
                        showAlert('Succès', 'Rendez-Vous annulé.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    }
                
                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur lors de la suppression.','error');
                }
            });
        }

        function update_rdv()
        {
            const id = document.getElementById('id_rdvM').value;
            const patient_id = document.getElementById('patientid_rdvM').value;
            const medecin_id = document.getElementById('medecinid_rdvM').value;
            const date_rdv = document.getElementById('date_rdvM');
            const motif_rdv = document.getElementById('motif_rdvM');
            const tel = document.getElementById('tel_patientModif');

            if (!date_rdv.value.trim() || !motif_rdv.value.trim() || !tel.value.trim()) {
                showAlert("ALERT", 'Veuillez remplir tous les champs.', "warning");
                return false;
            }

            if (tel.value.length !== 10 ) {
                showAlert("Alert", "Contact incomplet.", "warning");
                return false;
            }

            var modal = bootstrap.Modal.getInstance(document.getElementById('Modif_Rdv_modal'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/update_rdv/' + id,
                method: 'GET',
                data:{
                    patient_id: patient_id,
                    medecin_id: medecin_id,
                    date: date_rdv.value,
                    motif: motif_rdv.value,
                    tel: tel.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }
                    
                    if (response.success) {

                        $('#Table_day').DataTable().ajax.reload(null, false);
                        count_rdv_two_day();
                        // showAlert("ALERT", 'Mise à jour éffectué', "success");

                        contacts.push({
                            tel: response.tel,
                            date: formatDateHeure(response.date),
                        });

                        const message = 'Cher Patient, Votre RDV au Centre Medico-Social la Pyramide a été réporter au ${date}. Arrivez 15 min en avance. Merci';

                        smsSenderMultipleAsync(contacts, message);

                        showAlert('Succès', `Opération éffctuée. ${message}`, 'success');

                    } else if (response.error) {
                        showAlert("ERREUR", 'Une erreur est survenue', "error");
                    } else if (response.existe) {
                        showAlert("Alert", 'Cet patient a déjà un RDV programmé a cette date', "info");
                    }

                },
                error: function() {
                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert("ERREUR", 'Une erreur est survenue lors de l\'enregistrement', "error");
                }
            });
        }

        function count_rdv_two_day() 
        {

            fetch($('#url').attr('content') +'/api/count_rdv_two_day')
                .then(response => response.json())
                .then(data => {
                    const nbre = data.nbre || 0;

                    document.getElementById('div_two_rdv').innerHTML = '';

                    if (nbre > 0) {

                        const div = `
                            <div class="sidebar-contact" style="background-color: red;">
                                <a class="text-white" href="{{route('rdv_two_day')}}">
                                    <p class="fw-light mb-1 text-nowrap text-truncate">Rendez-Vous dans 2 jours</p>
                                    <h5 class="m-0 lh-1 text-nowrap text-truncate">${nbre}</h5>
                                    <i class="ri-calendar-schedule-line"></i>
                                </a>
                            </div>
                        `;

                        document.getElementById('div_two_rdv').innerHTML = div;
                    }
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function smsSenderMultipleAsync(contacts, message) {

            const replacePlaceholders = (message, item) => {
                return message
                    .replace('${date}', item.date);
            };

            const params = {
                username: '{{ env('API_SMS_USERNANME') }}',
                password: '{{ env('API_SMS_PASSWORD') }}',
                serviceid: '{{ env('API_SMS_SERVICEID') }}',
                sender: '{{ env('API_SMS_SENDER') }}',
            };

            const requests = contacts.map((item) => {

                const personalizedMessage = replacePlaceholders(message, item);

                const queryString = new URLSearchParams({
                    ...params,
                    msisdn: `+225${item.tel}`,
                    msg: personalizedMessage,
                }).toString();

                const url = `https://api-public-2.mtarget.fr/messages?${queryString}`;

                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => ({ item, success: true, data }))
                    .catch((error) => ({
                        item,
                        success: false,
                        error,
                    }));
            });

            Promise.all(requests).then((results) => {

                contacts.length = 0;

                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                const successCount = results.filter((result) => result.success).length;
                const failureCount = results.length - successCount;

                if (successCount > 0) {
                    showAlert('Succès', `Opération éffctuée. ${successCount}/${results.length} SMS envoyé avec succès`, 'success');
                }

                if (failureCount > 0) {
                    showAlert('Alert', `Opération éffctuée. Mais l'sms de confirmation n'a pas été envoyé`, 'warning');
                }

                if (results.length === 0 || failureCount === results.length) {
                    // Aucun SMS envoyé, probablement pas de connexion
                    showAlert('Alert', 'Opération éffctuée. Mais l\'sms de confirmation n\'a pas été envoyé.', 'warning');
                }

            }).catch((error) => {
                // Gérer les erreurs globales (par exemple, problème réseau avant même de démarrer)
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                showAlert('Alert', 'Opération éffctuée. Mais l\'sms de confirmation n\'a pas été envoyé.', 'warning');
            });
        }

    });
</script>


@endsection
