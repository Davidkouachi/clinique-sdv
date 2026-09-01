<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page non trouvée</title>
    <meta property="og:title" content="Espace Santé">
    <meta property="og:description" content="CENTRE MEDICAL LA NOUVELLE AMITIE DE YAMOUSSOUKRO.">
    <meta property="og:image" content="{{ asset('assets/images/logo.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Espace Santé">
    <link rel="shortcut icon" href="{{asset('assets/images/logo.jpg')}}">
    <link rel="stylesheet" href="{{asset('assets/fonts/remix/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
</head>

<body class="error-bg">
    <div class="error-container text-center" style="background: rgba(0, 0, 0, 0.5);">
        <h1 class="mb-2">404</h1>
        <h3 class="fw-light mb-4 text-white">
            Oups ! La page que vous recherchez est introuvable.
            <br>Elle a peut-être été déplacée ou supprimée.
        </h3>
        <a href="{{route('index_accueil')}}" class="btn btn-warning px-4 py-2 fs-5">
            <i class="ri-arrow-left-s-line lh-1 me-2"></i> Retour à l'accueil
        </a>
    </div>
</body>

</html>
