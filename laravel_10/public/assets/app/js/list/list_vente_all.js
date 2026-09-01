$(document).ready(function () {

    window.list_vente_all = function () {

        let date1 = $('#Date1').val();
        let date2 = $('#Date2').val();

        $.ajax({
            url: url_base + '/api/list_vente_all/'+date1+'/'+date2+'/'+user.magasin_id,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                preloader('end');

                const vente = data.data;

                // Détruire l'instance DataTable existante (si elle existe)
                const table = $('.table_vente');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                // Effacer le contenu du tableau
                table.find("tbody").empty();

                let row = '';

                if (vente.length > 0) {

                    // const table = $('.table_client');
                    // table.DataTable().destroy();

                    $.each(vente, function(index, item) {

                        row += `
                            <tr class="nk-tb-item" >
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${index + 1}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <div class="user-card gap-1">
                                        <div class="user-avatar bg-azure">
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
                                    <span class="tb-amount">${item.client}</span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount ${ item.regle == 1 ? `text-success` : `text-danger` }" >
                                        ${ item.regle == 1 ? `Oui` : `Non` }
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">
                                        ${item.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount}" >
                                        ${ item.partassurance == null ? 0 : item.partassurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') } Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">
                                        ${item.partclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">
                                        ${item.reste.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDateHeure(item.date)}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <ul class="nk-tb${tableBtnPosition == 1 ? `-actions` : ``} gx-1 d-flex">
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
                                        ${item.payer == 0 && item.partclient > 0 ?  
                                        `<li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-delete text-danger"
                                                title="Supprimer"
                                                data-code="${item.code}"
                                                data-matricule="${item.matricule}"
                                            >
                                                <em class="icon ni ni-trash"></em>
                                            </a>
                                        </li>` : ``}
                                    </ul>
                                </td>
                            </tr>
                        `;

                        // $(`#detail-${item.matricule}`).on('click', function() {
                        //     console.log(item.nomprenom);
                        //     $('#m_nom').text(item.nomprenom);
                        // });

                        // $(`#delete-${item.id}`).on('click', function() {
                        //     $('#litIddelete').val(item.id);
                        // });
                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_vente");
                } else {
                    initializeDataTable(".table_vente");
                }
            },
            error: function() {
                preloader('end');
                initializeDataTable(".table_vente");
            }
        });

        $('.table_vente').off('click', '.btn-pdf').on('click', '.btn-pdf', function (event) {
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

                        PDF_Facture_Vente(response.client, response.pres, response.produits,user_magasin,reste_payer = 1);

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

        $('.table_vente').off('click', '.btn-delete').on('click', '.btn-delete', function (event) {
            event.preventDefault();

            const code = $(this).data('code');
            const matricule = $(this).data('matricule');

            confirmAction().then((result) => {
                if (result.isConfirmed) {

                    let preloader_ch = `
                        <div id="preloader_ch">
                            <div class="spinner_preloader_ch"></div>
                        </div>
                    `;
                    $("body").append(preloader_ch);

                    $.ajax({
                        url: url_base + '/api/delete_fac_vente/' + code,
                        method: 'GET',
                        success: function(response) {
                            preloader('end');

                            if (response.success) {
                                list_vente_all();
                                Swal.fire("Succès!", "Opération éffectuée.", "success");
                            } else if (response.error) {
                                showAlert("Alert", "Échec de l'opération", "warning");
                            } else {
                                showAlert("Alert", "Une erreur est survenue", "error");
                            }
                        },
                        error: function() {
                            preloader('end');
                            showAlert("Erreur", "Une erreur est survenue", "error");
                        }
                    });
                }
            });
        });
        
    }

    list_vente_all();

    $("#btn_search").on('click', function (event) {
        event.preventDefault();

        // Ajouter le préchargeur
        let preloader_ch = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;
        $("body").append(preloader_ch);

        list_vente_all();

    });

});