$(document).ready(function () {
    // window.urlBase = "https://espacemedicosociallapyramideducomplexe.net/amitie/public/index.php";
    window.urlBase = $('#url').attr('content');

    let session = null;
    let user = null;

    if (window.dataTheme) {
        try {
            const sessionData = atob(window.dataTheme); // décodage Base64
            session = JSON.parse(sessionData);          // parse JSON
            user = session.user || null;                // récupère user si présent
        } catch (error) {
            console.error("Erreur lors du décodage de dataTheme :", error);
            session = null;
            user = null;
        }
    } else {
        console.warn("window.dataTheme n'existe pas");
    }

    // On peut les mettre globalement si nécessaire
    window.session = session;
    window.user = user;
});