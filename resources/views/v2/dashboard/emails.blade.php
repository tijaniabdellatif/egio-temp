@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <style>
    </style>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Gestion des emails :</h1>
    </div>

    <section class="section" id="app">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Emails : </h5>

                        <fieldset class="filter_cnt">

                            <legend>Filtrer:</legend>

                            <div class="row">



                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">État :</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.status" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">Tous</option>
                                            <option v-for="s of emails_arr" :value="s.val">@{{ s.desc }}
                                            </option>
                                        </select>
                                    </div>
                                </div>




                            </div>



                        </fieldset>

                        <datatable-component :datatable="datatable">

                            <template #message="props">
                                @{{ props.column.value }}
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
                                <button class="btn p-0 m-0 me-2" @click="confirm(props.row)">
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" @click="cancel(props.row)">
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
        let email_app = Vue.createApp({
            data() {
                return {
                    search: '',
                    emails_arr: emails_arr,
                    filter: {
                        status: null,
                    },
                    datatable: {
                        key: "email",
                        api: "/api/v2/emails/filter",
                        columns: [{
                            name: "#",
                            field: "id",
                            type: "number",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "Annonce",
                            field: "ad_id",
                            type: "number",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "Utilisateur",
                            field: "username",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "Nom",
                            field: "name",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "Email",
                            field: "email",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "Téléphone",
                            field: "phone",
                            type: "string",
                            sortable: true,
                            searchable: false
                        }, {
                            name: "Message",
                            field: "message",
                            type: "string",
                            sortable: true,
                            searchable: true,
                            customize: true
                        }, {
                            name: "Date",
                            field: "date",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: 'Status',
                            field: 'status',
                            type: 'custom',
                            sortable: true,
                            searchable: true,
                            customize: true,
                        }, {
                            name: "Action",
                            field: "action",
                            type: "action",
                            sortable: false,
                            searchable: false,
                            customize: true
                        }],
                        rows: [],
                        filters: [],
                        sort: {
                            column: "",
                            order: ""
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 30,
                            total: 0,
                            links: []
                        },
                        loadingrows: [],
                        show_controls: {
                            settings: true,
                            export_xlsx: true,
                            export_json: true,
                            search_input: true,
                            pagination_selection: true,
                            pagination_buttons: true,
                            filters: true
                        },
                        input_filters: [],
                        search: ""
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
            watch: {
                filter: {
                    handler(value) {

                      return value;

                    },
                    deep: true
                }
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
                },
                loadFilter() {
                    this.datatable.filters = [];
                    if (this.filter.status){

                         this.datatable.filters.unshift({
                            type: 'where',
                            col: 'emails.status',
                            val: `${this.filter.status}`,
                            op: '='
                         })

                         this.datatable.filters = this.datatable.filters.splice(0,1);
                    }

                    else {

                          return this.datatable.filters;
                    }



                },
                confirm(row) {
                    // post to /api/v2/email/confirm/{id}

                    this.datatable.loadingrows.push(row.key);

                    axios.post('/api/v2/email/confirm/' + row.value.id)
                        .then(response => {
                            console.log(response);
                            if (response.data.status == "success") {
                                row.value.status = 1;

                                // remove all loaded rows from datatable loadingrows array
                                this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                    loaded_row => loaded_row != row.key
                                );

                                swal("Email confirmé avec succès", "", "success");

                            }
                        })
                        .catch(error => {
                            console.log(error);

                            // remove all loaded rows from datatable loadingrows array
                            this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                loaded_row => loaded_row != row.key
                            );
                            if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                            else swal( error.response.data.error, "", "error");
                        });

                },
                cancel(row) {
                    // post to : /api/v2/email/cancel/{id}
                    this.datatable.loadingrows.push(row.key);

                    axios.post('/api/v2/email/cancel/' + row.value.id)
                        .then(response => {
                            console.log(response);
                            if (response.data.status == "success") {
                                row.value.status = 2;

                                // remove all loaded rows from datatable loadingrows array
                                this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                    loaded_row => loaded_row != row.key
                                );
                            }
                        })
                        .catch(error => {
                            console.log(error);

                            // remove all loaded rows from datatable loadingrows array
                            this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                loaded_row => loaded_row != row.key
                            );
                            if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                            else swal( error.response.data.error, "", "error");
                        });
                }
            },
        }).mount('#app');
    </script>

@endsection
