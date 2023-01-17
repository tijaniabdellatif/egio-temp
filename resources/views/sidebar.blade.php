<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- <li class="nav-item">
            <a class="nav-link " href="{{ url('/') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Annonces</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ url('annonces') }}">
                        <i class="bi bi-circle"></i><span>Toutes les annonces</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url('annonces') }}">
                        <i class="bi bi-circle"></i><span>À valider</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav --> --}}

        <span v-html="generateSidebarHtml()"></span>

    </ul>

</aside><!-- End Sidebar-->

<script type="text/javascript">
    let {
        createApp
    } = Vue;

    let test = createApp({
        data() {
            return {
                menu: [{
                        name: 'Dashboard',
                        href: '/admin',
                        icon: 'bi bi-bar-chart-fill',
                        can: `1`,
                    },
                    {
                        name: 'Gestion des annonces',
                        icon: 'bi bi-layout-text-window-reverse',
                        can: `{{auth()->user()->hasAnyPermission(['Show-ads','Add-ads'])}}`,
                        children: [{
                                name: 'Ajouter une annonce',
                                href: '/admin/new-ad',
                                can: `{{auth()->user()->hasAnyPermission(['Add-ads'])}}`,
                                icon: 'bi bi-circle'
                            },
                            {
                                name: 'Toutes les annonces',
                                href: '/admin/items',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-ads'])}}`,
                            },

                        ]
                    },
                    {
                        name: 'Gestion des utilisateurs',
                        icon: 'bi bi-person',
                        can: `{{auth()->user()->hasAnyPermission(['Add-user','Show-users'])}}`,
                        children: [{
                                name: 'Ajouter un utilisateur',
                                href: '/admin/user/add',
                                can: `{{auth()->user()->hasAnyPermission(['Add-user'])}}`,
                                icon: 'bi bi-circle'
                            },
                            {
                                name: 'Tous les utilisateurs',
                                href: '/admin/users',
                                can: `{{auth()->user()->hasAnyPermission(['Show-users'])}}`,
                                icon: 'bi bi-circle'
                            }
                        ]
                    },
                    {
                        name: 'Gestion des emails',
                        href: '/admin/emails',
                        icon: 'fa-solid fa-envelope',
                        can: `{{auth()->user()->hasAnyPermission(['Show-emails'])}}`,
                    },
                    // {
                    //     name: 'Gestion des messages',
                    //     href: '/admin/messages',
                    //     icon: 'fa-solid fa-message'
                    // },
                    {
                        name: 'Paramètres',
                        icon: 'bi bi-gear',
                        can:  `1`,
                        children: [
                            {
                                name: 'SEO',
                                href: '/admin/settings/seo',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-seo'])}}`,
                            },
                            {
                                name: 'Cycle de vie',
                                href: '/admin/settings/cycle-de-vie',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-cycle'])}}`,
                            },
                            {
                                name: 'Liens',
                                href: '/admin/settings/links',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-links'])}}`,
                            },
                            {
                                name: 'Gestion des bannières',
                                href: '/admin/settings/banners',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-banners'])}}`,
                            },
                            {
                                name: 'Catégories',
                                href: '/admin/settings/categories',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-categories'])}}`,
                            },
                            {
                                name: 'Types des options',
                                href: '/admin/settings/option-types',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-property-types'])}}`,
                            },
                            {
                                name: 'Standings',
                                href: '/admin/settings/standings',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-standings'])}}`,
                            },
                            {
                                name: 'Localisations',
                                href: '/admin/settings/localisations',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-localisations'])}}`,
                            },
                            {
                                name: "Catalogue des plans d'abonnement",
                                href: '/admin/settings/plans',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-plans'])}}`,
                            },
                            {
                                name: "Catalogue des options de boost",
                                href: '/admin/settings/options',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-options'])}}`,
                            },
                            /* {
                                name: "Catalogue des Emails",
                                href: '/admin/settings/emails',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-emails-catalogue'])}}`,
                            }, */
                            {
                                name: "Les Pages Statiques",
                                href: '/admin/settings/pages',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-pages'])}}`,
                            },
                            {
                                name: 'Privilèges',
                                href: '/admin/settings/privileges',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-privileges'])}}`,
                            },
                            {
                                name: 'Logs',
                                href: '/admin/settings/logs',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-logs'])}}`,
                            },
                            {
                                name: 'Notifications',
                                href: '/admin/settings/notifications',
                                icon: 'bi bi-circle',
                                can: `{{auth()->user()->hasAnyPermission(['Show-notifications'])}}`,
                            },
                        ]
                    },

                ]
            }
        },
        methods: {
            generateSidebarHtml() {

                let html = '';

                //loop through menu items
                this.menu.forEach(item => {
                    if(item.can=="1")





                  /* To hide some items (Use it)
                    @if(auth()->user()->cannot('Add-user'))

                    if(item.name !== "Gestion des utilisateurs")

                    @endif */


                    //if item has children
                    if (item.children) {

                        //add menu item

                        active = false;
                        item.children.forEach(child => {
                            if(child.href==window.location.pathname) {
                                console.log('child',child.href,window.location.pathname);
                                active = true;
                            }
                        })




                        html +=
                            `<li class="nav-item">
                            <a class="nav-link ${ active?'':'collapsed' }" data-bs-target="#${item.name.toLowerCase().replaceAll(/\s+/g,'-')}-nav" data-bs-toggle="collapse" href="#">
                                <i class="${item.icon}"></i><span>${item.name}</span><i class="bi bi-chevron-down ms-auto"></i>
                            </a>`;

                        //add children
                        html +=
                            `<ul id="${item.name.toLowerCase().replaceAll(/\s+/g,'-')}-nav" class="nav-content collapse ${ active?'show':'' }" data-bs-parent="#sidebar-nav">`;

                        item.children.forEach(child => {
                            if(child.can=="1")
                            html +=
                                `<li>
                                <a href="${child.href}" class="${ child.href==window.location.pathname?'active':'' }">
                                    <i class="bi bi-circle"></i><span>${child.name}</span>
                                </a>
                            </li>
                            `;
                        });

                        html += `</ul></li>`;

                    } else {

                        html +=
                            `<li class="nav-item">
                            <a class="nav-link ${ item.href==window.location.pathname?'':'collapsed' }" href="${item.href}">
                                <i class="${item.icon}"></i><span>${item.name}</span>
                            </a>
                        </li>`;

                    }


                });

                return html;
            }
        }
    }).mount('#sidebar')
</script>

