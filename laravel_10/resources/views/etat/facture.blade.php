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
            Etats des Factures
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
                                <label class="form-label">Type</label>
                                <select class="form-control select2" id="type">
                                    <option value="tous">Tout</option>
                                    <option value="fac_deposer">
                                        Déposer
                                    </option>
                                    <option value="fac_deposer_regler">
                                        Déposer & régler
                                    </option>
                                    <option value="fac_deposer_non_regler">
                                        Déposer & non-régler
                                    </option>
                                </select>
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
                                    Société
                                </label>
                                <select class="form-select select2" id="societe_id"></select>
                            </div>
                        </div>
                        <dpiv class="col-12">
                            <div class="mb-3">
                                <label class="form-label">
                                    Période
                                </label>
                                <input type="month" class="form-control" id="periode" max="{{ date('Y-m', strtotime('-1 months')) }}">
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
<script src="{{asset('assets/app/js/pdf/etat.js')}}"></script>

@include('select2')

<script>
    document.addEventListener("DOMContentLoaded", function() {

        select_assurance();
        select_societe();

        document.getElementById("btn_imp").addEventListener("click", imp_fac);

        function select_assurance()
        {
            const selectElement = $('#assurance_id');
            selectElement.empty();

            // Ajouter l'option par défaut
            const defaultOption = $('<option>', {
                value: 'tous',
                text: 'Tout'
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

        function select_societe() 
        {
            const selectElement2 = $('#societe_id');
            selectElement2.empty();
            selectElement2.append($('<option>', {
                value: 'tous',
                text: 'Tout',
            }));

            $.ajax({
                url: $('#url').attr('content') +'/api/societe_select_patient_new',
                method: 'GET',
                success: function(response) {
                    const data = response.societe;

                    data.forEach(function(item) {
                        selectElement2.append($('<option>', {
                            value: item.codesocieteassure,
                            text: item.nomsocieteassure,
                        }));
                    });
                },
                error: function() {
                    // showAlert('danger', 'Impossible de generer le code automatiquement');
                }
            });
        }

        function imp_fac()
        {
            const type = document.getElementById('type');
            const assurance_id = $('#assurance_id').val();
            const societe_id = $('#societe_id').val();
            const periode = document.getElementById('periode');

            if (!periode.value.trim()) {
                showAlert('Alert', 'Veuillez saisir une période.','warning');
                return false; 
            }

            if (assurance_id == 'tous' && type.value != 'tous' ) {
                showAlert('Alert', 'Veuillez selectionner une assurance SVP!!! .','info');
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
                url: $('#url').attr('content') +'/api/etat_fac_assurance',
                method: 'GET',
                data: {
                    type: type.value || null, 
                    assurance_id: assurance_id || null, 
                    societe_id: societe_id || null, 
                    periode: periode.value,
                },
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.facture_non_trouve) {

                        showAlert('Informations', 'Aucune facture n\'a été trouvée pour cette période','info');

                    } else if (response.success) {

                        const societes = response.societes;
                        const assurance = response.assurance;
                        const month = response.month;
                        const year = response.year;
                        const type = response.type;
                        const m_total = response.m_total;
                        const m_assurance = response.m_assurance;
                        const m_patient = response.m_patient;

                        if (societes.length > 0) {

                            pdfEtatFacture(societes,assurance,month,year,type,m_total,m_assurance,m_patient);
                        } else {
                           showAlert('Informations', 'Aucune facture n\'a été trouvée pour cette période','info'); 
                        }

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


