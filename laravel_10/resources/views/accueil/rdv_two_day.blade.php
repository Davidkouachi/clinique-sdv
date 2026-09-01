@extends('app')

@section('titre', 'Acceuil')

@section('info_page')
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-bar-chart-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{route('index_accueil')}}">Espace Santé</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Rendez-Vous
        </li>
    </ol>
</div>
@endsection

@section('content')

<div class="app-body">
    <div class="row gx-3 ">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-3">
                <div class="card-body " style="background: rgba(0, 0, 0, 0.7);" >
                    <div class="py-4 px-3 text-white">
                        <h6>RENDEZ-VOUS</h6>
                        {{-- <h2>{{Auth::user()->sexe.'. '.Auth::user()->name}}</h2> --}}
                        <p>Récéption / Rendez-Vous dans deux jour(s).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row gx-3" >
        <div class="col-sm-12">
            <div class="card mb-3 mt-3">
                <div class="card-body" style="margin-top: -30px;">
                    <div class="custom-tabs-container">
                        <ul class="nav nav-tabs justify-content-center bg-primary bg-2" id="customTab4" role="tablist" style="background: rgba(0, 0, 0, 0.7);">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active text-white" id="tab-twoAAA" data-bs-toggle="tab" href="#twoAAA" role="tab" aria-controls="twoAAA" aria-selected="false" tabindex="-1">
                                    <i class="ri-contacts-line me-2"></i>
                                    Liste
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="customTabContent">
                            <div class="tab-pane fade active show" id="twoAAA" role="tabpanel" aria-labelledby="tab-twoAAA">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title">Liste des Rendez-Vous dans 2 jours</h5>
                                    <div class="d-flex">
                                        <a id="btn_refresh_table_rdv" class="btn btn-outline-info ms-auto me-1">
                                            <i class="ri-loop-left-line"></i>
                                        </a>
                                        <a style="display: none;" id="btn_sms" class="btn btn-outline-warning ms-auto" data-bs-toggle="modal" data-bs-target="#Message">
                                            <i class="ri-mail-add-line"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-outer" id="div_Table_rdv" style="display: none;">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-hover m-0 truncate" id="Table_rdv">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Patient</th>
                                                        <th>Contact</th>
                                                        <th>Médecin</th>
                                                        <th>Spécialité</th>
                                                        <th>Rdv prévu</th>
                                                        <th>Date de création</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div id="message_Table_rdv" style="display: none;">
                                        <p class="text-center">
                                            Aucun Rendez-Vous n'est prévu aujourd'hui
                                        </p>
                                    </div>
                                    <div id="div_Table_loader_rdv" style="display: none;">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="spinner-border text-warning me-2" role="status" aria-hidden="true"></div>
                                            <strong>Chargement des données...</strong>
                                        </div>
                                    </div>
                                    <div id="pagination-controls_rdv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="Detail_motif" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-body" id="modal_Detail_motif"></div>
    </div>
</div>

<div class="modal fade" id="Message" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">
                    Notification Rappel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-3">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea style="resize: none;" class="form-control" id="messageSms" rows="5">RAPPEL! Cher Patient, Vous avez un RDV au Centre Medico-Social la Pyramide le ${date}. Arrivez 15 min en avance. Merci</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-success" id="senderSms">
                    <span class="me-1" >Envoyer</span>
                    <i class="ri-send-plane-fill"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('jsPDF-master/dist/jspdf.umd.js')}}"></script>
<script src="{{asset('assets/vendor/apex/apexcharts.min.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        list_rdv();

        document.getElementById("btn_refresh_table_rdv").addEventListener("click", list_rdv);

        function showAlert(title, message, type) 
        {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
            });
        }

        function formatDate(dateString) 
        {

            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const year = date.getFullYear();

            return `${day}/${month}/${year}`; // Format as dd/mm/yyyy
        }

        function formatDateHeure(dateString) 
        {

            const date = new Date(dateString);
                
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');

            return `${day}/${month}/${year} à ${hours}:${minutes}:${seconds}`;
        }

        const contacts = [];

        function list_rdv(page = 1) 
        {
            const tableBody = document.querySelector('#Table_rdv tbody');
            const messageDiv = document.getElementById('message_Table_rdv');
            const tableDiv = document.getElementById('div_Table_rdv');
            const loaderDiv = document.getElementById('div_Table_loader_rdv');

            messageDiv.style.display = 'none';
            tableDiv.style.display = 'none';
            loaderDiv.style.display = 'block';

            const url = $('#url').attr('content') +`/api/list_rdv_two_days?page=${page}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const rdvs = data.rdv || [];
                    const pagination = data.pagination || {};
                    const perPage = pagination.per_page || 10;
                    const currentPage = pagination.current_page || 1;

                    tableBody.innerHTML = '';

                    if (rdvs.length > 0) {

                        document.getElementById('btn_sms').style.display = 'block';

                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'none';
                        tableDiv.style.display = 'block';

                            rdvs.forEach((item, index) => {

                                contacts.push({
                                    nom: item.patient,
                                    tel: item.tel,
                                    date: formatDateHeure(item.date),
                                });

                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td>${((currentPage - 1) * perPage) + index + 1}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="d-flex align-items-center flex-column me-2">
                                                <img src="/assets/images/rdv1.png" class="img-2x rounded-circle border border-1">
                                            </a>
                                            ${item.patient}
                                        </div>
                                    </td>
                                    <td>+225 ${item.tel}</td>
                                    <td>${item.medecin}</td>
                                    <td>${item.specialite}</td>
                                    <td>${formatDateHeure(item.date)}</td>
                                    <td>${formatDateHeure(item.created_at)}</td>
                                    <td>
                                        <div class="d-inline-flex gap-1">
                                            <a class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#Detail_motif" id="motif-${item.id}">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                `;
                                tableBody.appendChild(row);

                                document.getElementById(`motif-${item.id}`).addEventListener('click', () =>
                                {
                                    const modal = document.getElementById('modal_Detail_motif');
                                    modal.innerHTML = '';

                                    const div = document.createElement('div');
                                    div.innerHTML = `
                                           <div class="row gx-3">
                                                <div class="col-12">
                                                    <div class=" mb-3">
                                                        <div class="card-body">
                                                            <ul class="list-group">
                                                                <li class="list-group-item active text-center" aria-current="true">
                                                                    Motif
                                                                </li>
                                                                <li class="list-group-item">
                                                                    ${item.motif} 
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>     
                                    `;

                                    modal.appendChild(div);

                                });

                            });

                        updatePaginationControlsRdv(pagination);

                    } else {
                        document.getElementById('btn_sms').style.display = 'none';
                        tableDiv.style.display = 'none';
                        loaderDiv.style.display = 'none';
                        messageDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    loaderDiv.style.display = 'none';
                    tableDiv.style.display = 'none';
                    messageDiv.style.display = 'block';
                });
        }

        function updatePaginationControlsRdv(pagination) 
        {
            const paginationDiv = document.getElementById('pagination-controls_rdv');
            paginationDiv.innerHTML = '';

            // Bootstrap pagination wrapper
            const paginationWrapper = document.createElement('ul');
            paginationWrapper.className = 'pagination justify-content-center';

            // Previous button
            if (pagination.current_page > 1) {
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                prevButton.onclick = () => list_rdv(pagination.current_page - 1);
                paginationWrapper.appendChild(prevButton);
            } else {
                // Disable the previous button if on the first page
                const prevButton = document.createElement('li');
                prevButton.className = 'page-item disabled';
                prevButton.innerHTML = `<a class="page-link" href="#">Precédent</a>`;
                paginationWrapper.appendChild(prevButton);
            }

            // Page number links (show a few around the current page)
            const totalPages = pagination.last_page;
            const currentPage = pagination.current_page;
            const maxVisiblePages = 5; // Max number of page links to display

            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust start page if end page exceeds the total pages
            if (endPage - startPage < maxVisiblePages - 1) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            // Loop through pages and create page links
            for (let i = startPage; i <= endPage; i++) {
                const pageItem = document.createElement('li');
                pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageItem.onclick = () => list_rdv(i);
                paginationWrapper.appendChild(pageItem);
            }

            // Ellipsis (...) if not all pages are shown
            if (endPage < totalPages) {
                const ellipsis = document.createElement('li');
                ellipsis.className = 'page-item disabled';
                ellipsis.innerHTML = `<a class="page-link" href="#">...</a>`;
                paginationWrapper.appendChild(ellipsis);

                // Add the last page link
                const lastPageItem = document.createElement('li');
                lastPageItem.className = `page-item`;
                lastPageItem.innerHTML = `<a class="page-link" href="#">${totalPages}</a>`;
                lastPageItem.onclick = () => list_rdv(totalPages);
                paginationWrapper.appendChild(lastPageItem);
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                nextButton.onclick = () => list_rdv(pagination.current_page + 1);
                paginationWrapper.appendChild(nextButton);
            } else {
                // Disable the next button if on the last page
                const nextButton = document.createElement('li');
                nextButton.className = 'page-item disabled';
                nextButton.innerHTML = `<a class="page-link" href="#">Suivant</a>`;
                paginationWrapper.appendChild(nextButton);
            }

            // Append pagination controls to the DOM
            paginationDiv.appendChild(paginationWrapper);
        }

        function smsSenderMultipleAsync(contacts, message) {

            const replacePlaceholders = (message, item) => {
                return message
                    .replace('${date}', item.date)
                    .replace('${nom}', item.nom);
            };
            
            var preloader_ch = `
                <div id="preloader_ch">
                    <div class="spinner_preloader_ch"></div>
                </div>
            `;
            // Ajouter le préchargeur au body
            document.body.insertAdjacentHTML('beforeend', preloader_ch);

            const params = {
                username: '{{ env('API_SMS_USERNANME') }}',
                password: '{{ env('API_SMS_PASSWORD') }}',
                serviceid: '{{ env('API_SMS_SERVICEID') }}',
                sender: '{{ env('API_SMS_SENDER') }}',
            };

            const requests = contacts.map((item) => {

                const personalizedMessage = replacePlaceholders(message, item);

                const queryString = new URLSearchParams({
                    ...params,
                    msisdn: `+225${item.tel}`,
                    msg: personalizedMessage,
                }).toString();

                const url = `https://api-public-2.mtarget.fr/messages?${queryString}`;

                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then((data) => ({ item, success: true, data }))
                    .catch((error) => ({
                        item,
                        success: false,
                        error,
                    }));
            });

            Promise.all(requests).then((results) => {

                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                const successCount = results.filter((result) => result.success).length;
                const failureCount = results.length - successCount;

                if (successCount > 0) {
                    console.log(`Succès : ${successCount} SMS envoyés avec succès.`);
                    showAlert('Succès', `${successCount}/${results.length} SMS envoyés avec succès`, 'success');
                }

                if (failureCount > 0) {
                    console.error(`Echec : ${failureCount} SMS n'ont pas été envoyés.`);
                    showAlert('Echec', `${failureCount}/${results.length} SMS n'ont pas été envoyés`, 'error');
                }

                if (results.length === 0 || failureCount === results.length) {
                    // Aucun SMS envoyé, probablement pas de connexion
                    console.error('Aucune connexion ou tous les SMS ont échoué.');
                    showAlert('Echec', 'Aucune connexion internet ou tous les envois ont échoué.', 'error');
                }

                console.log('Data:', JSON.stringify(results, null, 2));

            }).catch((error) => {
                // Gérer les erreurs globales (par exemple, problème réseau avant même de démarrer)
                var preloader = document.getElementById('preloader_ch');
                if (preloader) {
                    preloader.remove();
                }

                console.error('Erreur globale:', error);
                showAlert('Echec', 'Une erreur inattendue est survenue.', 'error');
            });
        }

        $('#senderSms').on('click', function () {

            const message = document.getElementById("messageSms").value;

            smsSenderMultipleAsync(contacts, message);
        });

    });
</script>

@endsection
