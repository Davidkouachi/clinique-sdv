$(document).ready(function () {

    window.list_facture_client = function () {

        $('#donnee1').hide();
        $('#donnee2').hide();

        let client = $('#client').val();

        // Ajouter le préchargeur
        preloader('start');

        $.ajax({
            url: url_base + '/api/list_facture_client/'+client,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                preloader('end');

                const facture = data.data;
                const donne = data.donne;

                const table = $('.table_facture_client');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                table.find("tbody").empty();

                let row = '';

                if (facture.length > 0) {

                    $('#donnee1').show();
                    $('#donnee2').show();

                    $.each(facture, function(index, item) {

                        row += `
                            <tr class="nk-tb-item" >
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${index + 1}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <div class="user-card gap-1">
                                        <div class="user-avatar bg-orange">
                                            <em class="ni ni-file" ></em>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="tb-amount">
                                                ${item.code}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge ${item.regle == 1 ? 'bg-success' : 'bg-danger'}"> 
                                        ${item.regle == 1 ? 'Soldé' : 'Non-soldé'}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge text-primary"> 
                                        ${item.total ? item.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>

                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge text-warning"> 
                                        ${item.partassurance ? item.partassurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge text-success"> 
                                        ${item.partclient ? item.partclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount"> 
                                        ${item.pourcentage_assurance ? item.pourcentage_assurance : '0'} %
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge text-danger"> 
                                        ${item.reste ? item.reste.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDateHeure(item.date)}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <ul class="nk-tb${tableBtnPosition == 1 ? `-actions` : ``} gx-1 d-flex">
                                        ${item.regle == null ? 
                                        `<li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-plus text-success"
                                                title="Effectuer un Versement"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#Versement"
                                                data-code="${item.code}"
                                                data-matricule="${item.matricule}"
                                                data-reste="${item.reste}"
                                            >
                                                <em class="icon ni ni-plus-circle"></em>
                                            </a>
                                        </li>` : ``}
                                        <li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-pdf text-warning"
                                                title="Facture"
                                                data-code="${item.code}"
                                                data-matricule="${item.matricule}"
                                            >
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                        </li>
                                        ${item.versement.length > 0 ? 
                                        `<li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-recu text-primary"
                                                title="Recu Paiement"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalLarge" 
                                                data-code="${item.code}"
                                                data-matricule="${item.matricule}"
                                                data-versement='${JSON.stringify(item.versement)}'
                                            >
                                                <em class="icon ni ni-list"></em>
                                            </a>
                                        </li>` : ``}
                                    </ul>
                                </td>
                            </tr>
                        `;

                        $('#total').text('Montant Total : '+donne['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#payer').text('Montant Payer : '+donne['payer'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#non_payer').text('Montant non-payer : '+donne['non_payer'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_facture_client", { responsive: { details: true } });
                } else {

                    showAlert("Alert", "Aucune facture n'a été trouver.", "info");

                    $('#donnee1').hide();
                    $('#donnee2').hide();

                    // initializeDataTable(".table_facture_client", { responsive: { details: true } });

                    // $('#total').text('Montant Total : 0 Fcfa');
                    // $('#payer').text('Montant Payer : 0 Fcfa');
                    // $('#non_payer').text('Montant non-payer : 0 Fcfa');

                }
            },
            error: function() {
                preloader('end');

                $('#donnee1').hide();
                $('#donnee2').hide();
                // initializeDataTable(".table_facture_client", { responsive: { details: true } });

                // $('#total').text('Montant Total : 0 Fcfa');
                // $('#payer').text('Montant Payer : 0 Fcfa');
                // $('#non_payer').text('Montant non-payer : 0 Fcfa');
            }
        });
        
    }

    $('.table_facture_client').off('click', '.btn-pdf').on('click', '.btn-pdf', function (event) {
        event.preventDefault();

        const code = $(this).data('code');
        const matricule = $(this).data('matricule');

        // Ajouter le préchargeur
        preloader('start');

        $.ajax({
            url: url_base + '/api/imp_fac_vente/'+code+'/'+matricule,
            method: 'GET',
            success: function(response) {
                preloader('end');

                if (response.success) {

                    PDF_Facture_Vente(response.client, response.pres, response.produits,user_magasin, reste_payer = 1);

                } else if (response.error) {

                    showAlert("Alert", "Echec de l'opération ", "error");
                    console.log(response.message);

                } else {

                    showAlert("Alert", "Une erreur est survenue ", "error");

                }

            },
            error: function() {
                preloader('end');
                showAlert("Alert", "Une erreur est survenue ", "error");
            }
        });

    });

    $('.table_facture_client').on('click', '.btn-recu', function (event) {
        event.preventDefault();
        
        let code = $(this).data('code');
        let matricule = $(this).data('matricule');
        let versementData = $(this).data("versement");

        const div = $('#contenu_versement');
        div.empty();
        $.each(versementData, function(index, item) {

            let row = $(`
                <li>
                    <a href="#" data-id="${item.id}" class="btn btn-primary mb-2 btn_recu_vers">
                        <em class="icon ni ni-printer"></em>
                        <span>Recu ${index+1} : ${item.montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa, le ${formatDate(item.date)}</span>
                    </a>
                </li>
            `);
            div.append(row);
        })

        // Ajout d'un gestionnaire d'événements pour récupérer l'id du versement
        $('#contenu_versement').off('click', '.btn_recu_vers').on('click', '.btn_recu_vers', function (event) {
            event.preventDefault();

            let versementId = $(this).data('id');

            var modal = bootstrap.Modal.getInstance(document.getElementById('modalLarge'));
            modal.hide();

            // Ajouter le préchargeur
            let preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            $("body").append(preloader_ch);

            $.ajax({
                url: url_base + '/api/imp_fac_recu/'+code+'/'+matricule+'/'+versementId,
                method: 'GET',
                success: function(response) {
                    preloader('end');

                    if (response.success) {

                        PDF_Recu_Paiement(response.facture, response.recu);

                    } else if (response.error) {

                        showAlert("Alert", "Echec de l'opération ", "error");
                        console.log(response.message);

                    } else {

                        showAlert("Alert", "Une erreur est survenue ", "error");

                    }

                },
                error: function() {
                    preloader('end');
                    showAlert("Alert", "Une erreur est survenue ", "error");
                }
            });

        });

    });

    $('.table_facture_client').on('click', '.btn-plus', function (event) {
        event.preventDefault();

        const code = $(this).data('code');
        const matricule = $(this).data('matricule');
        const reste = $(this).data('reste');

        $('#input_code').val(code);
        $('#input_matricule').val(matricule);

        $('#montant_payer').val(reste.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        $('#montant_verser').val(0);
        $('#montant_restant').val(0);
        $('#obs').val(null);
        $('#date_livraison').val(null);

    });

    $("#client").on("change", list_facture_client); 

});