document.addEventListener('DOMContentLoaded', function() {

    var inputElement = document.getElementById('patient_tel_new');
    inputElement.addEventListener('input', function() {
        // Supprimer tout sauf les chiffres
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    var inputElement = document.getElementById('patient_tel2_new');
    inputElement.addEventListener('input', function() {
        // Supprimer tout sauf les chiffres
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById("btn_eng_patient").addEventListener("click", function(event) {
        event.preventDefault();

        const divAssurer = document.getElementById("div_assurer");

        var nom = document.getElementById("patient_np_new");
        var email = document.getElementById("patient_email_new");
        var phone = document.getElementById("patient_tel_new");
        var phone2 = document.getElementById("patient_tel2_new");
        var adresse = document.getElementById("patient_adresse_new");
        var assurer = document.querySelector('input[name="patient_statut_assurer"]:checked');

        var assurerNon = document.getElementById('assurerN');
        var assurerOui = document.getElementById('assurerO');

        var assurance_id = document.getElementById("patient_assurance_id_new");
        var taux_id = document.getElementById("patient_taux_id_new");
        var societe_id = document.getElementById("patient_societe_id_new");

        if (!nom.value.trim() || !phone.value.trim() || !adresse.value.trim()) {
            showAlert('warning', 'Tous les champs sont obligatoires.');
            return false; 
        }

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.value.trim() && !emailRegex.test(email.value.trim())) {  // Use email.value.trim() to check the actual input
            showAlert('warning', 'Email incorrect.');
            return false;
        }

        if (phone.value.length !== 10 || (phone2.value !== '' && phone2.value.length !== 10)) {
            showAlert('warning', 'Contact incomplet.');
            return false;
        }

        if (assurer.value == 'oui') {
            if (assurance_id.value !== '' && taux_id.value !== '' && societe_id.value !== '') {
                // Do something when all the fields have values
            } else {
                showAlert('warning', 'Veuillez remplir tous les champs relatifs à l\'assurance, taux et société.');
                return false; // Prevent form submission
            }
        }

        var dynamicFields = document.getElementById("div_alert");
        // Remove existing content
        while (dynamicFields.firstChild) {
            dynamicFields.removeChild(dynamicFields.firstChild);
        }

        var preloader_ch = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;
        // Add the preloader to the body
        document.body.insertAdjacentHTML('beforeend', preloader_ch);

        $.ajax({
            url: '/api/patient_new',
            method: 'GET',  // Use 'POST' for data creation
            data: { nom: nom.value, email: email.value || null , tel: phone.value, tel2: phone2.value || null,
            adresse: adresse.value, assurer: assurer.value, assurance_id: assurance_id.value || null,
            taux_id: taux_id.value || null, societe_id: societe_id.value || null,},
            success: function(response) {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                
                if (response.tel_existe) {
                    showAlert('warning', 'Ce numéro de téléphone appartient déjà a un patient.');
                }else if (response.email_existe) {
                    showAlert('warning', 'Cet email appartient déjà a un patient.');
                }else if (response.nom_existe) {
                    showAlert('warning', 'Cet patient existe déjà.');
                } else if (response.success) {
                    showAlert('success', 'Patient Enregistrée.');
                } else if (response.error) {
                    showAlert('danger', 'Une erreur est survenue lors de l\'enregistrement.');
                }

                nom.value = '';
                email.value = '';
                phone.value = '';
                phone2.value = '';
                adresse.value = '';

                assurerNon.checked = true;
                assurerOui.checked = false;

                divAssurer.style.display = "none";

                document.getElementById("matricule_patient").value = response.matricule;

                var modalHtml = `
                    <div class="modal fade show" id="Matricule" tabindex="-1" aria-labelledby="exampleModalSmLabel" 
                    aria-modal="true" role="dialog" style="position: fixed;" data-bs-backdrop="static" 
                    data-bs-keyboard="false">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalSmLabel">
                              Matricule du patient
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>P-${response.matricule}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                `;

                // Insert the modal into the DOM
                document.body.insertAdjacentHTML('beforeend', modalHtml);

                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('Matricule'));
                modal.show();

                // Remove the modal from the DOM after it is closed
                document.getElementById('Matricule').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('Matricule').remove();
                });

            },
            error: function() {

                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                showAlert('danger', 'Une erreur est survenue lors de l\'enregistrement.');

                nom.value = '';
                email.value = '';
                phone.value = '';
                phone2.value = '';
                adresse.value = '';

                assurerNon.checked = true;
                assurerOui.checked = false;

                divAssurer.style.display = "none";
            }
        });

    });

    // function RefreshSelectAssurance() {
    //     const selectElement = document.getElementById('patient_assurance_id_new');

    //     // Clear existing options
    //     selectElement.innerHTML = '';

    //     const defaultOption = document.createElement('option');
    //     defaultOption.value = '';
    //     defaultOption.textContent = 'Sélectionner une Assurance';
    //     selectElement.appendChild(defaultOption);

    //     fetch('/api/assurance_select_patient_new')
    //         .then(response => response.json())
    //         .then(data => {
    //             data.forEach(assurance => {
    //                 const option = document.createElement('option');
    //                 option.value = assurance.id; // Ensure 'id' is the correct key
    //                 option.textContent = assurance.nom; // Ensure 'nom' is the correct key
    //                 selectElement.appendChild(option);
    //             });
    //         })
    //         .catch(error => console.error('Erreur lors du chargement des societes:', error));
    // }

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

});
