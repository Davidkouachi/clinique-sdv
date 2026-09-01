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
            Nouveau Dépôt de facture
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row justify-content-center">
        <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title text-center">Formulaire Nouveau Dépôt de facture</h5>
                </div>
                <div class="card-header">
                    <div class="text-center">
                        <a class="d-flex align-items-center flex-column">
                            <img src="{{asset('assets/images/depot_fac.jpg')}}" class="img-7x rounded-circle">
                        </a>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Assurance</label>
                                <select class="form-select select2" id="assurance_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Période</label>
                                <input type="month" class="form-control" id="periode" max="{{ date('Y-m', strtotime('-1 months')) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Date de dépôt</label>
                                <input type="date" class="form-control" id="date_depot" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2 justify-content-center">
                                <button id="btn_eng_depot" class="btn btn-success">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        select_assurance();

        $("#btn_eng_depot").on("click", eng_depot);

        function showAlert(title, message, type)
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function isValidDate(dateString) {
            const regEx = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateString.match(regEx)) return false;
            const date = new Date(dateString);
            const timestamp = date.getTime();
            if (typeof timestamp !== 'number' || isNaN(timestamp)) return false;
            return dateString === date.toISOString().split('T')[0];
        }

        function formatPrice(price)
        {

            // Convert to float and round to the nearest whole number
            let number = Math.round(parseFloat(price));
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

        function select_assurance()
        {
            const selectElement = $('#assurance_id');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: '',
                text: 'Selectionner'
            });
            selectElement.append(defaultOption);

            $.ajax({
                url: $('#url').attr('content') +'/api/assurance_select_patient_new',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    data.assurance.forEach(item => {
                        const option = $('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance
                        });
                        selectElement.append(option);
                    });
                },
                error: function() {
                    console.error('Erreur lors du chargement des assurance');
                }
            });
        }

        function eng_depot() 
        {
            const login = @json(Auth::user()->login);
            const $assurance_id = $('#assurance_id');
            const $periode = $('#periode');
            const $date_depot = $('#date_depot');

            if (!$assurance_id.val().trim() || !$periode.val().trim() || !$date_depot.val().trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate($date_depot.val())) {
                showAlert('Erreur', 'La date de dépôt est invalide.', 'error');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: $('#url').attr('content') +'/api/new_depot_fac',
                method: 'GET',
                data: {
                    assurance_id: $assurance_id.val(),
                    periode: $periode.val(),
                    date_depot: $date_depot.val(),
                    login: login,
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        $assurance_id.val("").trigger('change');
                        $periode.val("");
                        $date_depot.val("");
                        showAlert('Succès', 'Opération effectuée', 'success');
                    } else if (response.error) {
                        showAlert('Informations', 'Echec de l\'opération', 'info');
                    } else if (response.existe) {
                        showAlert('Informations', 'La factures a déjà été déposées.', 'info');
                    } else if (response.montant_inferieur) {
                        showAlert('Informations', 'Opération impossible. Car le montant Total = 0 Fcfa.', 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

    });
</script>

@endsection


