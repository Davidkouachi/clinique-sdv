$(document).ready(function () {

    window.overDisplay = function (mode) {

        if (mode === 0) {
            // Créer et afficher l'overlay si non présent
            if ($('#global-spinner-overlay').length === 0) {
                $('body').append(`
                    <div id="global-spinner-overlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100vw;
                        height: 100vh;
                        background: rgba(255, 255, 255, 0);
                        z-index: 9999;
                        cursor: not-allowed;
                    "></div>
                `);
            }
        } else {
            // Supprimer l'overlay
            $('#global-spinner-overlay').remove();
        }
    }
    
    window.spinerButton = function (mode, buttonId, label, icon = 0, iconLabel = '') {
        const $button = $(buttonId);

        if (mode === 0) {
            // Mode avec spinner
            $button.html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                ${label}...
            `);
            overDisplay(0);

        } else if (icon === 1) {
            // Mode avec icône
            $button.html(`
                <i class="fa fa-${iconLabel} me-2"></i>
                ${label}
            `);
            overDisplay(1);

        } else {
            // Mode simple (juste le texte)
            $button.html(`${label}`);
            overDisplay(1);
        }
    }

});