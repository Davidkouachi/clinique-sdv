@extends('app')

@section('titre', 'Acceuil')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            A Propos
        </li>
    </ol>
</div>
@endsection

@section('content')

    <div class="app-body">
        <!-- Row starts -->
        <div class="row gx-3">
            <div class="col-12">
                <div class="card mb-3 ">
                    <div class="card-body text-center" >
                        <img height="150" height="150" src="{{asset('assets/images/logo.jpg')}}">
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="accordion mb-3" id="accordionSpecialTitle">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleOne" aria-expanded="true" aria-controls="collapseSpecialTitleOne">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Présentation</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleOne" class="accordion-collapse collapse show" aria-labelledby="headingSpecialTitleOne" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                <p class="mb-3">
                                    La <strong>Clinique médicale SOURCE DE VIE</strong> est un établissement de santé moderne dédié à la prise en charge médicale de proximité. Implanté au cœur de la ville d'Abidjan, le centre offre un cadre accueillant, sécurisé et accessible, favorisant une prise en charge efficace et humaine des patients.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleTwo" aria-expanded="false" aria-controls="collapseSpecialTitleTwo">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Missions</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleTwo" class="accordion-collapse collapse" aria-labelledby="headingSpecialTitleTwo" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                <p class="mb-3">
                                    Notre mission est d’assurer des soins de qualité accessibles à tous, en mettant l’accent sur la prévention, le diagnostic précoce et le traitement efficace des pathologies courantes. Le centre s’engage à offrir une prise en charge respectueuse, professionnelle et centrée sur le bien-être du patient, tout en contribuant à l’amélioration continue de la santé communautaire.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSpecialTitleThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpecialTitleThree" aria-expanded="false" aria-controls="collapseSpecialTitleThree">
                                <div class="d-flex flex-column">
                                    <h5 class="m-0">Services Proposés</h5>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseSpecialTitleThree" class="accordion-collapse collapse" aria-labelledby="headingSpecialTitleThree" data-bs-parent="#accordionSpecialTitle">
                            <div class="accordion-body">
                                Nous mettons à disposition une gamme de services médicaux comprenant :
                                <ul>
                                    <li>Consultations médicales générales et spécialisées.</li>
                                    <li>Soins infirmiers et actes paramédicaux.</li>
                                    <li>Examens et analyses médicales.</li>
                                    <li>Suivi médical et accompagnement des patients.</li>
                                    <li>Actions de prévention, de sensibilisation et de conseil en santé.</li>
                                </ul>
                                <p class="mt-3">
                                    Le centre s’appuie sur une équipe médicale et paramédicale qualifiée, engagée à fournir des soins fiables et adaptés, dans un environnement respectueux des normes d’hygiène et de sécurité.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Row ends -->
    </div>


@endsection
