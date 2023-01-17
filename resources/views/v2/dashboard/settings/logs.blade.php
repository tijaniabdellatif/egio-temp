@extends('v2.layouts.dashboard')

@section('title', 'Logs')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Logs : </h1>
    </div>
    <section id="app">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body needs-validation">

                        <fieldset class="filter_cnt">

                            <legend>Filtrer:</legend>

                            <div class="row">

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Subject :</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter()" v-model="filter.subject" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">Tous</option>
                                            <option v-for="s of subject_arr" :value="s.val">@{{ s.val }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Date :</label>
                                    <div class="col-sm-8">
                                        <div class="inner-addon right-addon">
                                            <i class="fa fa-calendar glyphicon"></i>
                                            <input type="text" id="datepicker" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </fieldset>

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
                                    <input class="form-control w-100" v-model="search" placeholder="Search"
                                        @keyup.enter="loadFilter()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component  ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                            @selectedchanged="selectedChanged">
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="edit(props.row.value)">
                                    <i class="fas fa-edit text-success"></i>
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

        let app = Vue.createApp({
            data() {

                return {
                    log_activities:[],
                    search: '',
                    subject_arr: subject_arr,
                    parents : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        subject:null,
                        startDate: null,
                        endDate: null,
                    },
                    datatable: {
                        key: 'logs_datatable',
                        api: '/api/v2/logs/filter',
                        columns: [

                            {
                                name: 'Utilisateur',
                                field: 'username',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Subject',
                                field: 'subject',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Subject id',
                                field: 'subject_id',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'IP',
                                field: 'ip',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Region',
                                field: 'region',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Date',
                                field: 'created_at',
                                type: 'date',
                                sortable: true,
                                searchable: false,
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
                            export_xlsx: true,
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                }
            },
            watch: {
                filter: {
                    handler(value) {

                        return value;
                    },
                    deep: true
                }
            },
            computed:{

                filterSubject(){

                      console.log('filters');
                }

            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {




                new Lightpick({
                    field: document.getElementById('datepicker'),
                    singleDate: false,
                    numberOfMonths: 2,
                    footer: true,
                    onSelect: (start, end) => {
                        this.filter.startDate = start ? start.format("YYYY-MM-DD") : null;
                        this.filter.endDate = end ? end.format("YYYY-MM-DD") : null;
                        if ((this.filter.startDate && this.filter.endDate) || (!this.filter.startDate &&
                                !this.filter.endDate)) this.loadFilter()
                    }
                });
            },


            methods: {
              loadFilter(){
                  const filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'log_activities.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'log_activities.subject',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'log_activities.subject_id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];

                if(this.filter.subject){
                    filters.push({
                        type: 'where',
                        col: 'log_activities.subject',
                        val: `${this.filter.subject}`,
                        op: '='
                    });
                }

                if (this.filter.startDate && this.filter.endDate){
                    filters.push({
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'log_activities.created_at',
                                val: `${this.filter.startDate}`,
                                op: '>'
                            },
                            {
                                type: 'where',
                                col: 'log_activities.created_at',
                                val: `${this.filter.endDate}`,
                                op: '<'
                            },
                        ]
                    });
                }


                this.datatable.filters = filters;


                },
                }
        }).mount('#app')
    </script>

@endsection
