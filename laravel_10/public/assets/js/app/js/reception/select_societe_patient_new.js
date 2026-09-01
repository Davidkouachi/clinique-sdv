document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('patient_societe_id_new');

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Sélectionner une société';
    selectElement.appendChild(defaultOption);
    
    // Vérifie que l'élément select existe
    if (selectElement) {
        // Effectuer une requête pour récupérer les taux
        fetch('/api/societe_select_patient_new')
            .then(response => response.json())
            .then(data => {
                data.forEach(societe => {
                    const option = document.createElement('option');
                    option.value = societe.id; // Assure-toi que 'id' est la clé correcte
                    option.textContent = societe.nom; // Assure-toi que 'nom' est la clé correcte
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors du chargement des societes:', error));
    }
});
