$(document).ready(function () {

    select_societe('#societe_id');
    select_assurance('#assurance_id');
    select_taux('#taux_id');

    numberTel("#tel1");
    // numberTel("#tel2");

    numberTelLimit("#tel1");
    // numberTelLimit("#tel2");

    $('#assurer').on('change', function() {

        $('#assurance_id').val(null).trigger('change');
        $('#societe_id').val(null).trigger('change');
        // $('#taux_id').val(null).trigger('change');
        $('#matricule').val(null);

        if ($(this).val() === '1') {
            $('#div_assurer').stop(true, true).slideDown();
        } else {
            $('#div_assurer').stop(true, true).slideUp();
        }

    });

    
    $("#formulaire_new_client").on("submit", function (event) {
        event.preventDefault();

        let nom = $("#nom");
        let prenoms = $("#prenoms");
        let datenais = $("#datenais");
        let sexe = $("#sexe");
        let sondage = $("#sondage");
        let profession = $("#profession");
        let residence = $("#residence");
        let tel1 = $("#tel1");
        // let tel2 = $("#tel2");
        let email = $("#email");
        // let reseau_sociaux = $("#reseau_sociaux");
        // let commercial = $("#commercial");
        let assurer = $("#assurer");
        let assurance_id = $("#assurance_id");
        let societe_id = $("#societe_id");
        // let taux_id = $("#taux_id");
        let matricule = $("#matricule");

        if (!nom.val().trim() || !prenoms.val().trim() || !datenais.val().trim() || !sexe.val().trim() || !tel1.val().trim() || !assurer.val().trim() ) {
            showAlert("Alert","Veuillez remplir tous les champs obligatoire s'il vous plaît !!!","warning");
            return false;
        }

        if (tel1.val().length !== 10 ) {
            showAlert("Alert", "Contact incomplet.", "info");
            return false;
        }

        // if (tel2.val().trim() && tel2.val().length !== 10) {
        //     showAlert("Alert", "Contact 2 incomplet.", "info");
        //     return false;
        // }

        // Validation des champs relatifs à l'assurance
        if (assurer.val().trim() === "1") {
            if (!assurance_id.val().trim() || !societe_id.val().trim() || !matricule.val().trim()) {
                showAlert("Alert", "Veuillez remplir tous les champs relatifs à l'assurance.", "warning");
                return false;
            }
        }

        if (email.val().trim()) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.val().trim())) { 
                showAlert('Alert', 'Email incorrect.','warning');
                return false;
            }
        } 

        // Ajouter le préchargeur
        // preloader('start');

        spinerButton(1, '#btn_loader', 'Vérification');

        $.ajax({
            url: url_base + "/api/insert_client",
            method: "GET",
            data: {
                nom: nom.val(),
                prenoms: prenoms.val(),
                datenais: datenais.val(),
                sexe: sexe.val(),
                sondage: sondage.val(),
                profession: profession.val() || null,
                residence: residence.val() || null,
                tel: tel1.val(),
                // tel2: tel2.val() || null,
                email: email.val() || null,
                assurance_id: assurance_id.val() || null,
                societe_id: societe_id.val() || null,
                // taux_id: taux_id.val() || null,
                matricule_ass: matricule.val() || null,
                login: user.login,
                magasin: user.magasin_id,
            },
            success: function (response) {
                spinerButton(0, '#btn_loader', 'Enregistrer');

                if (response.success) {

                    restForm();
                    list_user_all();
                    showAlert("Succès", "Opération éffectuée", "success");

                } else if (response.matricule_ass_existe) {
                    showAlert("Alert", "Ce matricule d\'asurance existe déjà", "info");
                } else if (response.error) {
                    showAlert("Alert", "Echec de l\'opération", "error");
                    console.log(response.message);
                }
            },
            error: function () {
                spinerButton(0, '#btn_loader', 'Enregistrer');
                showAlert("Erreur", "Erreur est survenu, veuillez réessayer.", "warning");
                console.log(response.message);
            },
        });
    });

    function restForm()
    {
        $("#nom").val(null);
        $("#prenoms").val(null);
        $("#datenais").val(null);
        $("#sexe").val(null).trigger('change');
        $("#sondage").val(null).trigger('change');
        $("#profession").val(null);
        $("#residence").val(null);
        $("#tel1").val(null);
        // $("#tel2").val(null);
        $("#email").val(null);
        // $("#reseau_sociaux").val(null);
        // $("#commercial").val(null).trigger('change');
        $("#assurer").val(0).trigger('change');
        $("#assurance_id").val(null).trigger('change');
        $("#societe_id").val(null).trigger('change');
        // $("#taux_id").val(null).trigger('change');
        $("#matricule");

        $('#div_assurer').stop(true, true).slideUp();
    }

});