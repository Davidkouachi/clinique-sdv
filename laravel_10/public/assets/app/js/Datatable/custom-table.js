class CustomTable {

    constructor(options) {

        this.options = {
            perPage: 15,

            searchPlaceholder: 'Rechercher...',

            searchButtonText: 'Rechercher',

            showSearch: true,

            showPagination: true,

            showInfo: true,

            method: 'GET',

            ...options
        };


        this.page = 1;

        this.search = '';

        this.currentData = [];

        this.meta = {};

        this.activeMenu = null;


        this.container =
            document.querySelector(
                this.options.selector
            );


        if (!this.container) {

            console.error(
                `CustomTable: élément ${this.options.selector} introuvable`
            );

            return;
        }


        this.init();
    }


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    init() {

        this.render();

        this.bindEvents();

        this.bindGlobalEvents();

        this.load();
    }


    /*
    |--------------------------------------------------------------------------
    | Structure
    |--------------------------------------------------------------------------
    */

    render() {

        this.container.innerHTML = `

            <div class="custom-table-wrapper">

                <div class="custom-table-toolbar">

                    ${
                        this.options.showSearch
                        ? `

                            <div class="custom-table-search">

                                <input
                                    type="text"
                                    class="custom-table-search-input"
                                    placeholder="${this.escape(
                                        this.options.searchPlaceholder
                                    )}"
                                >

                                <button
                                    type="button"
                                    class="custom-table-btn custom-table-search-btn"
                                >
                                    ${this.escape(
                                        this.options.searchButtonText
                                    )}
                                </button>

                            </div>

                        `
                        : ''
                    }

                </div>


                <div class="custom-table-container">

                    <table class="custom-table">

                        <thead>

                            <tr>

                                ${
                                    this.options.columns
                                        .map(column => `

                                            <th>
                                                ${this.escape(
                                                    column.label
                                                )}
                                            </th>

                                        `)
                                        .join('')
                                }


                                ${
                                    this.options.actions
                                    ? '<th></th>'
                                    : ''
                                }

                            </tr>

                        </thead>


                        <tbody class="custom-table-body"></tbody>

                    </table>

                </div>


                <div class="custom-table-footer">

                    <div class="custom-table-info"></div>

                    <div class="custom-table-pagination"></div>

                </div>


                <div class="custom-table-loading">

                    <div class="custom-table-spinner"></div>

                </div>

            </div>

        `;


        this.body =
            this.container.querySelector(
                '.custom-table-body'
            );


        this.loading =
            this.container.querySelector(
                '.custom-table-loading'
            );


        this.info =
            this.container.querySelector(
                '.custom-table-info'
            );


        this.pagination =
            this.container.querySelector(
                '.custom-table-pagination'
            );


        this.searchInput =
            this.container.querySelector(
                '.custom-table-search-input'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Events internes
    |--------------------------------------------------------------------------
    */

    bindEvents() {


        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */

        const searchButton =
            this.container.querySelector(
                '.custom-table-search-btn'
            );


        if (searchButton) {

            searchButton.addEventListener(
                'click',
                () => {

                    this.page = 1;

                    this.search =
                        this.searchInput.value.trim();

                    this.closeActionMenu();

                    this.load();

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Entrée recherche
        |--------------------------------------------------------------------------
        */

        if (this.searchInput) {

            this.searchInput.addEventListener(
                'keydown',
                event => {

                    if (event.key === 'Enter') {

                        event.preventDefault();

                        this.page = 1;

                        this.search =
                            this.searchInput.value.trim();

                        this.closeActionMenu();

                        this.load();

                    }

                }
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        this.pagination.addEventListener(
            'click',
            event => {

                const button =
                    event.target.closest(
                        '[data-page]'
                    );


                if (!button) {
                    return;
                }


                if (button.disabled) {
                    return;
                }


                const page =
                    parseInt(
                        button.dataset.page
                    );


                if (!page || page === this.page) {
                    return;
                }


                this.closeActionMenu();


                this.page = page;

                this.load();

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Bouton action
        |--------------------------------------------------------------------------
        */

        this.body.addEventListener(
            'click',
            event => {

                const button =
                    event.target.closest(
                        '.custom-table-action-btn'
                    );


                if (!button) {
                    return;
                }


                event.stopPropagation();


                const rowIndex =
                    parseInt(
                        button.dataset.rowIndex
                    );


                /*
                | Si le menu actif appartient déjà
                | à ce bouton => fermeture
                */

                if (
                    this.activeMenu &&
                    this.activeMenu.button === button
                ) {

                    this.closeActionMenu();

                    return;
                }


                this.closeActionMenu();


                this.createActionMenu(
                    button,
                    rowIndex
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Events globaux
    |--------------------------------------------------------------------------
    */

    bindGlobalEvents() {


        /*
        |--------------------------------------------------------------------------
        | Clic extérieur
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            event => {

                if (
                    event.target.closest(
                        '.custom-table-action-menu'
                    )
                ) {

                    const action =
                        event.target.closest(
                            '.custom-table-action-item'
                        );


                    if (!action) {
                        return;
                    }


                    const actionName =
                        action.dataset.action;


                    const rowIndex =
                        parseInt(
                            action.dataset.rowIndex
                        );


                    const row =
                        this.currentData[rowIndex];


                    if (
                        this.options.onAction &&
                        row
                    ) {

                        this.options.onAction(
                            actionName,
                            row
                        );

                    }


                    this.closeActionMenu();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Clic en dehors
                |--------------------------------------------------------------------------
                */

                if (
                    !event.target.closest(
                        '.custom-table-action-btn'
                    )
                ) {

                    this.closeActionMenu();

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Scroll
        |--------------------------------------------------------------------------
        |
        | Comme le menu est positionné en fixed,
        | on le ferme pendant le scroll.
        |
        */

        window.addEventListener(
            'scroll',
            () => {

                this.closeActionMenu();

            },
            true
        );


        /*
        |--------------------------------------------------------------------------
        | Resize
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            () => {

                this.closeActionMenu();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Création du dropdown
    |--------------------------------------------------------------------------
    */

    createActionMenu(button, rowIndex) {

        const row =
            this.currentData[rowIndex];


        if (!row) {
            return;
        }


        const menu =
            document.createElement('div');


        menu.className =
            'custom-table-action-menu';


        /*
        |--------------------------------------------------------------------------
        | Actions visibles
        |--------------------------------------------------------------------------
        */

        const actions =
            (this.options.actions || [])
                .filter(action => {

                    /*
                    | Si visible n'est pas défini,
                    | l'action est visible.
                    */

                    if (
                        typeof action.visible !==
                        'function'
                    ) {

                        return true;

                    }


                    return action.visible(
                        row,
                        rowIndex
                    );

                });


        /*
        |--------------------------------------------------------------------------
        | Aucun menu disponible
        |--------------------------------------------------------------------------
        */

        if (!actions.length) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Construction des boutons
        |--------------------------------------------------------------------------
        */

        menu.innerHTML =
            actions
                .map(action => {

                    const actionClass =
                        action.class
                        ? this.escape(
                            action.class
                        )
                        : '';


                    const icon =
                        action.icon
                        ? `
                            <i class="${this.escape(
                                action.icon
                            )}"></i>
                        `
                        : '';


                    return `

                        <button
                            type="button"
                            class="
                                custom-table-action-item
                                ${actionClass}
                            "
                            data-action="${this.escape(
                                action.name
                            )}"
                            data-row-index="${rowIndex}"
                        >

                            ${icon}

                            <span>
                                ${this.escape(
                                    action.label
                                )}
                            </span>

                        </button>

                    `;

                })
                .join('');


        /*
        |--------------------------------------------------------------------------
        | Ajouter dans BODY
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | le menu n'est plus dans le tableau.
        |
        */

        document.body.appendChild(menu);


        /*
        |--------------------------------------------------------------------------
        | Positionnement
        |--------------------------------------------------------------------------
        */

        this.positionActionMenu(
            button,
            menu
        );


        /*
        |--------------------------------------------------------------------------
        | Affichage
        |--------------------------------------------------------------------------
        */

        requestAnimationFrame(() => {

            menu.classList.add('show');

        });


        this.activeMenu = {
            menu: menu,
            button: button
        };

    }


    /*
    |--------------------------------------------------------------------------
    | Positionner le dropdown
    |--------------------------------------------------------------------------
    */

    positionActionMenu(button, menu) {

        const rect =
            button.getBoundingClientRect();


        /*
        |--------------------------------------------------------------------------
        | Dimensions
        |--------------------------------------------------------------------------
        */

        const menuWidth = 190;


        /*
        | Le menu doit être ajouté au DOM avant
        | de pouvoir mesurer correctement sa hauteur.
        */

        const menuHeight =
            menu.offsetHeight || 150;


        const viewportWidth =
            window.innerWidth;


        const viewportHeight =
            window.innerHeight;


        const margin = 8;


        /*
        |--------------------------------------------------------------------------
        | Position horizontale
        |--------------------------------------------------------------------------
        */

        let left =
            rect.right - menuWidth;


        /*
        | Ne pas sortir à gauche
        */

        if (left < margin) {

            left = margin;

        }


        /*
        | Ne pas sortir à droite
        */

        if (
            left + menuWidth >
            viewportWidth - margin
        ) {

            left =
                viewportWidth -
                menuWidth -
                margin;

        }


        /*
        |--------------------------------------------------------------------------
        | Position verticale
        |--------------------------------------------------------------------------
        */

        const spaceBelow =
            viewportHeight -
            rect.bottom;


        const spaceAbove =
            rect.top;


        let top;


        /*
        |--------------------------------------------------------------------------
        | Ouvrir vers le haut si nécessaire
        |--------------------------------------------------------------------------
        */

        if (
            spaceBelow < menuHeight &&
            spaceAbove >= menuHeight
        ) {

            top =
                rect.top -
                menuHeight -
                5;

        } else {

            top =
                rect.bottom +
                5;

        }


        /*
        |--------------------------------------------------------------------------
        | Sécurité verticale
        |--------------------------------------------------------------------------
        */

        if (top < margin) {

            top = margin;

        }


        if (
            top + menuHeight >
            viewportHeight - margin
        ) {

            top =
                viewportHeight -
                menuHeight -
                margin;

        }


        /*
        |--------------------------------------------------------------------------
        | Appliquer
        |--------------------------------------------------------------------------
        */

        menu.style.left =
            `${left}px`;

        menu.style.top =
            `${top}px`;

    }


    /*
    |--------------------------------------------------------------------------
    | Fermer dropdown
    |--------------------------------------------------------------------------
    */

    closeActionMenu() {

        if (!this.activeMenu) {
            return;
        }


        const menu =
            this.activeMenu.menu;


        if (menu && menu.parentNode) {

            menu.parentNode.removeChild(
                menu
            );

        }


        this.activeMenu = null;

    }


    /*
    |--------------------------------------------------------------------------
    | Chargement AJAX
    |--------------------------------------------------------------------------
    */

    async load() {

        this.showLoading();


        try {

            const params =
                new URLSearchParams();


            /*
            |--------------------------------------------------------------------------
            | Pagination
            |--------------------------------------------------------------------------
            */

            params.set(
                'page',
                this.page
            );


            params.set(
                'per_page',
                this.options.perPage
            );


            /*
            |--------------------------------------------------------------------------
            | Recherche
            |--------------------------------------------------------------------------
            */

            if (this.search !== '') {

                params.set(
                    'search',
                    this.search
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Paramètres supplémentaires
            |--------------------------------------------------------------------------
            */

            if (this.options.params) {

                const extraParams =
                    typeof this.options.params === 'function'
                    ? this.options.params()
                    : this.options.params;


                Object.entries(extraParams)
                    .forEach(([key, value]) => {

                        if (
                            value !== null &&
                            value !== undefined &&
                            value !== ''
                        ) {

                            params.set(
                                key,
                                value
                            );

                        }

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            const separator =
                this.options.url.includes('?')
                ? '&'
                : '?';


            const url =
                `${this.options.url}${separator}${params.toString()}`;


            /*
            |--------------------------------------------------------------------------
            | Fetch
            |--------------------------------------------------------------------------
            */

            const response =
                await fetch(
                    url,
                    {
                        method:
                            this.options.method,

                        headers: {
                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        }
                    }
                );


            if (!response.ok) {

                throw new Error(
                    `HTTP ${response.status}`
                );

            }


            const result =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Données
            |--------------------------------------------------------------------------
            */

            this.currentData =
                result.data || [];


            this.meta =
                result.meta || {};


            /*
            |--------------------------------------------------------------------------
            | Affichage
            |--------------------------------------------------------------------------
            */

            this.closeActionMenu();


            this.renderRows();

            this.renderPagination();

            this.renderInfo();


        } catch (error) {

            console.error(
                'CustomTable:',
                error
            );


            this.renderError();


        } finally {

            this.hideLoading();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Lignes
    |--------------------------------------------------------------------------
    */

    renderRows() {

        if (!this.currentData.length) {

            this.body.innerHTML = `

                <tr>

                    <td
                        colspan="${
                            this.options.columns.length +
                            (this.options.actions ? 1 : 0)
                        }"
                        class="custom-table-empty"
                    >

                        Aucun résultat trouvé

                    </td>

                </tr>

            `;

            return;
        }


        this.body.innerHTML =
            this.currentData
                .map((row, rowIndex) => {

                    let html =
                        '<tr>';


                    /*
                    |--------------------------------------------------------------------------
                    | Colonnes
                    |--------------------------------------------------------------------------
                    */

                    this.options.columns
                        .forEach(column => {

                            let value =
                                this.getValue(
                                    row,
                                    column.data
                                );


                            if (
                                column.render
                            ) {

                                value =
                                    column.render(
                                        value,
                                        row,
                                        rowIndex
                                    );

                            } else {

                                value =
                                    this.escape(
                                        value
                                    );

                            }


                            html += `

                                <td>
                                    ${value ?? ''}
                                </td>

                            `;

                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Actions
                    |--------------------------------------------------------------------------
                    |
                    | IMPORTANT :
                    | seulement le bouton est placé
                    | dans le tableau.
                    |
                    */

                    if (
                        this.options.actions
                    ) {

                        html += `

                            <td>

                                <div class="custom-table-actions">

                                    <button
                                        type="button"
                                        class="custom-table-action-btn"
                                        data-row-index="${rowIndex}"
                                        aria-label="Actions"
                                    >

                                        ⋮

                                    </button>

                                </div>

                            </td>

                        `;

                    }


                    html +=
                        '</tr>';


                    return html;

                })
                .join('');

    }


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    // avec ->paginate
    // renderPagination() {

    //     if (
    //         !this.options.showPagination
    //     ) {

    //         return;

    //     }


    //     const current =
    //         parseInt(
    //             this.meta.current_page || 1
    //         );


    //     const last =
    //         parseInt(
    //             this.meta.last_page || 1
    //         );


    //     if (last <= 1) {

    //         this.pagination.innerHTML =
    //             '';

    //         return;
    //     }


    //     let html = '';


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Précédent
    //     |--------------------------------------------------------------------------
    //     */

    //     html += `

    //         <button
    //             type="button"
    //             class="custom-table-page"
    //             data-page="${current - 1}"
    //             ${current <= 1 ? 'disabled' : ''}
    //         >
    //             ‹
    //         </button>

    //     `;


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Pages
    //     |--------------------------------------------------------------------------
    //     */

    //     const pages =
    //         this.getPageNumbers(
    //             current,
    //             last
    //         );


    //     pages.forEach(page => {

    //         if (page === '...') {

    //             html += `

    //                 <span
    //                     style="
    //                         padding: 0 6px;
    //                         color:#6c757d;
    //                     "
    //                 >
    //                     ...
    //                 </span>

    //             `;

    //         } else {

    //             html += `

    //                 <button
    //                     type="button"
    //                     class="custom-table-page ${
    //                         page === current
    //                         ? 'active'
    //                         : ''
    //                     }"
    //                     data-page="${page}"
    //                 >
    //                     ${page}
    //                 </button>

    //             `;

    //         }

    //     });


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Suivant
    //     |--------------------------------------------------------------------------
    //     */

    //     html += `

    //         <button
    //             type="button"
    //             class="custom-table-page"
    //             data-page="${current + 1}"
    //             ${current >= last ? 'disabled' : ''}
    //         >
    //             ›
    //         </button>

    //     `;


    //     this.pagination.innerHTML =
    //         html;

    // }

    // avec ->simplePaginate
    // renderPagination() {

    //     if (!this.options.showPagination) {

    //         if (this.pagination) {

    //             this.pagination.innerHTML = '';

    //         }

    //         return;
    //     }


    //     if (!this.pagination) {
    //         return;
    //     }


    //     const current =
    //         parseInt(
    //             this.meta.current_page || 1
    //         );


    //     const hasMore =
    //         Boolean(
    //             this.meta.has_more_pages
    //         );


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Aucune pagination nécessaire
    //     |--------------------------------------------------------------------------
    //     */

    //     if (
    //         current <= 1 &&
    //         !hasMore
    //     ) {

    //         this.pagination.innerHTML = '';

    //         return;
    //     }


    //     let html = '';


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Précédent
    //     |--------------------------------------------------------------------------
    //     */

    //     html += `

    //         <button
    //             type="button"
    //             class="custom-table-page"
    //             data-page="${current - 1}"
    //             ${current <= 1 ? 'disabled' : ''}
    //         >
    //             ‹
    //         </button>

    //     `;


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Page actuelle
    //     |--------------------------------------------------------------------------
    //     */

    //     html += `

    //         <span
    //             class="custom-table-page-info"
    //             style="
    //                 padding: 0 12px;
    //                 display: inline-flex;
    //                 align-items: center;
    //             "
    //         >
    //             Page ${current}
    //         </span>

    //     `;


    //     /*
    //     |--------------------------------------------------------------------------
    //     | Suivant
    //     |--------------------------------------------------------------------------
    //     */

    //     html += `

    //         <button
    //             type="button"
    //             class="custom-table-page"
    //             data-page="${current + 1}"
    //             ${!hasMore ? 'disabled' : ''}
    //         >
    //             ›
    //         </button>

    //     `;


    //     this.pagination.innerHTML =
    //         html;

    // }

    // les deux en meme temps
    renderPagination() {

        /*
        |--------------------------------------------------------------------------
        | Pagination désactivée
        |--------------------------------------------------------------------------
        */

        if (!this.options.showPagination) {

            if (this.pagination) {

                this.pagination.innerHTML = '';

            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Vérification
        |--------------------------------------------------------------------------
        */

        if (!this.pagination) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Page courante
        |--------------------------------------------------------------------------
        */

        const current =
            parseInt(
                this.meta.current_page || 1
            );


        /*
        |--------------------------------------------------------------------------
        | Détection du type de pagination
        |--------------------------------------------------------------------------
        |
        | paginate()       => last_page existe
        | simplePaginate() => last_page absent
        |
        */

        const hasLastPage =
            this.meta.last_page !== null &&
            this.meta.last_page !== undefined;


        /*
        |--------------------------------------------------------------------------
        | PAGINATE
        |--------------------------------------------------------------------------
        */

        if (hasLastPage) {

            const last =
                parseInt(
                    this.meta.last_page || 1
                );


            /*
            |--------------------------------------------------------------------------
            | Une seule page
            |--------------------------------------------------------------------------
            */

            if (last <= 1) {

                this.pagination.innerHTML =
                    '';

                return;
            }


            let html = '';


            /*
            |--------------------------------------------------------------------------
            | Précédent
            |--------------------------------------------------------------------------
            */

            html += `

                <button
                    type="button"
                    class="custom-table-page"
                    data-page="${current - 1}"
                    ${current <= 1 ? 'disabled' : ''}
                >
                    ‹
                </button>

            `;


            /*
            |--------------------------------------------------------------------------
            | Numéros de pages
            |--------------------------------------------------------------------------
            */

            const pages =
                this.getPageNumbers(
                    current,
                    last
                );


            pages.forEach(page => {

                /*
                |--------------------------------------------------------------------------
                | Ellipsis
                |--------------------------------------------------------------------------
                */

                if (page === '...') {

                    html += `

                        <span
                            class="custom-table-page-info"
                            style="
                                padding: 0 6px;
                                color: #6c757d;
                            "
                        >
                            ...
                        </span>

                    `;

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Bouton page
                |--------------------------------------------------------------------------
                */

                html += `

                    <button
                        type="button"
                        class="custom-table-page ${
                            page === current
                                ? 'active'
                                : ''
                        }"
                        data-page="${page}"
                    >
                        ${page}
                    </button>

                `;

            });


            /*
            |--------------------------------------------------------------------------
            | Suivant
            |--------------------------------------------------------------------------
            */

            html += `

                <button
                    type="button"
                    class="custom-table-page"
                    data-page="${current + 1}"
                    ${current >= last ? 'disabled' : ''}
                >
                    ›
                </button>

            `;


            this.pagination.innerHTML =
                html;


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPLE PAGINATE
        |--------------------------------------------------------------------------
        */

        const hasMore =
            Boolean(
                this.meta.has_more_pages
            );


        /*
        |--------------------------------------------------------------------------
        | Aucune pagination nécessaire
        |--------------------------------------------------------------------------
        */

        if (
            current <= 1 &&
            !hasMore
        ) {

            this.pagination.innerHTML =
                '';

            return;
        }


        let html = '';


        /*
        |--------------------------------------------------------------------------
        | Précédent
        |--------------------------------------------------------------------------
        */

        html += `

            <button
                type="button"
                class="custom-table-page"
                data-page="${current - 1}"
                ${current <= 1 ? 'disabled' : ''}
            >
                ‹
            </button>

        `;


        /*
        |--------------------------------------------------------------------------
        | Page actuelle
        |--------------------------------------------------------------------------
        */

        html += `

            <span
                class="custom-table-page-info"
                style="
                    padding: 0 12px;
                    display: inline-flex;
                    align-items: center;
                "
            >
                Page ${current}
            </span>

        `;


        /*
        |--------------------------------------------------------------------------
        | Suivant
        |--------------------------------------------------------------------------
        */

        html += `

            <button
                type="button"
                class="custom-table-page"
                data-page="${current + 1}"
                ${!hasMore ? 'disabled' : ''}
            >
                ›
            </button>

        `;


        this.pagination.innerHTML =
            html;

    }


    /*
    |--------------------------------------------------------------------------
    | Numéros de pages
    |--------------------------------------------------------------------------
    */

    getPageNumbers(current, last) {

        if (last <= 7) {

            return Array.from(
                { length: last },
                (_, i) => i + 1
            );

        }


        const pages = [];


        pages.push(1);


        if (current > 4) {

            pages.push('...');

        }


        const start =
            Math.max(
                2,
                current - 2
            );


        const end =
            Math.min(
                last - 1,
                current + 2
            );


        for (
            let i = start;
            i <= end;
            i++
        ) {

            pages.push(i);

        }


        if (current < last - 3) {

            pages.push('...');

        }


        pages.push(last);


        return pages;

    }


    /*
    |--------------------------------------------------------------------------
    | Informations
    |--------------------------------------------------------------------------
    */

    // avec ->paginate
    // renderInfo() {

    //     if (
    //         !this.options.showInfo
    //     ) {

    //         return;

    //     }


    //     const total =
    //         parseInt(
    //             this.meta.total || 0
    //         );


    //     const from =
    //         this.meta.from || 0;


    //     const to =
    //         this.meta.to || 0;


    //     this.info.innerHTML =

    //         total > 0

    //         ? `
    //             Affichage de
    //             <strong>${from}</strong>
    //             à
    //             <strong>${to}</strong>
    //             sur
    //             <strong>${total}</strong>
    //             résultats
    //         `

    //         : 'Aucun résultat';

    // }

    // avec ->simplePaginate
    // renderInfo() {

    //     if (
    //         !this.options.showInfo
    //     ) {

    //         return;
    //     }


    //     if (!this.info) {
    //         return;
    //     }


    //     const current =
    //         parseInt(
    //             this.meta.current_page || 1
    //         );


    //     const from =
    //         this.meta.from || 0;


    //     const to =
    //         this.meta.to || 0;


    //     const count =
    //         this.currentData.length;


    //     if (!count) {

    //         this.info.innerHTML =
    //             'Aucun résultat';

    //         return;
    //     }


    //     this.info.innerHTML = `

    //         Affichage de
    //         <strong>${from}</strong>
    //         à
    //         <strong>${to}</strong>
    //         — Page
    //         <strong>${current}</strong>

    //     `;

    // }

    // les deux en meme temps
    renderInfo() {

        if (
            !this.options.showInfo
        ) {

            return;
        }


        if (!this.info) {
            return;
        }


        const current =
            parseInt(
                this.meta.current_page || 1
            );


        const from =
            this.meta.from || 0;


        const to =
            this.meta.to || 0;


        const count =
            this.currentData.length;


        /*
        |--------------------------------------------------------------------------
        | Aucun résultat
        |--------------------------------------------------------------------------
        */

        if (!count) {

            this.info.innerHTML =
                'Aucun résultat';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATE
        |--------------------------------------------------------------------------
        |
        | Laravel fournit :
        |
        | total
        | last_page
        |
        */

        if (
            this.meta.total !== null &&
            this.meta.total !== undefined
        ) {

            const total =
                parseInt(
                    this.meta.total || 0
                );


            this.info.innerHTML =

                total > 0

                    ? `
                        Affichage de
                        <strong>${from}</strong>
                        à
                        <strong>${to}</strong>
                        sur
                        <strong>${total}</strong>
                        résultats
                    `

                    : 'Aucun résultat';


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPLE PAGINATE
        |--------------------------------------------------------------------------
        |
        | Pas de total global.
        |
        */

        this.info.innerHTML = `

            Affichage de
            <strong>${from}</strong>
            à
            <strong>${to}</strong>
            — Page
            <strong>${current}</strong>

        `;

    }

    /*
    |--------------------------------------------------------------------------
    | Erreur
    |--------------------------------------------------------------------------
    */

    renderError() {

        this.body.innerHTML = `

            <tr>

                <td
                    colspan="${
                        this.options.columns.length +
                        (this.options.actions ? 1 : 0)
                    }"
                    class="custom-table-empty"
                >

                    Une erreur est survenue
                    lors du chargement.

                </td>

            </tr>

        `;

    }


    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    */

    showLoading() {

        if (this.loading) {

            this.loading.style.display =
                'flex';

        }

    }


    hideLoading() {

        if (this.loading) {

            this.loading.style.display =
                'none';

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Valeur imbriquée
    |--------------------------------------------------------------------------
    */

    getValue(row, path) {

        if (!path) {

            return '';

        }


        return path
            .split('.')
            .reduce(
                (obj, key) =>
                    obj?.[key],
                row
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Sécurité HTML
    |--------------------------------------------------------------------------
    */

    escape(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }

}