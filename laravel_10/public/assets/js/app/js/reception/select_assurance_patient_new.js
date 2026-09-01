document.addEventListener('DOMContentLoaded', function() {
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
});