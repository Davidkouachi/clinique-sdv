$(document).ready(function () { 
    
    window.Verfication_statut = function () 
    {

        $('#btn_fermer').hide();
        $('#btn_ouvert').hide();
        $('#chargement').show();

        $.ajax({
            url: url_base + '/api/caisseVerf/' + user.magasin_id,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                $('#chargement').hide();
                $('#solde').show();

                if (data.statut == 0) {
                    $('#btn_fermer').hide();
                    $('#btn_ouvert').show();
                    $('#div_operation').hide();
                    $('#solde').text(data.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Fcfa');
                } else {
                    $('#btn_fermer').show();
                    $('#btn_ouvert').hide();
                    $('#div_operation').show();
                    $('#solde').text(data.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Fcfa');
                }
            
            },
            error: function() {
                $('#message').show();
                $('#btn_fermer').hide();
                $('#btn_ouvert').hide();
                $('#chargement').hide();
                $('#div_operation').hide();
            }
        });
    }

    window.Verfication_solde = function () 
    {

        $.ajax({
            url: url_base + '/api/caisseVerf/' + user.magasin_id,
            method: 'GET',
            success: function(response) {
                const data = response.data;

                $('#solde').show();
                $('#solde').text(data.solde.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' Fcfa');
            
            },
            error: function() {
                console.log('une erreur est survenue lors de l\'actualisation de solde');
            }
        });
    }

    window.Ouverture_caisse = function () 
    {

        $('#chargement').show();
        $('#btn_fermer').hide();
        $('#btn_ouvert').hide();
        $('#div_operation').hide();

        $.ajax({
            url: url_base + '/api/caisseOuvert/'+ user.magasin_id + '/' + user.login + '/' + user_magasin,
            method: 'GET',
            success: function(response) {
                $('#chargement').hide();

                if (response.success) {
                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();
                    $('#div_operation').show();

                    if ($('.table_operation').length > 0) {
                        list_operation_all();
                    }

                    showAlert2("Succès","La caisse à été ouverte","success");

                } else if (response.success_deja) {
                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();
                    $('#div_operation').show();

                    if ($('.table_operation').length > 0) {
                        list_operation_all();
                    }

                    showAlert2("Succès","La caisse été déjà ouverte","info");
                    
                } else {
                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();
                    $('#div_operation').hide();
                    showAlert2("Alert","Imposible d'ouvrir la caisse pour le moment, veuillez réessayer plus tard.","warning");
                }
            
            },
            error: function() {
                $('#chargement').hide();
                $('#btn_fermer').hide();
                $('#btn_ouvert').show();
                $('#div_operation').hide();
                $('#message').show();
                showAlert2("Alert","Une erreur c'est produite lors de l'ouverture de la caisse.","danger");
            }
        });
    }

    window.Fermeture_caisse = function () 
    {

        $('#chargement').show();
        $('#btn_fermer').hide();
        $('#btn_ouvert').hide();
        $('#div_operation').hide();

        $.ajax({
            url: url_base + '/api/caisseFermer/' + user.magasin_id + '/' + user.login + '/' + user_magasin,
            method: 'GET',
            success: function(response) {
                $('#chargement').hide();

                if (response.success) {
                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();
                    $('#div_operation').hide();

                    showAlert2("Succès","La caisse à été fermer","success");
                    
                } else if (response.success_deja) {
                    $('#btn_ouvert').show();
                    $('#btn_fermer').hide();
                    $('#div_operation').hide();

                    showAlert2("Succès","La caisse été déjà fermer","info");
                    
                } else {
                    $('#btn_ouvert').hide();
                    $('#btn_fermer').show();
                    $('#div_operation').show();
                    showAlert2("Alert","Imposible de fermer la caisse pour le moment, veuillez réessayer plus tard.","warning");
                }
            
            },
            error: function() {
                $('#chargement').hide();
                $('#btn_fermer').show();
                $('#btn_ouvert').hide();
                $('#div_operation').show();
                $('#message').show();
                showAlert2("Alert","Une erreur c'est produite lors de la fermeture de la caisse.","danger");
            }
        });
    }

    $('#btn_fermer').on('click', function() {

        Fermeture_caisse();
    });

    $('#btn_ouvert').on('click', function() {

        Ouverture_caisse();
        Verfication_solde();
    });

});