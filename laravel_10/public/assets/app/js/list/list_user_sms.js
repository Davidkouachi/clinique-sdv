$(document).ready(function () {

    list_user_sms();
    numberTel("#ss_tel");
    numberTelLimit("#ss_tel");

    // $('#sendSMS').on('shown.bs.modal', function () {
    //     select_type_message("#ss_type");
    // });
    // $('#sendSMSmultipleModal').on('shown.bs.modal', function () {
    //     select_type_message("#sm_type");
    // });

    let selectedItems = new Set();

    function list_user_sms() {
        $.ajax({
            url: $('#url').attr('content') + '/api/list_client_all',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const clients = data.data;

                // Détruire l'instance DataTable existante (si elle existe)
                const table = $('.table_client');
                if ($.fn.DataTable.isDataTable(table)) {
                    table.DataTable().destroy();
                }

                // Effacer le contenu du tableau
                table.find("tbody").empty();

                let row = '';

                if (clients.length > 0) {

                    // const table = $('.table_client');
                    // table.DataTable().destroy();

                    $.each(clients, function(index, item) {

                        row += `
                            <tr class="nk-tb-item">
                                <td class="nk-tb-col">
                                    <div class="custom-control custom-control-sm custom-checkbox notext">
                                        <input type="checkbox" value="${item.cel}" class="custom-control-input" id="pid-${index + 1}">
                                        <label class="custom-control-label" for="pid-${index + 1}"></label>
                                    </div>
                                </td>
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
                                                ${item.nomprenom}
                                            </span>
                                            <span>
                                                Id : ${item.matricule}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">+225 ${item.cel}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${formatDateHeure(item.dateenregistre)}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <span class="tb-amount">${timeAgo(item.dateenregistre)}</span>
                                </td>
                                <td class="nk-tb-col">
                                    <ul class="nk-tb${tableBtnPosition == 1 ? `-actions` : ``} gx-1 d-flex">
                                        <li class="nk-tb${tableBtnPosition == 1 ? `-action-hidden` : ``}" >
                                            <a  href="#"
                                                class="btn btn-trigger btn-icon btn-sendsms text-warning"
                                                title="Envoi d'sms"
                                                data-matricule="${item.matricule}" 
                                                data-np="${item.nomprenom}"
                                                data-tel="${item.cel}" 
                                            >
                                                <em class="icon ni ni-send"></em>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        `;

                    });

                    table.find("tbody").append(row);

                    initializeDataTable(".table_client", { responsive: { details: true } });
                } else {
                    initializeDataTable(".table_client", { responsive: { details: true } });
                }

                updateCounter();
            },
            error: function() {
                initializeDataTable(".table_client", { responsive: { details: true } });
                updateCounter();
            }
        });

        $('.table_client').off('click', '.btn-sendsms').on('click', '.btn-sendsms', function (event) {
            event.preventDefault();

            $('#sendSMS').remove();

            $('body').append(`
                <div class="modal fade" id="sendSMS" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content bg-white">
                            <div class="modal-body">
                                <form class="form-validate is-alter">
                                    <div class="form-group">
                                        <label class="form-label">Nom et Prénoms</label>
                                        <div class="form-control-wrap">
                                            <input readonly type="text" class="form-control" id="ss_np">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Contact</label>
                                        <div class="form-control-wrap">
                                            <input type="tel" class="form-control" id="ss_tel">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">
                                            Type message
                                        </label>
                                        <div class="form-control-wrap">
                                            <select id="ss_type" class="form-select js-select2" data-search="on" data-placeholder="Selectionnez un type">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Message</label>
                                        <div class="form-control-wrap">
                                            <textarea class="form-control no-resize" id="ss_message" maxlength="100"></textarea>
                                        </div>
                                        <small id="char_count">0/100</small>
                                    </div>
                                    <div class="form-group text-center">
                                        <a id="btn_sendSMS" data-bs-dismiss="modal" aria-label="Close" class="btn btn-dim btn-md btn-outline-warning">
                                            <span>Envoyer</span>
                                            <em class="icon ni ni-send" ></em>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            // Affichage du modal
            $('#sendSMS').modal('show');

            select_type_message("#ss_type");

            $('#ss_type').on('change', function() {

                const selectedOption = $(this).find(':selected');
                const message = selectedOption.data('message');

                if (message) {
                    $('#ss_message').val(message).trigger('input');
                } else {
                    $('#ss_message').val('').trigger('input');
                }
            });

            const matricule = $(this).data('matricule');
            const np = $(this).data('np');
            const contact = $(this).data('tel');

            // $('#d_matricule').text(matricule);
            $('#ss_np').val(np);
            $('#ss_tel').val(contact);
            $('#ss_message').val(null);

            $('#btn_sendSMS').on('click', sendSMS);
 
        });  
    }

    function updateCounter() {
        let total = $(".table_client").DataTable().rows().count(); // Nombre total d'entrées dans DataTable
        let selected = selectedItems.size; // Nombre d'éléments sélectionnés globalement

        $("#selectedCount").text(selected);
        $("#totalCount").text(total);

        // Afficher ou masquer la balise <p> en fonction de la sélection
        if (total > 0) {
            $("#selectionStatus").show();
            $('#selectionStatusCheckbox').show();
        } else {
            $("#selectionStatus").hide();
            $('#selectionStatusCheckbox').hide();
        }

        // Afficher ou masquer le bouton "Envoyer" en fonction de la sélection
        if (selected > 0) {
            $("#btn_sendSMSmultiple").show(); // Afficher le bouton si des éléments sont sélectionnés
        } else {
            $("#btn_sendSMSmultiple").hide(); // Masquer le bouton si rien n'est sélectionné
        }
    }

    $(document).on("change", ".table_client tbody input[type='checkbox']", function () {
        let itemId = $(this).attr("id"); // ID unique de chaque checkbox

        if ($(this).is(":checked")) {
            selectedItems.add(itemId); // Ajouter l'élément sélectionné
        } else {
            selectedItems.delete(itemId); // Retirer l'élément si décoché
        }

        updateCounter();
    });

    $(document).on("change", "#pid-all", function () {
        let table = $(".table_client").DataTable(); // Obtenir l'instance de DataTable
        let allCheckboxes = $(".table_client tbody input[type='checkbox']"); // Récupérer toutes les cases à cocher

        if (this.checked) {
            // Si la case 'tout sélectionner' est cochée, ajouter tous les éléments du tableau au Set
            table.rows().every(function () { // Parcours de toutes les lignes, visibles et non visibles
                let itemId = this.node().querySelector('input[type="checkbox"]').id;
                selectedItems.add(itemId);
            });
            allCheckboxes.prop("checked", true); // Cocher toutes les cases à cocher
        } else {
            // Si la case 'tout sélectionner' est décochée, enlever tous les éléments du Set
            table.rows().every(function () {
                let itemId = this.node().querySelector('input[type="checkbox"]').id;
                selectedItems.delete(itemId);
            });
            allCheckboxes.prop("checked", false); // Décochez toutes les cases à cocher
        }

        updateCounter();
    });

    $('.table_client').on('draw.dt', function () {
        $(".table_client tbody input[type='checkbox']").each(function () {
            let itemId = $(this).attr("id");
            $(this).prop("checked", selectedItems.has(itemId)); // Mettre à jour l'état de chaque case
        });
    });

    $(document).on('click', '#btn_sendSMSmultiple', function () {

        $('#sendSMSmultipleModal').remove();

        $('body').append(`
            <div class="modal fade" id="sendSMSmultipleModal" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-white">
                        <div class="modal-body">
                            <form class="">
                                <div class="form-group">
                                    <label class="form-label">
                                        Type message
                                    </label>
                                    <div class="form-control-wrap">
                                        <select id="sm_type" class="form-select js-select2" data-search="on" data-placeholder="Selectionnez un type">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Message</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control no-resize" id="sm_message" maxlength="100"></textarea>
                                    </div>
                                    <small id="char_count_muktiple">0/100</small>
                                </div>
                                <div class="form-group text-center">
                                    <a data-bs-dismiss="modal" aria-label="Close" id="btn_sendSMSMULTIPLE" class="btn btn-dim btn-md btn-outline-warning">
                                        <span>Envoyer</span>
                                        <em class="icon ni ni-send" ></em>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Affichage du modal
        $('#sendSMSmultipleModal').modal('show');

        select_type_message("#sm_type");

        $('#sm_type').on('change', function() {

            const selectedOption = $(this).find(':selected');
            const message = selectedOption.data('message');

            if (message) {
                $('#sm_message').val(message).trigger('input');
            } else {
                $('#sm_message').val('').trigger('input');
            }
        });

        // Récupérer toutes les cases à cocher sélectionnées
        const selectedCheckboxes = $(".table_client tbody input[type='checkbox']:checked");

        // Créer un tableau pour stocker les données des lignes sélectionnées
        let selectedClients = [];

        // Parcourir les cases sélectionnées et récupérer les données associées
        selectedCheckboxes.each(function() {
            const row = $(this).closest('tr'); // Trouver la ligne parent de la case cochée
            const matricule = row.find('td').eq(2).text(); // Récupérer la valeur du matricule
            const nom = row.find('td').eq(3).text().trim(); // Récupérer la valeur du nom et prénom
            const tel = row.find('td').eq(4).text().trim().replace(/^\+225|\s+/g, '');// Récupérer le numéro de téléphone

            selectedClients.push({ nom, tel });
        });

        $(document).on('click', '#btn_sendSMSMULTIPLE', function () {
            event.preventDefault();

            let message = $('#sm_message').val();

            if (!message.trim()) {
                showAlert("Alert", "Veuillez saisir le message s'il vous plaît.", "info");
                return false;
            }

            smsSenderMultipleAsync(selectedClients, message);
        });
    });

    function deselectAll() {
        // Désélectionner toutes les cases à cocher dans le tableau
        $(".table_client tbody input[type='checkbox']").prop("checked", false);

        // Si vous avez besoin de mettre à jour les éléments sélectionnés dans le Set ou autre variable
        selectedItems.clear();  // Supposons que vous utilisez un Set pour suivre les éléments sélectionnés
        updateCounter();  // Mise à jour du compteur ou de l'affichage de sélection
    }

    var maxLength = 100;
    
    $('#ss_message').on('input', function() {
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        // Met à jour le décompte des caractères
        $('#char_count').text(currentLength + '/' + maxLength);
        
        // Optionnel : Désactive l'écriture si le nombre de caractères est atteint
        if (remaining <= 0) {
            $(this).val($(this).val().substring(0, maxLength)); // Empêche l'ajout de plus de caractères
        }
    });

    $('#sm_message').on('input', function() {
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        // Met à jour le décompte des caractères
        $('#char_count_muktiple').text(currentLength + '/' + maxLength);
        
        // Optionnel : Désactive l'écriture si le nombre de caractères est atteint
        if (remaining <= 0) {
            $(this).val($(this).val().substring(0, maxLength)); // Empêche l'ajout de plus de caractères
        }
    });

    $('#type_message').on('input', function() {
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        // Met à jour le décompte des caractères
        $('#char_count_type').text(currentLength + '/' + maxLength);
        
        // Optionnel : Désactive l'écriture si le nombre de caractères est atteint
        if (remaining <= 0) {
            $(this).val($(this).val().substring(0, maxLength)); // Empêche l'ajout de plus de caractères
        }
    });

    function sendSMS()
    {
        event.preventDefault();

        const contacts = [];

        let nom = $('#ss_np').val();
        let contact = $('#ss_tel').val();
        let message = $('#ss_message').val();

        if (contact.length !== 10 ) {
            showAlert("Alert", "Contact incomplet.", "info");
            return false;
        }

        if (!message.trim()) {
            showAlert("Alert", "Veuillez saisir le message s'il vous plaît.", "info");
            return false;
        }

        contacts.push({
            nom: nom,
            tel: contact,
        });

        smsSenderMultipleAsync(contacts, message);
    }    

    function smsSenderMultipleAsync(contacts, message) {

        if (!Array.isArray(contacts) || contacts.length === 0) {
            showAlert('Erreur', 'Aucun contact valide à traiter.', 'error');
            return;
        }

        // Affichage du préloader
        preloader('start');

        // Paramètres API (récupérés via Laravel)

        $.ajax({
            url: url_base + '/api/smsSender',
            method: 'GET',
            success: function(response) {

                if (response.success) {

                    const params = {
                        username: response.username,
                        password: response.password,
                        serviceid: response.serviceid,
                        sender: response.sender,
                    };

                    // Fonction d'envoi d'un SMS pour un contact
                    const sendSMS = (contact) => {
                        if (!contact.tel || typeof contact.tel !== 'string') {
                            return Promise.resolve({ contact, success: false, error: 'Numéro invalide' });
                        }

                        const queryString = new URLSearchParams({
                            ...params,
                            msisdn: `+225${contact.tel.trim()}`, // Nettoyage du numéro
                            msg: message.trim(),
                        }).toString();

                        return fetch(`https://api-public-2.mtarget.fr/messages?${queryString}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                        })
                            .then(response => {
                                if (!response.ok) throw new Error(`Erreur HTTP: ${response.status}`);
                                return response.json();
                            })
                            .then(data => ({ contact, success: true, data }))
                            .catch(error => ({ contact, success: false, error }));
                    };

                    // Exécution des requêtes en parallèle
                    Promise.all(contacts.map(sendSMS))
                        .then(results => {
                            preloader('end'); // Supprimer le préloader
                            deselectAll();

                            // Regroupement des résultats
                            const successContacts = results.filter(r => r.success).map(r => r.contact);
                            const failedContacts = results.filter(r => !r.success);

                            // Affichage des résultats
                            if (successContacts.length > 0) {
                                showAlert('Succès', `${successContacts.length}/${contacts.length} SMS envoyés avec succès.`, 'success');
                            }

                            if (failedContacts.length > 0) {
                                console.warn("Erreurs SMS:", failedContacts); // Pour debug dans la console
                                showAlert('Alerte', `${failedContacts.length}/${contacts.length} SMS n'ont pas été envoyés.`, 'warning');
                            }

                            if (contacts.length === 0 || failedContacts.length === contacts.length) {
                                showAlert('Alerte', `Échec total de l'envoi des SMS.`, 'error');
                            }
                        })
                        .catch(error => {
                            console.error("Erreur réseau:", error);
                            preloader('end');
                            showAlert('Alerte', `Une erreur est survenue lors de l'envoi des SMS.`, 'danger');
                        });
            
                } else {
                    preloader('end');
                    showAlert('Alerte', `Échec total de l'envoi des sms.`, 'info');
                    return;
                }
            },
            error: function() {
                preloader('end');
                showAlert('Alerte', `Échec total de l'envoi des sms.`, 'error');
            }
        });
        
    }

});