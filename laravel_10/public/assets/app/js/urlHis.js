$(document).ready(function () {

    window.urlHistorique = function (login) {
        
        // Supposons que vous ayez le login de l'utilisateur connecté
        let userLogin = login; // Remplacez ceci par la méthode appropriée pour obtenir le login

        // Récupérer l'URL de la page actuelle
        let currentUrl = window.location.href;

        // Récupérer le tableau stocké dans le localStorage, ou initialiser un nouveau tableau s'il n'existe pas
        let userPages = JSON.parse(localStorage.getItem('userPages')) || [];

        // Vérifier si l'utilisateur est déjà présent dans le tableau
        let userIndex = userPages.findIndex(user => user.login === userLogin);

        if (userIndex !== -1) {
            // Mettre à jour l'URL de la dernière page visitée pour cet utilisateur
            userPages[userIndex].lastUrl = currentUrl;
        } else {
            // Ajouter une nouvelle entrée pour le nouvel utilisateur
            userPages.push({ login: userLogin, lastUrl: currentUrl });
        }

        // Stocker le tableau mis à jour dans le localStorage
        localStorage.setItem('userPages', JSON.stringify(userPages));

    }

    window.historiqueBtnMenu = function () {
        const btnReduc = localStorage.getItem("btn_historique");
        // console.log(btnReduc);

        if (btnReduc == null) {
            localStorage.setItem("btn_historique", "large");
            $("#div_menu").removeClass("is-compact");
        } else if (btnReduc == "reduit") {
            $("#div_menu").addClass("is-compact");
        }

        $("#btn_menu").on("click", function () {
            const btnhistorique = localStorage.getItem("btn_historique");

            if (btnhistorique == "reduit") {
                localStorage.setItem("btn_historique", "large");
                $("#div_menu").addClass("is-compact");
            } else {
                localStorage.setItem("btn_historique", "reduit");
                $("#div_menu").removeClass("is-compact");
            }

            // console.log("État du menu : ", localStorage.getItem("btn_historique"));
        });
    }


});
