$(document).ready(function () {

    window.initializeDataTable = function (selector, additionalOptions = {}) {
        const tableSelector = $(selector);

        if (tableSelector.length) {
            tableSelector.each(function () {
                const responsiveOption = $(this).data("auto-responsive");
                const hasButtons = !(typeof additionalOptions.buttons === "undefined" || !additionalOptions.buttons);
                const exportTitle = $(this).data("export-title") || "Export";
                const exportButtonsHTML = hasButtons
                    ? '<"dt-export-buttons d-flex align-center"<"dt-export-title d-none d-md-inline-block">B>'
                    : "";
                const wrapperClass = hasButtons ? " with-export" : "";
                const defaultDOM = `
                    <"row justify-between g-2${wrapperClass}"<"col-7 col-sm-4 text-start"f><"col-5 col-sm-8 text-end"<"datatable-filter"<"d-flex justify-content-end g-2"${exportButtonsHTML}l>>>>
                    <"datatable-wrap my-3"t>
                    <"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-start text-md-end"i>>
                `;

                const defaultOptions = {
                    responsive: false,
                    autoWidth: false,
                    pagingType: "simple_numbers",
                    dom: $(this).hasClass("is-separate") ? defaultDOM : defaultDOM,
                    language: {
                        search: "",
                        searchPlaceholder: "Recherche",
                        lengthMenu: "<span class='d-none d-sm-inline-block'>Afficher</span><div class='form-control-select'> _MENU_ </div>",
                        info: "_START_ - _END_ sur _TOTAL_",
                        infoEmpty: "0",
                        infoFiltered: "(Total _MAX_)",
                        paginate: {
                            first: "Premier",
                            last: "Dernier",
                            next: "Suivant",
                            previous: "Précédent",
                        },
                        zeroRecords: "<div class='text-center no-data'>Aucune donnée trouvée</div>",
                        emptyTable: "<div class='text-center no-data'>Aucune donnée disponible dans le tableau</div>"
                    },
                    width: '100%',
                };

                const finalOptions = additionalOptions
                    ? $.extend(true, {}, defaultOptions, additionalOptions)
                    : defaultOptions;

                const optionsWithResponsive = responsiveOption === false
                    ? $.extend(true, {}, finalOptions, { responsive: false, autoWidth: true })
                    : finalOptions;

                $(this).DataTable(optionsWithResponsive);
                $(".dt-export-title").text(exportTitle);
            });
        }
    }

    // window.initializeDataTable = function (selector) {
    //     const tableSelector = $(selector);

    //     if (tableSelector.length) {
    //         tableSelector.each(function () {
    //             const $table = $(this);

    //             if ($.fn.DataTable.isDataTable($table)) {
    //                 $table.DataTable().clear().destroy();
    //             }

    //             $table.DataTable({
    //                 scrollX: true,                    
    //             });
    //         });
    //     }
    // }

});
