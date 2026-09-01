$(document).ready(function () {

	$('.logoutBtn').on('click', function (e) {
        e.preventDefault();

        confirmAction('Confirmation', 'Êtes-vous sûr de vouloir vous deconnectez ?').then((result) => {
            if (result.isConfirmed) {

                const ModalDeco = `
                    <div id="preloaderLogout" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                        background: rgba(255,255,255,1); z-index: 9999; display: flex; align-items: center; justify-content: center;">
                        <div style="text-align: center;">
                            <div class="spinner-border text-danger" role="status"></div>
                            <p style="margin-top: 10px; font-weight: bold;">Déconnexion en cours...</p>
                        </div>
                    </div>`;

                // Ajoute le préloader
                $('body').append(ModalDeco);

                // Optionnel : petit délai pour voir le préloader
                setTimeout(function () {
                    window.location.href = $('.logoutBtn').attr('href');
                }, 1000);
            }
        });
    });

    $('#parametrage').on('click', function (e) {

        e.preventDefault();

        // Supprimer un éventuel ancien modal
        $('#Parametrage').remove();


        const modalHtml = `
            <div class="modal fade"
                 id="Parametrage"
                 tabindex="-1"
                 aria-labelledby="ParametrageLabel"
                 aria-hidden="true">

                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title" id="ParametrageLabel">
                                Mise à jour du mot de passe
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                            </button>

                        </div>

                        <div class="modal-body" id="modal_parametrage">

                            <div class="text-center mb-4">

                                <div class="d-inline-flex align-items-center justify-content-center
                                            rounded-circle bg-primary-subtle text-primary"
                                     style="width: 90px; height: 90px;">

                                    <i class="ri-lock-password-line"
                                       style="font-size: 48px;">
                                    </i>

                                </div>

                            </div>

                            <div class="text-center mb-4">

                                <h5 class="fw-bold mb-1">
                                    Modification du mot de passe
                                </h5>

                                <p class="text-muted small mb-0">
                                    Saisissez votre nouveau mot de passe
                                </p>

                            </div>

                            <div class="row gx-3">

                                <div class="col-lg-6 col-12">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Nouveau mot de passe
                                        </label>

                                        <div class="input-group">

                                            <input type="password"
                                                   class="form-control"
                                                   id="password1_para"
                                                   placeholder="Saisie obligatoire"
                                                   autocomplete="off">

                                            <button type="button"
                                                    class="btn btn-white"
                                                    id="btn_hidden_mpd1">

                                                <i id="toggleIcon1"
                                                   class="ri-eye-line text-primary">
                                                </i>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-lg-6 col-12">

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Confirmer le mot de passe
                                        </label>

                                        <div class="input-group">

                                            <input type="password"
                                                   class="form-control"
                                                   id="password2_para"
                                                   placeholder="Saisie obligatoire"
                                                   autocomplete="off">

                                            <button type="button"
                                                    class="btn btn-white"
                                                    id="btn_hidden_mpd2">

                                                <i id="toggleIcon2"
                                                   class="ri-eye-line text-primary">
                                                </i>

                                            </button>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-12 text-center mt-3">

                                    <button type="button"
                                            id="btn_update_mdp"
                                            class="btn btn-primary px-4">

                                        <i class="ri-edit-line me-2"></i>
                                        Mise à jour

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        `;


        // Ajouter le modal au DOM
        $('body').append(modalHtml);


        // ============================
        // Bouton mise à jour
        // ============================

        $('#btn_update_mdp').on('click', function () {
            update_mdp();
        });


        // ============================
        // Afficher / masquer MDP 1
        // ============================

        $('#btn_hidden_mpd1').on('click', function () {

            const $input = $('#password1_para');
            const $icon = $('#toggleIcon1');

            const visible = $input.attr('type') === 'text';

            $input.attr(
                'type',
                visible ? 'password' : 'text'
            );

            $icon
                .toggleClass('ri-eye-line', visible)
                .toggleClass('ri-eye-off-line', !visible);

        });


        // ============================
        // Afficher / masquer MDP 2
        // ============================

        $('#btn_hidden_mpd2').on('click', function () {

            const $input = $('#password2_para');
            const $icon = $('#toggleIcon2');

            const visible = $input.attr('type') === 'text';

            $input.attr(
                'type',
                visible ? 'password' : 'text'
            );

            $icon
                .toggleClass('ri-eye-line', visible)
                .toggleClass('ri-eye-off-line', !visible);

        });


        // ============================
        // Initialiser Bootstrap
        // ============================

        const modalElement = document.getElementById('Parametrage');

        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true
        });

        modal.show();


        // ============================
        // Supprimer après fermeture
        // ============================

        $('#Parametrage').on('hidden.bs.modal', function () {
            $(this).remove();
        });

    });

    function update_mdp() {

        const login = window.user.login;

        const mdp1 = $('#password1_para').val().trim();
        const mdp2 = $('#password2_para').val().trim();


        // ==============================
        // Validation
        // ==============================

        if (!mdp1 || !mdp2) {

            showAlert(
                'Alerte',
                'Veuillez remplir tous les champs SVP.',
                'warning'
            );

            return false;
        }


        if (mdp1 !== mdp2) {

            showAlert(
                'Alerte',
                'Les mots de passe ne correspondent pas.',
                'warning'
            );

            return false;
        }


        if (mdp1.length < 5) {

            showAlert(
                'Alerte',
                'Le mot de passe doit contenir au moins 5 caractères.',
                'warning'
            );

            return false;
        }


        // ==============================
        // Fermeture du modal
        // ==============================

        const modalElement = document.getElementById('Parametrage');
        const modal = bootstrap.Modal.getInstance(modalElement);

        if (modal) {
            modal.hide();
        }


        // ==============================
        // Preloader
        // ==============================

        const preloader = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;

        $('body').append(preloader);


        // ==============================
        // Rafraîchissement CSRF
        // ==============================

        $.ajax({
            url: '/refresh-csrf',
            method: 'GET',

            success: function (response_crsf) {

                $('meta[name="csrf-token"]')
                    .attr('content', response_crsf.csrf_token);


                // ==============================
                // Mise à jour mot de passe
                // ==============================

                $.ajax({
                    url: '/api/update_mdp/' + login,
                    method: 'PUT',

                    headers: {
                        'X-CSRF-TOKEN': response_crsf.csrf_token
                    },

                    data: {
                        mdp1: mdp1
                    },

                    success: function (response) {

                        $('#preloader_ch').remove();


                        if (response.success) {

                            let timerInterval;

                            Swal.fire({

                                title: "Opération effectuée, veuillez patienter un instant s'il vous plaît",

                                timer: 2000,

                                timerProgressBar: true,

                                didOpen: () => {

                                    Swal.showLoading();

                                    const timer =
                                        Swal.getPopup().querySelector("b");

                                    timerInterval = setInterval(() => {

                                        timer.textContent =
                                            `${Swal.getTimerLeft()}`;

                                    }, 100);

                                },

                                willClose: () => {
                                    clearInterval(timerInterval);
                                }

                            }).then((result) => {

                                if (
                                    result.dismiss ===
                                    Swal.DismissReason.timer
                                ) {
                                    location.reload();
                                }

                            });

                        } else if (response.error) {

                            showAlert(
                                'Erreur',
                                "Échec de l'opération.",
                                'error'
                            );

                        }

                    },

                    error: function () {

                        $('#preloader_ch').remove();

                        showAlert(
                            'Erreur',
                            'Erreur lors de la mise à jour.',
                            'error'
                        );

                    }

                });

            },

            error: function () {

                console.log(
                    "Erreur lors du rafraîchissement du token CSRF"
                );

                $('#preloader_ch').remove();

                showAlert(
                    'Erreur',
                    'Erreur lors de la mise à jour.',
                    'error'
                );

            }

        });
    }  

});