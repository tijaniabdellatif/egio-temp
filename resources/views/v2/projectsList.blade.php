@extends('v2.layouts.default')

@section('title', __("general.Annonces"))

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/list.styles.css') }}">
    <script src="{{ asset('js/components/slider.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/multilist-filter.component.vue.css') }}">
    <script src="{{ asset('js/components/multilist-filter-component.vue.js') }}"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

    <style>
        footer{
            display: none !important;
        }
    </style>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <style>
        .multi-marker{
            width: 15px;
            height: 15px;
            border: 2px solid white;
            border-radius: 50%;
            box-shadow: 0 0 2px 0;
            background-color: var(--primary-color);
            z-index: 1;
            cursor: pointer;
        }
        .multi-marker:hover{
            width: 20px;
            height: 20px;
            box-shadow: 0 0 9px 0;
        }
        .map-adpopup{
            background: #fff;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            width: 150px;
            position: absolute;
            box-shadow: 0 0 9px 0;
            z-index: 2;
            cursor: pointer;
        }

        .map-adpopup-header{
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            text-align: end;
        }

        .map-adpopup-header #map-adpopup-close{
            border: 0;
            background: #00000061;
            color: white;
        }

        .map-adpopup-header #map-adpopup-close:hover{
            color: red;
        }

        .map-adpopup:hover{
            color: inherit;
        }

        .map-adpopup-cnt-info {
            padding: 10px;
        }
        .map-adpopup-media{
            width: 100%;
            height: 70px;
        }
        .map-adpopup-media img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .map-adpopup-title{
            box-sizing: border-box;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            overflow: hidden;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            font-size: 10px;
        }

        .map-adpopup-price{
            text-align: end;
            font-size: 12px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .loader__element {
            height: 8px;
            width: 100%;
            background: #d6d6d6;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }

        .loader__element:before {
            content: '';
            display: block;
            position: absolute;
            background-color: var(--primary-color);
            height: 9px;
            left: -40%;
            width: 40%;
            animation: getWidth 1s ease-in infinite;
        }

        @keyframes getWidth {
            0% { left: -40%; }
            100% { left: 100%; }
        }

        @media(max-width: 1000px) {
            .map-section {
                display: none;
            }

            .list-section {
                width: 100%;
            }
        }
    </style>

@endsection

@section('content')


    <div id="filter-bar" class="d-none">
        <section class="filter-bar site-section" style="z-index: 86!important;">

            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('/images/logo.png') }}" alt="">
                </a>
            </div>

            <div class="search-input">
                <input type="text" class="form-control" placeholder="{{ __('general.Rechercher') }}" v-model="search" @keyup.enter="searchInput()">
            </div>

            <div class="filter-buttons">

                <button @click="showFilter()" class="filter-button more-filter-button">
                    <i class="fa-solid fa-sliders me-2"></i>
                    {{ __('general.Filtrer') }}
                </button>

                <div class="switch-view">
                    <button type="button" class="btn rounded-pill btn-light btn-grid" @click="toggleGrid()">
                        <i class="fa fa-th mx-1"></i> <span>Grid</span>
                    </button>
                    <button type="button" class="btn rounded-pill btn-light btn-list d-none" @click="toggleGrid()">
                        <i class="fa-solid fa-table-list mx-1"></i> <span>List</span>
                    </button>
                </div>

            </div>

            <div class="filter-buttons mobile-filter-buttons">
                <button @click="showFilter()" class="filter-button more-filter-button">
                    <i class="fa-solid fa-sliders me-2"></i>
                </button>
            </div>

        </section>

        <multilist-filter-component @submitfilter="submitfilter">
        </multilist-filter-component>
    </div>

    <div id="map-section" class="d-none">

        <section style="height: calc(100vh - 134px);">
            <div class="row" style="margin: auto;height: 100%;background-color:#cecece2b;">
                <div class="col-6 list-section" style="overflow-y: scroll;max-height: calc(100% - 25px);">



                    <div style="width: 100%;height:100%;">

                        <div v-if="Object.keys(searchItems).length>0">Filters :</div>
                        <button v-for="s of Object.keys(searchItems)" class="filter-selected" @click="openFilter(s)" :class="(searchItems.hasOwnProperty('min_surface')&&searchItems.hasOwnProperty('max_surface')&&(s=='min_surface'||s=='max_surface'))||(searchItems.hasOwnProperty('min_price')&&searchItems.hasOwnProperty('max_price')&&(s=='min_price'||s=='max_price'))||!searchItems[s]?'d-none':''">
                            @{{s + (searchItems[s]=='true'&&s!="search"?'':(': ' + searchItems[s]))}}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button class="filter-selected" :class="!(searchItems.hasOwnProperty('min_price')&&searchItems.hasOwnProperty('max_price'))?'d-none':''" @click="openFilter('price')">
                            price: @{{ searchItems['min_price'] + "DHS - " + searchItems['max_price'] + "DHS" }}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button class="filter-selected" :class="!(searchItems.hasOwnProperty('min_surface')&&searchItems.hasOwnProperty('max_surface'))?'d-none':''" @click="openFilter('surface')">
                            surface: @{{ searchItems['min_surface'] + "m² - " + searchItems['max_surface'] + "m²" }}
                            <i class="fa-solid fa-edit"></i>
                        </button>

                        <button v-if="Object.keys(searchItems).length>0" class="filter-selected" style="background-color: var(--secondary-color);color: #fff;" @click="clearFilter()">
                            Supprimer filtres
                            <i class="fa-solid fa-close"></i>
                        </button>

                        <hr>

                        <div class="list-container">

                            <a v-for="l of list" class="list-item" @click="toItemPage($event,l)">



                                <div class="list-item-container hoverable" style="position: relative;">
                                    <div v-if="l.premium" class="ribbon"><span>PREMIUM</span></div>

                                    <div class="item-body" >
                                        <div class="media">
                                            <div v-if="l.usertype == 3 || l.usertype == 4" class="avatar">
                                                <img :src="l.avatar?'/storage'+l.avatar:'/images/default-logo.png'" alt="">
                                            </div>
                                            <Slider :images="l.images"></Slider>
                                        </div>

                                        <div class="content">
                                            <div class="title">
                                                <h3>
                                                    <span>@{{ l.title }}</span>
                                                </h3>
                                            </div>

                                            <div class="categorie">
                                                <p>
                                                    <span>@{{ l.categorie }}</span>
                                                </p>
                                            </div>

                                            <div class="localisation">
                                                <span>
                                                    <i class="multilist-icons multilist-location" style="font-size: 25px;"></i>
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
                                        <span class="publication-date">
                                            <i class="fa-regular fa-calendar-alt me-2"></i>
                                            @if(Session::get('lang') === 'ar')
                                        <span class="translates">@{{ formatDateTime(l.date) }}</span> <span class="translates">{{ __('general.Publié le') }} </span>
                                        @else
                                        <span class="translates">{{ __('general.Publié le') }} </span> <span class="translates">@{{ formatDateTime(l.date) }}</span>
                                        @endif
                                    </div>

                                </div>


                            </a>

                        </div>

                        <div v-if="list.length == 0&&!loader" class="text-muted" style="text-align: center;">
                            <span>{{ __('general.Aucun résultat trouvé') }}</span>
                        </div>

                        <div v-if="loader" class="loader"></div>
                    </div>
                </div>
                <div class="col-6 map-section" style="padding:0;height:100%;position: relative;">
                    <div v-if="loaderMap" class="loader__element"></div>
                    <a class="map-adpopup d-none" target="_blank">
                        <div class="map-adpopup-header"><button id="map-adpopup-close"><i class="fa fa-close"></i></button></div>
                        <div style="margin: 10px auto;" class="loader d-none"></div>
                        <div class="map-adpopup-cnt d-none">
                            <div class="map-adpopup-media">
                                <img src="" alt="">
                            </div>
                            <div class="map-adpopup-cnt-info">
                                <div class="map-adpopup-title"></div>
                                <div class="map-adpopup-price"></div>
                            </div>
                        </div>
                    </a>
                    <div id="map" style="width: 100%;height:100%;"> </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const city_slug = '{{ $city??'' }}';
        const city_name = '{{ $cityName??'' }}';
        const neighborhood_slug = '{{ $neighborhood??'' }}';
        const neighborhood_name = '{{ $neighborhoodName??'' }}';
        const univer_slug = '{{ $univer??'' }}';
        const categorie_slug = '{{ $categorie??'' }}';
        const categorie_title = '{{ $categorieTitle??'' }}';
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);
        var lastScrollTop = 0;
        let listingApp = createApp({
            data() {
                return {
                    loader:false,
                    loaderMap:false,
                    listType: 'list',
                    search: '',
                    total: 0,
                    searchTotal: 0,
                    from: 0,
                    count:8,
                    list: [],
                    spotlightAds: [],
                    searchItems: {},
                    Object: Object,
                    map: null,
                }
            },
            computed: {},
            mounted() {

                let meta_title = 'Immobilier neuf';
                if(categorie_title) {meta_title = categorie_title;this.searchItems['categorie'] = categorie_title;}
                if(neighborhood_name) {meta_title += " " + neighborhood_name;this.searchItems['neighborhood'] = neighborhood_name;}
                if(city_name) {meta_title += " " + city_name;this.searchItems['city'] = city_name;} else meta_title += " Maroc";

                document.title = meta_title + " | Multilist";
                this.map = new maplibregl.Map({
                    container: 'map',
                    style: mapStyle,
                    hash: false,
                    maxBounds: [
                        [-17.8, 20],
                        [0, 36.1]
                    ],
                    minZoom: 5,
                    center: [-11,30], // starting position [lng, lat]
                    zoom: 5 // starting zoom
                });
                this.map.on('load', () => {
                    this.map.resize();
                    //loadData
                    this.loadMapPoints();
                    this.loadListing();
                });
                var search = window.location.search.split('?')[1]?.split('&');
                this.searchItems={};
                if(search) for(const s of search){
                    if(s.split('=')[1]){

                        if(s.split('=')[0]=="city"){

                            axios.get("/api/v2/city/getCityById?id="+s.split('=')[1])
                                .then((response) => {
                                    if(response.data.data) this.searchItems[s.split('=')[0]] = response.data.data.name;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        else if(s.split('=')[0]=="neighborhood"){

                            axios.get("/api/v2/neighborhood/getNeighborhoodById?id="+s.split('=')[1])
                                .then((response) => {
                                    if(response.data.data) this.searchItems[s.split('=')[0]] = response.data.data.name;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        else if(s.split('=')[0]=="categorie"){

                            axios.get("/api/v2/getCategoryById?id="+s.split('=')[1])
                                .then((response) => {
                                    if(response.data.data) this.searchItems[s.split('=')[0]] = response.data.data.title;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        else this.searchItems[s.split('=')[0]] = decodeURI(s.split('=')[1]);
                    }
                }
                //infinite scroll
                $('.list-section').on("scroll", (e) => {
                    let id_down = false;
                    let scale = 70;
                    if(document.documentElement.clientWidth <= 767){
                        scale = 70;
                    }
                    if(e.target.scrollTop>lastScrollTop) id_down = true;
                    lastScrollTop=e.target.scrollTop;
                    let bottomOfWindow = e.target.scrollTop + e.target.offsetHeight > e.target.scrollHeight-scale;
                    if (bottomOfWindow) {
                        if(id_down&&this.loader==false&&this.searchTotal>this.list.length){
                            this.from += this.count;
                            this.loadListing();
                        }
                    }
                });


            },
            components: {
                'Slider': Slider
            },
            methods: {

                openFilter(elem=null){
                    filterApp.showFilter(elem);
                },
                clearFilter(){
                    if(multilistType=="multilist") window.location.href = '/projectsList';
                    else window.location.href = '/'+multilistType+'/projectsList';
                },
                loadMapPoints(){
                    this.loaderMap = true;
                    let urlParams = '';
                    let constUrl = (multilistTypeObj.id?`&univer=${multilistTypeObj.id}`:'');
                    if(city_slug) constUrl += "&city="+city_slug;
                    if(neighborhood_slug) constUrl += "&neighborhood="+neighborhood_slug;
                    if(categorie_slug) constUrl += "&categorie="+categorie_slug;
                    if(window.location.search) urlParams = window.location.search + '&' + constUrl;
                    else urlParams = '?' + constUrl;
                    //univer type
                    axios.get("/api/v2/mapPointsProjects"+urlParams)
                        .then((response) => {
                            if(response.data.data){
                                /*for(const d of response.data.data){
                                    console.log(d);
                                    var el = document.createElement('div');
                                    el.className = 'multi-marker';
                                    el.id = d.id;
                                    if(d.loclat&&d.loclng){
                                        let marker = new maplibregl.Marker({
                                                element: el,
                                                draggable: false
                                            })
                                            .setLngLat([d.loclng, d.loclat])
                                            .addTo(this.map);


                                        let thiss=this;
                                        var check = true;
                                        $('.multi-marker').on('mouseenter',function(e){
                                            if(check==false) return;
                                            check = false;
                                            $('.map-adpopup .loader').removeClass('d-none');
                                            $('.map-adpopup .map-adpopup-cnt').addClass('d-none');
                                            let pos = $(this).position();
                                            $('.map-adpopup').removeClass("d-none");
                                            let top = (pos.top - $(".map-adpopup").outerHeight())>5?(pos.top - $(".map-adpopup").outerHeight() + 3):(pos.top + 17);
                                            let left = (pos.left - ($(".map-adpopup").outerWidth()/2) + 10)>5?
                                                            ((pos.left + ($(".map-adpopup").outerWidth()/2) + 10)<($('#map').width()-5)?(pos.left - ($(".map-adpopup").outerWidth()/2) + 10):(pos.left - $(".map-adpopup").outerWidth() +10))
                                                            :(pos.left + 10);
                                            const ad_id = $(this).attr('id');
                                            $(".map-adpopup").css({ top: top + "px" , left: left + "px"  });
                                            axios.get("/api/v2/mapPopupAdById?id="+ad_id)
                                                .then((response) => {
                                                    $('.map-adpopup .loader').addClass('d-none');
                                                    $('.map-adpopup').attr("href","/item/"+ad_id);
                                                    if(response.data.data){
                                                        $('.map-adpopup .map-adpopup-cnt').removeClass('d-none');
                                                        $('.map-adpopup .map-adpopup-media img').attr('src',response.data.data.img?'/storage'+response.data.data.img:"/assets/img/no-photo.png");
                                                        $('.map-adpopup .map-adpopup-title').text(response.data.data.title);
                                                        $('.map-adpopup .map-adpopup-price').text(response.data.data.price+" "+response.data.data.price_curr);
                                                        pos = $(this).position();
                                                        top = (pos.top - $(".map-adpopup").outerHeight())>5?(pos.top - $(".map-adpopup").outerHeight() + 3):(pos.top + 17);
                                                        left = (pos.left - ($(".map-adpopup").outerWidth()/2) + 10)>5?
                                                            ((pos.left + ($(".map-adpopup").outerWidth()/2) + 10)<($('#map').width()-5)?(pos.left - ($(".map-adpopup").outerWidth()/2) + 10):(pos.left - $(".map-adpopup").outerWidth() +10))
                                                            :(pos.left + 10);
                                                        $(".map-adpopup").css({ top: top + "px" , left: left + "px" });
                                                        $('.map-adpopup .loader').addClass('d-none');
                                                    }
                                                });
                                        });
                                        $('.multi-marker').on('mouseleave',(e)=>{
                                            if(
                                                !(e.relatedTarget.classList.contains('map-adpopup-cnt-info')||e.relatedTarget.parentElement.classList.contains('map-adpopup-cnt-info')||
                                                e.relatedTarget.classList.contains('map-adpopup-media')||e.relatedTarget.parentElement.classList.contains('map-adpopup-media')||
                                                e.relatedTarget.classList.contains('map-adpopup-cnt')||e.relatedTarget.parentElement.classList.contains('map-adpopup-cnt')||
                                                e.relatedTarget.classList.contains('map-adpopup')||e.relatedTarget.parentElement.classList.contains('map-adpopup'))
                                            ){
                                                $('.map-adpopup').addClass("d-none");
                                                check = true;
                                            }
                                        });
                                        $('.map-adpopup').on('mouseleave',(e)=>{
                                            $('.map-adpopup').addClass("d-none");
                                            check = true;
                                        });
                                    }
                                }*/
                                this.map.addSource('projects', {
                                    type: 'geojson',
                                    data:{
                                        type: "FeatureCollection",
                                        crs: { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
                                        features: response.data.data.map(v=>{ return { "type": "Feature", "properties": { "id": v.id }, "geometry": { "type": "Point", "coordinates": [ v.loclng, v.loclat, 0.0 ] } } })
                                    },
                                    cluster: true,
                                    clusterMaxZoom: 14,
                                    clusterRadius: 50
                                });

                                this.map.addLayer({
                                    id: 'clusters',
                                    type: 'circle',
                                    source: 'projects',
                                    filter: ['has', 'point_count'],
                                    paint: {
                                        'circle-color': '#4cc6d6',
                                        'circle-radius': [
                                            'step',
                                            ['get', 'point_count'],
                                            20,
                                            100,
                                            30,
                                            750,
                                            40
                                        ]
                                    }
                                });

                                this.map.addLayer({
                                    id: 'cluster-count',
                                    type: 'symbol',
                                    source: 'projects',
                                    filter: ['has', 'point_count'],
                                    layout: {
                                        'text-field': '{point_count_abbreviated}',
                                        'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                                        'text-size': 12
                                    }
                                });

                                this.map.addLayer({
                                    id: 'unclustered-point',
                                    type: 'circle',
                                    source: 'projects',
                                    filter: ['!', ['has', 'point_count']],
                                    paint: {
                                    'circle-color': '#4cc6d6',
                                    'circle-radius': 4,
                                    'circle-stroke-width': 1,
                                    'circle-stroke-color': '#fff'
                                    }
                                });

                                let check = true;
                                let adid = null;

                                this.map.on('click', function(e) {
                                    check = true;
                                    adid = null;
                                    $('.map-adpopup').addClass("d-none");
                                });

                                this.map.on('movestart', function(e) {
                                    check = true;
                                    adid = null;
                                    $('.map-adpopup').addClass("d-none");
                                });

                                this.map.on('zoomstart', function(e) {
                                    check = true;
                                    adid = null;
                                    $('.map-adpopup').addClass("d-none");
                                });

                                this.map.on('click', 'clusters', (e) => {
                                    check = true;
                                    $('.map-adpopup').addClass("d-none");
                                    var features = this.map.queryRenderedFeatures(e.point, {
                                        layers: ['clusters']
                                    });
                                    var clusterId = features[0].properties.cluster_id;
                                    this.map.getSource('projects').getClusterExpansionZoom(
                                        clusterId,
                                        (err, zoom) => {
                                            if (err) return;

                                            this.map.easeTo({
                                                center: features[0].geometry.coordinates,
                                                zoom: zoom
                                            });
                                        }
                                    );
                                });

                                this.map.on('mouseenter', 'clusters', () => {
                                    this.map.getCanvas().style.cursor = 'pointer';
                                });
                                this.map.on('mouseleave', 'clusters', () => {
                                    this.map.getCanvas().style.cursor = '';
                                });

                                this.map.on('mouseenter', 'unclustered-point', (e) => {
                                    this.map.getCanvas().style.cursor = 'pointer';
                                    if(check==false&&adid==e.features[0]?.properties.id) return;
                                    check = false;
                                    adid = e.features[0]?.properties.id;
                                    $('.map-adpopup').addClass("d-none");
                                    $('.map-adpopup .loader').removeClass('d-none');
                                    $('.map-adpopup .map-adpopup-cnt').addClass('d-none');
                                    let pos = {left: e.point.x,top: e.point.y};
                                    $('.map-adpopup').removeClass("d-none");
                                    let top = (pos.top - $(".map-adpopup").outerHeight())>5?(pos.top - $(".map-adpopup").outerHeight() - 10):(pos.top + 5);
                                    let left = (pos.left - ($(".map-adpopup").outerWidth()/2) + 10)>5?
                                                    ((pos.left + ($(".map-adpopup").outerWidth()/2) + 10)<($('#map').width()-5)?(pos.left - ($(".map-adpopup").outerWidth()/2) + 10):(pos.left - $(".map-adpopup").outerWidth() +10))
                                                    :(pos.left + 10);
                                    $(".map-adpopup").css({ top: top + "px" , left: left + "px"  });
                                    axios.get("/api/v2/mapPopupAdById?id="+adid)
                                        .then((response) => {
                                            $('.map-adpopup .loader').addClass('d-none');
                                            $('.map-adpopup').attr("href","/item/"+adid);
                                            if(response.data.data){
                                                $('.map-adpopup .map-adpopup-cnt').removeClass('d-none');
                                                $('.map-adpopup .map-adpopup-media img').attr('src',response.data.data.img?'/storage'+response.data.data.img:"/assets/img/no-photo.png");
                                                $('.map-adpopup .map-adpopup-title').text(response.data.data.title);
                                                $('.map-adpopup .map-adpopup-price').text(response.data.data.price+" "+response.data.data.price_curr);
                                                top = (pos.top - $(".map-adpopup").outerHeight())>5?(pos.top - $(".map-adpopup").outerHeight() - 10):(pos.top + 5);
                                                left = (pos.left - ($(".map-adpopup").outerWidth()/2) )>5?
                                                    ((pos.left + ($(".map-adpopup").outerWidth()/2) )<($('#map').width()-5)?(pos.left - ($(".map-adpopup").outerWidth()/2) ):(pos.left - $(".map-adpopup").outerWidth()))
                                                    :(pos.left );
                                                $(".map-adpopup").css({ top: top + "px" , left: left + "px" });
                                                $('.map-adpopup .loader').addClass('d-none');
                                            }
                                        });
                                });
                                this.map.on('mouseleave', 'unclustered-point', (e) => {
                                    this.map.getCanvas().style.cursor = '';
                                });
                                $('#map-adpopup-close').on('click',function(event){
                                    event.preventDefault();
                                    check = true;
                                    adid = null;
                                    $('.map-adpopup').addClass("d-none");
                                });
                            }
                            this.loaderMap = false;
                        })
                        .catch(error => {
                            this.loaderMap = false;
                            console.log(error);
                        });
                },
                loadListing(){
                    this.loader = true;
                    let urlParams = '';
                    let constUrl = `from=${this.from}&count=${this.count}`+(multilistTypeObj.id?`&univer=${multilistTypeObj.id}`:'');
                    if(city_slug) constUrl += "&city="+city_slug;
                    if(neighborhood_slug) constUrl += "&neighborhood="+neighborhood_slug;
                    if(categorie_slug) constUrl += "&categorie="+categorie_slug;
                    if(window.location.search) urlParams = window.location.search + '&' + constUrl;
                    else urlParams = '?' + constUrl;
                    //univer type
                    axios.get("/api/v2/listingProjects"+urlParams)
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
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
                localisation(data){
                    let r = '';
                    if(data.neighborhood){
                        r = data.neighborhood + ', ' + data.city;
                    }
                    else{
                        if(data.locdept2){
                            r = data.locdept2 + ', ' + data.city;
                        }
                        else r = data.city;
                    }
                    return r;
                },
                price(data){
                    let r = '';

                    if(data.price){
                        data.price = data.price.toLocaleString(undefined, {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        if(data.price <= 0) r = 'Prix à consulter';
                        else{
                            if(data.price_curr){
                                r = data.price + ' ' + data.price_curr;
                            }
                            else r = data.price;
                        }
                    }
                    else r = 'Prix à consulter';
                    return r;
                },
                toItemPage(e,l){
                    if(e.target.classList.contains('btn')||e.target.parentElement.classList.contains('btn')){
                        return;
                    }
                    window.location.href = (multilistType!="multilist"?'/'+multilistType:'') + '/item/'+l.id+'/'+l.title;
                }
            }
        }).mount('#map-section');
        document.getElementById('map-section').classList.remove('d-none');

        let filterApp = createApp({
            data() {
                return {
                    searchItems: [],
                    search:'',
                }
            },
            components: {
                'MultilistFilterComponent': MultilistFilterComponent
            },
            mounted() {
                var search = window.location.search.split('?')[1]?.split('&');
                this.searchItems=[];
                if(search) for(const s of search){
                    this.searchItems.push({key:s.split('=')[0],name:s.split('=')[1]});
                    if(s.split('=')[0]=="search"){
                        this.search = decodeURI(s.split('=')[1]);
                    }
                }
                multilistPopup();
            },
            methods:{

                toggleGrid(){
                    let listContainer = document.querySelector('.list-container')
                    let btnGrid = document.querySelector('.btn-grid')
                    let btnList = document.querySelector('.btn-list')
                    let listSection = document.querySelector('.list-section')
                    let mapSection = document.querySelector('.map-section')
                    if(listContainer.classList.contains('is-grid')){
                        console.log('toggleGridOn')
                        listContainer.classList.remove('is-grid')
                        btnList.classList.add('d-none')
                        btnGrid.classList.remove('d-none')

                        listSection.classList.remove('col-8')
                        listSection.classList.add('col-6')
                        mapSection.classList.remove('col-4')
                        mapSection.classList.add('col-6')

                    }else{
                        console.log('toggleGridOff')
                        btnList.classList.remove('d-none')
                        btnGrid.classList.add('d-none')

                        listSection.classList.remove('col-6')
                        listSection.classList.add('col-8')
                        mapSection.classList.remove('col-6')
                        mapSection.classList.add('col-4')

                        listContainer.classList.add('is-grid')

                    }
                },
                searchInput(){

                    if (multilistType == "multilist") window.location.href = '/immobiliers-neufs?search='+this.search;
                    else window.location.href = '/' + multilistType + '/immobiliers-neufs?search='+this.search;
                },
                submitfilter(data) {



                    let listing_url = '/' + addObjToUrl(data.filter, 'projectsList');


                console.log('listing',listing_url.replace(/\/\//g, '/'));
                    localStorage.setItem('latestsearch',listing_url);


                    if(multilistType=="multilist"){
                            window.location.href = listing_url.replace(/\/\//g, '/');
                    }
                    else {

                        window.location.href = '/'+multilistType+listing_url.replace(/\/\//g, '/');
                    }
                },
                showFilter(elem=null) {
                    document.querySelectorAll('#filter-popup .col-12').forEach(e => e.classList.remove('selected-filter'));
                    document.querySelectorAll('#filter-popup .col-6').forEach(e => e.classList.remove('selected-filter'));
                    // #filter-popup
                    let filterPopup = document.querySelector('#filter-popup');
                    filterPopup.classList.add('active');
                    document.querySelector('body').classList.add('modal-open');
                    //mumtilistTabNav(document.querySelector('#filter-popup'), false);
                    if(elem) {
                        window.location.href = '#filter-'+elem;
                        document.getElementById('filter-'+elem).classList.add('selected-filter');
                    }
                },
            }
        }).mount('#filter-bar');
        document.getElementById('filter-bar').classList.remove('d-none');
    </script>

{{-- apply grid style --}}
    <style>
    .switch-view{
    position: sticky;
    z-index: 9 !important;
    top: 0;
    }
    .is-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr) ) ;
        grid-gap: 15px;
    }
    .is-grid .list-item{
        transition: all .3s ease-in-out;
        margin-bottom: 0 !important;
    }
    .list-item{
        transition: all .3s ease-in-out;
        padding: 5px;
    }
    .list-container .list-item .list-item-container .item-info{
        background: rgb(236 236 236);
        border-bottom: 1px solid #0000004a;
    }
    .list-container .list-item{
        background-color: transparent !important;
    }
    .is-grid .list-item .list-item-container {
        display: flex;
        flex-direction: row;
        width: 100%;
        flex-direction: column;
        height: max-content;
        border: 1px solid #cececec4;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .is-grid .list-item .list-item-container .item-body {
        display: flex;
        z-index: 3;
        flex-direction: column;
        border-top-right-radius: 5px;
        border-top-left-radius: 5px;
        overflow: hidden;
    }
    .is-grid .list-item .list-item-container .media {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        background: rgb(249, 249, 249);
        width: 100%;
        /* margin-right: 10px; */
        position: relative;
        border-top-left-radius: 12px;
        height: 230px;

    }
    .is-grid .list-item .list-item-container .content {
        display: flex;
        flex-direction: column;
        /* width: 100%; */
        /* height: 190px; */
        overflow: hidden;
        position: relative;
        padding: 10px;

    }
    .is-grid .list-item .list-item-container .media .avatar{
        width: 50px;
        height: 50px;
    }

    .is-grid .list-item .list-item-container .content .feature {

    display: flex !important;
    align-items: center;


    }
    .is-grid .list-item .list-item-container .content .features{
        display: flex;

    }

    @media(max-width:450px){
        .switch-view{
            display: none;
        }
    }


    </style>

@endsection

@section('custom_foot')

    <script src=" {{ asset('assets/js/v2/list.scripts.js') }}"></script>



@endsection
