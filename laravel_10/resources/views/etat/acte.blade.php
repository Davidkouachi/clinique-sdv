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
            Actes éffectués
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <!-- Row starts -->
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title text-center">Actes éffectués</h5>
                </div>
                <div class="card-header">
                    <div class="text-center">
                        <a class="d-flex align-items-center flex-column">
                            <img src="{{asset('assets/images/pdf2.png')}}" class="img-7x">
                        </a>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                Actes
                                </label>
                                <select class="form-select select2" id="acte">
                                    <option value="tous">Tout</option>
                                    <option value="cons">Consultation</option>
                                    <option value="hos">Hospitalisation</option>
                                    <option value="exam">Examen</option>
                                    <option value="soinsam">Soins Ambulatoire</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_pres">
                            <div class="mb-3">
                                <label class="form-label">
                                Filtre Consultation
                                </label>
                                <select class="form-select select2" id="pres">
                                    <option value="tous">Tout</option>
                                    <option value="medecin">Medecin</option>
                                    <option value="specialite">Spécialité</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_medecin">
                            <div class="mb-3">
                                <label class="form-label">
                                Médecin
                                </label>
                                <select class="form-select select2" id="medecin_id"></select>
                            </div>
                        </div>
                        <div class="col-12" style="display: none;" id="div_specialite">
                            <div class="mb-3">
                                <label class="form-label">
                                Spécialité
                                </label>
                                <select class="form-select select2" id="specialite_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                Assurance
                                </label>
                                <select class="form-select select2" id="assurance_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date début
                                </label>
                                <input type="date" class="form-control" id="date1" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date Fin
                                </label>
                                <input type="date" class="form-control" id="date2" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            <div class="mb-3 d-flex gap-2 justify-content-center">
                                <button id="btn_imp" class="btn btn-primary">
                                    <i class="ri-printer-line"></i>
                                    Imprimer
                                </button>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/etat.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        select_medecin();
        select_specialite();
        select_assurance();

        document.getElementById("date1").addEventListener("change", datechange);
        document.getElementById("btn_imp").addEventListener("click", imp_fac);

        $('#acte').on('select2:select', function() {
            change_div_cons();
        });

        $('#pres').on('select2:select', function() {
            change_pres();
        });

        function showAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatPrice(price) {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPriceT(price) {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseInt(price));
            if (isNaN(number)) {
                return '';
            }

            // Format the number with dot as thousands separator
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

        function datechange()
        {
            const date1Value = document.getElementById('date1').value;
            const date2 = document.getElementById('date2');

            date2.value = date1Value;
            date2.min = date1Value;
        }

        function select_medecin()
        {
            const selectElement = document.getElementById('medecin_id');
            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            fetch($('#url').attr('content') +'/api/select_list_medecin')
                .then(response => response.json())
                .then(data => {
                    const medecins = data.medecin;
                    medecins.forEach((item, index) => {
                        const option = document.createElement('option');
                        option.value = `${item.codemedecin}`;
                        option.textContent = `${item.nomprenomsmed}`;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function select_specialite() {

            const selectElement = document.getElementById('specialite_id');

            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            $.ajax({
                    url: $('#url').attr('content') +'/api/select_specialite',
                    method: 'GET',
                    success: function(response) {
                        const data = response.specialite; 

                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.codespecialitemed;
                            option.textContent = item.nomspecialite;
                            selectElement.appendChild(option);
                        });
                    },
                    error: function() {
                        console.error('Erreur lors du chargement des types d\'actes');
                    }
                });
        }

        function change_div_cons() {
            const select = $('#acte').val();
            $('#pres').val('tous').trigger('change'); // Reset 'pres' to 'tous' and trigger change

            if (select === "cons") {
                $('#div_pres').show();
            } else {
                $('#div_pres').hide();
            }

            $('#div_medecin').hide();
            $('#div_specialite').hide();
            $('#medecin_id').val('tous').trigger('change');
            $('#specialite_id').val('tous').trigger('change');
        }

        function change_pres() {
            const select = $('#pres').val();

            if (select === "medecin") {
                $('#div_medecin').show();
                $('#div_specialite').hide();
            } else if (select === "specialite") {
                $('#div_medecin').hide();
                $('#div_specialite').show();
            } else {
                $('#div_medecin').hide();
                $('#div_specialite').hide();
            }

            $('#medecin_id').val('tous').trigger('change');
            $('#specialite_id').val('tous').trigger('change');
        }

        function select_assurance()
        {
            const selectElement = document.getElementById('assurance_id');

            selectElement.innerHTML = '';
            const defaultOption = document.createElement('option');
            defaultOption.value = 'tous';
            defaultOption.textContent = 'Tout';
            selectElement.appendChild(defaultOption);

            fetch($('#url').attr('content') +'/api/assurance_select_patient_new')
                .then(response => response.json())
                .then(data => {
                    data.assurance.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.codeassurance; // Ensure 'id' is the correct key
                        option.textContent = item.libelleassurance; // Ensure 'nom' is the correct key
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des societes:', error));
        }

        function imp_fac()
        {
            const acte = document.getElementById('acte');
            const pres = document.getElementById('pres');
            const medecin_id = document.getElementById('medecin_id');
            const specialite_id = document.getElementById('specialite_id');
            const assurance_id = document.getElementById('assurance_id');
            const date1 = document.getElementById('date1');
            const date2 = document.getElementById('date2');

            if (!date1.value.trim() || !date2.value.trim()) {
                showAlert('Alert', 'Veuillez saisir des dates S\'il vous plaît.','warning');
                return false; 
            }

            function isValidDate(dateString) {
                const regEx = /^\d{4}-\d{2}-\d{2}$/;
                if (!dateString.match(regEx)) return false;
                const date = new Date(dateString);
                const timestamp = date.getTime();
                if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
                return dateString === date.toISOString().split('T')[0];
            }

            if (!isValidDate(date1.value)) {
                showAlert('Erreur', 'La première date est invalide.', 'error');
                return false;
            }

            if (!isValidDate(date2.value)) {
                showAlert('Erreur', 'La deuxième date est invalide.', 'error');
                return false;
            }

            const startDate = new Date(date1.value);
            const endDate = new Date(date2.value);

            if (startDate > endDate) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieur à la date de fin.', 'error');
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
                url: $('#url').attr('content') +'/api/etat_fac_acte',
                method: 'GET',
                data: {
                    acte: acte.value || null, 
                    pres: pres.value || null,
                    medecin_id: medecin_id.value || null, 
                    specialite_id: specialite_id.value || null, 
                    assurance_id: assurance_id.value || null,
                    date1: date1.value, 
                    date2: date2.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.donnee_0) {

                        showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');

                    } else if (response.success) {

                        const acte_cons = response.acte_cons || [];
                        const acte_hop = response.acte_hop || [];
                        const acte_exam = response.acte_exam || [];
                        const acte_soinsam = response.acte_soinsam || [];
                        const date1 = response.date1;
                        const date2 = response.date2;

                        pdfEtatActe(acte_cons,acte_hop,acte_exam,acte_soinsam,date1,date2);

                    } else {
                        showAlert('Informations', 'Aucune donnée n\'a été trouvée pour cette période','info');
                    }

                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Alert', ' Une erreur est survenue.','error');
                }
            });
        }

    });
</script>

@endsection


