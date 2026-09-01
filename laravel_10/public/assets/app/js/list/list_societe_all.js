$(document).ready(function () {

    window.list_societe_all = function () {
        $.ajax({
            url: url_base + '/api/list_societe_all',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const societe = data.data;

                // Détruire l'instance DataTable existante (si elle existe)
                const table = $('.table_societe');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                // Effacer le contenu du tableau
                table.find("tbody").empty();

                let row = '';

                if (societe.length > 0) {

                    $.each(societe, function(index, item) {

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
                                                ${item.libelle}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;

                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_societe", { responsive: { details: true } });
                } else {
                    initializeDataTable(".table_societe", { responsive: { details: true } });
                }
            },
            error: function() {
                initializeDataTable(".table_societe", { responsive: { details: true } });
            }
        });        
    }

    list_societe_all();

});