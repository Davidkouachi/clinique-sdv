@if (session('success'))
        <div class="modal fade" id="success" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5 text-center">
                        <h1 class="display-4">
                            <i class="ri-checkbox-circle-line text-success"></i>
                        </h1>
                        <h4 class="text-success">Succès</h4>
                        <p>{{session('success')}}</p>
                        <a data-bs-dismiss="modal" class="btn btn-lg btn-success w-25">
                            ok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('success'));
                myModal.show();
            });
        </script>
        @php session()->forget('success'); @endphp
    @endif

    @if (session('error'))
        <div class="modal fade" id="error" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5 text-center">
                        <h1 class="display-4">
                            <i class="ri-close-circle-line text-danger"></i>
                        </h1>
                        <h4 class="text-danger">Erreur</h4>
                        <p>{{session('error')}}</p>
                        <a data-bs-dismiss="modal" class="btn btn-lg btn-danger w-25">
                            ok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('error'));
                myModal.show();
            });
        </script>
        @php session()->forget('error'); @endphp
    @endif

    @if (session('info'))
        <div class="modal fade" id="info" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5 text-center">
                        <h1 class="display-4">
                            <i class="ri-question-line text-info"></i>
                        </h1>
                        <h4 class="text-info">Information</h4>
                        <p>{{session('info')}}</p>
                        <a data-bs-dismiss="modal" class="btn btn-lg btn-info w-25">
                            ok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('info'));
                myModal.show();
            });
        </script>
        @php session()->forget('info'); @endphp
    @endif

    @if (session('warning'))
        <div class="modal fade" id="warning" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-5 text-center">
                        <h1 class="display-4">
                            <i class="ri-alert-line text-warning"></i>
                        </h1>
                        <h4 class="text-warning">Alert</h4>
                        <p>{{session('warning')}}</p>
                        <a data-bs-dismiss="modal" class="btn btn-lg btn-warning w-25">
                            ok
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('warning'));
                myModal.show();
            });
        </script>
        @php session()->forget('warning'); @endphp
    @endif