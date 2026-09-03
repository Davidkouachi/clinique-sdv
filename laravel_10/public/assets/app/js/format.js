$(document).ready(function () {

    // window.urlBase = function () {

    //     const url = $('#url').attr('content');

    //     return url;
    // }

    window.formatDate = function (dateString) {

        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
        const year = date.getFullYear();

        return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
    }

    window.formatDateHeure = function (dateString) {

        const date = new Date(dateString);
            
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
    }

    window.getDateToday = function () {
        const date = new Date();

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    window.calculAge = function (dateString) {
        const birthDate = new Date(dateString);
        const today = new Date();

        let ageYears = today.getFullYear() - birthDate.getFullYear();
        let monthDiff = today.getMonth() - birthDate.getMonth();
        let dayDiff = today.getDate() - birthDate.getDate();

        // Ajustement pour les mois et jours
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            ageYears--;
            monthDiff += 12; // Compte les mois restants de l'année précédente
        }

        // Ajustement des jours pour éviter des mois incomplets
        if (dayDiff < 0) {
            const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0); // Dernier jour du mois précédent
            dayDiff += prevMonth.getDate();
            monthDiff--;
        }

        // Si l'âge est inférieur à un an, retourner les mois et jours
        if (ageYears === 0) {
            if (monthDiff === 0) {
                return `${dayDiff} jour${dayDiff > 1 ? 's' : ''}`; // Retourne les jours si < 1 mois
            }
            return `${monthDiff} mois${dayDiff > 0 ? ` et ${dayDiff} jour${dayDiff > 1 ? 's' : ''}` : ''}`;
        }

        // Retourne l'âge en années
        return `${ageYears} an${ageYears > 1 ? 's' : ''}`;
    };

    window.calculateAge = function (dateString) {
        const birthDate = new Date(dateString);
        const today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();

        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        return age;
    }

    window.calculateDuration = function (date_debut) {
        const now = new Date();
        const startDate = new Date(date_debut);

        // Si la date de début est dans le passé par rapport à aujourd'hui, retourne 0
        if (startDate > now) {
            return '0';
        }

        const diffTime = Math.abs(now - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convertir en jours

        if (diffDays < 7) {
            return `${diffDays} jour${diffDays > 1 ? 's' : ''}`;
        } else if (diffDays < 30) {
            const weeks = Math.floor(diffDays / 7);
            return `${weeks} semaine${weeks > 1 ? 's' : ''}`;
        } else if (diffDays < 365) {
            const months = Math.floor(diffDays / 30);
            return `${months} mois`;
        } else {
            const years = Math.floor(diffDays / 365);
            return `${years} an${years > 1 ? 's' : ''}`;
        }
    }

    window.timeAgo = function (date) {
        const now = new Date();
        const past = new Date(date);
        const diffInSeconds = Math.floor((now - past) / 1000);

        const intervals = [
            { label: "an", seconds: 31536000 },
            { label: "mois", seconds: 2592000 },
            { label: "semaine", seconds: 604800 },
            { label: "jour", seconds: 86400 },
            { label: "heure", seconds: 3600 },
            { label: "minute", seconds: 60 },
            { label: "seconde", seconds: 1 }
        ];

        for (const interval of intervals) {
            const count = Math.floor(diffInSeconds / interval.seconds);
            if (count >= 1) {
                return `Il y a ${count} ${interval.label}${count > 1 ? "" : ""}`;
            }
        }
        return "À l'instant";
    }

    window.numberTel = function (id) {
        var inputElement = $(id);

        // Nettoyer avant d’attacher (évite les doublons)
        inputElement.off('keypress').on('keypress', function (event) {
            const key = event.which || event.keyCode;

            if (
                (key < 48 || key > 57) &&
                key !== 8 &&  // Backspace
                key !== 46 && // Delete
                key !== 9     // Tab
            ) {
                event.preventDefault();
            }
        });

        inputElement.off('input').on('input', function () {
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });
    };

    window.numberTelLimit = function (id) {
        var inputElement = $(id); // Sélectionner l'élément avec son ID

        inputElement.on('input', function () {
            let value = $(this).val(); // Récupérer la valeur actuelle
            if (value.length > 10) {
                value = value.substring(0, 10); // Limiter à 10 caractères
            }
            $(this).val(value); // Mettre à jour la valeur nettoyée et limitée
        });
    };

    window.initMontantInput = function (ids) {

        // Permettre un seul ID ou un tableau d'IDs
        if (!Array.isArray(ids)) {
            ids = [ids];
        }

        ids.forEach(function (id) {

            const $input = $('#' + id);

            if (!$input.length) {
                return;
            }

            // Évite de multiplier les événements si la fonction
            // est appelée plusieurs fois
            $input.off('.montant');

            // Autoriser uniquement les chiffres au clavier
            $input.on('keypress.montant', function (event) {

                const key = event.key;

                if (!/[0-9]/.test(key)) {
                    event.preventDefault();
                }
            });

            // Formatage automatique
            $input.on('input.montant', function () {

                let value = $(this)
                    .val()
                    .replace(/\D/g, '');

                if (value === '') {
                    $(this).val('');
                    return;
                }

                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                $(this).val(value);
            });
        });
    };

    window.formatPrice = function (price) {

        if (price === null || price === undefined || price === '') {
            return '0';
        }

        const number = Math.round(
            parseFloat(String(price).replace(/\./g, '').replace(',', '.'))
        );

        if (isNaN(number)) {
            return '0';
        }

        return number.toLocaleString('fr-FR').replace(/\s/g, '.');
    };

    window.formatPriceT = function (price) {
        let number = Math.round(parseInt(price));
        if (isNaN(number)) return '';

        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    window.toNumber = function (value) {
        return parseInt(
            String(value ?? '').replace(/\D/g, ''),
            10
        ) || 0;
    }

    window.calculateDaysBetween = function (startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // Calcul de la différence en millisecondes
        const diffInMilliseconds = end - start;

        // Conversion en jours (millisecondes en secondes, minutes, heures, jours)
        let diffInDays = diffInMilliseconds / (1000 * 60 * 60 * 24);

        // Si la différence est inférieure ou égale à 0, on retourne 1 jour minimum
        return diffInDays <= 0 ? 1 : Math.round(diffInDays); 
    }

    window.confirmAction = function (title = "Confirmation requise", text = "Cette opération est irréversible. Êtes-vous sûr de vouloir effectuer cette action ? .")
    {
        return Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: !0,
            customClass: {
                confirmButton: "btn btn-success me-2 mt-2",
                cancelButton: "btn btn-danger mt-2"
            },
            showCancelButton: true,
            confirmButtonText: "Oui",
            cancelButtonText: "Non",
            buttonsStyling: !1,
            showCloseButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    }

    window.confirmActionAuto = function (title = "Confirmation requise", text = "Cette opération est irréversible. Êtes-vous sûr de vouloir effectuer cette action ? .", btn = "oui", color = "success")
    {
        return Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: false,
            confirmButtonText: btn,
            buttonsStyling: false,
            showCloseButton: false,
            customClass: {
                confirmButton: "btn btn-" + color + " me-2 mt-2",
            },
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false, // facultatif
        });
    }

    window.preloader = function (type) {

        if (type === 'start') {
            
            let preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch">
                        <div></div><div></div><div></div><div></div>
                        <div></div><div></div><div></div><div></div>
                    </div>
                </div>
            `;
            $("body").append(preloader_ch);

        } else if (type === 'end') {

            $("#preloader_ch").remove();

        }
        
    }

    window.overDisplay = function (mode) {

        if (mode === 1) {
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

    window.spinerButton = function (mode, buttonId, label) {
        const $button = $(buttonId);

        if (mode === 1) {
            overDisplay(1);

            $button
                .prop('disabled', true)
                .addClass('text-white')
                .html(`
                    <div class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></div>
                    ${label}...
                `);
        } else {
            overDisplay(0);

            $button.prop('disabled', false).html(label);
        }
    }

    window.numberToWordsFr = function(n) {
        if (n === 0) return "zéro";

        const units = ["", "un", "deux", "trois", "quatre", "cinq", "six", "sept", "huit", "neuf",
            "dix", "onze", "douze", "treize", "quatorze", "quinze", "seize"];

        const tens = ["", "", "vingt", "trente", "quarante", "cinquante", "soixante"];

        function underHundred(nb) {
            if (nb < 17) return units[nb];
            if (nb < 20) return "dix-" + units[nb - 10];
            if (nb < 70) {
                let d = Math.floor(nb / 10);
                let u = nb % 10;
                if (u === 1 && d !== 8) return tens[d] + " et un";
                return tens[d] + (u > 0 ? "-" + units[u] : "");
            }
            if (nb < 80) return "soixante-" + underHundred(nb - 60);
            if (nb < 100) return "quatre-vingt" + (nb > 80 ? "-" + underHundred(nb - 80) : "");
        }

        function underThousand(nb) {
            if (nb < 100) return underHundred(nb);
            let c = Math.floor(nb / 100);
            let r = nb % 100;
            if (c === 1) return "cent" + (r > 0 ? " " + underHundred(r) : "");
            return units[c] + " cent" + (r > 0 ? " " + underHundred(r) : "") + (r === 0 ? "s" : "");
        }

        let words = "";
        if (n >= 1000000) {
            let m = Math.floor(n / 1000000);
            words += (m > 1 ? numberToWordsFr(m) + " millions" : "un million");
            n %= 1000000;
            if (n > 0) words += " " + numberToWordsFr(n);
            return words;
        }

        if (n >= 1000) {
            let k = Math.floor(n / 1000);
            if (k === 1) words += "mille";
            else words += numberToWordsFr(k) + " mille";
            n %= 1000;
            if (n > 0) words += " " + underThousand(n);
            return words;
        }

        return underThousand(n);
    }

    window.verifPassword = function (motDePasse) {

        if (motDePasse.length < 8) {
            return false;
        }

        if (!/[A-Z]/.test(motDePasse)) {
            return false;
        }

        if (!/[a-z]/.test(motDePasse)) {
            return false;
        }

        if (!/\d/.test(motDePasse)) {
            return false;
        }

        return true;
    }

    window.verifEmail = function (email) {

        const test = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!test.test(email)) {
            return false;
        }

        return true;
    }

    window.OffClick = function (selector, fonction) {
        $(document).off('click', selector).on('click', selector, fonction);
    }

    window.OffChange = function (selector, fonction) {
        $(document).off('change', selector).on('change', selector, fonction);
    }

    window.OffInput = function (selector, fonction) {
        $(document).off('input', selector).on('input', selector, fonction);
    }

    window.showPreloader = function () {
        $('body').append(`
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `);
    }

    window.hidePreloader = function () {
        $("#preloader_ch").remove();
    }

    window.truncateText = function (text, maxLength = 20, suffix = '...') {
        if (!text) return '';

        return text.length > maxLength
            ? text.slice(0, maxLength) + suffix
            : text;
    }

    window.genererNomMois = function (month) {
        // Tableau des noms de mois en français
        const moisNoms = [
        'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
        ];

        const moisNom = moisNoms[month - 1];
        // Génération du nom de fichier
        return `${moisNom}`;
    }

    window.togglePassword = function (passwordId, iconId) {

        $('#' + iconId).closest('a, button').on('click', function (event) {

            event.preventDefault();

            const $password = $('#' + passwordId);
            const $icon = $('#' + iconId);

            if (!$password.length || !$icon.length) {
                return;
            }

            const isPassword = $password.attr('type') === 'password';

            $password.attr('type', isPassword ? 'text' : 'password');

            $icon
                .toggleClass('ri-eye-line', !isPassword)
                .toggleClass('ri-eye-off-line', isPassword);
        });
    }

window.openModal = function ({
    title = '',
    content = '',
    size = 'modal-lg'
} = {}) {

    let modalElement =
        document.getElementById('globalModal');

    const previousFocus =
        document.activeElement;

    /*
    |--------------------------------------------------------------------------
    | Créer le modal uniquement s'il n'existe pas
    |--------------------------------------------------------------------------
    */

    if (!modalElement) {

        const modalHtml = `

            <div
                id="globalModal"
                class="custom-modal"
                role="dialog"
                aria-modal="true"
            >

                <div class="custom-modal-overlay"></div>

                <div class="custom-modal-dialog ${size}">

                    <div class="custom-modal-content">

                        <div class="custom-modal-header">

                            <h5 class="custom-modal-title"></h5>

                            <button
                                type="button"
                                class="custom-modal-close"
                                aria-label="Fermer"
                            >
                                &times;
                            </button>

                        </div>

                        <div class="custom-modal-body"></div>

                    </div>

                </div>

            </div>

        `;

        document.body.insertAdjacentHTML(
            'beforeend',
            modalHtml
        );

        modalElement =
            document.getElementById('globalModal');
    }

    /*
    |--------------------------------------------------------------------------
    | Mise à jour du contenu
    |--------------------------------------------------------------------------
    */

    const dialog =
        modalElement.querySelector(
            '.custom-modal-dialog'
        );

    const titleElement =
        modalElement.querySelector(
            '.custom-modal-title'
        );

    const bodyElement =
        modalElement.querySelector(
            '.custom-modal-body'
        );

    const closeButton =
        modalElement.querySelector(
            '.custom-modal-close'
        );

    const overlay =
        modalElement.querySelector(
            '.custom-modal-overlay'
        );


    /*
    |--------------------------------------------------------------------------
    | Taille
    |--------------------------------------------------------------------------
    */

    dialog.className =
        `custom-modal-dialog ${size}`;


    /*
    |--------------------------------------------------------------------------
    | Titre
    |--------------------------------------------------------------------------
    */

    titleElement.textContent = title;


    /*
    |--------------------------------------------------------------------------
    | Contenu
    |--------------------------------------------------------------------------
    */

    bodyElement.innerHTML = content;


    /*
    |--------------------------------------------------------------------------
    | Éviter plusieurs événements
    |--------------------------------------------------------------------------
    */

    if (!modalElement.dataset.eventsAttached) {

        let isClosing = false;

        const closeModal = function () {

            if (isClosing) {
                return;
            }

            isClosing = true;

            if (
                document.activeElement &&
                modalElement.contains(
                    document.activeElement
                )
            ) {
                document.activeElement.blur();
            }

            modalElement.classList.remove('show');

            document.body.classList.remove(
                'custom-modal-open'
            );

            setTimeout(function () {

                isClosing = false;

            }, 200);

        };


        closeButton.addEventListener(
            'click',
            closeModal
        );


        overlay.addEventListener(
            'click',
            closeModal
        );


        const keydownHandler = function (event) {

            if (
                event.key === 'Escape' &&
                modalElement.classList.contains('show')
            ) {

                closeModal();

            }

        };


        document.addEventListener(
            'keydown',
            keydownHandler
        );


        modalElement.dataset.eventsAttached = 'true';

        modalElement._closeModal = closeModal;
    }


    /*
    |--------------------------------------------------------------------------
    | Affichage
    |--------------------------------------------------------------------------
    */

    document.body.classList.add(
        'custom-modal-open'
    );


    requestAnimationFrame(function () {

        modalElement.classList.add('show');

    });


    /*
    |--------------------------------------------------------------------------
    | Focus
    |--------------------------------------------------------------------------
    */

    requestAnimationFrame(function () {

        if (closeButton) {
            closeButton.focus();
        }

    });


    return {

        close: function () {

            if (
                typeof modalElement._closeModal ===
                'function'
            ) {

                modalElement._closeModal();

            }

        },

        element: modalElement

    };

};

});
