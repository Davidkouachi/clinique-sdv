document.addEventListener('DOMContentLoaded', function() {

    document.getElementById("btn_rech_num_dossier").addEventListener("click", function(event) {
        event.preventDefault(); // Empêcher le rechargement de la page si nécessaire

        const matricule_patient = document.getElementById("matricule_patient");

        if(!matricule_patient.value.trim()){
            showAlert('warning', 'Veuillez saisie le numéro de dossier du patient.');
            return false;
        }

        // Créer l'élément de préchargement
        var preloader_ch = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;

        // Ajouter le préchargeur au body
        document.body.insertAdjacentHTML('beforeend', preloader_ch);

        $.ajax({
            url: '/api/rech_patient',
            method: 'GET',  // Use 'POST' for data creation
            data: { matricule: matricule_patient.value },
            success: function(response) {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                if(response.existep) {
                    showAlert('warning', 'Ce patient n\'existe pas.');
                    Reset();
                } else if (response.success) {
                    showAlert('success', 'Patient trouvé.');
                    addGroup(response.patient);
                    document.getElementById("div_info_consul").style.display = 'block';
                }
            },
            error: function() {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert('danger', 'Une erreur est survenue lors de la recherche.');
                societeInput.value = '';
            }
        });
    });

    function addGroup(data) {

        var dynamicFields = document.getElementById("div_info_patient");
        // Remove existing content
        while (dynamicFields.firstChild) {
            dynamicFields.removeChild(dynamicFields.firstChild);
        }

        var groupe = document.createElement("div");
        groupe.className = "row gx-3";
        groupe.innerHTML = `
                                    <div class="col-12">
                                        <div class="card-header">
                                            <h5 class="card-title">Information du patient</h5>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Nom et Prénoms</label>
                                            <input value="${data.np}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input value="${data.email}" readonly class="form-control" placeholder="Néant">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tel">Contact</label>
                                            <input value="+225 ${data.tel}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="tel2">Contact 2</label>
                                            <input value="+225 ${data.tel2}" readonly class="form-control" placeholder="Néant">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="adresse">Adresse</label>
                                            <input value="${data.adresse}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Assurer</label>
                                            <input value="${data.assurer}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="adresse">Assurance</label>
                                            <input value="${data.assurance}" readonly class="form-control" placeholder="Néant">
                                        </div>
                                    </div>  
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="adresse">Taux</label>
                                            <input value="${data.taux}%" readonly class="form-control" placeholder="Néant">
                                        </div>
                                    </div>  
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="adresse">Société</label>
                                            <input value="${data.societe}" readonly class="form-control" placeholder="Néant">
                                        </div>
                                    </div>   
                `;

        document.getElementById("div_info_patient").appendChild(groupe);
    }

    function showAlert(type, message) {
        var groupe = document.createElement("div");
        groupe.className = `alert bg-${type} text-white alert-dismissible fade show`;
        groupe.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>   
        `;
        document.getElementById("div_alert").appendChild(groupe);

        setTimeout(function() {
            groupe.classList.remove("show");
            groupe.classList.add("fade");
            setTimeout(function() {
                groupe.remove();
            }, 150); // Time for the fade effect to complete
        }, 3000);
    }

    function Reset() {

        var dynamicFields = document.getElementById("div_info_patient");
        // Remove existing content
        while (dynamicFields.firstChild) {
            dynamicFields.removeChild(dynamicFields.firstChild);
        }

        document.getElementById("div_info_consul").style.display = 'none';
        document.getElementById("matricule_patient").value='';
    }

});