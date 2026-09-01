function divAssurerChange(event) {
    // Empêcher l'événement par défaut
    event.preventDefault();

    // Vérifier quelle option est sélectionnée
    const assurerO = document.getElementById("assurerO");
    const assurerN = document.getElementById("assurerN");
    const divAssurer = document.getElementById("div_assurer");

    if (assurerN.checked) {
        // Si "Oui" est sélectionné, afficher le div
        divAssurer.style.display = "flex";
    } else {
        // Si "Non" est sélectionné, masquer le div
        divAssurer.style.display = "none";
    }
}

// Ajouter un gestionnaire d'événements aux deux boutons radio
document.getElementById("assurerO").addEventListener("change", divAssurerChange);
document.getElementById("assurerN").addEventListener("change", divAssurerChange);

// Appeler la fonction initialement pour gérer l'état par défaut
document.addEventListener("DOMContentLoaded", function() {
    divAssurerChange(new Event('change')); // Simuler un changement pour initialiser l'affichage correct
});
