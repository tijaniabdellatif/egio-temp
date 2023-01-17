@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <style>
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
                        <datatable-component :datatable="datatable" >

                            <template #message="props" >
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Repellendus architecto qui reiciendis facilis voluptatum dolorum officia mollitia, blanditiis doloribus accusantium nostrum tenetur harum quibusdam veniam sunt debitis natus asperiores aliquid.
                            </template>

                            <template #status="props">
                                <span class="badge" :class="
                                        props.column.value==0?
                                            'bg-primary':
                                        props.column.value==1?
                                            'bg-success':
                                        props.column.value==2?
                                            'bg-danger':
                                        props.column.value==3?
                                            'bg-warning':''">
                                    @{{ email_status[props.column.value]??'?' }}
                                </span>
                            </template>

                            <template #action="props" >
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
                                name : 'Annonce',
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
            computed : {
                email_status(){
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
        }).mount('#app')
    </script>

@endsection
