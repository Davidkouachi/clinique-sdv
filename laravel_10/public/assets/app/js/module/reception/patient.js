$(document).ready(function() {

    select_patient('#patient_id');
    select_assurance('#patient_codeassurance_new');
    select_assurance('#patient_codeassurance_Modif');
    select_taux('#patient_idtauxcouv_new');
    select_taux('#patient_idtauxcouv_Modif');
    select_societe('#patient_codesocieteassure_new');
    select_societe('#patient_codesocieteassure_Modif');
    select_filiation('#patient_codefiliation_new');
    select_filiation('#patient_codefiliation_Modif');

    OffClick('#btn_eng_patient', eng_patient);
    OffClick('#btn_eng_modif', eng_patient_modif);
    OffClick('#deleteBtnCons', delete_pat);

    OffClick('#btn_affiche_stat', function () {
        $('#div_btn_affiche_stat').hide();
        $('#div_btn_cache_stat').show();
        Statistique();
    });

    OffClick('#btn_cache_stat', function () {
        $('#div_btn_affiche_stat').show();
        $('#div_btn_cache_stat').hide();
        $('#stat').empty();
    });

    OffClick('#btn_refresh_table', function () {
        $('#Table_day').DataTable().ajax.reload();
    });

    OffClick('#btn_search_table', function () {
        $('#Table_day').DataTable().ajax.reload();
    });

    OffChange('#patient_id', function () {
        const id = $(this).val();
        rech_dosier(id);
    });

    OffChange('#assure', function () {
        if ($(this).val() === '1') {
            $('#div_assurer').css('display', 'flex');
        } else {
            $('#div_assurer').css('display', 'none');
        }
    });

    OffChange('#assure_Modif', function () {
        if ($(this).val() === '1') {
            $('#div_assurer_Modif').css('display', 'flex');
        } else {
            $('#div_assurer_Modif').css('display', 'none');
        }
    });

    var numberInput = [
    	'#patient_tel_new', 
    	'#patient_tel2_new', 
    	'#patient_telu_new', 
    	'#patient_telu2_new',
    	'#patient_tel_Modif', 
    	'#patient_tel2_Modif', 
    	'#patient_telu_Modif', 
    	'#patient_telu2_Modif'
    ]; 

    numberInput.forEach(function (id) {
        numberTel(id);
        numberTelLimit(id);
    });


	function addGroup(data) {
	    const $dynamicFields = $("#div_info_patient");
	    $dynamicFields.empty();

	    let $groupe = $(`
	        <div class="row gx-3">
	            <div class="col-12">
	                <div class="card-header">
	                    <h5 class="card-title">Information du patient</h5>
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="email">Nom et Prénoms</label>
	                    <input value="${data.np}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="tel">Contact</label>
	                    <input value="+225 ${data.tel}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Adresse</label>
	                    <input value="${data.adresse}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label">Assurer</label>
	                    <input value="${data.assurer}" readonly class="form-control">
	                </div>
	            </div>
	        </div>
	    `);

	    if (data.assurer === 'oui') {
	        $groupe.append(`
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Assurance</label>
	                    <input value="${data.assurance}" readonly class="form-control" placeholder="Néant">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label">Taux</label>
	                    <div class="input-group">      
	                        <input id="patient_taux" value="${data.taux}" readonly class="form-control" placeholder="Néant">
	                        <span class="input-group-text">%</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Société</label>
	                    <input value="${data.societe}" readonly class="form-control" placeholder="Néant">
	                </div>
	            </div>
	        `);
	    } else {
	        $groupe.append(`
	            <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Assurance</label>
	                    <input value="Aucun" readonly class="form-control" placeholder="Néant">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Taux</label>
	                    <div class="input-group">      
	                        <input id="patient_taux" value="0" readonly class="form-control" placeholder="Néant">
	                        <span class="input-group-text">%</span>
	                    </div>
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6" hidden>
	                <div class="mb-3">
	                    <label class="form-label" for="adresse">Société</label>
	                    <input value="Aucun" readonly class="form-control" placeholder="Néant">
	                </div>
	            </div>
	        `);
	    }

	    $dynamicFields.append($groupe);
	}

	function eng_patient() {
	    const $divAssurer = $("#div_assurer");

	    let nom = $("#patient_nom_new");
	    let prenom = $("#patient_prenom_new");
	    let sexe = $("#patient_sexe_new");
	    let datenais = $("#patient_datenaiss_new");
	    let phone = $("#patient_tel_new");
	    let phone2 = $("#patient_tel2_new");
	    let residence = $("#patient_residence_new");
	    let assurer = $("#assure");

	    let filiation = $("#patient_codefiliation_new");
	    let matricule_assurance = $("#patient_matriculeA_new");
	    let assurance_id = $("#patient_codeassurance_new");
	    let taux_id = $("#patient_idtauxcouv_new");
	    let societe_id = $("#patient_codesocieteassure_new");

	    let nomu = $("#patient_nomu_new");
	    let telu = $("#patient_telu_new");
	    let telu2 = $("#patient_telu2_new");

	    // Validation des champs obligatoires
	    if (!nom.val().trim() || !prenom.val().trim() || !phone.val().trim() || !datenais.val().trim() || !sexe.val().trim() || !residence.val().trim() || !assurer.val().trim()) {
	        showAlert("Alert", "Veuillez remplir tous les champs obligatoires.", "warning");
	        return false;
	    }

	    // Validation des numéros de téléphone
	    if (phone.val().length !== 10 ) {
	        showAlert("Alert", "Contact 1 incomplet.", "warning");
	        return false;
	    }
	    if (phone2.val() && phone2.val().length !== 10) {
	        showAlert("Alert", "Contact 2 incomplet.", "warning");
	        return false;
	    }
	    if (telu.val() && telu.val().length !== 10) {
	        showAlert("Alert", "Contact 1 en cas d'urgence incomplet.", "warning");
	        return false;
	    }
	    if (telu2.val() && telu2.val().length !== 10) {
	        showAlert("Alert", "Contact 2 en cas d'urgence incomplet.", "warning");
	        return false;
	    }

	    // Validation des champs relatifs à l'assurance
	    if (assurer.val() === "1") {
	        if (!assurance_id.val() || !taux_id.val() || !societe_id.val() || !filiation.val() || !matricule_assurance.val()) {
	            showAlert("Alert", "Veuillez remplir tous les champs relatifs à l'assurance.", "warning");
	            return false;
	        }
	    }

	    const label = $('#btn_eng_patient').text();
        const idBtn = '#btn_eng_patient';
        spinerButton(1, idBtn, 'Vérification');

	    // Envoi AJAX
	    $.get($('#url').attr('content') + "/api/patient_new", {
	        nom: nom.val(),
	        prenom: prenom.val(),
	        tel: phone.val(),
	        tel2: phone2.val() || null,
	        residence: residence.val(),
	        assurer: assurer.val(),
	        assurance_id: assurance_id.val() || null,
	        taux_id: taux_id.val() || null,
	        societe_id: societe_id.val() || null,
	        datenais: datenais.val(),
	        sexe: sexe.val(),
	        filiation: filiation.val() || null,
	        matricule_assurance: matricule_assurance.val() || null,
	        nomu: nomu.val() || null,
	        telu: telu.val() || null,
	        telu2: telu2.val() || null
	    })
	    .done(function(response) {
	        spinerButton(0, idBtn, label);

	        if (response.success) {
	            // Réinitialisation des champs
	            nom.val(""); prenom.val(""); phone.val(""); phone2.val(""); residence.val("");
	            datenais.val(""); sexe.val("").trigger('change');
	            nomu.val(""); telu.val(""); telu2.val("");
	            filiation.val("").trigger('change');
	            matricule_assurance.val(""); assurance_id.val("").trigger('change');
	            taux_id.val("").trigger('change'); societe_id.val("").trigger('change');
	            assurer.val("").trigger('change');
	            select_patient('#patient_id');
	            $divAssurer.hide();
	            $("#Table_day").DataTable().ajax.reload(null, false);
	            showAlert("Succès", response.message, "success");
	        } else if (response.error) {
	            showAlert("Alert", response.message, "error");
	        }
	    })
	    .fail(function() {
	        spinerButton(0, idBtn, label);
	        showAlert("Alert", "Une erreur est survenue lors de l'enregistrement.", "error");
	    });
	}

	function eng_patient_modif() {
	    let matricule = $("#MatriculeModif").val();
	    let nom = $("#patient_nom_Modif");
	    let prenom = $("#patient_prenom_Modif");
	    let sexe = $("#patient_sexe_Modif");
	    let datenais = $("#patient_datenaiss_Modif");
	    let phone = $("#patient_tel_Modif");
	    let phone2 = $("#patient_tel2_Modif");
	    let residence = $("#patient_residence_Modif");
	    let nomu = $("#patient_nomu_Modif");
	    let telu = $("#patient_telu_Modif");
	    let telu2 = $("#patient_telu2_Modif");
	    let assure = $("#assure_Modif");
	    let filiation = $("#patient_codefiliation_Modif");
	    let assurance = $("#patient_codeassurance_Modif");
	    let matriculeas = $("#patient_matriculeA_Modif");
	    let taux = $("#patient_idtauxcouv_Modif");
	    let societe = $("#patient_codesocieteassure_Modif");

	    if (!nom.val().trim() || !prenom.val().trim() || !phone.val().trim() || !datenais.val().trim() || !sexe.val().trim() || !residence.val().trim()) {
	        showAlert("Alert", "Veuillez remplir tous les champs obligatoires.", "warning");
	        return false;
	    }

	    if (phone.val().length !== 10 ) { showAlert("Alert", "Contact 1 incomplet.", "warning"); return false; }
	    if (phone2.val() && phone2.val().length !== 10) { showAlert("Alert", "Contact 2 incomplet.", "warning"); return false; }
	    if (telu.val() && telu.val().length !== 10) { showAlert("Alert", "Contact 1 en cas d'urgence incomplet.", "warning"); return false; }
	    if (telu2.val() && telu2.val().length !== 10) { showAlert("Alert", "Contact 2 en cas d'urgence incomplet.", "warning"); return false; }

	    if (assure.val() == 1 && (!filiation.val().trim() || !assurance.val().trim() || !matriculeas.val().trim() || !taux.val().trim() || !societe.val().trim())) {
	        showAlert("Alert", "Veuillez remplir tous les champs obligatoires.", "warning");
	        return false;
	    }

	    const label = $('#btn_eng_modif').text();
        const idBtn = '#btn_eng_modif';
        spinerButton(1, idBtn, 'Vérification');

	    $.get($('#url').attr('content') + `/api/patient_modif/${matricule}`, {
	        nom: nom.val(),
	        prenom: prenom.val(),
	        tel: phone.val(),
	        tel2: phone2.val() || null,
	        residence: residence.val(),
	        datenais: datenais.val(),
	        sexe: sexe.val(),
	        nomu: nomu.val() || null,
	        telu: telu.val() || null,
	        telu2: telu2.val() || null,
	        assure: assure.val(),
	        filiation: filiation.val() || null,
	        assurance: assurance.val() || null,
	        matriculeas: matriculeas.val() || null,
	        taux: taux.val() || null,
	        societe: societe.val() || null
	    })
	    .done(function(response) {
	        spinerButton(0, idBtn, label);
	        if (response.success) {
	        	bootstrap.Modal.getInstance($('#ModifP')).hide();
	            $("#Table_day").DataTable().ajax.reload(null, false);
	            showAlert("Succès", response.message, "success");
	        } else if (response.error) {
	            showAlert("Alert", response.message, "error");
	        }
	    })
	    .fail(function() {
	        spinerButton(0, idBtn, label);
	        showAlert("Alert", "Une erreur est survenue lors de l'enregistrement.", "error");
	    });
	}

	function delete_pat() {
	    const matricule = $("#IddeleteCons").val();
	    

	    const label = $('#deleteBtnCons').text();
        const idBtn = '#deleteBtnCons';
        spinerButton(1, idBtn, 'Vérification');

	    $.get($('#url').attr('content') +`/api/delete_Pat/${matricule}`)
	        .done(function(response) {
	            spinerButton(0, idBtn, label);
	            if (response.success) {
	            	bootstrap.Modal.getInstance($('#MdeletePat')).hide();
	                $("#Table_day").DataTable().ajax.reload(null, true);
	                showAlert('Succès', 'Opération éffectuée. Veuillez patienter un instant pour la mise à jour','success');
	            } else if (response.error) {
	                showAlert("ERREUR", 'Echec de l\'opération', "error");
	            } else if (response.existe_p) {
	                showAlert("Alert", 'Patient introuvable', "info");
	            } else {
	                showAlert("ERREUR", 'Echec de l\'opération', "error");
	            }
	        })
	        .fail(function() {
	            spinerButton(0, idBtn, label);
	            showAlert('Erreur', 'Erreur lors de la suppression.','error');
	        });
	}

	function Statistique() {
	    const $stat = $("#stat");
	    $stat.html(`
	        <div class="d-flex justify-content-center align-items-center mb-3">
	            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
	            <strong>Chargement des données...</strong>
	        </div>
	    `);

	    $('#div_btn_affiche_stat').hide();
	    $('#div_btn_cache_stat').hide();

	    $.getJSON($('#url').attr('content') +'/api/statistique_patient')
	        .done(function(data) {

	            $('#div_btn_affiche_stat').hide();
	            $('#div_btn_cache_stat').show();

	            const { stat_h, stat_f, stat_a, stat_an } = data;
	            $stat.empty();

	            if (stat_h === 0 && stat_f === 0 && stat_a === 0 && stat_an === 0) {
	                $stat.html(`<div class="d-flex justify-content-center align-items-center"><p>Aucune données n'a été trouvée</p></div>`);
	                return;
	            }

	            const $rowDiv = $('<div class="row justify-content-center align-items-center"></div>');

	            const $div1 = $(`
	                <div class="col-sm-6 mb-3">
	                    <div class="card">
	                        <div class="card-body">
	                        <h5 class="card-title">Genre</h5>
	                            <div class="auto-align-graph">
	                                <div id="graphGenre"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            `);
	            const $div2 = $(`
	                <div class="col-sm-6 mb-3">
	                    <div class="card">
	                        <div class="card-body">
	                            <h5 class="card-title">Assurer ?</h5>
	                            <div class="auto-align-graph">
	                                <div id="graphAssurer"></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            `);

	            $rowDiv.append($div1, $div2);
	            $stat.append($rowDiv);

	            var chartGenre = new ApexCharts(document.querySelector("#graphGenre"), {
	                chart: { width: 240, type: "donut" },
	                labels: ["Homme", "Femme"],
	                series: [stat_h, stat_f],
	                legend: { position: "bottom" },
	                dataLabels: { enabled: false },
	                stroke: { width: 0 },
	                colors: ["#116AEF", "#ff5a39", "#ff5a39", "#3e3e42", "#75C2F6"],
	            });
	            chartGenre.render();

	            var chartAssurer = new ApexCharts(document.querySelector("#graphAssurer"), {
	                chart: { width: 240, type: "donut" },
	                labels: ["Assuré", "Non-Assuré"],
	                series: [stat_a, stat_an],
	                legend: { position: "bottom" },
	                dataLabels: { enabled: false },
	                stroke: { width: 0 },
	                colors: ["#0ebb13", "#3e3e42"],
	            });
	            chartAssurer.render();
	        })
	        .fail(function(error) {
	            $stat.html(`
	                <div class="d-flex justify-content-center align-items-center mb-3">
	                    <strong class="text-danger">Erreur lors du chargement des données</strong>
	                </div>
	            `);
	            console.error('Erreur lors du chargement des données:', error);
	        });
	}

	$('#Table_day').DataTable({

	    processing: true,
	    serverSide: false,
	    ajax: function(data, callback) {
	        const statut = $('#statutP').val();
	        
	        $.ajax({
	            url: $('#url').attr('content') +`/api/list_patient_all/${statut}`,
	            type: 'GET',
	            success: function(response) {
	                callback({ data: response.data });
	            },
	            error: function() {
	                console.log('Error fetching data. Please check your API or network.');
	            }
	        });
	    },
	    columns: [
	        { 
	            data: null, 
	            render: (data, type, row, meta) => meta.row + 1,
	            searchable: false,
	            orderable: false,
	        },
	        { 
	            data: 'nomprenomspatient', 
	            render: (data, type, row) => `
	            <div class="d-flex align-items-center">
	                <a class="d-flex align-items-center flex-column me-2">
	                    <img src="assets/images/user8.png" class="img-2x rounded-circle border border-1">
	                </a>
	                ${data}
	            </div>`,
	            searchable: true, 
	        },
	        { 
	            data: 'idenregistremetpatient',
	            searchable: true, 
	        },
	        { 
	            data: 'datenaispatient',
	            render: formatDate,
	            searchable: true, 
	        },
	        { 
	            data: null, 
	            render: (data, type, row) => `${calculateAge(row.datenaispatient)} Ans`,
	            searchable: true, 
	        },
	        {
	            data: 'assure',
	            render: (data, type, row) => `
	                <span class="badge ${data === 1 ? 'bg-success' : 'bg-danger'}">
	                    ${data === 1 ? 'Assurer' : 'Non-assurer'}
	                </span>
	            `,
	            searchable: true,
	        },
	        {
	            data: 'assurance',
	            render: (data, type, row) => `
	                ${row.assure === 1 ? `${data}` : 'Aucune assurance' }
	            `,
	            searchable: true,
	        },
	        { 
	            data: 'telpatient', 
	            render: (data) => `${data}`,
	            searchable: true, 
	        },
	        { 
	            data: 'nbre_acte', 
	            render: (data) => `${data}`,
	            searchable: true, 
	        },
	        { 
	            data: 'dateenregistrement',
	            render: formatDate,
	            searchable: true, 
	        },
	        {
	            data: null,
	            render: (data, type, row) => `
	                <div class="btn-group">
	                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="dropdown">
	                        <i class="ri-more-2-fill"></i>
	                    </button>
	                    <ul class="dropdown-menu">
	                        <li>
	                            <a href="#" class="dropdown-item text-warning"
	                                data-bs-toggle="modal" 
	                               data-bs-target="#DetailP" 
	                               id="detailP" 
	                                data-nom="${row.nompatient}"
	                               data-prenom="${row.prenomspatient}"
	                               data-matricule="${row.idenregistremetpatient}"
	                               data-tel="${row.telpatient}" 
	                               data-tel2="${row.telpatient_2}" 
	                               data-telu="${row.telurgence_1}" 
	                               data-telu2="${row.telurgence_2}" 
	                               data-nomu="${row.nomurgence}"
	                               data-sexe="${row.sexe}" 
	                               data-residence="${row.lieuderesidencepat}" 
	                               data-filiation="${row.filiation}"
	                               data-created_at="${row.dateenregistrement}" 
	                               data-datenais="${row.datenaispatient}" 
	                               data-assurer="${row.assure}"
	                               data-assurance="${row.assurance}"
	                               data-societe="${row.societe}" 
	                               data-taux="${row.taux}"
	                               data-matricule_assurance="${row.matriculeassure}"
	                               data-numdossierc="${row.numdossierC}"
	                               data-numdossierh="${row.numdossierH}"
	                            >
	                                <i class="ri-eye-line"></i>
	                                Détail
	                            </a>
	                        </li>
	                        <li>
	                            <a href="#" class="dropdown-item text-info" 
	                                data-bs-toggle="modal" 
	                               data-bs-target="#ModifP" 
	                               id="modifP"
	                               data-nom="${row.nompatient}"
	                               data-prenom="${row.prenomspatient}"
	                               data-matricule="${row.idenregistremetpatient}"
	                               data-tel="${row.telpatient}" 
	                               data-tel2="${row.telpatient_2}" 
	                               data-telu="${row.telurgence_1}" 
	                               data-telu2="${row.telurgence_2}" 
	                               data-nomu="${row.nomurgence}"
	                               data-sexe="${row.sexe}" 
	                               data-residence="${row.lieuderesidencepat}"
	                               data-datenais="${row.datenaispatient}"

	                               data-assure="${row.assure}"
	                               data-codeassurance="${row.codeassurance}"
	                               data-codefiliation="${row.codefiliation}"
	                               data-matriculeassure="${row.matriculeassure}"
	                               data-codesocieteassure="${row.codesocieteassure}"
	                               data-idtauxcouv="${row.idtauxcouv}"

	                               data-nbre_acte="${row.nbre_acte}"
	                            >
	                                <i class="ri-edit-box-line"></i>
	                                Modifier
	                            </a>
	                        </li>
	                        ${row.nbre_acte == 0 ? `<li>
	                            <a href="#" class="dropdown-item text-danger" 
	                                data-bs-toggle="modal" 
	                               data-bs-target="#MdeletePat" 
	                               id="deleteP"
	                               data-matricule="${row.idenregistremetpatient}"
	                            >
	                                <i class="ri-delete-bin-line"></i>
	                                Supprimer
	                            </a>
	                        </li>` : ``}
	                        
	                    </ul>
	                </div>
	            `,
	            searchable: false,
	            orderable: false,
	        }
	    ],
	    ...dataTableConfig, 
	    initComplete: function(settings, json) {
	        initializeRowEventListeners();
	    },
	});

	function initializeRowEventListeners() {

	    // ----- Affichage détails patient -----
	    $('#Table_day').on('click', '#detailP', function() {

	        const row = {
	            nom : $(this).data('nom'),
	            prenom : $(this).data('prenom'),
	            matricule : $(this).data('matricule'),
	            tel : $(this).data('tel'), 
	            tel2 : $(this).data('tel2'), 
	            telu : $(this).data('telu'), 
	            telu2 : $(this).data('telu2'), 
	            nomu : $(this).data('nomu'),
	            sexe : $(this).data('sexe'), 
	            residence : $(this).data('residence'),
	            created_at : $(this).data('created_at'), 
	            datenais : $(this).data('datenais'), 
	            assurer : $(this).data('assurer'),
	            assurance : $(this).data('assurance'),
	            societe : $(this).data('societe'), 
	            taux : $(this).data('taux'),
	            filiation : $(this).data('filiation'),
	            matricule_assurance : $(this).data('matricule_assurance'),
	            numdossierC : $(this).data('numdossierc'),
	            numdossierH : $(this).data('numdossierh'),
	        };

	        const $modal = $('#modal_detailP');
	        $modal.empty(); // vide le contenu

	        let content = `
	            <div class="row gx-3">
	                <div class="col-12">
	                    <div class="mb-3">
	                        <div class="card-body text-center">
	                            <a href="doctors-profile.html" class="d-flex align-items-center flex-column">
	                                <img src="{{asset('assets/images/user8.png')}}" class="img-7x rounded-circle mb-3 border border-3">
	                                <h5>${row.nom} ${row.prenom}</h5>
	                                <p>Date création : ${formatDate(row.created_at)}</p>
	                            </a>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-12">
	                    <div class="mb-3">
	                        <div class="card-body">
	                            <ul class="list-group">
	                                <li class="list-group-item active text-center" aria-current="true">
	                                    Informations personnelles
	                                </li>
	                                <li class="list-group-item">Matricule : ${row.matricule}</li>
	                                <li class="list-group-item">Nom : ${row.nom}</li>
	                                <li class="list-group-item">Prénoms : ${row.prenom}</li>
	                                <li class="list-group-item">Date de naissance : ${formatDate(row.datenais)}</li>
	                                <li class="list-group-item">Age : ${calculateAge(row.datenais)} An(s)</li>
	                                <li class="list-group-item">Genre : ${row.sexe == 'H' ? 'Homme' : 'Femme'}</li>
	                                <li class="list-group-item">Contact 1 : ${row.tel || 'Néant'}</li>
	                                <li class="list-group-item">Contact 2 : ${row.tel2 || 'Néant'}</li>
	                                <li class="list-group-item">Résidence : ${row.residence || 'Néant'}</li>
	                                <li class="list-group-item ${row.assurer == 1 ? 'text-success' : 'text-danger'}">
	                                    Assurer : ${row.assurer == 1 ? 'Oui' : 'Non'}
	                                </li>
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	                ${row.assurer == '1' ?  
	                `<div class="col-12">
	                    <div class="mb-3">
	                        <div class="card-body">
	                            <ul class="list-group">
	                                <li class="list-group-item text-white text-center bg-success" aria-current="true">
	                                    Informations Assurance
	                                </li>
	                                <li class="list-group-item">Nom de l'assurance : ${row.assurance || 'Néant'}</li>
	                                <li class="list-group-item">Taux de Couverture : ${row.taux ? row.taux + ' %' : 'Néant'}</li>
	                                <li class="list-group-item">Filiation : ${row.filiation || 'Néant'}</li>
	                                <li class="list-group-item">Matricule : ${row.matricule_assurance || 'Néant'}</li>
	                                <li class="list-group-item">Société : ${row.societe || 'Néant'}</li>
	                            </ul>
	                        </div>
	                    </div>
	                </div>` : ''}
	                <div class="col-12">
	                    <div class="mb-3">
	                        <div class="card-body">
	                            <ul class="list-group">
	                                <li class="list-group-item text-white text-center bg-warning" aria-current="true">
	                                    Information Dossier
	                                </li>
	                                <li class="list-group-item">Numéro Dossier Consultation : ${row.numdossierC || 'Néant'}</li>
	                                <li class="list-group-item">Numéro Dossier Hospitalisation : ${row.numdossierH || 'Néant'}</li>
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-12">
	                    <div class="mb-3">
	                        <div class="card-body">
	                            <ul class="list-group">
	                                <li class="list-group-item text-white text-center bg-danger" aria-current="true">
	                                    Informations en Cas d'urgence
	                                </li>
	                                <li class="list-group-item">Nom : ${row.nomu || 'Néant'}</li>
	                                <li class="list-group-item">Contact 1 : ${row.telu || 'Néant'}</li>
	                                <li class="list-group-item">Contact 2 : ${row.telu2 || 'Néant'}</li>
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        `;

	        $modal.append(content);
	    });

	    // ----- Modification patient -----
	    $('#Table_day').on('click', '#modifP', function() {
	        const row = {
	            nom : $(this).data('nom'),
	            prenom : $(this).data('prenom'),
	            matricule : $(this).data('matricule'),
	            tel : $(this).data('tel'), 
	            tel2 : $(this).data('tel2'), 
	            telu : $(this).data('telu'), 
	            telu2 : $(this).data('telu2'), 
	            nomu : $(this).data('nomu'),
	            sexe : $(this).data('sexe'), 
	            residence : $(this).data('residence'), 
	            datenais : $(this).data('datenais'),

	            assure : $(this).data('assure'),
	            assurance : $(this).data('codeassurance'),
	            societe : $(this).data('codesocieteassure'),
	            filiation : $(this).data('codefiliation'),
	            taux : $(this).data('idtauxcouv'),
	            matriculeas : $(this).data('matriculeassure'),

	            nbre_acte : $(this).data('nbre_acte'),
	        };

	        $('#MatriculeModif').val(row.matricule);
	        $('#patient_nom_Modif').val(row.nom);
	        $('#patient_prenom_Modif').val(row.prenom);
	        $('#patient_datenaiss_Modif').val(row.datenais);
	        $('#patient_tel_Modif').val(row.tel);
	        $('#patient_tel2_Modif').val(row.tel2);
	        $('#patient_residence_Modif').val(row.residence);
	        $('#patient_nomu_Modif').val(row.nomu);
	        $('#patient_telu_Modif').val(row.telu);
	        $('#patient_telu2_Modif').val(row.telu2);
	        $('#patient_sexe_Modif').val(row.sexe).trigger('change');

	        if (row.assure == 1) {
	            $('#div_assurer_Modif').css('display', 'flex');
	            $('#assure_Modif').val(1).trigger('change');

	            $('#patient_codefiliation_Modif').val(row.filiation).trigger('change');
	            $('#patient_codeassurance_Modif').val(row.assurance).trigger('change');
	            $('#patient_idtauxcouv_Modif').val(row.taux).trigger('change');
	            $('#patient_codesocieteassure_Modif').val(row.societe).trigger('change');
	            $('#patient_matriculeA_Modif').val(row.matriculeas);
	        } else {
	            $('#div_assurer_Modif').hide();
	            $('#assure_Modif').val(0).trigger('change');

	            $('#patient_codefiliation_Modif').val(null).trigger('change');
	            $('#patient_codeassurance_Modif').val(null).trigger('change');
	            $('#patient_idtauxcouv_Modif').val(null).trigger('change');
	            $('#patient_codesocieteassure_Modif').val(null).trigger('change');
	            $('#patient_matriculeA_Modif').val(null);
	        }
	    });

	    // ----- Suppression patient -----
	    $('#Table_day').on('click', '#deleteP', function() {
	        const matricule = $(this).data('matricule');
	        $('#IddeleteCons').val(matricule);
	    });
	}

	function rech_dosier(id) {
	    const $dynamicFields = $("#div_info_patient");
	    $dynamicFields.html(`
	        <div class="d-flex justify-content-center align-items-center" id="laoder_stat">
	            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
	            <strong>Chargement des données...</strong>
	        </div>
	    `);

	    $.ajax({
	        url: $('#url').attr('content') +'/api/rech_patient',
	        method: 'GET',
	        data: { id: id },
	        success: function(response) {
	            if(response.existep) {
	                showAlert('Alert', 'Ce patient n\'existe pas.', 'error');
	            } else if (response.success) {
	                addGroupD(response.patient);
	            }
	        },
	        error: function() {
	            showAlert('Alert', 'Une erreur est survenue lors de la recherche.', 'error');
	        }
	    });
	}

	function addGroupD(item) {

	    const url = $('#url').attr('content') + `/api/patient_stat/${item.idenregistremetpatient}`;

	    $.getJSON(url, function(data) {

	        const $dynamicFields = $("#div_info_patient");
	        $dynamicFields.empty();

	        const nbre_cons = data.nbre_cons;
	        const nbre_hos = data.nbre_hos;
	        const nbre_exam = data.nbre_exam;
	        const nbre_soinsam = data.nbre_soinsam;
	        const stats = data.data;
	        const payer = data.fac_patient_payer;
	        const impayer = data.fac_patient_impayer;
	        const total = data.fac_patient_total;

	        // Groupe info patient
	        var groupe = $('<div class="row gx-3"></div>');
	        groupe.html(`
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="email">N° Dossier</label>
	                    <input value="${item.numdossier ? item.numdossier : `Néant` }" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="tel">Date de naissance</label>
	                    <input value="${formatDate(item.datenaispatient) ? formatDate(item.datenaispatient) : `Néant`}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="tel">Age</label>
	                    <input value="${calculateAge(item.datenaispatient) ? calculateAge(item.datenaispatient) + ` Ans` : `Néant`}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="tel">Contact 1</label>
	                    <input value="${item.telpatient ? item.telpatient : `Néant`}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="tel">Contact 2</label>
	                    <input value="${item.telpatient_2 ? item.telpatient_2 : `Néant`}" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label" for="email">Lieu de résidence</label>
	                    <input value="${item.lieuderesidencepat ? item.lieuderesidencepat : `Néant` }" readonly class="form-control">
	                </div>
	            </div>
	            <div class="col-xxl-3 col-lg-4 col-sm-6">
	                <div class="mb-3">
	                    <label class="form-label">Assurer</label>
	                    <input value="${item.assure == 1 ? `Oui` : `Non`}" readonly class="form-control">
	                </div>
	            </div>
	        `);

	        if (item.assure == 1) {
	            groupe.append(`
	                <div class="col-xxl-3 col-lg-4 col-sm-6">
	                    <div class="mb-3">
	                        <label class="form-label" for="adresse">Assurance</label>
	                        <input value="${item.assurance}" readonly class="form-control" placeholder="Néant">
	                    </div>
	                </div>
	                <div class="col-xxl-3 col-lg-4 col-sm-6">
	                    <div class="mb-3">
	                        <label class="form-label" for="adresse">Filiation</label>
	                        <input value="${item.filiation}" readonly class="form-control" placeholder="Néant">
	                    </div>
	                </div>
	                <div class="col-xxl-3 col-lg-4 col-sm-6">
	                    <div class="mb-3">
	                        <label class="form-label" for="adresse">Matricule Assurance</label>
	                        <input value="${item.matriculeassure ? item.matriculeassure : `aucun` }" readonly class="form-control" placeholder="Néant">
	                    </div>
	                </div>
	                <div class="col-xxl-3 col-lg-4 col-sm-6">
	                    <div class="mb-3">
	                        <label class="form-label">Taux</label>
	                        <div class="input-group">      
	                            <input id="patient_taux" value="${item.taux}" readonly class="form-control" placeholder="Néant">
	                            <span class="input-group-text">%</span>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-xxl-3 col-lg-4 col-sm-6">
	                    <div class="mb-3">
	                        <label class="form-label" for="adresse">Société</label>
	                        <input value="${item.societe}" readonly class="form-control" placeholder="Néant">
	                    </div>
	                </div>
	            `);
	        }

	        $dynamicFields.append(groupe);

	        // Statistique
	        var groupe1 = $(`
	            <div class="row gx-3">
	                <div class=" mb-0">
	                    <div class="card-body">
	                        <div class="card-header d-flex flex-column justify-content-center align-items-center">
	                            <h5 class="card-title mb-3">Statistique des actes éffectués</h5>
	                        </div>
	                        <div class="row gx-3 d-flex align-items-center justify-content-center">
	                            <div class="mb-3 col-md-4 col-lg-4 col-12">
	                                <div class="border rounded-2 d-flex align-items-center justify-content-center flex-row p-2">
	                                    <div class="text-center">
	                                        <div class="d-flex align-items-center">
	                                            <h4 class="m-0 fw-bold text-primary">${formatPriceT(total)} Fcfa</h4>
	                                        </div>
	                                        <small>Montant Total</small>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="mb-3 col-md-4 col-lg-4 col-12">
	                                <div class="border rounded-2 d-flex align-items-center justify-content-center flex-row p-2">
	                                    <div class="text-center">
	                                        <div class="d-flex align-items-center">
	                                            <h4 class="m-0 fw-bold text-success">${formatPriceT(payer)} Fcfa</h4>
	                                        </div>
	                                        <small>Montant Payer</small>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="mb-3 col-md-4 col-lg-4 col-12">
	                                <div class="border rounded-2 d-flex align-items-center justify-content-center flex-row p-2">
	                                    <div class="text-center">
	                                        <div class="d-flex align-items-center">
	                                            <h4 class="m-0 fw-bold text-danger">${formatPriceT(impayer)} Fcfa</h4>
	                                        </div>
	                                        <small>Montant Impayer</small>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        `);

	        $dynamicFields.append(groupe1);

	        // Statistique des actes détaillés
	        var $groupe01 = $('<div class="row gx-3 stat_acte"></div>');
	        $dynamicFields.append($groupe01);
	        $groupe01.empty();

	        const cardData_acte = [
	            { label: "Consultations", count: nbre_cons, icon: "ri-lungs-line", colorClass: "text-success", borderColor: "border-success", bgColor: "bg-success", mTotal : formatPriceT(stats.m_cons.total_general), pTotal : formatPriceT(stats.m_cons.total_payer), ipTotal : formatPriceT(stats.m_cons.total_impayer), assurance : formatPriceT(stats.m_cons.part_assurance), patient : formatPriceT(stats.m_cons.part_patient)},
	            { label: "Examens", count: nbre_exam, icon: "ri-medicine-bottle-line", colorClass: "text-danger", borderColor: "border-danger", bgColor: "bg-danger", mTotal : formatPriceT(stats.m_exam.total_general), pTotal : formatPriceT(stats.m_exam.total_payer), ipTotal : formatPriceT(stats.m_exam.total_impayer), assurance : formatPriceT(stats.m_exam.part_assurance), patient : formatPriceT(stats.m_exam.part_patient)},
	            { label: "Hospitalisations", count: nbre_hos, icon: "ri-hotel-bed-line", colorClass: "text-primary", borderColor: "border-primary", bgColor: "bg-primary", mTotal : formatPriceT(stats.m_hos.total_general), pTotal : formatPriceT(stats.m_hos.total_payer), ipTotal : formatPriceT(stats.m_hos.total_impayer), assurance : formatPriceT(stats.m_hos.part_assurance), patient : formatPriceT(stats.m_hos.part_patient)},
	            { label: "Soins Ambulatoires", count: nbre_soinsam, icon: "ri-dossier-line", colorClass: "text-warning", borderColor: "border-warning", bgColor: "bg-warning", mTotal : formatPriceT(stats.m_soinsam.total_general), pTotal : formatPriceT(stats.m_soinsam.total_payer), ipTotal : formatPriceT(stats.m_soinsam.total_impayer), assurance : formatPriceT(stats.m_soinsam.part_assurance), patient : formatPriceT(stats.m_soinsam.part_patient)},
	        ];

	        $.each(cardData_acte, function(i, card) {
	            var $div = $(`
	                <div class="col-lg-6 col-md-6 col-12">
	                    <div class="border rounded-2 d-flex align-items-center flex-row p-2 mb-3">
	                        <div class="card-body">
	                            <div class="d-flex align-items-center">
	                                <div class="p-2 ${card.borderColor} rounded-circle me-3">
	                                    <div class="icon-box md ${card.bgColor} rounded-5">
	                                        <i class="${card.icon} fs-4 text-white"></i>
	                                    </div>
	                                </div>
	                                <div class="d-flex flex-column">
	                                    <h2 class="lh-1">${card.count}</h2>
	                                    <p class="m-0">${card.label}</p>
	                                </div>
	                            </div>
	                            <div class="d-flex align-items-end justify-content-between mt-1">
	                                <a class="${card.colorClass}" href="javascript:void(0);"><span>Montant Total</span><i class="ri-arrow-right-line ${card.colorClass} ms-1"></i></a>
	                                <div class="text-end"><p class="mb-0 ${card.colorClass}">${card.mTotal} Fcfa</p></div>
	                            </div>
	                            <div class="d-flex align-items-end justify-content-between mt-1">
	                                <a class="${card.colorClass}" href="javascript:void(0);"><span>Montant Réglé</span><i class="ri-arrow-right-line ${card.colorClass} ms-1"></i></a>
	                                <div class="text-end"><p class="mb-0 ${card.colorClass}">${card.pTotal} Fcfa</p></div>
	                            </div>
	                            <div class="d-flex align-items-end justify-content-between mt-1">
	                                <a class="${card.colorClass}" href="javascript:void(0);"><span>Montant Non-Réglé</span><i class="ri-arrow-right-line ${card.colorClass} ms-1"></i></a>
	                                <div class="text-end"><p class="mb-0 ${card.colorClass}">${card.ipTotal} Fcfa</p></div>
	                            </div>
	                            <div class="d-flex align-items-end justify-content-between mt-1">
	                                <a class="${card.colorClass}" href="javascript:void(0);"><span>Part Assurance</span><i class="ri-arrow-right-line ${card.colorClass} ms-1"></i></a>
	                                <div class="text-end"><p class="mb-0 ${card.colorClass}">${card.assurance} Fcfa</p></div>
	                            </div>
	                            <div class="d-flex align-items-end justify-content-between mt-1">
	                                <a class="${card.colorClass}" href="javascript:void(0);"><span>Part Patient</span><i class="ri-arrow-right-line ${card.colorClass} ms-1"></i></a>
	                                <div class="text-end"><p class="mb-0 ${card.colorClass}">${card.patient} Fcfa</p></div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            `);
	            $groupe01.append($div);
	        });

	        //--------------------------------------------------
	        // Liste des actes (onglets)
	        var groupe2 = $(`
	            <div class="row">
	                <div class="col-12">
                        <div class="card-body">
                            <div class="card-header d-flex justify-content-center">
                                <h5 class="card-title">Liste des actes éffectués</h5>
                            </div>
                            <div class="custom-tabs-container">
                                <ul class="nav nav-tabs justify-content-center" id="customTab4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="tab-one1" data-bs-toggle="tab" href="#one1" role="tab" aria-controls="one1" aria-selected="false" tabindex="-1">Consultations</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-one2" data-bs-toggle="tab" href="#one2" role="tab" aria-controls="one2" aria-selected="false" tabindex="-1">Examens</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-one3" data-bs-toggle="tab" href="#one3" role="tab" aria-controls="one3" aria-selected="true">Hospitalisations</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="tab-one4" data-bs-toggle="tab" href="#one4" role="tab" aria-controls="one4" aria-selected="true">Soins Ambulatoires</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="customTabContent">
                                    <div class="tab-pane fade active show" id="one1" role="tabpanel" aria-labelledby="tab-one1">
                                        <div class="card-body">
                                            <div class="table-outer" id="div_TableC" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-hover m-0 truncate" id="TableC">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Code</th>
                                                                <th scope="col">Médecin</th>
                                                                <th scope="col">Spécialité</th>
                                                                <th scope="col">Prix</th>
                                                                <th scope="col">N° Facture</th>
                                                                <th scope="col">Date et Heure</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="message_TableC" style="display: none;">
                                                <p class="text-center">
                                                    Aucune Consultation n'a été trouvée
                                                </p>
                                            </div>
                                            <div id="div_Table_loaderC" style="display: none;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                    <strong>Chargement des données...</strong>
                                                </div>
                                            </div>
                                            <div id="pagination-controlsC"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="one2" role="tabpanel" aria-labelledby="tab-one2">
                                        <div class="card-body">
                                            <div class="table-outer" id="div_TableED" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-hover m-0 truncate" id="TableED">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Type d'examen</th>
                                                                <th scope="col">Nombre d'examen</th>
                                                                <th scope="col">Montant Total</th>
                                                                <th scope="col">Date de création</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="message_TableED" style="display: none;">
                                                <p class="text-center" >
                                                    Aucun examen demandé pour le moment
                                                </p>
                                            </div>
                                            <div id="div_Table_loaderED" style="display: none;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                    <strong>Chargement des données...</strong>
                                                </div>
                                            </div>
                                            <div id="pagination-controlsED" ></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="one3" role="tabpanel" aria-labelledby="tab-one3">
                                        <div class="card-body">
                                            <div class="table-outer" id="div_Table_hos" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-hover m-0 truncate" id="Table_hos">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Type</th>
                                                                <th scope="col">Nature</th>
                                                                <th scope="col">Date entrer</th>
                                                                <th scope="col">Date sorti</th>
                                                                <th scope="col">Médecin</th>
                                                                <th scope="col">Statut</th>
                                                                <th scope="col">Montant Total</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="message_Table_hos" style="display: none;">
                                                <p class="text-center" >
                                                    Aucune hospitalisation pour le moment
                                                </p>
                                            </div>
                                            <div id="div_Table_loader_hos" style="display: none;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                    <strong>Chargement des données...</strong>
                                                </div>
                                            </div>
                                            <div id="pagination-controls-hos" ></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="one4" role="tabpanel" aria-labelledby="tab-one4">
                                        <div class="card-body">
                                            <div class="table-outer" id="div_Tablesoinsam" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table align-middle table-hover m-0 truncate" id="Tablesoinsam">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">N°</th>
                                                                <th scope="col">Nbre Soins</th>
                                                                <th scope="col">Nbre Produits</th>
                                                                <th scope="col">Montant Total</th>
                                                                <th scope="col">Date de création</th>
                                                                <th scope="col"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div id="message_Tablesoinsam" style="display: none;">
                                                <p class="text-center" >
                                                    Aucun Soins Ambulatoires pour le moment
                                                </p>
                                            </div>
                                            <div id="div_Table_loadersoinsam" style="display: none;">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                                    <strong>Chargement des données...</strong>
                                                </div>
                                            </div>
                                            <div id="pagination-controlssoinsam" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
	                </div>
	            </div>
	        `);

	        $dynamicFields.append(groupe2);

	        //--------------------------------------------------
	        list_cons_patient(item.idenregistremetpatient);
	        list_exam_patient(item.idenregistremetpatient);
	        list_hos_patient(item.idenregistremetpatient);
	        list_soinsam_patient(item.idenregistremetpatient);

	    }).fail(function(error) {
	        console.error('Erreur lors du chargement des données:', error);
	    });
	}

	//-------------------------------------------

	function list_cons_patient(id, page = 1) {
	    var $tableBody = $('#TableC tbody');
	    var $messageDiv = $('#message_TableC');
	    var $tableDiv = $('#div_TableC');
	    var $loaderDiv = $('#div_Table_loaderC');

	    $messageDiv.hide();
	    $tableDiv.hide();
	    $loaderDiv.show();

	    $.getJSON($('#url').attr('content') +`/api/list_cons_patient/${id}?page=${page}`, function(data) {
	        var allCons = data.consultation || [];
	        var pagination = data.pagination || {};
	        var perPage = pagination.per_page || 10;
	        var currentPage = pagination.current_page || 1;

	        $tableBody.empty();

	        if(allCons.length > 0) {
	            $loaderDiv.hide();
	            $messageDiv.hide();
	            $tableDiv.show();

	            $.each(allCons, function(index, item) {
	                var row = `
	                    <tr>
	                        <td>${((currentPage - 1) * perPage) + index + 1}</td>
	                        <td>${item.idconsexterne}</td>
	                        <td>${item.medecin}</td>
	                        <td>${item.specialite}</td>
	                        <td>${formatPriceT(item.montant)} Fcfa</td>
	                        <td>${item.numfac}</td>
	                        <td>${formatDateHeure(item.date)}</td>
	                    </tr>
	                `;
	                $tableBody.append(row);
	            });

	            updatePaginationControlsC(id, pagination);
	        } else {
	            $loaderDiv.hide();
	            $messageDiv.show();
	            $tableDiv.hide();
	        }
	    }).fail(function() {
	        console.error('Erreur lors du chargement des données');
	        $loaderDiv.hide();
	        $messageDiv.show();
	        $tableDiv.hide();
	    });
	}

	function updatePaginationControlsC(id, pagination) {
	    updatePaginationControls(id, pagination, 'pagination-controlsC', list_cons_patient);
	}

	function list_exam_patient(id, page = 1) {
	    var $tableBody = $('#TableED tbody');
	    var $messageDiv = $('#message_TableED');
	    var $tableDiv = $('#div_TableED');
	    var $loaderDiv = $('#div_Table_loaderED');

	    $messageDiv.hide();
	    $tableDiv.hide();
	    $loaderDiv.show();

	    $.getJSON($('#url').attr('content') +`/api/list_examend_patient/${id}?page=${page}`, function(data) {
	        var allExamens = data.examen || [];
	        var pagination = data.pagination || {};
	        var perPage = pagination.per_page || 10;
	        var currentPage = pagination.current_page || 1;

	        $tableBody.empty();

	        if(allExamens.length > 0) {
	            $loaderDiv.hide();
	            $messageDiv.hide();
	            $tableDiv.show();

	            $.each(allExamens, function(index, item) {
	                var row = $(`
	                    <tr>
	                        <td>${((currentPage - 1) * perPage) + index + 1}</td>
	                        <td>${item.typedemande}</td>
	                        <td>${item.nbre}</td>
	                        <td>${formatPriceT(item.montant)} Fcfa</td>
	                        <td>${formatDateHeure(item.date)}</td>
	                        <td>
	                            <div class="d-inline-flex gap-1">
	                                <a class="btn btn-outline-warning btn-sm rounded-5" data-bs-toggle="modal" data-bs-target="#Detailexam" id="detail-${item.idtestlaboimagerie}">
	                                    <i class="ri-archive-2-line"></i>
	                                </a>
	                            </div>
	                        </td>
	                    </tr>
	                `);
	                $tableBody.append(row);

	                $(`#detail-${item.idtestlaboimagerie}`).off('click').on('click', function() {
	                    var $tableBodyP = $('#Tableexam tbody');
	                    var $messageDivP = $('#message_Tableexam');
	                    var $tableDivP = $('#div_Tableexam');
	                    var $loaderDivP = $('#div_Table_loaderexam');

	                    $messageDivP.hide();
	                    $tableDivP.hide();
	                    $loaderDivP.show();

	                    $.getJSON($('#url').attr('content') +`/api/list_facture_exam_d/${item.idtestlaboimagerie}`, function(data) {
	                        var factureds = data.factured;
	                        var sumMontantEx = data.sumMontantEx;

	                        $tableBodyP.empty();

	                        if(factureds.length > 0) {
	                            $loaderDivP.hide();
	                            $messageDivP.hide();
	                            $tableDivP.show();

	                            $.each(factureds, function(index, fitem) {
	                                $tableBodyP.append(`
	                                    <tr>
	                                        <td><h6>${fitem.examen}</h6></td>
	                                        <td><h6>${formatPriceT(fitem.prix)} Fcfa</h6></td>
	                                        <td><h6>${fitem.resultat == null || fitem.resultat == '' ? 'Néant' : fitem.resultat}</h6></td>
	                                    </tr>
	                                `);
	                            });

	                            $tableBodyP.append(`
	                                <tr>
	                                    <td colspan="1">&nbsp;</td>
	                                    <td colspan="2"><h5 class="mt-4 text-success">Total : ${formatPriceT(sumMontantEx)} Fcfa</h5></td>
	                                </tr>
	                                <tr>
	                                    <td colspan="5">
	                                        <h6 class="text-danger">NOTE</h6>
	                                        <p class="small m-0">Montant total de la facture = Montant Total examens + montant du prélevement.</p>
	                                    </td>
	                                </tr>
	                            `);

	                        } else {
	                            $loaderDivP.hide();
	                            $messageDivP.show();
	                            $tableDivP.hide();
	                        }
	                    }).fail(function() {
	                        console.error('Erreur lors du chargement des données');
	                        $loaderDivP.hide();
	                        $messageDivP.show();
	                        $tableDivP.hide();
	                    });
	                });
	            });

	            updatePaginationControlsED(id, pagination);
	        } else {
	            $tableDiv.hide();
	            $loaderDiv.hide();
	            $messageDiv.show();
	        }
	    }).fail(function() {
	        console.error('Erreur lors du chargement des données');
	        $loaderDiv.hide();
	        $tableDiv.hide();
	        $messageDiv.show();
	    });
	}

	function updatePaginationControlsED(id, pagination) {
	    updatePaginationControls(id, pagination, 'pagination-controlsED', list_exam_patient);
	}

	function list_hos_patient(id, page = 1) {
	    var $tableBody = $('#Table_hos tbody');
	    var $messageDiv = $('#message_Table_hos');
	    var $tableDiv = $('#div_Table_hos');
	    var $loaderDiv = $('#div_Table_loader_hos');

	    $messageDiv.hide();
	    $tableDiv.hide();
	    $loaderDiv.show();

	    $.getJSON($('#url').attr('content') +`/api/list_hopital_patient/${id}?page=${page}`, function(data) {
	        var hopitals = data.hopital || [];
	        var pagination = data.pagination || {};
	        var perPage = pagination.per_page || 10;
	        var currentPage = pagination.current_page || 1;

	        $tableBody.empty();

	        if(hopitals.length > 0) {
	            $loaderDiv.hide();
	            $messageDiv.hide();
	            $tableDiv.show();

	            $.each(hopitals, function(index, item) {
	                var row = $(`
	                    <tr>
	                        <td>${((currentPage - 1) * perPage) + index + 1}</td>
	                        <td>${item.type_hospit}</td>
	                        <td>${item.nature_hospit}</td>
	                        <td>${formatDate(item.dateentree)}</td>
	                        <td>${formatDate(item.datesortie)}</td>
	                        <td>${item.medecin}</td>
	                        <td>${item.statut === 'en cours' ? 
	                            `<span class="badge bg-danger">${item.statut}</span>` : 
	                            `<span class="badge bg-success">${item.statut}</span>`}</td>
	                        <td>${formatPriceT(item.montant)} Fcfa</td>
	                        <td>
	                            <div class="d-inline-flex gap-1">
	                                <a class="btn btn-outline-danger btn-sm" id="detail_produit-${item.numhospit}" data-bs-toggle="modal" data-bs-target="#Detail_produit">
	                                    <i class="ri-archive-2-fill"></i>
	                                </a>
	                                <a class="btn btn-outline-warning btn-sm" id="detail_garantie-${item.numhospit}" data-bs-toggle="modal" data-bs-target="#Detail_garantie">
	                                    <i class="ri-eye-line"></i>
	                                </a>
	                            </div>
	                        </td>
	                    </tr>
	                `);
	                $tableBody.append(row);

	                // Click produit
	                $(`#detail_produit-${item.numhospit}`).off('click').on('click', function() {
	                    var $tableBodyP = $('#TableP tbody');
	                    var $messageDivP = $('#message_TableP');
	                    var $tableDivP = $('#div_TableP');
	                    var $loaderDivP = $('#div_Table_loaderP');

	                    $messageDivP.hide();
	                    $tableDivP.hide();
	                    $loaderDivP.show();

	                    $.getJSON($('#url').attr('content') +`/api/list_facture_hos_d/${item.numhospit}`, function(data) {
	                        var factureds = data.factured;
	                        $tableBodyP.empty();
	                        if(factureds.length > 0) {
	                            $loaderDivP.hide();
	                            $messageDivP.hide();
	                            $tableDivP.show();

	                            var total = 0;
	                            $.each(factureds, function(index, fitem) {
	                                var prixTotal = parseFloat(fitem.prix_t) || 0;
	                                var prixUnitaire = parseFloat(fitem.prix_u) || 0;
	                                var quantite = parseInt(fitem.quantite) || 0;
	                                total += prixTotal;

	                                var row = $(`
	                                    <tr>
	                                        <td><h6>${fitem.name}</h6></td>
	                                        <td><h6>${formatPriceT(prixUnitaire)} Fcfa</h6></td>
	                                        <td><h6>${quantite}</h6></td>
	                                        <td><h6>${formatPriceT(prixTotal)} Fcfa</h6></td>
	                                    </tr>
	                                `);
	                                $tableBodyP.append(row);
	                            });

	                            $tableBodyP.append(`
	                                <tr>
	                                    <td colspan="2">&nbsp;</td>
	                                    <td colspan="2"><h5 class="mt-4 text-success">Total : ${formatPriceT(total)} Fcfa</h5></td>
	                                </tr>
	                                <tr>
	                                    <td colspan="4">
	                                        <h6 class="text-danger">NOTE</h6>
	                                        <p class="small m-0">
	                                            Le Montant Total des produits utilisés
	                                            seront ajouter au montant total de la facture.
	                                        </p>
	                                    </td>
	                                </tr>
	                            `);
	                        } else {
	                            $loaderDivP.hide();
	                            $messageDivP.show();
	                            $tableDivP.hide();
	                        }
	                    }).fail(function() {
	                        console.error('Erreur lors du chargement des données');
	                        $loaderDivP.hide();
	                        $messageDivP.show();
	                        $tableDivP.hide();
	                    });
	                });

	                // Click garantie
	                $(`#detail_garantie-${item.numhospit}`).off('click').on('click', function() {
	                    var $tableBodyP = $('#TableP_garantie tbody');
	                    var $messageDivP = $('#message_TableP_garantie');
	                    var $tableDivP = $('#div_TableP_garantie');
	                    var $loaderDivP = $('#div_Table_loaderP_garantie');

	                    $messageDivP.hide();
	                    $tableDivP.hide();
	                    $loaderDivP.show();

	                    $.getJSON($('#url').attr('content') +`/api/list_facture_hos_d2/${item.numhospit}`, function(data) {
	                        var factureds = data.factured;
	                        $tableBodyP.empty();
	                        if(factureds.length > 0) {
	                            $loaderDivP.hide();
	                            $messageDivP.hide();
	                            $tableDivP.show();

	                            var total = 0;
	                            $.each(factureds, function(index, fitem) {
	                                var prixTotal = parseFloat(fitem.prix) || 0;
	                                var prixAssurance = parseFloat(fitem.prix_ass) || 0;
	                                var prixPatient = parseFloat(fitem.prix_pat) || 0;
	                                total += prixPatient;

	                                var row = $(`
	                                    <tr>
	                                        <td><h6>${fitem.name}</h6></td>
	                                        <td><h6>${formatPriceT(prixTotal)} Fcfa</h6></td>
	                                        <td><h6>${formatPriceT(prixAssurance)} Fcfa</h6></td>
	                                        <td><h6>${formatPriceT(prixPatient)} Fcfa</h6></td>
	                                        <td><h6>${fitem.numfac == null ? 'Néant' : fitem.numfac}</h6></td>
	                                    </tr>
	                                `);
	                                $tableBodyP.append(row);
	                            });

	                            $tableBodyP.append(`
	                                <tr>
	                                    <td colspan="1">&nbsp;</td>
	                                    <td colspan="3"><h5 class="mt-4 text-success">Total : ${formatPriceT(total)} Fcfa</h5></td>
	                                    <td colspan="1">&nbsp;</td>
	                                </tr>
	                                <tr>
	                                    <td colspan="4">
	                                        <h6 class="text-danger">NOTE</h6>
	                                        <p class="small m-0">
	                                            Le Montant Total des produits utilisés
	                                            seront ajouter au montant total de la facture.
	                                        </p>
	                                    </td>
	                                </tr>
	                            `);
	                        } else {
	                            $loaderDivP.hide();
	                            $messageDivP.show();
	                            $tableDivP.hide();
	                        }
	                    }).fail(function() {
	                        console.error('Erreur lors du chargement des données');
	                        $loaderDivP.hide();
	                        $messageDivP.show();
	                        $tableDivP.hide();
	                    });
	                });

	            });

	            updatePaginationControlshos(id, pagination);

	        } else {
	            $loaderDiv.hide();
	            $messageDiv.show();
	            $tableDiv.hide();
	        }

	    }).fail(function() {
	        console.error('Erreur lors du chargement des données');
	        $loaderDiv.hide();
	        $messageDiv.show();
	        $tableDiv.hide();
	    });
	}

	function updatePaginationControlshos(id, pagination) {
	    updatePaginationControls(id, pagination, 'pagination-controls-hos', list_hos_patient);
	}

	function list_soinsam_patient(id, page = 1) {
	    var $tableBody = $('#Tablesoinsam tbody');
	    var $messageDiv = $('#message_Tablesoinsam');
	    var $tableDiv = $('#div_Tablesoinsam');
	    var $loaderDiv = $('#div_Table_loadersoinsam');

	    $messageDiv.hide();
	    $tableDiv.hide();
	    $loaderDiv.show();

	    $.getJSON($('#url').attr('content') +`/api/list_soinsam_patient/${id}?page=${page}`, function(data) {
	        var spatients = data.spatient || [];
	        var pagination = data.pagination || {};
	        var perPage = pagination.per_page || 10;
	        var currentPage = pagination.current_page || 1;

	        $tableBody.empty();

	        if(spatients.length > 0) {
	            $loaderDiv.hide();
	            $messageDiv.hide();
	            $tableDiv.show();

	            $.each(spatients, function(index, item) {
	                var row = $(`
	                    <tr>
	                        <td>${((currentPage - 1) * perPage) + index + 1}</td>
	                        <td>${item.nbre_soins}</td>
	                        <td>${item.nbre_produit}</td>
	                        <td>${formatPriceT(item.montant)} Fcfa</td>
	                        <td>${formatDateHeure(item.date_soin)}</td>
	                        <td>
	                            <div class="d-inline-flex gap-1">
	                                <a class="btn btn-outline-danger btn-sm" id="detail_produit-${item.id_soins}" data-bs-toggle="modal" data-bs-target="#Detail_produit_s">
	                                    <i class="ri-archive-2-fill"></i>
	                                </a>
	                            </div>
	                        </td>
	                    </tr>
	                `);
	                $tableBody.append(row);

	                // Click sur produit
	                $(`#detail_produit-${item.id_soins}`).off('click').on('click', function() {
	                    var $tableBodyP = $('#TableP tbody');
	                    var $tableBodyProdP = $('#TableProdP tbody');
	                    var $messageDivP = $('#message_TableP');
	                    var $tableDivP = $('#div_TableP');
	                    var $tableDivProdP = $('#div_TableProdP');
	                    var $loaderDivP = $('#div_Table_loaderP');

	                    $messageDivP.hide();
	                    $tableDivP.hide();
	                    $tableDivProdP.hide();
	                    $loaderDivP.show();

	                    $.getJSON($('#url').attr('content') +`/api/detail_soinam/${item.id_soins}`, function(data) {
	                        if(data.existep) {
	                            var modal = bootstrap.Modal.getInstance($('#Detail_produit')[0]);
	                            modal.hide();
	                            showAlert("ALERT", "Une erreur s'est produite, veuillez réessayer plus tard.", "error");
	                            return;
	                        }

	                        var patient = data.patient;
	                        var soins = data.soins;
	                        var produit = data.produit;
	                        var total = patient.prototal + patient.stotal;

	                        $tableBodyP.empty();
	                        $tableBodyProdP.empty();

	                        if(soins.length > 0 || produit.length > 0) {
	                            $loaderDivP.hide();
	                            $messageDivP.hide();
	                            $tableDivP.show();
	                            $tableDivProdP.show();

	                            // Remplir tableau soins
	                            $.each(soins, function(i, soin) {
	                                $tableBodyP.append(`
	                                    <tr>
	                                        <td><h6>${soin.libelle_soins}</h6></td>
	                                        <td><h6>${formatPriceT(soin.price)} Fcfa</h6></td>
	                                    </tr>
	                                `);
	                            });

	                            $tableBodyP.append(`
	                                <tr>
	                                    <td>&nbsp;</td>
	                                    <td><h5 class="mt-4 text-success">Total Soins : ${formatPriceT(patient.stotal)} Fcfa</h5></td>
	                                </tr>
	                            `);

	                            // Remplir tableau produits
	                            $.each(produit, function(i, p) {
	                                $tableBodyProdP.append(`
	                                    <tr>
	                                        <td><h6>${p.name}</h6></td>
	                                        <td><h6>${formatPriceT(p.priceu)} Fcfa</h6></td>
	                                        <td><h6>${p.qte}</h6></td>
	                                        <td><h6>${formatPriceT(p.price)} Fcfa</h6></td>
	                                    </tr>
	                                `);
	                            });

	                            $tableBodyProdP.append(`
	                                <tr>
	                                    <td colspan="2">&nbsp;</td>
	                                    <td colspan="2"><h5 class="mt-4 text-success">Total Produits : ${formatPriceT(patient.prototal)} Fcfa</h5></td>
	                                </tr>
	                                <tr>
	                                    <td colspan="4">
	                                        <h6 class="text-danger">TOTAL : ${formatPriceT(total)} Fcfa</h6>
	                                        <p class="small m-0">
	                                            Le Montant Total des produits utilisés
	                                            seront ajoutés au montant total des soins.
	                                        </p>
	                                    </td>
	                                </tr>
	                            `);

	                        } else {
	                            $loaderDivP.hide();
	                            $messageDivP.show();
	                            $tableDivP.hide();
	                            $tableDivProdP.hide();
	                        }

	                    }).fail(function() {
	                        console.error('Erreur lors du chargement des données');
	                        $loaderDivP.hide();
	                        $messageDivP.show();
	                        $tableDivP.hide();
	                        $tableDivProdP.hide();
	                    });
	                });

	            });

	            updatePaginationControlssoinsam(id, pagination);

	        } else {
	            $loaderDiv.hide();
	            $messageDiv.show();
	            $tableDiv.hide();
	        }

	    }).fail(function() {
	        console.error('Erreur lors du chargement des données');
	        $loaderDiv.hide();
	        $messageDiv.show();
	        $tableDiv.hide();
	    });
	}

	function updatePaginationControlssoinsam(id, pagination) {
	    updatePaginationControls(id, pagination, 'pagination-controlssoinsam', list_soinsam_patient);
	}

	function updatePaginationControls(id, pagination, containerId, callbackFunction) {
	    var $paginationDiv = $('#' + containerId);
	    $paginationDiv.empty();

	    var $paginationWrapper = $('<ul>', { class: 'pagination justify-content-center' });

	    // Previous
	    if (pagination.current_page > 1) {
	        $('<li>', { class: 'page-item' })
	            .append('<a class="page-link" href="#">Precédent</a>')
	            .click(function(e) {
	                e.preventDefault();
	                callbackFunction(id, pagination.current_page - 1);
	            })
	            .appendTo($paginationWrapper);
	    } else {
	        $('<li>', { class: 'page-item disabled' })
	            .append('<a class="page-link" href="#">Precédent</a>')
	            .appendTo($paginationWrapper);
	    }

	    var totalPages = pagination.last_page;
	    var currentPage = pagination.current_page;
	    var maxVisiblePages = 5;
	    var startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
	    var endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
	    if (endPage - startPage < maxVisiblePages - 1) startPage = Math.max(1, endPage - maxVisiblePages + 1);

	    // Page numbers
	    for (var i = startPage; i <= endPage; i++) {
	        $('<li>', { class: `page-item ${i === currentPage ? 'active' : ''}` })
	            .append(`<a class="page-link" href="#">${i}</a>`)
	            .click((function(page) {
	                return function(e) {
	                    e.preventDefault();
	                    callbackFunction(id, page);
	                };
	            })(i))
	            .appendTo($paginationWrapper);
	    }

	    // Ellipsis + last page
	    if (endPage < totalPages) {
	        $('<li>', { class: 'page-item disabled' })
	            .append('<a class="page-link" href="#">...</a>')
	            .appendTo($paginationWrapper);
	        $('<li>', { class: 'page-item' })
	            .append(`<a class="page-link" href="#">${totalPages}</a>`)
	            .click(function(e) {
	                e.preventDefault();
	                callbackFunction(id, totalPages);
	            })
	            .appendTo($paginationWrapper);
	    }

	    // Next
	    if (pagination.current_page < pagination.last_page) {
	        $('<li>', { class: 'page-item' })
	            .append('<a class="page-link" href="#">Suivant</a>')
	            .click(function(e) {
	                e.preventDefault();
	                callbackFunction(id, pagination.current_page + 1);
	            })
	            .appendTo($paginationWrapper);
	    } else {
	        $('<li>', { class: 'page-item disabled' })
	            .append('<a class="page-link" href="#">Suivant</a>')
	            .appendTo($paginationWrapper);
	    }

	    $paginationDiv.append($paginationWrapper);
	}

});