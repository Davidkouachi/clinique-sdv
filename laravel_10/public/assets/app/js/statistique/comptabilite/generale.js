$(document).ready(function () { 

    $('#solde').hide();
    $('#chargement').hide();

    // caisse();
    
    select_annee('#anne_op');
    select_annee('#anne_vente');
    select_annee('#anne_client');
    select_annee('#anne_eva_vente');
    select_magasin('#magasin_d_vente');
    select_magasin('#magasin_estimation_vente', function() {
        graph_vente_prevision();
    });

    stat_nbre();
    graph_op();
    graph_vente();
    graph_eva_vente();
    graph_client();

    $("#btn_refresh_stat_nbre").on("click", stat_nbre);
    $("#anne_op").on('change', graph_op);
    $("#anne_vente").on('change', graph_vente);
    $("#anne_eva_vente").on('change', graph_eva_vente);
    $("#anne_client").on('change', graph_client);
    $("#magasin_estimation_vente").on('change', graph_vente_prevision);
    $("#btn_search_vente_d").on('click', function (event) {
        
        event.preventDefault();

        vente_detail();

    });

    function stat_nbre()
    {
        const contenuDiv = $('#div_nbre');

        contenuDiv.empty();

        const div0 = $(`
            <div class="col">
                <div class="pt-3 pb-3">
                    ${messageLoader(`Veuillez patienter un instant ...`, `warning`, 1)}
                </div>
            </div>
        `);

        contenuDiv.append(div0);

        $.ajax({
            // url: $('#url').attr('content') + '/api/stat_nbre/' + $('#id_agence').val(),
            url: $('#url').attr('content') + '/api/stat_nbre',
            method: 'GET',
            success: function(response) {
                const data = response.data;

                const stats = [
                    {  
                        title: 'Solde Caisse', 
                        // count: (data.solde.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        count: (data.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'wallet-fill',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);',
                    },
                    { 
                        title: 'Total Clients', 
                        count: data.client,
                        icon: 'user-group-fill',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);', 
                    },
                    { 
                        title: 'Assurance Acceptées', 
                        count: data.assurance,
                        icon: 'building-fill',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);',
                    },
                    { 
                        title: 'Agences', 
                        count: data.agence,
                        icon: 'map-pin-fill',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);',
                    },
                    { 
                        title: 'Services', 
                        count: data.service,
                        icon: 'tree-structure-fill',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);',
                    },
                    { 
                        title: 'Utilisateurs', 
                        count: data.users,
                        icon: 'user',
                        color: '#FF4500',
                        backgroun: 'background: linear-gradient(to right, #FFA500, #FF4500);',
                    },
                ];

                contenuDiv.slick('unslick');
                contenuDiv.empty();

                stats.forEach(function(stat) {

                    const div = $(`
                        <div class="col" >
                            <div class="card pricing text-center rounded-5 " style="${stat.backgroun}" >
                                <div class="pricing-body">
                                    <ul class="nk-store-statistics">
                                        <li class="item">
                                            <em style="color:${stat.color};" class="icon bg-white ni ni-${stat.icon}"></em>
                                            <div class="info">
                                                <div class="title text-white">${stat.title}</div>
                                                <div style="font-size:14px;" class="count text-white">${stat.count}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);

                    contenuDiv.append(div);
                });

                // 3. Réinitialiser le slider avec les mêmes options
                contenuDiv.slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,
                    arrows: true,
                    accessibility: true,
                    responsive: [
                        { breakpoint: 1500, settings: { slidesToShow: 3} },
                        { breakpoint: 892, settings: { slidesToShow: 2} },
                        { breakpoint: 582, settings:{ slidesToShow: 1} },
                    ]
                });

                // Initial styling
                styliserSlickArrows();

                // Réappliquer à chaque redimensionnement ou changement
                $('#div_nbre').on('setPosition afterChange reInit', function () {
                    styliserSlickArrows();
                });
                
                function styliserSlickArrows() {
                    $('.slick-prev, .slick-next').css({
                        display: 'flex',
                        visibility: 'visible',
                        opacity: 1,
                        zIndex: 1000,
                        position: 'absolute',
                        top: '50%',
                        transform: 'translateY(-50%)',
                        backgroundColor: 'white',
                        borderRadius: '50%',
                        width: '40px',
                        height: '40px',
                        fontSize: '18px',
                        color: '#00008B',
                        border: '1px solid #ccc',
                        boxShadow: '0 2px 4px rgba(0,0,0,0.2)',
                        alignItems: 'center',
                        justifyContent: 'center',
                        cursor: 'pointer'
                    });

                    $('.slick-prev').css({ left: '-5px' }).html('<em class="ni ni-chevron-left"></em>');
                    $('.slick-next').css({ right: '-5px' }).html('<em class="ni ni-chevron-right"></em>');
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

    function graph_op() 
    {
        $('#div_graph_op_message').show();
        const yearSelect = $("#anne_op").val();

        const contenug = $("#contenu_graph_op");
        contenug.empty();

        const divcon = $(`
            <div class="card-body" id="graph_op" ></div>
            <div class="card-body " id="graphT_op" ></div>
        `);

        contenug.append(divcon);

        fetch( $('#url').attr('content') +'/api/G_bilan_op/' + yearSelect)
            .then(response => response.json())
            .then(data => {

                $('#div_graph_op_message').hide();

                const monthlyStats = data.monthlyStats;
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                const entrer = generateMonthlyData(monthlyStats.entrer, months);
                const sortie = generateMonthlyData(monthlyStats.sortie, months);
                const total = generateMonthlyData(monthlyStats.total, months);

                var options = {
                    chart: {
                        height: 300,
                        type: "line",
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                        width: 3,
                    },
                    series: [{
                            name: "Entrées",
                            data: entrer,
                        },
                        {
                            name: "Sorties",
                            data: sortie,
                        },
                        {
                            name: "Total",
                            data: total,
                        }, 
                    ],
                    grid: {
                        borderColor: "#d8dee6",
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 10,
                            left: 0,
                        },
                    },
                    markers: {
                        size: 1
                    },
                    xaxis: {
                        categories: [
                            "Janv",
                            "Fév",
                            "Mar",
                            "Avr",
                            "Mai",
                            "Jui",
                            "Juil",
                            "Aôut",
                            "Sept",
                            "Oct",
                            "Nov",
                            "Déc",
                        ],
                    },
                    yaxis: {
                        labels: {
                            show: true,
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                            },
                            offsetX: -10,
                        },
                    },
                    colors: ["#0ebb13", "#ff5a39", "#436ccf"],
                    markers: {
                        size: 0,
                        opacity: 0.5,
                        colors: ["#0ebb13", "#ff5a39", "#436ccf"],
                        strokeColor: "#ffffff",
                        strokeWidth: 1,
                        hover: {
                            size: 7,
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+" Fcfa";
                            },
                        },
                    },
                };
                var chart = new ApexCharts(document.querySelector("#graph_op"), options);
                chart.render();

                const stat = $(`
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div style="background-color: #0ebb13;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <em class="ni ni-money text-white fs-4"></em>
                            <span class="me-1 text-white ps-1">+ ${data.total_entrer.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa</span>
                            <span class="fw-semibold">Entrées</span>
                        </div>
                        <div style="background-color: #ff5a39;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <em class="ni ni-money text-white fs-4"></em>
                            <span class="me-1 text-white ps-1">- ${data.total_sortie.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa</span>
                            <span class="fw-semibold">Sorties</span>
                        </div>
                        <div style="background-color: #436ccf;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <em class="ni ni-money text-white fs-4"></em>
                            <span class="me-1 text-white ps-1">${data.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa</span>
                            <span class="fw-semibold">Total</span>
                        </div>
                    </div>
                `);

                $('#graphT_op').append(stat);

            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);

                

            });
    }

    function graph_eva_vente() 
    {
        $('#div_graph_eva_vente_message').show();
        const yearSelect = $("#anne_eva_vente").val();

        const contenug = $("#contenu_graph_eva_vente");
        contenug.empty();

        const divcon = $(`
            <div class="card-body" id="graph_eva_vente" ></div>
            <div class="card-body" id="graphT_eva_vente" ></div>
        `);

        contenug.append(divcon);

        fetch( $('#url').attr('content') +'/api/G_bilan_eva_vente/' + yearSelect)
            .then(response => response.json())
            .then(data => {

                $('#div_graph_eva_vente_message').hide();

                const monthlyStats = data.monthlyStats;
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                const client = generateMonthlyData(monthlyStats.client, months);
                const assurance = generateMonthlyData(monthlyStats.assurance, months);
                const total = generateMonthlyData(monthlyStats.total, months);

                var options = {
                    chart: {
                        height: 300,
                        type: "area",
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                        width: 3,
                    },
                    series: [
                        // {
                        //     name: "Client",
                        //     data: client,
                        // },
                        // {
                        //     name: "Part Assurance",
                        //     data: assurance,
                        // },
                        {
                            name: "Total",
                            data: total,
                        }, 
                    ],
                    grid: {
                        borderColor: "#d8dee6",
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 10,
                            left: 0,
                        },
                    },
                    markers: {
                        size: 1
                    },
                    xaxis: {
                        categories: [
                            "Janv",
                            "Fév",
                            "Mar",
                            "Avr",
                            "Mai",
                            "Jui",
                            "Juil",
                            "Aôut",
                            "Sept",
                            "Oct",
                            "Nov",
                            "Déc",
                        ],
                        labels: {
                            style: {
                                colors: "#fff"
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: true,
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                            },
                            offsetX: -10,
                            style: {
                                colors: "#fff"
                            }
                        },
                    },
                    colors: ["#fff", "#0ebb13", "#3498db"],
                    markers: {
                        size: 0,
                        opacity: 0.5,
                        colors: ["#fff", "#0ebb13", "#3498db"],
                        strokeColor: "#ffffff",
                        strokeWidth: 1,
                        hover: {
                            size: 7,
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+" Fcfa";
                            },
                        },
                    },
                };
                var chart = new ApexCharts(document.querySelector("#graph_eva_vente"), options);
                chart.render();

                const stat = $(`
                    <div class="d-flex justify-content-center align-items-center">
                        <div style="background: transparent;" class="text-white">
                            <span class="fw-semibold text-white ps-1 h5">
                                Montant Total : ${data.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                            </span>
                        </div>
                    </div>
                `);

                // <div style="background-color: #ffa500;" class="d-flex justify-content-center align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                //             <em class="ni ni-money text-white fs-4"></em>
                //             <span class="me-1 text-white ps-1">
                //                 ${data.total_assurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                //             </span>
                //             <span class="fw-semibold">Part Assurance</span>
                //         </div>
                //         <div style="background-color: #3498db;" class="d-flex justify-content-center align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                //             <em class="ni ni-money text-white fs-4"></em>
                //             <span class="me-1 text-white ps-1">
                //                 ${data.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} Fcfa
                //             </span>
                //             <span class="fw-semibold">Montant Total</span>
                //         </div>

                $('#graphT_eva_vente').append(stat);

            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);
            });
    }

    function graph_vente() 
    {
        $('#div_vente_message').show();
        const yearSelect = $("#anne_vente").val();

        const contenug = $("#contenu_graph_vente");
        contenug.empty();

        const divcon = $(`
            <div class="col-xxl-6 col-sm-6" >
                <div class="card-body " id="graph_vente_montant" ></div>    
            </div>
            <div class="col-xxl-6 col-sm-6" >
                <div class="card-body " id="graph_vente_nombre" ></div>    
            </div>
        `);

        contenug.append(divcon);

        fetch( $('#url').attr('content') +'/api/G_bilan_vente/' + yearSelect)
            .then(response => response.json())
            .then(data => {

                $('#div_vente_message').hide();

                // Extraire les noms des magasins
                let categories = data.map(item => item.nom);

                // Extraire les montants des ventes
                let totalVentes = data.map(item => parseFloat(item.total_ventes));

                // Extraire le nombre de ventes
                let nombreVentes = data.map(item => parseInt(item.nombre_ventes));

                // Configuration du graphique en camembert pour le montant total des ventes

                function generateRandomColors(count) {
                    let colors = new Set(); // Utilisation d'un Set pour éviter les doublons

                    while (colors.size < count) {
                        let color = "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
                        colors.add(color); // Ajoute uniquement si elle n'existe pas déjà
                    }

                    return Array.from(colors); // Convertit le Set en tableau
                }

                let colors1 = generateRandomColors(categories.length);

                var optionsMontant = {
                    chart: {
                        type: "donut", // Changer le type en donut
                        height: 250,
                    },
                    labels: categories, // Noms des magasins
                    series: totalVentes, // Montant total des ventes
                    colors: colors1, // Couleurs personnalisées
                    legend: {
                        position: "bottom",
                    },
                    title: {
                        text: "Répartition du montant des ventes par magasin (en Fcfa)",
                        align: "center",
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + " Fcfa";
                            },
                        },
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '60%' // Ajuste la taille du trou au centre du donut
                            }
                        }
                    }
                };


                var chartMontant = new ApexCharts(document.querySelector("#graph_vente_montant"), optionsMontant);
                chartMontant.render();

                function generateRandomColors2(count) {
                    let colors = new Set(); // Utilisation d'un Set pour éviter les doublons

                    while (colors.size < count) {
                        let color = "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
                        colors.add(color); // Ajoute uniquement si elle n'existe pas déjà
                    }

                    return Array.from(colors); // Convertit le Set en tableau
                }

                let colors2 = generateRandomColors2(categories.length);

                // Configuration du graphique en camembert pour le nombre total de ventes
                var optionsNombre = {
                    chart: {
                        type: "pie",
                        height: 250,
                    },
                    labels: categories, // Noms des magasins
                    series: nombreVentes, // Nombre total de ventes
                    colors: colors2, // Couleurs personnalisées
                    legend: {
                        position: "bottom",
                    },
                    title: {
                        text: "Répartition du total des ventes par magasin",
                        align: "center",
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " ventes";
                            },
                        },
                    },
                };

                var chartNombre = new ApexCharts(document.querySelector("#graph_vente_nombre"), optionsNombre);
                chartNombre.render();

            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);
            });
    }    

    function graph_vente_prevision() 
    {
        $('#div_vente_prevision_message1').show();
        $('#div_vente_prevision_message2').show();

        const contenug1 = $("#contenu_graph_vente_prevision1");
        const contenug2 = $("#contenu_graph_vente_prevision2");
        contenug1.empty();
        contenug2.empty();

        fetch( $('#url').attr('content') +'/api/G_stat_prevision/' + $("#magasin_estimation_vente").val())
            .then(response => response.json())
            .then(data => {
                $('#div_vente_prevision_message1').hide();
                $('#div_vente_prevision_message2').hide();

                const months = [
                    "Janv", "Fév", "Mar", "Avr", "Mai", "Jui", 
                    "Juil", "Août", "Sept", "Oct", "Nov", "Déc"
                ];

                // Initialisation des tableaux pour 12 mois
                const smoothedVente = new Array(12).fill(0);
                const smoothedNombreVentes = new Array(12).fill(0);

                const currentYearVente = new Array(12).fill(0);
                const currentYearNombreVentes = new Array(12).fill(0);

                // Remplissage des données
                data.prevision.forEach(item => {
                    const monthIndex = item.month - 1;
                    smoothedVente[monthIndex] = item.smoothed_vente;
                    smoothedNombreVentes[monthIndex] = item.smoothed_nombre_ventes;
                    currentYearVente[monthIndex] = item.current_year_total;
                    currentYearNombreVentes[monthIndex] = item.current_year_sales;
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
                            name: "Montant Total (Fcfa)",
                            type: "line",
                            data: smoothedVente,
                            yaxisIndex: 0 // Associé à l'axe Y gauche (prix)
                        },
                        {
                            name: "Nombre de Ventes",
                            type: "column",
                            data: smoothedNombreVentes,
                            yaxisIndex: 1 // Associé à l'axe Y droit (nombre de ventes)
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
                                style: { colors: "#0ebb13" } // Vert pour les prix
                            }
                        },
                        {
                            opposite: true,
                            labels: {
                                style: { colors: "#3498db" } // Bleu pour les ventes
                            }
                        }
                    ],
                    colors: ["#0ebb13", "#3498db"], // Vert pour le prix, Bleu pour les ventes
                    markers: {
                        size: 4,
                        colors: ["#0ebb13", "#3498db"],
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

                var options2 = {
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
                            name: "Montant Total (Fcfa)",
                            type: "line",
                            data: currentYearVente,
                            yaxisIndex: 0 // Associé à l'axe Y gauche (prix)
                        },
                        {
                            name: "Nombre de Ventes",
                            type: "column",
                            data: currentYearNombreVentes,
                            yaxisIndex: 1 // Associé à l'axe Y droit (nombre de ventes)
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
                                style: { colors: "#3498db" } // Vert pour les prix
                            }
                        },
                        {
                            opposite: true,
                            labels: {
                                style: { colors: "#0ebb13" } // Bleu pour les ventes
                            }
                        }
                    ],
                    colors: ["#3498db", "#0ebb13"], // Vert pour le prix, Bleu pour les ventes
                    markers: {
                        size: 4,
                        colors: ["#3498db", "#0ebb13"],
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

                const divcon1 = $(`
                    <div class="" id="contenu_graph_vente_prevision1_G" ></div>
                `);
                const divcon2 = $(`
                    <div class="" id="contenu_graph_vente_prevision2_G" ></div>
                `);

                contenug1.append(divcon1);
                contenug2.append(divcon2);

                var chart = new ApexCharts(document.querySelector("#contenu_graph_vente_prevision1_G"), options);
                chart.render();

                var chart2 = new ApexCharts(document.querySelector("#contenu_graph_vente_prevision2_G"), options2);
                chart2.render();

            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);
            });
    }

    function graph_client() 
    {
        $('#div_client_message').show();
        const yearSelect = $("#anne_client").val();

        const contenug = $("#contenu_graph_client");
        contenug.empty();

        const divcon = $(`
            <div class="card-body" id="graph_client" ></div>
            <div class="card-body" id="graphT_client" ></div>
        `);

        contenug.append(divcon);

        fetch( $('#url').attr('content') +'/api/G_bilan_client/' + yearSelect)
            .then(response => response.json())
            .then(data => {

                $('#div_client_message').hide();

                const monthlyStats = data.monthlyStats;
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',];

                const total = months.map(month => monthlyStats.new[month] || 0);

                var options = {
                    chart: {
                        height: 300,
                        type: "area",
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                        width: 3,
                    },
                    series: [
                        {
                            name: "Total",
                            data: total,
                        }, 
                    ],
                    grid: {
                        borderColor: "#d8dee6",
                        strokeDashArray: 5,
                        xaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 10,
                            left: 0,
                        },
                    },
                    markers: {
                        size: 1
                    },
                    xaxis: {
                        categories: [
                            "Janv",
                            "Fév",
                            "Mar",
                            "Avr",
                            "Mai",
                            "Jui",
                            "Juil",
                            "Aôut",
                            "Sept",
                            "Oct",
                            "Nov",
                            "Déc",
                        ],
                        labels: {
                            style: {
                                colors: "#fff"
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            show: true,
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format y-axis labels
                            },
                            offsetX: -10,
                            style: {
                                colors: "#fff"
                            }
                        },
                    },
                    colors: ["#fff"],
                    markers: {
                        size: 0,
                        opacity: 0.5,
                        colors: ["#fff"],
                        strokeColor: "#ffffff",
                        strokeWidth: 1,
                        hover: {
                            size: 7,
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+" patient(s)";
                            },
                        },
                    },
                };
                var chart = new ApexCharts(document.querySelector("#graph_client"), options);
                chart.render();

                const stat = $(`
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div style="background: transparent;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <span class="me-1 text-white ps-1">
                                Nouveau Patient(s) : + ${data.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}
                            </span>
                        </div>
                        <div style="background: transparent;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <span class="me-1 text-white ps-1">
                                Homme(s) : ${data.homme.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}
                            </span>
                        </div>
                        <div style="background: transparent;" class="d-flex align-items-center box-shadow px-3 py-1 rounded-2 me-2 mb-2 text-white">
                            <span class="me-1 text-white ps-1">
                                Femme(s) : ${data.femme.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}
                            </span>
                        </div>
                    </div>
                `);

                $("#graphT_client").append(stat);

            })
            .catch(error => {
                console.error('Erreur lors du chargement des données:', error);

            });
    }

    function vente_detail()
    {
        $('#div_d_vente').empty();

        let periode = $('#periode').val();
        let magasin = $('#magasin_d_vente').val();

        if (!periode.trim()) {
            showAlert("Alert", "Veuillez saisir la période s'il vous plaît", "info");
            return false;
        }

        // Ajouter le préchargeur
        preloader('start');

        $.ajax({
            url: $('#url').attr('content') + '/api/G_bilan_detail_vente/'+periode+'/'+magasin,
            method: 'GET',
            success: function(response) {
                preloader('end');

                const data = response.data;

                const contenuDiv = $('#div_d_vente');

                const stats = [
                    {  
                        title: 'Total Proforma', 
                        count: (data.nbre_proforma.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'archive',
                        color: 'warning', 
                    },
                    {  
                        title: 'Total Proforma Validé', 
                        count: (data.nbre_proforma_valide.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'check-circle',
                        color: 'success', 
                    },
                    {  
                        title: 'Total Proforma non-validés', 
                        count: (data.nbre_proforma_nvalide.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'cross-circle',
                        color: 'danger', 
                    },
                    {  
                        title: 'Total Ventes', 
                        count: (data.nbre_vente.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'clipboard',
                        color: 'warning', 
                    },
                    { 
                        title: 'Total Ventes non-soldées', 
                        count: (data.nbre_vente_nsolde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'cross-circle',
                        color: 'danger', 
                    },
                    { 
                        title: 'Total Ventes soldées', 
                        count: (data.nbre_vente_solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ),
                        icon: 'check-circle',
                        color: 'success', 
                    },
                    { 
                        title: 'Montant Total', 
                        count: (data.total_tvente.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'money',
                        color: 'primary', 
                    },
                    // { 
                    //     title: 'Montant Soldées', 
                    //     count: (data.total_svente.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                    //     icon: 'money',
                    //     color: 'success', 
                    // },
                    // { 
                    //     title: 'Montant non-soldées', 
                    //     count: (data.total_nvente.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                    //     icon: 'money',
                    //     color: 'danger', 
                    // },
                    { 
                        title: 'Montant Part Assurance', 
                        count: (data.total_partassurance.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'money',
                        color: 'warning', 
                    },
                    { 
                        title: 'Montant Part Client', 
                        count: (data.total_partclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'money',
                        color: 'warning', 
                    },
                    { 
                        title: 'Part Client Soldées',
                        count: (data.total_spartclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'money',
                        color: 'success', 
                    },
                    { 
                        title: 'Part Client non-soldées', 
                        count: (data.total_npartclient.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') ?? 0 ) + ' Fcfa',
                        icon: 'money',
                        color: 'danger', 
                    },
                ];

                stats.forEach(function(stat) {

                    const div = $(`
                        <div class="col-xxl-3 col-lg-4 col-sm-6" >
                            <div class="card pricing text-center card_hover">
                                <div class="pricing-body">
                                    <ul class="nk-store-statistics">
                                        <li class="item">
                                            <em class="icon bg-${stat.color}-dim ni ni-${stat.icon}"></em>
                                            <div class="info">
                                                <div class="title">${stat.title}</div>
                                                <div class="count text-${stat.color}">${stat.count}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);

                    contenuDiv.append(div);
                });


                const div2 = $(`
                    <div class="col-12">
                        <div class="card" style="background: linear-gradient(to right, #FFA500, #FF4500);">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h6 class="title text-white">Informations supplémentaires</h6>
                                        <p class="text-white" >Par rapport aux ventes effectuées dans la période défini</p>
                                    </div>
                                </div>
                                <div class="align-end gy-3 gx-5 align-items-center justify-content-center">
                                    <div class="nk-sale-data-group flex-wrap g-5" id="info_detail_vente"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
                contenuDiv.append(div2);

                const stats2 = [
                    {  
                        title: 'Vente reglées', 
                        donne: (data.vente_solde_pourcent ?? 0 ) + '%', 
                    },
                    {  
                        title: 'Vente non-reglées', 
                        donne: (data.vente_nsolde_pourcent ?? 0 ) + '%',  
                    },
                    {  
                        title: 'Vente avec assurance', 
                        donne: (data.vente_ass_pourcent ?? 0 ) + '%', 
                    },
                    {  
                        title: 'Vente sans assurance', 
                        donne: (data.vente_nass_pourcent ?? 0 ) + '%',  
                    },
                    {  
                        title: 'Nombre de Versement', 
                        donne: (data.vente_nbre_vers ?? 0 ),  
                    },
                ];

                const contenuDetail = $('#info_detail_vente');
                contenuDetail.empty();

                stats2.forEach(function(stat2) {

                    const div2 = $(`
                        <div class="nk-sale-data text-center">
                            <span class="amount text-white">${stat2.donne}</span>
                            <span class="title h6 text-white">${stat2.title}</span>
                        </div>
                    `);

                    contenuDetail.append(div2);
                });
            },
            error: function() {
                preloader('end');
                // showAlert('danger', 'Impossible de generer le code automatiquement');
            }
        });
    }



















    function generateMonthlyData(stats, defaultMonths) 
    {
        return defaultMonths.map(month => stats[month] || 0);
    }

});