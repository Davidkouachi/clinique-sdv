$(document).ready(function () {

    select_op_magasin('#magasin_id');

    function list_operation() {

        let date1 = $('#Date1').val();
        let date2 = $('#Date2').val();
        let magasin_id = $('#magasin_id').val();

        $.ajax({
            url: url_base + '/api/list_operation_all/'+date1+'/'+date2+'/'+magasin_id+'/0',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                preloader('end');
                const operation = data.data;
                const donne = data.donne;

                // Détruire l'instance DataTable existante (si elle existe)
                const table = $('.table_operation');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                // Effacer le contenu du tableau
                table.find("tbody").empty();

                let row = '';

                if (operation.length > 0) {

                    // const table = $('.table_client');
                    // table.DataTable().destroy();

                    $.each(operation, function(index, item) {

                        row += `
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${index + 1}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge ${item.type_operation == '4' || item.type_operation == '5' ? 'bg-secondary' : (item.type_operation == 6 || item.type_operation == 7 ? 'bg-warning' : (item.type == 'sortie' ? 'bg-danger' : (item.type == 'entree' ? 'bg-success' :'bg-secondary' )))}">
                                        ${item.type_operation == '4' ? 'Ouverture de caisse' : (item.type_operation == '5'   ? 'Fermeture de caisse' : (item.type_operation == 6 || item.type_operation == 7 ? 'Transfert de recettes' : (item.type == 'entree' ? 'Entrer d\'argent' : (item.type == 'sortie' ? 'Sortie d\'argent' :'Systéme' ))))}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge ${item.type == 'sortie' ? 'bg-danger' : (item.type == 'entree' ? 'bg-success' : (item.type_operation == '4' || item.type_operation == '5' ? 'bg-secondary' : (item.type_operation == 6 || item.type_operation == 7 ? 'bg-warning' : 'bg-secondary' )))}">
                                        ${(item.type == 'sortie' || item.type_operation == 6 ? '-' : (item.type == 'entree' || item.type_operation == 7 ? '+' : ''))} 
                                        ${item.montant ? item.montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount" >
                                        ${item.login}
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="badge bg-azure" >
                                        ${item.magasin_nom}
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount}" >
                                       ${formatDateHeure(item.dateop)}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDateHeure(item.created_at)}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <ul class="nk-tb${tableBtnPosition == 1 ? `-actions` : ``} gx-1 d-flex">
                                        <li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-detail text-warning"
                                                title="Détails"
                                                data-codeop="${item.codeop}"
                                                data-type="${item.type}"
                                                data-typeop="${item.type_operation}"
                                                data-libelle="${item.libelle}"
                                                data-montant="${item.montant}"
                                                data-magasin="${item.magasin_nom}"
                                                data-dateop="${item.dateop}"
                                                data-login="${item.login}"
                                                data-created_at="${item.created_at}"
                                                data-numop="${item.num_operation}"
                                            >
                                                <em class="icon ni ni-eye"></em>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        `;

                        $('#total').text('Total : '+donne['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#entrer').text('Entrer : + '+donne['entree'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#sortie').text('Sortie : - '+donne['sortie'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');

                        // $(`#detail-${item.matricule}`).on('click', function() {
                        //     console.log(item.nomprenom);
                        //     $('#m_nom').text(item.nomprenom);
                        // });

                        // $(`#delete-${item.id}`).on('click', function() {
                        //     $('#litIddelete').val(item.id);
                        // });
                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_operation", { responsive: { details: true } });
                } else {
                    initializeDataTable(".table_operation", { responsive: { details: true } });

                    $('#total').text('Total : 0 Fcfa');
                    $('#entrer').text('Entrer : + 0 Fcfa');
                    $('#sortie').text('Sortie : - 0 Fcfa');

                }
            },
            error: function() {
                preloader('end');
                initializeDataTable(".table_operation", { responsive: { details: true } });

                $('#total').text('Total : 0 Fcfa');
                $('#entrer').text('Entrer : + 0 Fcfa');
                $('#sortie').text('Sortie : - 0 Fcfa');
            }
        });
        
    }

    $('.table_operation').on('click', '.btn-detail', function (event) {
        event.preventDefault();

        const modalBody = `
            <div class="modal fade" tabindex="-1" id="modalLarge">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-inner">
                                <div class="team">
                                    <div class="user-card user-card-s2">
                                        <div class="user-avatar lg">
                                            <img height="80px" width="80px" class="rounded-pill border border-1" src="assets/images/factures.jpg">
                                        </div>
                                        <div class="user-info">
                                            <h6 id="d_creer_par" ></h6> 
                                            <span class="sub-text" id="d_datecreat"></span>
                                        </div>
                                    </div>
                                    <div class="p-2" style="max-height: 400px;" data-simplebar >
                                        <ul class="team-info">
                                            <li><span>Numéro d'opération</span><span id="d_num" ></span></li>
                                            <li><span>Type</span><span id="d_type" ></span></li>
                                            <li><span>Motif</span><span id="d_motif"></span></li>
                                            <li><span>Montant</span><span id="d_montant" ></span></li>
                                            <li><span>Magasin</span><span id="d_magasin" ></span></li>
                                            <li><span>Date d'opération</span><span id="d_dateop" ></span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;


        $('body').append(modalBody);

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalLarge'));
        modal.show();

        const codeop = $(this).data('codeop');
        const type = $(this).data('type');
        const typeop = $(this).data('typeop');
        const libelle = $(this).data('libelle');
        const montant = $(this).data('montant');
        const magasin = $(this).data('magasin');
        const dateop = $(this).data('dateop');
        const login = $(this).data('login');
        const created_at = $(this).data('created_at');
        const numop = $(this).data('numop');

        let solde;
        if (type == 'entree' || typeop == 7) {
            solde = '+ '+montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa';
        } else if (type == 'sortie' || typeop == 6) {
            solde = '- '+montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa';
        } else {
            solde = montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa';
        }

        let op;
        if (type == 'entree') {
            op = 'Entrer d\'argent';
        } else if (type == 'sortie') {
            op = 'Sortie d\'argent';
        } else if (typeop == 4) {
            op = 'Ouverture de la caisse';
        } else if (typeop == 5) {
            op = 'Fermeture de la caisse';
        } else if (typeop == 6 || typeop == 7) {
            op = 'Transfert de recettes';
        }

        $('#d_creer_par').text('Créer par '+login);
        $('#d_datecreat').text('Le '+formatDate(created_at));

        $('#d_type').text(op);
        $('#d_motif').text(libelle);
        $('#d_montant').text(solde);
        $('#d_magasin').text(magasin);
        $('#d_dateop').text('Le '+formatDateHeure(created_at));
        $('#d_num').text(numop ?? 'Aucun');


    });

    list_operation();
    $("#btn_search").on('click', function (event) {
        event.preventDefault();

        // Ajouter le préchargeur
        preloader('start');

        list_operation();

    });

});