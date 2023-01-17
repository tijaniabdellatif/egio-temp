@extends('v2.layouts.default')

@section('title', $meta_title . ' | MULTILIST')

@section('desc', $meta_desc)

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/list.styles.css') }}">
    <script src="{{ asset('js/components/slider.vue.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/multilist-filter.component.vue.css') }}">
    <script src="{{ asset('js/components/multilist-filter-component.vue.js') }}"></script>

    <script src="{{ asset('js/anime.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/stories-component.vue.css') }}">
    <script src="{{ asset('js/components/stories-component.vue.js') }}"></script>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <script async src="//adserver.multilist.immo/www/delivery/asyncjs.php"></script>



@endsection

@section('content')


    <div id="filter-bar" class="d-none">
        <section class="filter-bar site-section" style="z-index: 86!important;">

            <div class="filter-bar-cnt">

                <div class="search-input">
                    <input type="text" id="search_input" class="form-control translates" placeholder="{{__('general.Rechercher')}}" v-model="search"
                        @keyup.enter="searchInput()">
                    <button class="search_btn" @click="searchInput()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                <div class="filter-buttons">

                    <button @click="showFilter()" class="filter-button more-filter-button translate">
                        @if(Session::get('lang') === 'ar')
                        <div style="    display: flex;
                        justify-content: center;
                        flex-direction: row-reverse;
                        align-items: center;">
                            <i class="fa-solid fa-sliders me-2"></i>
                            {{ __('general.Filtrer') }}
                        </div>

                        @else
                        <i class="fa-solid fa-sliders me-2"></i>
                        {{ __('general.Filtrer') }}
                        @endif

                    </button>

                </div>

                <div class="filter-buttons mobile-filter-buttons">
                    <button @click="showFilter()" class="filter-button more-filter-button">
                        <i class="fa-solid fa-sliders me-2"></i>
                    </button>
                </div>
            </div>

        </section>

        <multilist-filter-component v-on:submitfilter="submitfilter">
        </multilist-filter-component>
    </div>

    @if (isset($billboard_banner) && $billboard_banner)
        <div class="banner-billboard">
            @php
                echo $billboard_banner->html_code;
            @endphp
        </div>
    @endif

    @if (isset($panorama_banner) && $panorama_banner)
        <div class="banner-panorama">
            @php
                echo $panorama_banner->html_code;
            @endphp
        </div>
    @endif

    <div id="stories" class="container-md list-section">
        <stories-component :multilist-type="multilistType" ></stories-component>
    </div>

    <div id="list-section" class="d-none">


        @if (isset($leaderboard_banner) && $leaderboard_banner)
            <div class="banner-leaderboard">
                @php
                    echo $leaderboard_banner->html_code;
                @endphp
            </div>
        @endif

        @if (isset($mobile_banner) && $mobile_banner)
            <div class="banner-mobile">
                @php
                    echo $mobile_banner->html_code;
                @endphp
            </div>
        @endif

        @if (isset($right_banner) && $right_banner)
            <div class="banner-sidebanner sidebanner-right">
                <div class="side_banner_sticky">
                    <div class="side_banner_sticky_cnt">
                        @php
                            echo $right_banner->html_code;
                        @endphp
                    </div>
                </div>
            </div>
        @endif

        @if (isset($left_banner) && $left_banner)
            <div class="banner-sidebanner sidebanner-left">
                <div class="side_banner_sticky">
                    <div class="side_banner_sticky_cnt">
                        @php
                            echo $left_banner->html_code;
                        @endphp
                    </div>
                </div>
            </div>
        @endif

        <section class="container-md list-section">

            <div class="row">
                <div class="col-12 col-lg-12">


                    <div class="section-heading">
                        <h1 id="pageTitle">{{ $meta_title }}</h1>
                        <div class="heading-underline"></div>
                    </div>

                    <div style="text-align: right">
                        <button v-for="s of Object.keys(searchItems)" class="filter-selected" @click="openFilter(s)"
                            :class="(searchItems.hasOwnProperty('min_surface') && searchItems.hasOwnProperty('max_surface') && (
                                s ==
                                'min_surface' || s == 'max_surface')) || (searchItems.hasOwnProperty('min_price') &&
                                searchItems.hasOwnProperty('max_price') && (s == 'min_price' || s == 'max_price')) || s == 'usertype' || !
                            searchItems[s] ? 'd-none' : ''">
                            @{{ (s!='city'&&s!='type'&&s!='categorie'&&s!='neighborhood'&&s!='search'?s:'') + (searchItems[s] == 'true' && s != "search" ? '' : (' ' + searchItems[s])) }}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button class="filter-selected"
                            :class="!(searchItems.hasOwnProperty('min_price') && searchItems.hasOwnProperty('max_price')) ?
                            'd-none' : ''"
                            @click="openFilter('price')">
                            price: @{{ searchItems['min_price'] + "DHS - " + searchItems['max_price'] + "DHS" }}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button class="filter-selected"
                            :class="!(searchItems.hasOwnProperty('min_surface') && searchItems.hasOwnProperty('max_surface')) ?
                            'd-none' : ''"
                            @click="openFilter('surface')">
                            surface: @{{ searchItems['min_surface'] + "m² - " + searchItems['max_surface'] + "m²" }}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button v-if="Object.keys(searchItems).length>0" class="filter-selected"
                            style="background-color: var(--secondary-color);color: #fff;" @click="clearFilter()">
                            {{ __('general.Supprimer filtres') }}
                            <i class="fa-solid fa-close"></i>
                        </button>
                    </div>

                    <div v-if="spotlightAds.length>0&&false">
                        <div class="section-heading">
                            <h1>À la une</h1>
                            <div class="heading-underline"></div>
                        </div>
                        <div class="spotlightAds">
                            <div v-for="s of spotlightAds" class="multiads" @click="toItemPage($event,s)">
                                <div class="img media">
                                    <Slider :images="s.images"></Slider>
                                </div>
                                <div class="content">
                                    <i :class="favoris.find((e) => e == s.id) ? 'fas primary-c' : 'far'"
                                        class=" btn fa-star favori-ico" style="align-self: self-end;"
                                        @click="toggleFavoris(s.id)"></i>
                                    <div class="title">
                                        <a href="#">
                                            <h2> @{{ s.title }}</h2>
                                        </a>
                                    </div>
                                    <div style="font-size: 14px;">
                                        <span>@{{ s.categorie }}</span>
                                    </div>
                                    <div class="location" style="color: gray;">
                                        <span>
                                            <i class="multilist-icons multilist-location"></i>
                                            <span> @{{ localisation(s) }} </span>
                                        </span>
                                    </div>
                                    <div class="price" style="margin-left: auto;">
                                        <span> @{{ price(s) }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="list-organizer">

                        <div class="border-bottom mb-2 d-flex align-items-center justify-content-between pb-2">
                            <span style="font-size: 12px;" class="translates">@{{ searchTotal }} {{ __('general.Sur total de') }}
                                @{{ total }}</span>
                            <div class="search-input">
                                <div style="display:flex;align-items: center;">
                                    <div class="sort-label"><i class="fas fa-arrow-down-wide-short"></i></div>
                                    <div>
                                        <select @change="changeSort()" class="sort-select" v-model="sort">
                                            <option :value="null" class="translates">{{ __('general.Date') }}</option>
                                            <option value="price" class="translates">{{ __('general.Prix croissant') }}</option>
                                            <option value="priceD" class="translates">{{ __('general.Prix décroissant') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2 d-flex align-items-center justify-content-between pb-2">

                            <div id="display-type" class="display-type">
                                <button class="display-type-button" :class="listType == 'list' ? 'checked' : ''">
                                    <i class="fa-solid fa-list me-1"></i>
                                    Liste
                                </button>
                                <button class="display-type-button" :class="listType == 'map' ? 'checked' : ''"
                                    @click="goToMap">
                                    <i class="fa-solid fa-map me-1"></i>
                                    Map
                                </button>
                                <div class="checked-bg" :style="listType == 'list' ? 'left:0' : 'left:50%'"></div>
                            </div>

                        </div>

                    </div>

                    <div class="list-container">

                        <div v-for="l of list" class="list-item" @click="toItemPage($event,l)">



                            <div class="list-item-container hoverable" style="position: relative;">
                                <div v-if="l.premium" class="ribbon"><span>PREMIUM</span></div>

                                <div class="item-body">
                                    <div class="media">
                                        <div v-if="l.usertype == 3 || l.usertype == 4" class="avatar">
                                            <img :src="l.avatar ? '/storage' + l.avatar :
                                                '/images/default-logo.png'"
                                                alt="">
                                        </div>
                                        <Slider :images="l.images"></Slider>
                                    </div>

                                    <div class="content">
                                        <i :class="favoris.find((e) => e == l.id) ? 'fas primary-c' : 'far'"
                                            class=" btn fa-star favori-ico" style="align-self: self-end;"
                                            @click="toggleFavoris(l.id)"></i>
                                        <a :href="`/annonce/${l.slug??'immobilier'}/${l.city}/${l.id}`" class="title">
                                            <h2>
                                                <span>@{{ l.title }}</span>
                                            </h2>
                                        </a>

                                        <div class="categorie">
                                            <p>
                                                <span>@{{ l.categorie }}</span>
                                            </p>
                                        </div>

                                        <div class="localisation">
                                            <span>
                                                <i class="multilist-icons multilist-location"
                                                    style="font-size: 25px;"></i>
                                                @{{ localisation(l) }}
                                            </span>
                                        </div>

                                        <span class="features">

                                            <span v-if="l.surface" class="feature" title="Supérficie">
                                                <i class="multilist-icons multilist-surface" style="font-size: 25px;"></i>

                                                @if(Session::get('lang') === 'ar')
                                                <div style="display:flex; flex-direction:row-reverse;justify-content:center;align-items:center">

                                                    <span style="padding:0 3px">@{{ l.surface }} </span>
                                                    <span> {{ __('general.m²') }}  </span>
                                                </div>
                                                @else
                                                <div>

                                                    <span>@{{ l.surface }}</span>
                                                    {{ __('general.m²') }}
                                                </div>
                                                @endif

                                            </span>

                                            <span v-if="l.bedrooms" class="feature" title="chambres">
                                                <i class="multilist-icons multilist-bed"></i>
                                                @if(Session::get('lang') === 'ar')
                                                <div style="padding:0 3px;display:flex; flex-direction:row-reverse;justify-content:center;align-items:center">
                                                    <span style="padding:0 3px">@{{ l.bedrooms }}</span>
                                                    {{ __('general.Chambres') }}
                                                </div>
                                                @else
                                                <div>
                                                    <span>@{{ l.bedrooms }}</span>
                                                    {{ __('general.Chambres') }}
                                                </div>
                                                @endif

                                            </span>

                                            <span v-if="l.bathrooms" class="feature" title="Salles de bain">
                                                <i class="multilist-icons multilist-bath"></i>
                                                @if(Session::get('lang') === 'ar')
                                                <div style="display:flex; flex-direction:row-reverse;justify-content:center;align-items:center">
                                                    <span style="padding:0 3px">@{{ l.bathrooms }}</span>
                                                    {{ __('general.sdb') }}
                                                </div>
                                                @else
                                                <div>
                                                    <span>@{{ l.bathrooms }}</span>
                                                    {{ __('general.sdb') }}
                                                </div>

                                                @endif

                                            </span>

                                        </span>

                                        <div class="price">
                                            <span>@{{ price(l) }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="item-info">
                                    <span class="publication-date ">
                                        <i class="fa-regular fa-calendar-alt me-2"></i>
                                        @if(Session::get('lang') === 'ar')
                                        <span class="translates">@{{ formatDateTime(l.date) }}</span> <span class="translates">{{ __('general.Publié le') }} </span>
                                        @else
                                        <span class="translates">{{ __('general.Publié le') }} </span> <span class="translates">@{{ formatDateTime(l.date) }}</span>
                                        @endif
                                    </span>
                                    {{-- <span class="features">

                                        <span v-if="l.bedrooms" title="chambres">
                                            <i class="multilist-icons multilist-bed"></i>
                                            @{{ l.bedrooms }}
                                        </span>

                                        <span v-if="l.bathrooms" title="Salles de bain">
                                            <i class="fa-solid fa-bath"></i>
                                            @{{ l.bathrooms }}
                                        </span>

                                        <span v-if="l.surface" title="Supérficie">
                                            <i class="fa-solid fa-expand"></i>
                                            @{{ l.surface }}m²
                                        </span>

                                    </span> --}}
                                </div>

                            </div>

                        </div>



                    </div>

                    <div v-if="list.length == 0&&!loader" class="text-muted" style="text-align: center;">
                        <span>{{ __('general.Aucun résultat trouvé') }}</span>
                    </div>

                    <div v-if="loader" class="loader"></div>

                </div>
            </div>
        </section>
    </div>

    <script>
        const type_slug = '{{ $type??'' }}';
        const city_slug = '{{ $city??'' }}';
        const city_name = '{{ $cityName??'' }}';
        const neighborhood_slug = '{{ $neighborhood??'' }}';
        const neighborhood_name = '{{ $neighborhoodName??'' }}';
        const univer_slug = '{{ $univer??'' }}';
        const categorie_slug = '{{ $categorie??'' }}';
        const categorie_title = '{{ $categorieTitle??'' }}';
        var lastScrollTop = 0;
        let listingApp = createApp({
            data() {
                return {
                    loader: false,
                    listType: 'list',
                    search: '',
                    total: 0,
                    searchTotal: 0,
                    from: 0,
                    count: 8,
                    sort: null,
                    list: [],
                    spotlightAds: [],
                    searchItems: {},
                    Object: Object,
                    favoris: localStorage.favoris ? JSON.parse(localStorage.favoris) : [],
                }
            },
            computed: {},
            mounted() {
                this.searchItems = {};
                let meta_title = 'Immobilier';
                if(categorie_title) {meta_title = categorie_title;this.searchItems['categorie'] = categorie_title;}
                if(neighborhood_name) {meta_title += " " + neighborhood_name;this.searchItems['neighborhood'] = neighborhood_name;}
                if(city_name) {meta_title += " " + city_name;this.searchItems['city'] = city_name;} else meta_title += " Maroc";
                if(type_slug) {meta_title += " - " + type_slug;this.searchItems['type'] = type_slug;}
                //document.title = meta_title + " | Multilist";
                //loadData
                this.loadListing();
                this.loadSpotlight();
                var search = window.location.search.split('?')[1]?.split('&');
                if (search)
                    for (const s of search) {
                        if (s.split('=')[1]) {

                            if (s.split('=')[0] == "city") {

                                axios.get("/api/v2/city/getCityById?id=" + s.split('=')[1])
                                    .then((response) => {
                                        if (response.data.data) this.searchItems[s.split('=')[0]] = response
                                            .data.data.name;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                            } else if (s.split('=')[0] == "neighborhood") {

                                axios.get("/api/v2/neighborhood/getNeighborhoodById?id=" + s.split('=')[1])
                                    .then((response) => {
                                        if (response.data.data) this.searchItems[s.split('=')[0]] = response
                                            .data.data.name;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                            } else if (s.split('=')[0] == "categorie") {

                                axios.get("/api/v2/getCategoryById?id=" + s.split('=')[1])
                                    .then((response) => {
                                        if (response.data.data) this.searchItems[s.split('=')[0]] = response
                                            .data.data.title;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                            } else this.searchItems[s.split('=')[0]] = decodeURI(s.split('=')[1]);
                        }
                    }
                //infinite scroll
                window.onscroll = () => {
                    let id_down = false;
                    let scale = 500;
                    if (document.documentElement.clientWidth <= 767) {
                        scale = 700;
                    }
                    if (window.pageYOffset > lastScrollTop) id_down = true;
                    lastScrollTop = window.pageYOffset;
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight > document
                        .documentElement.offsetHeight - scale;
                    if (bottomOfWindow) {
                        if (id_down && this.loader == false && this.searchTotal > this.list.length) {
                            this.from += this.count;
                            this.loadListing();
                        }
                    }
                };
            },
            components: {
                'Slider': Slider
            },
            methods: {
                changeSort() {
                    this.from = 0;
                    this.list = [];
                    this.loadListing();
                },
                toggleFavoris(id) {
                    if (localStorage.getItem('favoris')) {
                        if (Array.isArray(JSON.parse(localStorage.getItem('favoris')))) {
                            ids = JSON.parse(localStorage.getItem('favoris'));
                            const idIndex = ids.indexOf(id);
                            if (idIndex == -1) {

                                const swalWithBootstrapButtons = Swal.mixin({
                                    customClass: {
                                      confirmButton: 'btn btn-success',
                                      cancelButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: false
                                  })

                                  swalWithBootstrapButtons.fire({
                                    title: '{{ __("general.Ajouter l'annonce à la liste des favoris") }}',
                                    text: "{{ __('general.Voulez vous vraiment continuer') }}",
                                    icon: 'info',
                                    showCancelButton: true,
                                    confirmButtonText: '{{ __('general.oui') }}',
                                    cancelButtonText: '{{ __('general.Annuler') }}',
                                    reverseButtons: true
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                        ids.push(id);
                                        localStorage.favoris = JSON.stringify(ids);
                                        this.favoris = ids;
                                      swalWithBootstrapButtons.fire(
                                        '{{ __('general.Annonce ajoutée') }}',
                                        `{{ __("general.lannonce a été ajoutée avec succés") }}`,
                                        'success'
                                      )
                                    } else if (
                                      /* Read more about handling dismissals below */
                                      result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                      swalWithBootstrapButtons.fire(
                                        `{{ __('general.Annuler') }}`,
                                        `{{ __('general.Vous avez annuler') }}`,
                                        'error'
                                      )
                                    }
                                  })

                            } else {

                                const swalWithBootstrapButtons = Swal.mixin({
                                    customClass: {
                                      confirmButton: 'btn btn-success',
                                      cancelButton: 'btn btn-danger'
                                    },
                                    buttonsStyling: false
                                  })

                                  swalWithBootstrapButtons.fire({
                                    title: '{{ __("general.Supprimer lannonce de la liste des favoris") }}',
                                    text: "{{ __('general.Voulez vous vraiment continuer') }}",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: "{{ __('general.oui') }}",
                                    cancelButtonText: "{{ __('general.Annuler') }}",
                                    reverseButtons: true
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                        ids.splice(idIndex, 1);
                                        localStorage.favoris = JSON.stringify(ids);
                                        this.favoris = ids;
                                      swalWithBootstrapButtons.fire(
                                        "{{ __('general.Annonce supprimé!') }}",
                                        '{{ __("general.l'annonce a été supprimé de la liste des favoris") }}',
                                        'success'
                                      )
                                    } else if (
                                      /* Read more about handling dismissals below */
                                      result.dismiss === Swal.DismissReason.cancel
                                    ) {
                                      swalWithBootstrapButtons.fire(
                                        'Annulé',
                                        'Vous avez annuler l\'action de la suppression',
                                        'error'
                                      )
                                    }
                                  })


                            }
                        } else {
                            localStorage.favoris = JSON.stringify([id]);
                            this.favoris = [id];
                        }
                    } else {
                        localStorage.setItem('favoris', '[' + id + ']');
                        this.favoris = [id];
                    }
                },
                goToMap() {
                    window.location.href = (multilistTypeObj.id ? '/' + multilistTypeObj.name : '') + '/map' +
                        window.location.search;
                },
                openFilter(elem = null) {
                    filterApp.showFilter(elem);
                },
                clearFilter() {
                    if (multilistType == "multilist") window.location.href = '/list';
                    else window.location.href = '/' + multilistType + '/list';
                },
                loadListing() {
                    console.log('the object',multilistTypeObj);
                    console.log('multilist',multilistType)
                    this.loader = true;
                    let urlParams = '';
                    let constUrl = `from=${this.from}&count=${this.count}` + (
                        multilistTypeObj.id ? `&univer=${multilistTypeObj.id}` : '') + (
                        this.sort ? `&sort=${this.sort}` : '');
                    if(city_slug) constUrl += "&city="+city_slug;
                    if(univer_slug) constUrl += "&univer="+univer_slug;
                    if(type_slug) constUrl += "&type="+type_slug;
                    if(neighborhood_slug) constUrl += "&neighborhood="+neighborhood_slug;
                    if(categorie_slug) constUrl += "&categorie="+categorie_slug;
                    if (window.location.search) urlParams = window.location.search + '&' + constUrl;
                    else urlParams = '?' + constUrl;
                    //univer type
                    axios.get("/api/v2/listingAds" + urlParams)
                        .then((response) => {
                            this.loader = false;
                            this.list = this.list.concat(response.data.data.data);
                            this.total = response.data.data.total;
                            this.searchTotal = response.data.data.searchTotal;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                loadSpotlight() {
                    let urlParams = '';
                    let constUrl = `from=${this.from}&count=${this.count}` + (
                        multilistTypeObj.id ? `&univer=${multilistTypeObj.id}` : '');
                    if(city_slug) constUrl += "&city="+city_slug;
                    if(univer_slug) constUrl += "&univer="+univer_slug;
                    if(type_slug) constUrl += "&type="+type_slug;
                    if(neighborhood_slug) constUrl += "&neighborhood="+neighborhood_slug;
                    if(categorie_slug) constUrl += "&categorie="+categorie_slug;
                    if (window.location.search) urlParams = window.location.search + '&' + constUrl;
                    else urlParams = '?' + constUrl;
                    //univer type
                    axios.get("/api/v2/spotlightAds" + urlParams)
                        .then((response) => {
                            this.spotlightAds = response.data.data;
                        })
                        .catch(error => {
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

                    window.location.href = (multilistType !== "multilist" ? '/' + multilistType : '') +
                    `/annonce/${l.slug??'immobilier'}/${l.city}/${l.id}`;
                }
            }
        }).mount('#list-section');
        document.getElementById('list-section').classList.remove('d-none');

        let filterApp = createApp({
            data() {
                return {
                    searchItems: [],
                    search: '',
                }
            },
            components: {
                'MultilistFilterComponent': MultilistFilterComponent
            },
            mounted() {
                if (document.getElementById('search_input'))
                    animatePlaceholder(document.getElementById('search_input'),
                        [
                            `{{__('general.Que recherchez-vous ?')}}`,
                            `{{__('general.Appartement à vendre Casablanca')}}`,
                            `{{__('general.Immobilier à Marrakech')}}`,
                            `{{__('general.Appartement de 200m2')}}`
                        ]);

                var search = window.location.search.split('?')[1]?.split('&');
                this.searchItems = [];
                if (search)
                    for (const s of search) {
                        this.searchItems.push({
                            key: s.split('=')[0],
                            name: s.split('=')[1]
                        });
                        if (s.split('=')[0] == "search") {
                            this.search = decodeURI(s.split('=')[1]);
                        }
                    }
                multilistPopup();
            },
            methods: {
                searchInput() {
                    // redirect to the listing page
                    if (multilistType == "multilist") window.location.href = '/list?search=' + this.search;
                    else window.location.href = '/' + multilistType + '/list?search=' + this.search;
                },
                submitfilter(data) {
                    let listing_url = '/' + addObjToUrl(data.filter, 'list');
                    localStorage.setItem('latestsearch', listing_url);

                    // redirect to the listing page
                    if (multilistType == "multilist") window.location.href = listing_url.replace(/\/\//g, '/');
                    else window.location.href = '/' + multilistType + listing_url.replace(/\/\//g, '/');
                },
                showFilter(elem = null) {
                    document.querySelectorAll('#filter-popup .col-12').forEach(e => e.classList.remove(
                        'selected-filter'));
                    document.querySelectorAll('#filter-popup .col-6').forEach(e => e.classList.remove(
                        'selected-filter'));
                    // #filter-popup
                    let filterPopup = document.querySelector('#filter-popup');
                    filterPopup.classList.add('active');
                    document.querySelector('body').classList.add('modal-open');
                    //mumtilistTabNav(document.querySelector('#filter-popup'), false);
                    if (elem) {
                        window.location.href = '#filter-' + elem;
                        document.getElementById('filter-' + elem).classList.add('selected-filter');
                    }
                },
            }
        }).mount('#filter-bar');
        document.getElementById('filter-bar').classList.remove('d-none');

        let storiesApp = createApp({
            // import StoriesComponent
            components: {
                'stories-component': StoriesComponent
            },
            computed : {
                multilistType() {
                    // story type from props
                    return this.multilist_type;
                }
            }
        }).mount('#stories');
    </script>

@endsection

@section('custom_foot')

    <script src=" {{ asset('assets/js/v2/list.scripts.js') }}"></script>
    <script>
        const elements = document.querySelectorAll('.translates');
        const localLang = '<?= session('lang') ?>';
        if(localLang === 'ar'){
        elements.forEach(item => {

               item.setAttribute('dir','rtl');
          })
        }
        </script>


@endsection
