document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('patient_taux_id_new');

    // Vérifie que l'élément select existe
    if (selectElement) {
        // Effectuer une requête pour récupérer les taux
        fetch('/api/taux_select_patient_new')
            .then(response => response.json())
            .then(data => {
                data.forEach(taux => {
                    const option = document.createElement('option');
                    option.value = taux.id; // Assure-toi que 'id' est la clé correcte
                    option.textContent = taux.taux+'%'; // Assure-toi que 'nom' est la clé correcte
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur lors du chargement des taux:', error));
    }
});
