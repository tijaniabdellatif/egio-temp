@extends('v2.dashboard')

@section('title1',__("general.Mes emails"))

@section('custom_head1')

    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- formatter/beautify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.4/beautify.min.js"></script>

    <!-- highlight js ( to highlight text, used in datatables ) -->
    <link rel="stylesheet" href="{{ asset('css/highlight/a11y-dark.min.css') }}" />
    <script src="{{ asset('js/highlight/highlight.min.js') }}"></script>
    <script src="{{ asset('js/highlight/languages/javascript.min.js') }}"></script>
    <script src="{{ asset('js/highlight/languages/json.min.js') }}"></script>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="/assets/vendor/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>

@endsection

@section('content1')


    <div class="d-none" id="myemails-app" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{__("general.Mes emails")}} </h5>
                        <fieldset class="filter_cnt">

                            <legend>{{__("general.Filtrer")}}</legend>

                            <div class="row">

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">{{__("general.Date")}}</label>
                                    <div class="col-sm-8">
                                        <div class="inner-addon right-addon" style="position: relative;">
                                            <i class="fa fa-calendar glyphicon"
                                                style="position: absolute;right: 0px;padding:6px;"></i>
                                            <input type="text" id="datepicker"
                                                style="padding: 5px;font-size: 12px;padding-right: 25px;"
                                                class="form-control" />
                                        </div>
                                    </div>
                                </div>

                            </div>



                        </fieldset>
                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class=" col-sm-2">
                                <select v-model="datatable.pagination.per_page"
                                    style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;"
                                    class="form-select" aria-label="Default select example">
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="250">250</option>
                                    <option :value="500">250</option>
                                </select>
                            </div>
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label"
                                    style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="{{__('general.Rechercher')}}"
                                        @keyup.enter="loadFilter()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component :datatable="datatable">

                            <template #message="props">
                                @{{ props.column.value.length > 100 ? (props.column.value.substring(0, 100) + "...") : props.column.value }}
                            </template>

                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="seeDetails(props.row.value)">
                                    <i class="fas fa-eye text-primary"></i>
                                </button>
                            </template>

                        </datatable-component>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Modal -->
        <div class="modal fade modal-close" @click.self="hideDetails" id="messageModal" data-id="messageModal">
            <div class="modal-dialog modal-lg">
                <form onsubmit="event.preventDefault()" id="messageModal" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" @click="hideDetails" class="btn-close modal-close"
                            data-id="messageModal"></button>
                    </div>
                    <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                        <table class="messageTable">
                            <tr>
                                <td>{{__('general.Annonce')}}</td>
                                <td><a :href="'/item/' + modalData.ad_id" target="_blank">@{{ modalData.annonce }} <i
                                            class="fas fa-external-link"></i></a></td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td>@{{ modalData.name }}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>@{{ modalData.email }}</td>
                            </tr>
                            <tr>
                                <td>Phone:</td>
                                <td>@{{ modalData.phone }}</td>
                            </tr>
                            <tr>
                                <td>Message:</td>
                                <td style=" text-align: justify;">@{{ modalData.message }}</td>
                            </tr>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="hideDetails" class="btn btn-secondary modal-close"
                            data-id="messageModal">{{__("general.close")}}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End message Modal-->

    </div>


    <script>
        var lastScrollTop = 0;
        let myemailsApp = createApp({
            data() {
                return {
                    loader: false,
                    search: "",
                    datatable: {
                        key: "myemails",
                        api: "/api/v2/myemails/filter",
                        columns: [{
                            name:` {{__('general.Annonce')}}`,
                            field: "annonce",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: `{{__('general.Nom')}}`,
                            field: "name",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: `{{__('general.Email')}}`,
                            field: "email",
                            type: "string",
                            sortable: true,
                            searchable: true,
                            customize: true
                        }, {
                            name: `{{__('general.TÉLÉPHONE')}}`,
                            field: "phone",
                            type: "string",
                            sortable: true,
                            searchable: false
                        }, {
                            name: `{{__('general.Message')}}`,
                            field: "message",
                            type: "string",
                            sortable: true,
                            searchable: true,
                            customize: true
                        }, {
                            name: `{{__('general.Date')}}`,
                            field: "date",
                            type: "string",
                            sortable: true,
                            searchable: true
                        }, {
                            name: "",
                            field: "action",
                            type: "action",
                            sortable: false,
                            searchable: false,
                            customize: true
                        }],
                        rows: [],
                        filters: [{
                                type: 'where',
                                col: 'emails.status',
                                val: `1`,
                                op: '='
                            },
                            {
                                type: 'where',
                                col: 'ads.id_user',
                                val: '{{ Auth()->user()->id }}',
                                op: '='
                            },
                        ],
                        sort: {
                            column: 'emails.id',
                            order: 'DESC'
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 20,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            settings: false,
                            export_xlsx: false,
                            export_json: false,
                            pagination_buttons: true
                        }
                    },
                    filter: {
                        startDate: null,
                        endDate: null,
                    },
                    modalData: {}
                }
            },
            computed: {},
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
                                !this.filter.endDate)) this.loadFilter();
                    }
                });
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            methods: {
                seeDetails(val) {
                    this.modalData = val;
                    console.log(this.modalData)
                    showModal('messageModal');
                },
                hideDetails() {
                    hideModal('messageModal');
                },
                loadFilter() {
                    this.datatable.filters = [{
                            type: 'where',
                            col: 'ads.id_user',
                            val: '{{ Auth()->user()->id }}',
                            op: '='
                        }, {
                            type: 'where',
                            col: 'emails.status',
                            val: `1`,
                            op: '='
                        },
                        {
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'ads.id',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'ads.title',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'emails.name',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'emails.message',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'emails.phone',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'emails.email',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                }
                            ]
                        },
                    ];
                    if (this.filter.startDate && this.filter.endDate)
                        this.datatable.filters.push({
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'emails.date',
                                    val: `${this.filter.startDate}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'emails.date',
                                    val: `${this.filter.endDate}`,
                                    op: '<'
                                },
                            ]
                        });
                }
            }
        }).mount('#myemails-app');
        document.querySelector('#myemails-app').classList.remove("d-none");
    </script>
@endsection


@section('custom_foot1')

@endsection
