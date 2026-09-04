$(document).ready(function () {

    window.requestWithCsrf = function (method, url, data = {}, options = {}) {

        // return $.ajax({

        //     url:
        //         $('#url').attr('content') +
        //         '/refresh-csrf',

        //     type: 'GET',

        //     dataType: 'json'

        // }).then(function (csrfResponse) {

        //     const csrfToken =
        //         csrfResponse.csrf_token;

        //     if (!csrfToken) {

        //         return $.Deferred()
        //             .reject({
        //                 status: 419,
        //                 responseText:
        //                     'Token CSRF invalide.'
        //             })
        //             .promise();
        //     }

        //     /*
        //      * Mise à jour du token
        //      */
        //     $('meta[name="csrf-token"]')
        //         .attr(
        //             'content',
        //             csrfToken
        //         );

        //     /*
        //      * Requête réelle
        //      */
            return $.ajax({

                url: url,

                type: method,

                // headers: {
                //     'X-CSRF-TOKEN':
                //         csrfToken
                // },

                data: data,

                xhrFields: {
                    withCredentials: true
                },

                dataType: 'json',

                ...options

            });
        // });
    }

});