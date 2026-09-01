$(document).ready(function () {

    window.showAlert = function (title, message, icon) {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
        });
    }

});
