const languageConfig = {
    search: "Recherche:",
    lengthMenu: "Afficher _MENU_ entrées",
    info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
    infoEmpty: "Affichage de 0 à 0 sur 0 entrée",
    paginate: {
        first: "Première",
        last: "Dernière",
        next: "Suivant",
        previous: "Précédent",
    },
    zeroRecords: "Aucune donnée trouvée",
    emptyTable: "Aucune donnée disponible dans le tableau",
    loadingRecords: "Chargement des données...",
    processing: "Chargement des données..."
};

const dataTableConfig = {
    //autoWidth: true,
    //scrollX: true,
    autoWidth: false,
    paging: true,
    language: languageConfig,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'All']
    ],
};
