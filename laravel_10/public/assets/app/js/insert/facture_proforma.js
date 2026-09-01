$(document).ready(function () {

    numberTel("#tel");
    numberTelLimit("#tel");

    select_remise('#remise');
    
    sphere_prescription('#Sphere_OD',null);
    cylindre_prescription('#Cylindre_OD',null);
    axe_prescription('#Axe_OD',null);
    addition_prescription('#Addition_OD',null);
    traitement_prescription('#Traitement_OD',null);
    type_verre_prescription('#Type_verre_OD',null);

    sphere_prescription('#Sphere_OG',null);
    cylindre_prescription('#Cylindre_OG',null);
    axe_prescription('#Axe_OG',null);
    addition_prescription('#Addition_OG',null);
    traitement_prescription('#Traitement_OG',null);
    type_verre_prescription('#Type_verre_OG',null);

    addDesignation();

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

        // Calcul du montant total
        contenuDiv.find('.contenu_enfant').each(function() {
            let total = parseInt($(this).find('.total').val().replace(/[^0-9]/g, '')) || 0; // Convertir en entier

            if (isNaN(total)) {
                showAlert("ALERT", 'Vérifier les prix et quantités des Produits s\'il vous plaît.', "warning");
                return false;  // Arrêter la boucle si une erreur est trouvée
            }

            montantTotal += total;  // Ajouter au montant total
        });

        // Vérification de la remise
        let remise = parseInt($('#remise').val().replace(/[^0-9.-]/g, '')) || 0; // Convertir en entier

        // Calcul du montant à payer en fonction de la remise
        if (!isNaN(remise) && remise !== 0) {
            montantPatient = Math.floor(montantTotal - ((montantTotal * remise) / 100));  // Arrondi vers le bas
        } else {
            montantPatient = montantTotal;  // Pas de remise, montant total à payer
        }

        // Mise à jour des champs avec les montants formatés
        $('#mTotal').val(formatPrice(montantTotal));  
        $('#netPayer').val(formatPrice(montantPatient));  
    }

    // Fonction pour formater les nombres avec séparateur de milliers
    function formatPrice(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }


    $('#remise').on('change', function() {
        const contenuDiv = $('#contenu');
        updateMontantTotal(contenuDiv);
    });

    $("#formulaire_proforma").on("submit", function (event) {
        event.preventDefault();

        let nom = $("#nom");
        let tel = $("#tel");
        let date = $("#date");

        let sphere_OD = $("#Sphere_OD");
        let cylindre_OD = $("#Cylindre_OD");
        let axe_OD = $("#Axe_OD");
        let addition_OD = $("#Addition_OD");

        let sphere_OG = $("#Sphere_OG");
        let cylindre_OG = $("#Cylindre_OG");
        let axe_OG = $("#Axe_OG");
        let addition_OG = $("#Addition_OG");

        let mTotal = parseFloat($("#mTotal").val().replace(/[^0-9]/g, ''));
        let remise = $("#remise").val();
        let netPayer = parseFloat($("#netPayer").val().replace(/[^0-9]/g, ''));

        if (!nom.val().trim() || !tel.val().trim() || !date.val().trim() ) {
            showAlert("Alert","Veuillez remplir tous les champs obligatoire s'il vous plaît !!!","warning");
            return false;
        }

        if (tel.val().length !== 10 ) {
            showAlert("Alert", "Contact incomplet.", "info");
            return false;
        }

        // Vérifier qu'au moins un champ a une valeur
        const hasOneValue = [
            sphere_OD.val(), cylindre_OD.val(), axe_OD.val(), addition_OD.val(),
            sphere_OG.val(), cylindre_OG.val(), axe_OG.val(), addition_OG.val()
        ].some(value => value && value.trim() !== '');

        if (!hasOneValue) {
            showAlert("Attention", "Veuillez remplir au moins un champ de prescription", "info");
            return false;
        }

        if ($('#contenu').children('.contenu_enfant').length <= 0) {
            showAlert("ALERT", 'Aucun produit n\'a été identifier.', "warning");
            return false;
        }

        const selectionsProduit = [];
        let formIsValid = true;

        $('#contenu').find('.contenu_enfant').each(function () {
            let desi = $(this).find('.designation').val();
            let prix = parseFloat($(this).find('.prix').val().replace(/[^0-9]/g, '')) || 0;
            let qte = parseInt($(this).find('.quantite').val().replace(/[^0-9]/g, '')) || 0;
            let total = parseFloat($(this).find('.total').val().replace(/[^0-9]/g, '')) || 0;

            if (!desi.trim()) {
                showAlert("ALERT", "Vérifier le nom de chaque Produit s'il vous plaît.", "warning");
                formIsValid = false;
                return false;
            }

            if (isNaN(total) || total === 0) {
                showAlert("ALERT", "Vérifier les prix et quantités des Produits s'il vous plaît.", "warning");
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

        // Ajouter le préchargeur
        spinerButton(1, '#btn_loader', 'Vérification');

        $.ajax({
            url: url_base + "/api/insert_proforma",
            method: "GET",
            data: {
                selectionsProduit: selectionsProduit,
                nom: nom.val(),
                tel: tel.val(),
                date: date.val(),
                total: mTotal,
                netPayer: netPayer,
                remise: remise,
                sphere_OD: sphere_OD.val() || null,
                cylindre_OD: cylindre_OD.val() || null,
                axe_OD: axe_OD.val() || null,
                addition_OD: addition_OD.val() || null,

                sphere_OG: sphere_OG.val() || null,
                cylindre_OG: cylindre_OG.val() || null,
                axe_OG: axe_OG.val() || null,
                addition_OG: addition_OG.val() || null,
                login: user.login,
                agence_id: user.magasin_id,
            },
            success: function (response) {
                spinerButton(0, '#btn_loader', 'Enregistrer');

                if (response.success) {

                    restForm();
                    list_proforma_all();
                    showAlert("Succès", "Opération éffectuée", "success");
                    PDF_Facture_Proforma(response.client, response.pres, response.produits,$('#agence').val());

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

    function restForm()
    {
        $("#nom").val(null);
        $("#tel").val(null);

        // ➕ Mettre la date du jour automatiquement
        const today = new Date().toISOString().split('T')[0];
        $('#date').val(today);

        $("#Sphere_OD").val(null).trigger('change');
        $("#Cylindre_OD").val(null).trigger('change');
        $("#Axe_OD").val(null).trigger('change');
        $("#Addition_OD").val(null).trigger('change');

        $("#Sphere_OG").val(null).trigger('change');
        $("#Cylindre_OG").val(null).trigger('change');
        $("#Axe_OG").val(null).trigger('change');
        $("#Addition_OG").val(null).trigger('change');

        $("#remise").val(0).trigger('change');
        $("#netPayer").val(0);
        $("#mTotal").val(0);

        $('#contenu').empty();

        addDesignation();
    }

});