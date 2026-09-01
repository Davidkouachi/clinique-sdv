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
            Emissions des Factures
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
                    <h5 class="card-title text-center">Facture par assurance</h5>
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
                                    Assurance
                                </label>
                                <select class="form-select select2" id="assurance_id"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Période
                                </label>
                                <input type="month" class="form-control" id="periode" max="{{ date('Y-m', strtotime('-1 months')) }}">
                            </div>
                        </div>
                        {{-- <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Date Fin
                                </label>
                                <input type="date" class="form-control" id="date2"  max="{{ date('Y-m-d') }}">
                            </div>
                        </div> --}}
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="mb-3 d-flex gap-2 justify-content-start">
                                <button id="btn_imp" class="btn btn-primary">
                                    <i class="ri-printer-line"></i>
                                    Imprimer
                                </button>
                            </div>
                            <div class="mb-3 d-flex gap-2 justify-content-start">
                                <button id="btn_imp_bordo" class="btn btn-warning">
                                    <i class="ri-printer-line"></i>
                                    Bordereaux
                                </button>
                            </div>
                        </div>  
                    </div>
                    <!-- Row ends -->
                </div>
            </div>
        </div>
    </div>
    <!-- Row ends -->
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/facture.js')}}"></script>

@include('select2')

<script>
    $(document).ready(function() {

        select_assurance();

        // $("#date1").on("change", datechange);
        $("#btn_imp").on("click", imp_fac_assurance);
        $("#btn_imp_bordo").on("click", imp_fac_assurance_bordo);

        function isValidDate(dateString) {
            const regEx = /^\d{4}-\d{2}-\d{2}$/;
            if (!dateString.match(regEx)) return false;
            const date = new Date(dateString);
            return dateString === date.toISOString().split('T')[0];
        }

        function datechange() {
            const date1Value = $('#date1').val();
            const $date2 = $('#date2');

            $date2.val(date1Value);
            $date2.attr('min', date1Value);
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

        function imp_fac_assurance() {

            const assurance_id = $('#assurance_id').val().trim();
            // const date1 = $('#date1').val().trim();
            // const date2 = $('#date2').val().trim();
            const periode = $('#periode').val().trim();

            if (!assurance_id || !periode) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: $('#url').attr('content') +'/api/imp_fac_assurance',
                method: 'GET',
                data: { assurance_id, periode },
                success: function(response) {
                    $('#preloader_ch').remove();
                    const { societes, assurance, month, year } = response;
                    if (societes.length > 0) {
                        pdfFactureEmis(societes, assurance, month, year);
                    } else {
                        showAlert('Informations', "Aucune facture n'a été trouvée pour cette période", 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function imp_fac_assurance_bordo() {

            const assurance_id = $('#assurance_id').val().trim();
            // const date1 = $('#date1').val().trim();
            // const date2 = $('#date2').val().trim();
            const periode = $('#periode').val().trim();

            if (!assurance_id || !periode) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            $('body').append('<div id="preloader_ch"><div class="spinner_preloader_ch"></div></div>');

            $.ajax({
                url: $('#url').attr('content') +'/api/imp_fac_assurance_bordo',
                method: 'GET',
                data: { assurance_id, periode },
                success: function(response) {
                    $('#preloader_ch').remove();
                    const { societes, assurance, month, year } = response;
                    if (societes.length > 0) {
                        pdfFactureEmisBordo(societes, assurance, month, year);
                    } else {
                        showAlert('Informations', "Aucune facture n'a été trouvée pour cette période", 'info');
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


