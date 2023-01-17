@extends('v2.layouts.dashboard')

@section('title', 'Notifications')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Historique des notifications : </h1>
    </div>
    <section id="app">
        <button @click="clearNotifications()" type="button" class="btn btn-danger my-2">
            <i class="fa-solid fa-trash-can me-1"></i>
            Supprimer l'historique !
        </button>

        <div class="row ">

            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body needs-validation">

                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class="col-sm-2">
                                <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                    <option :value="10">10</option>
                                    <option :value="15">15</option>
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                </select>
                            </div>
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="Rechercher ..."
                                        @keyup.enter="loadFilter()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                            @selectedchanged="selectedChanged">
                            <template #data="props">
                                @{{ parseJson(props.row.value.data).body }}
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


        let app = Vue.createApp({
            data() {

                return {
                    notifications:[],
                    search: '',
                    parents : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'notifications_datatable',
                        api: '/api/v2/notifications/filter',
                        columns: [{
                                name: 'Subject ID',
                                field: 'notifiable_id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Message',
                                field: 'data',
                                type: 'string',
                                customize: true,
                                searchable: true
                            },
                            {
                                name: 'Date',
                                field: 'created_at',
                                type: 'date',
                                sortable: true,
                                searchable: false
                            },

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
                            per_page: 10,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                }
            },
            watch: {

                },

            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {
            },
            methods: {

                clearNotifications(){

                    Swal.fire({
                        title: 'Vous êtes sûres?',
                        text: "Vous ne pouvez plus revenir",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimer!',
                        cancelButtonText: 'Annuler'
                        }).then((result) => {
                        if (result.isConfirmed) {
                            const options = {
                        url: '/api/v2/clearAllNotifications',
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    }
                    axios(options)
                        .then(response => {
                            if(response.status == 200)
                            {
                                Swal.fire(
                                'Supprimé !',
                                'Les notifications ont été supprimées',
                                'success'
                                )
                                setInterval(() => {
                                    location.reload()
                                }, 3000);
                            }

                        })

                        }
                    })



                },
              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'designation',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },
                parseJson(val){
                    return JSON.parse(val);
                }
            }
        }).mount('#app')
    </script>

@endsection
