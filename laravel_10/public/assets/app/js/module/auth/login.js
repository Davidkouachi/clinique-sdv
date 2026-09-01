$(document).ready(function () {

    /* ==========================================================
       INITIALISATION DU PASSWORD
       ========================================================== */

    togglePassword('pwd', 'toggleIcon');


    /* ==========================================================
       CONNEXION
       ========================================================== */

    $('#formulaire').on('submit', function (event) {

        event.preventDefault();

        const $form = $(this);
        const $login = $('#login');
        const $password = $('#pwd');
        const $alert = $('#alert');
        const $button = $('.btnConnexion');

        const login = $.trim($login.val());
        const password = $.trim($password.val());

        /* ------------------------------------------------------
           VALIDATION
           ------------------------------------------------------ */

        $alert.html('');

        if (!login || !password) {

            $alert.html('Veuillez remplir tous les champs.');

            return;
        }


        /* ------------------------------------------------------
           BOUTON EN CHARGEMENT
           ------------------------------------------------------ */

        spinerButton(
            0,
            '.btnConnexion',
            'Vérification'
        );


        /* ------------------------------------------------------
           RÉCUPÉRATION DES PAGES UTILISATEUR
           ------------------------------------------------------ */

        let userPagesString = null;

        const storedUserPages = localStorage.getItem('userPages');

        if (storedUserPages) {

            try {

                const userPages = JSON.parse(storedUserPages);

                userPagesString = JSON.stringify({
                    userPages: userPages
                });

            } catch (error) {

                console.warn(
                    'Impossible de lire userPages depuis localStorage.',
                    error
                );

                userPagesString = null;
            }
        }


        /* ------------------------------------------------------
           RAFRAÎCHISSEMENT CSRF
           ------------------------------------------------------ */

        $.ajax({

            url: $('#url').attr('content') + '/refresh-csrf',

            type: 'GET',

            dataType: 'json',

            success: function (csrfResponse) {

                const csrfToken = csrfResponse.csrf_token;

                if (!csrfToken) {

                    spinerButton(
                        1,
                        '.btnConnexion',
                        'Connexion'
                    );

                    showAlert(
                        'Alerte',
                        'Impossible de rafraîchir le formulaire.',
                        'warning'
                    );

                    return;
                }


                /* --------------------------------------------------
                   MISE À JOUR DU TOKEN CSRF
                   -------------------------------------------------- */

                $('meta[name="csrf-token"]')
                    .attr('content', csrfToken);


                /* --------------------------------------------------
                   AUTHENTIFICATION
                   -------------------------------------------------- */

                $.ajax({

                    url: $('#url').attr('content') + '/api/trait_login',

                    type: 'POST',

                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },

                    data: {
                        login: login,
                        password: password,
                        userPage: userPagesString
                    },

                    xhrFields: {
                        withCredentials: true
                    },

                    dataType: 'json',

                    success: function (response) {

                        /* ------------------------------------------
                           CONNEXION RÉUSSIE
                           ------------------------------------------ */

                        if (response.success) {

                            spinerButton(
                                0,
                                '.btnConnexion',
                                'Redirection en cours'
                            );

                            const currentPath =
                                window.location.pathname;

                            window.location.href =
                                currentPath.includes(
                                    '/amitie/public/index.php'
                                )
                                    ? '/amitie/public/index.php'
                                    : '/';

                            return;
                        }


                        /* ------------------------------------------
                           IDENTIFIANTS INCORRECTS
                           ------------------------------------------ */

                        if (response.error) {

                            spinerButton(
                                1,
                                '.btnConnexion',
                                'Connexion'
                            );

                            showAlert(
                                'Alerte',
                                'Login ou mot de passe incorrect.',
                                'info'
                            );

                            return;
                        }


                        /* ------------------------------------------
                           RÉPONSE INATTENDUE
                           ------------------------------------------ */

                        spinerButton(
                            1,
                            '.btnConnexion',
                            'Connexion'
                        );

                        showAlert(
                            'Alerte',
                            'Réponse inattendue du serveur.',
                            'warning'
                        );
                    },


                    /* ------------------------------------------------
                       ERREUR AUTHENTIFICATION
                       ------------------------------------------------ */

                    error: function (xhr) {

                        spinerButton(
                            1,
                            '.btnConnexion',
                            'Connexion'
                        );

                        console.error(
                            'Erreur authentification :',
                            xhr
                        );

                        showAlert(
                            'Erreur',
                            'Erreur lors de l\'authentification. Veuillez réessayer.',
                            'warning'
                        );
                    }
                });
            },


            /* ------------------------------------------------------
               ERREUR CSRF
               ------------------------------------------------------ */

            error: function (xhr) {

                spinerButton(
                    1,
                    '.btnConnexion',
                    'Connexion'
                );

                console.error(
                    'Erreur rafraîchissement CSRF :',
                    xhr
                );

                showAlert(
                    'Erreur',
                    'Une erreur est survenue lors du rafraîchissement du token.',
                    'error'
                );
            }
        });
    });

});