$(document).ready(function () {

    $('#solde').hide();
    $('#btn_fermer').hide();
    $('#btn_ouvert').hide();
    $('#chargement').hide();
    $('#div_operation').hide();
    $('#message').hide();

    Verfication_statut();
    select_client_vente('#client');

    numberTel("#montant_verser");

    $('#montant_verser').on('input', function() {
        let rawValue = parseInt($(this).val().replace(/[^0-9]/g, '')) || 0;
        let montant_payer = parseInt($('#montant_payer').val().replace(/[^0-9]/g, '')) || 0;

        // Vérifier si le montant versé est supérieur au montant à payer
        if (rawValue > montant_payer) {
            showAlert("Alert", "Le montant versé ne doit pas être supérieur au montant à payer.", "info");
            rawValue = montant_payer; // Remplace la valeur par le montant à payer
        }

        // Met à jour le champ montant versé avec la valeur formatée
        $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

        // Calcul du montant restant
        let calcul = Math.floor(montant_payer - rawValue);
        $('#montant_restant').val(calcul.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $("#btn_vers").on("click", function (event) {
        event.preventDefault();

        let montant_payer = parseInt($("#montant_payer").val().replace(/[^0-9]/g, '')) || 0;
        let montant_verser = parseInt($("#montant_verser").val().replace(/[^0-9]/g, '')) || 0;
        let montant_restant = parseInt($("#montant_restant").val().replace(/[^0-9]/g, '')) || 0;
        let obs = $("#obs");
        let date_livraison = $("#date_livraison");

        let code = $("#input_code").val();
        let matricule = $("#input_matricule").val();

        if (montant_verser <= 0) {
            showAlert("Alert","Le montant verser doit être supérieur à 0","warning");
            return false;
        }

        if (montant_verser <= 0) {
            showAlert("Alert","Le montant restant doit être egale ou supérieur à 0","warning");
            return false;
        }

        var modal = bootstrap.Modal.getInstance(document.getElementById('Versement'));
        modal.hide();

        // Ajouter le préchargeur
        preloader('start');

        $.ajax({
            url: url_base + "/api/insert_versement/" + code + '/' + matricule,
            method: "GET",
            data: {
                montant_payer: montant_payer,
                montant_verser: montant_verser,
                montant_restant: montant_restant,
                obs: obs.val() || null,
                date_livraison: date_livraison.val() || null,
                login: user.login,
                agence_id: user.magasin_id,
            },
            success: function (response) {
                preloader('end');

                if (response.success) {
                    list_facture_client();
                    Verfication_solde();
                    showAlert("Succès", "Opération éffectuée", "success");
                } else if (response.caisser_fermer) {
                    showAlert("Alert", "La caisse est fermer actuellement", "info");
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