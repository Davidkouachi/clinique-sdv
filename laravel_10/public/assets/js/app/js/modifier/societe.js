document.getElementById("form_societe_modifier").addEventListener("submit", function(event) {
    event.preventDefault();

    var modalWarning = `
            <div class="modal fade" id="modalWarning" tabindex="-1" aria-hidden="true" style="position: fixed;" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-5 text-center">
                            <h1 class="display-4">
                                <i class="ri-alert-line text-warning"></i>
                            </h1>
                            <h4 class="text-warning">Alert</h4>
                            <p>Veuillez vérifié l'email et les contacts</p>
                            <a data-bs-dismiss="modal" class="btn btn-lg btn-warning w-25">
                                ok
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;

    // Précharger le HTML du modal
    var modalChargement = `
        <div class="modal fade" id="modalChargement" tabindex="-1" aria-modal="true" style="position: fixed;" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg align-items-center justify-content-center">
                <a class="btn btn-warning" > 
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                    <span>Chargement en cours...</span> 
                </a> 
            </div>
        </div>
    `;

    var email = document.getElementById("email_modif").value;
    var tel = document.getElementById("tel_modif").value;
    var tel2 = document.getElementById("tel2_modif").value;

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email) || tel.length !== 10 || tel2.length !== 10 && tel2!= '') {   
        // Insérer le modal dans le DOM
        document.body.insertAdjacentHTML('beforeend', modalWarning);
        // Initialiser et afficher le modal après insertion
        var modalElement = document.getElementById('modalWarning');
        var modal = new bootstrap.Modal(modalElement);
        modal.show();

        return false;
    }   
    

    // Insérer le modal dans le DOM
    document.body.insertAdjacentHTML('beforeend', modalChargement);

    // Initialiser et afficher le modal après insertion
    var modalElement = document.getElementById('modalChargement');
    var modal = new bootstrap.Modal(modalElement);
    modal.show();


    this.submit();

});
