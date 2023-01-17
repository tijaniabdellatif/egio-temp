@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <style>
        #chat3 .form-control {
            border-color: transparent;
        }

        #chat3 .form-control:focus {
            border-color: transparent;
            box-shadow: inset 0px 0px 0px 1px transparent;
        }

        .badge-dot {
            border-radius: 50%;
            height: 10px;
            width: 10px;
            margin-left: 2.9rem;
            margin-top: -.75rem;
        }
        .users-list,
        .chat-conversation{
            overflow-y:scroll;
            margin-top: 5px;
        }

        /* left scrollbar 3px and gray with transparent background */
        #conversation-app *::-webkit-scrollbar {
            width: 3px;
            background-color: transparent;
        }
        #conversation-app *::-webkit-scrollbar-thumb {
            background-color: #f1f1f1;
            border-radius: 10px;
        }



    </style>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div>

    <section class="section" id="app">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Emails : </h5>
                        <div class="row">
                            <div class="col-lg-12">
                                <input class="form-control w-100" v-model="search" @keyup.enter="datatableSearch()"
                                    type="text" name="">
                            </div>
                        </div>
                        <datatable-component :datatable="datatable">

                            <template #message="props">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus architecto qui
                                reiciendis facilis voluptatum dolorum officia mollitia, blanditiis doloribus accusantium
                                nostrum tenetur harum quibusdam veniam sunt debitis natus asperiores aliquid.
                            </template>

                            <template #status="props">
                                <span class="badge"
                                    :class="props.column.value == 0 ?
                                        'bg-primary' :
                                        props.column.value == 1 ?
                                        'bg-success' :
                                        props.column.value == 2 ?
                                        'bg-danger' :
                                        props.column.value == 3 ?
                                        'bg-warning' : ''">
                                    @{{ email_status[props.column.value] ?? '?' }}
                                </span>
                            </template>

                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="editUser(props.row.value)">
                                    <i class="fa-solid fa-check text-success"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" @click="userState(props.row.value)">
                                    <i class="fa-solid fa-ban text-danger"></i>
                                </button>
                            </template>
                        </datatable-component>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="conversation-app">
        <popup-component :title="'Conversation'" :loading="loading" v-model:display="display">
            <section>

                <div class="row">
                    <div class="col-md-12">

                        <div class="card" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

                                        <div class="p-3">

                                            <div class="input-group d-flex align-items-center px-2 py-1 border-radius-5" style="background:#fbfbfb">
                                                <input type="search" class="form-control bg-transparent rounded" placeholder="Search"
                                                    aria-label="Search" aria-describedby="search-addon" />
                                                <span class="text-secondary border-0" id="search-addon">
                                                    <i class="fas fa-search"></i>
                                                </span>
                                            </div>

                                            <div data-mdb-perfect-scrollbar="true" class="users-list"
                                                style="position: relative; height: 400px">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Marie Horwitz</p>
                                                                    <p class="small text-muted">Hello, Are you there?
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Just now</p>
                                                                <span
                                                                    class="badge bg-danger rounded-pill float-end">3</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-warning badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Alexa Chung</p>
                                                                    <p class="small text-muted">Lorem ipsum dolor sit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">5 mins ago</p>
                                                                <span
                                                                    class="badge bg-danger rounded-pill float-end">2</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Danny McChain</p>
                                                                    <p class="small text-muted">Lorem ipsum dolor sit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Yesterday</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-danger badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Ashley Olsen</p>
                                                                    <p class="small text-muted">Lorem ipsum dolor sit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Yesterday</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="p-2 border-bottom">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava5-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-warning badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Kate Moss</p>
                                                                    <p class="small text-muted">Lorem ipsum dolor sit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Yesterday</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="p-2">
                                                        <a href="#!" class="d-flex justify-content-between">
                                                            <div class="d-flex flex-row">
                                                                <div>
                                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                                        alt="avatar" class="d-flex align-self-center me-3"
                                                                        width="60">
                                                                    <span class="badge bg-success badge-dot"></span>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p class="fw-bold mb-0">Ben Smith</p>
                                                                    <p class="small text-muted">Lorem ipsum dolor sit.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="pt-1">
                                                                <p class="small text-muted mb-1">Yesterday</p>
                                                            </div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6 col-lg-7 col-xl-8">

                                        <div class="pt-3 pe-3 chat-conversation" data-mdb-perfect-scrollbar="true"
                                            style="position: relative; height: 400px">

                                            <div class="d-flex flex-row justify-content-start">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3"
                                                        style="background-color: #f5f6f7;">Lorem ipsum
                                                        dolor
                                                        sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                                        incididunt ut labore et
                                                        dolore
                                                        magna aliqua.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM |
                                                        Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">Ut
                                                        enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex ea
                                                        commodo consequat.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13
                                                    </p>
                                                </div>
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3"
                                                        style="background-color: #f5f6f7;">Duis aute
                                                        irure
                                                        dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                                        fugiat nulla pariatur.
                                                    </p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM
                                                        | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">
                                                        Excepteur sint occaecat
                                                        cupidatat
                                                        non proident, sunt in culpa qui officia deserunt mollit anim id
                                                        est laborum.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13
                                                    </p>
                                                </div>
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3"
                                                        style="background-color: #f5f6f7;">Sed ut
                                                        perspiciatis
                                                        unde omnis iste natus error sit voluptatem accusantium
                                                        doloremque laudantium, totam
                                                        rem
                                                        aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                        architecto beatae vitae
                                                        dicta
                                                        sunt explicabo.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM
                                                        | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">Nemo
                                                        enim ipsam
                                                        voluptatem quia
                                                        voluptas sit aspernatur aut odit aut fugit, sed quia
                                                        consequuntur magni dolores eos
                                                        qui
                                                        ratione voluptatem sequi nesciunt.</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13
                                                    </p>
                                                </div>
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                            </div>

                                            <div class="d-flex flex-row justify-content-start">
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                                <div>
                                                    <p class="small p-2 ms-3 mb-1 rounded-3"
                                                        style="background-color: #f5f6f7;">Neque porro
                                                        quisquam
                                                        est, qui dolorem ipsum quia dolor sit amet, consectetur,
                                                        adipisci velit, sed quia non
                                                        numquam
                                                        eius modi tempora incidunt ut labore et dolore magnam aliquam
                                                        quaerat voluptatem.</p>
                                                    <p class="small ms-3 mb-3 rounded-3 text-muted float-end">12:00 PM
                                                        | Aug 13</p>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row justify-content-end">
                                                <div>
                                                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">Ut
                                                        enim ad minima veniam,
                                                        quis
                                                        nostrum exercitationem ullam corporis suscipit laboriosam, nisi
                                                        ut aliquid ex ea
                                                        commodi
                                                        consequatur?</p>
                                                    <p class="small me-3 mb-3 rounded-3 text-muted">12:00 PM | Aug 13
                                                    </p>
                                                </div>
                                                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava1-bg.webp"
                                                    alt="avatar 1" style="width: 45px; height: 100%;">
                                            </div>

                                        </div>

                                        <div
                                            class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava6-bg.webp"
                                                alt="avatar 3" style="width: 40px; height: 100%;">
                                            <input type="text" style="background: #f7f7f7;" class="form-control form-control-lg mx-3"
                                                id="exampleFormControlInput2" placeholder="Type message">
                                            <a class="ms-1 text-muted" href="#!"><i class="fas fa-paperclip"></i></a>
                                            <a class="ms-3 text-muted" href="#!"><i class="fas fa-smile"></i></a>
                                            <a class="ms-3" href="#!"><i class="fas fa-paper-plane"></i></a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </section>
        </popup-component>
    </div>


@endsection

@section('custom_foot')

    <script type="text/javascript">
        // id
        // ad_id
        // name
        // email
        // phone
        // message
        // date

        Vue.createApp({
            data() {
                return {
                    search: '',
                    datatable: {
                        api: '/api/v2/emails/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Annonce',
                                field: 'ad_id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Utilisateur',
                                field: 'username',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Email',
                                field: 'email',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: 'Téléphone',
                                field: 'phone',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Message',
                                field: 'message',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: 'Date',
                                field: 'date',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Statu',
                                field: 'status',
                                type: 'string',
                                sortable: false,
                                searchable: false,
                                customize: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'action',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 30,
                            total: 0,
                            links: []
                        }
                    }
                }
            },
            computed: {
                email_status() {
                    return email_status_obj;
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            watch: {},
            mounted() {

            },
            methods: {
                datatableSearch() {
                    this.datatable.filters = [{
                            type: 'where',
                            col: 'id',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'name',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'email',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'phone',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'message',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'date',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        }
                    ]
                }
            },
        }).mount('#app');

        conversationApp = Vue.createApp({
            data() {
                return {
                    loading: false,
                    display: true
                }
            },
            components: {
                'PopupComponent': PopupComponent
            }
        }).mount('#conversation-app');
    </script>

@endsection
