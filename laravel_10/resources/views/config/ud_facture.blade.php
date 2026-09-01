@extends('app')

@section('titre', 'Nouveau Produit')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Mise à jour / Suppression Facture
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">    
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-left" id="customTab4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-deleteFac" data-bs-toggle="tab" href="#deleteFac" role="tab" aria-controls="deleteFac" aria-selected="false" tabindex="-1">
                                    <i class="ri-article-line me-2"></i>
                                    UD Facture
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="alert bg-info text-white alert-dismissible d-flex align-items-center fade show fade-in-out" role="alert">
                                <div>                                
                                    <h6>NOTE : </h6> 
                                    Ce volet est destiné aux mise à jour (date d'enregistrement) et suppression de facture de tout type. Toutes opérations est tracées pour d'eventuelle contrôle interne.
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="tab-pane active show fade" id="deleteFac" role="tabpanel" aria-labelledby="tab-deleteFac">
                                {{-- <div class="card-header d-flex align-items-center justify-content-center">
                                    <h5 class="card-title">
                                        Supprimer une Facture
                                    </h5>
                                </div> --}}
                                <div class="card-body">
                                    <div class="row gx-3">
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Type</label>
                                                <select class="form-select select2" id="type_facture">
                                                    <option value="">Selectionner</option>
                                                    <option value="updateDate">Changer date</option>
                                                    <option value="delete">Supprimer</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Acte</label>
                                                <select class="form-select select2" id="acte_facture">
                                                    <option value="">Selectionner</option>
                                                    <option value="cons">Consultations</option>
                                                    <option value="exam">Examens</option>
                                                    <option value="soins">Soins Ambulatoires</option>
                                                    <option value="hosp">Hospitalisations</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">N° Facture</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">N°</span>
                                                    <input type="text" class="form-control" id="numfac_facture" autocomplete="off" placeholder="Saisie Obligatoire">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Motif (Obligatoire)
                                                </label>
                                                <textarea style="resize: none;" rows="7" class="form-control" id="motif_facture"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-5 text-center">
                                            <button type="button" id="btnValide" class="btn btn-success">
                                                Valider
                                                <i class="ri-check-line"></i>
                                            </button>
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

<script>
    $(document).ready(function () {

        $("#btnValide").on("click", factUD);

        $('#type_facture').on('change', function () {
            let value = $(this).val();

            // On supprime le champ date s'il existe déjà
            $('#date_facture_wrapper').remove();

            if (value === "updateDate") { 
                // Bloc HTML à insérer dynamiquement
                let dateField = `
                    <div class="col-lg-4 col-sm-6" id="date_facture_wrapper">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <div class="input-group">
                                <input type="datetime-local" class="form-control" id="date_facture" max="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                `;

                // Insérer juste après le bloc N° Facture
                $('#numfac_facture').closest('.col-lg-4').after(dateField);
            }
        });

        function factUD()
        {
            const login = @json(Auth::user()->login);
            const type = $("#type_facture").val().trim();
            const acte = $("#acte_facture").val().trim();
            const numfac = $("#numfac_facture").val().trim();
            const motif = $("#motif_facture").val().trim();

            if(!type || !acte || !numfac || !motif){
                showAlert('Alert', 'Veuillez remplir tous les champs','warning');
                return false;
            }

            // Vérifier si le champ date existe
            let date = null;
            if ($("#date_facture").length > 0) {
                date = $("#date_facture").val().trim();
                if (!date) {
                    showAlert('Alert', 'Veuillez sélectionner une date','warning');
                    return false;
                }
            }

            if (numfac.startsWith("FCE")) {
                if (acte !== 'cons') {
                    showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                    return false;
                }
            } else if (numfac.startsWith("FCS")) {
                if (acte !== 'soins') {
                    showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                    return false;
                }
            } else if (numfac.startsWith("FCB")) {
                if (acte !== 'exam') {
                    showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                    return false;
                }
            } else if (numfac.startsWith("FCH")) {
                if (acte !== 'hosp') {
                    showAlert('Alert', 'Ce numéro de facture ne correspond pas à l\'acte sélectionné', 'warning');
                    return false;
                }
            }else {
                showAlert('Alert', 'Veuillez vérifier le numéro de facture et l\'acte.', 'warning');
                return false;
            }

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            $("body").append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/ud_facture/' + numfac,
                method: 'GET',
                data: {
                    type: type,
                    acte: acte,
                    date: date,
                    login: login,
                    motif: motif,
                },
                success: function(response) {

                    $('#preloader_ch').remove();

                    if (response.success) {

                        $("#type_facture").val(null);
                        $("#acte_facture").val(null);
                        $("#numfac_facture").val(null);
                        $("#motif_facture").val(null);
                        $('#date_facture_wrapper').remove();

                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.introuvable) {
                        showAlert('Alert', response.message,'info');
                    } else if (response.error_code) {
                        showAlert('Alert', response.message,'error');
                    } else if (response.error) {
                        showAlert('Alert', response.message,'warning');
                    }

                },
                error: function(xhr, status, error) {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue lors de l\'opération.','error');
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

    });
</script>



@endsection