@extends('v2.dashboard')
@section('title1', __("general.Mon tableau de bord"))

@section('custom_head1')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="{{ asset('js/components/slider.vue.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <style>
        .card {
            margin-bottom: 30px;
            border: none;
            border-radius: 5px;
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1);
        }

        .card-header,
        .card-footer {
            border-color: #ebeef4;
            background-color: #fff;
            color: #798eb3;
            padding: 15px;
        }

        .card-title {
            padding: 20px 0 15px 0;
            font-size: 18px;
            font-weight: 500;
            color: #012970;
            font-family: "Poppins", sans-serif;
        }

        .card-title span {
            color: #899bbd;
            font-size: 14px;
            font-weight: 400;
        }

        .card-body {
            padding: 0 20px 20px 20px;
        }

        .card-img-overlay {
            background-color: rgba(255, 255, 255, 0.6);
        }

        #dashboard-app .info-card {
            padding-bottom: 10px;
        }

        #dashboard-app .info-card h6 {
            font-size: 28px;
            color: #012970;
            font-weight: 700;
            margin: 0;
            padding: 0;
        }

        #dashboard-app .card-icon {
            font-size: 32px;
            line-height: 0;
            width: 64px;
            height: 64px;
            flex-shrink: 0;
            flex-grow: 0;
        }

        #dashboard-app .sales-card .card-icon {
            color: #4154f1;
            background: #f6f6fe;
        }

        #dashboard-app .revenue-card .card-icon {
            color: #2eca6a;
            background: #e0f8e9;
        }

        #dashboard-app .customers-card .card-icon {
            color: #ff771d;
            background: #ffecdf;
        }

        #dashboard-app .filter {
            position: absolute;
            right: 0px;
            top: 15px;
        }

        #dashboard-app .filter .icon {
            color: #aab7cf;
            padding-right: 20px;
            padding-bottom: 5px;
            transition: 0.3s;
            font-size: 16px;
        }

        #dashboard-app .filter .icon:hover,
        #dashboard-app .filter .icon:focus {
            color: #4154f1;
        }

        #dashboard-app .filter .dropdown-header {
            padding: 8px 15px;
        }

        #dashboard-app .filter .dropdown-header h6 {
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            color: #aab7cf;
            margin-bottom: 0;
            padding: 0;
        }

        #dashboard-app .filter .dropdown-item {
            padding: 8px 15px;
        }
    </style>

@endsection

@section('content1')


    <div id="dashboard-app" @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        <div class="row d-none" id="info-app" data-v-app="">
            @if (Auth()->user()->usertype != 3)
                <!-- total ads Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title mx-3">{{__('general.LISTCOIN SOLDE')}}</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="multilist-icons multilist-listcoin"></i>
                                </div>
                                <div class="ps-3">
                                    <h6 class="mx-2">@{{ balance }} <span class="mx-2">{{__('general.LTC')}}</span></h6>
                                    <!--v-if-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End total ads Card -->
            @endif
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.Annonces')}}</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ totalAds }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.Annonces Publiées')}}</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ publishedAds }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card customers-card">
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.Annonces en revue')}}</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ inReviewAds }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.Annonces refusées')}}</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                style="color: #ff2300;background:#ffe2df;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ rejectedAds }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.Annonces en brouillon')}}</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"
                                style="color: #797979;background:#e1e1e3;">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ draftAds }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
        </div>

        <hr>

        <div class="section-heading">
            <h1>{{__('general.Statistiques')}}</h1>
            <div class="heading-underline"></div>
        </div>

        <div class="row d-none" id="states-app" data-v-app="">
            <!-- total ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6 class="mx-2">{{__('general.Filter')}}</h6>
                            </li>
                            <li v-for="p of periodeFilters"><a @click="changeViews(p)" class="dropdown-item"
                                    href="#">@{{ p.name }}</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.VUES')}} <span>| @{{ ViewsPeriode.name }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="fas fa-eye"></i></div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ views }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total ads Card -->
            <!-- total published ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="filter"><a class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="fas fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6 class="mx-2">Filter</h6>
                            </li>
                            <li v-for="p of periodeFilters"><a @click="changePhones(p)" class="dropdown-item"
                                    href="#">@{{ p.name }}</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.TÉLÉPHONE')}} <span>| @{{ PhonesPeriode.name }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="fas fa-phone"></i></div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ phones }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total published ads Card -->
            <!-- total in review ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card customers-card">
                    <div class="filter"><a class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="fas fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6 class="mx-2">{{__('general.Filter')}}</h6>
                            </li>
                            <li v-for="p of periodeFilters"><a @click="changeEmails(p)" class="dropdown-item"
                                    href="#">@{{ p.name }}</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.EMAILS')}} <span>| @{{ EmailsPeriode.name }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="fas fa-envelope"></i></div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ emails }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total in review ads Card -->
            <!-- total in review ads Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="filter"><a class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="fas fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6 class="mx-2">{{__('general.Filter')}}</h6>
                            </li>
                            <li v-for="p of periodeFilters"><a @click="changeWtsps(p)" class="dropdown-item"
                                    href="#">@{{ p.name }}</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title mx-3">{{__('general.WHATSAPP')}} <span>| @{{ WtspsPeriode.name }}</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center"><i
                                    class="fa-brands fa-whatsapp"></i></div>
                            <div class="ps-3">
                                <h6 class="mx-2">@{{ wtsps }}</h6>
                                <!--v-if-->
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End total in review ads Card -->
        </div>

        <hr>
<!--
        <div class="section-heading">
            <h1>Derniers Emails reçus</h1>
            <div class="heading-underline"></div>
        </div>

        <div class="row datatable d-none" id="emails-app">
            <a href="/myemails"
                style="text-decoration: underline;color:#798eb3;text-align: right;margin-bottom: 5px;">Voir tous...</a>
            <div class="dataTable-container table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Email</th>
                            <th scope="col">Annonce</th>
                            <th scope="col">message</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-loader" :class="loading ? '' : 'd-none'">
                            <td colspan="99">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="empty-table" :class="emails.length == 0 && !loading ? '' : 'd-none'">
                            <td colspan="99">
                                <div class="message d-flex justify-content-center">
                                    There is no data to display
                                </div>
                            </td>
                        </tr>
                        <tr v-for="(e,key) in emails" :class="!loading ? '' : 'd-none'">
                            <td>@{{ e.name }}</td>
                            <td>@{{ e.phone }}</td>
                            <td>@{{ e.email }}</td>
                            <td><a style="text-decoration: underline;color:#798eb3;" :href="'/item/' + e.ad_id"
                                    target="_blank">@{{ e.title }}</td>
                            <td>@{{ e.message }}</td>
                            <td>@{{ formatDateTime(e.date) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>-->

        <div class="section-heading d-none">
            <h1>{{__('general.Annonces les plus visitées')}}</h1>
            <div class="heading-underline"></div>
        </div>

        <div class="row d-none" id="myads-app">
            <div class="multiads_cnt" style="margin-top: 0;">

                <div v-for="a of ads" class="multiads" @click="toItemPage($event,a)">
                    <div class="img media">
                        <Slider :images="a.images"></Slider>
                    </div>
                    <div class="content">
                        <div class="multiads-section">
                            <div class="status_box" :class="'s_' + a.status">@{{ displayStatus(a.status) }}</div>
                            <div class="title">
                                <a href="#">
                                    <h1> @{{ a.title }}</h1>
                                </a>
                            </div>
                            <div style="font-size: 14px;">
                                <span>@{{ a.categorie }}</span>
                            </div>
                            <div class="location" style="color: gray;">
                                <span>
                                    <i class="multilist-icons multilist-location"></i>
                                    <span> @{{ localisation(a) }} </span>
                                </span>
                            </div>
                            <div class="price">
                                <span> @{{ price(a) }} </span>
                            </div>
                        </div>

                        <div>
                            <div class="multiads-cards-cnt">
                                <div class="multiads-card" title="vues"> <i class="fas fa-eye"></i>
                                    @{{ a.views }} </div> <!-- views -->
                                <div class="multiads-card" title="whatsapp"> <i class="fa-brands fa-whatsapp"></i>
                                    @{{ a.wtsps }} </div> <!-- whatsapp -->
                                <div class="multiads-card" title="téléphone"> <i class="fas fa-phone"></i>
                                    @{{ a.phones }} </div> <!-- phones -->
                                <div class="multiads-card" title="emails"> <i class="fas fa-envelope"></i>
                                    @{{ a.emails }} </div> <!-- emails -->
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div v-if="ads.length == 0&&!loader" class="text-muted" style="text-align: center;">
                <span>{{__('general.Aucun résultat trouvé')}}</span>
            </div>

            <div v-if="loader" class="loader"></div>
        </div>

    </div>


    <script>
        let infoApp = Vue.createApp({
            data() {
                return {
                    loading: false,
                    balance: 0,
                    usertype: null,
                    totalAds: 0,
                    publishedAds: 0,
                    draftAds: 0,
                    inReviewAds: 0,
                    rejectedAds: 0,
                    contract: null,
                }
            },
            mounted() {

                this.setGoogleAuth();

                this.loadData();

            },
            methods: {
                moment(date){
                    return moment(date);
                },
                loadData() {
                    axios.get(`/api/v2/dashboardInfos?id={{ Auth::user()->id }}`)
                        .then((response) => {
                            this.loader = false;
                            this.contract = response.data.data.contracts;
                            this.balance = response.data.data.balance;
                            this.usertype = response.data.data.usertype;
                            this.totalAds = response.data.data.totalAds;
                            this.publishedAds = response.data.data.publishedAds;
                            this.draftAds = response.data.data.draftAds;
                            this.inReviewAds = response.data.data.inReviewAds;
                            this.rejectedAds = response.data.data.rejectedAds;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                setGoogleAuth() {


                    let isGoogle = '<?php echo session('isGoogle'); ?>';

                    if (isGoogle) {
                        let token = '<?php echo session('token'); ?>'
                        let auth = '<?php echo session('auth'); ?>'
                        localStorage.setItem('token', token);
                        localStorage.setItem('auth', auth);


                    }




                }
            }
        }).mount('#info-app');
        document.querySelector('#info-app').classList.remove("d-none");

        let statesApp = Vue.createApp({
            data() {
                return {
                    loading: false,
                    views: 0,
                    phones: 0,
                    wtsps: 0,
                    emails: 0,
                    periodeFilters: [{
                            name: `<?php echo __('general.tous') ?>`,
                            days: null
                        },
                        {
                            name: `<?php echo __("general.Aujourd'hui") ?>`,
                            days: 1
                        },
                        {
                            name: `<?php echo __('general.Cette semaine') ?>`,
                            days: 7
                        },
                        {
                            name: `<?php echo __('general.Ce mois') ?>`,
                            days: 30
                        },
                    ],
                    ViewsPeriode: {
                        name: `<?php echo __('general.tous') ?>`,
                        days: null
                    },
                    WtspsPeriode: {
                        name: `<?php echo __('general.tous') ?>`,
                        days: null
                    },
                    PhonesPeriode: {
                        name: `<?php echo __('general.tous') ?>`,
                        days: null
                    },
                    EmailsPeriode: {
                        name: `<?php echo __('general.tous') ?>`,
                        days: null
                    },
                }
            },
            mounted() {
                this.loadData();
            },
            methods: {
                loadData() {
                    axios.get(`/api/v2/dashboardStatistics?id={{ Auth::user()->id }}`)
                        .then((response) => {
                            this.loader = false;
                            this.views = response.data.data.views;
                            this.phones = response.data.data.phones;
                            this.wtsps = response.data.data.wtsps;
                            this.emails = response.data.data.emails;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                loadViews(days) {
                    this.views = 0;
                    axios.post(`/api/v2/dashboardViews?id={{ Auth::user()->id }}`, {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.views = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadPhones(days) {
                    this.phones = 0;
                    axios.post(`/api/v2/dashboardPhones?id={{ Auth::user()->id }}`, {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.phones = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadWtsps(days) {
                    this.wtsps = 0;
                    axios.post(`/api/v2/dashboardWtsps?id={{ Auth::user()->id }}`, {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.wtsps = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadEmails(days) {
                    this.emails = 0;
                    axios.post(`/api/v2/dashboardEmails?id={{ Auth::user()->id }}`, {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.emails = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                changeViews(obj) {
                    this.ViewsPeriode = obj;
                    this.loadViews(this.ViewsPeriode.days);
                },
                changePhones(obj) {
                    this.PhonesPeriode = obj;
                    this.loadPhones(this.PhonesPeriode.days);
                },
                changeWtsps(obj) {
                    this.WtspsPeriode = obj;
                    this.loadWtsps(this.WtspsPeriode.days);
                },
                changeEmails(obj) {
                    this.EmailsPeriode = obj;
                    this.loadEmails(this.EmailsPeriode.days);
                },
            }
        }).mount('#states-app');
        document.querySelector('#states-app').classList.remove("d-none");

        let EmailsApp = Vue.createApp({
            data() {
                return {
                    emails: [],
                    loading: false,
                }
            },
            mounted() {
                //loadData
                this.loadData();
            },
            methods: {
                loadData() {
                    this.loading = true;
                    axios.get(`/api/v2/dashboardLatestEmails?id={{ Auth::user()->id }}`)
                        .then((response) => {
                            this.loading = false;
                            this.emails = response.data.data;
                        })
                        .catch(error => {
                            this.loading = false;
                            console.log(error);
                        });
                },
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
            }
        }).mount('#emails-app');
        document.querySelector('#emails-app').classList.remove("d-none");

        let myadsApp = createApp({
            data() {
                return {
                    status_arr: status_arr,
                    loader: false,
                    ads: [],
                    filters: {
                        from: 0,
                        count: 4,
                        orderBy: 'vues',
                    }
                }
            },
            computed: {},
            mounted() {
                //loadData
                this.loadData();
            },
            components: {
                'Slider': Slider
            },
            methods: {
                loadData() {
                    this.loader = true;
                    axios.get(`/api/v2/myAds?id={{ Auth::user()->id }}`, {
                            params: this.filters
                        })
                        .then((response) => {
                            this.loader = false;
                            this.ads = response.data.data.data;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
                localisation(data) {
                    let r = '';
                    if (data.neighborhood) {
                        r = data.neighborhood + ', ' + data.city;
                    } else {
                        if (data.locdept2) {
                            r = data.locdept2 + ', ' + data.city;
                        } else r = data.city;
                    }
                    return r;
                },
                price(data) {
                    let r = '';

                    if (data.price) {
                        data.price = data.price.toLocaleString(undefined, {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        if (data.price <= 0) r = 'Prix à consulter';
                        else {
                            if (data.price_curr) {
                                r = data.price + ' ' + data.price_curr;
                            } else r = data.price;
                        }
                    } else r = 'Prix à consulter';
                    return r;
                },
                toItemPage(e, l) {
                    if (e.target.classList.contains('btn') || e.target.parentElement.classList.contains('btn')) {
                        return;
                    }
                    //window.location.href = '/item/'+l.id+'/'+l.title;
                    window.open((multilistType!="multilist"?'/'+multilistType:'') + '/item/' + l.id + '/' + l.title, '_blank');
                },
                displayStatus(val) {
                    for (const s of status_arr) {
                        if (s.val == val) return s.desc;
                    }
                    return '';
                },
            }
        }).mount('#myads-app');
        document.querySelector('#myads-app').classList.remove("d-none");
    </script>
@endsection


@section('custom_foot1')

@endsection



<script>

    window.addEventListener('DOMContentLoaded', (e) => {

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })


                const body = document.querySelector('body')
                const selectedLocal = '<?= session('lang') ?>'

                console.log('langue is:',selectedLocal)
                if(selectedLocal == 'ar'){

                    body.style.fontFamily='Noto Kufi Arabic,Montserrat'

                }


                    function checkIsFacebookAuth() {
                        let isFacebookAuth = getCookie("isFacebookAuth");
                        if (isFacebookAuth == true ) {
                            Toast.fire({
                                icon: 'info',
                                title: `{{__('general.connected with facebook')}}`
                            })
                            setCookie("isFacebookAuth", false, 1);
                        }
                    }
                    checkIsFacebookAuth()


                    function checkNewUserFromFacebook() {
                        let NewUserFromFacebook = getCookie("NewUserFromFacebook");
                        if (NewUserFromFacebook == true ) {
                            Swal.fire({
                            title: `<strong>{{__("general.signedup with facebook")}}</strong>`,
                            icon: 'success',
                            html:
                            '<div class="bd-callout bd-callout-info">'+
                                `<p>{{__("general.facebooksignedupAlert")}}</p>`+
                            '</div>'

                            ,
                            showCloseButton: true,
                            // showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText:
                                '<i class="fa fa-thumbs-up mx-2"></i> {{__("general.dacc")}}',
                            confirmButtonAriaLabel: 'Thumbs up, great!',
                            })
                            setCookie("NewUserFromFacebook", false, 1);
                        }
                    }
                    checkNewUserFromFacebook()


                    function checkIsGoogleAuth() {
                        let isGoogleAuth = getCookie("isGoogleAuth");
                        if (isGoogleAuth == true ) {
                            Toast.fire({
                                icon: 'info',
                                title: `{{__('general.connected with google')}}`
                            })
                            setCookie("isGoogleAuth", false, 1);
                        }
                    }
                    checkIsGoogleAuth()


                    function checkNewUserFromGoogle() {
                        let NewUserFromGoogle = getCookie("NewUserFromGoogle");
                        if (NewUserFromGoogle == true ) {
                            Swal.fire({
                            title: `<strong>{{__("general.signedup with google")}}</strong>`,
                            icon: 'success',
                            html:
                            '<div class="bd-callout bd-callout-info">'+
                                `<p>{{__("general.googlesignedupAlert")}}</p>`+
                            '</div>'

                            ,
                            showCloseButton: true,
                            // showCancelButton: true,
                            focusConfirm: false,
                            confirmButtonText:
                                '<i class="fa fa-thumbs-up mx-2"></i> {{__("general.dacc")}}',
                            confirmButtonAriaLabel: 'Thumbs up, great!',
                            })
                            setCookie("NewUserFromGoogle", false, 1);
                        }
                    }
                    checkNewUserFromGoogle()

                    history.replaceState(null, null, ' ');
    })

</script>
