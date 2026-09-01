document.addEventListener('DOMContentLoaded', function() {

    var inputElement = document.getElementById('tel_assurance_new');
    inputElement.addEventListener('input', function() {
        // Supprimer tout sauf les chiffres
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    var inputElement = document.getElementById('tel2_assurance_new');
    inputElement.addEventListener('input', function() {
        // Supprimer tout sauf les chiffres
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    document.getElementById("btn_eng_assurance").addEventListener("click", function(event) {
        event.preventDefault();

        var nom = document.getElementById("nom_assurance_new");
        var email = document.getElementById("email_assurance_new");
        var phone = document.getElementById("tel_assurance_new");
        var phone2 = document.getElementById("tel2_assurance_new");
        var adresse = document.getElementById("adresse_assurance_new");
        var fax = document.getElementById("fax_assurance_new");

        if (!nom.value.trim() || !email.value.trim() || !phone.value.trim() || !adresse.value.trim() || !fax.value.trim()) {
            showAlert('warning', 'Tous les champs sont obligatoires.');
            return false; 
        }

        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {  // Use email.value.trim() to check the actual input
            showAlert('warning', 'Email incorrect.');
            return false;
        }


        if (phone.value.length !== 10 || (phone2.value !== '' && phone2.value.length !== 10)) {
            showAlert('warning', 'Contact incomplet.');
            return false;
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
            url: '/api/assurance_new',
            method: 'GET',  // Use 'POST' for data creation
            data: { nom: nom.value, email: email.value, tel: phone.value, tel2: phone2.value || null, fax: fax.value, adresse: adresse.value},
            success: function(response) {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                
                if (response.tel_existe) {
                    showAlert('warning', 'Ce numéro de téléphone appartient déjà a une assurance.');
                }else if (response.email_existe) {
                    showAlert('warning', 'Ce email appartient déjà a une assurance.');
                }else if (response.nom_existe) {
                    showAlert('warning', 'Cette assurance existe déjà.');
                }else if (response.fax_existe) {
                    showAlert('warning', 'Ce fax appartient déjà a une assurance.');
                } else if (response.success) {
                    showAlert('success', 'Assurance Enregistrée.');
                } else if (response.error) {
                    showAlert('danger', 'Une erreur est survenue lors de l\'enregistrement.');
                }

                nom.value = '';
                email.value = '';
                phone.value = '';
                phone2.value = '';
                fax.value = '';
                adresse.value = '';

                RefreshSelectAssurance();
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
                fax.value = '';
                adresse.value = '';
            }
        });

    });

    function RefreshSelectAssurance() {
        const selectElement = document.getElementById('patient_assurance_id_new');

        // Clear existing options
        selectElement.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Sélectionner une Assurance';
        selectElement.appendChild(defaultOption);

        fetch('/api/assurance_select_patient_new')
            .then(response => response.json())
            .then(data => {
                data.forEach(assurance => {
                    const option = document.createElement('option');
                    option.value = assurance.id; // Ensure 'id' is the correct key
                    option.textContent = assurance.nom; // Ensure 'nom' is the correct key
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors du chargement des societes:', error));
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

});
