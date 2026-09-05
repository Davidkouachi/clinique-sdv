$(document).ready(function() {

    let patientGlobal = null;
    let typeSoins = [];
    let cachedSoins = {};

    let cachedProduits = {};
    let produitsSoinsPromise = null;

    initialiserVolet();

    function globalUrl(path) {

        return (
            $('#url').attr('content') +
            path
        );
    }

    function getNumber(value) {

        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return 0;
        }

        return parseInt(
            String(value).replace(/[^0-9]/g, ''),
            10
        ) || 0;
    }

    function formatMoney(value) {

        value = Math.max(
            0,
            parseInt(value, 10) || 0
        );

        return value
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function removePreloader() {

        const preloader =
            document.getElementById('preloader_ch');

        if (preloader) {
            preloader.remove();
        }
    }

    function showPreloader() {

        removePreloader();

        const preloader = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;

        document.body.insertAdjacentHTML(
            'beforeend',
            preloader
        );
    }

    function initialiserVolet() {

        chargerPatients();

        chargerTypeSoinsApi();

        chargerProduitsSoins();

        $('#patient_id').on(
            'change',
            changementPatient
        );

        $('#taux_remise').on(
            'input',
            function () {

                formaterChampMontant(this);

                calculerMontantsSoins();
            }
        );

        $('#btn_eng_exd').on(
            'click',
            function () {

                enregistrerSoins();
            }
        );

        $('#add_select_soins').on(
            'click',
            function () {

                ajouterBlocSoins();
            }
        );

        $('#add_select_produit').on(
            'click',
            function () {

                ajouterProduitDepuisBouton();
            }
        );

        $('#montant_assurance_soins, #montant_patient_soins')
            .on('input', function () {

                formaterChampMontant(this);

            });


        $('#numcode').on(
            'keypress',
            allowOnlyNumbersAndLetters
        );


        $('#medecin, #rensg').on(
            'input',
            function () {

                this.value =
                    this.value.toUpperCase();

            }
        );

    }

    function chargerPatients() {

        const selectPatient =
            $('#patient_id');

        selectPatient.empty();

        selectPatient.append(
            $('<option>', {
                value: '',
                text: 'Sélectionner'
            })
        );


        $.ajax({

            url: globalUrl(
                '/api/select/patient'
            ),

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                const patients =
                    Array.isArray(response.results)
                        ? response.results
                        : [];

                patients.forEach(function (patient) {

                    selectPatient.append(

                        $('<option>', {

                            value: patient.id,

                            text:
                                `ID : ${patient.id} | ${patient.nom}`

                        })

                    );

                });

            },

            error: function (xhr) {

                console.error(
                    'Erreur chargement patients :',
                    xhr.responseText
                );

                showAlert(
                    'ALERT',
                    'Impossible de charger les patients.',
                    'error'
                );

            }

        });

    }

    function changementPatient() {

        const patientId =
            $(this).val();


        /*
         * Réinitialisation complète
         */
        resetVoletGlobal();


        if (!patientId) {

            $('#select_periode_div').hide();

            $('#select_soins_div').hide();

            $('#div_info_patient').hide();

            return;
        }


        /*
         * Récupérer les informations du patient
         */
        chargerInformationsPatient(patientId);

    }

    function chargerInformationsPatient(patientId) {

        showPreloader();

        $.ajax({

            url: globalUrl(
                '/api/rech/patient'
            ),

            type: 'GET',

            dataType: 'json',

            data: {
                id: patientId
            },


            success: function (response) {

                removePreloader();


                if (
                    !response.success ||
                    !response.patient
                ) {

                    showAlert(
                        'ALERT',
                        'Les informations du patient sont introuvables.',
                        'warning'
                    );

                    return;
                }


                /*
                 * Stockage UNE SEULE FOIS
                 */
                patientGlobal =
                    response.patient;


                afficherInformationsPatient(
                    patientGlobal
                );


                const assure = Number(patientGlobal.assure) === 1;

                $('#patient_taux').val(
                    assure
                        ? Number(patientGlobal.taux) || 0
                        : 0
                );


                /*
                 * Assurance
                 */
                gererAssurancePatient();


                /*
                 * Afficher période
                 */
                $('#select_periode_div')
                    .show();


                /*
                 * Afficher la partie examen
                 * seulement après choix période
                 */
                $('#select_soins_div')
                    .show();

            },


            error: function (xhr) {

                removePreloader();

                console.error(
                    'Erreur informations patient :',
                    xhr.responseText
                );

                showAlert(
                    'ALERT',
                    'Erreur lors du chargement des informations du patient.',
                    'error'
                );

            }

        });

    }

    function afficherInformationsPatient(patient)
    {
        const assure =
            Number(patient.assure) === 1;

        const numero =
            patient.id ||
            patient.idenregistremetpatient ||
            '';

        const nom =
            patient.nomprenomspatient ||
            patient.nomprenoms ||
            patient.np ||
            '';

        const sexe =
            patient.sexepatient ||
            patient.sexe ||
            '';

        const dateNaissance =
            patient.datenaispatient ||
            patient.date_naissance ||
            '';

        const telephone =
            patient.telpatient ||
            patient.tel ||
            '';

        const taux =
            assure
                ? Number(patient.taux) || 0
                : 0;


        let html = `

            <div class="col-12 mt-2 mb-3">

                <div
                    class="d-flex align-items-center
                           border-top pt-3"
                >

                    <div
                        class="d-flex align-items-center justify-content-center
                               bg-warning text-white rounded-circle me-3"
                        style="width: 40px; height: 40px;"
                    >
                        <i class="ri-user-line fs-5"></i>
                    </div>

                    <div>

                        <h6 class="mb-1 fw-semibold">
                            Information du patient
                        </h6>

                        <p class="text-muted mb-0 small">
                            Informations générales du dossier
                        </p>

                    </div>

                </div>

            </div>


            <div class="row gx-3">

                <!-- N° dossier -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            N° dossier
                        </label>

                        <input
                            id="patient_numdossier"
                            type="text"
                            value="${numero}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Nom et Prénoms -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Nom et Prénoms
                        </label>

                        <input
                            type="text"
                            value="${nom}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Sexe -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Sexe
                        </label>

                        <input
                            type="text"
                            value="${sexe}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Date naissance -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Naissance
                        </label>

                        <input
                            type="text"
                            value="${formatDate(dateNaissance)}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Contact -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Contact
                        </label>

                        <input
                            type="text"
                            value="${telephone}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Assuré -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Assuré
                        </label>

                        <input
                            type="text"
                            value="${assure ? 'Oui' : 'Non'}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>
        `;


        /*
         * Informations assurance
         */
        if (assure) {

            html += `

                <!-- Assurance -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Assurance
                        </label>

                        <input
                            type="text"
                            value="${patient.assurance ?? ''}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Matricule assurance -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Matricule assurance
                        </label>

                        <input
                            type="text"
                            value="${patient.matriculeassure ?? ''}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>


                <!-- Taux -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Taux
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                value="${taux}"
                                readonly
                                class="form-control"
                            >

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>
                </div>


                <!-- Société -->
                <div class="col-xxl-3 col-lg-4 col-sm-6">
                    <div class="mb-3">

                        <label class="form-label">
                            Société
                        </label>

                        <input
                            type="text"
                            value="${patient.societe ?? ''}"
                            readonly
                            class="form-control"
                        >

                    </div>
                </div>

            `;
        }


        html += `

            </div>

        `;


        $('#div_info_patient')
            .html(html)
            .show();
    }

    function gererAssurancePatient() {

        if (!patientGlobal) {
            return;
        }


        const taux =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(patientGlobal.taux) || 0
                )
            );


        const assure =
            Number(patientGlobal.assure) === 1;


        const divNumCode =
            $('#div_numcode');


        /*
         * Patient assuré
         */
        if (assure && taux > 0) {

            divNumCode.show();

        } else {

            divNumCode.hide();

            $('#numcode').val('');

        }

    }

    function chargerTypeSoinsApi() {

        /*
         * Déjà chargées
         */
        if (typeSoins.length > 0) {

            return $.Deferred()
                .resolve()
                .promise();
        }


        // showPreloader();


        return $.ajax({

            url: globalUrl(
                '/api/select/typesoins'
            ),

            type: 'GET',

            dataType: 'json'

        })

        .done(function (response) {

            typeSoins =
                Array.isArray(response.results)
                    ? response.results
                    : [];

            if (
                typeSoins.length === 0
            ) {

                showAlert(
                    'ALERT',
                    "Aucune famille d'examen n'a été trouvée.",
                    'warning'
                );

            }

        })


        .fail(function (xhr) {

            console.error(
                'Erreur:',
                xhr.responseText
            );

            showAlert(
                'ALERT',
                "Impossible de charger les familles d'examens.",
                'error'
            );

        })


        .always(function () {

            // removePreloader(); --}}

        });

    }

    function chargerProduitsSoins() {

        // ---------------------------------------------------------
        // CACHE
        // ---------------------------------------------------------

        if (
            Object.keys(cachedProduits).length > 0
        ) {

            return $.Deferred()
                .resolve(cachedProduits)
                .promise();

        }


        // ---------------------------------------------------------
        // REQUÊTE DÉJÀ EN COURS
        // ---------------------------------------------------------

        if (produitsSoinsPromise) {

            return produitsSoinsPromise;

        }


        // ---------------------------------------------------------
        // AJAX
        // ---------------------------------------------------------

        produitsSoinsPromise = $.ajax({

            url: globalUrl('/api/select/produits'),

            type: 'GET',

            dataType: 'json'

        })

        .done(function (response) {

            const produits =
                Array.isArray(response.results)
                    ? response.results
                    : [];


            cachedProduits = {};


            produits.forEach(function (produit) {

                cachedProduits[
                    produit.medicine_id
                ] = produit;

            });

        })

        .fail(function () {

            showAlert(
                'error',
                'Impossible de charger les produits.'
            );

        })

        .always(function () {

            produitsSoinsPromise = null;

        });


        return produitsSoinsPromise;

    }

    function ajouterProduitDepuisBouton() {

        // ---------------------------------------------------------
        // SI LES PRODUITS NE SONT PAS ENCORE CHARGÉS
        // ---------------------------------------------------------

        if (
            Object.keys(cachedProduits).length === 0
        ) {

            chargerProduitsSoins()
                .done(function () {

                    ajouterBlocProduit();

                });


            return;

        }


        // ---------------------------------------------------------
        // PRODUITS DÉJÀ DISPONIBLES
        // ---------------------------------------------------------

        ajouterBlocProduit();

    }

    function ajouterBlocProduit() {

        const container =
            $('#contenu_produit');


        const numero =
            container
                .find('.produit-block')
                .length + 1;


        const bloc = $(`
            <div class="col-12 produit-block mb-3">

                <div class="border rounded-3 p-3">

                    <!-- HEADER -->
                    <div
                        class="
                            d-flex
                            align-items-center
                            justify-content-between
                            mb-3
                        "
                    >

                        <div class="d-flex align-items-center">

                            <div
                                class="
                                    avatar-xs
                                    bg-info-subtle
                                    rounded
                                    me-2
                                "
                            >

                                <div
                                    class="
                                        avatar-title
                                        bg-info-subtle
                                        text-info
                                        rounded
                                    "
                                >
                                    ${numero}
                                </div>

                            </div>

                            <h6 class="mb-0">
                                Produit ${numero}
                            </h6>

                        </div>


                        <button
                            type="button"
                            class="
                                btn
                                btn-sm
                                btn-outline-danger
                                btn-supprimer-produit
                            "
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>

                    </div>


                    <!-- CONTENU -->
                    <div class="row gx-3">

                        <!-- PRODUIT -->
                        <div class="col-lg-7 col-md-6 mb-3">

                            <label class="form-label">
                                Produit / Médicament
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="
                                    form-select
                                    select2
                                    produit-select
                                "
                                style="width: 100%;"
                            >

                                <option value="">
                                    Sélectionner
                                </option>

                            </select>

                        </div>


                        <!-- PRISE EN CHARGE -->
                        <div class="col-lg-5 col-md-6 mb-3">

                            <label class="form-label">
                                Prise en charge
                            </label>

                            <select
                                class="
                                    form-select
                                    produit-assurance
                                    select2
                                "
                                style="width: 100%;"
                            >

                                <option value="non">
                                    Non
                                </option>

                            </select>

                        </div>


                        <!-- QUANTITÉ -->
                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Quantité
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                class="
                                    form-control
                                    text-end
                                    produit-quantite
                                "
                                value="1"
                                min="1"
                                step="1"
                            >

                        </div>


                        <!-- PRIX UNITAIRE -->
                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Prix unitaire
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="
                                        form-control
                                        text-end
                                        produit-prix
                                    "
                                    value="0"
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>


                        <!-- MONTANT -->
                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Montant
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="
                                        form-control
                                        text-end
                                        produit-montant
                                    "
                                    value="0"
                                    readonly
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>

                        <!-- Part assurance -->
                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Part assurance
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="form-control text-end produit-montant-assurance"
                                    value="0"
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>


                        <!-- Part patient -->
                        <div class="col-lg-4 col-md-6 mb-3">

                            <label class="form-label">
                                Part patient
                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    class="form-control text-end produit-montant-patient"
                                    value="0"
                                    readonly
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        `);


        container.append(bloc);


        /*
         * Remplir les produits
         */
        remplirProduitsBloc(bloc);


        /*
         * Select2
         */
        initialiserSelect2Produit(bloc);


        /*
         * Assurance
         */
        initialiserAssuranceBloc(
            'produit',
            bloc
        );


        /*
         * Événements
         */
        initialiserEvenementsProduit(bloc);


        /*
         * Calcul initial
         */
        calculerMontantsSoins();

    }

    function initialiserSelect2Produit(bloc)
    {
        bloc.find('.produit-select').select2({

            theme: 'bootstrap',

            width: '100%',

            placeholder: 'Sélectionner',

            language: {

                noResults: function () {

                    return 'Aucun résultat trouvé';

                }

            }

        });
    }

    function remplirProduitsBloc(bloc) {

        const select =
            bloc.find('.produit-select');


        select.empty();


        select.append(
            $('<option>', {

                value: '',

                text: 'Sélectionner'

            })
        );


        Object.values(cachedProduits)
            .forEach(function (produit) {

                const prix =
                    Number(produit.price) || 0;


                const option =
                    $('<option>', {

                        value:
                            produit.medicine_id,

                        text:
                            `${String(produit.name || '').trim()}`

                    });


                option.attr(
                    'data-prix',
                    prix
                );


                select.append(option);

            });


        select
            .val('')
            .trigger('change.select2');

    }

    function renumeroterBlocsProduits() {

        $('#contenu_produit .produit-block')
            .each(function (index) {

                const bloc =
                    $(this);


                const numero =
                    index + 1;


                bloc.find('.avatar-title')
                    .first()
                    .text(numero);


                bloc.find('h6')
                    .first()
                    .text(
                        'Produit ' + numero
                    );

            });

    }

    function validerProduits() {

        const blocs =
            $('#contenu_produit .produit-block');


        /*
         * Aucun bloc produit :
         * parfaitement valide.
         */
        if (blocs.length === 0) {

            return true;

        }


        let valide = true;


        blocs.each(function () {

            if (!valide) {
                return false;
            }


            const bloc = $(this);


            const produit =
                bloc.find('.produit-select')
                    .val();


            const quantite =
                parseInt(
                    bloc.find('.produit-quantite')
                        .val()
                ) || 0;


            /*
             * =====================================================
             * BLOC COMPLÈTEMENT VIDE
             * =====================================================
             */

            if (!produit) {

                /*
                 * On ignore le bloc.
                 */
                return;

            }


            /*
             * =====================================================
             * QUANTITÉ
             * =====================================================
             */

            if (quantite <= 0) {

                showAlert(
                    'ALERT',
                    'La quantité doit être supérieure à zéro.',
                    'warning'
                );

                valide = false;

                return false;

            }

        });


        return valide;

    }

    function construireSelectionsProduits() {

        const selections = [];


        $('#contenu_produit .produit-block')
            .each(function () {

                const bloc = $(this);


                const select =
                    bloc.find('.produit-select');


                const produitId =
                    select.val();


                /*
                 * =================================================
                 * BLOC VIDE
                 * =================================================
                 */

                if (!produitId) {

                    return;

                }


                const option =
                    select.find(
                        'option:selected'
                    );


                const quantite =
                    parseInt(
                        bloc.find(
                            '.produit-quantite'
                        ).val()
                    ) || 0;


                const prix =
                    getNumber(
                        bloc.find(
                            '.produit-prix'
                        ).val()
                    );


                const montant =
                    quantite * prix;


                const priseEnCharge =
                    bloc.find(
                        '.produit-assurance'
                    ).val() || 'non';


                // const taux =
                //     Math.max(
                //         0,
                //         Math.min(
                //             100,
                //             Number(
                //                 patientGlobal?.taux
                //             ) || 0
                //         )
                //     );


                // let montantAssurance = 0;

                // let montantPatient = montant;


                // if (
                //     priseEnCharge === 'oui' &&
                //     taux > 0
                // ) {

                //     montantAssurance =
                //         Math.floor(
                //             (
                //                 montant *
                //                 taux
                //             ) / 100
                //         );


                //     montantAssurance =
                //         Math.min(
                //             montant,
                //             montantAssurance
                //         );


                //     montantPatient =
                //         Math.max(
                //             0,
                //             montant -
                //             montantAssurance
                //         );

                // }

                const resultat =
                    calculerBlocProduit(bloc);

                selections.push({

                    medicine_id:
                        produitId,

                    produit:
                        option.text().trim(),

                    quantite:
                        quantite,

                    prix:
                        prix,

                    montant:
                        resultat.total,

                    assurance:
                        priseEnCharge,

                    montant_assurance:
                        resultat.assurance,

                    montant_patient:
                        resultat.patient

                });

            });


        return selections;

    }

    function selectionnerProduit(bloc) {

        const select =
            bloc.find('.produit-select');


        const option =
            select.find(':selected');


        /*
         * Aucun produit
         */
        if (!select.val()) {

            bloc.find('.produit-prix')
                .val('0');

            bloc.find('.produit-quantite')
                .val('1');

            bloc.find('.produit-montant')
                .val('0');

            calculerMontantsSoins();

            return;

        }


        /*
         * Prix du produit
         */
        const prix =
            Number(
                option.attr('data-prix')
            ) || 0;


        bloc.find('.produit-prix')
            .val(
                formatMoney(prix)
            );


        /*
         * Quantité par défaut
         */
        let quantite =
            parseInt(
                bloc.find('.produit-quantite').val()
            ) || 1;


        if (quantite < 1) {

            quantite = 1;

            bloc.find('.produit-quantite')
                .val(1);

        }


        calculerBlocProduit(bloc);

        calculerMontantsSoins();

    }

    function selectionnerAssuranceProduit(bloc)
    {
        const selectProduit =
            bloc.find('.produit-select');

        const assuranceInput =
            bloc.find('.produit-montant-assurance');


        if (!selectProduit.val()) {

            bloc.find('.produit-assurance')
                .val('non')
                .trigger('change.select2');

            assuranceInput
                .val(formatMoney(0))
                .attr('data-manual', '0');

            bloc.find('.produit-montant-patient')
                .val(formatMoney(0));

            showAlert(
                'ALERT',
                'Veuillez sélectionner un produit.',
                'warning'
            );

            return;
        }


        /*
         * Nouveau choix de prise en charge :
         * recalcul selon le taux.
         */

        assuranceInput.attr(
            'data-manual',
            '0'
        );

        calculerBlocProduit(bloc);

        calculerMontantsSoins();
    }

    function calculerBlocProduit(bloc) {

        const select =
            bloc.find('.produit-select');


        /*
         * =========================================================
         * AUCUN PRODUIT
         * =========================================================
         */

        if (!select.val()) {

            bloc.find('.produit-montant')
                .val(formatMoney(0));

            return {

                total: 0,

                assurance: 0,

                patient: 0

            };

        }


        /*
         * =========================================================
         * PRIX UNITAIRE
         * =========================================================
         */

        let prix =
            getNumber(
                bloc.find('.produit-prix').val()
            );


        prix =
            Math.max(
                0,
                prix
            );


        /*
         * =========================================================
         * QUANTITÉ
         * =========================================================
         */

        let quantite =
            parseInt(
                bloc.find('.produit-quantite').val()
            ) || 0;


        quantite =
            Math.max(
                1,
                quantite
            );


        /*
         * =========================================================
         * MONTANT PRODUIT
         * =========================================================
         */

        const montant =
            Math.max(
                0,
                prix * quantite
            );


        /*
         * Affichage du montant
         */

        bloc.find('.produit-prix')
            .val(
                formatMoney(prix)
            );


        bloc.find('.produit-montant')
            .val(
                formatMoney(montant)
            );


        /*
         * =========================================================
         * TAUX ASSURANCE
         * =========================================================
         */

        const taux =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(
                        patientGlobal?.taux
                    ) || 0
                )
            );


        /*
         * =========================================================
         * PRISE EN CHARGE
         * =========================================================
         */

        const assurance =
            bloc.find(
                '.produit-assurance'
            ).val();


        // let montantAssurance = 0;

        // let montantPatient =
        //     montant;

        // if (
        //     assurance === 'oui' &&
        //     taux > 0
        // ) {

        //     montantAssurance =
        //         Math.floor(
        //             (
        //                 montant *
        //                 taux
        //             ) / 100
        //         );


        //     montantAssurance =
        //         Math.min(
        //             montant,
        //             Math.max(
        //                 0,
        //                 montantAssurance
        //             )
        //         );


        //     montantPatient =
        //         Math.max(
        //             0,
        //             montant -
        //             montantAssurance
        //         );

        // }

        const assuranceInput =
            bloc.find('.produit-montant-assurance');

        const assuranceModifiee =
            assuranceInput.attr('data-manual') === '1';

        let montantAssurance =
            getNumber(
                assuranceInput.val()
            );

        if (assurance !== 'oui') {

            montantAssurance = 0;

            assuranceInput.attr(
                'data-manual',
                '0'
            );

        } else if (!assuranceModifiee) {

            montantAssurance =
                Math.floor(
                    (
                        montant *
                        taux
                    ) / 100
                );
        }

        montantAssurance =
            Math.min(
                montant,
                Math.max(
                    0,
                    montantAssurance
                )
            );

        const montantPatient =
            Math.max(
                0,
                montant - montantAssurance
            );

        assuranceInput
            .val(
                formatMoney(montantAssurance)
            );

        bloc.find('.produit-montant-patient')
            .val(
                formatMoney(montantPatient)
            );


        /*
         * =========================================================
         * RETOUR
         * =========================================================
         */

        return {

            total:
                Math.max(
                    0,
                    montant
                ),

            assurance:
                Math.max(
                    0,
                    montantAssurance
                ),

            patient:
                Math.max(
                    0,
                    montantPatient
                )

        };

    }

    function initialiserEvenementsProduit(bloc) {

        /*
         * =========================================================
         * PRODUIT
         * =========================================================
         */

        bloc.on(
            'change',
            '.produit-select',
            function () {

                selectionnerProduit(bloc);

            }
        );


        /*
         * =========================================================
         * PRISE EN CHARGE ASSURANCE
         * =========================================================
         */

        bloc.on(
            'change',
            '.produit-assurance',
            function () {

                selectionnerAssuranceProduit(bloc);

            }
        );


        /*
         * =========================================================
         * QUANTITÉ
         * =========================================================
         */

        bloc.on(
            'input',
            '.produit-quantite',
            function () {

                let quantite =
                    parseInt(
                        $(this).val()
                    ) || 0;


                if (quantite < 1) {

                    quantite = 1;

                    $(this).val(1);

                }


                calculerBlocProduit(bloc);

                calculerMontantsSoins();

            }
        );


        /*
         * =========================================================
         * PRIX
         * =========================================================
         */

        bloc.on(
            'input',
            '.produit-prix',
            function () {

                formaterChampMontant(this);

                calculerBlocProduit(bloc);

                calculerMontantsSoins();

            }
        );

        bloc.on(
            'input',
            '.produit-montant-assurance',
            function () {

                formaterChampMontant(this);

                $(this).attr(
                    'data-manual',
                    '1'
                );

                calculerBlocProduit(bloc);

                calculerMontantsSoins();

            }
        );


        /*
         * =========================================================
         * SUPPRESSION
         * =========================================================
         */

        bloc.on(
            'click',
            '.btn-supprimer-produit',
            function () {

                bloc.remove();

                renumeroterBlocsProduits();

                calculerMontantsSoins();

            }
        );

    }

    function ajouterBlocSoins() {

        if (!patientGlobal) {

            showAlert(
                'ALERT',
                'Veuillez sélectionner un patient.',
                'warning'
            );

            return;

        }


        const container =
            $('#contenu_soins');


        const index =
            container.find('.soins-block').length + 1;


        const bloc = $(`
            <div
                class="
                    soins-block
                    border
                    rounded-3
                    p-3
                    mb-3
                "
            >

                <div
                    class="
                        d-flex
                        align-items-center
                        justify-content-between
                        mb-3
                    "
                >

                    <h6 class="fw-semibold mb-0">

                        <i class="ri-test-tube-line me-1"></i>

                        Soins ${index}

                    </h6>


                    <button
                        type="button"
                        class="
                            btn
                            btn-outline-danger
                            btn-sm
                            btn-supprimer-soins
                        "
                    >

                        <i class="ri-delete-bin-line"></i>

                    </button>

                </div>


                <div class="row gx-3">

                    <!-- Famille -->

                    <div class="col-lg-4 col-md-5 col-sm-12">

                        <div class="mb-3">

                            <label class="form-label">
                                Type de soins
                            </label>

                            <select
                                class="
                                    form-select
                                    soins-type
                                    select2
                                "
                            >

                                <option value="">
                                    Sélectionner
                                </option>

                            </select>

                        </div>

                    </div>


                    <!-- Examen -->

                    <div class="col-lg-8 col-md-7 col-sm-12">

                        <div class="mb-3">

                            <label class="form-label">
                                Soins
                            </label>

                            <select
                                class="
                                    form-select
                                    soins-select
                                    select2
                                "
                                disabled
                            >

                                <option value="">
                                    Sélectionner
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <div class="row gx-3">

                    <!-- Assurance -->
                    <div class="col-lg-3 col-md-6 col-sm-6">

                        <div class="mb-3">

                            <label class="form-label">
                                Prise en charge
                            </label>

                            <select
                                class="form-select soins-assurance select2"
                            >
                                <option value="non">
                                    Non
                                </option>
                            </select>

                        </div>

                    </div>


                    <!-- Quantité -->
                    <div class="col-lg-3 col-md-6 col-sm-6">

                        <div class="mb-3">

                            <label class="form-label">
                                Quantité
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control text-end soins-quantite"
                                value="1"
                                min="1"
                                step="1"
                            >

                        </div>

                    </div>


                    <!-- Prix unitaire -->
                    <div class="col-lg-3 col-md-6 col-sm-6">

                        <div class="mb-3">

                            <label class="form-label">
                                Prix unitaire
                            </label>

                            <div class="input-group">

                                <input
                                    type="tel"
                                    class="form-control text-end soins-prix"
                                    value="0"
                                >

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- Montant -->
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">

                        <label class="form-label">
                            Montant
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control text-end soins-montant"
                                value="0"
                                readonly
                            >

                            <span class="input-group-text">
                                FCFA
                            </span>

                        </div>

                    </div>


                    <!-- Part assurance -->
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">

                        <label class="form-label">
                            Part assurance
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control text-end soins-montant-assurance"
                                value="0"
                            >

                            <span class="input-group-text">
                                FCFA
                            </span>

                        </div>

                    </div>


                    <!-- Part patient -->
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-3">

                        <label class="form-label">
                            Part patient
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                class="form-control text-end soins-montant-patient"
                                value="0"
                                readonly
                            >

                            <span class="input-group-text">
                                FCFA
                            </span>

                        </div>

                    </div>

                </div>

            </div>
        `);


        container.append(bloc);


        /*
         * Remplir les familles
         */
        remplirTypeSoinsBloc(bloc);


        /*
         * Initialiser Select2 document.getElementById
         */
        // initialiserSelect2Bloc(bloc); --}}

        bloc.find('.soins-type').select2({

            theme: 'bootstrap',

            width: '100%',

            placeholder: 'Sélectionner',

            language: {

                noResults: function () {

                    return 'Aucun résultat trouvé';

                }

            }

        });

        /*
         * Assurance
         */
        initialiserAssuranceBloc('soins', bloc);


        /*
         * Événements du bloc
         */
        initialiserEvenementsBloc(bloc);


        updateSoinsIndexes();

    }

    function remplirTypeSoinsBloc(bloc) {

        const select =
            bloc.find('.soins-type');


        select.find('option:not(:first)').remove();


        typeSoins.forEach(function (item) {

            select.append(

                $('<option>', {

                    value:
                        item.code_typesoins,

                    text:
                        item.libelle_typesoins

                })

            );

        });

    }

    function initialiserSelect2Bloc(bloc) {

        bloc.find('.soins-type').select2({

            theme: 'bootstrap',

            width: '100%',

            placeholder: 'Sélectionner',

            language: {

                noResults: function () {

                    return 'Aucun résultat trouvé';

                }

            }

        });


        bloc.find('.soins-select').select2({

            theme: 'bootstrap',

            width: '100%',

            placeholder: 'Sélectionner',

            language: {

                noResults: function () {

                    return 'Aucun résultat trouvé';

                }

            }

        });

    }

    function initialiserAssuranceBloc(mode, bloc) {

        /*
         * Select de prise en charge
         */
        const select =
            mode === 'soins'
                ? bloc.find('.soins-assurance')
                : bloc.find('.produit-assurance');


        /*
         * Nettoyage des anciennes options
         */
        select
            .find('option[value="oui"]')
            .remove();


        /*
         * Toujours conserver NON
         */
        if (
            select.find('option[value="non"]').length === 0
        ) {

            select.prepend(
                $('<option>', {
                    value: 'non',
                    text: 'Non'
                })
            );

        }


        /*
         * Patient assuré
         */
        const assure =
            Number(patientGlobal?.assure) === 1;


        /*
         * Taux d'assurance
         */
        const taux =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(patientGlobal?.taux) || 0
                )
            );


        /*
         * Si assuré + taux > 0
         * alors proposer OUI
         */
        if (
            assure &&
            taux > 0
        ) {

            select.append(
                $('<option>', {
                    value: 'oui',
                    text: 'Oui'
                })
            );

        }


        /*
         * Par défaut :
         * Non
         */
        select
            .val('non')
            .trigger('change.select2');

    }

    function initialiserEvenementsBloc(bloc)
    {
        /*
         * Changement famille
         */
        bloc.find('.soins-type')
            .on('change', function () {

                const id = $(this).val();

                /*
                 * Reset des informations
                 */
                viderInformationsBloc(bloc);

                /*
                 * Reset select examen
                 */
                const soinsSelect = bloc.find('.soins-select');

                soinsSelect
                    .empty()
                    .append(
                        $('<option>', {
                            value: '',
                            text: 'Sélectionner'
                        })
                    )
                    .prop('disabled', true);


                calculerMontantsSoins();
                /*
                 * Aucune famille
                 */
                if (!id) {

                    calculerMontantsSoins();

                    return;
                }

                /*
                 * Charger les examens
                 */
                chargerTypeSoins(id, bloc);
            });


        /*
         * Changement examen
         */
        bloc.find('.soins-select')
            .on('change', function () {

                /*
                 * Mettre à jour les informations
                 * de l'examen sélectionné
                 */
                selectionnerSoins(bloc);

                /*
                 * Recalcul global immédiat
                 */
                calculerMontantsSoins();
            });


        /*
         * Changement prise en charge
         */
        bloc.find('.soins-assurance')
            .on('change', function () {

                /*
                 * Mettre à jour la prise en charge
                 * du bloc
                 */
                selectionnerAssuranceBloc(bloc);

                /*
                 * Recalcul global immédiat
                 */
                calculerMontantsSoins();
            });


        /*
         * Prix
         */
        bloc.find('.soins-prix')
            .on('input', function () {

                formaterChampMontant(this);

                /*
                 * Recalcul du bloc
                 */
                calculerBlocSoins(bloc);

                /*
                 * Recalcul global
                 */
                calculerMontantsSoins();
            });

        bloc.find('.soins-quantite')
            .on('input', function () {

                let quantite =
                    parseInt(
                        $(this).val()
                    ) || 0;


                if (quantite < 1) {

                    quantite = 1;

                    $(this).val(1);

                }


                calculerBlocSoins(bloc);

                calculerMontantsSoins();

            });

        bloc.find('.soins-montant-assurance')
            .on('input', function () {

                formaterChampMontant(this);

                $(this).attr(
                    'data-manual',
                    '1'
                );

                calculerBlocSoins(bloc);

                calculerMontantsSoins();

            });


        /*
         * Suppression
         */
        bloc.find('.btn-supprimer-soins')
            .on('click', function () {

                const nombreBlocs =
                    $('#contenu_soins .soins-block').length;

                /*
                 * Toujours garder au moins
                 * un bloc.
                 */
                if (nombreBlocs <= 1) {

                    showAlert(
                        'Alert',
                        'Au moins un examen doit être renseigné.',
                        'info'
                    );

                    return;
                }

                /*
                 * Suppression
                 */
                bloc.remove();

                /*
                 * Renumérotation
                 */
                updateSoinsIndexes();

                /*
                 * Recalcul global
                 */
                calculerMontantsSoins();
            });
    }

    function chargerTypeSoins(
        id,
        bloc
    ) {

        if (!patientGlobal) {
            return;
        }


        const codeassurance =
            patientGlobal.codeassurance || '';


        const cacheKey =
            `${id}_${codeassurance}`;


        /*
         * EXAMENS DÉJÀ EN CACHE
         */
        if (
            Object.prototype.hasOwnProperty.call(
                cachedSoins,
                cacheKey
            )
        ) {

            remplirSoinsBloc(

                bloc,

                cachedSoins[cacheKey]

            );

            return;

        }


        /*
         * REQUÊTE
         */
        showPreloader();


        $.ajax({

            url: globalUrl(
                '/api/select/soinsinfirmier/' + id
            ),

            type: 'GET',

            dataType: 'json',

            // data: {

            //     id: id,

            //     codeassurance:
            //         codeassurance,

            //     periode:
            //         periode

            // },


            success: function (data) {

                if (
                    Array.isArray(data.results) &&
                    data.results.length > 0
                ) {

                    /*
                     * Mise en cache
                     */
                    cachedSoins[cacheKey] =
                        data.results;


                    remplirSoinsBloc(

                        bloc,

                        data.results

                    );

                } else {

                    showAlert(

                        'ALERT',

                        "Aucun examen n'a été trouvé.",

                        'warning'

                    );

                }

            },


            error: function (
                xhr,
                status,
                error
            ) {

                console.error(

                    'Erreur chargement examens :',

                    error

                );


                console.error(

                    'Réponse serveur :',

                    xhr.responseText

                );


                showAlert(

                    'ALERT',

                    'Erreur lors du chargement des examens.',

                    'error'

                );

            },


            complete: function () {

                removePreloader();

            }

        });

    }

    function remplirSoinsBloc(
        bloc,
        soins
    ) {

        const select = bloc.find('.soins-select');

        select.empty()
            .append(

                $('<option>', {

                    value: '',

                    text: 'Sélectionner'

                })

            );


        soins.forEach(function (item) {

            select.append(

                $('<option>', {

                    value:
                        item.code_soins,

                    text:
                        item.libelle_soins,

                    'data-prix':
                        item.price ?? 0,

                })

            );

        });


        select.prop(
            'disabled',
            false
        );


        /*
         * Reinitialiser Select2
         */
        if (
            select.hasClass(
                'select2-hidden-accessible'
            )
        ) {

            select.select2('destroy');

        }

        select.select2({

            theme: 'bootstrap',

            width: '100%',

            placeholder: 'Sélectionner',

            language: {

                noResults: function () {

                    return 'Aucun résultat trouvé';

                }

            }

        });

    }

    function selectionnerSoins(bloc) {

        const select =
            bloc.find('.soins-select');

        const option =
            select.find(':selected');


        if (!select.val()) {

            viderInformationsBloc(bloc);

            calculerMontantsSoins();

            return;
        }


        const prix =
            Math.max(
                0,
                getNumber(
                    option.attr('data-prix')
                )
            );


        bloc.find('.soins-prix')
            .val(
                formatMoney(prix)
            );


        bloc.find('.soins-quantite')
            .val(1);


        calculerBlocSoins(bloc);

    }

    function selectionnerAssuranceBloc(bloc)
    {
        const selectSoins =
            bloc.find('.soins-select');

        const assuranceInput =
            bloc.find('.soins-montant-assurance');


        if (!selectSoins.val()) {

            bloc.find('.soins-assurance')
                .val('non')
                .trigger('change.select2');

            assuranceInput
                .val(formatMoney(0))
                .attr('data-manual', '0');

            bloc.find('.soins-montant-patient')
                .val(formatMoney(0));

            showAlert(
                'ALERT',
                'Veuillez sélectionner un soins.',
                'warning'
            );

            return;
        }


        /*
         * Nouveau choix de prise en charge :
         * on repart du calcul selon le taux.
         */

        assuranceInput.attr(
            'data-manual',
            '0'
        );

        calculerBlocSoins(bloc);

        calculerMontantsSoins();
    }

    function calculerBlocSoins(bloc)
    {
        const select =
            bloc.find('.soins-select');

        /*
         * =========================================================
         * AUCUN SOIN
         * =========================================================
         */

        if (!select.val()) {

            bloc.find('.soins-prix')
                .val(formatMoney(0));

            bloc.find('.soins-montant')
                .val(formatMoney(0));

            bloc.find('.soins-montant-assurance')
                .val(formatMoney(0));

            bloc.find('.soins-montant-patient')
                .val(formatMoney(0));

            return {
                total: 0,
                assurance: 0,
                patient: 0
            };
        }


        /*
         * =========================================================
         * PRIX
         * =========================================================
         */

        let prix =
            getNumber(
                bloc.find('.soins-prix').val()
            );

        prix = Math.max(0, prix);


        /*
         * =========================================================
         * QUANTITÉ
         * =========================================================
         */

        let quantite =
            parseInt(
                bloc.find('.soins-quantite').val()
            ) || 0;

        quantite =
            Math.max(1, quantite);


        /*
         * =========================================================
         * MONTANT TOTAL DU SOIN
         * =========================================================
         */

        const montant =
            Math.max(
                0,
                prix * quantite
            );


        bloc.find('.soins-quantite')
            .val(quantite);

        bloc.find('.soins-prix')
            .val(formatMoney(prix));

        bloc.find('.soins-montant')
            .val(formatMoney(montant));


        /*
         * =========================================================
         * PRISE EN CHARGE
         * =========================================================
         */

        const assuranceActive =
            bloc.find('.soins-assurance').val() === 'oui';


        /*
         * =========================================================
         * TAUX ASSURANCE
         * =========================================================
         */

        const taux =
            Math.max(
                0,
                Math.min(
                    100,
                    Number(patientGlobal?.taux) || 0
                )
            );


        /*
         * =========================================================
         * PART ASSURANCE
         * =========================================================
         */

        const assuranceInput =
            bloc.find('.soins-montant-assurance');

        const assuranceModifiee =
            assuranceInput.attr('data-manual') === '1';

        // let montantAssurance =
        //     getNumber(
        //         bloc.find('.soins-montant-assurance').val()
        //     );

        // if (!assuranceActive) {

        //     montantAssurance = 0;

        // } else {

        //     if (
        //         montantAssurance === 0 &&
        //         taux > 0
        //     ) {

        //         montantAssurance =
        //             Math.floor(
        //                 (
        //                     montant *
        //                     taux
        //                 ) / 100
        //             );

        //     }

        // }

        let montantAssurance =
            getNumber(assuranceInput.val());

        if (!assuranceActive) {

            montantAssurance = 0;

            assuranceInput.attr(
                'data-manual',
                '0'
            );

        } else if (!assuranceModifiee) {

            montantAssurance =
                Math.floor(
                    (
                        montant *
                        taux
                    ) / 100
                );
        }


        /*
         * Assurance jamais supérieure
         * au montant du bloc
         */

        montantAssurance =
            Math.min(
                montant,
                Math.max(
                    0,
                    montantAssurance
                )
            );


        /*
         * =========================================================
         * PART PATIENT
         * =========================================================
         */

        const montantPatient =
            Math.max(
                0,
                montant - montantAssurance
            );


        /*
         * =========================================================
         * AFFICHAGE
         * =========================================================
         */

        bloc.find('.soins-montant-assurance')
            .val(
                formatMoney(montantAssurance)
            );

        bloc.find('.soins-montant-patient')
            .val(
                formatMoney(montantPatient)
            );


        /*
         * =========================================================
         * RETOUR
         * =========================================================
         */

        return {

            total:
                montant,

            assurance:
                montantAssurance,

            patient:
                montantPatient

        };
    }

    function calculerMontantsSoins() {

        let montantTotal = 0;

        let totalProduits = 0;

        let totalSoins = 0;

        let montantAssurance = 0;

        let montantPatient = 0;


        /*
         * =========================================================
         * SOINS
         * =========================================================
         */

        $('#contenu_soins .soins-block')
            .each(function () {

                const bloc = $(this);


                const resultat =
                    calculerBlocSoins(bloc);


                totalSoins +=
                    Math.max(
                        0,
                        resultat.total
                    );


                montantAssurance +=
                    Math.max(
                        0,
                        resultat.assurance
                    );


                montantPatient +=
                    Math.max(
                        0,
                        resultat.patient
                    );

            });


        /*
         * =========================================================
         * PRODUITS
         * =========================================================
         *
         * Un bloc produit peut être vide.
         * Dans ce cas calculerBlocProduit()
         * retourne simplement 0.
         */

        $('#contenu_produit .produit-block')
            .each(function () {

                const bloc = $(this);


                const resultat =
                    calculerBlocProduit(bloc);


                totalProduits +=
                    Math.max(
                        0,
                        resultat.total
                    );


                montantAssurance +=
                    Math.max(
                        0,
                        resultat.assurance
                    );


                montantPatient +=
                    Math.max(
                        0,
                        resultat.patient
                    );

            });


        /*
         * =========================================================
         * REMISE
         * =========================================================
         */

        let remise =
            Math.max(
                0,
                getNumber(
                    $('#taux_remise').val() ?? 0
                )
            );


        /*
         * La remise ne peut pas dépasser
         * la part patient.
         */

        remise =
            Math.min(
                remise,
                montantPatient
            );


        montantPatient =
            Math.max(
                0,
                montantPatient -
                remise
            );


        /*
         * =========================================================
         * TOTAL GLOBAL
         * =========================================================
         */

        montantTotal =
            totalSoins +
            totalProduits;


        /*
         * =========================================================
         * AFFICHAGE
         * =========================================================
         */

        $('#taux_remise')
            .val(
                formatMoney(remise)
            );


        $('#montant_total')
            .val(
                formatMoney(montantTotal)
            );

        $('#montant_total_soins')
            .val(
                formatMoney(totalSoins)
            );

        $('#montant_total_produit')
            .val(
                formatMoney(totalProduits)
            );


        $('#montant_assurance_soins')
            .val(
                formatMoney(montantAssurance)
            );


        $('#montant_patient_soins')
            .val(
                formatMoney(montantPatient)
            );


        $('#patient_taux')
            .val(
                Number(
                    patientGlobal?.taux
                ) || 0
            );


        /*
         * =========================================================
         * RETOUR
         * =========================================================
         */

        return {

            total:
                Math.max(
                    0,
                    montantTotal
                ),

            assurance:
                Math.max(
                    0,
                    montantAssurance
                ),

            patient:
                Math.max(
                    0,
                    montantPatient
                ),

            remise:
                Math.max(
                    0,
                    remise
                )

        };

    }

    function updateSoinsIndexes() {

        $('#contenu_soins .soins-block')
            .each(function (index) {

                $(this)
                    .find('h6')
                    .first()
                    .html(`

                        <i class="ri-test-tube-line me-1"></i>

                        EXAMEN ${index + 1}

                    `);

            });

    }

    function viderInformationsBloc(bloc) {
        bloc.find('.soins-prix').val(0);
        bloc.find('.soins-quantite').val(1);
        bloc.find('.soins-montant').val(0);
    }

    function formaterChampMontant(input) {

        const valeur =
            getNumber(
                input.value
            );


        input.value =
            formatMoney(
                valeur
            );
    }

    function validerSoins(
        afficherMessage = true
    ) {

        /*
         * Patient
         */
        const patientId =
            $('#patient_id').val();


        if (!patientId) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    'Veuillez sélectionner un patient.',
                    'warning'
                );

            }

            return false;

        }


        /*
         * Médecin
         */
        const medecin =
            $.trim(
                $('#medecin').val()
            );


        // if (!medecin) {

        //     if (afficherMessage) {

        //         showAlert(
        //             'ALERT',
        //             'Veuillez saisir le nom du médecin.',
        //             'warning'
        //         );

        //     }

        //     return false;

        // }


        /*
         * Blocs
         */
        const blocsSoins =
            $('#contenu_soins .soins-block');


        if (blocsSoins.length === 0) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    "Veuillez ajouter au moins un soins.",
                    'warning'
                );

            }

            return false;

        }


        const soinsUtilises =
            new Set();


        let valide =
            true;


        blocsSoins.each(function () {

            if (!valide) {
                return false;
            }


            const bloc =
                $(this);


            /*
             * Famille
             */
            const type =
                bloc.find(
                    '.soins-type'
                ).val();


            if (!type) {

                if (afficherMessage) {

                    showAlert(
                        'ALERT',
                        "Veuillez sélectionner le type d'examen de chaque bloc.",
                        'warning'
                    );

                }

                valide = false;

                return false;

            }


            /*
             * Examen
             */
            const soins =
                bloc.find(
                    '.soins-select'
                ).val();


            if (!soins) {

                if (afficherMessage) {

                    showAlert(
                        'ALERT',
                        "Veuillez sélectionner un soins dans chaque bloc.",
                        'warning'
                    );

                }

                valide = false;

                return false;

            }


            /*
             * Pas de doublon
             */
            if (
                soinsUtilises.has(
                    soins
                )
            ) {

                if (afficherMessage) {

                    showAlert(
                        'ALERT',
                        "Le même soins ne peut pas être sélectionné plusieurs fois.",
                        'warning'
                    );

                }

                valide = false;

                return false;

            }


            soinsUtilises.add(
                soins
            );


            /*
             * Prix
             */
            const prix =
                getNumber(
                    bloc.find(
                        '.soins-prix'
                    ).val()
                );


            if (prix < 0) {

                if (afficherMessage) {

                    showAlert(
                        'ALERT',
                        'Le prix ne peut pas être négatif.',
                        'warning'
                    );

                }

                valide = false;

                return false;

            }

        });


        if (!valide) {
            return false;
        }


        /*
         * Calcul global
         */
        const montants =
            calculerMontantsSoins();


        /*
         * Vérification identité comptable
         *
         * Total = Assurance + Patient + Remise
         */
        const total =
            montants.total;


        const assurance =
            montants.assurance;


        const patient =
            montants.patient;


        const remise =
            montants.remise;


        if (
            total !==
            assurance +
            patient +
            remise
        ) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    'Les montants sont incohérents. Veuillez vérifier les données.',
                    'warning'
                );

            }

            return false;

        }


        /*
         * Aucun négatif
         */
        if (
            total < 0 ||
            assurance < 0 ||
            patient < 0 ||
            remise < 0
        ) {

            if (afficherMessage) {

                showAlert(
                    'ALERT',
                    'Les montants ne peuvent pas être négatifs.',
                    'warning'
                );

            }

            return false;

        }


        return true;

    }

    function construireSelectionsSoins() {

        const selections =
            [];


        $('#contenu_soins .soins-block')
            .each(function () {

                const bloc =
                    $(this);


                const option =
                    bloc.find(
                        '.soins-select option:selected'
                    );


                if (!option.val()) {
                    return;
                }

                const quantite =
                    parseInt(
                        bloc.find('.soins-quantite').val()
                    ) || 1;

                const prix =
                    getNumber(
                        bloc.find('.soins-prix').val()
                    );

                const montant =
                    prix * quantite;


                const resultat =
                    calculerBlocSoins(bloc);

                selections.push({

                    type:
                        bloc.find('.soins-type').val(),

                    id:
                        option.val(),

                    soins:
                        option.text().trim(),

                    assurance:
                        bloc.find('.soins-assurance').val(),

                    quantite:
                        quantite,

                    prix:
                        prix,

                    montant:
                        resultat.total,

                    montant_assurance:
                        resultat.assurance,

                    montant_patient:
                        resultat.patient

                });

            });


        return selections;

    }

    function enregistrerSoins() {

        if (!validerSoins(true)) {
            return false;
        }


        const selectionsSoins =
            construireSelectionsSoins();

        const selectionsProduits =
            construireSelectionsProduits();


        if (
            selectionsSoins.length === 0
        ) {

            showAlert(
                'ALERT',
                "Aucun examen n'a été sélectionné.",
                'warning'
            );

            return false;

        }


        const montants =
            calculerMontantsSoins();

        /*
         * Informations générales
         */
        const patientId =
            $('#patient_id').val();

        const medecin =
            $.trim(
                $('#medecin').val()
            );


        const numhosp =
            $.trim(
                $('#numhosp').val()
            );


        const numcode =
            $.trim(
                $('#numcode').val()
            );


        const rensg =
            $.trim(
                $('#rensg').val()
            );


        const taux =
            Number(
                $('#patient_taux').val()
            ) || 0;


        const remise =
            montants.remise;


        /*
         * Vérification finale des montants
         */
        if (
            montants.total <
            0 ||
            montants.assurance <
            0 ||
            montants.patient <
            0 ||
            remise < 0
        ) {

            showAlert(
                'ALERT',
                'Les montants sont invalides.',
                'warning'
            );

            return false;

        }


        /*
         * Payload
         */
        const data = {

            selectionsSoins:
                selectionsSoins,

            selectionsProduits:
                selectionsProduits,

            patient_id:
                patientId,

            codeassurance:
                patientGlobal.codeassurance,

            codesocieteassure:
                patientGlobal.codesocieteassure,

            medecin:
                medecin,

            numhosp:
                numhosp || null,

            numcode:
                numcode || null,

            rensg:
                rensg || null,

            taux:
                taux,

            montantA:
                montants.assurance,

            montantP:
                montants.patient,

            montantT:
                montants.total,

            remise:
                remise,

        };


        // console.log(
        //     'Données soins :',
        //     data
        // );

        /*
         * Loader
         */
        showPreloader();


        requestWithCsrf(
                'POST',
                $('#url').attr('content') + '/api/soins/create',
                data
            )
            .done(function (response) {

                removePreloader();


                if (response.success) {

                    resetVoletGlobal();


                    showAlert(
                        'ALERT',
                        'Opération effectuée.',
                        'success'
                    );

                    soinsTable.page = 1;
                    soinsTable.load();

                    const tab =
                        document.getElementById(
                            'tab-oneAAA'
                        );


                    if (tab) {

                        const newTab =
                            new bootstrap.Tab(tab);

                        newTab.show();

                    }


                } else if (
                    response.existe
                ) {

                    showAlert(
                        'ALERT',
                        'Ce numéro de prise en charge existe déjà.',
                        'warning'
                    );


                } else if (
                    response.num_hosp_liberer
                ) {

                    showAlert(
                        'ALERT',
                        "Le patient lié à ce numéro d'hospitalisation a déjà été libéré.",
                        'warning'
                    );


                } else if (
                    response.matricule_hosp_error
                ) {

                    showAlert(
                        'ALERT',
                        "Le patient n'est pas lié à ce numéro d'hospitalisation.",
                        'warning'
                    );


                } else if (
                    response.num_hosp_introuvable
                ) {

                    showAlert(
                        'ALERT',
                        "Le numéro d'hospitalisation est introuvable.",
                        'warning'
                    );


                } else {

                    showAlert(
                        'ERREUR',
                        "Une erreur est survenue lors de l'enregistrement.",
                        'error'
                    );

                }

            })
            .fail(function (xhr, textStatus, errorThrown) {

                console.error('Erreur consultation :', {
                    status: xhr?.status,
                    statusText: xhr?.statusText,
                    responseText: xhr?.responseText,
                    textStatus: textStatus,
                    errorThrown: errorThrown,
                    readyState: xhr?.readyState
                });

                removePreloader();

                showAlert(
                    'ERREUR',
                    "Une erreur est survenue lors de l'enregistrement.",
                    'error'
                );
            });

    }

    function resetVoletGlobal() {

        /*
         * Patient
         */
        patientGlobal = null;


        /*
         * Champs
         */
        $('#numhosp').val('');

        $('#medecin').val('');

        $('#numcode').val('');

        $('#rensg').val('');

        $('#patient_taux').val(0);

        $('#taux_remise').val(0);

        $('#montant_total').val(0);

        $('#montant_patient_soins').val(0);

        $('#montant_total_produit').val(0);

        $('#montant_total_soins').val(0);

        $('#montant_assurance_soins').val(0);


        /*
         * Contenu
         */
        $('#contenu_soins')
            .empty();

        $('#contenu_produit')
            .empty();

        /*
         * Sections
         */
        $('#div_info_patient')
            .empty()
            .hide();

        $('#select_periode_div')
            .hide();

        $('#select_soins_div')
            .hide();

        $('#div_numcode')
            .hide();

        cachedSoins = {};

    }

    function allowOnlyNumbersAndLetters(event) {

        const key =
            event.key;


        /*
         * Autoriser les touches de contrôle
         */
        if (
            key === 'Backspace' ||
            key === 'Delete' ||
            key === 'Tab' ||
            key === 'ArrowLeft' ||
            key === 'ArrowRight'
        ) {

            return;

        }


        if (
            !/^[a-zA-Z0-9]+$/.test(key)
        ) {

            event.preventDefault();

        }

    }

    function allowOnlyLetters(event) {

        const key =
            event.key;


        if (
            !/^[a-zA-ZÀ-ÿ\s]+$/.test(key)
        ) {

            event.preventDefault();

        }

    }

    function allowOnlyNumbers(event) {

        const key =
            event.key;


        if (
            !/^[0-9]+$/.test(key)
        ) {

            event.preventDefault();

        }

    }

    // -----------------------------------------------

    let soinsTable = new CustomTable({

        selector: '#soinsTable',

        url:
            $('#url').attr('content') + '/api/soins',

        perPage: 15,

        searchPlaceholder:
            'N° facture, dossier, patient...',

        searchButtonText:
            'Rechercher',

        params: function () {

            return {

            };

        },

        columns: [

            {
                label: 'N°',
                data: null,

                render: function (
                    value,
                    row,
                    index
                ) {

                    return (
                        (
                            soinsTable.meta
                                .from || 1
                        ) + index
                    );

                }
            },


            {
                label: 'N° Dossier',
                data: 'numdossier',

                render: function (value) {

                    return value
                        ? soinsTable.escape(value)
                        : '<span class="text-muted">Aucun</span>';

                }
            },


            {
                label: 'Nom et Prénoms',
                data: 'nom_patient',

                render: function (value) {

                    return value
                        ? soinsTable.escape(value)
                        : '<span class="text-muted">Inconnu</span>';

                }
            },

            {
                label: 'Nb soins',
                data: 'nbre_soins',

                render: function (value) {

                    return `
                        <strong class="text-primary">
                            ${value ?? 0}
                        </strong>
                    `;

                }
            },

            {
                label: 'Nb produits',
                data: 'nbre_produits',

                render: function (value) {

                    return `
                        <strong class="text-primary">
                            ${value ?? 0}
                        </strong>
                    `;

                }
            },


            {
                label: 'Montant',
                data: 'montant_total',

                render: function (value) {

                    return `
                        <strong>
                            ${formatPriceT(value)} Fcfa
                        </strong>
                    `;

                }
            },

            {
                label: 'Part patient',
                data: 'partpatient',

                render: function (value) {

                    return `
                        <strong>
                            ${formatPriceT(value)} Fcfa
                        </strong>
                    `;

                }
            },

            {
                label: 'Part assurance',
                data: 'partassurance',

                render: function (value) {

                    return `
                        <strong>
                            ${formatPriceT(value)} Fcfa
                        </strong>
                    `;

                }
            },

            {
                label: 'N° Facture',
                data: 'numfac_soins'
            },


            {
                label: 'Date',
                data: 'date_soin',

                render: function (value) {

                    return formatDate(value);

                }
            }

        ],


        actions: [

            {
                name: 'facture',

                label: 'Imprimer facture',

                icon: 'ri-printer-fill',

                class: 'text-info'
            },


            {
                name: 'detail',

                label: 'Détails',

                icon: 'ri-eye-fill',

                class: 'text-primary'
            },


            {
                name: 'delete',

                label: 'Supprimer',

                icon: 'ri-delete-bin-line',

                class: 'text-danger',

                visible: function (row) {

                    return (
                        parseFloat(row.montant_regle) === 0
                    );

                }

            }

        ],


        onAction: function (action, row) {

            if (action === 'facture') {

                window.showPreloader();

                const id = row.id_soins;

                fetch( $('#url').attr('content') + `/api/soins/detail/${id}`)
                    .then(response => response.json())

                    .then(data => {

                        window.hidePreloader();

                        const facture = data.facture;
                        const soins = data.soins;
                        const produits = data.produits;

                        pdfFactureSoins(facture, soins, produits);

                    })

                    .catch(error => {

                        window.hidePreloader();

                        console.error(
                            'Erreur lors du chargement des données:',
                            error
                        );

                    });

            }


            if (action === 'detail') {

                const id = row.id_soins;

                const content = `

                    <!-- =========================
                         SOINS
                    ========================== -->

                    <div class="mb-4">

                        <h6 class="fw-bold mb-3">
                            <i class="ri-heart-pulse-line me-1"></i>
                            Soins
                        </h6>

                        <div class="table-responsive">

                            <table
                                class="table table-bordered align-middle"
                                id="TableSoins"
                            >

                                <thead>
                                    <tr>
                                        <th>Soins</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                        <th>Part assurance</th>
                                        <th>Part patient</th>
                                        <th>Prise en charge ?</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-4"
                                        >

                                            <div
                                                class="spinner-border text-warning me-2"
                                                role="status"
                                                aria-hidden="true"
                                            ></div>

                                            <strong>
                                                Chargement des soins...
                                            </strong>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>


                    <!-- =========================
                         PRODUITS
                    ========================== -->

                    <div class="mb-4">

                        <h6 class="fw-bold mb-3">
                            <i class="ri-medicine-bottle-line me-1"></i>
                            Produits
                        </h6>

                        <div class="table-responsive">

                            <table
                                class="table table-bordered align-middle"
                                id="TableProduits"
                            >

                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                        <th>Part assurance</th>
                                        <th>Part patient</th>
                                        <th>Prise en charge ?</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-4"
                                        >

                                            <div
                                                class="spinner-border text-warning me-2"
                                                role="status"
                                                aria-hidden="true"
                                            ></div>

                                            <strong>
                                                Chargement des produits...
                                            </strong>

                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                `;

                window.showPreloader();


                fetch(
                    $('#url').attr('content') +
                    `/api/soins/detail/${encodeURIComponent(id)}`
                )

                    .then(response => {

                        if (!response.ok) {

                            throw new Error(
                                `Erreur HTTP ${response.status}`
                            );

                        }

                        return response.json();

                    })

                    .then(data => {

                        window.hidePreloader();

                        const soins =
                            data.soins ?? [];

                        const produits =
                            data.produits ?? [];


                        /*
                        |--------------------------------------------------------------------------
                        | OUVERTURE DU MODAL
                        |--------------------------------------------------------------------------
                        */

                        window.openModal({

                            title: 'Détail des soins',

                            content: content,

                            size: 'modal-xl'

                        });


                        /*
                        |--------------------------------------------------------------------------
                        | TABLE SOINS
                        |--------------------------------------------------------------------------
                        */

                        const tbodySoins =
                            document.querySelector(
                                '#globalModal #TableSoins tbody'
                            );


                        if (tbodySoins) {

                            if (soins.length === 0) {

                                tbodySoins.innerHTML = `

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center text-muted py-4"
                                        >

                                            Aucun soin enregistré.

                                        </td>

                                    </tr>

                                `;

                            } else {

                                tbodySoins.innerHTML = soins.map(
                                    function (soin) {

                                        const prix =
                                            parseFloat(
                                                soin.price ?? 0
                                            );

                                        const qte =
                                            parseInt(
                                                soin.qte ?? 0
                                            ) || 0;

                                        const montant =
                                            soin.total != null
                                                ? parseFloat(soin.total) || 0
                                                : prix * qte;

                                        const montantAssurance =
                                            parseFloat(
                                                soin.part_assurance ?? 0
                                            );

                                        const montantPatient =
                                            parseFloat(
                                                soin.part_patient ?? 0
                                            );

                                        const assure =
                                            Number(soin.assure) === 1 ||
                                            soin.assure === true;


                                        return `

                                            <tr>

                                                <td>
                                                    <strong>
                                                        ${soinsTable.escape(
                                                            soin.name ?? ''
                                                        )}
                                                    </strong>
                                                </td>

                                                <td class="text-end">

                                                    ${formatPriceT(prix)}
                                                    Fcfa

                                                </td>

                                                <td class="text-center">

                                                    ${qte}

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montant)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montantAssurance)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montantPatient)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-center">

                                                    ${
                                                        assure

                                                            ? `
                                                                <strong class="text-success">
                                                                    Oui
                                                                </strong>
                                                            `

                                                            : `
                                                                <strong class="text-danger">
                                                                    Non
                                                                </strong>
                                                            `
                                                    }

                                                </td>

                                            </tr>

                                        `;

                                    }
                                ).join('');


                                /*
                                |--------------------------------------------------------------------------
                                | TOTAL SOINS
                                |--------------------------------------------------------------------------
                                */

                                const totalSoins =
                                    soins.reduce(
                                        function (total, soin) {

                                            const prix =
                                                parseFloat(
                                                    soin.price ?? 0
                                                ) || 0;

                                            const qte =
                                                parseInt(
                                                    soin.qte ?? 0
                                                ) || 0;

                                            const montant =
                                                soin.total != null
                                                    ? parseFloat(soin.total) || 0
                                                    : prix * qte;

                                            return total + montant;

                                        },
                                        0
                                    );


                                tbodySoins.insertAdjacentHTML(
                                    'beforeend',
                                    `

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="text-center fw-bold"
                                            >
                                                Total soins
                                            </td>

                                            <td
                                                colspan="1"
                                                class="text-end fw-bold"
                                            >

                                                ${formatPriceT(totalSoins)}
                                                Fcfa

                                            </td>

                                        </tr>

                                    `
                                );

                            }

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TABLE PRODUITS
                        |--------------------------------------------------------------------------
                        */

                        const tbodyProduits =
                            document.querySelector(
                                '#globalModal #TableProduits tbody'
                            );


                        if (tbodyProduits) {

                            if (produits.length === 0) {

                                tbodyProduits.innerHTML = `

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center text-muted py-4"
                                        >

                                            Aucun produit enregistré.

                                        </td>

                                    </tr>

                                `;

                            } else {

                                tbodyProduits.innerHTML = produits.map(
                                    function (produit) {

                                        const prix =
                                            parseFloat(
                                                produit.price ?? 0
                                            );

                                        const qte =
                                            parseInt(
                                                produit.qte ?? 0
                                            ) || 0;

                                        const montant =
                                            produit.total != null
                                                ? parseFloat(produit.total) || 0
                                                : prix * qte;

                                        const montantAssurance =
                                            parseFloat(
                                                produit.part_assurance ?? 0
                                            );

                                        const montantPatient =
                                            parseFloat(
                                                produit.part_patient ?? 0
                                            );

                                        const assure =
                                            Number(produit.assure) === 1 ||
                                            produit.assure === true;


                                        return `

                                            <tr>

                                                <td>

                                                    <strong>
                                                        ${soinsTable.escape(
                                                            produit.name ?? ''
                                                        )}
                                                    </strong>

                                                </td>

                                                <td class="text-end">

                                                    ${formatPriceT(prix)}
                                                    Fcfa

                                                </td>

                                                <td class="text-center">

                                                    ${qte}

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montant)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montantAssurance)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-end">

                                                    <strong>
                                                        ${formatPriceT(montantPatient)}
                                                        Fcfa
                                                    </strong>

                                                </td>

                                                <td class="text-center">

                                                    ${
                                                        assure

                                                            ? `
                                                                <strong class="text-success">
                                                                    Oui
                                                                </strong>
                                                            `

                                                            : `
                                                                <strong class="text-danger">
                                                                    Non
                                                                </strong>
                                                            `
                                                    }

                                                </td>

                                            </tr>

                                        `;

                                    }
                                ).join('');


                                /*
                                |--------------------------------------------------------------------------
                                | TOTAL PRODUITS
                                |--------------------------------------------------------------------------
                                */

                                const totalProduits =
                                    produits.reduce(
                                        function (total, produit) {

                                            const prix =
                                                parseFloat(
                                                    produit.price ?? 0
                                                ) || 0;

                                            const qte =
                                                parseInt(
                                                    produit.qte ?? 0
                                                ) || 0;

                                            const montant =
                                                produit.total != null
                                                    ? parseFloat(produit.total) || 0
                                                    : prix * qte;

                                            return total + montant;

                                        },
                                        0
                                    );


                                tbodyProduits.insertAdjacentHTML(
                                    'beforeend',
                                    `

                                        <tr>

                                            <td
                                                colspan="3"
                                                class="text-center fw-bold"
                                            >
                                                Total produits
                                            </td>

                                            <td
                                                colspan="1"
                                                class="text-end fw-bold"
                                            >

                                                ${formatPriceT(totalProduits)}
                                                Fcfa

                                            </td>

                                        </tr>

                                    `
                                );

                            }

                        }

                    })

                    .catch(error => {

                        console.error(
                            'Erreur lors du chargement des soins :',
                            error
                        );


                        const tbodySoins =
                            document.querySelector(
                                '#globalModal #TableSoins tbody'
                            );

                        if (tbodySoins) {

                            tbodySoins.innerHTML = `

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-danger py-4"
                                    >

                                        Impossible de charger les soins.

                                    </td>

                                </tr>

                            `;

                        }


                        const tbodyProduits =
                            document.querySelector(
                                '#globalModal #TableProduits tbody'
                            );

                        if (tbodyProduits) {

                            tbodyProduits.innerHTML = `

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-danger py-4"
                                    >

                                        Impossible de charger les produits.

                                    </td>

                                </tr>

                            `;

                        }

                    });

            }

            if (action === 'delete') {

                window.confirmAction(
                    "Confirmation requise",
                    "Cette opération est irréversible. Êtes-vous sûr de vouloir effectuer cette action ?"
                ).then(function (result) {

                    if (!result.isConfirmed) {
                        return;
                    }

                    window.showPreloader();

                    $.ajax({

                        url: $('#url').attr('content') + '/api/soins/delete/' + encodeURIComponent(row.id_soins),

                        method: 'delete',

                        success: function (response) {

                            if (response.success) {

                                soinsTable.page = 1;
                                soinsTable.load();

                                showAlert(
                                    'Succès',
                                    response.message ?? 'Opération effectuée.',
                                    'success'
                                );

                            } else if (response.warn) {

                                showAlert(
                                    'Alerte',
                                    response.message ?? "Une erreur c'est produite, réessayer.",
                                    'warning'
                                );

                            } else {

                                showAlert(
                                    'Erreur',
                                    response.message ?? "Échec de l'opération.",
                                    'error'
                                );

                            }

                        },

                        error: function () {

                            showAlert(
                                'Erreur',
                                "Echc de l'opération",
                                'error'
                            );

                        },

                        complete: function () {

                            window.hidePreloader();
                        }

                    });

                });
            }

        }

    });

});