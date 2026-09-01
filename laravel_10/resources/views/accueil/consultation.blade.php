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
            Consultations
        </li>
    </ol>
    <div class="ms-auto d-flex flex-row justify-content-center align-items-center">
        <div class="d-md-block d-none" >
            <li class="me-2" style="display: block;" id="div_btn_affiche_stat">
                <a class="btn btn-sm btn-warning" id="btn_affiche_stat">
                    Afficher les Statstiques
                    <i class="ri-eye-line" ></i>
                </a>
            </li>
            <li class="me-2" style="display: none;" id="div_btn_cache_stat">
                <a class="btn btn-sm btn-danger" id="btn_cache_stat">
                    Cacher les Statstiques
                    <i class="ri-eye-off-line" ></i>
                </a>
            </li>
        </div>
        <div class="d-flex flex-row gap-1 day-sorting">
            <input type="date" class="form-control" id="stat_bord_date" value="{{ \Carbon\Carbon::now()->toDateString() }}" max="{{ \Carbon\Carbon::now()->toDateString() }}">
        </div>
    </div>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 cadreTitle">
                <div class="card-body">
                    <div class="py-4 px-3 text-white">
                        <h3>CONSULTATIONS</h3>
                        <h6>Services / Consultations</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3 mb-3" id="stat_consultation_date" style="display: none;">
        <div class="row gx-3">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="w-100">
                            <div class="input-group">
                                <span class="input-group-text">Du</span>
                                <input type="date" id="searchDate1_stat" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}">
                                <span class="input-group-text">au</span>
                                <input type="date" id="searchDate2_stat" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                <a id="btn_search_stat_const_date" class="btn btn-outline-success ms-auto">
                                    <i class="ri-search-2-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-3 mb-3" id="stat_consultation" ></div>

    <div class="row gx-3">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Liste des Conultations
                    </h5>
                </div>
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="w-100">
                        <div class="input-group">
                            <span class="input-group-text">Du</span>
                            {{-- <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-1 months')) }}" max="{{ date('Y-m-d') }}"> --}}
                            <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d', strtotime('-15 days')) }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">au</span>
                            <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                <i class="ri-search-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="">
                            <table id="Table_day" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">N°</th>
                                        <th scope="col">N° Consultation</th>
                                        <th scope="col">N° dossier</th>
                                        <th scope="col">Nom et Prénoms</th>
                                        {{-- <th scope="col">Contact</th> --}}
                                        <th scope="col">Médecin consultant</th>
                                        <th scope="col">Motif</th>
                                        <th scope="col">Prix</th>
                                        <th scope="col">N° Facture</th>
                                        <th scope="col">Date</th>
                                        <th scope="col"></th>
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

<div class="modal fade" id="MdeleteCons" tabindex="-1" aria-labelledby="delRowLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="delRowLabel">
                    Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment supprimé cette consultation ?
                <input type="hidden" id="IddeleteCons">
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                    <a class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Non</a>
                    <button id="deleteBtnCons" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/consultation.js')}}"></script>

<script>
    $(document).ready(function() {

        $("#deleteBtnCons").on("click", delete_cons);

        $('#btn_search_table').on('click', function() {
            $('#Table_day').DataTable().ajax.reload();
        });

        $('#searchDate1').on('change', function() {
            const date1Value = $(this).val(); // Récupère la valeur de #searchDate1
            const $date2 = $('#searchDate2'); // Sélecteur pour #searchDate2

            if ($date2.val() && new Date(date1Value) > new Date($date2.val())) {
                showAlert('Erreur', 'La date de début ne peut pas être supérieure à la date de fin.', 'warning');
                $(this).val($date2.val()); // Définit la valeur de #searchDate1 égale à celle de #searchDate2
            } else {
                $date2.attr('min', date1Value); // Définit la date minimale pour #searchDate2
            }
        });

        $('#searchDate2').on('change', function() {
            const date1Value = $('#searchDate1').val(); // Récupère la valeur de #searchDate1
            const date2Value = $(this).val(); // Récupère la valeur de #searchDate2

            if (new Date(date1Value) > new Date(date2Value)) {
                showAlert('Erreur', 'La date de fin ne peut pas être inférieure à la date de début.', 'warning');
                $(this).val(date1Value); // Définit la valeur de #searchDate2 égale à celle de #searchDate1
            }
        });

        $('#btn_affiche_stat').on('click', function() {
            $('#div_btn_affiche_stat').hide();
            $('#div_btn_cache_stat').show();

            $('#stat_consultation_date').show();
            $('#stat_consultation').empty();

            $('#stat_consultation').css({
                'height': 'auto',  // Vous pouvez ajuster la hauteur à votre convenance
                'overflow-y': 'hidden'
            });
        });

        $('#btn_cache_stat').on('click', function() {
            $('#div_btn_affiche_stat').show();
            $('#div_btn_cache_stat').hide();

            $('#stat_consultation_date').hide();
            $('#stat_consultation').empty();

            $('#stat_consultation').css({
                'height': 'auto',  // Vous pouvez ajuster la hauteur à votre convenance
                'overflow-y': 'hidden'
            });
        });

        $('#btn_search_stat_const_date').on('click', function() {
            Statistique_cons();
        });

        // ------------------------------------------------------------------

        function Statistique_cons() {

            const stat_consultation = document.getElementById("stat_consultation");

            const div = document.createElement('div');
            div.innerHTML = `
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                    <strong>Chargement des données...</strong>
                </div>
            `;
            stat_consultation.appendChild(div);

            const date1 = $('#searchDate1_stat').val();
            const date2 = $('#searchDate2_stat').val();

            $('#div_btn_affiche_stat').hide();
            $('#div_btn_cache_stat').hide();

            fetch($('#url').attr('content') +`/api/statistique_reception_cons/${date1}/${date2}`) // API endpoint
                .then(response => response.json())
                .then(data => {

                    const typeactes = data.typeacte;
                    stat_consultation.innerHTML = '';

                    $('#div_btn_affiche_stat').hide();
                    $('#div_btn_cache_stat').show();

                    if (typeactes.length > 0) {

                        // Loop through each item in the chambre array
                        typeactes.forEach((item, index) => {
                            // Create a new row
                            const row = document.createElement('div');
                            row.className = "col-xxl-3 col-xl-4 col-md-6 col-12";
                            // Create and append cells to the row based on your table's structure
                            row.innerHTML = `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 border border-primary rounded-circle me-3">
                                                <div class="icon-box md bg-primary-subtle rounded-5">
                                                    <i class="ri-stethoscope-line fs-4 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="lh-1">
                                                    ${item.libgaran}
                                                </h6>
                                                <p class="m-0">
                                                    ${item.nbre} Consultation(s)
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Assurance</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_assurance.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Part Patient</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.part_patient.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-end justify-content-between mt-1">
                                            <div class="text-start">
                                                <p class="mb-0 text-primary">Montant Total</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="mb-0 text-primary">
                                                    ${formatPrice(item.total.toString())} Fcfa
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            // Append the row to the table body
                            stat_consultation.style.height = "550px";
                            stat_consultation.style.overflowY = "auto";
                            stat_consultation.appendChild(row);

                        });
                    } else {

                        stat_consultation.innerHTML = '';
                        const div_alert = document.createElement('div');
                        div_alert.innerHTML = `
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <strong class="text-danger" >Aucune données n'a été trouvées</strong>
                            </div>
                        `;
                        stat_consultation.appendChild(div_alert);

                    }
                })
                .catch(error => {

                    stat_consultation.innerHTML = '';
                    const div_alert = document.createElement('div');
                    div_alert.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <strong class="text-danger" >Erreur lors du chargement des données</strong>
                        </div>
                    `;
                    stat_consultation.appendChild(div_alert);

                    console.error('Erreur lors du chargement des données:', error);
                });
        }

        // ------------------------------------------------------------------

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: function(data, callback) {
                const startDate = $('#searchDate1').val();
                const endDate = $('#searchDate2').val();
                
                $.ajax({
                    url: $('#url').attr('content') +`/api/list_cons_all/${startDate}/${endDate}`,
                    type: 'GET',
                    success: function(response) {
                        callback({ data: response.data });
                    },
                    error: function() {
                        console.log('Error fetching data. Please check your API or network.');
                    }
                });
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'idconsexterne',
                    searchable: true, 
                },
                {
                    data: 'numdossier',
                    render: (data, type, row) => {
                        return data ? `${data}` : 'Aucun';
                    },
                    searchable: true,
                },
                { 
                    data: 'nom_patient',
                    searchable: true, 
                },
                // { 
                //     data: 'tel_patient', 
                //     render: (data) => `+225 ${data}`,
                //     searchable: true, 
                // },
                { 
                    data: 'nom_medecin',
                    searchable: true, 
                },
                { 
                    data: 'garantie',
                    searchable: true, 
                },
                { 
                    data: 'montant', 
                    render: (data) => `${formatPriceT(data)} Fcfa`,
                    searchable: true, 
                },
                { 
                    data: 'numfac', 
                    render: (data) => `${data}`,
                    searchable: true, 
                },
                { 
                    data: 'date', 
                    render: (data) => `${formatDate(data)}`,
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
                                    <a href="#" class="dropdown-item text-info" id="Cfacture" 
                                    data-idconsexterne="${row.idconsexterne}">
                                        <i class="ri-printer-fill"></i>
                                        Imprimer Facture
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="Cfiche" 
                                    data-idconsexterne="${row.idconsexterne}">
                                        <i class="ri-printer-fill"></i>
                                        Imprimer Fiche
                                    </a>
                                </li>
                                ${parseFloat(row.montant_regle) === 0 ? `
                                    <li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#MdeleteCons" id="deleteCons" data-numfac="${row.numfac}" 
                                        >
                                            <i class="ri-delete-bin-line"></i>
                                            Supprimer
                                        </a>
                                    </li>` : 
                                ''}
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

            $('#Table_day').on('click', '#Cfacture', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const code = $(this).data('idconsexterne');

                fetch($('#url').attr('content') +`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                    pdfFactureConsultation(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });

            $('#Table_day').on('click', '#Cfiche', function() {
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;
                // Add the preloader to the body
                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                const code = $(this).data('idconsexterne');

                fetch($('#url').attr('content') +`/api/fiche_consultation/${code}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    var preloader = document.getElementById('preloader_ch');
                        if (preloader) {
                            preloader.remove();
                        }

                    pdfFicheConsultation(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });
            });

            $('#Table_day').on('click', '#deleteCons', function() {
                const numfac = $(this).data('numfac');

                $('#IddeleteCons').val(numfac);
            });
        }

        function delete_cons() {

            const numfac = document.getElementById('IddeleteCons').value;

            var modal = bootstrap.Modal.getInstance(document.getElementById('MdeleteCons'));
            modal.hide();

            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Add the preloader to the body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/delete_Cons/'+numfac,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload(null, true);
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert("ERREUR", 'Echec de l\'opération', "error");
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

    });
</script>

@endsection
