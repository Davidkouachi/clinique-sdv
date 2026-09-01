<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Marketplace for Bootstrap Admin Dashboards">
    <meta property="og:title" content="Admin Templates - Dashboard Templates">
    <meta property="og:description" content="Marketplace for Bootstrap Admin Dashboards">
    <meta property="og:type" content="Website">
    <link rel="shortcut icon" href="{{asset('assets/images/logo.jpg')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/remix/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    {{-- <meta name="base-url" content="https://espacemedicosociallapyramideducomplexe.net/amitie/public/index.php" id="url"> --}}
    <meta name="base-url" content="http://127.0.0.1:8000" id="url">

    <script src="{{asset('jquery.min.js')}}"></script>
    <script src="{{asset('assets/app/js/vGlobal.js')}}"></script>
    <script src="{{asset('assets/app/js/alert.js')}}"></script>
    <script src="{{asset('assets/app/js/format.js')}}"></script>
    <script src="{{asset('assets/app/js/loader.js')}}"></script>
</head>

<body class="login-bg" style="font-family: sans-serif; font-weight: bold;">
    <div style="background: rgba(0, 0, 0, 0.5);">
        <div class="container">
            <div class="auth-wrapper">
                <form id="formulaire">
                    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center py-4">
                        <div class="row g-0 overflow-hidden bg-white shadow-lg rounded-4" style="max-width: 1050px; width: 100%; min-height: 650px;">
                            <!-- ===================================================== -->
                            <!--                    PARTIE GAUCHE                       -->
                            <!-- ===================================================== -->
                            <div class="col-lg-7 d-none d-lg-flex position-relative overflow-hidden text-white p-5 flex-column justify-content-between" style="
                    background: linear-gradient(145deg, #087f5b 0%, #05668d 100%);
                ">
                                <!-- Décorations -->
                                <div class="position-absolute rounded-circle" style="
                        width: 300px;
                        height: 300px;
                        top: -120px;
                        right: -120px;
                        background: rgba(255,255,255,.08);
                    "></div>
                                <div class="position-absolute rounded-circle" style="
                        width: 400px;
                        height: 400px;
                        bottom: -220px;
                        left: -180px;
                        background: rgba(255,255,255,.06);
                    "></div>
                                <!-- Logo -->
                                <div class="position-relative">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-4 shadow p-2 mb-4" style="width: 75px; height: 75px;">
                                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" width="60" height="60" class="rounded-3" style="object-fit: cover;">
                                    </div>
                                    <h2 class="fw-bold mb-3">
                                        Votre santé,<br>
                                        notre priorité.
                                    </h2>
                                    <p class="mb-0" style="
                            color: rgba(255,255,255,.78);
                            line-height: 1.7;
                        ">
                                        Une plateforme moderne et sécurisée pour
                                        gérer efficacement votre établissement de santé,
                                        vos patients et votre activité médicale.
                                    </p>
                                </div>
                                <!-- Fonctionnalités -->
                                <div class="position-relative">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="
                                width: 40px;
                                height: 40px;
                                background: rgba(255,255,255,.12);
                            ">
                                            <i class="ri-shield-check-line fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                Données sécurisées
                                            </div>
                                            <small style="color: rgba(255,255,255,.65);">
                                                Vos informations sont protégées
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="
                                width: 40px;
                                height: 40px;
                                background: rgba(255,255,255,.12);
                            ">
                                            <i class="ri-heart-pulse-line fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                Gestion médicale
                                            </div>
                                            <small style="color: rgba(255,255,255,.65);">
                                                Un suivi complet de votre activité
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="
                                width: 40px;
                                height: 40px;
                                background: rgba(255,255,255,.12);
                            ">
                                            <i class="ri-dashboard-3-line fs-5"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">
                                                Simple et intuitif
                                            </div>
                                            <small style="color: rgba(255,255,255,.65);">
                                                Tout votre établissement au même endroit
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <!-- Footer -->
                                <div class="position-relative small" style="color: rgba(255,255,255,.55);">
                                    © {{ date('Y') }} — Plateforme de gestion santé
                                </div>
                            </div>
                            <!-- ===================================================== -->
                            <!--                    PARTIE DROITE                      -->
                            <!-- ===================================================== -->
                            <div class="col-lg-5 d-flex align-items-center justify-content-center p-4 p-md-5">
                                <div class="w-100" style="max-width: 430px;">
                                    <!-- ================= EN-TÊTE ================= -->
                                    <div class="mb-2">
                                        <div class="d-flex d-lg-none justify-content-center mb-4">
                                            <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" width="85" height="85" class="rounded-4 shadow-sm" style="object-fit: cover;">
                                        </div>
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 mb-3">
                                            <i class="ri-lock-line me-1"></i>
                                            Espace sécurisé
                                        </span>
                                        <h3 class="fw-bold text-dark mb-2">
                                            Bienvenue 👋
                                        </h3>
                                        <p class="text-muted mb-0">
                                            Connectez-vous pour accéder à votre espace.
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <p id="alert" class="text-danger small text-center mb-0"></p>
                                    </div>
                                    <!-- ================= FORMULAIRE ================= -->
                                    <form method="POST" id="formulaire">
                                        <!-- LOGIN -->
                                        <div class="mb-4">
                                            <label for="login" class="form-label fw-semibold small">
                                                Login
                                            </label>
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-user-3-line text-primary"></i>
                                                </span>
                                                <input type="text" id="login" class="form-control bg-light border-start-0 ps-1" placeholder="Votre identifiant" autocomplete="username">
                                            </div>
                                        </div>
                                        <!-- MOT DE PASSE -->
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between mb-2">
                                                <label for="pwd" class="form-label fw-semibold small mb-0">
                                                    Mot de passe
                                                </label>
                                            </div>
                                            <div class="input-group input-group-md">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-lock-password-line text-primary"></i>
                                                </span>
                                                <input type="password" id="pwd" class="form-control bg-light border-start-0 border-end-0 ps-1" placeholder="Votre mot de passe" autocomplete="current-password">
                                                <button type="button" id="btn_hidden_mpd" class="btn bg-white border border-start-0" aria-label="Afficher le mot de passe">
                                                    <i id="toggleIcon" class="ri-eye-line text-primary"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- MOT DE PASSE OUBLIÉ -->
                                        <div class="d-flex justify-content-end mb-4">
                                            <a href="#" id="btn_mot_de_passe_oublie" class="text-primary fw-semibold small text-decoration-none">
                                                Mot de passe oublié ?
                                            </a>
                                        </div>
                                        <!-- BOUTON CONNEXION -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success btn-md btnConnexion d-flex align-items-center justify-content-center gap-2 rounded-3 py-3X fw-semibold">
                                                <span>
                                                    Connexion
                                                </span>
                                                <i class="ri-arrow-right-line"></i>
                                            </button>
                                        </div>
                                        <!-- INFO -->
                                        <div class="text-center mt-4">
                                            <small class="text-muted">
                                                <i class="ri-shield-check-line me-1 text-primary"></i>
                                                Connexion sécurisée
                                            </small>
                                        </div>
                                    </form>
                                    <!-- ================= FOOTER ================= -->
                                    <div class="text-center mt-2 d-flex align-items-center justify-content-center d-lg-none">
                                        <small class="text-muted">
                                            Plateforme de gestion santé
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/app/js/module/auth/login.js')}}"></script>  

</body>

</html>
