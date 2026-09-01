document.addEventListener('DOMContentLoaded', function() {

    document.getElementById("btn_eng_societe").addEventListener("click", function(event) {
        event.preventDefault();

        const societeInput = document.getElementById("societe_new");

        var dynamicFields = document.getElementById("div_alert");
        // Remove existing content
        while (dynamicFields.firstChild) {
            dynamicFields.removeChild(dynamicFields.firstChild);
        }

        if(societeInput.value == ''){
            showAlert('warning', 'Veuillez saisie le nom de la société SVP.');
            return false;
        }

        var preloader_ch = `
            <div id="preloader_ch">
                <div class="spinner_preloader_ch"></div>
            </div>
        `;
        // Add the preloader to the body
        document.body.insertAdjacentHTML('beforeend', preloader_ch);

        $.ajax({
            url: '/api/societe_new',
            method: 'GET',  // Use 'POST' for data creation
            data: { societe: societeInput.value },
            success: function(response) {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                if (response.warning) {
                    showAlert('warning', 'Cette société existe déjà.');
                } else if (response.success) {
                    showAlert('success', 'Société Enregistrée.');
                } else if (response.error) {
                    showAlert('danger', 'Une erreur est survenue lors de l\'enregistrement.');
                }
                societeInput.value = '';
                RefreshSelectSociete();
            },
            error: function() {
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }
                showAlert('danger', 'Une erreur est survenue lors de l\'enregistrement.');
                societeInput.value = '';
            }
        });
    });

    function RefreshSelectSociete() {
        const selectElement = document.getElementById('patient_societe_id_new');

        // Clear existing options
        selectElement.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Sélectionner une société';
        selectElement.appendChild(defaultOption);

        fetch('/api/societe_select_patient_new')
            .then(response => response.json())
            .then(data => {
                data.forEach(societe => {
                    const option = document.createElement('option');
                    option.value = societe.id; // Ensure 'id' is the correct key
                    option.textContent = societe.nom; // Ensure 'nom' is the correct key
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