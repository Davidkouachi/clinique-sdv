$(document).ready(function () {

    window.list_message_all = function () {

        const contenuDiv = $('#div_contenu_message');

        const div_loader = $(`
            <div class="col-12">
                <div class="">
                    <div class="nk-ecwg nk-ecwg6">
                        <div class="card-inner">
                            <div class="card-title-group justify-content-center align-items-center">
                                <div class="card-title d-flex justify-content-center align-items-center">
                                    <h6 class="text-warning">
                                        Veuillez patienter s'il vous plaît ...
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        const div_message = $(`
            <div class="col-12">
                <div class="">
                    <div class="nk-ecwg nk-ecwg6">
                        <div class="card-inner">
                            <div class="card-title-group justify-content-center align-items-center">
                                <div class="card-title d-flex justify-content-center align-items-center">
                                    <h6 class="text-danger">
                                        Aucunes données trouvées ...
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        contenuDiv.empty();
        contenuDiv.append(div_loader);

        $.ajax({
            url: url_base + '/api/list_message_all',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const messages = data.data;

                contenuDiv.empty();

                if (messages.length > 0) {

                    $.each(messages, function(index, item) {

                        const row = $(`
                            <div class="col-sm-6 col-lg-4 col-xxl-3">
                                <div class="card h-100">
                                    <div class="card-inner">
                                        <div class="project">
                                            <div class="project-head">
                                                <a href="#" class="project-title">
                                                    <div class="user-avatar bg-success">
                                                        <span>${index+1}</span>
                                                    </div>
                                                    <div class="project-info">
                                                        <span class="sub-text">Type</span>
                                                        <h6 class="title">${item.type}</h6>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="project-details">
                                                <p>${item.message}.</p>
                                            </div>
                                            <div class="project-progress">
                                                <div class="progress progress-pill progress-sm">
                                                    <div class="progress-bar bg-success" data-progress="100" style="width: 100%;"></div>
                                                </div>
                                            </div>
                                            <div class="project-meta">
                                                <ul class="project-users g-1">
                                                    <li>
                                                        <a class="btn btn-sm btn-info btn-dim btn_edit"
                                                            data-id="${item.id}"
                                                            data-type="${item.type}"
                                                            data-message="${item.message}"
                                                        >
                                                            <em class="icon ni ni-edit" ></em>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="btn btn-sm btn-danger btn-dim btn_suppr"
                                                            data-id="${item.id}"
                                                        >
                                                            <em class="icon ni ni-trash" ></em>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                        contenuDiv.append(row);
                    });

                } else {
                    contenuDiv.append(div_message);
                }
            },
            error: function() {
                contenuDiv.append(div_message);
            }
        });
        
    }

    $(document).off('click', '.btn_edit').on('click', '.btn_edit', function (event) {

        $('#modalUpdate').remove();

        $('body').append(`
            <div class="modal fade" id="modalUpdate" tabindex="-1" aria-modal="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-white">
                        <div class="modal-header">
                            <h5 class="modal-title">Mise à jour</h5>
                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <em class="icon ni ni-cross"></em>
                            </a>
                        </div>
                        <div class="modal-body">
                            <div class="form-validate is-alter">
                                <div class="form-group">
                                    <label class="form-label">Type</label>
                                    <div class="form-control-wrap">
                                        <input id="type_modif" type="text" class="form-control">
                                        <input id="id_modif" type="hidden">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Message</label>
                                    <div class="form-control-wrap">
                                        <textarea class="form-control" id="type_message_modif" maxlength="100"></textarea>
                                    </div>
                                    <small id="char_count_type_modif">0/100</small>
                                </div>
                                <div class="form-group text-center">
                                    <a data-bs-dismiss="modal" aria-label="Close" id="btn_modif_message" class="btn btn-dim btn-md btn-success">
                                        <span>Enregistrer</span>
                                        <em class="icon ni ni-check" ></em>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Affichage du modal
        $('#modalUpdate').modal('show');

        const id = $(this).data('id');
        const type = $(this).data('type');
        const message = $(this).data('message');

        $('#id_modif').val(id);
        $('#type_modif').val(type);
        $('#type_message_modif').val(message).trigger('input');

        $('#type_message_modif').on('input', function() {
            var currentLength = $(this).val().length;
            var maxLength = $(this).attr('maxlength');

            $('#char_count_type_modif').text(currentLength + '/' + maxLength);

            if (currentLength >= maxLength) {
                $(this).val($(this).val().substring(0, maxLength));
            }
        });

    });

    $(document).off('click', '#btn_modif_message').on('click', '#btn_modif_message', function (event) {
        event.preventDefault();

        let id = $("#id_modif").val();
        let type = $("#type_modif");
        let message = $("#type_message_modif");

        if (!type.val().trim() || !message.val().trim() ) {
            showAlert("Alert","Veuillez remplir tous les champs s'il vous plaît !!!","warning");
            return false;
        }

        // Ajouter le préchargeur
        preloader('start');

        // Première requête : rafraîchir le token CSRF
        $.ajax({
            url: url_base + "/refresh-csrf",
            method: "GET",
            success: function (response_crsf) {
                $('meta[name="csrf-token"]').attr("content", response_crsf.csrf_token);

                // Deuxième requête : authentification
                $.ajax({
                    url: url_base + "/api/update_type_message/" + id,
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": response_crsf.csrf_token,
                    },
                    data: {
                        type: type.val(),
                        message: message.val(),
                    },
                    success: function (response) {
                        preloader('end');

                        if (response.success) {

                            list_message_all();
                            showAlert("Succès", "Opération éffectuée", "success");

                        } else if (response.error) {
                            showAlert("Alert", "Echec de l'opération ", "error");
                        }
                    },
                    error: function () {
                        preloader('end');
                        showAlert("Alert", "Une erreur est survenue ", "error");
                    },
                });
            },
            error: function () {
                preloader('end');
                showAlert("Alert", "Une erreur est survenue ", "error");
            },
        });
    });

    $(document).off('click', '.btn_suppr').on('click', '.btn_suppr', function (event) {
        event.preventDefault();

        // $('#modalDelete').remove();

        // $('body').append(`
        //     <div class="modal fade" id="modalDelete" tabindex="-1">
        //         <div class="modal-dialog" role="document">
        //             <div class="modal-content bg-white">
        //                 <div class="modal-header">
        //                     <h5 class="modal-title">Confirmation</h5>
        //                     <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
        //                         <em class="icon ni ni-cross"></em>
        //                     </a>
        //                 </div>
        //                 <div class="modal-body">
        //                     <div class="">
        //                         <div class="form-group text-center">
        //                             <label class="form-label">
        //                                 Voulez-vous vraiment effectuée la suppresion ?
        //                             </label>
        //                             <div class="form-control-wrap">
        //                                 <input type="hidden" id="Iddelete">
        //                             </div>
        //                         </div>
        //                         <div class="form-group d-flex justify-content-center align-items-center">
        //                             <a data-bs-dismiss="modal" aria-label="Close" class="btn btn-md btn-danger mb-2 me-2">
        //                                 <span>Non</span>
        //                             </a>
        //                             <a id="deleteBtn" data-bs-dismiss="modal" aria-label="Close" class="btn btn-dim btn-md btn-outline-success mb-2">
        //                                 <span>Oui</span>
        //                             </a>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>
        // `);

        // // Affichage du modal
        // $('#modalDelete').modal('show');

        const id = $(this).data('id');

        confirmAction().then((result) => {
            if (result.isConfirmed) {

                preloader('start');

                $.ajax({
                    url: url_base + '/api/delete_type_message/' + id,
                    method: 'GET',
                    success: function(response) {
                        preloader('end');

                        if (response.success) {

                            list_message_all();
                            showAlert("Succès", "Opération éffectuée", "success");

                        } else if (response.error_db) {

                            showAlert("Alert", "Echec de l'opération ", "error");
                            console.log(response.message);

                        } else {

                            showAlert("Alert", "Une erreur est survenue ", "error");

                        }

                    },
                    error: function() {
                        preloader('end');
                        showAlert("Alert", "Une erreur est survenue ", "error");
                    }
                });
            }
        });

    });

    // $(document).off('click', '#deleteBtn').on('click', '#deleteBtn', function (event) {
    //     // let id = $('#Iddelete').val();



    //     // $('#modalDelete').remove();

    //     // Ajouter le préchargeur
    //     // preloader('start');

    //     // $.ajax({
    //     //     url: url_base + '/api/delete_type_message/' + id,
    //     //     method: 'GET',
    //     //     success: function(response) {
    //     //         preloader('end');

    //     //         if (response.success) {

    //     //             list_message_all();
    //     //             showAlert("Succès", "Opération éffectuée", "success");

    //     //         } else if (response.error_db) {

    //     //             showAlert("Alert", "Echec de l'opération ", "error");
    //     //             console.log(response.message);

    //     //         } else {

    //     //             showAlert("Alert", "Une erreur est survenue ", "error");

    //     //         }

    //     //     },
    //     //     error: function() {
    //     //         preloader('end');
    //     //         showAlert("Alert", "Une erreur est survenue ", "error");
    //     //     }
    //     // });

    // });

    $('#reloader_liste_type').on('click', list_message_all);

    list_message_all();

});