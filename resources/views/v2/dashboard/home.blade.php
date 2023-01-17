@extends('v2.layouts.dashboard')

@section('title', 'Administration')

@section('custom_head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
    <script src="{{ asset('js/components/activity-components.vue.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>

    <style>
        .actElem::before {
            content: "";
            position: absolute;
            width: 5px;
            height: 100%;
            background: #aaa;
            transform: translate(106px, -4px);
            z-index: 1;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle">
        <h1>Tableau de bord</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">

        @if  (auth()->user()->hasRole(['moderator']))
        <div class="row">


            <div class="col-lg-12">
                <div class="row">


                    <div class="row" id="states-app">

                        <!-- total ads Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li v-for="p of periodeFilters"><a @click="changeTotalAds(p)"

                                            class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Annonces<br/> total <span>| @{{  AdsPeriode.name  }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div

                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>@{{  totalAds  }}</h6>
                                            <div v-if="totalAds==null" class="gradient element1"></div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div><!-- End total ads Card -->



                        <!-- total published ads Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li v-for="p of periodeFilters"><a @click="changeTotalPubAds(p)"

                                            class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Annonces <br/>publiées <span>| @{{  PubAdsPeriode.name  }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div

                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>@{{  totalPubAds  }}</h6>
                                            <div v-if="totalPubAds==null" class="gradient element1"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End total published ads Card -->

                        <!-- total in review ads Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card customers-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i

                                        class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li v-for="p of periodeFilters"><a @click="changeTotalRevAds(p)"

                                            class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Annonces<br/> en revue <span>| @{{  RevAdsPeriode.name  }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div

                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>@{{  totalRevAds  }}</h6>
                                            <div v-if="totalRevAds==null" class="gradient element1"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li v-for="u of universFilters"><a @click="changeAdsUnivers(u)"

                                            class="dropdown-item" href="#">@{{  u.name  }}</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Annonces par univers <span>| @{{  AdsByUnivers.name  }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div

                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-tag"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>@{{  totalAdsByUnivers }}</h6>
                                            <div v-if="totalAdsByUnivers==null" class="gradient element1"></div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div><!-- End total ads Card -->


                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li v-for="u of usersFilters"><a @click="changeAdsUsers(u)"

                                            class="dropdown-item" href="#">@{{  u.name  }}</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Annonces par utilisateur <span>| @{{  AdsByUser.name  }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div

                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>@{{  totalAdsByUser }}</h6>
                                            <div v-if="totalAdsByUser==null" class="gradient element1"></div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class="row" id="adsGraph-app">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nombre d'annonces publieés par mois</h5>
                                    <canvas id="AdsByMonths"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>
                    @endif

        @if  (auth()->user()->hasRole(['moderator manager','admin','ced manager','ced']))
                <div class="row">


                    <div class="col-lg-8">
                        <div class="row">


                            <div class="row" id="states-app">

                                <!-- total ads Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card total-card">

                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li class="dropdown-header text-start">
                                                    <h6>Filter</h6>
                                                </li>

                                                <li v-for="p of periodeFilters"><a @click="changeTotalAds(p)"

                                                    class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">Annonces<br/> total <span>| @{{  AdsPeriode.name  }}</span></h5>

                                            <div class="d-flex align-items-center">
                                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-megaphone"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>@{{  totalAds  }}</h6>
                                                    <div v-if="totalAds==null" class="gradient element1"></div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div><!-- End total ads Card -->



                                <!-- total published ads Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card revenue-card">

                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li class="dropdown-header text-start">
                                                    <h6>Filter</h6>
                                                </li>

                                                <li v-for="p of periodeFilters"><a @click="changeTotalPubAds(p)"

                                                    class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">Annonces <br/>publiées <span>| @{{  PubAdsPeriode.name  }}</span></h5>

                                            <div class="d-flex align-items-center">
                                                <div

                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-megaphone"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>@{{  totalPubAds  }}</h6>
                                                    <div v-if="totalPubAds==null" class="gradient element1"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div><!-- End total published ads Card -->

                                <!-- total in review ads Card -->
                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card customers-card">

                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i

                                                class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li class="dropdown-header text-start">
                                                    <h6>Filter</h6>
                                                </li>

                                                <li v-for="p of periodeFilters"><a @click="changeTotalRevAds(p)"

                                                    class="dropdown-item" href="#">@{{  p.name  }}</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">Annonces<br/> en revue <span>| @{{  RevAdsPeriode.name  }}</span></h5>

                                            <div class="d-flex align-items-center">
                                                <div

                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-megaphone"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>@{{  totalRevAds  }}</h6>
                                                    <div v-if="totalRevAds==null" class="gradient element1"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div><!-- End total in review ads Card -->

                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card sales-card">

                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li class="dropdown-header text-start">
                                                    <h6>Filter</h6>
                                                </li>

                                                <li v-for="u of universFilters"><a @click="changeAdsUnivers(u)"

                                                    class="dropdown-item" href="#">@{{  u.name  }}</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">Annonces par univers <span>| @{{  AdsByUnivers.name  }}</span></h5>

                                            <div class="d-flex align-items-center">
                                                <div

                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-tag"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>@{{  totalAdsByUnivers }}</h6>
                                                    <div v-if="totalAdsByUnivers==null" class="gradient element1"></div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div><!-- End total ads Card -->


                                <div class="col-xxl-4 col-md-6">
                                    <div class="card info-card sales-card">

                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <li class="dropdown-header text-start">
                                                    <h6>Filter</h6>
                                                </li>

                                                <li v-for="u of usersFilters"><a @click="changeAdsUsers(u)"

                                                    class="dropdown-item" href="#">@{{  u.name  }}</a></li>
                                            </ul>
                                        </div>

                                        <div class="card-body">
                                            <h5 class="card-title">Annonces par utilisateur <span>| @{{  AdsByUser.name  }}</span></h5>

                                            <div class="d-flex align-items-center">
                                                <div

                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-people"></i>
                                                </div>
                                                <div class="ps-3">
                                                    <h6>@{{  totalAdsByUser }}</h6>
                                                    <div v-if="totalAdsByUser==null" class="gradient element1"></div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                </div>

                            </div>

                        <div class="row" id="adsGraph-app">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Nombre d'annonces publieés par mois</h5>
                                    <canvas id="AdsByMonths"></canvas>
                                </div>
                            </div>
                        </div>


                        </div>
                    </div>

                    <!-- Right side columns -->
                    <div class="col-lg-4">
                        <!-- Recent Activity -->
                        <div class="card" id="recentActivities" style="overflow: hidden">
                            <div class="card-body">
                                <h5 class="card-title">Activité récente <span>| Les Derniers </span></h5>
                                <div class="actElem my-0" v-for="act in recentActivities">
                                    <div class="activity mb-3">
                                        <div class="activity-item d-flex">
                                            <div class="activite-label" style="width: 100px">
                                                @{{ act.date }}
                                            </div>
                                        <i v-if=" act.ActivityType == 'log' "

                                            class='bi bi-circle-fill activity-badge text-success'></i>
                                        <i v-if=" act.ActivityType == 'notif' "

                                            class='bi bi-circle-fill activity-badge text-info'></i>

                                        <div class="activity-content d-flex align-items-start text-capitalize"
                                            style="margin-left: 1rem;">
                                            <span class="p-1">
                                                <i class="fa-regular fa-flag"></i>
                                                @{{ act.method ? ` ${act.subject}  by ${act.user[0].username}` : act.data.body }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Recent Activity -->
                </div><!-- End Right side columns -->
        @endif

        @if  (auth()->user()->hasAnyPermission(['Show-clients-dashboard']))
                <div class="row" id="clients-app">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Clients</h5>

                            <fieldset class="filter_cnt">

                                <legend>Filter:</legend>

                                <div class="row">

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Etat</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.status" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">tous</option>
                                            <option v-for="s of status_arr" :value="s.val">@{{ s.desc }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Type d'utilisateur</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.usertype" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">tous</option>
                                            <option v-for="u of userTypes" :value="u.id">@{{ u.designation }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">CED</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.ced" class="form-select"
                                            :disabled="disabledCed" aria-label="Default select example">
                                            <option :value="null">tous</option>
                                            <option v-for="u of ceds" :value="u.id">@{{ u.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Commercial</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.commercial" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">tous</option>
                                            <option v-for="u of ceds" :value="u.id">@{{ u.username }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                    <div v-if="false" class="row col-sm-4">
                                        <label class="col-sm-4 col-form-label">Date</label>
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
                                <div class=" col-sm-2">
                                    <select v-model="datatable.pagination.per_page"

                                    style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;"

                                    class="form-select" aria-label="Default select example">
                                        <option :value="10">10</option>
                                        <option :value="20">20</option>
                                        <option :value="50">50</option>
                                        <option :value="100">100</option>
                                        <option :value="250">250</option>
                                        <option :value="500">500</option>
                                    </select>
                                </div>
                                <div class="row col-sm-4" style="margin-left: auto;">
                                    <label for="inputTitle" class="col-sm-2 col-form-label"

                                    style="text-align: end;padding: 3px 0;font-size: 13px;">
                                        <i class="bi bi-search"></i>
                                    </label>
                                    <div class="col-sm-10">
                                        <input class="form-control w-100" v-model="search" placeholder="Search"
                                            @keyup.enter="loadFilter()" type="text" name="">
                                    </div>
                                </div>
                            </div>

                        <datatable-component :datatable="datatable" @loaded="datatableLoaded">
                            <template #status="props">
                                <div :class="'status_box s_' + props.column.value">@{{ status_obj[props.column.value] }}</div>
                            </template>
                                <template #action="props">
                                    <button class="btn p-0 m-0 me-2" title="statistiques"

                                    @click="seeStates(props.row.value)">
                                        <i class="fas fa-chart-line text-primary"></i>
                                    </button>
                                </template>
                            </datatable-component>

                        </div>
                    </div>
                </div>
        @endif
    </section>

    <div id="states-modal-app">
        <popup-component :title="`Statistique de l'annonce`" v-model:display="display">
            <div class="popup-component-container d-none">
                <section class="section">

                    <div class=" col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="dashboard_filter_cnt">
                                    <select v-model="statesFilter.periode" @change="changeStatesFilter()">
                                        <option :value="0">aujourd'hui</option>
                                        <option :value="7">7 derniers jours</option>
                                        <option :value="30">30 derniers jours</option>
                                        <option :value="90">90 derniers jours</option>
                                        <option :value="365">365 derniers jours</option>
                                        <option :value="-1">Période personnalisée</option>
                                    </select>
                                </div>


                                <div class="date_cnt_dashboard" v-if="statesFilter.periode==-1">
                                    <input type="date" v-model="statesFilter.dateFrom">
                                    <input type="date" v-model="statesFilter.dateTo">
                                    <button @click="validateStatesFilter()">valider</button>
                                </div>

                                <div class="tilet-stats-row">

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #f3be2e;">
                                                <div class="icon">
                                                    <i class="bi bi-eye"></i>
                                                </div>
                                                <div class="count">@{{  views  }}</div>
                                            <h3>Vues</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #d9534f;">
                                                <div class="icon">
                                                    <i class="bi bi-envelope"></i>
                                                </div>
                                                <div class="count">@{{  emails  }}</div>
                                            <h3>Email</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #058dc7;">
                                                <div class="icon">
                                                    <i class="bi bi-telephone"></i>
                                                </div>
                                                <div class="count">@{{  phones  }}</div>
                                            <h3>Télephone</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #25d366;">
                                                <div class="icon">
                                                    <i class="bi bi-whatsapp"></i>
                                                </div>
                                                <div class="count">@{{  wtsps  }}</div>
                                            <h3>Whatsapp</h3>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class=" col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="dashboard_filter_cnt">
                                    <select v-model="emailsFilter.periode" @change="changeEmailsFilter()">
                                        <option :value="0">aujourd'hui</option>
                                        <option :value="7">7 derniers jours</option>
                                        <option :value="30">30 derniers jours</option>
                                        <option :value="90">90 derniers jours</option>
                                        <option :value="365">365 derniers jours</option>
                                        <option :value="-1">Période personnalisée</option>
                                    </select>
                                </div>


                                <div class="date_cnt_dashboard" v-if="emailsFilter.periode==-1">
                                    <input type="date" v-model="emailsFilter.dateFrom">
                                    <input type="date" v-model="emailsFilter.dateTo">
                                    <button @click="validateEmailsFilter()">valider</button>
                                </div>

                                <h5 class="card-title">Emails : </h5>
                                <datatable-component :datatable="datatable"></datatable-component>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </popup-component>
    </div>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        Vue.createApp({
            data() {
                return {
                    totalAds: null,
                    totalPubAds: null,
                    totalRevAds: null,
                    totalAdsByUnivers: null,
                    totalAdsByUser: null,
                    periodeFilters: [{
                            name: "Toujours",
                            days: null
                        },
                        {
                            name: "Aujourd'hui",
                            days: 1
                        },
                        {
                            name: "Cette semaine",
                            days: 7
                        },
                        {
                            name: "Ce mois",
                            days: 30
                        },
                    ],

                    universFilters: [{
                             name: "Booklist",
                             id : 5
                        },
                        {
                             name: "Primelist",
                             id : 3
                        },
                        {
                             name: "Officelist",
                             id : 2
                        },
                        {
                             name: "Homelist",
                             id : 1
                        },
                        {
                             name: "Landlist",
                             id : 4
                        }
                    ],

                    usersFilters: [{
                             name: "Promoteur",
                             id : 3
                        },
                        {
                             name: "Agence",
                             id : 4
                        },
                        {
                             name: "Particulier",
                             id : 5
                        },
                    ],

                    AdsPeriode: {

                        name:  "Toujours",

                        days:  null

                    },
                    PubAdsPeriode: {

                        name:  "Toujours",

                        days:  null

                    },
                    RevAdsPeriode: {

                        name:  "Toujours",

                        days:  null

                    },

                    AdsByUnivers: {

                        name : "Homelist",

                        id : 1

                    },

                     AdsByUser: {

                        name : "Promoteur",

                        id : 3

                    }
                }
            },
            mounted() {
                this.loadTotalAds(this.AdsPeriode.days);
                this.loadTotalPubAds(this.PubAdsPeriode.days);
                this.loadTotalRevAds(this.RevAdsPeriode.days);
                this.loadTotalAdsByUnivers(this.AdsByUnivers.id);
                this.loadTotalAdsByUser(this.AdsByUser.id);
            },
            methods: {
                loadTotalAds(days)  {
                    this.totalAds = null;
                    axios.post('/api/v2/dashboard/getTotalAds', {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.totalAds = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadTotalPubAds(days)  {
                    this.totalPubAds = null;
                    axios.post('/api/v2/dashboard/getTotalPublishedAds', {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.totalPubAds = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadTotalRevAds(days)  {
                    this.totalRevAds = null;
                    axios.post('/api/v2/dashboard/getTotalInReviewAds', {
                            days: days
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.totalRevAds = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadTotalAdsByUnivers(id) {
                    this.totalAdsByUnivers = null;
                    axios.post('/api/v2/dashboard/getAdsByUnivers', {
                            id: id
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.totalAdsByUnivers = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });

                },
                loadTotalAdsByUser(id) {
                    this.totalAdsByUser = null;
                    axios.post('/api/v2/dashboard/getAdsByUser', {
                            id: id
                        })
                        .then(response => {
                            if (response.data.success == true) {
                                this.totalAdsByUser = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });

                },
                changeTotalAds(obj) {
                    this.AdsPeriode = obj;
                    this.loadTotalAds(this.AdsPeriode.days);
                },
                changeTotalPubAds(obj) {
                    this.PubAdsPeriode = obj;
                    this.loadTotalPubAds(this.PubAdsPeriode.days);
                },
                changeTotalRevAds(obj) {
                    this.RevAdsPeriode = obj;
                    this.loadTotalRevAds(this.RevAdsPeriode.days);
                },
                changeAdsUnivers(obj) {
                    this.AdsByUnivers = obj;
                    this.loadTotalAdsByUnivers(this.AdsByUnivers.id);
                },
                changeAdsUsers(obj) {
                    this.AdsByUser = obj;
                    this.loadTotalAdsByUser(this.AdsByUser.id);

                }
            }
        }).mount('#states-app');

        // ads graph
        Vue.createApp({
            data() {
                return {}
            },
            mounted() {
                this.loadData();
            },
            methods: {
                loadData()  {
                    axios.post('/api/v2/dashboard/getTotalAdsByMonths')
                        .then(response => {
                            if (response.data.success == true) {
                                const months = ["janv.", "févr.", "mars", "avril.", "may", "juin", "juil.",
                                    "août.", "sept.", "oct.", "nov.", "déc."
                                ];
                                const keys = response.data.data.map(v => months[v.month - 1]+" "+v.year).reverse();
                                const vals = response.data.data.map(v => v.data).reverse();
                                console.log(keys, vals);
                                const data = {
                                    type: 'bars',
                                    labels: keys,
                                    datasets: [{
                                        label: "Nombre d'annonces",
                                        data: vals,
                                        backgroundColor: [
                                            'rgba(75, 192, 192, 0.2)',
                                        ],
                                        borderColor: [
                                            'rgb(75, 192, 192)',
                                        ],
                                        borderWidth: 1
                                    }]
                                };
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            },
                                        }
                                    },
                                };
                                const myChart = new Chart(
                                    document.getElementById('AdsByMonths'),
                                    config
                                );
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });

                },
            }
        }).mount('#adsGraph-app');


        let clientsApp = Vue.createApp({
            data() {
                return {
                    search: '',
                    disabledCed: false,
                    status_obj: status_obj,
                    status_arr: status_arr,
                    filter: {
                        status:  null,
                        startDate:  null,
                        endDate:  null,
                        usertype:  null,
                        ced:  null,
                        commercial:  null,
                    },
                    users: [],
                    userTypes: [],
                    ceds: [],
                    updateAd:  {
                        id:  null,
                        display:  false,
                    },
                    datatable: {
                        key: 'clients_datatable',
                        api: '/api/v2/dashboard/clientsFilter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom de client',
                                field: 'fullname',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                                hide: true,
                            },
                            {
                                name: 'Nom de société',
                                field: 'company',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Client type',
                                field: 'u_type',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                            },
                            {
                                name: "État",
                                field: 'status',
                                type: 'custom',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: 'Date de creation',
                                field: 'created_at',
                                type: 'date',
                                sortable: false,
                                searchable: false,
                                hide: true,
                            },
                            {
                                name: 'Ville',
                                field: 'city',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                                hide: true,
                            },
                            {
                                name: 'Commercial assigné',
                                field: 'assigned_commercial',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                                hide: true,
                            },
                            {
                                name: 'CED assigné',
                                field: 'assigned_ced',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                                hide: true,
                            },
                            {
                                name: 'Contract type',
                                field: 'contract_name',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                            },
                            {
                                name: 'date de début',
                                field: 'start_date',
                                type: 'date',
                                sortable: false,
                                searchable: false
                            },
                            {
                                name: 'date de fin',
                                field: 'end_date',
                                type: 'date',
                                sortable: false,
                                searchable: false
                            },
                            {
                                name: 'Annonces autorisées',
                                field: 'permitted_ads',
                                type: 'string',
                                sortable: false,
                                //hide:true,
                                searchable: true
                            },
                            {
                                name: 'Annonces',
                                field: 'total_ads',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Annonces actives',
                                field: 'active_ads',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: "Solde",
                                field: 'balance',
                                type: 'custom',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: "Solde initiale",
                                field: 'init_ltc',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Solde utilisé',
                                field: 'used_ltc',
                                type: 'string',
                                sortable: true,
                                searchable: false
                            },
                            {
                                name: "Whatsapp",
                                field: 'wtsps',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "Téléphone",
                                field: 'phones',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "Emails",
                                field: 'emails',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "Vues",
                                field: 'views',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'custom',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: 'users.id',
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
                            settings: true,
                            export_xlsx: true,
                            export_json: false,
                            pagination_buttons: true
                        }
                    }
                }
            },
            components: {
                'DatatableComponent': DatatableComponent,
            },
            watch: {
                filter: {
                    handler(value) {
                        // this.datatable.filters = [{
                        //     type: 'where',
                        //     col: 'id',
                        //     val: `%${this.search}%`,
                        //     op: 'LIKE'
                        // }];
                    },
                    deep: true
                }
            },
            mounted() {
                /*new Lightpick({
                    field: document.getElementById('datepicker'),
                    singleDate: false,
                    numberOfMonths: 2,
                    footer: true,
                    onSelect: (start, end)=>{
                        this.filter.startDate = start?start.format("YYYY-MM-DD"):null;
                        this.filter.endDate = end?end.format("YYYY-MM-DD"):null;
                        if((this.filter.startDate&&this.filter.endDate)||(!this.filter.startDate&&!this.filter.endDate)) this.loadFilter();
                    }
                });*/
                if ('{{ auth()->user()->hasRole('ced') }}' == '1') {
                    this.disabledCed = true;
                    this.filter.ced = '{{ auth()->user()->id }}';
                    this.datatable.filters.push({
                        type: 'where',
                        col: 'ced.id',
                        val: `${this.filter.ced}`,
                        op: '='
                    });
                }
                axios.get('/api/v2/user/types').then(response => {
                    if (response.data.data) {
                        console.log(response.data.data);
                        const userTypes = response.data.data;
                        // add loaded types types to appUpdateUser vue object
                        this.userTypes = userTypes.filter((ut) => {
                            return [3, 4].indexOf(ut.id) != -1
                            // return true;
                        });
                    }
                }).catch(error => {
                    console.log(error);
                });

                axios.post('/api/v2/user/filter/', {
                        where: [{
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'usertype',
                                    val: '8',
                                    op: '='
                                },
                                {
                                    type: 'orWhere',
                                    col: 'usertype',
                                    val: '9',
                                    op: '='
                                }
                            ],
                        }]
                    })
                    .then(response => {
                        console.log("load ceds ", response);
                        if (response.data.success) {
                            this.ceds = response.data.data;
                        }
                    })
                    .catch(error => {
                        var err = error.response.data.error;
                        displayToast(err, '#842029');
                    });
            },
            methods: {

                datatableLoaded(rows) {

                },
                loadFilter()  {

                    /*this.datatable.filters = [
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
                                    col: 'ads.catid',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'ads.id_user',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'ads.parent_project',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                },
                                {
                                    type: 'orWhere',
                                    col: 'ads.status',
                                    val: `%${this.search}%`,
                                    op: 'LIKE'
                                }
                            ]
                        },
                    ];*/
                    this.datatable.filters = [];
                    if  (this.filter.usertype)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'users.usertype',
                            val: `${this.filter.usertype}`,
                            op: '='
                        });
                    if  (this.filter.status)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'users.status',
                            val: `${this.filter.status}`,
                            op: '='
                        });
                    if  (this.filter.ced)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'ced.id',
                            val: `${this.filter.ced}`,
                            op: '='
                        });
                    if  (this.filter.commercial)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'commercial.id',
                            val: `${this.filter.commercial}`,
                            op: '='
                        });
                    /*if(this.filter.startDate&&this.filter.endDate)
                        this.datatable.filters.push({
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'ads.created_at',
                                    val: `${this.filter.startDate}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'ads.created_at',
                                    val: `${this.filter.endDate}`,
                                    op: '<'
                                },
                            ]
                        });*/
                    console.log(this.datatable.filters);
                },
                seeStates(user)  {
                    statesModal.id = user.id;
                    statesModal.loadData();
                    statesModal.display = true;
                },
            },
        }).mount('#clients-app');

        //STATES MODAL ------------------------------------------------------------------

        let statesModal = Vue.createApp({
            data()  {
                return  {
                    display:  false,
                    id:  null,
                    views:  null,
                    wtsps:  null,
                    emails:  null,
                    phones:  null,
                    statesFilter:  {
                        periode:  30,
                        dateFrom:  null,
                        dateTo:  null
                    },
                    emailsFilter:  {
                        periode:  30,
                        dateFrom:  null,
                        dateTo:  null
                    },
                    datatable: {
                        key: 'users_emails_datatable',
                        api: '/api/v2/emails/filterByUser',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "ID d'annonce",
                                field: 'ad_id',
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
                                name: 'E-mail',
                                field: 'email',
                                type: 'string',
                                sortable: true,
                                searchable: true
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
                                searchable: true
                            },
                            {
                                name: 'Date',
                                field: 'date',
                                type: 'string',
                                sortable: true,
                                searchable: true
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
                            per_page: 30,
                            total: 0,
                            links: []
                        }
                    }
                }
            },
            components: {
                'PopupComponent': PopupComponent,
                'DatatableComponent': DatatableComponent
            },
            watch: {},
            mounted() {},
            methods: {
                loadData()  {
                    this.views = null;
                    this.wtsps = null;
                    this.emails = null;
                    this.phones = null;
                    this.statesFilter.periode = 30;
                    this.emailsFilter.periode = 30;
                    this.loadStates();
                    this.loadEmails();
                },
                loadStates() {
                    if (this.statesFilter.periode != -1) {
                        const dateFrom = new Date();
                        const dateTo = new Date();
                        dateFrom.setDate(dateFrom.getDate() - this.statesFilter.periode);
                        dateTo.setDate(dateTo.getDate() + 1);
                        this.statesFilter.dateFrom = dateFrom.toISOString().split('T')[0];
                        this.statesFilter.dateTo = dateTo.toISOString().split('T')[0];
                    }
                    var config = {
                        method: 'post',
                        data: {

                            id:  this.id,

                            dateFrom:  this.statesFilter.dateFrom,

                            dateTo:  this.statesFilter.dateTo

                        },
                        url: `/api/v2/items/statesByUser`
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                this.views = response.data.data.views;
                                this.wtsps = response.data.data.wtsps;
                                this.emails = response.data.data.emails;
                                this.phones = response.data.data.phones;
                            }
                        })
                        .catch((error) => {
                            var err = error.response.data.error;
                            displayToast(err,  '#842029');
                        });
                },
                loadEmails() {
                    if (this.emailsFilter.periode != -1) {
                        const dateFrom = new Date();
                        const dateTo = new Date();
                        dateFrom.setDate(dateFrom.getDate() - this.emailsFilter.periode);
                        dateTo.setDate(dateTo.getDate() + 1);
                        this.emailsFilter.dateFrom = dateFrom.toISOString().split('T')[0];
                        this.emailsFilter.dateTo = dateTo.toISOString().split('T')[0];
                    }
                    this.datatable.filters = [{
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'emails.date',
                                    val: `${this.emailsFilter.dateFrom}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'emails.date',
                                    val: `${this.emailsFilter.dateTo}`,
                                    op: '<'
                                },
                            ]
                        },
                        {
                            type: 'where',
                            col: 'ads.id_user',
                            val: `${this.id}`,
                            op: '='
                        },
                    ];
                },
                changeStatesFilter()  {
                    this.views = null;
                    this.wtsps = null;
                    this.emails = null;
                    this.phones = null;
                    if (this.statesFilter.periode == -1) {
                        this.statesFilter.dateFrom = null;
                        this.statesFilter.dateTo = null;
                    } else {
                        this.loadStates();
                    }
                },
                changeEmailsFilter() {
                    if (this.emailsFilter.periode == -1) {
                        this.emailsFilter.dateFrom = null;
                        this.emailsFilter.dateTo = null;
                    } else {
                        this.loadEmails();
                    }
                },
                validateStatesFilter() {
                    if (this.statesFilter.dateFrom != null && this.statesFilter.dateTo) {
                        this.loadStates();
                    }
                },
                validateEmailsFilter() {
                    if (this.emailsFilter.dateFrom != null && this.emailsFilter.dateTo) {
                        this.loadEmails();
                    }
                }
            },
        }).mount('#states-modal-app');

        Vue.createApp({
            data() {
                return {
                    recentActivities: []
                }
            },

            mounted() {
                this.getRecentActivities()
            },

            methods: {
                dateFormat(val) {
                    return moment.duration(val).humanize(true);
                },
                getRecentActivities() {
                    const options = {
                        url: '/api/v2/latest',
                        method: 'GET',
                    }

                    axios(options)
                        .then(response => {

                            data = response.data
                            this.recentActivities = data.filter((items,  idx) => idx < 9)
                            // this.recentActivities = data

                            console.log('recent: ',  response.data)

                        })
                        .catch(error => {

                            console.log(error);
                        })
                }
            }
        }).mount('#recentActivities')
    </script>

@endsection
