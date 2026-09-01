$(document).ready(function () { 

    select_fondtrans_magasin('#magasin_id_fondTrans', user.magasin_id);
    numberTel("#montant");

    $('#solde').hide();
    $('#btn_fermer').hide();
    $('#btn_ouvert').hide();
    $('#chargement').hide();
    $('#div_operation').hide();
    $('#message').hide();

    Verfication_statut();

    $('#Date1').on('change', function() {
        const date1 = $(this).val();
        
        if (date1) {
            $('#Date2').val(date1);
            $('#Date2').attr('min', date1);
        }
    });

    $('#Date2').on('change', function() {
        const date2 = $(this).val();
        const date1 = $('#Date1').val();

        if (date2 && date1 && new Date(date2) < new Date(date1)) {
            $(this).val(date1);
        }
    });

    $('#montant').on('input', function() {

        let rawValue = 0;
        rawValue = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
        $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $('#montant_fondTrans').on('input', function() {

        let rawValue = 0;
        rawValue = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
        $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $("#formulaire_operation").on("submit", function (event) {
        event.preventDefault();

        // Récupération valeurs du formulaire
        let motif = $("#motif").val().trim();
        let montant = $("#montant").val();
        let type = $("#type").val().trim();
        let dateop = $("#dateop").val();

        if (montant <= 0) {
            showAlert("Alert","Le montant de l'opération doit être superieux a 0 Fcfa","warning");
            return;
        }

        if (!montant || !type || !dateop || !motif) {
            showAlert("Alert","Veuillez bien remplir tout le formulaire vous plaît !!!","warning");
            return;
        }

        const uniqueId = "modalValide_" + Date.now();

        const modalBody = `
            <div class="modal fade" tabindex="-1" id="${uniqueId}">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="card">
                            <div class="card-inner">
                                <div class="team">
                                    <div class="user-card user-card-s2">
                                        <div class="user-avatar lg">
                                            <img height="80px" width="80px" class="rounded-pill border border-1" src="assets/images/factures.jpg" alt="">
                                        </div>
                                        <div class="user-info">
                                            <h6>Veuillez bien vérifier les informations saisies</h6>
                                        </div>
                                    </div>
                                    <div class="alert alert-fill alert-warning alert-dismissible alert-icon mt-2">
                                        <em class="icon ni ni-info"></em> 
                                        <strong>INFO : </strong> Cette opération ne sera plus modifiable après validation.
                                    </div>
                                    <div class="p-2" style="max-height: 400px;" data-simplebar >
                                        <ul class="team-info">
                                            <li><span>Type</span><span id="v_type_op"></span></li>
                                            <li><span>Motif</span><span id="v_motif_op"></span></li>
                                            <li><span>Montant</span><span id="v_montant_op"></span></li>
                                            <li><span>Date d'opération</span><span id="v_date_op"></span></li>
                                        </ul>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group text-center">
                                            <a href="#" class="btn btn-md btn-danger btn-cancel" data-bs-dismiss="modal">
                                                <span>Annuler</span>
                                            </a>
                                            <a href="#" class="btn btn-md btn-outline-success btn-validate" data-unique="${uniqueId}">
                                                <span>Validé</span>
                                                <em class="icon ni ni-check-circle"></em>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalBody);

        const modal = new bootstrap.Modal(document.getElementById(uniqueId));
        modal.show();

        // Nettoyage après fermeture
        $(`#${uniqueId}`).on("hidden.bs.modal", function () {
            modal.hide();
            $(this).remove();
        });

        // Action "Annuler" → fermer et supprimer le modal
        $(`#${uniqueId} .btn-cancel`).on("click", function (e) {
            e.preventDefault();
            modal.hide();
            $(`#${uniqueId}`).on("hidden.bs.modal", function () {
                $(this).remove(); // supprime le DOM après fermeture
            });
        });

        let solde;
        if (type === 'entree') {
            solde = '+ ' + montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Fcfa';
        } else if (type === 'sortie') {
            solde = '- ' + montant.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Fcfa';
        }

        let op;
        if (type === 'entree') {
            op = "Entrer d'argent";
        } else if (type === 'sortie') {
            op = "Sortie d'argent";
        }

        // Injecter les données dans le bon modal
        $(`#${uniqueId} #v_type_op`).text(op);
        $(`#${uniqueId} #v_motif_op`).text(motif);
        $(`#${uniqueId} #v_montant_op`).text(solde);
        $(`#${uniqueId} #v_date_op`).text('Le ' + formatDate(dateop));

        $(`#${uniqueId} .btn-validate`).on("click", function (e) {

            // Supprimer le modal
            modal.hide();
            $(`#${uniqueId}`).remove();

            // Ajouter le préchargeur
            preloader('start');

            $.ajax({
                url: $('#url').attr('content') + "/api/insert_operation/" + user.magasin_id + '/' + user.login,
                method: "GET",
                data: {
                    motif: motif,
                    montant: montant.replace(/[^0-9]/g, '') || 0,
                    type: type,
                    dateop: dateop,
                },
                success: function (response) {
                    preloader('end');

                    if (response.success) {

                        Verfication_solde();
                        rest_form();
                        list_operation_all();
                        showAlert("Succès", "Opération éffectuée", "success");

                    } else if (response.montant) {
                        showAlert("Alert", "Le montant saisir est supérieur au solde actuel de la caisse", "warning");
                    } else if (response.caisser_fermer) {
                        showAlert("Alert", "La caisse est actuellement fermer", "info");
                    } else if (response.error) {
                        showAlert("Alert", "Echec de l\'opération", "error");
                        console.log(response.message);
                    }
                },
                error: function () {
                    preloader('end');
                    showAlert("Erreur", "Erreur est survenu, veuillez réessayer.", "error");
                    console.log(response.message);
                },
            });
        });
    });

    $("#formulaire_fondTrans").on("submit", function (event) {
        event.preventDefault();

        let montant = $("#montant_fondTrans").val().replace(/[^0-9]/g, '') || 0;
        let dateop = $("#dateop_fondTrans").val().trim();
        let magasin_id = $("#magasin_id_fondTrans").val().trim();

        if (montant <= 0) {
            showAlert("Alert","Le montant de transfert doit être superieux a 0 Fcfa","warning");
            return;
        }

        if (!magasin_id) {
            showAlert("Alert","Veuillez selectionner la magasin qui va recevoir la recette s'il vous plaît !!!","warning");
            return;
        }

        if (!dateop) {
            showAlert("Alert","Veuillez saisir la date s'il vous plaît !!!","warning");
            return;
        }

        // Ajouter le préchargeur
        preloader('start');

        let selectedOption = $("#magasin_id_fondTrans").find(":selected");

        $.ajax({
            url: $('#url').attr('content') + "/api/insert_fondTrans/" + user.magasin_id + '/' + magasin_id + '/' + user.login,
            method: "GET",
            data: {
                montant: montant,
                dateop: dateop,
                nom_agence: user_magasin,
                nom_agence_receved: selectedOption.text(),
            },
            success: function (response) {
                preloader('end');

                if (response.success) {

                    Verfication_solde();
                    rest_form_fondTrans();
                    list_operation_all();
                    showAlert("Succès", "Opération éffectuée", "success");

                } else if (response.montant) {
                    showAlert("Alert", "Le montant saisir est supérieur au solde actuel de la caisse", "warning");
                } else if (response.caisser_fermer) {
                    showAlert("Alert", "La caisse est actuellement fermer", "info");
                } else if (response.error) {
                    showAlert("Alert", "Echec de l\'opération", "error");
                    console.log(response.message);
                }
            },
            error: function () {
                preloader('end');
                showAlert("Erreur", "Erreur est survenu, veuillez réessayer.", "error");
                console.log(response.message);
            },
        });
    });

    function rest_form()
    {
        $("#motif").val(null);
        $("#montant").val(0);
        $("#type").val(null);

        let now = new Date();
        let formattedDate = now.getFullYear() + "-" +
                        String(now.getMonth() + 1).padStart(2, '0') + "-" +
                        String(now.getDate()).padStart(2, '0');
                        
        $("#dateop").val(formattedDate);
    }

    function rest_form_fondTrans()
    {

        $("#montant_fondTrans").val(0);
        $("#magasin_id_fondTrans").val(null).trigger('change.select2');

        let now = new Date();
        let formattedDate = now.getFullYear() + "-" +
                        String(now.getMonth() + 1).padStart(2, '0') + "-" +
                        String(now.getDate()).padStart(2, '0');
                        
        $("#dateop_fondTrans").val(formattedDate);
    }

});