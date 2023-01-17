@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
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
                        <h5 class="card-title">Datatables</h5>
                        <p>Add lightweight datatables to your project with using the <a
                                href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple
                                DataTables</a> library. Just add <code>.datatable</code> class name to any table you
                            wish to conver to a datatable
                        </p>
                        <div class="row">
                            <div class="col-lg-12">
                                <input class="form-control w-100" v-model="search" @keyup.enter="datatableSearch()" type="text"
                                    name="">
                            </div>
                        </div>
                        <datatable-component :datatable="datatable" />
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        // firstname
        // lastname
        // username
        // email
        // phone
        // usertype
        // status

        Vue.createApp({
            data() {
                return {
                    search: '',
                    datatable: {
                        api: '/api/v2/user/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'First Name',
                                field: 'firstname',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Last Name',
                                field: 'lastname',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Username',
                                field: 'username',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Email',
                                field: 'email',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Phone',
                                field: 'phone',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'User Type',
                                field: 'usertype',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Status',
                                field: 'status',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'string',
                                sortable: false,
                                searchable: false
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
                            col: 'firstname',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'lastname',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'username',
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
                            col: 'usertype',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'status',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        }
                    ]
                }
            },
        }).mount('#app')
    </script>

@endsection
