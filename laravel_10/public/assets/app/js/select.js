$(document).ready(function () {

    window.select_patient = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/name_patient_reception',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.name || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.idenregistremetpatient,
                            text: item.idenregistremetpatient + ' | ' + item.nomprenomspatient
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des patients');
            }
        });
    }

    window.api_rech_dossier = function (id_patient, onSuccess, onError) {
        $.ajax({
            url: window.urlBase + '/api/rech_patient',
            type: 'GET',
            data: { id: id_patient },
            dataType: 'json',
            success: function (response) {
                if (typeof onSuccess === 'function') {
                    onSuccess(response);
                }
            },
            error: function (error) {
                if (typeof onError === 'function') {
                    onError(error);
                }
            }
        });
    };

    window.api_select_list_typeacte = function (codeassurance, onSuccess, onError) {
        $.ajax({
            url: window.urlBase + '/api/select_typeacte/' + codeassurance,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (typeof onSuccess === 'function') {
                    onSuccess(response);
                }
            },
            error: function (error) {
                if (typeof onError === 'function') {
                    onError(error);
                }
            }
        });
    };

    window.select_assurance = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: '',
        }));

        $.ajax({
            url: window.urlBase + '/api/assurance_select_patient_new',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.assurance || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.codeassurance,
                            text: item.libelleassurance,
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des assurances');
            }
        });
    }

    window.select_assureur = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/select_assureur',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.assureur || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.codeassureur,
                            text: item.libelle_assureur,
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des assureurs');
            }
        });
    }

    window.select_taux = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/taux_select_patient_new',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.taux || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.idtauxcouv,
                            text: item.valeurtaux + '%',
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des taux');
            }
        });
    }

    window.select_societe = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/societe_select_patient_new',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.societe || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.codesocieteassure,
                            text: item.nomsocieteassure,
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des societes');
            }
        });
    }

    window.select_filiation = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/filiation_select_patient_new',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.filiation || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.codefiliation,
                            text: item.libellefiliation,
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement des filiations');
            }
        });
    }

    window.select_list_medecin = function (id) 
    {
        const place = $(id);
        place.empty();
        place.append($('<option>', {
            value: '',
            text: 'Selectionner',
        }));

        $.ajax({
            url: window.urlBase + '/api/select_list_medecin',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const data = response.medecin || [];

                data.forEach(function(item) {
                    place.append(
                        $('<option>', {
                            value: item.codemedecin,
                            text: item.nomprenomsmed
                        })
                    );
                });
            },
            error: function() {
                console.error('Erreur lors du chargement de la liste des medecins');
            }
        });
    }

});