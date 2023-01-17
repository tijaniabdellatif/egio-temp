@extends('v2.layouts.default')

<?php

$seo_of_this_page = [];
$seoHome = json_decode($seo);

if ($multilistType == '') {
    $multilistType = 'multilist';
}

if ($seoHome) {
    // loop through seo
    foreach ($seoHome as $key => $value) {
        if ($value->name == $multilistType) {
            $seo_of_this_page = $value;
        }
    }
}

?>

@section('title' , $seo_of_this_page->meta_tags )

@section('desc' , $seo_of_this_page->meta_desc )

@section('custom_head')

<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <script src="{{ asset('js/anime.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/multilist-filter.component.vue.css') }}">
    <script src="{{ asset('js/components/multilist-filter-component.vue.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <style>
        body {
            overflow-x: hidden;
        }

        .arround{

             display:flex;
             justify-content: center;
             align-items: center;
             width:100%;
        }

        .arrow-button {
            display: flex;
            background-color: rgb(76, 198, 214);
            padding: 10px 16px;
            border-radius: 20px;
            transition: all .3s ease;
            cursor: pointer;
            align-items: center;
            border: none;
        }

        .arrow-button a{

            text-decoration:none;
            font-weight: 500;
            font-size: 16px;
            color: white;

        }

        .arrow-button > .arrow {
            width: 6px;
            height: 6px;
            border-right: 2px solid #C2FFE9;
            border-bottom: 2px solid #C2FFE9;
            position: relative;
            transform: rotate(-45deg);
            margin: 0 6px;
            transition: all .3s ease;
        }

        .arrow-button > .arrow::before {
            display: block;
            background-color: currentColor;
            width: 3px;
            transform-origin: bottom right;
            height: 2px;
            position: absolute;
            opacity: 0;
            bottom: calc(-2px / 2);
            transform: rotate(45deg);
            transition: all .3s ease;
            content: "";
            right: 0;
            color:white;
        }

        .arrow-button:hover > .arrow  {
            transform: rotate(-45deg) translate(4px, 4px);
            color:white;

        }

        .arrow-button:hover > .arrow::before {
            opacity: 1;
            width: 8px;
        }

        .arrow-button:hover {
            background-color: rgba(76, 198, 214,0.7);
            color: #fff;
        }


    </style>

    <link rel="stylesheet" href="{{ asset('assets/css/v2/customiser.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/components/stories-component.vue.css') }}">
    <script src="{{ asset('js/components/stories-component.vue.js') }}"></script>

@endsection

@section('content')

    <div id="app" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

        <div class="filter-section site-section" id="filter-app">

            <div class="filter-form-container">

                <h1 style="text-shadow: rgb(55 55 55 / 34%) -1px 1px 1px;letter-spacing: 2px;">{{__('general.Quel est votre projet ?')}}</h1>

                <div class="multilist-nav-tab" id="filter-form" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

                    <div class="multilist-nav-items">
                        <button v-if="multilistTypeObj.id!=5" class="multilist-nav-button"
                            :class="type == 'vente' ? 'active' : ''" @click="search='';type='vente'"
                            pan="form-filter-2">{{__('general.Acheter')}}</button>
                        <button v-if="multilistTypeObj.id!=5&&multilistTypeObj.id!=3"
                            :class="type == 'location' ? 'active' : ''" class="multilist-nav-button"
                            @click="search='';type='location'" pan="form-filter-1">{{__('general.Louer')}}</button>
                        <button v-if="multilistTypeObj.id==5||multilistTypeObj.id==null"
                            :class="type == 'vacance' ? 'active' : ''" class="multilist-nav-button"
                            @click="search='';type='vacance'" pan="form-filter-3">{{__('general.Voyager')}}</button>
                        {{-- <button class="multilist-nav-button" pan="form-filter-3">Estimer</button> --}}
                        {{--<button class="multilist-nav-button" style="font-weight:600;"><i class="fa-solid fa-sliders me-2"></i>
                            <span class="d-none d-md-inline">Filtrer</span></button>--}}
                        <div class="active-underline"></div>
                    </div>

                    <div class="multilist-tab-container">
                        <div v-if="type=='vente'" class="multilist-tab-pane" id="form-filter-2">
                            <div class="mb-2 d-flex align-items-center">
                                <input id="search1" v-model="search" @keyup.enter="filterSearch('vente')"
                                    class="form-control" placeholder="{{__('general.Que recherchez-vous ?')}}" type="text" name="">
                                <button class="btn-submit" @click="filterSearch('vente')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>
                        </div>
                        <div v-if="type=='location'" class="multilist-tab-pane" id="form-filter-1">

                            <div class="mb-2 d-flex align-items-center">
                                <input id="search2" v-model="search" @keyup.enter="filterSearch('location')"
                                    class="form-control" placeholder="{{__('general.Que recherchez-vous ?')}}" type="text" name="">
                                <button class="btn-submit" @click="filterSearch('location')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>

                        </div>

                        <div v-if="type=='vacance'" class="multilist-tab-pane" id="form-filter-3">

                            <div class="mb-2 d-flex align-items-center">
                                <input id="search3" v-model="search" @keyup.enter="filterSearch('vacance')"
                                    class="form-control" placeholder="{{__('general.Que recherchez-vous ?')}}" type="text" name="">
                                <button class="btn-submit" @click="filterSearch('vacance')">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </div>

                        </div>

                    </div>
                    <div class="multilist-tab-filter">
                        <button id="filter-btn" @click="showFilter()">
                            <i class="fa-solid fa-sliders me-2"></i>
                            {{ __('general.Recherche avancée') }}
                        </button>
                    </div>
                    <div v-if="lastSearch" class="continue-search">
                        <hr>
                        <div class="continue-search-label">{{ __('general.souhait') }}</div>
                        <button class="continue-search-btn yes" @click="continueSearch()"> {{ __('general.Reprendre') }} </button>
                        <button class="continue-search-btn no" @click="dontContinueSearch()"> {{ __('general.Nouvelle recherche') }} </button>
                    </div>

                </div>

            </div>

            <div id="multi-list-types" class="type-container animate__animated animate__fadeInUp">
                <div class="type-scroll-container">
                    <h2 class="type-item" :class="multilistType == 'booklist' ? 'active' : ''" id="booklist">
                        <a href="/booklist" class="">
                            <img src="/images/booklist-logo-white.png" alt="Booklist : location saisonnière" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>
                    <h2 class="type-item" :class="multilistType == 'homelist' ? 'active' : ''" id="homelist">
                        <a href="/homelist" class="">
                            <img src="/images/homelist-logo-white.png" alt="Homelist : immobilier seconde main" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>
                    <h2 class="type-item" :class="multilistType == 'primelist' ? 'active' : ''" id="primelist">
                        <a href="/primelist" class="">
                            <img src="/images/primelist-logo-white.png" alt="Primelist : immobilier neuf" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>
                    <h2 class="type-item" :class="multilistType == 'landlist' ? 'active' : ''" id="landlist">
                        <a href="/landlist" class="">
                            <img src="/images/landist-logo-white.png" alt="Landlist : Lot de terrain" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>

                    {{--  <div></div>  --}}
                    <h2 class="type-item" :class="multilistType == 'officelist' ? 'active' : ''" id="officelist">
                        <a href="/officelist" class="">
                            <img src="/images/officelist-logo-white.png" alt="Officelist : immobilier professionnel" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>
                </div>
            </div>

        </div>

        <multilist-filter-component v-on:submitfilter="submitfilter" @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        </multilist-filter-component>

        {{-- stories --}}
        <stories-component :multilist-type="multilistType"> </stories-component>

        <test-component></test-component>


        <div v-if="multilistTypeObj.id!=5&&multilistTypeObj.id!=1" class="a-la-lune-cette-semaine-section site-section">

            <div class="section-heading translate">
                <h3>{{ __('general.Les projets') }}</h3>
                <div class="heading-underline"></div>
            </div>

            <div class="slide noselect" id="slide-alalunecettesemain">

                <button class="mov-btn-next">
                    <i class="fa-solid fa-angle-right"></i>
                </button>
                <button class="mov-btn-prev">
                    <i class="fa-solid fa-angle-left"></i>
                </button>

                {{--
                <div class="slide-item" v-for="proj in projects" @click="toItemPage($event,proj)"
                    style="cursor:pointer;">

                    <div class="multiads">

                        <div class="img" :style="`background: url('${proj.thumbnail}')`"></div>

                        <div class="content">
                            <div class="title" style="margin-bottom: 10px;">
                                <h1 class="m-0">
                                    @{{ proj.title }}
                                </h1>
                            </div>
                            <div class="location" style="margin-bottom: 10px;">
                                <span>
                                    <i class="multilist-icons multilist-location"></i>
                                    <span>
                                        @{{ proj.city }}
                                    </span>
                                    <span v-if="proj.neighborhood">
                                        @{{ ', ' + proj.neighborhood }}
                                    </span>
                                </span>
                            </div>

                            <div class="price">
                                <span>
                                    @{{ price(proj) }}
                                </span>
                            </div>
                        </div>

                    </div>

                </div>
                --}}

            </div>

        </div>

        <div class="arround" v-if="multilistTypeObj.id!=5&&multilistTypeObj.id!=1">
            <button class="arrow-button"><a href="/immobiliers-neufs">{{ __('general.Voir plus de projet / map') }}</a><span class="arrow"></span>
            </button>

        </div>

    </div>

    {{-- <div class="univ ${!multilistTypeObj.id ? 'd-block' : 'd-none'}">${multilistTypeObj.id == 3? 'Primelist' : '' }</div> --}}

    <script>

        function price(data) {


            let r = '';

            if (data.price) {
                data.price = data.price.toLocaleString(undefined, {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

                if (data.price <= 0) r = '{{ __("general.Prix à consulter") }}';
                else {
                    if (data.price_curr) {
                        r = data.price + ' ' + data.price_curr;
                    } else r = data.price;
                }
            } else r = '{{ __("general.Prix à consulter") }}';
            return r;
        }

        $( document ).ready(function() {
            setInterval(() => {
                document.querySelectorAll("#img-loader").forEach((el)=>el.remove())
                document.querySelectorAll(".multiads .default-pic").forEach((el)=>el.classList.remove('d-none'))

            }, 10000);
            let paramas = "";
            if (multilistTypeObj.id) {
                paramas += "univer=" + multilistTypeObj.id + "&";
            }
            if (paramas.length > 0) {
                // remove las char
                paramas = paramas.substring(0, paramas.length - 1);
                // add ?
                paramas = "?" + paramas;
            }
            axios.get('/api/v2/getProjectsAds' + paramas)
                .then(response => {
                    if (response.data.success) {
                        let data = response.data.data;
                        if (data) {
                            let content = '';
                            for(const d of data){
                                console.log('data:',d)
                                console.log('data2:',multilistTypeObj.id)
                                let thumbnail;
                                if (d.images[0]?.name)
                                    thumbnail = ('/storage' + d.images[0].name)/*.replace('/storage/images/', '/storage/images/tn_')
                                    .replace('/storage/old/', '/storage/old/tn_')*/;
                                else thumbnail = '/assets/img/no-photo.png';
                                content += `
                                    <a href="${multilistType == 'multilist' ? '' : multilistType }/annonce/${d.slug}/${d.city}/${d.id}" class="slide-item" style="cursor:pointer;">

                                        <div class="city-card multiads">

                                            <div class="card-image img" href="#" style="background-image: url(${thumbnail})" data-image-full="${thumbnail}">
                                                <img src="https://ik.imagekit.io/qnmbyz2r2/house-model.jpg?tr=w-70,h-50" width="100px" alt="Cover image" />
                                            </div>
                                            <div class="content">
                                                <div class="title" style="margin-bottom: 10px;">
                                                    <h1 class="m-0">
                                                        ${ d.title }
                                                    </h1>
                                                </div>
                                                <div class="location" style="margin-bottom: 10px;">
                                                    <span>
                                                        <i class="multilist-icons multilist-location"></i>
                                                        <span>
                                                            ${ d.city }
                                                        </span>
                                                        ${d.neighborhood?
                                                            `   <span v-if="d.neighborhood">
                                                                    ${ ', ' + d.neighborhood }
                                                                </span>`:''}
                                                    </span>
                                                </div>
                                                <div class="price">
                                                    <span>
                                                        ${ price(d) }
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            }
                            $('#slide-alalunecettesemain').append(content);
                            slide('slide-alalunecettesemain', 5000);
                        }
                    }
                })
                .catch(error => {
                    console.log(error);
                });
        });

        let homeApp = createApp({
            data() {
                return {
                    selectedFrom: 'form-1',
                    search: '',
                    projects: [],
                    multilistTypeObj: multilistTypeObj,
                    type: multilistTypeObj.id == 5 ? 'vacance' : 'vente',
                    lastSearch: localStorage.latestsearch??null,
                }
            },
            components: {
                'MultilistFilterComponent': MultilistFilterComponent,
                'stories-component': StoriesComponent
            },
            computed: {
                multilistType() {
                    return multilistType;
                }
            },
            mounted() {

                if (document.getElementById('search1'))
                    animatePlaceholder(document.getElementById('search1'),
                        [
                            `{{__('general.Que recherchez-vous ?')}}`,
                            `{{__('general.Appartement à vendre Casablanca')}}`,
                            `{{__('general.Immobilier à Marrakech')}}`,
                            `{{__('general.Appartement de 200m2')}}`
                        ]);

                if (document.getElementById('search2'))
                    animatePlaceholder(document.getElementById('search2'),
                        [
                            `{{__('general.Que recherchez-vous ?')}}`,
                            `{{__('general.Appartement à louer Casablanca')}}`,
                            `{{__('general.Immobilier à Rabat')}}`,
                            `{{__('general.Appartement de 140m2')}}`

                        ]);

                //mumtilistTabNav();

                multilistPopup();

                // load projects
                //this.loadProjects();

            },
            methods: {
                continueSearch(){

                    let origin = window.location.origin;
                    let newOrigin = window.location.href;
                    let word = newOrigin.split('/');

                        if(localStorage.latestsearch){

                            if(word[3]){
                              window.location.href = origin + `/${word[3]}`+ localStorage.latestsearch.replace(/\/\//g, '/');

                            }else {

                               window.location.href = localStorage.latestsearch.replace(/\/\//g, '/');
                            }


                             this.dontContinueSearch();

                      }

                },
                dontContinueSearch(){
                    localStorage.removeItem('latestsearch');
                    this.lastSearch = null;
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
                    } else r = '{{ __("general.Prix à consulter") }}';
                    return r;
                },
                displayMoreTypes(e) {
                    // get the button
                    let button = e.target;

                    // #multi-list-types > .type-scroll-container

                    // get the type-scroll-container element who is inside #multi-list-types
                    let typeScrollContainer = document.querySelector('#multi-list-types > .type-scroll-container');

                    // scroll to right
                    typeScrollContainer.scrollLeft += typeScrollContainer.clientWidth;

                    // console.log(typeScrollContainer);
                },
                showFilter() {

                    // #filter-popup
                    let filterPopup = document.querySelector('#filter-popup');

                    // add .active
                    filterPopup.classList.add('active');
                    document.querySelector('body').classList.add('modal-open');

                    //mumtilistTabNav(document.querySelector('#filter-popup'), false);

                },
                submitfilter(data) {
                    let host_name = window.location.href;
                    // remove comments #
                    host_name = host_name.replace(/#.*/, '');
                    // remove params
                    host_name = host_name.replace(/\?.*/, '');

                    let listing_url = host_name + '/' + addObjToUrl(data.filter, 'list');
                    localStorage.setItem('latestsearch','/' + addObjToUrl(data.filter, 'list'));

                    // redirect to the listing page
                    window.location.href = new URL(listing_url.replace(/\/\//g, '/'));
                },
                filterSearch(type) {
                    let listing_url = window.location.href.split('?')[0] + '/' + addObjToUrl({
                        search: this.search,
                        type
                    }, 'list');
                    localStorage.setItem('latestsearch','/' + addObjToUrl({
                        search: this.search,
                        type
                    }, 'list'));

                    // redirect to the listing page
                    window.location.href = new URL(listing_url.replace(/\/\//g, '/'));
                },
                setStorieButtonOpacity() {

                    // get .stories-container
                    let storiesContainer = document.querySelector('.stories-container');

                    // get .stories
                    let stories = storiesContainer?.querySelector('.stories');

                    if (!stories) {
                        return;
                    }

                    // get .btn-scroll-right
                    let btnScrollRight = storiesContainer.querySelector('.btn-scroll-right');

                    // get .btn-scroll-left
                    let btnScrollLeft = storiesContainer.querySelector('.btn-scroll-left');


                    // get how many pixels to end the scroll
                    let initialScrollPosition = stories.scrollWidth - stories.clientWidth;

                    // console.log(stories.scrollWidth,stories.clientWidth+stories.scrollLeft);

                    // get the opacity based on initialScrollPosition and current scroll position
                    let opacity = 1 - (stories.scrollLeft / initialScrollPosition);
                    let opacity2 = (stories.scrollLeft / initialScrollPosition);


                    // if the opacity is 0 then make the button display none
                    if (opacity <= 0) {
                        btnScrollRight.style.display = 'none';
                    } else {
                        btnScrollRight.style.display = 'block';
                    }

                    if (opacity2 <= 0) {
                        btnScrollLeft.style.display = 'none';
                    } else {
                        btnScrollLeft.style.display = 'block';
                    }

                    // set the opacity
                    btnScrollRight.style.opacity = opacity;
                    btnScrollLeft.style.opacity = opacity2;
                },
                loadProjects() {
                    // axios get /api/v2/getProjectsAds

                    let paramas = "";

                    if (multilistTypeObj.id) {
                        paramas += "univer=" + multilistTypeObj.id + "&";
                    }

                    if (paramas.length > 0) {
                        // remove las char
                        paramas = paramas.substring(0, paramas.length - 1);
                        // add ?
                        paramas = "?" + paramas;
                    }

                    axios.get('/api/v2/getProjectsAds' + paramas)
                        .then(response => {
                            if (response.data.success) {
                                let data = response.data.data;
                                if (data) {
                                    this.projects = data;
                                    // get thumbnail
                                    // ad th th_ pregec to the image name
                                    for (let i = 0; i < this.projects.length; i++) {
                                        // convert image ("/images/img_62fd88a5c0cc3.jpg") to img_thumb ("/images/tn_img_62fd88a5c0cc3.jpg")
                                        if (this.projects[i].images[0]?.name)
                                            this.projects[i].thumbnail = ('/storage' + this.projects[i].images[
                                                0].name).replace('/storage/images/', '/storage/images/tn_')
                                            .replace('/storage/old/', '/storage/old/tn_');

                                        else this.projects[i].thumbnail = '/assets/img/no-photo.png';
                                    }
                                    slide('slide-alalunecettesemain', 5000);
                                }
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                toItemPage(e, l) {
                    if (e.target.classList.contains('btn') || e.target.parentElement.classList.contains('btn')) {
                        return;
                    }
                    window.location.href = (multilistType != "multilist" ? '/' + multilistType : '') + '/item/' + l
                        .id + '/' + l.title;
                },
                toItemById(id) {
                    window.location.href = '/item/' + id;
                }
            }
        }).mount('#app');
    </script>

    {{-- section start --}}

    <section style="opacity:0.5;transition:all 1s ease;background-color: rgb(0 83 125 / 70%);display:none;">

        <style>
            .city-card{
                transition: all .2s ease-in-out;
            }
            .city-card:hover {
                box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px;
                transition: all .2s ease-in-out !important;
                transform: scale(1.03) !important;
            }
            .stop-animate-on-hover:hover {
                animation: none !important;
            }

            .wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .cta-hero {
                display: flex;
                align-items: center;
                padding: 15px 25px;
                text-decoration: none;
                font-family: 'Poppins', sans-serif;
                font-size: 19px;
                color: white;
                background: rgb(23, 106, 142);
                transition: 1s;
                box-shadow: 6px 6px 0 black;
                transform: skewX(deg);
                border-radius: 30px
            }

            .cta-hero:focus {
                outline: none;
            }

            .cta-hero:hover {
                transition: 0.5s;
                box-shadow: 10px 10px 0 #FBC638;
            }

            .cta-hero span:nth-child(2) {
                transition: 0.5s;
                margin-right: 0px;
            }

            .cta-hero:hover span:nth-child(2) {
                transition: 0.5s;
                margin-right: 25px;
            }

            .cta-hero span:nth-child(2) {
                width: 20px;
                margin-left: 20px;
                position: relative;
                top: 12%;
            }

            /**************SVG****************/

            path.one {
                transition: 0.4s;
                transform: translateX(-60%);
            }

            path.two {
                transition: 0.5s;
                transform: translateX(-30%);
            }

            .cta-hero:hover path.three {
                animation: color_anim 1s infinite 0.2s;
            }

            .cta-hero:hover path.one {
                transform: translateX(0%);
                animation: color_anim 1s infinite 0.6s;
            }

            .cta-hero:hover path.two {
                transform: translateX(0%);
                animation: color_anim 1s infinite 0.4s;
            }

            /* SVG animations */

            @keyframes color_anim {
                0% {
                    fill: white;
                }

                50% {
                    fill: #FBC638;
                }

                100% {
                    fill: white;
                }
            }
        </style>
    </section>

    <div class="section-heading translate" style="display: none;">
        <h3>{{__('general.Les annonces par villes')}}</h3>
        <div class="heading-underline "></div>
    </div>

    <section>
        <div class="section-heading translate">
            <h3 class="">{{__('general.Les annonces par villes')}}</h3>
            <div class="heading-underline"></div>
        </div>

        <div id="cities_cards" class="cities-card-cnt" style="position: relative">
{{--
<div class="spinner-border" style="color:var(--primary-color);position: absolute;right: 50%;top: 50%;" role="status">
    <span class="visually-hidden"></span>
</div> --}}

            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=2':('/'+multilistTypeObj.name+'/map?city=2')):'/map?city=2'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="300"
            data-aos-offset="0">
                <div class="city-card-cover casablanca" title="Immobilier Casablanca"></div>
                <div class="city-card-title">{{__('general.Casablanca')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=8':('/'+multilistTypeObj.name+'/map?city=8')):'/map?city=8'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="500"
            data-aos-offset="0">
                <div class="city-card-cover rabat" title="Immobilier Rabat"></div>
                <div class="city-card-title">{{__('general.Rabat')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=6':('/'+multilistTypeObj.name+'/map?city=6')):'/map?city=6'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="700"
            data-aos-offset="0">
                <div class="city-card-cover marrakech" title="Immobilier Marrakech"></div>
                <div class="city-card-title">{{__('general.marrakech')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=11':('/'+multilistTypeObj.name+'/map?city=11')):'/map?city=11'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="350"
            data-aos-offset="0">
                <div class="city-card-cover agadir" title="Immobilier Agadir"></div>
                <div class="city-card-title">{{__('general.Agadir')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=5':('/'+multilistTypeObj.name+'/map?city=5')):'/map?city=5'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="550"
            data-aos-offset="0">
                <div class="city-card-cover tanger" title="Immobilier Tanger"></div>
                <div class="city-card-title">{{__('general.Tanger')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/map?city=4':('/'+multilistTypeObj.name+'/map?city=4')):'/map?city=4'" class="city-card"
            data-aos="flip-left"
            data-aos-delay="750"
            data-aos-offset="0">
                <div class="city-card-cover fes" title="Immobilier Fès"></div>
                <div class="city-card-title">{{__('general.Fès')}}</div>
            </a>

            <!-- mobile -->
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=2':('/'+multilistTypeObj.name+'/list?city=2')):'/list?city=2'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="200"
            data-aos-offset="0">
                <div class="city-card-cover casablanca" title="Immobilier Casablanca"></div>
                <div class="city-card-title">{{__('general.Casablanca')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=8':('/'+multilistTypeObj.name+'/list?city=8')):'/list?city=8'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="300"
            data-aos-offset="0">
                <div class="city-card-cover rabat" title="Immobilier Rabat"></div>
                <div class="city-card-title">{{__('general.Rabat')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=6':('/'+multilistTypeObj.name+'/list?city=6')):'/list?city=6'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="350"
            data-aos-offset="0">
                <div class="city-card-cover marrakech" title="Immobilier Marrakech"></div>
                <div class="city-card-title">{{__('general.marrakech')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=11':('/'+multilistTypeObj.name+'/list?city=11')):'/list?city=11'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="170"
            data-aos-offset="0">
                <div class="city-card-cover agadir" title="Immobilier Agadir"></div>
                <div class="city-card-title">{{__('general.Agadir')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=5':('/'+multilistTypeObj.name+'/list?city=5')):'/list?city=5'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="250"
            data-aos-offset="0">
                <div class="city-card-cover tanger" title="Immobilier Tanger"></div>
                <div class="city-card-title">{{__('general.Tanger')}}</div>
            </a>
            <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/list?city=4':('/'+multilistTypeObj.name+'/list?city=4')):'/list?city=4'" class="city-card-mobile"
            data-aos="flip-left"
            data-aos-delay="300"
            data-aos-offset="0">
                <div class="city-card-cover fes" title="Immobilier Fès"></div>
                <div class="city-card-title">{{__('general.Fès')}}</div>
            </a>
        </div>


        <script>
            createApp({
                data() {
                    return {
                        multilistTypeObj: multilistTypeObj,
                    }
                },
            }).mount('#cities_cards');
        </script>

    </section>

    <section class="map" @if(Session::get('lang') === 'ar')
        dir="rtl"
    @else
        dir="ltr"
    @endif>

        <div class="section-heading translate">
            <h3 class="">{{__('general.Les annonces par régions')}}</h3>
            <div class="heading-underline "></div>
        </div>
        <div class="map-container">
            <div class="map-section">
                <div class="region" data-aos="fade-right" data-aos-duration="1000">
                    <ul class="items" id="list">

                    </ul>

                </div>
                <div class="map_svg" data-aos="fade-up" data-aos-duration="1300">

                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1282 1299">
                        <g>

                            <path id="3" class="regions"

                                d="m36.501 1275c-0.5371-3.179-0.15188-4.7483 2.115-8.6165 1.5245-2.6013 4.1849-9.9411 5.912-16.311l3.1403-11.581 10.416 0.2386c8.6278 0.1977 41.564 2.2961 74.416 4.7413 9.4076 0.7002 39.785 2.7989 101.5 7.0124 86.319 5.8934 109.92 7.5394 116.75 8.143l7.25 0.6406-0.0434-10.388c-0.0239-5.7133-0.4594-28.163-0.96783-49.888-0.77536-33.131-1.2363-40.87-2.8589-48l-1.9345-8.5 5.4821-10.739c5.9771-11.708 6.2621-11.984 19.823-19.216 4.95-2.6398 12.6-6.9586 17-9.5975 6.8147-4.0871 9.1853-4.9494 16-5.8197 5.6151-0.7171 10.534-2.1887 16.5-4.9362 4.675-2.153 10.664-4.2334 13.308-4.6232 2.6445-0.3897 4.9877-1.2396 5.2071-1.8886s0.87305-11.08 1.4526-23.18c0.57954-12.1 1.4854-30.751 2.013-41.446 0.5276-10.696 0.86022-19.538 0.73915-19.651s-3.6593-0.7786-7.8628-1.4804c-7.1654-1.1964-8.3996-1.1118-19.762 1.3544l-12.12 2.6305-15.563-5.4535c-14.193-4.9732-16.32-5.4535-24.151-5.4535-4.7234 0-12.507-0.75129-17.297-1.6695l-8.7092-1.6695-16.518 9.6838-20.366-6.9224c-20.335-6.9118-20.371-6.9197-23.449-5.1724-1.6958 0.9625-4.8202 1.75-6.9432 1.75s-4.1381 0.45-4.478 1c-0.88992 1.4399-4.6874 1.1731-6.8744-0.48298-1.6772-1.27-2.4531-1.0492-7.3749 2.0993l-5.5005 3.5186-2.8484-2.7289c-1.5666-1.5009-3.573-4.9106-4.4587-7.577-1.8378-5.5331-4.2156-7.2575-8.6612-6.2811-2.515 0.55238-3.4208 0.25665-4.7952-1.5655-2.4006-3.1825-6.8543-1.6473-9.2564 3.1908-1.4063 2.8324-2.9224 4.0054-7.3842 5.7128-7.1186 2.7241-17.103 11.189-17.534 14.866-0.25387 2.1651-1.2684 3.0424-5.4407 4.7048-7.5014 2.9888-16.507 11.438-17.283 16.217l-0.61147 3.7618-8.5601 1.5322c-6.7552 1.209-9.2975 2.1647-12.057 4.5321-3.1364 2.6908-12.859 16.258-12.859 17.944 0 1.5944 2.3544-1.0577 6.891-7.7624 5.6812-8.3965 7.8519-10.535 11.072-10.908 1.9062-0.2209 2.522 0.3452 3.2214 2.9616 1.259 4.7096-2.2721 10.946-12.199 21.546-7.1524 7.6372-8.1512 9.1414-7.6694 11.55 0.46621 2.3311-0.27201 3.6329-4.6608 8.2193-2.8681 2.9973-6.2838 7.2496-7.5904 9.4496s-3.9396 5.6542-5.8511 7.6761l-3.4754 3.676 2.1308 2.7088c2.6378 3.3535 2.6512 4.4987 0.11042 9.4789-1.6558 3.2456-2.5462 3.9602-4.9349 3.9602-1.603 0-4.1041 0.4523-5.558 1.005-2.3148 0.8801-2.5594 1.3876-1.9677 4.0819 0.53502 2.4359 0.18683 3.5938-1.6716 5.5591-1.8613 1.9683-2.5545 4.2809-3.3474 11.168-0.97114 8.4351-1.0931 8.7291-4.227 10.186-2.7532 1.2798-3.3188 2.1684-3.8527 6.0531-1.3287 9.6679-13.081 18.279-18.389 13.475-1.8343-1.6601-2.0652-1.6401-4.9724 0.43-2.4123 1.7177-2.9976 2.812-2.8037 5.2417 0.21403 2.6822-0.18461 3.2187-3.1497 4.2392-3.0847 1.0617-3.776 2.099-7.5689 11.358-2.2958 5.604-5.2326 11.316-6.5262 12.693-2.1467 2.285-2.2303 2.7309-0.95829 5.1077 1.2478 2.3316 1.1986 2.7468-0.4709 3.9676-1.0255 0.7498-2.2869 1.5041-2.8031 1.6762-0.55028 0.1834-0.45606 1.3716 0.2278 2.8725 0.98626 2.1646 0.69499 3.8949-1.8871 11.21-2.9442 8.3415-5.0106 16.653-8.6175 34.66-0.93465 4.6663-2.4893 10.066-3.4547 12-1.2817 2.5671-1.5291 4.3255-0.91672 6.5159 0.46126 1.65 0.5993 3.9 0.30675 5-0.49219 1.8507-0.44778 1.8654 0.59488 0.1976 0.67071-1.0729 0.86098-3.3758 0.47009-5.6895z">

                                </path>
                            <path id="4" class="regions" d="m424.42 996.78c8.0454-2.0338 16.423-1.9326 22.212 0.26807 1.7422 0.6624 2.5713 0.57347 2.8919-0.31021 0.24724-0.68134 0.92437-11.589 1.5047-24.239 2.4121-52.576 2.9113-63.619 3.4354-76 0.90237-21.318 1.2633-23.393 4.1981-24.129 1.3835-0.34724 8.6637-0.2921 16.178 0.12253 7.5145 0.41463 16.813 0.921 20.663 1.1253s14.425 0.77155 23.5 1.2606 24.825 1.196 35 1.571c10.175 0.37498 21.65 0.8613 25.5 1.0807 3.85 0.21941 11.05 0.63264 16 0.9183s20.334 0.99969 34.187 1.5867 28.478 1.258 32.5 1.4909c15.743 0.91177 44.321 2.2818 44.465 2.1317 0.0838-0.0871 0.44372-11.6 0.79992-25.585 0.35621-13.984 0.82515-25.713 1.0421-26.064 0.21694-0.35103 0.47663-6.0092 0.57708-12.574 0.18166-11.872 0.16993-11.937-2.1944-12.248-1.3074-0.17163-5.4683-1.7466-9.2465-3.5l-6.8695-3.1879-11.256-17.97c-6.1911-9.8838-11.907-18.381-12.702-18.883-1.0249-0.6465-2.1893-0.1688-3.9985 1.6404-4.1898 4.1898-10.484 4.5617-19.024 1.1241-9.0811-3.6558-10.371-4.7404-15.78-13.267-2.475-3.9015-4.991-7.1052-5.5911-7.1193-0.60009-0.0141-3.1828 0.60394-5.7394 1.3734-4.5286 1.3629-4.7477 1.327-8.5034-1.3936-2.1203-1.5359-5.8375-4.8886-8.2606-7.4504-5.3601-5.667-9.8571-7.0384-17.821-5.4348-3.8805 0.78136-6.4319 0.78516-9 0.0134-1.9713-0.5924-11.234-1.2474-20.584-1.4555-15.661-0.34859-17.284-0.21787-20.604 1.6603l-3.6045 2.0386-6.412-3.1885c-5.5126-2.7412-7.4764-3.1885-14-3.1885h-7.5885l-4.145-8.9214c-2.2798-4.9067-4.145-9.3794-4.145-9.9392s-1.7348-3.0223-3.8552-5.4721c-2.537-2.9313-4.0797-5.8584-4.5118-8.5608-0.74506-4.6593-0.80232-4.6807-6.7573-2.5158-4.9775 1.8095-19.402 6.0758-25.376 7.5054-2.2 0.52646-4.675 1.2297-5.5 1.5628-0.825 0.33305-2.4 0.7364-3.5 0.89632-1.1 0.15993-6.725 1.0506-12.5 1.9792-12.23 1.9666-12.559 2.0109-24.905 3.3535-5.173 0.56255-9.7484 1.3804-10.168 1.8175-0.41926 0.43704-2.2044 3.4946-3.9671 6.7946-5.2025 9.7402-5.5892 10.169-10.215 11.334l-4.3859 1.1044-2.7734 10.281c-5.4143 20.071-6.0028 21.395-11.898 26.783-4.8247 4.4095-5.4846 5.4941-5.9198 9.7302-0.34627 3.3703-1.8494 6.952-5.1289 12.221-2.5515 4.0997-4.6391 7.8406-4.6391 8.313 0 0.47244-1.3589 3.6765-3.0197 7.1202-2.1539 4.466-3.9459 6.7355-6.25 7.9154-1.7767 0.9098-5.2553 3.414-7.7303 5.5649s-9.6006 6.4883-15.835 9.6387c-9.283 4.6912-11.952 5.6278-14.747 5.1743-2.9428-0.47754-3.4906-0.24098-3.9827 1.7198-0.31385 1.2504-2.8405 3.9681-5.6147 6.0393-4.0142 2.9969-5.6339 3.6478-7.933 3.188-7.0311-1.4062-10.888 3.4134-10.888 13.605 0 5.3375-0.56041 7.3878-3.5669 13.05-1.9618 3.6945-3.8743 6.7322-4.25 6.7506-2.3692 0.11539-5.3553 4.2839-5.6077 7.8284-0.1537 2.1579-1.1913 5.4344-2.3057 7.2809-1.1145 1.8466-2.334 5.0853-2.7101 7.1972-0.42363 2.3787-2.1183 5.4228-4.4538 8-4.176 4.6083-7.1058 11.094-7.1058 15.73 0 1.6662-0.69035 4.3644-1.5341 5.9961-1.3529 2.6163-1.3639 3.4679-0.0931 7.2112 1.7935 5.2826 0.636 12.905-3.071 20.223-3.227 6.3703-3.8857 13.557-1.5114 16.489 1.6891 2.086 5.2096 1.9942 5.2096-0.13588 0-0.52756 0.54276-0.48765 1.25 0.0919 0.6875 0.56339 2.6972 2.1833 4.466 3.5997 2.0387 1.6326 3.4528 3.8377 3.8629 6.0238 0.35581 1.8966 1.5266 4.5668 2.6019 5.9337l1.9549 2.4853 4.576-3.494c5.2292-3.9928 5.505-4.0533 8.7565-1.9229 1.9167 1.2559 2.8349 1.3371 4.5769 0.40485 1.1985-0.64143 3.3557-0.94131 4.7938-0.66641 1.5472 0.29578 4.2449-0.33187 6.6081-1.5375 3.6178-1.8457 4.4666-1.9186 9.0233-0.77583 2.7664 0.69381 5.4799 1.5797 6.0299 1.9687s7.4974 2.878 15.439 5.5312c14.38 4.8043 14.451 4.8167 17.5 3.0366 1.6838-0.9831 4.5526-2.6386 6.3752-3.6788s4.3335-2.5595 5.5798-3.3761c1.2463-0.81661 2.4894-1.2614 2.7625-0.98831 0.27304 0.27305 2.9371 0.99196 5.9202 1.5976 2.983 0.60562 6.5487 1.3384 7.9237 1.6284s7.45 0.84664 13.5 1.2369c10.778 0.69528 15.279 1.8257 31.5 7.9096 6.0068 2.253 7.5518 2.1712 20.417-1.081z" />
                            <path id="7" class="regions"
                                d="m710.02 798.25c0.0106-2.6125 0.67556-23.2 1.4777-45.75s1.4685-45.311 1.4808-50.581l0.02226-9.5805-9-11.621c-6.6975-8.6478-9-12.379-9-14.586 0-2.5994-0.52409-3.1401-4.2458-4.3807-2.3352-0.77839-4.2916-2.0534-4.3477-2.8333-0.24999-3.478-0.52214-3.9181-2.4227-3.9181-1.3196 0-2.5928-1.2092-3.6852-3.5-1.5619-3.2754-1.5599-3.7288 0.0312-7.0654 1.6138-3.3841 1.6104-3.5562-0.0648-3.3848-3.9381 0.40286-4.4971 0.0112-3.8222-2.6777 0.48735-1.9418 0.12397-3.127-1.3916-4.539-2.2852-2.129-2.7361-7.0197-0.79559-8.6302 0.69058-0.57313 2.4906-1.352 4-1.7308 2.0479-0.514 2.7444-1.3037 2.7444-3.1118 0-1.3326 0.675-2.682 1.5-2.9986 2.0988-0.80538 1.8679-4.0784-0.5-7.0888-1.1-1.3984-2-3.8464-2-5.4398 0-6.4574-4.8784-13.344-10.154-14.334-3.2024-0.60078-3.5922-0.38593-5.399 2.9762-1.3631 2.5363-2.4723 3.487-3.6965 3.1681-0.96196-0.25056-2.001-0.0478-2.309 0.45055-1.0327 1.671-2.3785 0.92958-3.9982-2.2025l-1.6076-3.1086-2.7228 2.6086c-3.3321 3.1924-4.4344 3.2543-6.3858 0.35866l-1.5163-2.25-4.4342 2.3854c-4.2353 2.2784-4.5493 2.31-7.0012 0.70343-2.5954-1.7006-3.7374-4.2316-3.7623-8.3389-0.0162-2.671-0.73285-2.8141-3.1477-0.62872-2.396 2.1683-4.6484 1.1359-5.0444-2.3122-0.29405-2.5605-0.56138-2.2994-3.0214 2.951-1.4843 3.168-4.1185 7.0567-5.8536 8.6415-1.7444 1.5933-3.4617 4.3819-3.8412 6.2376-0.57654 2.819-1.5349 3.7619-5.9886 5.892-4.3276 2.0698-6.3527 4-11.016 10.5-5.1261 7.1445-6.7629 8.599-15.906 14.134-5.6055 3.3938-13.23 7.1725-16.943 8.3972-13.28 4.3798-16.961 6.4487-19.166 10.769-1.4284 2.7998-3.0298 4.3243-5.3451 5.0885-4.3144 1.4239-15.611 13.057-19.577 20.16l-3.1162 5.5806-22.242 6.8128 0.6576 5.013c0.47287 3.6047 1.4729 5.8582 3.5596 8.0216 1.5961 1.6548 4.6881 7.2798 6.871 12.5l3.969 9.4913 7.8188 0.51609c5.8988 0.38937 9.11 1.1926 13.077 3.2712 4.9578 2.5975 5.4269 2.6681 8.2003 1.2339 3.9785-2.0574 39.259-2.1607 42.931-0.12575 1.9901 1.103 3.1521 1.0952 6.9472-0.047 3.6445-1.0968 5.7305-1.1408 10.414-0.21928 5.1549 1.0142 6.4797 1.8066 11.053 6.611 2.8577 3.0023 6.3858 6.3107 7.8402 7.3521 2.4533 1.7566 2.9502 1.7856 6.8757 0.40056 6.9612-2.456 7.451-2.1992 13.841 7.2571 5.6955 8.4287 6.1647 8.8513 12.776 11.508 8.7722 3.5247 13.23 3.5778 16.407 0.19552 1.9112-2.0344 3.1791-2.4871 6.3016-2.25l3.909 0.29683 23.245 37.455 6 2.4738c3.3 1.3606 7.0125 2.4957 8.25 2.5225 2.0218 0.0438 2.252-0.43309 2.2692-4.7013z" />
                            <path id="8" class="regions"
                                d="m715.27 659.39c0.37325-0.37325 4.0701-2.7516 8.2153-5.2852s17.543-12.279 29.773-21.656c12.23-9.3772 27.683-20.529 34.34-24.782 10.083-6.4416 13.145-7.9002 18.343-8.7374 3.8328-0.61725 9.7589-2.7722 15.361-5.5857l9.1209-4.5808 9.5876 1.5923c17.722 2.9432 23.545 3.1686 26.696 1.0331 1.5335-1.0395 4.5721-2.3576 6.7524-2.9292 4.2174-1.1055 5.2399-2.8336 5.4362-9.1878 0.0743-2.4036-0.53818-3.6449-2.4003-4.865-3.0721-2.0129-3.1246-3.1143-0.29194-6.1295 2.1966-2.3382 2.1983-2.3684 0.32311-5.8505l-1.885-3.5001 4.8893-4.9639c4.9835-5.0596 4.7069-5.9043-1.2302-3.7574-2.9776 1.0767-3.5648 0.97847-5.4563-0.91306l-2.1196-2.1196 4.312-6.9769-2.5068-1.8485c-1.3787-1.0167-2.7806-3.2425-3.1153-4.9463-0.52432-2.6694-0.0822-3.5943 3.1971-6.6881 3.7152-3.5051 3.7624-3.638 1.9885-5.5982-1.0169-1.1237-3.3962-2.1417-5.4031-2.3119-3.0894-0.26193-3.6992-0.74375-4.403-3.4786l-0.81689-3.1746-4.4685 2.4975c-2.4577 1.3736-4.9286 2.2131-5.491 1.8655-0.63157-0.39033-1.0225 0.89737-1.0225 3.3681 0 4.6931-1.396 5.9747-3.9703 3.6449-1.7323-1.5677-2.0892-1.5144-5.8782 0.8775l-4.034 2.5465-5.1388-4.062c-2.8264-2.2341-5.2394-3.9106-5.3622-3.7255-0.12286 0.18511-1.0984 2.3616-2.1679 4.8366s-2.9128 5.4097-4.0961 6.5216c-2.0034 1.8824-2.0585 2.2924-0.79942 5.9547 2.0582 5.9869 0.29932 7.3028-4.5511 3.4049-3.2002-2.5718-4.135-2.8803-5.8867-1.9428-1.7504 0.93678-2.43 0.71457-4.2633-1.3938-1.9015-2.187-2.9268-2.4877-8.0044-2.3478-3.2029 0.0883-6.1356-0.15164-6.5171-0.53316-0.38151-0.38151 0.70402-2.1494 2.4123-3.9287 3.0651-3.1925 3.0839-3.2746 1.4316-6.2501-1.1683-2.1039-2.3001-2.921-3.7453-2.7039-1.7326 0.26032-2.2501-0.47181-3.1677-4.4816-1.2914-5.6432-0.40966-7.2994 3.8859-7.2994 2.1757 0 3.1403-0.62592 3.8528-2.5 0.58192-1.5306 1.7356-2.5 2.9752-2.5 2.5979 0 2.55-0.4448-0.47525-4.4111-1.375-1.8027-2.5-4.4727-2.5-5.9333 0-2.4834-0.29377-2.6556-4.5314-2.6556-5.5939 0-6.4745-1.6364-3.4843-6.4746 1.7735-2.8695 1.8956-3.5254 0.65627-3.5254-2.4237 0-2.9381-1.7895-2.7338-9.5091 0.15703-5.9328-0.1075-7.354-1.4658-7.8752-1.2199-0.46811-1.5516-1.542-1.2568-4.0679l0.40066-3.4319-4.5424 3.3525c-3.6156 2.6684-4.9505 4.5254-6.5424 9.1009-2.7368 7.8661-4.3024 8.6372-10.53 5.1861l-4.9525-2.7447-6.6675 4.081c-5.1624 3.1598-7.2525 3.9278-9.259 3.4026-1.4253-0.3731-6.5065-1.1637-11.292-1.7569-8.6603-1.0736-8.7184-1.0658-12.691 1.6874-4.9638 3.4399-6.5285 2.6815-5.7907-2.8066 0.5172-3.8473-0.84298-7.6182-2.748-7.6182-0.39027 0-2.6748 1.3521-5.0768 3.0047-3.418 2.3516-4.4352 3.71-4.6802 6.25l-0.31301 3.2453-7.4301-0.26765c-4.0865-0.14721-7.691 0.15461-8.01 0.67071-0.31897 0.51611-1.7004 1.2189-3.0699 1.5619-2.1725 0.544-2.806 0.0441-4.9691-3.9207-1.3635-2.4993-2.6587-4.5442-2.8783-4.5442s-2.776 1.3599-5.6812 3.022l-5.282 3.022-5.2669-6.5441-1.7063 2.2852c-0.93846 1.2569-2.3813 3.1874-3.2063 4.29-1.0531 1.4075-2.8309 2.0674-5.967 2.2148-4.2284 0.19878-4.4957 0.0513-5.005-2.7619-0.49747-2.7481-0.74471-2.445-3.283 4.0248-3.3304 8.4887-3.3534 9.045-0.43976 10.665 2.4339 1.3535 8.5276 7.8503 13.024 13.886l2.6851 3.6041-1.9946 9.3959c-3.3278 15.677-3.5446 16.287-10.434 29.344-5.5382 10.497-7.8266 13.734-13.522 19.13-6.7989 6.442-6.8464 6.5239-5.7882 9.9718l1.067 3.4764 2.2774-2.1395c3.4064-3.2002 4.9954-1.8928 5.6725 4.6674 0.78201 7.5769 1.7122 8.3733 6.5826 5.636 4.6786-2.6295 5.897-2.7052 8.3154-0.51666 1.7357 1.5708 2.0287 1.5402 4.7882-0.5 3.4719-2.5669 4.8712-2.7257 5.6702-0.64339 0.35847 0.93417 1.2869 1.3077 2.3845 0.95932 0.98858-0.31376 2.11-0.0648 2.4919 0.55329 0.43244 0.6997 1.402-0.0397 2.5695-1.9593 2.1374-3.5146 1.8586-3.452 8.0725-1.8138 3.4343 0.90543 5.4827 2.2582 7.75 5.1182 1.695 2.1381 3.0818 4.6506 3.0818 5.5833s0.33101 2.0268 0.73557 2.4314c0.40456 0.40456 0.80336 2.0836 0.88622 3.7311 0.0829 1.6476 1.1019 4.008 2.2644 5.2456 2.4597 2.6182 2.9036 9.0718 0.70999 10.321-0.77209 0.43983-1.5596 1.9096-1.75 3.2661-0.26947 1.9197-1.1775 2.6622-4.0962 3.3493-4.2946 1.0111-5.1227 3.8792-1.6558 5.7347 1.3783 0.73763 1.9231 1.7753 1.5936 3.0352-0.38816 1.4843 0.0267 1.9144 1.8467 1.9144 3.4163 0 3.8928 1.5925 1.9771 6.6086-2.1081 5.5201-1.4861 7.3914 2.4569 7.3914 2.7704 0 3.0314 0.28768 3.0314 3.3417 0 3.0323 0.37041 3.4527 4 4.5401 3.684 1.1038 4 1.477 4 4.7253 0 2.8661 1.4519 5.4135 7.75 13.597l7.75 10.071 0.54811-13.602c0.30147-7.4813 0.8535-13.908 1.2268-14.281z" />
                            <path id="9" class="regions"
                                d="m942 595.6c0-1.3222 1.8686-6.2983 4.1525-11.058 2.9908-6.2329 5.3428-9.6294 8.4071-12.14 2.34-1.9175 4.9942-5.0448 5.8981-6.9497 1.0072-2.1224 2.8924-3.9852 4.8692-4.8112 2.1581-0.90171 3.2817-2.0906 3.3948-3.5921 0.18603-2.4696 1.8988-4.0877 7.4704-7.058 2.0306-1.0825 4.8734-3.5171 6.3174-5.4102 1.4439-1.8931 8.1075-7.1446 14.808-11.67 41.746-28.196 41.12-27.816 48.353-29.337 3.782-0.79557 8.1034-1.913 9.6031-2.4832s2.7267-0.84279 2.7267-0.6058 1.8063-0.20639 4.0139-0.9853c2.2077-0.77891 4.3452-1.434 4.75-1.4556 0.7486-0.0401 0.889-0.54159 1.1111-3.968 0.069-1.0607 0.694-2.5232 1.3894-3.25 0.6954-0.72679 2.8325-3.3464 4.7491-5.8214s4.1733-5.175 5.0149-6c7.7748-7.6214 9.4558-11.868 5.5298-13.969-1.6251-0.86974-2.5582-2.2224-2.5582-3.7086 0-2.0339-0.244-2.1871-1.8675-1.1732-2.2975 1.4348-5.5427 0.36198-4.7955-1.5853 0.4256-1.1089-0.1797-1.3016-2.7691-0.88136-4.3763 0.71018-4.9645-0.59175-2.6775-5.9269 1.5813-3.689 1.7383-5.2966 0.944-9.6666-1.0461-5.7556-0.089-8.0892 3.3194-8.0892 2.4753 0 3.307-2.2705 1.8375-5.0162-0.9794-1.8301-0.8065-2.5516 1.0973-4.5781 2.5413-2.7051 4.5697-2.9804 7.2093-0.97856 1.5782 1.1968 2.4614 0.93285 7.227-2.1603 2.986-1.938 6.3243-5.0409 7.4184-6.8953l1.9892-3.3716h9.0338c5.4261 0 9.0337-0.40406 9.0337-1.0118 0-0.60896-1.4931-0.77349-3.75-0.41324-3.3585 0.53608-3.9897 0.27408-6.0458-2.5096-1.2627-1.7095-2.0762-3.4635-1.8078-3.8978 0.2684-0.43427 2.3089-1.6621 4.5343-2.7286 3.6993-1.7727 5.2755-3.439 3.2532-3.439-0.4489 0-2.1162-2.72-3.7051-6.0444-2.2373-4.681-2.8992-7.5048-2.9339-12.518l-0.045-6.4737-4.1088 0.61052c-3.0869 0.45867-5.1288 0.0901-8.2101-1.4818-5.2995-2.7036-5.6669-2.6676-10.307 1.0071-4.7775 3.7838-7.3496 3.3405-6.3746-1.0987 0.5702-2.5962 0.374-3.0008-1.4555-3.0008-3.5894 0-7.0443-2.9378-7.0443-5.99 0-5.5707-1.3416-9.1516-4.679-12.489-1.8791-1.8791-4.2535-3.6822-5.2764-4.0068-2.2938-0.72802-3.4691-3.4897-2.0774-4.8814 1.8531-1.8531 1.0998-3.226-2.9588-5.3923-3.618-1.9312-4.2497-1.9959-6.75-0.69169-3.4017 1.7745-7.3724 1.8593-9.4193 0.20123-0.8487-0.6875-4.1088-5.1226-7.2447-9.8558s-7.1838-10.042-8.9953-11.798c-2.9638-2.8726-3.5527-3.0743-5.8806-2.0136-1.9326 0.88055-4.4094 0.90822-9.7915 0.10941-3.9625-0.58812-7.5571-1.4344-7.9882-1.8806-0.43104-0.44619-0.15344-2.6113 0.61687-4.8113 1.7287-4.9372 1.1885-6.8339-2.7439-9.634-2.4945-1.7762-3.418-1.9685-4.9273-1.026-1.0215 0.63798-3.4384 1.16-5.3709 1.16-2.3652 0-3.5136 0.46318-3.5136 1.4171 0 0.77941-0.87371 3.4554-1.9416 5.9466-1.8405 4.2937-2.1138 4.5021-5.25 4.0035-2.7569-0.43836-3.8526 9e-3 -6.5726 2.686-4.061 3.996-4.6703 6.362-3.8663 15.013 0.55143 5.9331 0.31076 7.6003-1.7795 12.327-2.0293 4.5885-2.752 5.378-4.5036 4.92-1.7107-0.44735-2.0864-0.08835-2.0864 1.9935 0 2.05-0.72225 2.7745-3.75 3.7615-3.3762 1.1006-5.1918 2.4752-5.2347 3.9634-8e-3 0.29192 1.3606 1.0501 3.0422 1.6849 2.8226 1.0654 3.007 1.4234 2.4002 4.6578-0.4614 2.4594-0.1805 4.1844 0.94246 5.7877 3.3289 4.7527 0.53637 6.4089-5.5229 3.2755-4.947-2.5582-10.41-0.52627-11.136 4.1419-0.54713 3.5181-1.8185 4.4215-6.2224 4.4215-4.1157 0-4.4303 0.20252-5.5406 3.5669-0.9777 2.9625-0.92427 3.7674 0.31526 4.75 0.82085 0.65071 2.1381 2.4633 2.9273 4.0279 1.2846 2.5469 1.2389 3.2408-0.43605 6.6262-1.5196 3.0715-2.5528 3.892-5.5036 4.3709-2.7825 0.45153-4.9373 1.9986-9.2068 6.61-5.1643 5.5779-6.1622 6.1842-13.574 8.2467-5.5354 1.5403-9.1562 3.239-11.753 5.5138-2.0641 1.8082-4.5753 3.2876-5.5805 3.2876s-4.0107 1.1052-6.6788 2.456c-3.7607 1.9039-5.7898 2.3273-9.0271 1.8836l-4.176-0.57238-0.69122 6.1096c-0.75912 6.7097-0.44695 6.4655-9.5934 7.506-2.475 0.28157-6.3517 0.80842-8.6149 1.1708-3.8796 0.62113-4.1544 0.50147-4.8054-2.092-0.51844-2.0656-0.97554-2.4657-1.8351-1.6062-1.811 1.811-5.573 1.334-6.7446-0.85536-1.4364-2.6839-2.6525-2.5205-3.3998 0.45684-0.66114 2.6342-7.8927 6.5432-12.105 6.5432-1.2377 0-2.7478 0.59928-3.3557 1.3317s-3.7005 2.1751-6.8724 3.2059c-3.172 1.0308-6.6962 2.6815-7.8317 3.6683s-2.5097 1.7941-3.0538 1.7941-2.4719 1.5295-4.2838 3.399l-3.2944 3.399 3.0982 0.68049c3.3497 0.73572 3.9871 2.539 1.5982 4.5216-0.95816 0.7952-1.5 3.0068-1.5 6.1224v4.8776h3.941c5.509 0 6.083 1.757 1.718 5.2587-4.727 3.7921-4.7002 5.5344 0.0911 5.931 3.5683 0.29533 3.7765 0.509 4.2973 4.4106 0.33697 2.5243 1.8165 5.7027 3.8495 8.2695 3.7512 4.7363 3.5278 6.8954-0.80317 7.7616-1.6245 0.32489-3.9635 1.6658-5.1979 2.9797-1.2344 1.314-3.0659 2.389-4.07 2.389-2.3869 0-2.3574 1.6277 0.0409 2.2549 1.0538 0.27558 3.0408 2.9701 4.5631 6.1881 2.2716 4.8017 2.4896 5.9513 1.3834 7.2944-1.162 1.4109-0.99421 1.515 1.458 0.90416 2.182-0.54349 3.3013-0.16006 5.265 1.8037 2.0041 2.0041 2.957 2.3178 4.8519 1.5974 1.574-0.59844 2.7816-0.5052 3.6322 0.28046 1.0034 0.92683 1.1446 0.80838 0.66439-0.55731-0.40406-1.1492 0.38393-2.6673 2.3359-4.5 1.6201-1.5212 3.7833-5.2407 4.8071-8.2657 1.1686-3.453 2.5075-5.6261 3.5973-5.8388 0.95471-0.18633 3.9511 1.5292 6.6587 3.8122l4.9228 4.151 4.2468-2.3122c4.9928-2.7183 5.711-3.5446 5.7315-6.5936 0.0197-2.9374 2.5877-3.9156 4.8662-1.8536 1.0546 0.95444 2.1568 1.2452 2.7128 0.71551 0.51499-0.49066 2.7119-1.8763 4.8819-3.0791 3.7574-2.0827 4.0078-2.1019 5.25-0.40311 0.71743 0.98115 1.3044 2.8229 1.3044 4.0928 0 1.878 0.53808 2.3089 2.8836 2.3089 2.0956 0 3.8719 1.0939 6.5 4.0029 1.989 2.2016 3.6164 4.2784 3.6164 4.6151s-1.5329 2.0229-3.4064 3.7471c-4.6424 4.2723-4.8628 6.5422-0.88207 9.0842 3.8306 2.4461 3.906 2.8748 1.1794 6.7039-2.3853 3.3498-1.8208 4.9983 0.8758 2.5579 1.2176-1.102 2.245-1.3152 3.2561-0.67585 0.81244 0.51375 2.5038 0.94099 3.7585 0.94941 4.2447 0.0285 3.7609 2.896-1.3669 8.1022l-4.8586 4.9328 1.8658 3.4646c1.8934 3.5159 1.4508 5.3169-1.8954 7.7131-0.57075 0.40872-0.0287 1.7034 1.3097 3.128 2.0418 2.1734 2.2276 3.1316 1.755 9.0527-0.29069 3.6421-0.40022 6.622-0.24339 6.622 3.0246 0 12.6 2.5763 13.899 3.7394 3.9324 3.522 26.15 0.35725 29.1-4.1451 1.1748-1.793 2.1576-2.1339 5.1465-1.7849 3.3139 0.38688 3.6455 0.6868 3.2201 2.9121-0.43101 2.2547-0.21994 2.43 2.316 1.9228 3.0816-0.61631 4.4706 1.2066 3.4264 4.4967-0.40389 1.2724-0.09545 1.8591 0.97727 1.8591 0.98852 0 1.5674-0.88784 1.5674-2.404z" />
                            <path id="5" class="regions"
                                d="m650.48 475.35c1.1087-1.7345 3.3278-3.9415 4.9315-4.9044 2.8348-1.7023 2.9644-1.6907 4.6711 0.41696 0.96547 1.1923 1.9332 2.7014 2.1506 3.3535 0.26066 0.78198 2.3118 0.10406 6.024-1.9909l5.6287-3.1766 2.0549 2.6124c1.1302 1.4368 2.0618 2.8892 2.0702 3.2276 8e-3 0.33833 0.40531 1.2308 0.88197 1.9832 0.69777 1.1015 1.2924 0.94227 3.0516-0.81691 1.6114-1.6114 2.522-1.9052 3.4689-1.1193 0.70619 0.58608 3.3283 1.3458 5.827 1.6883 4.411 0.6046 4.5619 0.53668 5.1932-2.3379 0.48842-2.2238 2.2926-4.0482 7.2496-7.3309l6.5993-4.3703 2.8566 3.4217c1.6706 2.0012 2.8566 4.6026 2.8566 6.2662 0 2.7355 0.0847 2.7851 2.2116 1.2954 1.7312-1.2125 3.958-1.4326 10.25-1.0131 4.4211 0.29481 9.6543 1.0148 11.629 1.6 3.3266 0.98561 4.0889 0.76149 10.352-3.0436 3.7187-2.2592 7.2858-4.1076 7.9269-4.1076s2.8212 1.125 4.8447 2.5c4.7703 3.2415 5.4848 3.1568 6.3262-0.75 0.38494-1.7875 1.2938-4.4131 2.0197-5.8347 2.0018-3.9204 12.241-11.157 17.843-12.61 2.7186-0.70521 5.901-2.1822 7.0718-3.2821 1.1709-1.1 4.5394-2.4571 7.4856-3.0158 3.2084-0.60847 5.8807-1.7639 6.6632-2.8811 0.87568-1.2502 2.9555-2.0236 6.308-2.3458 6.0332-0.57971 8.0679-2.0747 8.0679-5.9278 0-2.1343 0.62799-3.1411 2.3733-3.8046 1.3053-0.49628 2.6534-2.0183 2.9957-3.3824 0.34235-1.364 0.89751-3.1968 1.2337-4.0729 0.45974-1.1981-0.0147-1.5929-1.9142-1.5929-1.795 0-3.1352-0.98666-4.6339-3.4116-1.1596-1.8764-3.4464-3.8779-5.0816-4.448-4.092-1.4265-3.7412-3.6814 1.4979-9.6281l4.3942-4.9876 2.3877 2.2431c2.0416 1.918 3.0718 2.1493 7.1079 1.5961 6.2738-0.85991 8.125-3.76 4.3412-6.8005-2.2272-1.7898-2.4675-2.5278-1.8108-5.5635 0.62567-2.8925 0.34146-4.0052-1.6374-6.4103-2.2109-2.6872-2.3425-3.4643-1.7172-10.137 0.37248-3.9749 0.3753-8.9653 6e-3 -11.09-0.63886-3.6778-1.0148-4.0062-7.8569-6.8627-5.4623-2.2804-7.5466-3.7211-8.6896-6.0063l-1.5036-3.0063-4.9964 1.1603c-2.748 0.63816-5.722 1.2035-6.6089 1.2563-3.1165 0.18554-9.3267-5.883-11.958-11.685-2.4528-5.4087-2.819-5.7394-8-7.225-5.1066-1.4642-5.6182-1.9102-8.6017-7.4994-3.9398-7.381-5.3275-8.0079-5.3275-2.4065 0 2.5313-0.66096 4.9849-1.6318 6.0577-1.5091 1.6675-1.5091 1.9904 0 4.2936 1.7351 2.6481 2.1657 5.5608 0.82203 5.5608-0.44537 0-3.1682 2.0131-6.0508 4.4736s-7.4614 5.4922-10.175 6.7372c-3.3719 1.5469-5.2291 3.1099-5.866 4.9368-0.78882 2.2628-1.7508 2.8079-6.2651 3.5503-3.3488 0.55067-6.352 0.5041-8.0712-0.12516-3.1388-1.1489-6.1835-6.6698-5.2683-9.5531 0.34584-1.0896-0.12861-3.059-1.0907-4.5274-1.6051-2.4498-2.024-2.5594-7.3029-1.9116-3.0801 0.37793-6.5192 1.0302-7.6425 1.4495-2.5047 0.93498-5.0152-0.35295-10.371-5.3205-4.4806-4.1558-5.1064-6.856-2.1715-9.369 1.5558-1.3322 1.6983-1.9869 0.75945-3.4902-1.6556-2.6511-2.2103-2.3856-5.7432 2.7493-1.8296 2.6592-4.5921 5.1962-6.548 6.0134-3.6336 1.5182-4.2883 3.3908-1.8833 5.3868 2.2027 1.8281 1.864 3.6106-1.6593 8.7316l-3.1593 4.5919 2.1593 1.7485c2.4888 2.0153 2.6716 3.6679 0.6944 6.2776-1.0824 1.4287-1.1504 2.4003-0.29912 4.2688 0.94493 2.0739 0.72975 2.8088-1.5079 5.1503-1.4356 1.5022-3.4795 4.6772-4.5422 7.0556-1.0626 2.3784-4.4325 6.8784-7.4887 10s-8.2883 8.6388-11.627 12.26c-5.8099 6.3025-6.0703 6.8087-6.0703 11.802 0 5.0967-0.16452 5.3911-7.1178 12.737l-7.1178 7.5201 5.3939 5.63-2.6173 8.2852c-2.3229 7.3533-2.5405 9.25-1.9342 16.863 0.48032 6.0318 1.2336 9.2854 2.5382 10.962 1.0203 1.3117 1.8551 3.9305 1.8551 5.8196 0 2.9834 0.29355 3.3927 2.2343 3.1151 1.2288-0.17575 3.1413-1.7387 4.25-3.4731z" />
                            <path id="12" class="regions"
                                d="m857.5 435.62c1.6968-0.2579 2.0758-1.2755 2.5-6.7135l0.5-6.4095 5.6994 0.30186c3.7217 0.19711 6.7407-0.23664 8.7003-1.25 1.6505-0.85352 4.1661-1.5519 5.5902-1.5519 1.6849 0 3.9104-1.3478 6.3719-3.859 3.1602-3.2241 5.2253-4.2402 12.551-6.1753 8.5289-2.2531 8.9205-2.4888 14.355-8.641 4.2624-4.825 6.2275-6.3247 8.2873-6.3247 5.1745 0 6.7723-6.2942 2.1772-8.576-3.4968-1.7364-3.5482-1.9567-1.7328-7.4225 1.8099-5.4492 3.9218-7.0015 9.5254-7.0015 2.5221 0 3.0527-0.52728 4.1219-4.0958 1.3703-4.5735 3.5985-5.9042 9.8868-5.9042 2.9335 0 3.7701-0.34761 3.3667-1.3988-0.29523-0.76937-0.0561-1.6959 0.53139-2.059 1.8983-1.1732 1.14-2.3674-1.9318-3.0421-1.65-0.3624-3-1.2432-3-1.9573 0-1.8911 2.8994-8.5298 3.7634-8.617 2.4022-0.24235 5.6908-1.0465 6.1475-1.5033 0.29282-0.29282 0.57148-1.9702 0.61925-3.7274 0.076-2.7943 0.411-3.1732 2.6716-3.0213 2.1746 0.1461 2.8354-0.48866 4.1643-4 1.2858-3.3976 1.3968-5.4468 0.59707-11.02-0.89182-6.2151-0.78263-7.2022 1.1836-10.701l2.166-3.8539-2.7492-3.6044c-2.3079-3.0258-2.6942-4.3934-2.4064-8.5201 0.34012-4.8771 0.32004-4.914-2.554-4.695-2.4295 0.18508-3.4738-0.57481-6.4742-4.7112-2.3947-3.3013-3.7513-6.4305-4.1032-9.4649-0.57003-4.9146-1.1709-5.4143-10.026-8.3379-2.475-0.81718-5.3512-1.8463-6.3916-2.2869-1.7314-0.73327-1.8298-0.46998-1.1621 3.1102 0.40121 2.1512 0.80241 4.3613 0.89155 4.9113 0.0915 0.56466-2.45 1.0429-5.8379 1.0985-3.3 0.0542-7.8 0.61419-10 1.2444-5.9236 1.6969-7.4409 1.4427-8.5329-1.4295-0.89464-2.3531-1.3344-2.5272-4.8726-1.9294-4.7363 0.80018-5.7142-0.38486-3.6354-4.405 2.1598-4.1765 2.0062-4.6006-2.1241-5.8683-4.0041-1.2289-5.335-0.88259-5.335 1.3881 0 0.81445-0.95057 3.3441-2.1124 5.6214l-2.1124 4.1406 2.6124 1.903c1.4368 1.0466 2.6124 2.2892 2.6124 2.7611 0 1.237-11.587 9.975-13.227 9.975-0.75843 0-3.1551-1.2882-5.326-2.8626-3.0365-2.2022-5.5275-3.0184-10.798-3.5382-8.1444-0.80318-9.6485 0.05017-9.6485 5.474 0 5.0896-1.9318 13.895-3.0829 14.052-0.50443 0.0688-1.4476 0.18125-2.0959 0.25-0.64831 0.0688-2.2993 4.7864-3.6689 10.484-2.4618 10.24-2.4689 10.398-0.62566 13.821 1.0255 1.9042 1.9935 5.5679 2.151 8.1414 0.3273 5.3453-2.7519 15.732-5.82 19.633-1.7231 2.1906-1.9236 3.4668-1.3829 8.8041 0.34776 3.4332 0.20851 8.4213-0.30943 11.085-0.8839 4.5452-0.76605 5.0426 1.9194 8.1011 2.4605 2.8023 2.7371 3.6909 1.9754 6.3466-0.79213 2.762-0.55883 3.34 2.2099 5.4752l3.0955 2.3872-2.971 4.4237c-1.634 2.433-3.4038 4.4457-3.9328 4.4726-0.52897 0.0269-3.1392 0.39798-5.8006 0.8246-3.9146 0.6275-5.1999 0.44887-6.7294-0.93523-1.7953-1.6247-1.9906-1.5907-3.8779 0.67541-2.3946 2.8752-2.544 3.8863-0.57425 3.8863 0.81382 0 2.398 1.8 3.5203 4 1.7903 3.5094 2.4675 4 5.5203 4 3.7644 0 4.0138 0.46495 2.4526 4.5714-1.8752 4.9322-2.0802 8.9002-0.56591 10.952 1.169 1.5841 1.7099 1.7282 2.7257 0.7263 2.0709-2.0426 4.8284-1.4296 6.0974 1.3556 1.2319 2.7038 2.5542 2.7981 14.27 1.0174z" />
                            <path id="10" class="regions"
                                d="m1126.8 403.91c6.0733-0.84644 9.0621-1.7807 10.983-3.4332 2.5882-2.2263 2.608-2.3361 1.0203-5.6655-1.4219-2.9818-1.4455-3.7264-0.194-6.115 1.3371-2.5519 1.2579-2.8637-1.2832-5.0494l-2.7082-2.3295 5.8004-4.285c5.6796-4.1958 5.9238-4.2721 11.723-3.666 4.9294 0.51522 6.3741 0.29126 8.6184-1.3362 1.483-1.0753 4.7214-2.4004 7.1964-2.9447 4.4401-0.97637 4.5582-0.93357 8.8729 3.2148 2.7633 2.6569 4.5424 3.7443 4.8333 2.9544 0.2697-0.73208 2.2239-1.25 4.7166-1.25 3.545 0 4.4425-0.40891 5.3711-2.4471 0.6133-1.3459 2.2706-3.2043 3.683-4.1297 2.3926-1.5677 3.0278-1.5711 9.2956-0.0503 4.5743 1.1099 11.208 1.634 20.728 1.6374 11.104 4e-3 15.345 0.41566 20.5 1.9898 8.0241 2.4501 10.178 2.4865 12.978 0.2192l2.1992-1.7808-2.5884-4.8681c-1.7273-3.2486-2.5884-6.4324-2.5884-9.57 0-4.1034 0.7-5.6775 5.5-12.367 3.025-4.216 5.4883-7.9956 5.4739-8.3992-0.014-0.40358-4.9034-4.2213-10.865-8.4838-8.5394-6.1061-11.577-7.75-14.319-7.75-4.0139 0-5.2605-1.1791-9.7571-9.2286l-3.2-5.7286 2.0833-2.9258c3.0327-4.259 2.6365-7.194-1.5081-11.171-4.0609-3.8966-10.408-15.666-10.408-19.299 0-1.3216 0.6934-4.0625 1.541-6.091 2.0765-4.9697 1.0904-13.816-2.0872-18.725-3.118-4.8168-3.8851-8.1566-2.4774-10.787 0.8484-1.5854 0.8583-3.0149 0.038-5.5002-0.8547-2.5897-0.7543-5.3984 0.4389-12.275 1.5352-8.8487 1.5279-8.9734-0.9532-16.268-1.3751-4.0431-2.5002-9.0025-2.5002-11.021 0-2.3841-0.6918-4.2959-1.9745-5.4567-3.2484-2.9397-3.3823-5.1888-0.5314-8.9264 2.0639-2.7059 2.3351-3.6101 1.2793-4.2653-1.9966-1.2389-7.7734-9.5127-7.7734-11.133 0-0.77438 1.3439-3.3992 2.9864-5.8329l2.9863-4.425-2.5514-0.97006c-1.4032-0.53354-3.0492-0.97007-3.6576-0.97007-1.4891 0-4.7636-3.0368-4.7636-4.4179 0-0.60934-1.0125-1.5521-2.25-2.095s-2.384-1.9246-2.5477-3.0705c-0.2232-1.562-1.0211-2.0833-3.1884-2.0833-4.1187 0-14.014-7.6025-14.014-10.767 0-2.6327-4.5049-5.8563-9.5253-6.816-2.3774-0.45447-3.6628 0.0903-6.7106 2.8437-4.0442 3.6537-7.4697 4.2785-12.449 2.2708-1.5483-0.62432-5.7401-1.0641-9.3151-0.97728l-6.5 0.15785-2.6704-4.2727c-1.4688-2.35-3.1963-7.1967-3.8389-10.77-1.6671-9.2701-2.3469-9.9767-4.4074-4.5813-1.3438 3.5188-2.2575 4.579-3.946 4.579-1.2084 0-3.648 1.5381-5.4211 3.418-2.3035 2.4422-4.6643 3.7269-8.2693 4.5-8.0652 1.7297-10.982 1.2761-18.935-2.9443-4.1183-2.1855-8.2196-3.9737-9.114-3.9737-0.8945 0-2.8125 1.7117-4.2623 3.8038-2.4601 3.5499-2.636 4.5424-2.636 14.87v11.066l4.75 1.1937c2.6125 0.65654 6.1 1.5171 7.75 1.9124 2.7479 0.65834 3.0257 1.117 3.3057 5.4581 0.2472 3.8322-0.3234 5.8836-2.9808 10.718-2.0431 3.7163-3.9379 5.9782-5.008 5.9782-1.4132 0-1.7748 1.1191-2.0192 6.25-0.2895 6.0776-0.3861 6.276-3.505 7.1948-3.1038 0.91427-3.2168 1.1404-3.5 7-0.2617 5.4132-0.5313 6.0891-2.5427 6.3748-3.1561 0.44824-2.8136 1.6488 1.3838 4.8503l3.6337 2.7716-0.5573 9.0293c-0.5942 9.6276-2.9358 16.56-7.4024 21.915-1.7861 2.1413-2.2506 3.5981-1.8006 5.647 0.5315 2.4201 0.043 3.1657-3.9504 6.0338-2.5062 1.7999-4.5568 3.59-4.5568 3.978s-0.2812 2.1117-0.625 3.8305c-0.5276 2.6382-0.3237 3.125 1.3089 3.125 1.0636 0 2.8073 0.64436 3.875 1.4319 1.7439 1.2864 2.2165 1.1697 4.6518-1.1495l2.7108-2.5814 1.7892 3.1431c2.1092 3.7052 2.4153 3.779 4.7893 1.1558 1.7673-1.9528 1.7466-2-0.8764-2-1.4775 0-2.9319-0.63984-3.232-1.4219-0.8168-2.1287 1.0873-5.0359 8.6177-13.157 3.7499-4.0442 8.2831-9.3933 10.074-11.887 1.7908-2.4936 3.9521-4.5338 4.803-4.5338 3.379 0 9.9246 3.289 10.928 5.4911 0.7771 1.7055 2.1833 2.476 5.3694 2.9421 17.09 2.5002 19.817 3.3652 19.817 6.2859 0 1.8526-11.338 14.34-14.584 16.063-3.6081 1.9147-3.0244 3.8711 1.2026 4.03 5.015 0.18854 10.867 4.933 18.115 14.688 9.2915 12.504 9.8582 13.512 8.1872 14.57-0.7815 0.49464-3.3334 0.90624-5.6709 0.91466-3.5782 0.0129-4.25 0.33144-4.25 2.0153 0 1.7909-0.6667 2-6.3779 2-3.5459 0-8.0541 0.70037-10.153 1.5773-7.3585 3.0746-14.108 3.5519-19.775 1.3985l-5.1275-1.9483-3.3687 3.2362c-1.8528 1.7799-4.0728 4.5862-4.9334 6.2362-0.8605 1.65-3.737 5.441-6.3922 8.4245-2.6909 3.0236-5.5159 7.4967-6.3827 10.106-0.8553 2.575-2.6654 5.725-4.0225 6.9998-3.1804 2.9879-3.1358 6.6622 0.1223 10.063 2.2384 2.3363 2.4278 3.0053 1.3968 4.9319-1.2918 2.4138-1.5768 2.0481 4.706 6.0388 2.4046 1.5273 3.5768 3.35 4.75 7.3857 0.8567 2.9469 1.5576 6.4751 1.5576 7.8405 0 2.1756 0.5563 2.566 4.5 3.1574 3.618 0.54255 4.5 1.069 4.5 2.686 0 1.9158 0.1506 1.8933 3.1731-0.47212l3.1731-2.4833 10.728 6.0546 7.3702-2.6165c4.0536-1.439 7.8618-2.6165 8.4628-2.6165 2.0193 0 1.1167 2.6028-1.9074 5.5-2.5868 2.4783-3 3.5967-3 8.1198 0 6.8529 3.4582 13.849 7.9968 16.178 1.825 0.93644 3.3212 2.1526 3.3249 2.7026 0 0.55-2.551 2.3788-5.6769 4.064-5.6882 3.0666-6.4831 4.947-1.693 4.0052 1.4015-0.27557 6.303-1.0243 10.892-1.6639z" />
                            <path id="6" class="regions"
                                d="m838.86 347.5c1.3394-2.8112 2.6039-7.698 2.8887-11.163 0.42683-5.1932 0.17134-6.6339-1.6234-9.1544-2.6444-3.7136-2.6197-4.2882 0.73702-17.182 2.1583-8.2908 3.1888-10.704 4.898-11.472 2.3747-1.0663 4.2351-7.5078 4.2351-14.664 0-3.568 0.34897-4.1694 3-5.17 2.6141-0.98672 3-1.6251 3-4.9628 0-2.1067 0.70772-5.199 1.5727-6.8717 1.4303-2.7658 1.4303-3.1986 0-4.7791-1.9681-2.1747-1.987-3.4916-0.0727-5.0803 2.2187-1.8414 1.8426-2.7658-1.4165-3.4816-1.604-0.35231-3.1536-1.2586-3.4435-2.0139-0.41759-1.0882-1.131-1.1454-3.4361-0.27532-3.8369 1.4483-5.8936 0.53548-6.5604-2.9115-2.9648-15.328-2.4787-14.178-6.4585-15.283-6.4062-1.7793-7.8123-1.3356-12.179 3.8427-4.916 5.8301-14.264 10.913-22.086 12.008-4.4519 0.62335-16.179 5.5676-23.92 10.085-0.825 0.48142-3.839 2.2415-6.6978 3.9113-3.0382 1.7746-7.4007 3.3121-10.5 3.7006-4.6957 0.58858-5.8742 1.2542-10.302 5.8185-7.8336 8.0747-7.9457 8.1305-11.398 5.6722l-2.9686-2.1138-2.4409 3.2706c-1.3425 1.7988-3.2042 5.6196-4.137 8.4906-1.7192 5.291-3.1418 6.8034-23.909 25.417l-4.0572 3.6365 5.7498 7.5384-5.7622 4.9564 3.9615 3.4147c3.6007 3.1037 4.3713 3.3605 8.4614 2.8201 14.185-1.8743 13.23-2.0119 16.245 2.3397 2.0431 2.9494 2.5971 4.6996 2.1675 6.8473-0.39978 1.9989 3e-3 3.6019 1.3122 5.2183 1.6532 2.0416 2.421 2.2433 6.1446 1.6142 3.0743-0.5194 4.4867-1.3275 5.089-2.9116 1.0645-2.8 2.8065-4.1585 6.4517-5.0318 1.6004-0.38337 5.6504-2.7978 9-5.3653 3.3496-2.5676 6.2833-4.7965 6.5192-4.9532 0.23593-0.15668-0.55157-1.6111-1.75-3.2321-2.6571-3.5939-2.6891-4.2565-0.30989-6.4097 1.028-0.93032 2.1365-3.6428 2.4634-6.0276 0.75138-5.4819 2.6002-9.5908 4.3155-9.5908 1.5184 0 5.9955 6.7064 8.1796 12.253 1.4664 3.7237 3.1452 4.6589 10.644 5.9297 1.2904 0.21868 3.063 2.5769 5.131 6.8259 1.7377 3.5705 4.3453 7.5896 5.7946 8.9313l2.6352 2.4395 7.0682-1.6964c3.8875-0.933 7.4474-1.462 7.9109-1.1755 0.46349 0.28646 1.3445 2.0411 1.9577 3.8993 0.87271 2.6443 2.138 3.8237 5.823 5.4274 7.2028 3.1347 9.2616 2.5662 12.073-3.3337z" />
                            <path id="1" class="regions"
                                d="m1049.4 328.75c2.2578-2.8801 2.7782-3.1068 4.5724-1.9915 1.6437 1.0216 2.0247 1.0115 2.0247-0.0538 0-0.72177 1.575-2.8389 3.5-4.7046s3.5-4.2336 3.5-5.2619c0-2.5129 1.7064-5.5426 5.1833-9.2031 1.5856-1.6692 4.4053-5.285 6.2662-8.035s5.3722-6.9338 7.803-9.2973l4.4196-4.2973 5.8944 2.2054c6.229 2.3306 6.4373 2.3192 19.934-1.0842 2.2-0.55478 6.0904-1.2582 8.6453-1.5632 3.4298-0.40944 4.7744-1.0484 5.1388-2.442 0.344-1.3154 1.5699-1.9835 4.045-2.2044l3.5513-0.31699-3.8985-5.9152c-7.2684-11.028-14.229-16.585-20.776-16.585-3.1525 0-4.2059-1.3916-4.2059-5.5561 0-1.6174 0.9208-2.8687 2.8421-3.8622 2.6254-1.3576 13.301-11.672 13.936-13.464 0.1528-0.43149-0.3434-0.78453-1.1027-0.78453-1.3543 0-15.062-2.0583-19.358-2.9068-1.2009-0.23715-2.4703-1.3351-2.8209-2.4398-0.6001-1.8908-5.1945-4.9867-7.4003-4.9867-0.5522 0-2.5924 2.0804-4.5339 4.6232-1.9416 2.5427-6.2372 7.5012-9.5459 11.019-3.3086 3.5176-6.0157 6.8372-6.0157 7.3768s1.35 0.98122 3 0.98122c4.3914 0 3.8577 2.5605-1.7124 8.2156-4.6692 4.7405-7.2876 5.9924-7.2876 3.4844 0-2.7966-2.0951-3.5512-5.1297-1.8475-2.814 1.5797-3.0054 1.5577-4.6525-0.53467-0.9448-1.2002-3.0678-2.3252-4.7178-2.5-2.9794-0.31563-2.9985-0.35734-2.7779-6.0814 0.2704-7.0163 1.528-9.579 5.3554-10.913 2.4497-0.85399 2.9225-1.5636 2.9225-4.3862 0-2.3008 0.9318-4.4287 2.9421-6.7182 4.4765-5.0985 7.0579-12.657 7.0579-20.667 0-6.4439-0.192-7.0356-3.0676-9.4553-3.0474-2.5642-5.0174-6.9532-4.1951-9.3466 0.2361-0.6875 1.4944-1.25 2.7961-1.25 2.1781 0 2.3907-0.45585 2.6667-5.7168 0.2885-5.4993 0.4235-5.752 3.5499-6.6447 3.1742-0.90626 3.25-1.0599 3.25-6.5832 0-5.8969 1.1349-8.0553 4.2355-8.0553 2.1142 0 5.7645-7.5208 5.7645-11.877 0-2.4968-0.4708-2.8153-5.9631-4.0337-5.7845-1.2833-6.0969-1.2445-10.428 1.2937-2.8014 1.6417-5.955 2.6166-8.4643 2.6166-3.1295 0-4.4501 0.57308-6.0719 2.6349-1.1399 1.4492-2.0726 3.2117-2.0726 3.9167 0 1.712-1.3435 2.0639-6.1353 1.607-2.9603-0.28232-4.2326-0.94305-4.8534-2.5206-0.7878-2.0017-0.9167-1.9312-2.0227 1.1079-0.8754 2.4053-2.3546 3.7648-5.7121 5.25-5.5884 2.472-6.4861 2.4555-18.276-0.33564-9.2771-2.1962-9.5469-2.3418-11.5-6.2046-1.1-2.1756-2.1037-4.1343-2.2304-4.3527-0.12674-0.21841-3.1737 0.56909-6.771 1.75-3.5973 1.1809-8.3311 2.1471-10.52 2.1471h-3.979v17.971l-6.1122 3.0854c-3.593 1.8137-6.3244 3.897-6.6271 5.0544-0.60576 2.3164-4.3487 4.4396-9.5453 5.4145-2.0435 0.38336-3.7154 1.0087-3.7154 1.3896s1.6121 2.2433 3.5824 4.1387c3.4238 3.2936 3.5538 3.6664 2.9357 8.4212-0.35569 2.7362-1.0984 5.5193-1.6505 6.1845-2.2232 2.6788-9.5036 5.5702-14.026 5.5707-6.0433 6.5e-4 -7.1828-0.77244-6.4606-4.3834 0.49636-2.4818 0.19134-3.0591-2.0705-3.9191-3.6176-1.3754-5.168-0.88922-7.0409 2.208-1.4998 2.4802-1.4638 2.8013 0.60942 5.437 1.2118 1.5406 4.4349 4.3039 7.1624 6.1407s4.959 3.6751 4.959 4.0852c0 0.41008-1.4307 2.4813-3.1794 4.6028l-3.1794 3.8572 6.5764 5.3674-3.1856 6.3107c-2.7907 5.5284-3.1012 6.9244-2.5047 11.262 0.58747 4.2714 0.37914 5.2723-1.5177 7.2914l-2.1986 2.3403 2.3609 1.5469c2.895 1.8968 10.454 4.623 12.819 4.623 3.4176 0 7.0091 3.9007 7.0091 7.6124 0 3.7374 2.8551 10.192 5.4668 12.36 1.2744 1.0577 2.3863 1.1013 5.2977 0.20775 4.9356-1.5148 6.3227-0.13969 3.9788 3.9442-2.4284 4.2312-2.1783 7.6166 0.85667 11.596 2.5132 3.295 2.6653 3.3498 4.5545 1.6401 1.075-0.97281 3.1547-1.7893 4.6216-1.8144 2.2172-0.038 2.937-0.77628 4.2667-4.3762 1.8462-4.9984 3.2445-6.133 7.5847-6.1542 1.7202-8e-3 3.6878-0.69031 4.3724-1.5153 1.672-2.0147 3.5288-1.8886 6.2424 0.424 1.2417 1.0582 3.2867 2.7457 4.5444 3.75 1.76 1.4053 2.3071 2.896 2.3747 6.4697 0.048 2.554-0.2019 5.1127-0.5562 5.6858-0.3918 0.63395 1.121 1.3068 3.8625 1.7179 4.0884 0.61309 6.6612 0.35915 11.785-1.1632 2.7766-0.825 13.691 11.704 18.631 21.389 2.8178 5.5232 6.6739 5.8813 10.519 0.97691z" />
                            <path id="2" class="regions"
                                d="m883.22 281.34 3.832-2.6555-4.3301-4.6092 2.6368-5.3895c1.4503-2.9642 2.6369-6.0462 2.6369-6.8487s0.5625-2.2198 1.25-3.1494c1.171-1.5834 1.661-1.573 7.75 0.16375 5.7358 1.636 6.4886 2.1356 6.4027 4.2491-0.0535 1.3173-0.46501 3.25-0.91443 4.2948-0.71659 1.666-0.49307 1.8183 1.8168 1.2386 2.5142-0.63103 3.4158 0.10948 6.2363 5.1224 0.0807 0.14337 2.1294-0.44921 4.5527-1.3168 2.6981-0.96603 5.8596-1.3532 8.156-0.99886 4.3945 0.67812 4.7245-0.4316 2.2302-7.5012-1.5122-4.2861-1.5051-4.323 1.422-7.3757 2.6159-2.7282 2.8829-3.5748 2.4096-7.6419-0.40163-3.4518-0.0145-5.5886 1.578-8.7102 2.7236-5.3388 2.6585-6.8106-0.3898-8.808-3.1726-2.0787-3.1506-3.8662 0.0997-8.1276l2.5997-3.4084-6.0997-5.5199c-7.0838-6.4104-7.4614-8.1723-3.0549-14.255 3.2576-4.497 3.3972-4.5169 10.705-1.5281 2.6661 1.0904 3.25 1.8774 3.25 4.3802 0 2.7258 0.32093 3.051 3.0109 3.051 1.656 0 4.9863-0.92853 7.4006-2.0634 3.6435-1.7127 4.5019-2.6614 5.0497-5.5815 0.60018-3.1992 0.24015-3.967-3.9726-8.4707-2.5479-2.7239-4.4511-5.4998-4.2292-6.1685 0.22185-0.66874 3.1543-1.9614 6.5165-2.8726 3.3622-0.91118 6.5881-1.9502 7.1686-2.309 1.9932-1.2319 1.0398-4.036-1.9509-5.7378-2.2548-1.2831-2.9937-2.4257-2.9937-4.6298 0-2.3769-1.0321-3.7056-5.4973-7.0769-6.8474-5.1698-7.8665-5.5613-9.465-3.6353-1.8272 2.2016-3.755 1.9192-6.648-0.97377-2.308-2.308-2.5003-3.1676-2.2936-10.249l0.2257-7.7298-5.3698-0.77279c-2.9534-0.42503-6.4612-1.4879-7.7952-2.362-1.5095-0.98906-5.05-1.6749-9.3756-1.8162l-6.9503-0.22699-5.4554 11.588c-11.813 25.093-14.641 30.547-29.336 56.588-6.4409 11.414-8.6253 14.253-13.851 18-3.0679 2.2-7.0808 5.2976-8.9175 6.8836-3.012 2.6007-3.1924 3.0461-1.8398 4.5408 0.8249 0.91151 1.986 4.1139 2.5803 7.1164 2.1963 11.097 1.6861 10.238 5.6427 9.4961 2.9251-0.54875 3.8245-0.29804 5.0118 1.3971 0.94315 1.3465 2.5589 2.0712 4.6403 2.0812 5.6003 0.0269 6.433 1.3262 3.399 5.3039-2.2956 3.0097-2.4548 3.6442-1.1797 4.7024 1.8558 1.5402 1.8344 3.9178-0.0578 6.4195-0.80751 1.0676-1.4682 3.8359-1.4682 6.1518v4.2107l5.8323 0.67691c3.9288 0.45598 6.7846 1.4554 8.75 3.0622 3.6904 3.017 4.0131 3.0096 8.6421-0.1983z" />
                            <path id="11" class="regions"
                                d="m967.85 170.33 3.8656-1.6747-0.0737-8.0753c-0.0405-4.4414 0.22737-9.2003 0.59529-10.575 0.64173-2.3982 0.94555-2.4872 7.465-2.1854 5.5919 0.25889 7.8336-0.14585 12.652-2.2843 6.8177-3.0257 7.886-2.5972 9.4993 3.8098l1.0777 4.2796 6.7834 1.2742c3.7309 0.70079 8.2888 1.7354 10.129 2.2991 5.0846 1.5578 10.241-1.1323 12.981-6.7724 2.4222-4.9856 4.3183-5.5934 5.9917-1.9206 1.7163 3.7667 5.5032 3.3343 8.1383-0.92929 2.4406-3.949 3.4159-4.4536 7.5437-3.9029 1.9107 0.25489 4.4524-0.43823 7-1.9089 4.7062-2.7168 5.0068-4.0132 4.016-17.322-0.6549-8.7975-0.7752-9.1434-4.1043-11.8l-3.4296-2.7366-12.212 4.6465c-11.748 4.4697-12.489 4.6206-19.48 3.9655-4.4463-0.41665-12.187-2.375-19.939-5.0446l-12.671-4.3636-11.098-11.302c-18.355-18.692-22.07-24.314-21.37-32.336 0.23854-2.7313 0.74845-5.6539 1.1331-6.4947 0.38469-0.84078 0.0676-2.3928-0.7047-3.449-1.2992-1.7768-1.719-1.8164-5.6172-0.52985-2.581 0.8518-5.2193 2.7097-6.8106 4.796-2.3963 3.1417-2.9046 3.3564-6.5616 2.7717-3.4052-0.54452-4.357-0.25747-6.7508 2.0359-2.6643 2.5525-2.9934 2.6178-7.4915 1.4852-2.6058-0.65616-5.0864-0.80311-5.5601-0.32939-0.47041 0.47041-1.495 3.3062-2.2769 6.3017-4.3682 16.736-5.9046 21.881-7.7476 25.946-1.1221 2.475-2.6126 6.525-3.3122 9s-2.6759 8.2986-4.3918 12.941c-1.7158 4.6428-3.1197 8.7392-3.1197 9.1033 0 0.36402 3.4412 0.95275 7.6472 1.3083 4.2059 0.35553 8.3017 1.1897 9.1018 1.8536 0.80004 0.66398 4.6713 1.7018 8.6028 2.3062 6.2131 0.95521 7.11 1.3533 6.856 3.0431-1.4297 9.5122-1.3608 15.072 0.20296 16.37 1.4187 1.1774 1.9567 1.0783 3.8686-0.71262l2.2207-2.0802 8.25 6.097c7.329 5.4164 8.25 6.4524 8.25 9.2806 0 2.3134 0.61506 3.4802 2.25 4.2688 3.1348 1.5119 4.1926 1.456 8.6018-0.45419z" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>

    </section>

    {{-- @include('v2.chatBot') --}}

    <script>
        (function($) {

            let defaults = {

                data: [
                    {id:11,name:'Tanger-Tétouan-Al Hoceima',arname:'طنجة - تطوان'},
                    {id:2,name:"Rabat-Salé-Kénitra",arname:'الرباط - سلا - القنيطرة'},
                    {id:1,name:'Fès-Meknès',arname:'جهة فاس مكناس'},
                    {id:6,name:'Casablanca-Settat',arname:'الدار البيضاء - سطات'},
                    {id:12,name:"Béni Mellal-Khénifra",arname:'بني ملال خنيفرة'},
                    {id:9,name:'Drâa-Tafilalet',arname:'درا-تافيلالت'},
                    {id:5,name:"Marrakech-Safi",arname:'مراكش - أسفي'},
                    {id:8,name:'Souss-Massa',arname:'سوس ماسة'},
                    {id:7,name:'Guelmim-Oued Noun',arname:'كلميم-واد نون'},
                    {id:10,name:'Oriental',arname:'الجهة الشرقية'},
                    {id:4,name:'Laâyoune-Sakia El Hamra',arname:'العيون - الساقية الحمراء'},
                    {id:3,name:'Dakhla-Oued Ed-Dahab',arname:'وادي الذهب لكويرة'}
                ],
                id: 1,
                selectedLanguage : '{{ Session::get("lang") }}',
                multilistType :'{{ $multilistType }}'
            };


            let methods = {

                init: function() {

                       methods.handler();

                },


                handler:function(){


                    console.log("types",multilistType);


                        defaults.data.forEach(item => {

                               let temp = '';
                                    temp = `
                                        <li style="display:flex; flex-direction:row">
                                            <i class="fa-solid fa-location-dot me-1"></i>
                                            <a href="/${multilistType}/list?search=&region=${item.id}" class="items-regions" data-id="${item.id}">${item.name}</a>
                                        </li>
                                        `;

                                if(defaults.selectedLanguage === 'ar'){

                                     temp = `
                                     <li style="display:flex; flex-direction:row">
                                        <i class="fa-solid fa-location-dot me-1"></i>
                                        <a href="/${multilistType}/list?search=&region=${item.id}" class="items-regions" data-id="${item.id}">${item.arname}</a>
                                    </li>
                                    `;
                                }

                               $('#list').append(temp);

                        });

                                    $('.items-regions').mouseenter(function() {

                                    let id = $(this).attr('data-id');
                                    console.log("id in the list items", id);
                                    $(`#${id}`).css({
                                        'fill': 'var(--primary-color)',
                                        'opacity': "1"
                                    });
                                    }).mouseleave(function() {
                                    let id = $(this).attr('data-id');
                                    $(`#${id}`).css('fill', '#57585a');

                                    })


                                     $('.map_svg .regions').mouseenter(function() {

                                    let id = $(this).attr('id');

                                    $(`#list li a[data-id=${id}]`).css({
                                        'color': 'var(--primary-color)',
                                        'font-weight': 'bold'
                                    });

                                }).mouseleave(function() {

                                    let id = $(this).attr('id');
                                    $(`#list li a[data-id=${id}]`).css({
                                        'color': '#57585a',
                                        'font-weight': 'bold'
                                    });

                                })

                                $('.map_svg .regions').click(function() {

                                    let id = $(this).attr('id');
                                    window.location.href =
                                        `${multilistType}/list?search=&region=${id}`;
                                })
                }
            }

            $.fn.Map = function(options) {
                var t = [];
                if (methods[options]) {
                    return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
                } else if (typeof options === "object" || !options) {
                    return methods.init.apply(this, arguments);
                }
            };

        })(jQuery);
    </script>

    <script>
     $( window ).on( "load", function() {
              $().Map('init');
     });
        $(document).ready(function() {

            function setBackground(container,image){

                $(container).css('background',image);
                $(container).css('background-repeat','no-repeat');
                $(container).css('background-size','cover');
                $(container).css('background-position','center');
            }

            let pathname = window.location.pathname.replace('/','');

            $('.filter-section').css('background',`linear-gradient(6deg, rgba(255, 255, 255, 0), rgb(33 37 41 / 36%)) center center / cover no-repeat,url(images/bg.webp)`);

            if(pathname === 'primelist'){
                  setBackground('.filter-section',`linear-gradient(6deg, rgba(255, 255, 255, 0), rgb(33 37 41 / 36%)) center center / cover no-repeat,url(images/prime.png)`);
            }

            if(pathname === 'booklist'){
                  setBackground('.filter-section',`linear-gradient(6deg, rgba(255, 255, 255, 0), rgb(33 37 41 / 36%)) center center / cover no-repeat,url(images/book1.png)`);
            }

            /*A suivre*/

        })
    </script>

    <script>
        console.log('page', window.pageYOffset);

        const element = document.querySelectorAll('.grid-container a');

        element.forEach(item => {

            item.addEventListener('click', bgenScroll);
        });

        function bgenScroll(e) {

            e.target.style.zIndex = '-10 !important';


            if (window.pageYOffset === 0) {
                st = window.pageYOffset;
            }
            if (document.body.scrollWidth != null) {
                if (document.body.scrollTop) {
                    st = document.body.scrollTop;
                }

            }
            setTimeout('window.scroll(0,st)', 10);
        }
    </script>

    <style>
        footer{
            margin-top: 0 !important;
        }
        .map {


            background-position: 10% 20%;
            background-repeat: no-repeat;
            background-image: url('{{ url('images/carte-bg.webp') }}');
            background-size: cover;
            background-color: #fff;
            background-blend-mode: multiply;



        }

        .section-heading {

            margin: 24px 0 0 24px;
        }

        .map-container {


            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;



        }


        .map_svg {

            width: 350px;
            height: 350px;
            opacity: 1;
        }

        .map-section {

            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
            align-items: center;
            width: 60%;
        }


        .region {

            display: flex;
            justify-content: center;
            align-items: center;
            width: 70%;

        }

        .region ul {

            display: grid;
            grid-template-columns: 50% 50%;
            flex: 1;

        }



        .region ul li {

            padding: 10px 15px 10px 15px;


        }

        .region ul li a {

            line-height: 17px;
            font-family: 'Roboto Regular';
            font-size: 14px;
            text-decoration: none;
            border-radius: 50px;
            padding: 5px 10px;
            font-family: 'Montserrat', sans-serif !important;
            color: #57585a;
            display: block;
            /* text-align: center; */
            padding: 0;
            font-weight: 700;
            letter-spacing: 1.1px;
        }


        .regions {

            cursor: pointer;
            fill: #57585a;
        }

        .regions:hover {

            fill: var(--secondary-color) !important;

        }

        .region ul li a:hover {

            color: var(--primary-color) !important;
        }

        @media screen and (max-width: 1304px) {

            .map-section {

                width: 70%;
            }
        }

        @media screen and (max-width: 1144px) {

            .map-section {

                width: 75%;
            }
        }

        @media screen and (max-width: 1062px) {

            .map-section {

                width: 80%;
            }
        }

        @media screen and (max-width: 999px) {

            .map-section {

                width: 90%;
                margin: 0 20px 0 20px;
            }
        }

        @media screen and (max-width: 850px) {

            .map-section {

                display: flex;
                flex-direction: column-reverse;
                justify-content: center;
                align-items: center;
            }
        }

        @media screen and (max-width: 850px) {


            .region {

              width: 100%;
                display: flex;
                flex-direction: initial;
            }

            .region ul {

                    display: grid;
                    width: 100%;
                    justify-content: center;
                    align-items: center;
                    grid-template-columns: repeat(2,1fr);
            }

            .region ul li a {

                 font-size:11px;
                 text-align: left;
                 font-weight: 600 !important;
                 letter-spacing: 0;
            }

            .map_svg {

                width: 450px;
                height: 450px;
            }
        }

        @media screen and (max-width: 768px) {



            .map_svg {

                width: 350px;
                height: 350px;
            }
        }

        @media screen and (max-width: 500px) {



        }
    </style>

    {{-- end section --}}

    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

    <!-- Demo styles -->
    <style>
        .swiper {
            width: 100%;
            height: 300px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: transparent;
            color: #fff;
            letter-spacing: 2px;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }

        .swiper-wrapper {
            -webkit-transition-timing-function: linear !important;
            -o-transition-timing-function: linear !important;
            transition-timing-function: linear !important;
        }

        /* loazy loading */

        .card-image {
            display: block;
            /* min-height: 20rem; layout hack */
            background: #fff center center no-repeat;
            background-size: cover;
            filter: blur(2px); /* blur the lowres image */
            }
            .card-image > img {
            display: block;
            width: 100%;
            opacity: 0; /* visually hide the img element */
            }
            .card-image.is-loaded {
            filter: none; /* remove the blur on fullres image */
            transition: filter 1s;
        }
    </style>

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
  AOS.init();
</script>

<script>
    window.addEventListener('load', function() {


  // setTimeout to simulate the delay from a real page load
  setTimeout(lazyLoad, 1500);

});
function lazyLoad() {
  var card_images = document.querySelectorAll('.card-image');

  // loop over each card image
  card_images.forEach(function(card_image) {
    var image_url = card_image.getAttribute('data-image-full');
    var content_image = card_image.querySelector('img');

    // change the src of the content image to load the new high res photo
    content_image.src = image_url;

    // listen for load event when the new photo is finished loading
    content_image.addEventListener('load', function() {
      // swap out the visible background image with the new fully downloaded photo
      card_image.style.backgroundImage = 'url(' + image_url + ')';
      // add a class to remove the blur filter to smoothly transition the image change
      card_image.className = card_image.className + ' is-loaded';
    });

  });

}


</script>
@endsection

@section('custom_foot')
@endsection

{{-- Old image div by youssef no loazi loading here --}}

{{-- <div class="img " style="background: url('${thumbnail}')"></div> --}}

{{-- end --}}
