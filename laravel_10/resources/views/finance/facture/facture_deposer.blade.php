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
            Factures
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    @include('pageTitre', [
        'title' => 'DEPÔTS DE FACTURES',
        'subtitle' => 'Gestions Factures / Liste des Dépôts'
    ])
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">
                        Liste des Dépôts
                    </h5>
                </div>
                {{-- <div class="card-header d-flex align-items-center justify-content-between" style="display: none;">
                    <div class="w-100">
                        <div class="input-group">
                            <span class="input-group-text">Du</span>
                            <input type="date" id="searchDate1" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">au</span>
                            <input type="date" id="searchDate2" placeholder="Recherche" class="form-control me-1" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                            <span class="input-group-text">Statut</span>
                            <select class="form-select me-1" id="statut">
                                <option selected value="tous">Tous</option>
                                <option value="oui">Réglée</option>
                                <option value="non">Non Réglée</option>
                            </select>
                            <a id="btn_search_table" class="btn btn-outline-success ms-auto">
                                <i class="ri-search-2-line"></i>
                            </a>
                        </div>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="">
                        <div class="">
                            <table id="Table_day" class="table m-0 align-middle ">
                                <thead>
                                    <tr class="bg-primary" >
                                        <th scope="col">N°</th>
                                        <th scope="col">Assurance</th>
                                        <th scope="col">Mois</th>
                                        <th scope="col">Année</th>
                                        <th scope="col">Date du dépôt</th>
                                        <th scope="col">Statut</th>
                                        <th scope="col">Montant Total</th>
                                        <th scope="col">Montant Accepté</th>
                                        <th scope="col">Montant Rejeté</th>
                                        <th scope="col">date de création</th>
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
                Voulez-vous vraiment supprimé cet dépôt ?
                <input type="hidden" id="Iddelete">
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

<div class="modal fade" id="Paiement" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >
                    Détail paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="IdPaiement">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant du dépôt</label>
                            <div class="input-group"> 
                                <input readonly type="tel" class="form-control" id="montant_depotP">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Date du dépôt</label>
                            <input readonly type="date" class="form-control" id="date_depotP">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Date du paiement</label>
                            <input type="date" class="form-control" id="date_payer" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Type de payement</label>
                            <select class="form-select" id="type_payer">
                                <option value="">Selectionner</option>
                                <option value="virement">Par Virement Bancaire</option>
                                <option value="cheque">Par Chèque</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12" id="div_num_cheque" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Numéro du Chèque</label>
                            <div class="input-group">
                                <span class="input-group-text">N°</span>
                                <input type="tel" class="form-control" id="num_cheque_payer" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Montant rejeté</label>
                            <div class="input-group">                
                                <input type="tel" class="form-control" id="montant_rejet" placeholder="Facultatif">
                                <span class="input-group-text">Fcfa</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Motif</label>
                            <input type="text" class="form-control" id="motif_rejet" placeholder="Facultatif">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btn_paiement" >
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Détail
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal_Detail">
                
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('jsPDF-AutoTable/dist/jspdf.plugin.autotable.min.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/para.js')}}"></script>
<script src="{{asset('assets/app/js/pdf/facture.js')}}"></script>

<script>
    $(document).ready(function() {

        $("#btn_paiement").on("click", eng_paiement);
        $("#deleteBtn").on("click", deletee);
        $("#type_payer").on("change", num_cheque);

        $('#btn_search_table').on('click', function () {
            $('#Table_day').DataTable().ajax.reload();
        });

        $('#num_cheque_payer').on('input', function()
        {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#montant_rejet').on('input', function()
        {
            this.value = formatPrice(this.value.replace(/[^0-9]/g, ''));
        });

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

        function datechangeM() 
        {
            const date1Value = $('#date1M').val();
            const $date2 = $('#date2M');

            $date2.val(date1Value);
            $date2.attr('min', date1Value);
        }

        function num_cheque() {
            const paymentType = $('#type_payer').val();
            $('#num_cheque_payer').val("");

            if (paymentType === 'virement') {
                $('#div_num_cheque').hide();
            } else if (paymentType === 'cheque') {
                $('#div_num_cheque').show();
            } else {
                $('#div_num_cheque').hide();
            }
        }

        function genererNomMois(month) {
          // Tableau des noms de mois en français
          const moisNoms = [
            'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
          ];

          const moisNom = moisNoms[month - 1];
          // Génération du nom de fichier
          return `${moisNom}`;
        }

        $('#Table_day').DataTable({

            processing: true,
            serverSide: false,
            ajax: {
                url: $('#url').attr('content') +`/api/list_depotfacture`,
                type: 'GET',
                dataSrc: 'data',
                error: function(xhr, status, error) {
                    console.error("Erreur AJAX :", error);
                    console.error("Détails :", xhr.responseText);
                }
            },
            columns: [
                { 
                    data: null, 
                    render: (data, type, row, meta) => meta.row + 1,
                    searchable: false,
                    orderable: false,
                },
                { 
                    data: 'assurance', render: (data) => `
                    <div class="d-flex align-items-center">
                        <a class="d-flex align-items-center flex-column me-2">
                            <img src="{{ asset('/assets/images/depot_fac.jpg') }}" class="img-2x rounded-circle border border-1">
                        </a>
                        ${data}
                    </div>
                    `,
                    searchable: true,
                },
                { 
                    data: 'periode_mois', 
                    render: genererNomMois, 
                    searchable: true,
                },
                { 
                    data: 'periode_annee', 
                    searchable: true,
                },
                { 
                    data: 'date_depot', 
                    render: formatDate, 
                    searchable: true,
                },
                { 
                    data: 'statut', 
                    render: (data) => `<span class="badge ${data == 1 ? 'bg-success' : 'bg-danger'}">${data == 1 ? 'Réglée' : 'Non Réglée'}</span>`,
                    searchable: true,
                },
                {
                    data: 'montant',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-primary';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant_accepte',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-success';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
                    searchable: true,
                },
                {
                    data: 'montant_rejet',
                    render: (data, type, row) => {
                        const value = data ? formatPrice(data) : 0;
                        const color = 'text-danger';
                        return `<span class="${color}">${value} Fcfa</span>`;
                    },
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
                                ${row.statut == 0 ? `
                                    <li>
                                        <a href="#" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#Paiement" id="paiement"
                                            data-date_depot="${row.date_depot}" 
                                            data-id="${row.id}" 
                                            data-montant="${row.montant}"
                                        >
                                            <i class="ri-hand-coin-line"></i>
                                            Paiement
                                        </a>
                                    </li> ` : 
                                ''}
                                <li>
                                    <a href="#" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif"
                                        data-assurance="${row.assurance}"
                                        data-mois="${row.periode_mois}" 
                                        data-annee="${row.periode_annee}" 
                                        data-montant="${row.montant}" 
                                        data-date_depot="${row.date_depot}" 
                                        data-type="${row.type_paiement}" 
                                        data-cheque="${row.num_cheque}" 
                                        data-date_payer="${row.date_payer}"
                                        data-statut="${row.statut}"
                                        data-creer="${row.creer_id}" 
                                        data-encaisser="${row.encaisser_id}"
                                        data-montant_accepte="${row.montant_accepte}"
                                        data-montant_rejet="${row.montant_rejet}"
                                        data-motif="${row.motif_rejet}"
                                    >
                                        <i class="ri-eye-fill"></i>
                                        Détail
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="detail" data-id="${row.id}">
                                        <i class="ri-printer-fill"></i>
                                        Imprimer Facture
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item text-info" id="printer" data-id="${row.id}">
                                        <i class="ri-printer-fill"></i>
                                        Imprimer Bordereau
                                    </a>
                                </li>
                                ${row.statut == 0 ? `
                                    <li>
                                        <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#Mdelete" id="delete" data-id="${row.id}" 
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
            $('#Table_day').on('click', '#paiement', function() {
                const id = $(this).data('id');
                const date_depot = $(this).data('date_depot');
                const montant = formatPrice($(this).data('montant'));
                
                document.getElementById('date_payer').value = "";
                document.getElementById('type_payer').value = "";
                document.getElementById('num_cheque_payer').value = "";
                document.getElementById('IdPaiement').value = id;
                document.getElementById('date_depotP').value = date_depot;
                document.getElementById('montant_depotP').value = montant;
            });

            $('#Table_day').on('click', '#detail', function() {
                const id = $(this).data('id');
                
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                fetch($('#url').attr('content') +`/api/imp_fac_depot/${id}`)
                    .then(response => response.json())
                    .then(data => {

                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) preloader.remove();

                        const societes = data.societes;
                        const assurance = data.assurance;
                        const month = data.month;
                        const year = data.year;

                        if (societes.length > 0) {
                            pdfFactureEmis(societes, assurance, month, year);
                        } else {
                            showAlert('Informations', 'Aucune donnée trouvée pour cette période', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('#Table_day').on('click', '#delete', function() {
                const id = $(this).data('id');
                
                document.getElementById('Iddelete').value = id;
            });

            $('#Table_day').on('click', '#printer', function() {
                const id = $(this).data('id');
                
                var preloader_ch = `
                    <div id="preloader_ch">
                        <div class="spinner_preloader_ch"></div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', preloader_ch);

                fetch($('#url').attr('content') +`/api/imp_fac_depot_bordo/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        var preloader = document.getElementById('preloader_ch');
                        if (preloader) preloader.remove();

                        const societes = data.societes;
                        const assurance = data.assurance;
                        const month = data.month;
                        const year = data.year;
                        const fac = data.fac;

                        if (societes.length > 0) {
                            pdfFactureEmisBordo(societes, assurance, month, year, fac);
                        } else {
                            showAlert('Informations', 'Aucune donnée trouvée pour cette période', 'info');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données:', error);
                    });
            });

            $('#Table_day').on('click', '#motif', function() {

                const assurance = $(this).data('assurance');
                const mois = genererNomMois($(this).data('mois'));
                const annee = $(this).data('annee');
                const montant = $(this).data('montant');
                const date_depot = $(this).data('date_depot');
                const type = $(this).data('type');
                const cheque = $(this).data('cheque');
                const date_payer = $(this).data('date_payer');
                const statut = $(this).data('statut');
                const creer = $(this).data('creer');
                const encaisser = $(this).data('encaisser');
                const montant_accepte = $(this).data('montant_accepte');
                const montant_rejet = $(this).data('montant_rejet');
                const motif = $(this).data('motif');

                // Handle the 'Delete' button click
                const modal = document.getElementById('modal_Detail');
                modal.innerHTML = '';

                const div = document.createElement('div');
                div.innerHTML = `
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <div class="text-center">
                                        <a href="doctors-profile.html" class="d-flex align-items-center flex-column">
                                            <img src="{{asset('assets/images/depot_facture.avif')}}" class="img-7x rounded-circle mb-3 border border-3">
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
                                            Détail Dépôt
                                        </li>
                                        <li class="list-group-item">
                                            Assurance : ${assurance}
                                        </li>
                                        <li class="list-group-item">
                                            Période : ${mois} ${annee}
                                        </li>
                                        <li class="list-group-item">
                                            Montant : ${formatPrice(montant)} Fcfa
                                        </li>
                                        <li class="list-group-item">
                                            Date du dépôt : ${formatDate(date_depot)}
                                        </li>
                                        <li class="list-group-item">
                                            Enregistré par : ${creer}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        ${statut == 1 ? 
                        `<div class="col-12">
                            <div class=" mb-3">
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item active text-center" aria-current="true">
                                            Détail Paiement
                                        </li>
                                        <li class="list-group-item">
                                            Type de paiement : ${type}
                                        </li>
                                        ${type == 'cheque' ? 
                                        `<li class="list-group-item">
                                            Numéro du cheque : ${cheque}
                                        </li>` : ``}
                                        <li class="list-group-item">
                                            Date de paiement : ${formatDate(date_payer)}
                                        </li>
                                        <li class="list-group-item">
                                            Montant accepté : ${formatPrice(montant_accepte)} Fcfa
                                        </li>
                                        <li class="list-group-item">
                                            Montant refusé : ${formatPrice(montant_rejet)} Fcfa
                                        </li>
                                        <li class="list-group-item">
                                            Motif :  ${motif !== null ? `${motif}` : 'Aucun'}
                                        </li>
                                        <li class="list-group-item">
                                            Enregistré par :  ${encaisser}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>` : ``}
                    </div>    
                `;

                modal.appendChild(div);
            });
        }

        function eng_paiement() {
            
            const login = @json(Auth::user()->login);
            const id = $('#IdPaiement').val();
            const date_depot = $('#date_depotP').val();
            const date = $('#date_payer').val();
            const type = $('#type_payer').val();
            const cheque = $('#num_cheque_payer').val();
            const montant_rejet = $('#montant_rejet').val();
            const motif_rejet = $('#motif_rejet').val();
            const montant_depotP = $('#montant_depotP').val();

            if (!date.trim() || !type.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (type === 'cheque' && !cheque.trim()) {
                showAlert('Alert', 'Tous les champs sont obligatoires.', 'warning');
                return false;
            }

            if (!isValidDate(date)) {
                showAlert('Erreur', 'La date de paiement est invalide.', 'error');
                return false;
            }

            const pDate = new Date(date);
            const Datedepot = new Date(date_depot);

            if (pDate < Datedepot) {
                showAlert('Erreur', 'La date de paiement ne peut pas être inférieure à la date du dépôt.', 'error');
                return false;
            }

            if ((montant_rejet.trim() && !motif_rejet.trim()) || (!montant_rejet.trim() && motif_rejet.trim()))  {
                showAlert('Alert', 'Veuillez vérifier le montannt du rejet et le motif.', 'warning');
                return false;
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('Paiement'));
            modal.hide();

            const preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $('body').append(preloader_ch);

            $.ajax({
                url: $('#url').attr('content') +'/api/paiement_depot_fac/' + id,
                method: 'GET',
                data: {
                    date: date,
                    type: type,
                    cheque: cheque || null,
                    login: login,
                    montant_rejet: montant_rejet,
                    motif_rejet: motif_rejet,
                    montant: montant_depotP,
                },
                success: function(response) {
                    $('#preloader_ch').remove();

                    if (response.success) {
                        $('#date_payer').val('');
                        $('#type_payer').val('').trigger('change');
                        $('#num_cheque_payer').val('');

                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération effectuée', 'success');

                    } else if (response.error) {
                        showAlert('Informations', 'Échec de l\'opération', 'info');

                    } else if (response.non_touve) {
                        showAlert('Information', 'Échec de l\'opération: Dépôt introuvable.', 'info');
                    }
                },
                error: function() {
                    $('#preloader_ch').remove();
                    showAlert('Alert', 'Une erreur est survenue.', 'error');
                }
            });
        }

        function deletee()
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
                url: $('#url').attr('content') +'/api/delete_depotfacture/'+id,
                method: 'GET',
                success: function(response) {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    if (response.success) {
                        $('#Table_day').DataTable().ajax.reload();
                        showAlert('Succès', 'Opération éffectuée.','success');
                    } else if (response.error) {
                        showAlert('Erreur', 'Echec de l\'opération.','error');
                    }
                      
                },
                error: function() {

                    var preloader = document.getElementById('preloader_ch');
                    if (preloader) {
                        preloader.remove();
                    }

                    showAlert('Erreur', 'Erreur est survenue.','error');
                }
            });
        }

        function generatePDFInvoice_Fac_Assurance(societes,assurance,month,year) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'l', unit: 'mm', format: 'a4' });

            const pdfFilename = "FACTURE EMISE - Période de " + genererNomMois(month) + ' ' + year;
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawSection(yPos) {

                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);

                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));

                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));

                // Définir le style pour le texte
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);

                const titleR = "LISTE DES FACTURES PAR ASSURANCE : " + assurance.libelleassurance;
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

                const paddingh = 5;  // Ajuster le padding en hauteur
                const paddingw = 5;  // Ajuster le padding en largeur

                const rectX = titleRX - paddingw;
                let rectY = yPos + 18; // Position initiale du rectangle
                const rectWidth = titleRWidth + (paddingw * 2);
                const rectHeight = 15 + (paddingh * 2);

                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight);

                // Centrer le texte dans le rectangle
                const textY = rectY + (rectHeight / 2) - 2;  // Ajustement de la position Y du texte pour centrer verticalement
                doc.text(titleR, titleRX, textY);

                // Ajout de la date sous le titre avec un saut de ligne
                const dateText = "Période de " + genererNomMois(month) + ' ' + year; // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date

                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);  // Ajuster `+ 10` selon l'espace souhaité entre le titre et la date


                yPoss = (yPos + 40);

                let grandTotalAssurance = 0;
                let grandTotalPatient = 0;
                let grandTotalMontant = 0;

                if (societes.length > 0) {
                    societes.forEach((societe, indexSociete) => {
                        const fac_cons = societe.fac_cons || [];
                        const fac_exam = societe.fac_exam || [];
                        const fac_soinsam = societe.fac_soinsam || [];
                        const fac_hopital = societe.fac_hopital || [];

                        // Fusionner consultations, examens et soins ambulatoires dans un tableau unique
                        const fac_global = [
                            ...fac_cons.map(item => ({
                                ...item,
                                acte: 'Consultation',
                            })),
                            ...fac_exam.map(item => ({
                                ...item,
                                acte: 'Examen',
                            })),
                            ...fac_soinsam.map(item => ({
                                ...item,
                                acte: 'Soins Ambulatoire',
                            })),
                            ...fac_hopital.map(item => ({
                                ...item,
                                acte: 'Hospitalisation',
                            })),
                        ];

                        if (fac_global.length > 0) {
                            // Titre de la société
                            yPoss += 20;
                            doc.setFontSize(14);
                            doc.setFont("Helvetica", "bold");
                            doc.text("Société : " + societe.nomsocieteassure, 15, yPoss);
                            yPoss += 5;

                            // Calculer les totaux pour la société
                            const totalAssurance = fac_global.reduce((sum, item) => sum + parseInt(item.part_assurance || 0), 0);
                            const totalPatient = fac_global.reduce((sum, item) => sum + parseInt(item.part_patient || 0), 0);
                            const totalMontant = fac_global.reduce((sum, item) => sum + parseInt(item.montant || 0), 0);

                            // Ajouter les totaux de cette société aux grands totaux
                            grandTotalAssurance += totalAssurance;
                            grandTotalPatient += totalPatient;
                            grandTotalMontant += totalMontant;

                            // Générer le tableau unique pour consultations, examens et soins ambulatoires avec une ligne de pied
                            doc.autoTable({
                                startY: yPoss,
                                head: [['N°', 'Date', 'Numéro de Bon', 'Patient', 'Acte effectué', 'Montant Total', 'Part Assurance', 'Part assuré']],
                                body: fac_global.map((item, index) => [
                                    index + 1,
                                    formatDate(item.created_at) || '',
                                    item.num_bon || '',
                                    item.patient || '',
                                    item.acte,
                                    (formatPrice(item.montant) || '') + " Fcfa",
                                    (formatPrice(item.part_assurance) || '') + " Fcfa",
                                    (formatPrice(item.part_patient) || '') + " Fcfa",
                                ]),
                                theme: 'striped',
                                tableWidth: 'auto',
                                styles: {
                                    fontSize: 7,
                                    overflow: 'linebreak',
                                },
                                // Footer row with the total values
                                foot: [[
                                    { content: 'Totals', colSpan: 5, styles: { halign: 'center', fontStyle: 'bold' } },
                                    { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    
                                ]]
                            });

                            const finalY = doc.autoTable.previous.finalY || yPoss + 10;
                            yPoss = finalY + 10;

                            if (indexSociete < societes.length - 1) {

                                if (yPoss + 30 > doc.internal.pageSize.height) {
                                    doc.addPage();
                                    yPoss = 20;
                                }
                            }
                            
                        }
                    });

                    const finalY = doc.autoTable.previous.finalY || yPoss + 20;
                    yPoss = finalY + 20;

                    if (yPoss + 40 > doc.internal.pageSize.height) {
                        doc.addPage();
                        yPoss = 20;
                    }

                    // Afficher les grands totaux sur cette page
                    doc.setFontSize(14);
                    doc.setFont("Helvetica", "bold");
                    doc.text("TOTAL DES FACTURES", 15, yPoss);
                    yPoss += 10;

                    const grandTotalInfo = [
                        { label: "Total Assurance", value: formatPrice(grandTotalAssurance) +" Fcfa" },
                        { label: "Total Patient", value: formatPrice(grandTotalPatient) + " Fcfa" },
                        { label: "Montant Total", value: formatPrice(grandTotalMontant) + " Fcfa" },
                    ];

                    // Afficher les grands totaux sur la nouvelle page
                    grandTotalInfo.forEach(info => {
                        doc.setFontSize(11);
                        doc.setFont("Helvetica", "bold");
                        doc.text(info.label, leftMargin, yPoss);
                        doc.setFont("Helvetica", "normal");
                        doc.text(": " + info.value, leftMargin + 50, yPoss);
                        yPoss += 7;
                    });
                }

            }

            function addFooter() {
                // Add footer with current date and page number in X/Y format
                const pageCount = doc.internal.getNumberOfPages();
                const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(0, 0, 0);
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY);
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
                }
            }

            drawSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

        function generatePDFInvoice_Fac_Assurance_Bordo(societes,assurance,month,year, fac) {

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });

            const pdfFilename = "BORDEREAUX DES FACTURES EMISES - Période de " + genererNomMois(month) + ' ' + year;
            doc.setProperties({
                title: pdfFilename,
            });

            let yPos = 10;

            function drawSection(yPos) {

                rightMargin = 15;
                leftMargin = 15;
                pdfWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(10);
                doc.setTextColor(0, 0, 0);
                doc.setFont("Helvetica", "bold");

                const logoSrc = "{{asset('assets/images/logo.png')}}";
                const logoWidth = 22;
                const logoHeight = 22;
                doc.addImage(logoSrc, 'PNG', leftMargin, yPos - 7, logoWidth, logoHeight);

                const title = "ESPACE MEDICO SOCIAL LA PYRAMIDE DU COMPLEXE";
                const titleWidth = doc.getTextWidth(title);
                const titleX = (doc.internal.pageSize.getWidth() - titleWidth) / 2;
                doc.text(title, titleX, yPos);

                doc.setFont("Helvetica", "normal");
                const address = "Abidjan Yopougon Selmer, Non loin du complexe sportif Jesse-Jackson - 04 BP 1523";
                const addressWidth = doc.getTextWidth(address);
                const addressX = (doc.internal.pageSize.getWidth() - addressWidth) / 2;
                doc.text(address, addressX, (yPos + 5));

                const phone = "Tél.: 20 24 44 70 / 20 21 71 92 - Cel.: 01 01 01 63 43";
                const phoneWidth = doc.getTextWidth(phone);
                const phoneX = (doc.internal.pageSize.getWidth() - phoneWidth) / 2;
                doc.text(phone, phoneX, (yPos + 10));

                // Définir le style pour le texte
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                doc.setLineWidth(0.5);
                doc.setTextColor(0, 0, 0);

                const titleR = "BORDEREAUX PAR ASSURANCE : " + assurance.libelleassurance;
                const titleRWidth = doc.getTextWidth(titleR);
                const titleRX = (doc.internal.pageSize.getWidth() - titleRWidth) / 2;

                const paddingh = 5;  // Ajuster le padding en hauteur
                const paddingw = 5;  // Ajuster le padding en largeur

                const rectX = titleRX - paddingw;
                let rectY = yPos + 18; // Position initiale du rectangle
                const rectWidth = titleRWidth + (paddingw * 2);
                const rectHeight = 15 + (paddingh * 2);

                doc.setDrawColor(0, 0, 0);
                doc.rect(rectX, rectY, rectWidth, rectHeight);

                // Centrer le texte dans le rectangle
                const textY = rectY + (rectHeight / 2) - 2;  // Ajustement de la position Y du texte pour centrer verticalement
                doc.text(titleR, titleRX, textY);

                // Ajout de la date sous le titre avec un saut de ligne
                const dateText = "Période de " + genererNomMois(month) + ' ' + year; // Assurez-vous que formatDate est une fonction qui formate la date comme vous le souhaitez
                const dateTextWidth = doc.getTextWidth(dateText);
                const dateTextX = (doc.internal.pageSize.getWidth() - dateTextWidth) / 2; // Centrer la date
                // Positionner la date sous le rectangle
                doc.text(dateText, dateTextX, textY + 10);

                yPoss = (yPos + 50);

                const pageWidth = doc.internal.pageSize.getWidth();
                let text;
                if (fac.statut == 1 ) {
                    if (fac.type_paiement === 'virement') {
                        text = "Paiement effectué le " + formatDate(fac.date_payer) + " par Virement Bancaire";
                    } else if (fac.type_paiement === 'cheque') {
                        text = "Paiement effectué le " + formatDate(fac.date_payer) + " par Chèque. N°" + fac.num_cheque;
                    }
                } else {
                    text = "Paiement non effectué";
                }
                doc.setFontSize(12);
                doc.setFont("Helvetica", "bold");
                if (fac.statut == 1) {
                    doc.setTextColor(0, 128, 0);
                } else {
                    doc.setTextColor(255, 0, 0);
                }
                const textWidth = doc.getTextWidth(text);
                const xPos = (pageWidth - textWidth) / 2;
                doc.text(text, xPos, yPoss);

                yPoss += 5;

                if (societes.length > 0) {

                    // Totals
                    const totalAssurance = societes.reduce((sum, item) => sum + parseInt(item.total_assurance || 0), 0);
                    const totalPatient = societes.reduce((sum, item) => sum + parseInt(item.total_patient || 0), 0);
                    const totalMontant = societes.reduce((sum, item) => sum + parseInt(item.total_montant || 0), 0);

                    doc.autoTable({
                        startY: yPoss,
                        head: [['N°', 'Société', 'Montant Total', 'Part Assurance', 'Part assuré']],
                        body: societes.map((item, index) => [
                            index + 1,
                            item.nomsocieteassure || '',
                            (formatPrice(item.total_montant) || '') + " Fcfa",
                            (formatPrice(item.total_assurance) || '') + " Fcfa",
                            (formatPrice(item.total_patient) || '') + " Fcfa",
                        ]),
                        theme: 'striped',
                        tableWidth: 'auto',
                        styles: {
                            fontSize: 7,
                            overflow: 'linebreak',
                        },
                        foot: [[
                            { content: 'Totals', colSpan: 2, styles: { halign: 'center', fontStyle: 'bold' } },
                            { content: formatPrice(totalMontant) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalAssurance) + " Fcfa", styles: { fontStyle: 'bold' } },
                            { content: formatPrice(totalPatient) + " Fcfa", styles: { fontStyle: 'bold' } },
                                    
                        ]]
                    });
                }

            }

            function addFooter() {
                // Add footer with current date and page number in X/Y format
                const pageCount = doc.internal.getNumberOfPages();
                const footerY = doc.internal.pageSize.getHeight() - 2; // 10 mm from the bottom

                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(0, 0, 0);
                    const pageText = `Page ${i} sur ${pageCount}`;
                    const pageTextWidth = doc.getTextWidth(pageText);
                    const centerX = (doc.internal.pageSize.getWidth() - pageTextWidth) / 2;
                    doc.text(pageText, centerX, footerY);
                    doc.text("Imprimé le : " + new Date().toLocaleDateString() + " à " + new Date().toLocaleTimeString(), 15, footerY); // Left-aligned
                }
            }

            drawSection(yPos);

            addFooter();

            doc.output('dataurlnewwindow');
        }

    });
</script>

@endsection


