$(document).ready(function() {

select_patient('#id_patient');

OffClick('#btn_eng', eng);
OffClick('#btn_remiseForm', resetForm);

OffChange('#id_patient', rech_dosier);

OffChange('#periode', function () {
    updateAmounts();
});

OffChange('#typeacte_idS', function () {
    updateAmounts();
});

OffChange('#assurance_utiliser', function () {
    updateAmounts();
});

OffChange('#appliq_remise', function () {
    updateAmounts();
});


OffInput('#montant_total', function () {

    const montantActe =
        Math.max(
            0,
            toNumber(this.value)
        );

    $(this).val(
        formatPrice(montantActe)
    );

    calculateAmounts(
        montantActe,
        true
    );
});

OffInput('#montant_assurance', function () {

    /*
     * Toute modification manuelle de l'assurance
     * annule la remise.
     */
    $('#taux_remise').val(
        formatPrice(0)
    );

    /*
     * On repart toujours du montant de l'acte.
     */
    const montantActe = Math.max(
        0,
        toNumber(
            $('#montant_total').val()
        )
    );

    if (montantActe <= 0) {

        clearAmounts();

        return;
    }

    calculateAmounts(
        montantActe
    );
});

OffInput('#taux_remise', function () {

    let value =
        Math.max(
            0,
            toNumber(this.value)
        );

    $(this).val(
        formatPrice(value)
    );

    updateAmounts();
});

function getSelectedPrice() {

    const option =
        $('#typeacte_idS option:selected');

    if (!option.val()) {
        return 0;
    }

    switch ($('#periode').val()) {

        case '0':
            return toNumber(
                option.data('prixj')
            );

        case '1':
            return toNumber(
                option.data('prixn')
            );

        case '2':
            return toNumber(
                option.data('prixf')
            );

        default:
            return 0;
    }
}

function updateAmounts() {

    const prix =
        getSelectedPrice();

    if (!prix) {

        clearAmounts();

        return;
    }

    calculateAmounts(
        prix
    );
}

function calculateAmounts(prix, modificationTotal = false) {

    prix = Math.max(
        0,
        toNumber(prix)
    );

    if (prix <= 0) {

        clearAmounts();

        return;
    }

    /*
     * --------------------------------------------------------------
     * ASSURANCE
     * --------------------------------------------------------------
     */

    const assuranceUsed =
        $('#assurance_utiliser').val() === '1';

    let taux =
        toNumber(
            $('#patient_taux').val()
        );

    if (!assuranceUsed) {
        taux = 0;
    }

    taux = Math.max(
        0,
        Math.min(
            100,
            taux
        )
    );


    /*
     * --------------------------------------------------------------
     * PARTS INITIALES
     * --------------------------------------------------------------
     */

    const assuranceInitiale =
        Math.round(
            prix * taux / 100
        );

    const patientInitial =
        prix - assuranceInitiale;


    let assurance =
        assuranceInitiale;

    let patient =
        patientInitial;


    /*
     * --------------------------------------------------------------
     * REMISE
     * --------------------------------------------------------------
     */

    let remise =
        Math.max(
            0,
            toNumber(
                $('#taux_remise').val()
            )
        );

    const applicationRemise =
        $('#appliq_remise').val();


    /*
     * --------------------------------------------------------------
     * REMISE SUR ASSURANCE
     * --------------------------------------------------------------
     */

    if (
        remise > 0 &&
        applicationRemise === 'assurance' &&
        assuranceInitiale > 0
    ) {

        /*
         * Lors d'une modification du total,
         * on limite simplement la remise sans afficher
         * l'alerte.
         */
        if (remise >= assuranceInitiale) {

            remise =
                assuranceInitiale;

            assurance =
                0;

            if (!modificationTotal) {

                showAlert(
                    'Remise limitée',
                    `La remise ne peut pas dépasser la part assurance de ${formatPrice(assuranceInitiale)} FCFA. La remise a été automatiquement limitée à ce montant.`,
                    'warning'
                );
            }

        } else {

            assurance =
                assuranceInitiale - remise;
        }
    }


    /*
     * --------------------------------------------------------------
     * REMISE SUR PATIENT
     * --------------------------------------------------------------
     */

    else if (
        remise > 0 &&
        applicationRemise === 'patient' &&
        patientInitial > 0
    ) {

        if (remise >= patientInitial) {

            remise =
                patientInitial;

            patient =
                0;

            if (!modificationTotal) {

                showAlert(
                    'Remise limitée',
                    `La remise ne peut pas dépasser la part du patient de ${formatPrice(patientInitial)} FCFA. La remise a été automatiquement limitée à ce montant.`,
                    'warning'
                );
            }

        } else {

            patient =
                patientInitial - remise;
        }
    }


    /*
     * --------------------------------------------------------------
     * AFFICHAGE
     * --------------------------------------------------------------
     */

    $('#montant_total').val(
        formatPrice(prix)
    );

    $('#montant_assurance').val(
        formatPrice(assurance)
    );

    $('#montant_patient').val(
        formatPrice(patient)
    );

    $('#taux_remise').val(
        formatPrice(remise)
    );
}

function clearAmounts() {

    $('#montant_total').val('');

    $('#montant_assurance').val('');

    $('#montant_patient').val('');

    $('#taux_remise').val(
        formatPrice(0)
    );
}

function rech_dosier() {

    const patientId =
        $('#id_patient').val();

    if (
        !patientId ||
        !String(patientId).trim()
    ) {

        resetForm();

        showAlert(
            'Alert',
            'Veuillez sélectionner un patient.',
            'warning'
        );

        return;
    }

    showPreloader();

    api_rech_dossier(
        patientId,

        function (response) {

            hidePreloader();

            if (response.existep) {

                showAlert(
                    'Alert',
                    'Ce patient n\'existe pas.',
                    'error'
                );

                resetForm();

                return;
            }

            if (!response.success) {
                return;
            }

            addGroup(
                response.patient
            );

            $('#medecin_id')
                .val('')
                .trigger('change');

            select_list_medecin(
                '#medecin_id'
            );

            updateInsuranceVisibility(
                response.patient
            );

            loadTypeActes(
                response.patient.codeassurance
            );
        },

        function () {

            hidePreloader();

            showAlert(
                'Alert',
                'Une erreur est survenue lors de la recherche.',
                'error'
            );
        }
    );
}

function updateInsuranceVisibility(patient) {

    const insured =
        Number(patient.assure) === 1;

    /*
     * Affichage des éléments liés
     * à l'assurance.
     */
    $('#input_part_assurance, #div_assurance_utiliser, #div_numcode')
        .toggle(insured);

    /*
     * Affichage de l'option de remise
     * sur l'assurance.
     */
    $('#appliq_remise option[value="assurance"]')
        .toggle(insured);

    /*
     * Patient non assuré :
     * assurance désactivée
     * remise obligatoirement patient.
     */
    if (!insured) {

        $('#assurance_utiliser')
            .val('0');

        $('#appliq_remise')
            .val('patient');
    }
}

function loadTypeActes(codeAssurance) {

    const $select =
        $('#typeacte_idS');

    $select.empty();

    $select.append(
        $('<option>', {
            value: '',
            text: 'Sélectionner'
        })
    );

    $('#div_typeacteS, #div_medecin')
        .hide();

    api_select_list_typeacte(
        codeAssurance,

        function (response) {

            const actes =
                response.results || [];

            if (!actes.length) {

                $select.append(
                    $('<option>', {
                        value: '',
                        text: 'Aucune donnée disponible'
                    })
                );

                return;
            }

            actes.forEach(function (acte) {

                $select.append(
                    $('<option>', {

                        value:
                            acte.codgaran,

                        text:
                            acte.libgaran,

                        'data-prixj':
                            acte.prixj,

                        'data-prixn':
                            acte.prixn,

                        'data-prixf':
                            acte.prixf
                    })
                );
            });

            $('#div_typeacteS, #div_medecin')
                .show();

            $('.select2').select2({

                theme: 'bootstrap',

                width: '100%',

                placeholder: 'Sélectionner',

                language: {

                    noResults: function () {

                        return 'Aucun résultat trouvé';
                    }
                }
            });
        },

        function () {

            console.error(
                'Erreur lors du chargement des types d\'actes'
            );
        }
    );
}

function addGroup(data) {

    const $container =
        $('#div_info_patient');

    $container.empty();

    const assure =
        Number(data.assure) === 1;

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

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        N° dossier
                    </label>

                    <input
                        id="patient_numdossier"
                        value="${data.numdossier ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Nom et Prénoms
                    </label>

                    <input
                        value="${data.nomprenomspatient ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Contact
                    </label>

                    <input
                        value="${data.telpatient ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Assuré
                    </label>

                    <input
                        value="${assure ? 'Oui' : 'Non'}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <input
                type="hidden"
                id="patient_codeassurance"
                value="${data.codeassurance ?? ''}"
            >


            <input
                type="hidden"
                id="patient_taux"
                value="${assure ? data.taux : 0}"
            >
    `;


    /*
     * --------------------------------------------------------------
     * INFORMATIONS ASSURANCE
     * --------------------------------------------------------------
     */

    if (assure) {

        html += `

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Assurance
                    </label>

                    <input
                        value="${data.assurance ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Matricule assurance
                    </label>

                    <input
                        value="${data.matriculeassure ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Taux
                    </label>

                    <div class="input-group">

                        <input
                            value="${data.taux ?? 0}"
                            readonly
                            class="form-control"
                        >

                        <span class="input-group-text">
                            %
                        </span>

                    </div>

                </div>

            </div>


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Société
                    </label>

                    <input
                        value="${data.societe ?? ''}"
                        readonly
                        class="form-control"
                    >

                </div>

            </div>
        `;
    }


    html += `

        </div>


        <div class="col-12 mt-2 mb-3">

            <div
                class="d-flex align-items-center
                       border-top pt-3"
            >

                <div
                    class="d-flex align-items-center justify-content-center
                           bg-primary text-white rounded-circle me-3"
                    style="width: 40px; height: 40px;"
                >
                    <i class="ri-stethoscope-line fs-5"></i>
                </div>

                <div>

                    <h6 class="mb-1 fw-semibold">
                        Consultation et médecin
                    </h6>

                    <p class="text-muted mb-0 small">
                        Sélectionnez le type de consultation et le médecin
                    </p>

                </div>

            </div>

        </div>


        <div class="row gx-3 mb-4">


            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Période
                    </label>

                    <select
                        class="form-select"
                        id="periode"
                    >

                        <option value="">
                            Sélectionner
                        </option>

                        <option value="0">
                            Jour
                        </option>

                        <option value="1">
                            Nuit
                        </option>

                        <option value="2">
                            Férié
                        </option>

                    </select>

                </div>

            </div>


            <div
                class="col-xxl-3 col-lg-4 col-sm-6"
                id="div_typeacteS"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Acte
                    </label>

                    <select
                        class="form-select"
                        id="typeacte_idS"
                    ></select>

                </div>

            </div>


            <div
                class="col-xxl-3 col-lg-4 col-sm-6"
                id="div_medecin"
                style="display:none"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Médecin
                    </label>

                    <select
                        class="form-select"
                        id="medecin_id"
                    ></select>

                </div>

            </div>


            <div
                class="col-xxl-3 col-lg-4 col-sm-6"
                id="div_assurance_utiliser"
                style="display:none"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Utiliser l'assurance
                    </label>

                    <select
                        class="form-select"
                        id="assurance_utiliser"
                    >

                        <option value="1">
                            Oui
                        </option>

                        <option value="0">
                            Non
                        </option>

                    </select>

                </div>

            </div>


            <div
                class="col-xxl-3 col-lg-4 col-sm-6"
                id="div_numcode"
                style="display:none"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Numéro de bon
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            N°
                        </span>

                        <input
                            type="tel"
                            class="form-control"
                            id="mumcode"
                            placeholder="Facultatif"
                        >

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 MONTANT DE L'ACTE
                 ===================================================== -->

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Montant Total
                    </label>

                    <div class="input-group">

                        <input
                            type="tel"
                            class="form-control"
                            id="montant_total"
                        >

                        <span class="input-group-text">
                            Fcfa
                        </span>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 mt-2 mb-3">

            <div
                class="d-flex align-items-center
                       border-top pt-3"
            >

                <div
                    class="d-flex align-items-center justify-content-center
                           bg-success text-white rounded-circle me-3"
                    style="width: 40px; height: 40px;"
                >
                    <i class="ri-money-dollar-circle-line fs-5"></i>
                </div>

                <div>

                    <h6 class="mb-1 fw-semibold">
                        Montants et facturation
                    </h6>

                    <p class="text-muted mb-0 small">
                        Détails des montants, de la prise en charge et de la remise
                    </p>

                </div>

            </div>

        </div>


        <div class="row gx-3 mb-4">


            <!-- =====================================================
                 ASSURANCE
                 ===================================================== -->

            <div
                class="col-xxl-3 col-lg-4 col-sm-6"
                id="input_part_assurance"
                style="display:none"
            >

                <div class="mb-3">

                    <label class="form-label">
                        Part Assurance
                    </label>

                    <div class="input-group">

                        <input
                            readonly
                            type="tel"
                            class="form-control"
                            id="montant_assurance"
                        >

                        <span class="input-group-text">
                            Fcfa
                        </span>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 PATIENT
                 ===================================================== -->

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Part Patient
                    </label>

                    <div class="input-group">

                        <input
                            readonly
                            type="tel"
                            class="form-control"
                            id="montant_patient"
                        >

                        <span class="input-group-text">
                            Fcfa
                        </span>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 REMISE
                 ===================================================== -->

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Remise
                    </label>

                    <div class="input-group">

                        <input
                            type="tel"
                            class="form-control"
                            id="taux_remise"
                            value="0"
                        >

                        <span class="input-group-text">
                            Fcfa
                        </span>

                    </div>

                </div>

            </div>


            <!-- =====================================================
                 APPLICATION REMISE
                 ===================================================== -->

            <div class="col-xxl-3 col-lg-4 col-sm-6">

                <div class="mb-3">

                    <label class="form-label">
                        Application de la remise
                    </label>

                    <select
                        class="form-select"
                        id="appliq_remise"
                    >

                        <option value="patient">
                            Patient
                        </option>

                        <option value="assurance">
                            Assurance
                        </option>

                    </select>

                </div>

            </div>


            <div class="col-sm-12">

                <div class="d-flex gap-2 justify-content-center">

                    <button
                        type="button"
                        id="btn_remiseForm"
                        class="btn btn-outline-danger"
                    >
                        Remise à zéro
                    </button>


                    <button
                        type="button"
                        id="btn_eng"
                        class="btn btn-success"
                    >
                        Enregistrer
                    </button>

                </div>

            </div>

        </div>
    `;


    $container.html(html);
}

function resetForm() {

    $('#div_info_patient').empty();

    $('#id_patient')
        .val('')
        .trigger('change.select2');
}

function eng() {

    const patientId =
        $('#id_patient').val();

    const acteId =
        $('#typeacte_idS').val();

    const medecinId =
        $('#medecin_id').val();

    const periode =
        $('#periode').val();


    /*
     * Montants
     */
    const total =
        toNumber(
            $('#montant_total').val()
        );

    const assurance =
        toNumber(
            $('#montant_assurance').val()
        );

    const patient =
        toNumber(
            $('#montant_patient').val()
        );

    const remise =
        toNumber(
            $('#taux_remise').val()
        );


    /*
     * --------------------------------------------------------------
     * VALIDATION CHAMPS
     * --------------------------------------------------------------
     */

    if (
        !patientId ||
        !acteId ||
        !medecinId ||
        periode === ''
    ) {

        showAlert(
            'Alert',
            'Veuillez renseigner tous les champs obligatoires.',
            'warning'
        );

        return;
    }


    /*
     * --------------------------------------------------------------
     * VALIDATION DES MONTANTS
     * --------------------------------------------------------------
     *
     * La règle est :
     *
     *       ACTE = ASSURANCE + PATIENT + REMISE
     *
     */

    const totalReconstitue =
        assurance +
        patient +
        remise;


    if (
        Math.abs(
            totalReconstitue - total
        ) > 0
    ) {

        showAlert(
            'Alert',
            'Veuillez vérifier les différents montants.',
            'warning'
        );

        return;
    }


    /*
     * --------------------------------------------------------------
     * CONTRÔLE VALEURS NÉGATIVES
     * --------------------------------------------------------------
     */

    if (
        total < 0 ||
        assurance < 0 ||
        patient < 0 ||
        remise < 0
    ) {

        showAlert(
            'Alert',
            'Les montants ne peuvent pas être négatifs.',
            'warning'
        );

        return;
    }


    /*
     * Assurance ne peut pas dépasser l'acte.
     */
    if (assurance > total) {

        showAlert(
            'Alert',
            'Le montant assurance ne peut pas dépasser le montant de l\'acte.',
            'warning'
        );

        return;
    }


    /*
     * Patient ne peut pas dépasser l'acte.
     */
    if (patient > total) {

        showAlert(
            'Alert',
            'Le montant patient ne peut pas dépasser le montant de l\'acte.',
            'warning'
        );

        return;
    }


    /*
     * --------------------------------------------------------------
     * ENREGISTREMENT
     * --------------------------------------------------------------
     */

    showPreloader();

    requestWithCsrf(
        'POST',

        $('#url').attr('content') +
            '/api/consultations/create',

        {

            id_patient:
                patientId,

            typeacte_id:
                acteId,

            user_id:
                medecinId,

            periode:
                periode,

            montant_assurance:
                assurance,

            montant_patient:
                patient,

            taux_remise:
                remise,

            total:
                total,

            appliq_remise:
                $('#appliq_remise').val(),

            mumcode:
                $('#mumcode').val() ||
                null,

            assurance_utiliser:
                $('#assurance_utiliser').val(),

            codeassurance:
                $('#patient_codeassurance').val() ||
                null,

            patient_numdossier:
                $('#patient_numdossier').val() ||
                null,

            patient_taux:
                $('#patient_taux').val() ||
                0
        }
    )

    .done(function (response) {

        hidePreloader();

        if (response.success) {

            resetForm();

            consultationTable.page = 1;

            consultationTable.load();

            showAlert(
                'Succès',
                response.message ??
                    'Consultation enregistrée avec succès.',
                'success'
            );

        }

        else if (response.warn) {

            showAlert(
                'Alert',
                response.message ??
                    'Une erreur est survenue.',
                'warning'
            );

        }

        else {

            showAlert(
                'Alert',
                'Une erreur est survenue lors de l\'enregistrement.',
                'error'
            );
        }

    })

    .fail(function (
        xhr,
        textStatus,
        errorThrown
    ) {

        hidePreloader();

        console.error(
            'Erreur consultation :',
            {
                status:
                    xhr?.status,

                statusText:
                    xhr?.statusText,

                responseText:
                    xhr?.responseText,

                textStatus:
                    textStatus,

                errorThrown:
                    errorThrown,

                readyState:
                    xhr?.readyState
            }
        );

        showAlert(
            'Alert',
            'Une erreur est survenue lors de l\'enregistrement.',
            'error'
        );
    });
}

    // ------------------------------------------------------------------

    let consultationTable = new CustomTable({

        selector: '#consultationTable',

        url:
            $('#url').attr('content') +
            '/api/consultations',

        perPage: 15,

        searchPlaceholder:
            'N° dossier, patient, médecin...',

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
                            consultationTable.meta
                                .from || 1
                        ) + index
                    );

                }
            },


            {
                label: 'N° dossier',
                data: 'numdossier',

                render: function (value) {

                    return value
                        ? consultationTable.escape(value)
                        : '<span class="text-muted">Aucun</span>';

                }
            },


            {
                label: 'Nom et Prénoms',
                data: 'nom_patient'
            },


            {
                label: 'Médecin consultant',
                data: 'medecin'
            },


            {
                label: 'Spécialité',
                data: 'specialite'
            },


            {
                label: 'Montant',
                data: 'montant',

                render: function (value) {

                    return `
                        <strong>
                            ${formatPriceT(value)} Fcfa
                        </strong>
                    `;

                }
            },

            {
                label: 'Remise',
                data: 'remise',

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
                label: 'Taux',
                data: 'taux',

                render: function (value) {

                    return `
                        <strong>
                            ${parseInt(value ?? 0)} %
                        </strong>
                    `;

                }
            },


            {
                label: 'N° Facture',
                data: 'numfac'
            },


            {
                label: 'Date',
                data: 'date',

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
                name: 'fiche',

                label: 'Imprimer fiche',

                icon: 'ri-file-text-fill',

                class: 'text-primary'
            },


            {
                name: 'delete',

                label: 'Supprimer',

                icon: 'ri-delete-bin-line',

                class: 'text-danger',

                visible: function (row) {

                    return (
                        parseFloat( row.montant_regle ) === 0
                    );

                }

            }

        ],

        onAction: function (action, row) {

            if (action === 'facture') {

                window.showPreloader();

                const code = row.idconsexterne;

                fetch($('#url').attr('content') +`/api/consultations/detail/${encodeURIComponent(code)}`) // API endpoint
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    window.hidePreloader();

                    pdfFactureConsultation(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                });

            }

            if (action === 'fiche') {

                window.showPreloader();

                const code = row.idconsexterne;

                fetch($('#url').attr('content') +`/api/consultations/detail/${encodeURIComponent(code)}`)
                .then(response => response.json())
                .then(data => {
                    // Access the 'chambre' array from the API response
                    const facture = data.facture;

                    window.hidePreloader();

                    pdfFicheConsultation(facture);

                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
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

                        url: $('#url').attr('content') + '/api/consultations/delete/' + encodeURIComponent(row.numfac),

                        method: 'GET',

                        success: function (response) {

                            if (response.success) {

                                consultationTable.page = 1;
                                consultationTable.load();

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