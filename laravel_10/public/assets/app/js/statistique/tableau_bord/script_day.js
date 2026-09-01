$(document).ready(function () { 

    stat_day();
    graph_vente_proforma();
    his_op();
    graph_rapport_caisse();
    stat_table();

    $("#btn_refresh_stat_day").on("click", stat_day);
    $("#btn_refresh_stat_vente_proforma").on("click", graph_vente_proforma);
    $("#btn_refresh_stat_hisOp").on("click", his_op);
    $("#btn_refresh_stat_raport_vente").on("click", graph_rapport_caisse);
    $("#btn_refresh_stat_table").on("click", stat_table);

    function stat_day() {

        const contenuDiv = $('#div_day');

        contenuDiv.empty();

        const div0 = $(`
            <div class="col-12">
                <div class="pt-3 pb-3">
                    ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
                </div>
            </div>
        `);

        contenuDiv.append(div0);

        $.ajax({
            url: url_base + '/api/stat_day/' + user.magasin_id,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                contenuDiv.empty();

                const stats = [
                    { 
                        title: 'Proformas',
                        change: data.proforma.change,  
                        today: data.proforma.today, 
                        last_day: data.proforma.last_day,
                        color: 'white',
                        backgroun: 'background: linear-gradient(to right, #FF4500, #FFA500);',
                        lien: 'Facture Proforma',
                    },
                    { 
                        title: 'Ventes', 
                        today: data.vente.today,
                        change: data.vente.change, 
                        last_day: data.vente.last_day,
                        color: 'white',
                        backgroun: 'background: linear-gradient(to right, #FF4500, #FFA500);',
                        lien: 'Facture Vente',
                    },
                    { 
                        title: 'Nouveau Clients', 
                        today: data.client.today,
                        change: data.client.change,
                        last_day: data.client.last_day,
                        color: 'white',
                        backgroun: 'background: linear-gradient(to right, #FF4500, #FFA500);',
                        lien: 'Clients',
                    },
                    { 
                        title: 'Versements', 
                        today: data.versement.today,
                        change: data.versement.change,
                        last_day: data.versement.last_day,
                        color: 'white',
                        backgroun: 'background: linear-gradient(to right, #FF4500, #FFA500);',
                        lien: 'Opération de Caisse/?filtre=Versement',
                    },
                ];

                stats.forEach(function(stat) {
                    const percentageChange = stat.change;
                    const changeClass = percentageChange >= 0 ? 'text-teal' : 'text-danger'; // Color based on positive or negative change
                    const changeIcon = percentageChange > 0 ? 'ni-arrow-long-up' : (percentageChange < 0 ? 'ni-arrow-long-down' : ''); // Icon for positive or negative change
                    const changeIcon0 = percentageChange > 0 ? 'up' : (percentageChange < 0 ? 'down' : ''); // Class for positive or negative change
                    const changeBg = 'bg-secondary';

                    // Conditionally check if the percentage change is 0, and hide the icon if true
                    const percent = percentageChange !== 0 
                        ? `<em class="icon ni ${changeIcon} ${changeClass}"></em> <span class="${changeClass}">${percentageChange.toFixed(2)}%</span>` 
                        : `<span class="${changeClass}">${percentageChange.toFixed(2)}%</span>`; // If percentage is 0, just display the percentage without the icon

                    const div = $(`
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="rounded-5 card_hover" style="${stat.backgroun}" >
                                <div class="p-3">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title text-${stat.color}">${stat.title}</h6>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="data-group mt-1">
                                            <h4 class="text-${stat.color}" >${stat.today}</h4>
                                        </div>
                                        <div class="info bg-white rounded-3 mt-1 px-1">
                                            <span class="change ${changeIcon0} ${changeClass}">
                                                ${percent}
                                            </span>
                                            <span class="text" style="font-size:12px;" > par rapport à hier (${stat.last_day})</span>
                                        </div>
                                        <div class="info rounded-3 mt-1 p-0">
                                            <a href="${url_APP}${stat.lien}" class="d-flex align-items-center justify-content-start text-${stat.color}" style="font-size:15px;" >
                                                <span> Détails </span>
                                                <em class="icon ni ni-chevron-right-circle"></em>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                    contenuDiv.append(div);

                });

                const stats2 = [
                    { 
                        title: 'Solde Caisse',
                        data: data.caisse.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#4682B4',
                        icon: 'wallet-new-fill',
                        backgroun: 'background: linear-gradient(to right, #3498DB, #4682B4);',
                    },
                    { 
                        title: 'Total Ventes',
                        data: data.vente.montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#4682B4',
                        icon: 'coins',
                        backgroun: 'background: linear-gradient(to right, #3498DB, #4682B4);',
                    },
                    { 
                        title: 'Total Versements',
                        data: data.versement.montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#4682B4',
                        icon: 'coins',
                        backgroun: 'background: linear-gradient(to right, #3498DB, #4682B4);',
                    },
                    { 
                        title: 'Total Sorties',
                        data: data.caisse.sortie.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#4682B4',
                        icon: 'coins',
                        backgroun: 'background: linear-gradient(to right, #3498DB, #4682B4);',
                    },
                ];

                stats2.forEach(function(stat) {

                    const div = $(`
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card pricing text-center rounded-5 card_hover" style="${stat.backgroun}" >
                                <div class="p-2">
                                    <ul class="nk-store-statistics">
                                        <li class="item">
                                            <em style="color:${stat.bgcolor};" class="icon bg-white ni ni-${stat.icon}"></em>
                                            <div class="info">
                                                <div class="title text-white">${stat.title}</div>
                                                <div style="font-size:14px;" class="count text-white">${stat.data}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);

                    contenuDiv.append(div);

                });


            },
            error: function() {
                contenuDiv.empty();

                const div0 = $(`
                    <div class="pt-3 pb-3">
                        ${messageLoader(`Impossible de récupérer les données`, `danger`, 0)}
                    </div>
                `);

                contenuDiv.append(div0);
            }
        });
    }

    function stat_table()
    {
        const contenuDiv = $('#div_table');

        contenuDiv.empty();

        $('#div_table').removeClass('card');

        const div0 = $(`
            <div class="pt-3 pb-3">
                ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
            </div>
        `);

        contenuDiv.append(div0);

        $.ajax({
            url: url_base + '/api/stat_table/' + user.magasin_id,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                contenuDiv.empty();

                if(data.length <= 0) {

                    const div0 = $(`
                        <div class="pt-3 pb-3">
                            ${messageLoader(`Aucune données n'à été trouvées`, `danger`, 0)}
                        </div>
                    `);

                    contenuDiv.append(div0);

                    return;
                }

                $('#div_table').addClass('card');

                const div1 = $(`
                    <div class="nk-tb-list border border-0" id="div_table2" >
                        <div class="nk-tb-item nk-tb-head bg-azure">
                            <div class="nk-tb-col text-white"><span>Code vente</span></div>
                            <div class="nk-tb-col text-white tb-col-md"><span>Client</span></div>
                            <div class="nk-tb-col text-white tb-col-md"><span>Date</span></div>
                            <div class="nk-tb-col text-white"><span>Montant Total</span></div>
                            <div class="nk-tb-col text-white"><span>Statut</span></div>
                        </div>
                    </div>
                `);

                contenuDiv.append(div1);

                data.forEach(function(item) {

                    const div = $(`
                        <div class="nk-tb-item">
                            <div class="nk-tb-col">
                                <div class="user-card">
                                    <div class="user-avatar sm bg-azure">
                                        <em class="ni ni-file"></em>
                                    </div>
                                    <div class="user-name">
                                        <span class="tb-lead">${item.code}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-tb-col tb-col-md">
                                <span class="tb-sub">${item.client}</span>
                            </div>
                            <div class="nk-tb-col tb-col-md">
                                <span class="tb-sub">${formatDate(item.date)}</span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="tb-sub tb-amount">
                                    <span class="badge bg-azure text-white">
                                        ${item.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? '0 '} Fcfa
                                    </span>
                                </span>
                            </div>
                            <div class="nk-tb-col">
                                <span class="badge badge-dot badge-dot-xs bg-${item.regle == '1' ? 'success' : 'danger'}">
                                    ${item.regle == '1' ? 'Reglé' : 'Non-rglé'}
                                </span>
                            </div>
                        </div>
                    `);

                    $('#div_table2').append(div);
                });

                
            },
            error: function() {
                contenuDiv.empty();

                const div0 = $(`
                    <div class="pt-3 pb-3">
                        ${messageLoader(`Impossible de récupérer les données`, `danger`, 0)}
                    </div>
                `);

                contenuDiv.append(div0);
            }
        });
    }

    function his_op()
    {
        const contenuDiv = $('#div_his_op');

        contenuDiv.empty();

        const div0 = `
            <div class="pt-3 pb-3" >
                ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
            </div>
        `;

        contenuDiv.append(div0);

        const today = new Date();

        // Obtenir le numéro du jour dans la semaine (0 = dimanche, 1 = lundi, ..., 6 = samedi)
        const dayOfWeek = today.getDay();

        // Calculer la date du lundi (début de semaine)
        const monday = new Date(today);
        monday.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));

        // Calculer la date du dimanche (fin de semaine)
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);

        // Fonction pour formater en yyyy-mm-dd
        function formatDateSemaine(date) {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        }

        // Générer les dates
        let date1 = formatDateSemaine(monday);   // Lundi
        let date2 = formatDateSemaine(sunday);   // Dimanche

        $.ajax({
            url: url_base + '/api/list_operation_all/'+date1+'/'+date2+'/'+user.magasin_id+'/0',
            method: 'GET',
            success: function(response) {
                const operation = response.data;

                contenuDiv.empty();

                if (operation.length > 0) {

                    const div1 = `
                        <ul class="timeline-list" id="div_his_op_contenu"></ul>
                    `;

                    contenuDiv.append(div1);

                    $.each(operation, function(index, item) {

                        const row = `
                            <li class="timeline-item">
                                <div class="timeline-status bg-azure"></div>
                                <div class="timeline-date">
                                    ${getRelativeDateLabel(item.created_at)}
                                </div>
                                <div class="timeline-data">
                                    <h6 class="timeline-title">
                                        ${item.libelle}
                                    </h6>
                                    <div class="timeline-des d-flex flex-column gap-2">
                                        <span class="time d-flex gap-1 justify-content-start align-items-center">
                                            <span class="badge ${item.type === 'sortie' ? 'bg-danger' : (item.type === 'entree' ? 'bg-success' : (item.type_operation === '4' || item.type_operation === '5'   ? 'bg-warning' : 'bg-secondary'))}">
                                            ${(item.type === 'sortie' ? '-' : (item.type === 'entree' ? '+' : ''))} 
                                            ${item.montant ? item.montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'} Fcfa
                                        </span>
                                            <em class="icon ni ni-alarm-alt"></em>
                                            ${formatDateHeure(item.created_at)}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        `;

                        $("#div_his_op_contenu").append(row);
                    });

                } else {

                    const div0 = $(`
                        <div class="pt-3 pb-3">
                            ${messageLoader(`Aucune données n'à été trouvées`, `danger`, 0)}
                        </div>
                    `);

                    contenuDiv.append(div0);
                }

            },
            error: function() {
                contenuDiv.empty();

                const div0 = $(`
                    <div class="pt-3 pb-3">
                        ${messageLoader(`Impossible de récupérer les données`, `danger`, 0)}
                    </div>
                `);

                contenuDiv.append(div0);
            }
        });
    }

    function graph_vente_proforma() 
    {

        const contenuDiv = $("#graph_vente_proforma");

        contenuDiv.empty();

        $('#graph_vente_proforma_parent').addClass('d-flex');

        const div0 = `
            <div class="pt-3 pb-3" >
                ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
            </div>
        `;

        contenuDiv.append(div0);

        fetch( url_base + '/api/stat_vente_proforma/' + user.magasin_id)
            .then(response => response.json())
            .then(data => {
                
                contenuDiv.empty();

                const months = [
                    "Janv", "Fév", "Mar", "Avr", "Mai", "Jui", 
                    "Juil", "Août", "Sept", "Oct", "Nov", "Déc"
                ];

                // Initialisation des tableaux pour 12 mois
                const total_vente = new Array(12).fill(0);
                const nombre_vente = new Array(12).fill(0);
                const nombre_proforma = new Array(12).fill(0);

                const currentYearVente = new Array(12).fill(0);
                const currentYearNombreVente = new Array(12).fill(0);
                const currentYearNombreProforma = new Array(12).fill(0);

                let totalVente = 0;
                let nbreVente = 0;
                let nbreProfoma = 0;

                // Remplissage des données
                data.prevision.forEach(item => {
                    const monthIndex = item.month - 1;
                    total_vente[monthIndex] = item.total_vente;
                    nombre_vente[monthIndex] = item.nombre_vente;
                    nombre_proforma[monthIndex] = item.nombre_proforma;

                    totalVente += parseInt(item.total_vente);
                    nbreVente += parseInt(item.nombre_vente);
                    nbreProfoma += parseInt(item.nombre_proforma);
                });

                var options = {
                    chart: {
                        height: 350,
                        type: "line",
                        stacked: false,
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: {
                        width: [2, 0], // Ligne pour les ventes, colonne pour le nombre
                        curve: "smooth"
                    },
                    series: [
                        {
                            name: "Montant des Ventes (Fcfa)",
                            type: "line",
                            data: total_vente,
                            yaxisIndex: 0 // Associé à l'axe Y gauche (prix)
                        },
                        {
                            name: "Nombre de Ventes",
                            type: "column",
                            data: nombre_vente,
                            yaxisIndex: 1 // Associé à l'axe Y droit (nombre de ventes)
                        },
                        {
                            name: "Nombre de Proformas",
                            type: "column",
                            data: nombre_proforma,
                            yaxisIndex: 2 // Associé à l'axe Y droit (nombre de ventes)
                        }
                    ],
                    xaxis: {
                        categories: months,
                        labels: { 
                            style: { colors: "#000" }, // Texte en noir
                        }
                    },
                    yaxis: [
                        {
                            labels: {
                                formatter: function(val) {
                                    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                                },
                                style: { colors: "#006400" } // Vert pour les prix
                            }
                        },
                        {
                            opposite: true,
                            labels: {
                                style: { colors: "#00000" } // Bleu pour les ventes
                            }
                        }
                    ],
                    colors: ["#006400", "#FF4500", "#00008B"], // Vert pour le prix, Bleu pour les ventes
                    markers: {
                        size: 4,
                        colors: ["#006400", "#FF4500", "#00008B"],
                        strokeColor: "#ffffff",
                        strokeWidth: 2
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            }
                        }
                    }
                };

                const divcon0 = $(`
                    <div class="row g-gs mb-2 d-flex justify-content-center align-items-center" id="contenu_graph_vente_proforma_bilan" ></div>
                `);

                const divcon = $(`
                    <div class="" id="contenu_graph_vente_proforma" ></div>
                `);

                contenuDiv.append(divcon0);
                contenuDiv.append(divcon);

                $('#graph_vente_proforma_parent').removeClass('d-flex');

                var chart = new ApexCharts(document.querySelector("#contenu_graph_vente_proforma"), options);
                chart.render();

                const divBilian = $('#contenu_graph_vente_proforma_bilan');

                const stats = [
                    { 
                        title: 'Total Ventes', 
                        data: totalVente.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#006400',
                        icon: 'coins',
                        backgroun: 'background: #006400;', 
                    },
                    { 
                        title: 'Total Versements', 
                        data: data.totalVersement.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa",
                        color: 'text-white',
                        bgcolor: '#006400',
                        icon: 'coins',
                        backgroun: 'background: #006400;', 
                    },
                    { 
                        title: 'Nombre Ventes', 
                        data: nbreVente,
                        color: 'text-white',
                        bgcolor: '#006400',
                        icon: 'cart-fill',
                        backgroun: 'background: #006400;',
                    },
                    { 
                        title: 'Nombre Proformas', 
                        data: nbreProfoma,
                        color: 'text-white',
                        bgcolor: '#006400',
                        icon: 'form-validation-fill',
                        backgroun: 'background: #006400;', 
                    },
                ];

                stats.forEach(function(stat) {

                    const div = $(`
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card pricing text-center rounded-5 card_hover" style="${stat.backgroun}" >
                                <div class="p-2">
                                    <ul class="nk-store-statistics">
                                        <li class="item">
                                            <em style="color:${stat.bgcolor};" class="icon bg-white ni ni-${stat.icon}"></em>
                                            <div class="info">
                                                <div class="title text-white">${stat.title}</div>
                                                <div style="font-size:14px;" class="count text-white">${stat.data}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);

                    divBilian.append(div);

                });

            })
            .catch(error => {
                contenuDiv.empty();

                const div0 = $(`
                    <div class="pt-3 pb-3">
                        ${messageLoader(`Impossible de récupérer les données`, `danger`, 0)}
                    </div>
                `);

                contenuDiv.append(div0);
            });
    }

    function graph_rapport_caisse() 
    {

        const contenuDiv = $("#graph_rapport_caisse");

        contenuDiv.empty();

        const preloader = `
            ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
        `;

        contenuDiv.append(preloader);

        fetch( url_base + '/api/stat_rapport_caisse/' + user.magasin_id)
            .then(response => response.json())
            .then(data => {
                
                contenuDiv.empty();

                const totalEntrer = data.total_entrer;
                const totalSortie = data.total_sortie;

                var options = {
                    chart: {
                        type: 'donut',
                        height: 300
                    },
                    labels: ['Entrées (+)', 'Sorties (-)'],
                    series: [totalEntrer, totalSortie],
                    colors: ["#0ebb13", "#ff5a39"],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        color: '#333',
                                        offsetY: -10
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontWeight: 'bold',
                                        color: '#000',
                                        formatter: function (val) {
                                            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                                        },
                                        offsetY: 10
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        fontSize: '16px',
                                        fontWeight: 600,
                                        color: '#436ccf',
                                        formatter: function (w) {
                                            let sum = w.globals.seriesTotals.reduce((a, b) => a - b);
                                            return sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                                        }
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val, opts) {
                            // return opts.w.config.labels[opts.seriesIndex] + ": " + val.toFixed(1) + "%";
                            return val.toFixed(1) + "%";
                        }
                    },
                    legend: {
                        position: 'bottom',
                        fontSize: '14px',
                        labels: {
                            colors: '#444'
                        }
                    },
                    tooltip: {
                        custom: function({ series, seriesIndex, w }) {
                            const label = w.config.labels[seriesIndex];
                            const value = series[seriesIndex];
                            const formatted = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            const signe = label === 'Sorties' ? '- ' : '+ ';
                            return `<div style="padding: 5px;">
                                        <strong>${label}</strong>: ${signe}${formatted} Fcfa
                                    </div>`;
                        }
                    }
                };

                const divcon = $(`
                    <div class="" id="contenu_graph_rapport_caisse" ></div>
                `);

                contenuDiv.append(divcon);

                var chart = new ApexCharts(document.querySelector("#contenu_graph_rapport_caisse"), options);
                chart.render();

            })
            .catch(error => {
                contenuDiv.empty();

                const div0 = $(`
                    <div class="pt-3 pb-3">
                        ${messageLoader(`Impossible de récupérer les données`, `danger`, 0)}
                    </div>
                `);

                contenuDiv.append(div0);
            });
    }





    // #f78c24 => orange ciel;
    // #1676fb => bleu ciel;
    // #006400 => vert;
    // #00008B => bleu;
    // #FF4500 => orange;




    function getRelativeDateLabel(dateString) {
        const date = new Date(dateString);
        const today = new Date();

        // Réinitialiser à minuit pour comparer les jours uniquement
        const oneDay = 24 * 60 * 60 * 1000;
        const dateOnly = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        const diffDays = Math.round((todayOnly - dateOnly) / oneDay);

        if (diffDays === 0) {
            return "Aujourd'hui";
        } else if (diffDays === 1) {
            return "Hier";
        } else {
            return `Il y a ${diffDays} jours`;
        }
    }


});