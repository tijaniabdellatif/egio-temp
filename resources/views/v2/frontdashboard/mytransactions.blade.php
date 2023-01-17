@extends('v2.dashboard')

@section('title1', __("general.Mes Opérations"))

@section('custom_head1')
    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <!-- sweetalert -->
    <script src="/assets/vendor/sweetalert.min.js"></script>

    <!-- excel exporter -->
    <script type="text/javascript" src="{{ asset('js/excellentexport.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content1')

    <div class="card" id="transactions"  @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        <div class="card-body">
            <h5 class="card-title">{{__('general.Mes transactions')}}</h5>
            <datatable-component :ref="datatable.key" :datatable="datatable">
                <template #amount="props">
                    <span :style="'color:' + (props.column.value > 0 ? 'green' : 'red')">
                        @{{ props.column.value }}
                    </span>
                </template>
            </datatable-component>
        </div>
    </div>

    <script>
        let appUserCoins = Vue.createApp({
            data() {
                return {
                    userId: -1,
                    display: false,
                    datatable: {
                        key: 'user_transaction_datatable',
                        api: '/api/v2/transactions/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true,
                                hide: true
                            },
                            {
                                name: `{{__('general.User id')}}`,
                                field: 'user_id',
                                type: 'number',
                                hide: true
                            },
                            {
                                name: `{{__('general.Date')}}`,
                                field: 'datetime',
                                type: 'datetime',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: `{{__('general.Type')}}`,
                                field: 'type',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: true
                            },
                            {
                                name: `{{__('general.Amount')}}`,
                                field: 'amount',
                                type: 'number',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: `{{__('general.Notes')}}`,
                                field: 'notes',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            // {
                            //     name: 'Action',
                            //     field: 'action',
                            //     type: 'action',
                            //     sortable: false,
                            //     searchable: false,
                            //     customize: true
                            // }
                        ],
                        rows: [],
                        filters: [{
                            type: 'where',
                            col: 'user_id',
                            val: `-1`,
                            op: '='
                        }],
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
                        },
                        show_controls: {
                            settings: false,
                            export_xlsx: false,
                            export_json: false,
                            search_input: false,
                            pagination_selection: true,
                            pagination_buttons: true,
                            filters: false
                        }
                    },
                    loading: false,
                    coins: {
                        new: 0,
                        current: '-'
                    },
                    errors: {}
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            filter: {
                momentDate: function(value) {
                    return moment(value).format('YYYY-MM-DD');
                }
            },
            mounted() {
                this.loadData();
            },
            methods: {
                loadData() {
                    this.lodaUserTransactions();
                },
                lodaUserTransactions() {
                    this.datatable.filters = [{
                        type: 'where',
                        col: 'user_id',
                        val: '{{ Auth::user()->id }}',
                        op: '='
                    }];
                }
            },
        }).mount('#transactions');
    </script>

@endsection

@section('custom_foot1')

@endsection
