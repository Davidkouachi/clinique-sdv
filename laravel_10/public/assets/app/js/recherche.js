$(document).ready(function () {

    select_op_magasin('#rech_magasin');

    $("#btn_rech").on("click", list_rech_fac);

    $('#rech_date1').on('change', function() {
        const date1 = $(this).val();
        
        if (date1) {
            $('#rech_date2').val(date1);
            $('#rech_date2').attr('min', date1);
        }
    });

    $('#rech_date2').on('change', function() {
        const date2 = $(this).val();
        const date1 = $('#rech_date1').val();

        if (date2 && date1 && new Date(date2) < new Date(date1)) {
            $(this).val(date1);
        }
    });

    function list_rech_fac () {

        $('#donnee').hide();

        let type = $('#rech_type').val();
        let magasin = $('#rech_magasin').val();
        let date1 = $('#rech_date1').val();
        let date2 = $('#rech_date2').val();

        if (!date1.trim() && !date2.trim() ) {
            showAlert("Alert","Veuillez verifier les dates s'il vous plaît","warning");
            return false;
        }

        // Ajouter le préchargeur
        preloader('start');
        $.ajax({
            url: $('#url').attr('content') + '/api/list_rech_fac',
            method: 'GET',
            data: {
                type: type,
                magasin: magasin,
                date1: date1,
                date2: date2,
            },
            dataType: 'json',
            success: function(data) {
                preloader('end');

                const facture = data.data;
                const donne = data.donne;

                const table = $('.table_rech_fac');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().clear().destroy();
                }

                table.find("tbody").empty();

                if (facture.length > 0) {

                    $('#donnee').show();

                    $.each(facture, function(index, item) {

                        const row = $(`
                            <tr class="nk-tb-item">
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
                                <td class="nk-tb-col" >
                                    <span class="tb-amount">
                                        <span class="title">${item.client}</span>
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge bg-info"> 
                                        ${item.total ? item.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge bg-warning"> 
                                        ${item.partassurance ? item.partassurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge bg-primary"> 
                                        ${item.partclient ? item.partclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge bg-success"> 
                                        ${item.payer ? item.payer.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge bg-danger"> 
                                        ${item.reste ? item.reste.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDate(item.date)}
                                    </span>
                                </td>
                            </tr>
                        `);

                        table.find("tbody").append(row);

                        $('#total').text('Total Générale : '+donne['total'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#payer').text('Montant Client Payer : '+donne['payer'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#non_payer').text('Montant Client Non-payer : '+donne['non_payer'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#part_assurance').text('Part Assurance : '+donne['part_assurance'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                        $('#part_client').text('Part Client : '+donne['part_client'].toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                    });

                    initializeDataTable(".table_rech_fac", { responsive: { details: true } });
                } else {
                    $('#donnee').hide();
                    showAlert("Alert","Aucune facture n'à été trouvée","info");
                    // initializeDataTable(".table_rech_fac", { responsive: { details: true } });

                    // $('#total').text('Total Générale : 0 Fcfa');
                    // $('#payer').text('Montant Client Payer : 0 Fcfa');
                    // $('#non_payer').text('Montant Client Non-payer : 0 Fcfa');
                    // $('#part_assurance').text('Part Assurance : 0 Fcfa');
                    // $('#part_client').text('Part Client : 0 Fcfa');

                    return false;
                }
            },
            error: function() {
                $('#donnee').hide();
                preloader('end');
                // initializeDataTable(".table_rech_fac", { responsive: { details: true } });

                // $('#total').text('Total Générale : 0 Fcfa');
                // $('#payer').text('Montant Client Payer : 0 Fcfa');
                // $('#non_payer').text('Montant Client Non-payer : 0 Fcfa');
                // $('#part_assurance').text('Part Assurance : 0 Fcfa');
                // $('#part_client').text('Part Client : 0 Fcfa');
            }
        });
        
    }

});