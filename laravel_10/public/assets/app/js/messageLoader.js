$(document).ready(function () {

    window.messageLoader = function (message, color, loader = 0) {
        const div = `
            <div class="card-title-group justify-content-center align-items-center">
                <div class="card-title d-flex justify-content-center align-items-center">
                    ${loader == 1 ? `<div class="spinner-border text-${color} me-1" role="status"></div>` : ``}
                    <h6 class="title text-${color}">
                        ${message}
                    </h6>
                </div>
            </div>
        `

        return div;
    }

});