$(document).ready(function () {

    window.list_assurance_all = function () {
        $.ajax({
            url: url_base + '/api/list_assurance_all',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const assurances = data.data;

                // Détruire l'instance DataTable existante (si elle existe)
                const table = $('.table_assurance');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                // Effacer le contenu du tableau
                table.find("tbody").empty();

                let row = '';

                if (assurances.length > 0) {

                    $.each(assurances, function(index, item) {

                        row += `
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${index + 1}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <div class="user-card gap-1">
                                        <div class="user-avatar bg-azure">
                                            <em class="ni ni-home" ></em>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="tb-amount">
                                                ${item.denomination}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount" >
                                        ${formatDateHeure(item.dateajout)}
                                    </span>
                                </td>
                            </tr>
                        `;

                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_assurance", { responsive: { details: true } });
                } else {
                    initializeDataTable(".table_assurance", { responsive: { details: true } });
                }
            },
            error: function() {
                initializeDataTable(".table_assurance", { responsive: { details: true } });
            }
        });        
    }

    list_assurance_all();

});