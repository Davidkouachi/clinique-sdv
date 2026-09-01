$(document).ready(function () {

    select_assurance('#assurance');
    select_client_facturation('#client');

    $("#type").val("assurance").trigger("change");

    // Gestion du changement de sélection
    $("#type").on("change", function () {
        let selectedValue = $(this).val();

        if (selectedValue === "assurance") {
            $("#assurance").val(null).trigger("change");
            $("#div_assurance").show();
            $("#div_client").hide();
        } else if (selectedValue === "client") {
            $("#client").val(null).trigger("change");
            $("#div_assurance").hide();
            $("#div_client").show();
        }
    });

    $('#date1').on('change', function() {
        const date1 = $(this).val();
        
        if (date1) {
            $('#date2').val(date1);
            $('#date2').attr('min', date1);
        }
    });

    $('#date2').on('change', function() {
        const date2 = $(this).val();
        const date1 = $('#date1').val();

        if (date2 && date1 && new Date(date2) < new Date(date1)) {
            $(this).val(date1);
        }
    });

    $("#btn_search").on("click", list_facturation);

    function list_facturation () {

        $('#donnee').hide();

        let type = $('#type').val();
        let assurance = $('#assurance').val();
        let client = $('#client').val();
        let date1 = $('#date1').val();
        let date2 = $('#date2').val();

        if (type == 'assurance' && !assurance.trim()) {
            showAlert("Alert","Veuillez selectionner un assurance s'il vous plaît","warning");
            return false;
        }

        if (type == 'client' && !client.trim()) {
            showAlert("Alert","Veuillez selectionner un client s'il vous plaît","warning");
            return false;
        }

        if (!date1.trim() && !date2.trim()) {
            showAlert("Alert","Veuillez verifier les dates s'il vous plaît","warning");
            return false;
        }

        // Ajouter le préchargeur
        preloader('start');

        $.ajax({
            url: url_base + '/api/list_facturation',
            method: 'GET',
            data: {
                type: type,
                assurance: assurance || null,
                client: client || null,
                date1: date1,
                date2: date2,
            },
            dataType: 'json',
            success: function(data) {
                preloader('end');

                const facture = data.data;
                const total = data.total;

                const table = $('.table_facturation');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                table.find("tbody").empty();

                let row = '';

                if (facture.length > 0) {

                    $('#donnee').show();

                    $.each(facture, function(index, item) {

                        row += `
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${index + 1}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <div class="user-card gap-1">
                                        <div class="user-avatar bg-azure">
                                            <em class="ni ni-user" ></em>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="tb-amount">
                                                ${item.client}
                                            </span>
                                            <span>
                                                Id : ${item.matricule}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount">
                                        <span class="title">${item.assurance}</span>
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount">
                                        <span class="title">${item.societe}</span>
                                    </span>
                                </td>
                                <td class="nk-tb-col" >
                                    <span class="tb-amount">
                                        <span class="title">${item.matricule_assurance}</span>
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="badge text-success"> 
                                        ${item.partassurance ? item.partassurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDate(item.datefacture)}
                                    </span>
                                </td>
                                <td class="nk-tb-col">
                                    <ul class="nk-tb${tableBtnPosition == 1 ? `-actions` : ``} gx-1 d-flex">
                                        <li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-pdf text-warning"
                                                title="Facture"
                                                data-code_vente="${item.code_vente}"
                                                data-partassurance="${item.partassurance}"
                                                data-matricule_assurance="${item.matricule_assurance}"
                                                data-client="${item.client}"
                                                data-societe="${item.societe}"
                                                data-assurance="${item.assurance}"
                                                data-numfacture="${item.numfacture}"
                                                data-datefacture="${item.datefacture}"
                                            >
                                                <em class="icon ni ni-printer"></em>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        `;

                        $('#total').text('Montant Total : '+total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Fcfa');
                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_facturation", { responsive: { details: true } });
                } else {
                    showAlert("Alert","Aucune données n'été trouver","info");

                    $('#donnee').hide();

                    // initializeDataTable(".table_facturation", { responsive: { details: true } });

                    // $('#total').text('Montant Total : 0 Fcfa');

                    return false;

                }
            },
            error: function() {
                $('#donnee').hide();
                preloader('end');
                // initializeDataTable(".table_facturation", { responsive: { details: true } });

                // $('#total').text('Montant Total : 0 Fcfa');
            }
        });
        
    }

    $('.table_facturation').off('click', '.btn-pdf').on('click', '.btn-pdf', function () {

        const data = {
            code_vente: $(this).data('code_vente'),
            partassurance: $(this).data('partassurance'),
            matricule_assurance: $(this).data('matricule_assurance'),
            client: $(this).data('client'),
            societe: $(this).data('societe'),
            assurance: $(this).data('assurance'),
            numfacture: $(this).data('numfacture'),
            datefacture: $(this).data('datefacture')
        };

        PDF_Facturation(data);

    });

    // const data_excel= {
    //     numfacture: "2025-001",
    //     assurance: "NSIA",
    //     societe: "Entreprise X",
    //     lignes: [
    //         { datefacture: "2025-09-01", matricule_assurance: "M123", client: "Jean Dupont", partassurance: 12500 },
    //         { datefacture: "2025-09-05", matricule_assurance: "M456", client: "Marie Koné", partassurance: 7500 },
    //         { datefacture: "2025-09-10", matricule_assurance: "M789", client: "Koffi Yao", partassurance: 15600 }
    //     ]
    // };

    // Excel_Facturation(data_excel);

});