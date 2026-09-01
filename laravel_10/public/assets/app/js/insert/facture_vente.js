$(document).ready(function () {

    select_client_vente('#client');
    select_remise('#remise');
    select_code_proforma_vente('#code_proforma');
    numberTel("#netAssurance");

    addDesignation();

    $('#client').on('change', function() {
        let selectedOption = $(this).find(':selected');
        // let taux = parseInt(selectedOption.data('taux'));
        // $('#taux').val(taux);

        // if (taux == 0) {
        //     $('#choix_assurance').val("0").trigger('change'); // Sélectionner "Non"
        //     $('#choix_assurance option[value="1"]').remove(); // Supprimer "Oui"
        // } else {
        //     // Ajouter l'option si elle a été supprimée auparavant
        //     if ($('#choix_assurance option[value="1"]').length === 0) {
        //         $('#choix_assurance').append('<option value="1">Oui</option>');
        //         $('#choix_assurance').val("1").trigger('change');
        //     }
        // }

        $('#contenu').empty();
        addDesignation();

        $("#netAssurance").val(0);
        $("#remise").val(0).trigger('change');
        $("#netPayer").val(0);
        $("#mTotal").val(0);

        const matricule = $(this).val();

        $.ajax({
            url: $('#url').attr('content') + '/api/select_client_prescription/'+matricule,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                if (data.length > 0) {
                    const prescription = data[0];

                    // Remplir les valeurs du tableau pour l'Œil Droit
                    $('#sphere_OD').text(prescription.OD.sphere);
                    $('#cylindre_OD').text(prescription.OD.cylindre);
                    $('#axe_OD').text(prescription.OD.axe);
                    $('#addition_OD').text(prescription.OD.addition);

                    // Remplir les valeurs du tableau pour l'Œil Gauche
                    $('#sphere_OG').text(prescription.OG.sphere);
                    $('#cylindre_OG').text(prescription.OG.cylindre);
                    $('#axe_OG').text(prescription.OG.axe);
                    $('#addition_OG').text(prescription.OG.addition);

                } else {

                    $('#sphere_OD, #cylindre_OD, #axe_OD, #addition_OD').text('');
                    $('#sphere_OG, #cylindre_OG, #axe_OG, #addition_OG').text('');

                    showAlert("Alert", "Le client n'a pas de préscription", "warning");

                    $("#client").val(null).trigger('change.select2');

                }
                
            },
            error: function() {
                console.error('Erreur lors de la récupération des prescriptions.');
            }
        });
    });

    // $('#choix_assurance').on('change', function() {
        
    //     const contenuDiv = $('#contenu');
    //     updateMontantTotal(contenuDiv);
    // });

    $("#btn_ajouter").on("click", addDesignation);

    function addDesignation() {
        const contenuDiv = $('#contenu');
        addContenu(contenuDiv);
    }

    function addContenu(contenuDiv) {

        const index = contenuDiv.children('.contenu_enfant').length + 1;

        const div = $(`
            <div class="contenu_enfant card-inner border border-1 rounded mb-5">
                <div class="card-header" style="background: linear-gradient(to right, #FFA500, #FF4500);">
                    <h5 class="card-title text-center text-white Title_produit">
                        Produit ${index}
                    </h5>
                </div>
                <div class="row g-gs mt-1">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Désignation</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-archived"></em>
                                </div>
                                <input type="text" class="form-control designation" placeholder="Saisie Obligatoire">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Prix</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">Fcfa</div>
                                <input type="tel" class="form-control prix" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Quantité</label>
                            <div class="form-control-wrap">
                                <input type="tel" class="form-control quantite" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">Total</label>
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">Fcfa</div>
                                <input readonly type="tel" class="form-control total" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group text-center">
                            <a class="btn btn-md btn-outline-danger suppr-btn">
                                <em class="icon ni ni-trash"></em>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `);

        contenuDiv.append(div);

        checkContenu(contenuDiv);

        // Ajouter un event listener pour le bouton supprimer
        div.find('.suppr-btn').on('click', function() {
            div.remove();
            checkContenu(contenuDiv);
            updateIndexes(contenuDiv);
            updateMontantTotal(contenuDiv);
        });

        div.find('.prix').on('input', function() {

            let parent = $(this).closest('.contenu_enfant');
            let prix = parseFloat(parent.find('.prix').val().replace(/[^0-9]/g, '')) || 0;
            let quantite = parseInt(parent.find('.quantite').val().replace(/[^0-9]/g, '')) || 0;
            let total = prix * quantite;
            parent.find('.total').val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

            if (!parent.find('.prix').val() || parent.find('.prix').val() == 0) {
                parent.find('.prix').val(0);
            }

            let rawValue = 0;
            rawValue = parseInt(parent.find('.prix').val().replace(/[^0-9]/g, ''));
            $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

            updateMontantTotal(contenuDiv);
        });

        div.find('.quantite').on('input', function() {

            let parent = $(this).closest('.contenu_enfant');
            let prix = parseFloat(parent.find('.prix').val().replace(/[^0-9]/g, '')) || 0;
            let quantite = parseInt(parent.find('.quantite').val().replace(/[^0-9]/g, '')) || 0;
            let total = prix * quantite;
            parent.find('.total').val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

            if (!parent.find('.quantite').val() || parent.find('.quantite').val() == 0) {
                parent.find('.quantite').val(0);
            }

            let rawValue = 0;
            rawValue = parseInt(parent.find('.quantite').val().replace(/[^0-9]/g, ''));
            $(this).val(rawValue);
                
            updateMontantTotal(contenuDiv);
        });
    }

    function updateIndexes(contenuDiv) {
        contenuDiv.find('.Title_produit').each(function(index) {
            $(this).text(`Produit ${index + 1}`);
        });
    }

    function checkContenu(contenuDiv) {
        const divBtn = $('#div_btn');

        if (contenuDiv.children('.contenu_enfant').length > 0) {
            if ($('#btn_div_valide').length === 0) { // ✅ Vérifier si ça n’existe pas déjà
                divBtn.append(`
                    <div class="form-group text-center" id="btn_div_valide">
                        <button type="submit" class="btn btn-md btn-success" id="btn_loader">
                            Enregistrer
                        </button>
                    </div>
                `);
            }
        } else {
            divBtn.empty(); // ✅ On supprime le bouton si plus aucun enfant
        }
    }

    function updateMontantTotal(contenuDiv) {

        let montantTotal = 0;
        let montantPatient = 0;
        let montantAssurance = 0;

        // let taux = parseInt($('#taux').val()) || 0;
        // let choix_assurance = $('#choix_assurance').val();

        // if (choix_assurance == 0) {
        //     taux = 0;
        // }

        // Calcul du montant total
        contenuDiv.find('.contenu_enfant').each(function () {
            let total = parseInt($(this).find('.total').val().replace(/[^0-9]/g, '')) || 0;

            if (isNaN(total)) {
                showAlert("ALERT", 'Vérifier les prix et quantités des Produits s\'il vous plaît.', "warning");
                return false;  // Arrêter la boucle si une erreur est trouvée
            }

            montantTotal += total;
        });

        // Vérification de la remise
        let remise = parseInt($('#remise').val().replace(/[^0-9.-]/g, '')) || 0; 

        // Calcul de la part de l'assurance et du patient
        // if (taux > 0) {
        //     montantAssurance = Math.floor((montantTotal * taux) / 100);
        //     montantPatient = montantTotal - montantAssurance;
        // } else {
        //     montantAssurance = 0;
        //     montantPatient = montantTotal;
        // }

        // montantAssurance = Math.floor((montantTotal * taux) / 100);
        // montantPatient = montantTotal - montantAssurance;

        if ($('#netAssurance').val().replace(/[^0-9]/g, '') > 0) {

            let assurance = parseInt($('#netAssurance').val().replace(/[^0-9]/g, '')) || 0;
            let newNetPayer = montantTotal - assurance;
        
            montantAssurance = assurance;
            montantPatient = newNetPayer;

        } else {

            montantAssurance = 0;
            montantPatient = montantTotal;
        }

        // Calcul du montant à payer en fonction de la remise
        if (!isNaN(remise) && remise !== 0) {
            montantPatient = Math.floor(montantPatient - ((montantPatient * remise) / 100));
        }

        // Mise à jour des champs avec les montants formatés
        $('#mTotal').val(formatPrice(montantTotal));
        $('#netAssurance').val(formatPrice(montantAssurance));
        $('#netPayer').val(formatPrice(montantPatient));
    }

    $('#netAssurance').on('input', function() {

        if ($('#mTotal').val().replace(/[^0-9]/g, '') <= 0) {
            showAlert("ALERT", 'Vérifier les prix et quantités des Produits s\'il vous plaît.', "warning");
            $(this).val(0);
            return false;
        }

        // Vérification de la remise
        let remise = parseInt($('#remise').val().replace(/[^0-9.-]/g, '')) || 0; 

        let parent = $(this);
        let assurance = parseInt(parent.val().replace(/[^0-9]/g, '')) || 0;
        let total = parseInt($('#mTotal').val().replace(/[^0-9]/g, '')) || 0;
        let newNetPayer = total - assurance;

        if (!isNaN(remise) && remise !== 0) {
            newNetPayer = Math.floor(newNetPayer - ((newNetPayer * remise) / 100));
        }

        if (assurance > total) {
            $(this).val(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
            $('#netPayer').val(0);
            showAlert("ALERT", 'Le montant de la part assurance ne doit pas depassé le montant Total.', "warning");
            return false;
        }

        $('#netPayer').val(newNetPayer.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

        if (!$(this).val().replace(/[^0-9]/g, '') || $(this).val().replace(/[^0-9]/g, '') == 0) {
            $(this).val(0);
        }

        let rawValue = 0;
        rawValue = parseInt($('#netAssurance').val().replace(/[^0-9]/g, ''));
        $(this).val(rawValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));

    });

    // Fonction pour formater les nombres avec séparateur de milliers
    function formatPrice(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }


    $('#remise').on('change', function() {
        const contenuDiv = $('#contenu');
        updateMontantTotal(contenuDiv);
    });

    function restForm()
    {
        $("#client").val(null).trigger('change.select2');
        // $("#taux").val(null);
        // $("#choix_assurance").val(0).trigger('change');
        $("#date").val(new Date().toISOString().slice(0, 10));

        $('#sphere_OD, #cylindre_OD, #axe_OD, #addition_OD').text('');
        $('#sphere_OG, #cylindre_OG, #axe_OG, #addition_OG').text('');

        $("#code_proforma").val(null).trigger('change');
        $("#netAssurance").val(0);
        $("#remise").val(0).trigger('change');
        $("#netPayer").val(0);
        $("#mTotal").val(0);

        $('#contenu').empty();

        addDesignation();
    }

    $("#formulaire_vente").on("submit", function (event) {
        event.preventDefault();

        let client = $("#client").val();
        // let taux = $("#taux").val();
        // let choix_assurance = $("#choix_assurance").val();
        let date = $("#date").val();

        let code_proforma = $("#code_proforma").val();
        let mTotal = parseFloat($("#mTotal").val().replace(/[^0-9]/g, ''));
        let netAssurance = parseFloat($("#netAssurance").val().replace(/[^0-9]/g, ''));
        let remise = $("#remise").val();
        let netPayer = parseFloat($("#netPayer").val().replace(/[^0-9]/g, ''));

        if (!client|| !date) {
            showAlert("Alert","Veuillez remplir tous les champs obligatoire s'il vous plaît !!!","warning");
            return false;
        }

        if ($('#contenu').children('.contenu_enfant').length <= 0) {
            showAlert("ALERT", 'Aucun produit n\'a été identifier.', "warning");
            return false;
        }

        const selectionsProduit = [];
        let formIsValid = true;

        $('#contenu').find('.contenu_enfant').each(function() {
            let desi = $(this).find('.designation').val();
            let prix = parseFloat($(this).find('.prix').val().replace(/[^0-9]/g, '')) || 0;
            let qte = parseInt($(this).find('.quantite').val().replace(/[^0-9]/g, '')) || 0;
            let total = parseFloat($(this).find('.total').val().replace(/[^0-9]/g, '')) || 0;

            if (!desi.trim()) {
                showAlert("ALERT", 'Vérifier le nom de chaques Produits s\'il vous plaît.', "warning");
                formIsValid = false;
                return false;
            }

            if (isNaN(total) || total == 0) {
                showAlert("ALERT", 'Vérifier les prix et quantités des Produits s\'il vous plaît.', "warning");
                formIsValid = false;
                return false;
            }

            selectionsProduit.push({
                nom: desi,
                prix: prix,
                qte: qte,
                total: total,
            });

        });

        // Si un problème a été trouvé, on stoppe le traitement
        if (!formIsValid) {
            return false;
        }
        
        if (isNaN(mTotal) || isNaN(netPayer) || mTotal < 0 || netPayer < 0 ) {
            showAlert("ALERT", 'Vérifier les montants s\'il vous plaît.', "warning");
            return false;
        }

        if (isNaN(netAssurance) || netAssurance < 0 ) {
            netAssurance = 0;
        }

        // Ajouter le préchargeur
        spinerButton(1, '#btn_loader', 'Vérification');

        $.ajax({
            url: url_base + "/api/insert_vente",
            method: "GET",
            data: {
                selectionsProduit: selectionsProduit,
                client: client,
                // taux: taux,
                // choix_assurance: choix_assurance,
                date: date,
                code_proforma:code_proforma || null,
                total: mTotal,
                netAssurance: netAssurance,
                netPayer: netPayer,
                remise: remise,
                login: user.login,
                id_agence: user.magasin_id,
            },
            success: function (response) {
                spinerButton(0, '#btn_loader', 'Enregistrer');

                if (response.success) {

                    if (response.fac === 0) {
                        showAlert("Succès", "Opération éffectuée, mais une erreur est survenur lors l'affiche de la facture", "info");
                        return false;
                    }

                    showAlert("Succès", "Opération éffectuée", "success");
                    restForm();
                    list_vente_all();
                    // PDF_Facture_Vente(response.client, response.pres, response.produits,$('#agence').val());

                } else if (response.json) {
                    showAlert("Alert", "Echec de l\'opération (Format JSON)", "info");
                } else if (response.error) {
                    showAlert("Alert", "Echec de l\'opération", "error");
                    console.log(response.message);
                }
            },
            error: function () {
                spinerButton(0, '#btn_loader', 'Enregistrer');
                showAlert("Erreur", "Erreur est survenu, veuillez réessayer.", "error");
                console.log(response.message);
            },
        });
    });

});