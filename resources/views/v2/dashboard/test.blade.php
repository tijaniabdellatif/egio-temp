@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/dashboard.styles.css') }}">

    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <style>
        .search-input {
            position: relative;
            display: flex;
        }

        .search-input input:focus~.search-list {
            display: block;
        }

        .search-input .search-list {
            position: absolute;
            width: 100%;
            top: 100%;
            background: white;
            z-index: 1;
            max-height: 100px;
            overflow-y: scroll;
            display: none;
        }

        .search-input .search-list .search-item {
            padding: 5px;
            cursor: pointer;
        }

        .search-input .search-list .search-item:hover {
            background: #f5f5f5;
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
                        <h5 class="card-title">Datatables</h5>
                        <p>Add lightweight datatables to your project with using the <a
                                href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple
                                DataTables</a> library. Just add <code>.datatable</code> class name to any table you
                            wish to conver to a datatable
                        </p>
                        <div class="row">
                            <div class="col-lg-12">
                                <input class="form-control w-100" v-model="search" @keyup.enter="datatableSearch()"
                                    type="text" name="">
                            </div>
                        </div>
                        <datatable-component :datatable="datatable" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ml-select" >
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Select Input</h5>
                        <div class="form-group">
                            <label for="">Select input</label>
                            <ml-select :options="options" v-model:selected-option="selectedOption" mls-class="form-control" mls-placeholder="select an option" v-model="selected" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="app2">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Search component</h5>
                        <div class="form-group">
                            <label for="">Search input</label>

                            <div class="d-flex search-input">
                                <input type="text" class="form-control" name="" id=""
                                    aria-describedby="helpId" placeholder="" v-model="search">
                                <div class="search-list form-control">
                                    <div v-if="loading" class="search-item">loading...</div>
                                    <div class="search-item" v-for="item in data">@{{ item.username }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="section" id="app3">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Files upload</h5>
                        <div class="form-group mt-2">
                            <label for="">Upload images</label>
                            <upload-files-component v-model:files="images" type="images" :max="50"
                                :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="true" />
                        </div>
                        <div class="form-group mt-2">
                            <label for="">Upload videos</label>
                            <upload-files-component v-model:files="videos" type="videos" :max="50"
                                :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                        </div>
                        <div class="form-group mt-2">
                            <label for="">Upload audios</label>
                            <upload-files-component v-model:files="audios" type="audios" :max="50"
                                :allowed-extensions="['mpeg', 'mpga', 'mp3', 'wav', 'aac']" :multiple="true" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section id="loaderApp">
        <button @click="openLoader">Open loader</button>
        <div v-if="globalLoader==true" id="globalLoader" class="globalLoader">
            <div
                style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Operation en cours...</div>
            </div>
        </div>
    </section>



@endsection

@section('custom_foot')

    <script type="text/javascript">

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

                // axios post api/v2/logout

                // axios.post('/api/v2/logout').then(response => {
                //     console.log(response);
                // }).catch(error => {
                //     console.log(error);
                // });


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

        Vue.createApp({
            data() {
                return {
                    searchInput: {
                        api: '/api/v2/user/filter',
                        columns: ['id', 'firstname', 'lastname', 'username', 'email', 'phone'],
                    },
                    data: [],
                    slectedVlaue: null,
                    search: '',
                    loading: false,
                    searchInterval: null
                }
            },
            components: {},
            watch: {
                // watch search and loadData
                search() {
                    this.loadData(this.search);
                }
            },
            mounted() {
                this.loadData('');
            },
            methods: {
                loadData(search = '') {

                    // wait untill the loading is finished and pass
                    if (this.loading) {
                        if (this.searchInterval)
                            clearInterval(this.searchInterval);
                        this.searchInterval = setInterval(() => {
                            if (!this.loading) {
                                this.loadData(search);
                                clearInterval(this.searchInterval);
                            }
                        }, 1000);
                        return;
                    }

                    console.log(search);

                    let filters = [];

                    //loop through the columns and filter them to filters
                    this.searchInput.columns.forEach((col, i) => {

                        let type = 'orWhere';
                        if (i == 0) {
                            type = 'where';
                        }

                        filters.push({
                            type,
                            col: col,
                            val: `%${search}%`,
                            op: 'LIKE'
                        });

                    })

                    // convert filters to json string
                    let laravelWhereJson = JSON.stringify(filters);

                    formData = new FormData();
                    formData.append('where', laravelWhereJson);

                    var config = {
                        method: 'post',
                        url: this.searchInput.api,
                        data: formData
                    };

                    this.loading = true;
                    this.data = [];

                    axios(config)
                        .then((response) => {
                            this.loading = false;
                            console.log(response.data.data);
                            if (response.data.success) {
                                this.data = response.data.data;
                            }
                        })
                        .catch((error) => {
                            this.loading = false;
                        });
                }
            },
        }).mount('#app2');

        Vue.createApp({
            data() {
                return {
                    images: [{
                        "id": 1202,
                        "name": "/images/img_629c80b680566.jpg"
                    }],
                    videos: [],
                    audios: []
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent
            },
            watch: {},
            mounted() {},
            methods: {},
        }).mount('#app3');

        let loaderApp = Vue.createApp({
            data() {
                return {
                    globalLoader: false,
                }
            },
            watch: {},
            mounted() {},
            methods: {
                openLoader() {
                    this.globalLoader = true;
                    setTimeout(() => {
                        this.globalLoader = false;
                    }, 5000);
                }
            },
        }).mount('#loaderApp');

        // ml-select
        let mlsel = Vue.createApp({
            data(){
                return {
                    selected: null,
                    options: [
                        { value: 1, label: 'One' },
                        { value: 2, label: 'Two' }
                    ],
                    selectedOption: null,
                }
            },
            components: {
                'ml-select': MlSelect
            },
            watch: {},
            mounted() {},
            methods: {},
        }).mount('#ml-select');

    </script>

@endsection
