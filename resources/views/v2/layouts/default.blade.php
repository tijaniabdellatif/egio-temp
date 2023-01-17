
<?php


$seo_of_this_page = [];

if ($multilistType == '') {
    $multilistType = 'multilist';
}

// convert $seo to object
$seo = json_decode($seo);

if ($seo) {
    // loop through seo
    foreach ($seo as $key => $value) {
        if ($value->name == $multilistType) {
            $seo_of_this_page = $value;
        }
    }
}

?>
<!doctype html>
<html lang="{{  Session::get('lang')??'fr' }}">

<head>

    <title> @yield('title') </title>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon.ico')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.ico')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico')}}">
     <style>
        @import url('https://fonts.googleapis.com/css2?family=Lateef:wght@200;300;400;500;600;700&display=swap');
     </style>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>


    <script>


        window.addEventListener('DOMContentLoaded', (e) => {
         document.querySelector('body').setAttribute('style', 'color:black');
         const lgLocal = '{{ Session::get('lang') }}';

             if(lgLocal === 'ar'){

                 document.querySelector('body').setAttribute('style',"font-family: 'Lateef', serif; !important");
             }

        })
    </script>

    <!-- Use transtale in javascript -->
    <script>
        window._locale = '{{ app()->getLocale() }}';
        window._translations = {!! cache('translations') !!};
    </script>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('desc')">
    <!-- animate css -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">-->
    <!-- axios -->
    <script src="{{ asset('assets/js/axios.min.js') }}"></script>
    <!-- vuejs -->
    <script src="{{ asset('assets/js/vue.global.js') }}"></script>
    <!-- <script src="{{ asset('assets/js/vue.global.prod.js') }}"></script> -->

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <script src="{{ asset('js2/app.js') }}" defer></script>



    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;600;800;900&display=swap"
        rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href=" {{ asset('assets/icons/multilist-icons.css') }}">

    <script>
        let multilistType = '{{ $multilistType }}';

        let multilistTypeObj = {};

        // Multilist - 0
        // Homelist - 1
        // Officelist - 2
        // primelist - 3
        // landlist - 4
        // booklist - 5

        multilistTypeObj = {};

        let multilistTypeObjs = [{
            id: null,
            name: 'multilist',
            title: 'Multilist',
            description: 'Multilist',
            keywords: 'Multilist',
            image: '/images/logo.png',
            color: '#00a8ff',
        }, {
            id: 1,
            name: 'homelist',
            title: 'Homelist',
            description: 'Homelist',
            keywords: 'Homelist',
            image: '/images/homelist-logo.png',
            color: '#00a8ff',
        }, {
            id: 2,
            name: 'officelist',
            title: 'Officelist',
            description: 'Officelist',
            keywords: 'Officelist',
            image: '/images/officelist-logo.png'
        }, {
            id: 3,
            name: 'primelist',
            title: 'Primelist',
            description: 'Primelist',
            keywords: 'Primelist',
            image: '/images/primelist-logo.png'
        }, {
            id: 4,
            name: 'landlist',
            title: 'Landlist',
            description: 'Landlist',
            keywords: 'Landlist',
            image: '/images/landlist-logo.png'
        }, {
            id: 5,
            name: 'booklist',
            title: 'Booklist',
            description: 'Booklist',
            keywords: 'Booklist',
            image: '/images/booklist-logo.png'
        }];

        let colors = [];

        <?php
        foreach ($seo as $key => $value) {
            echo "colors.push({
                            name: '{$value->name}',
                            color: '{$value->main_color}'
                        });";
        }
        ?>

        // set the color attribute of the multilist type
        // loop through colors
        for (let i = 0; i < colors.length; i++) {
            let color = colors[i];

            // loop through multilist type objs
            for (let j = 0; j < multilistTypeObjs.length; j++) {
                let multilistTypeObj = multilistTypeObjs[j];
                if (color.name == multilistTypeObj.name) {
                    multilistTypeObj.color = color.color;
                }
            }
        }


        // get multilistTypeObj by name
        multilistTypeObj = multilistTypeObjs.find(obj => obj.name == multilistType);
    </script>

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/v2/bootstrap.min.css') }}">

    <!--load all Font Awesome styles -->
    <link href=" {{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('/assets/css/v2/styles.css') }}" rel="stylesheet" type="text/css">

    <style>
        footer {

            overflow-x: hidden;
        }


    </style>

    <script type="text/javascript">
        // check if token exists and pass it to axios package
        if (localStorage.getItem('token')) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
        }

        // check if window.Laravel.csrfToken exists and pass it to axios package
        if (window.Laravel.csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
        }
    </script>
    <!-- script -->
    <script src="{{ asset('assets/js/v2/scripts.js') }}"></script>

    <style>
        :root {
            --primary-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->main_color) {
                echo $seo_of_this_page->main_color;
            } else {
                echo 'red';
            }

            ?>;

            --secondary-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->secondary_color) {
                echo $seo_of_this_page->secondary_color;
            } else {
                echo '#fff';
            }

            ?>;

            --third-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->third_color) {
                echo $seo_of_this_page->third_color;
            } else {
                echo '#fff';
            }

            ?>;

            <?php

            foreach ($seo as $key => $value) {
                echo '--' . $value->name . '-main-color: ' . $value->main_color . ';';
                echo '--' . $value->name . '-secondary-color: ' . $value->secondary_color . ';';
                echo '--' . $value->name . '-third-color: ' . $value->third_color . ';';
            }

            ?>
        }
    </style>

    <script>
        let = {
            createApp
        } = Vue;
    </script>



    <script>
        const filterComponentLang = {
            "Ville":"{{ __('general.Ville') }}",
            "Quartier":"{{ __('general.Quartier') }}",
            "Catégorie":"{{ __('general.Catégorie') }}",
            "Prix":"{{ __('general.Prix') }}",
            "Min":"{{ __('general.Min') }}",
            "Max":"{{ __('general.Max') }}",
            "Surface":"{{ __('general.Surface') }}",
            "Pieces":"{{ __('general.Pieces') }}",
            "Chambres":"{{ __('general.Chambres') }}",
            "Salles de bain":"{{ __('general.Salles de bain') }}",
            "Année de construction":"{{ __('general.Année de construction') }}",
            "Équipements":"{{ __('general.Équipements') }}",
            "Filtrer":"{{ __('general.Filtrer') }}",
            "Acheter":"{{ __('general.Acheter') }}",
            "Louer":"{{ __('general.Louer') }}",
            "Voyager":"{{ __('general.Voyager') }}",
            "Jardin":"{{ __('general.Jardin') }}",
            "Meublé":"{{ __('general.Meublé') }}",
            "Syndic":"{{ __('general.Syndic') }}",
            "Sécurité":"{{ __('general.Sécurité') }}",
            "Piscine":"{{ __('general.Piscine') }}",
            "Climatisé":"{{ __('general.Climatisé') }}",
            "Cave":"{{ __('general.Cave') }}",
            "Parking":"{{ __('general.Parking') }}",
            "Terrasse":"{{ __('general.Terrasse') }}",
            "Ascenseur":"{{ __('general.Ascenseur') }}",
            "Autre":"{{ __('general.Autre') }}",
            "Sélectionner une ville":"{{ __('general.Sélectionner une ville') }}",
            "Sélectionnez un quartier":"{{ __('general.Sélectionnez un quartier') }}",
            "Sélectionner une catégorie":"{{ __('general.Sélectionner une catégorie') }}",
        }
    </script>

    @if(Session::get('lang') === 'ar')
    <style>
        .ml-select input{
            background-position: left 0.75rem center !important;
        }
    </style>
    @endif

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BRPW0NHPHX"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BRPW0NHPHX');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W764B5K');</script>
    <!-- End Google Tag Manager -->



    @yield('custom_head')

</head>

<body id="v2-app">
    @include('cookieConsent::index')
    <div id="header" class="header">

<div class="logo">
    <a :href="multilistTypeObj?(multilistTypeObj.name=='multilist'?'/':('/'+multilistTypeObj.name)):'/'">
        <img :src="multilistTypeObj?.image ? multilistTypeObj.image : '/images/logo.png'" alt="">
    </a>
</div>

@if (!Auth()->user() || (Auth()->user() && Auth()->user()->usertype != 3))
    <a class="btn-new-announcement" href="/deposer-annonce" @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        <span class="btn-new-announcement-text">+ {{ __('general.Déposer une annonce') }}</span>
        <span class="btn-new-announcement-text-mobile">+ {{ __('general.Annonce') }}</span>
    </a>
@else
    <a class="btn-new-announcement" href="/deposer-annonce" @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        <span class="btn-new-announcement-text">+ Déposer un projet</span>
        <span class="btn-new-announcement-text-mobile">+ Projet</span>
    </a>
@endif

@if (!Auth()->user())
    <div class="menu" id="menu" v-if="auth==null">
        <a href="/favoris" class="menu-item d-flex align-items-center" @if(Session::get('lang') === 'ar')
            dir="rtl"
        @else
            dir="ltr"
        @endif>
            <svg class="mx-1 mb-1" width="18px" height="17px" viewBox="0 0 18 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <!-- Generator: Sketch 52.5 (67469) - http://www.bohemiancoding.com/sketch -->
                <title>star_border</title>
                <desc>Created with Sketch.</desc>
                <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Rounded" transform="translate(-239.000000, -4323.000000)">
                        <g id="Toggle" transform="translate(100.000000, 4266.000000)">
                            <g id="-Round-/-Toggle-/-star_border" transform="translate(136.000000, 54.000000)">
                                <g>
                                    <polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M19.65,9.04 L14.81,8.62 L12.92,4.17 C12.58,3.36 11.42,3.36 11.08,4.17 L9.19,8.63 L4.36,9.04 C3.48,9.11 3.12,10.21 3.79,10.79 L7.46,13.97 L6.36,18.69 C6.16,19.55 7.09,20.23 7.85,19.77 L12,17.27 L16.15,19.78 C16.91,20.24 17.84,19.56 17.64,18.7 L16.54,13.97 L20.21,10.79 C20.88,10.21 20.53,9.11 19.65,9.04 Z M12,15.4 L8.24,17.67 L9.24,13.39 L5.92,10.51 L10.3,10.13 L12,6.1 L13.71,10.14 L18.09,10.52 L14.77,13.4 L15.77,17.68 L12,15.4 Z" id="🔹-Icon-Color" fill="#1D1D1D"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
             {{ __('general.Favoris') }}</a>
        <a href="/register" class="menu-item d-flex align-items-center" @if(Session::get('lang') === 'ar')
                dir="rtl"
            @else
                dir="ltr"
            @endif>
            <svg class="mx-1" width="19px" height="16px" viewBox="0 0 22 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <!-- Generator: Sketch 52.5 (67469) - http://www.bohemiancoding.com/sketch -->
                <title>person_add</title>
                <desc>Created with Sketch.</desc>
                <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Outlined" transform="translate(-169.000000, -4102.000000)">
                        <g id="Social" transform="translate(100.000000, 4044.000000)">
                            <g id="Outlined-/-Social-/-person_add" transform="translate(68.000000, 54.000000)">
                                <g>
                                    <polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M15,12 C17.21,12 19,10.21 19,8 C19,5.79 17.21,4 15,4 C12.79,4 11,5.79 11,8 C11,10.21 12.79,12 15,12 Z M15,6 C16.1,6 17,6.9 17,8 C17,9.1 16.1,10 15,10 C13.9,10 13,9.1 13,8 C13,6.9 13.9,6 15,6 Z M15,14 C12.33,14 7,15.34 7,18 L7,20 L23,20 L23,18 C23,15.34 17.67,14 15,14 Z M9,18 C9.22,17.28 12.31,16 15,16 C17.7,16 20.8,17.29 21,18 L9,18 Z M6,15 L6,12 L9,12 L9,10 L6,10 L6,7 L4,7 L4,10 L1,10 L1,12 L4,12 L4,15 L6,15 Z" id="🔹-Icon-Color" fill="#1D1D1D"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
            {{ __('general.Créer mon compte') }}
        </a>
        <a href="/login" class="menu-item d-flex align-items-center" @if(Session::get('lang') === 'ar')
                dir="rtl"
            @else
                dir="ltr"
            @endif>
            <svg class="mx-1" width="15px" height="15px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <!-- Generator: Sketch 52.5 (67469) - http://www.bohemiancoding.com/sketch -->
                <title>person</title>
                <desc>Created with Sketch.</desc>
                <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Outlined" transform="translate(-206.000000, -4102.000000)">
                        <g id="Social" transform="translate(100.000000, 4044.000000)">
                            <g id="Outlined-/-Social-/-person" transform="translate(102.000000, 54.000000)">
                                <g>
                                    <polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
                                    <path d="M12,6 C13.1,6 14,6.9 14,8 C14,9.1 13.1,10 12,10 C10.9,10 10,9.1 10,8 C10,6.9 10.9,6 12,6 Z M12,16 C14.7,16 17.8,17.29 18,18 L6,18 C6.23,17.28 9.31,16 12,16 Z M12,4 C9.79,4 8,5.79 8,8 C8,10.21 9.79,12 12,12 C14.21,12 16,10.21 16,8 C16,5.79 14.21,4 12,4 Z M12,14 C9.33,14 4,15.34 4,18 L4,20 L20,20 L20,18 C20,15.34 14.67,14 12,14 Z" id="🔹-Icon-Color" fill="#1D1D1D"></path>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
            {{__('Se connecter')}}
        </a>
        <a v-if="multilistTypeObj?.name!='multilist'" href="/" class="menu-item" style="font-weight: 600;">
            <img style="width:90px" src={{ asset('/images/logo.png') }} />
        </a>
        <a style="cursor:pointer;text-transform: UPPERCASE;font-weight: 600;" class="menu-item" @click="activeLangDropDown($event)">
            <i class="fas fa-caret-down mx-2"></i>
            {{ App()->getLocale() }}
        </a>
        <ul class="dropdown-menu1" style="right: 25px;min-width: 70px;text-transform: UPPERCASE;font-weight: 600;" @click="stopPropagation($event)" :class="langDropDownActive ? 'show' : ''">
            <li @click="changeLang('fr')">
                <a class="dropdown-item1">
                    <span>FR</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li @click="changeLang('ar')">
                <a class="dropdown-item1">
                    <span>AR</span>
                </a>
            </li>
        </ul>
    </div>
@else
    <div class="menu" id="menu" v-if="auth">
        <a class="menu-item user-avatar-info" @click="activeDropDown($event)">
            <img :src="auth.avatar ? '/storage' + auth.avatar : '/images/default-logo.png'">
            <span class="username mx-2">{{ Auth()->user()->username }} <i class="fa-solid fa-caret-down mx-2"></i> </span>
        </a>
        <ul class="dropdown-menu1" @click="stopPropagation($event)" :class="dropDownActive ? 'show' : ''">

            <li v-if="multilistTypeObj?.name!='multilist'">
                <a href="/" class="dropdown-item1">
                    <span style="font-weight: 600;">
                        <img style="width:90px" src={{ asset('/images/logo.png') }} />
                    </span>
                </a>
            </li>
            <li v-if="multilistTypeObj?.name!='multilist'">
                <hr class="dropdown-divider">
            </li>

            <li>
                <a href="/dashboard" class="dropdown-item1">
                    <i class="fa-solid fa-gauge mx-2"></i>
                    <span>{{ __('general.Mon tableau de bord') }}</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <a href="/favoris" class="dropdown-item1">
                    <i class="far fa-star mx-2"></i>
                    <span>{{ __('general.Favoris') }}</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li v-if="[3,4,5].indexOf(auth.usertype)==-1">
                <a href="/admin" class="dropdown-item1" target="_blank">
                    <i class="fa-solid fa-gear mx-2"></i>
                    <span>{{ __('general.Administration') }}</span>
                </a>
            </li>



            <li v-if="[3,4,5].indexOf(auth.usertype)==-1">
                <hr class="dropdown-divider">
            </li>

            <li>
                <button class="dropdown-item1" @click="signOut">
                    <i class="fa-solid fa-arrow-right-from-bracket mx-2"></i>
                    <span>{{ __('general.Se déconnecter') }}</span>
                </button>
            </li>

            <li v-if="switchedAccountName != null">
                <hr class="dropdown-divider">
            </li>

            <li v-if="switchedAccountName != null">
                <button class="dropdown-item1 btn-sm" style="font-size:13px" @click="switchAccount()">
                    <i class="fa-solid fa-people-arrows mx-2"></i>
                    Basculer vers <span class="text-primary mx-2">@{{ switchedAccountName }}</span>
                </button>
            </li>

        </ul>
        <a style="cursor:pointer;text-transform: UPPERCASE;font-weight: 600;" class="menu-item" @click="activeLangDropDown($event)">
            <i class="fas fa-caret-down mx-2"></i>
            {{ App()->getLocale() }}
        </a>
        <ul class="dropdown-menu1" style="right: 25px;min-width: 70px;text-transform: UPPERCASE;font-weight: 600;" @click="stopPropagation($event)" :class="langDropDownActive ? 'show' : ''">
            <li @click="changeLang('fr')">
                <a class="dropdown-item1">
                    <span>FR</span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>

            <li @click="changeLang('ar')">
                <a class="dropdown-item1">
                    <span>AR</span>
                </a>
            </li>
        </ul>
    </div>
@endif
<a @click="activeRightMenu()" class="right-menu-btn-container">
    <i class="fa-solid fa-times text-danger mx-2" :class="rightMenuIsActive ? '' : 'd-none'"></i>
    <i class="fa-solid fa-bars mx-2" :class="rightMenuIsActive ? 'd-none' : ''"></i>
    <span></span>
</a>

<a style="cursor:pointer;text-transform: UPPERCASE;font-weight: 600;margin-right: 15px;margin-left: 0;" class="menu-item right-menu-btn-container" @click="activeLangDropDown($event)">
    <i class="fas fa-caret-down"></i>
    {{ App()->getLocale() }}
</a>
<ul class="dropdown-menu1" style="right: 25px;min-width: 70px;text-transform: UPPERCASE;font-weight: 600;" @click="stopPropagation($event)" :class="langDropDownActive ? 'show' : ''">
    <li @click="changeLang('fr')">
        <a class="dropdown-item1">
            <span>FR</span>
        </a>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>

    <li @click="changeLang('ar')">
        <a class="dropdown-item1">
            <span>AR</span>
        </a>
    </li>
</ul>

<div class="right-menu" :class="rightMenuIsActive ? 'right-menu-active' : ''" id="right-menu" @if (Session('lang')=='ar')
    dir="rtl"
@else
    dir="ltr"
@endif>

    @if (!Auth()->user())
        <a href="/register" class="right-menu-item {{ Request::is('register') ? 'active' : '' }}">
            <span class="d-flex align-items-center">
                <svg class="me-1" width="19px" height="16px" viewBox="0 0 22 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <!-- Generator: Sketch 52.5 (67469) - http://www.bohemiancoding.com/sketch -->
                    <title>person_add</title>
                    <desc>Created with Sketch.</desc>
                    <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Outlined" transform="translate(-169.000000, -4102.000000)">
                            <g id="Social" transform="translate(100.000000, 4044.000000)">
                                <g id="Outlined-/-Social-/-person_add" transform="translate(68.000000, 54.000000)">
                                    <g>
                                        <polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M15,12 C17.21,12 19,10.21 19,8 C19,5.79 17.21,4 15,4 C12.79,4 11,5.79 11,8 C11,10.21 12.79,12 15,12 Z M15,6 C16.1,6 17,6.9 17,8 C17,9.1 16.1,10 15,10 C13.9,10 13,9.1 13,8 C13,6.9 13.9,6 15,6 Z M15,14 C12.33,14 7,15.34 7,18 L7,20 L23,20 L23,18 C23,15.34 17.67,14 15,14 Z M9,18 C9.22,17.28 12.31,16 15,16 C17.7,16 20.8,17.29 21,18 L9,18 Z M6,15 L6,12 L9,12 L9,10 L6,10 L6,7 L4,7 L4,10 L1,10 L1,12 L4,12 L4,15 L6,15 Z" id="🔹-Icon-Color" fill="#1D1D1D"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                {{ __('general.Créer mon compte') }}
            </span>
        </a>
        <a href="/login" class="right-menu-item {{ Request::is('login') ? 'active' : '' }}">
            <span class="d-flex align-items-center">
                <svg class="me-1" width="15px" height="15px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <!-- Generator: Sketch 52.5 (67469) - http://www.bohemiancoding.com/sketch -->
                    <title>person</title>
                    <desc>Created with Sketch.</desc>
                    <g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Outlined" transform="translate(-206.000000, -4102.000000)">
                            <g id="Social" transform="translate(100.000000, 4044.000000)">
                                <g id="Outlined-/-Social-/-person" transform="translate(102.000000, 54.000000)">
                                    <g>
                                        <polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
                                        <path d="M12,6 C13.1,6 14,6.9 14,8 C14,9.1 13.1,10 12,10 C10.9,10 10,9.1 10,8 C10,6.9 10.9,6 12,6 Z M12,16 C14.7,16 17.8,17.29 18,18 L6,18 C6.23,17.28 9.31,16 12,16 Z M12,4 C9.79,4 8,5.79 8,8 C8,10.21 9.79,12 12,12 C14.21,12 16,10.21 16,8 C16,5.79 14.21,4 12,4 Z M12,14 C9.33,14 4,15.34 4,18 L4,20 L20,20 L20,18 C20,15.34 14.67,14 12,14 Z" id="🔹-Icon-Color" fill="#1D1D1D"></path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                {{__('Se connecter')}}
            </span>
        </a>
        <a href="/favoris" class="right-menu-item {{ Request::is('favoris') ? 'active' : '' }}">
            <i class="far fa-star mx-2"></i>
            <span>{{ __('general.Favoris') }}</span>
        </a>

        <br />
         <a v-if="multilistTypeObj?.name!='multilist'" href="/" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='booklist'" href="/booklist" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/booklist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='homelist'" href="/homelist" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/homelist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='primelist'" href="/primelist" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/primelist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='landlist'" href="/landlist" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/landlist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='officelist'" href="/officelist" class="right-menu-item">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/officelist-logo.png') }} alt="Multilist">
            </span>
        </a>
    @else
        <div v-if="auth" class="right-menu-item user-avatar-info">
            <img :src="auth.avatar ? '/storage' + auth.avatar : '/images/default-logo.png'">
            <span class="username mx-2">{{ Auth()->user()->username }}</span>
        </div>
        <a href="/dashboard" class="right-menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge mx-2"></i>
            <span> {{ __("general.Mon tableau de bord") }}</span>
        </a>
        <a href="/myprofile" class="right-menu-item {{ Request::is('myprofile') ? 'active' : '' }}">
            <i class="fa-solid fa-user mx-2"></i>
            <span> {{ __("general.Mon profile") }}</span>
        </a>

        <a href="/reset-password" class="right-menu-item {{ Request::is('mdp') ? 'active' : '' }}">
            <i class="fa-solid fa-gear mx-2"></i>
            <span> {{ __("general.Modifier mot de passe") }}</span>
        </a>
        <a href="/editprofile" class="right-menu-item {{ Request::is('editprofile') ? 'active' : '' }}">
            <i class="fa-solid fa-user-pen mx-2"></i>
            <span> {{ __("general.Modifier mon profile") }}</span>
        </a>
        <a href="/myitems" class="right-menu-item {{ Request::is('myitems') ? 'active' : '' }}">
            <i class="fa-solid fa-bullhorn mx-2"></i>
            <span>
                @if (Auth()->user()->usertype != 3)
                    {{ __("general.Mes annonces") }}
                @else
                    {{ __("general.Mes projets") }}
                @endif
            </span>
        </a>
        <a href="/myemails" class="right-menu-item {{ Request::is('myemails') ? 'active' : '' }}">
            <i class="fa-solid fa-envelope mx-2"></i>
            <span> {{ __("general.Mes emails") }}</span>
        </a>
        <a href="/bookings" class="right-menu-item {{ Request::is('bookings') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar-days mx-2"></i>
            <span> {{ __("general.Les Réservation") }}</span>
        </a>
        @if (Auth()->user()->usertype != 3)
            <a href="/mytransactions" class="right-menu-item {{ Request::is('mytransactions') ? 'active' : '' }}">
                <i class="fa-solid fa-right-left mx-2"></i>
                <span> {{ __('general.Mes Opérations') }}</span>
            </a>
        @endif
        <a href="/admin" class="right-menu-item" v-if="auth&&[3,4,5].indexOf(auth.usertype)==-1">
            <i class="fa-solid fa-gear mx-2"></i>
            <span> {{ __("general.Administration") }}</span>
        </a>
        <a href="/favoris" class="right-menu-item {{ Request::is('favoris') ? 'active' : '' }}">
            <i class="fas fa-star mx-2"></i>
            <span>{{ __("general.Favoris") }}</span>
        </a>

        <button class="right-menu-item" @click="signOut">
            <i class="fa-solid fa-arrow-right-from-bracket mx-2"></i>
            <span>{{ __("general.Se déconnecter") }}</span>
        </button>
        <li v-if="switchedAccountName != null">
                <hr class="dropdown-divider">
        </li>

        <br />

        <div class="d-flex flex-wrap justify-content-center mt-1">

         <a v-if="multilistTypeObj?.name!='multilist'" href="/" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='booklist'" href="/booklist" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/booklist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='homelist'" href="/homelist" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/homelist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='primelist'" href="/primelist" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/primelist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='landlist'" href="/landlist" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/landlist-logo.png') }} alt="Multilist">
            </span>
        </a>
        <a v-if="multilistTypeObj?.name!='officelist'" href="/officelist" class="right-menu-item p-1 m-0">
            <span style="font-weight: 600;">
                <img style="width:100px" src={{ asset('/images/officelist-logo.png') }} alt="Multilist">
            </span>
        </a>
        </div>

        <li v-if="switchedAccountName != null" class="d-flex justify-content-center">
            <button class="dropdown-item1 btn-sm" style="font-size:13px" @click="switchAccount()">
                <i class="fa-solid fa-people-arrows mx-2"></i>
                Basculer vers <span class="text-primary mx-2">@{{ switchedAccountName }}</span>
            </button>
        </li>
    @endif

</div>

</div>



    @yield('content')



    <!-- Bootstrap JavaScript Libraries -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script> --}}
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script> --}}
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <script>
        let = {
            createApp
        } = Vue;
    </script>

    <script>
        window.switchedAccount = localStorage.getItem('switchedAccount')
        const currUserId = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        let headerApp = createApp({
            data() {
                return {
                    rightMenuIsActive: false,
                    dropDownActive: false,
                    langDropDownActive: false,
                    auth: null,
                    multilistTypeObj: multilistTypeObj,
                    switchedAccount : localStorage.getItem('switchedAccount'),
                    switchedAccountName : localStorage.getItem('switchedAccountName')
                }
            },
            mounted() {

                if (currUserId)
                    axios.get('/api/v2/getHeaderUser/' + currUserId)
                    .then((response) => {
                        this.auth = response.data.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });

                document.addEventListener('click', (e) => {
                    this.dropDownActive = false;
                });

                this.initial()
                this.switchAccountMsg()

            },
            updated() {
                //document.querySelector('#menu').classList.remove('d-none');
            },
            methods: {
                switchAccountMsg(){
                    if(localStorage.getItem('switchedAccountSuccess')){
                        Swal.fire({
                            icon: 'success',
                            title: 'Changement de compte',
                            html:
                            `Compte basculer vers <b> ${JSON.parse(localStorage.getItem('auth')).username} </b> `,
                            })
                            localStorage.removeItem('switchedAccountSuccess')
                    }
                },
                initial(){


                // switch (selectedLocal) {

                //     case "ar":
                //         arBtn.style.display = "none"
                //         break;
                //     case "fr":
                //         frBtn.style.display = "none"
                //         break;
                //     case "en":
                //         enBtn.style.display = "none"
                //         break;

                //     default:
                //         console.log("Désolé, nous n'avons plus ..");
                //     }

                },
                changeLang(lang){
                    axios.post('/api/v2/changelang?lang='+lang)
                        .then(function(response) {
                            console.log(response);
                            window.location.reload();
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                },
                activeRightMenu() {
                    this.rightMenuIsActive = !this.rightMenuIsActive;
                },
                activeDropDown(e) {
                    e.stopPropagation();
                    this.dropDownActive = !this.dropDownActive;
                    this.langDropDownActive = false;
                },
                activeLangDropDown(e) {
                    e.stopPropagation();
                    this.langDropDownActive = !this.langDropDownActive;
                    this.dropDownActive = false;
                },
                stopPropagation(e) {
                    e.stopPropagation();
                },
                signOut() {
                    axios.post('/api/v2/logout')
                        .then(function(response) {

                            localStorage.removeItem('auth');
                            localStorage.removeItem('token');


                            document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";


                            window.location.href = '/';
                            console.log(response);
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                    // clear jwt token from storage and cookies
                    /*localStorage.removeItem('auth');
                    localStorage.removeItem('token');
                    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";*/
                },
                switchAccount(){
                    axios({
                    method: 'post',
                    url: '/api/v2/switchUser',
                    data: {
                        email: localStorage.getItem('switchedAccount'),
                    }
                    })
                    .then(function (response) {
                        console.log('response data: ',response.data)
                        let data = response.data;
                        let token = data.data.token;
                        let auth = data.data.auth;
                        let status = data.success

                        // store the token & auth
                        localStorage.setItem('token', token);
                        localStorage.setItem('auth', JSON.stringify(auth));
                        if(localStorage.getItem('switchedAccount')){
                            localStorage.removeItem('switchedAccount')
                            localStorage.removeItem('switchedAccountName');
                        }



                        window.location = '/admin';
                    });
                }
            }
        }).mount('#header');

        hidephone();
    </script>

    @yield('custom_foot')




    <footer class="text-center text-lg-start" style="{{Request::is('/')?'margin-top:0':''}}"  @if(Session::get('lang') === 'ar') dir="rtl" @endif>
        <div class="row">
            {{--<div class="d-flex mb-2">
                <div style="width:100%;height: 5px;background-color: rgb(181, 36, 131);"></div>
                <div style="width:100%;height: 5px;background-color: rgb(246, 77, 75);"></div>
                <div style="width:100%;height: 5px;background-color: rgb(243, 190, 46);"></div>
                <div style="width:100%;height: 5px;background-color: rgb(84, 194, 27);"></div>
                <div style="width:100%;height: 5px;background-color: rgb(0, 83, 125);"></div>

            </div>--}}
            <!-- Section: Social media -->
            <section class="d-flex justify-content-center justify-content-lg-evenly border-bottom" style="padding: 10px 0;">
                <!-- Left -->
                {{--<div class="me-5 d-none d-lg-block">
                    <span>Rejoignez nous sur les réseaux sociaux : </span>
                </div>--}}
                <!-- Left -->

                <div class="footer-univers">
                    <a href="/booklist" class="footer-univer" id="booklist">
                        <img src="/images/booklist-logo-white.png">
                        <div class="bg-rect"></div>
                    </a>
                    <a href="/homelist" class="footer-univer" id="homelist">
                        <img src="/images/homelist-logo-white.png">
                        <div class="bg-rect"></div>
                    </a>
                    <a href="/primelist" class="footer-univer" id="primelist">
                        <img src="/images/primelist-logo-white.png">
                        <div class="bg-rect"></div>
                    </a>
                    <a href="/landlist" class="footer-univer" id="landlist">
                        <img src="/images/landist-logo-white.png">
                        <div class="bg-rect"></div>
                    </a>
                    <a href="/officelist" class="footer-univer" id="officelist">
                        <img src="/images/officelist-logo-white.png">
                        <div class="bg-rect"></div>
                    </a>
                </div>

                <!-- Right -->
                <div style="display: flex;align-items: center;flex: 1;justify-content: center;">
                    <a href="https://www.facebook.com/Multilist.group/" target="_blank" class=" text-reset" style="margin: 0 10px;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.youtube.com/channel/UCNjQB9JoAbAmQJDXrDIjn4w"  target="_blank" class=" text-reset" style="margin: 0 10px;">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/multilist-immo/about/"  target="_blank" class=" text-reset" style="margin: 0 10px;">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="https://www.instagram.com/multilist.immo/?hl=fr" class=" text-reset"  target="_blank" style="margin: 0 10px;">
                        <i class="fab fa-instagram"></i>
                    </a>

                </div>
                <!-- Right -->
            </section>
            <!-- Section: Social media -->

            <!-- Section: Links  -->
            <section class="border-bottom">
                <div class="text-center text-md-start mt-5 ml-mb">
                    <!-- Grid row -->
                    <div class="row mt-3">

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-4 footer-info">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-2">
                                {{ __("general.l'entreprise") }}
                            </h6>
                            <p>
                                <a href="/aboutus" class="text-reset">{{ __('general.Qui sommes-nous ?') }}</a>
                            </p>
                            <p>
                                <a href="/privacy" class="text-reset">{{ __('general.Vie privée') }}</a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-4 footer-info">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-2">
                                {{ __('general.NOS APPLICATIONS') }}
                            </h6>
                            <p>
                                {{ __('general.Bientôt disponible sur') }}
                            </p>
                            <p>
                                <a href="" class="text-reset" style="font-size: 20px;">
                                    <i class="fab fa-android me-3"></i> <i class="fab fa-apple me-3"></i>
                                </a>
                            </p>
                        </div>
                        <!-- Grid column -->

                        <!-- Grid column -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-4 footer-info">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-2">
                                {{ __('general.Contact') }}
                            </h6>
                            <p>
                                <i class="fas fa-envelope me-3"></i>
                                contact@multilist.ma
                            </p>
                            <p><i class="fas fa-map me-3"></i> <a href="https://goo.gl/maps/GeN1hFR3svJQ9oMB8" style="color: inherit;text-decoration: none;" target="_blank">{{ __('general.Voir sur la carte') }}</a></p>
                            <p class="d-none"><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                        </div>
                        <!-- Grid column -->

                    </div>
                    <!-- Grid row -->
                </div>
            </section>

            <div class="p-2 d-flex d-mb-block justify-content-center border-0 border-light" id="copyright" style="padding: 10px 20px !important; border-top: 1px !important;">
                © 2022 {{ __('general.Copyright') }}:
                <a class="text-reset fw-bold">multilist</a>
                 |
                 <a href="/info" class="text-reset">{{ __('general.Infos légales et CGU') }}/a>
                 <a href="/roles" class="text-reset">Règles de diffusion</a>
                 <a href="/cookies" class="text-reset">Cookies</a>
            </div>
    </footer>
            <script>
                const paragraph = `
            <p style="color: #fff;margin: 0;font-size: 12px;display: flex;align-items: center;padding: 0 10px;margin-right: auto;justify-content: center;">
              {{ __('general.Copyright') }} &copy; ${new Date().getFullYear()} Multilist
            </p>
            <div class="footer-bottom-link-cnt">
                <a href="/info" class="text-reset footer-bottom-link"> {{ __('general.Infos légales et CGU') }} </a>
                <a href="/roles" class="text-reset footer-bottom-link"> {{ __('general.Règles de diffusion') }} </a>
                <a href="/cookies" class="text-reset footer-bottom-link"> {{ __('general.Cookies') }} </a>
             </div>
          `;
          document.getElementById('copyright').innerHTML = paragraph;
          </script>

    <style>

        footer{
            z-index: 2;
        }

        .footer-info p{
            font-size: 14px;
            margin-bottom: 0.5rem!important;
        }


        @import url('https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200;0,300;0,400;1,200;1,300&display=swap');

        .cookie-consent-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 2147483645;
            box-sizing: border-box;
            width: 100%;
            font-family: 'Karla', sans-serif;
            box-shadow: 0 0 4px 0 darkgrey;
            background-color: #F1F6F4;
          }

          .cookie-consent-banner__inner {
            max-width: 90%;
            margin: 0 auto;
            padding: 15px 0;
            display: flex;
            align-items: center;
            padding-top: 0;
          }

          .cookie-consent-banner__copy {
            width: 70%;
          }

          .cookie-consent-banner__header {
            font-weight: normal;
            font-size: 16px;
            line-height: 24px;
            padding-top: 15px;
            width: 90%;
            margin: auto;
          }

          .cookie-consent-banner__description {
            font-weight: normal;
            color: #838F93;
            font-size: 12px;
            text-align: justify;
          }

          .cookie-consent__message{
            margin-bottom: 0 !important;
            color: #373736;
            font-size: 11px;
          }

          .cookie-consent__message a{
            text-decoration: underline;
          }

          .cookie-consent-banner__actions{
              margin: auto;
              text-align: center;
          }

          .cookie-consent-banner__cta {
            box-sizing: border-box;
            display: inline-block;
            min-width: 164px;
            padding: 11px 13px;
            border-radius: 2px;
            background-color: var(--primary-color);
            color: #FFF;
            text-decoration: none;
            text-align: center;
            font-weight: normal;
            font-size: 16px;
            line-height: 20px;
            text-decoration: none;
            transition: all .2s ease-in-out;
            font-weight: 600;
            border-radius: 25px;
            margin: 5px 10px;
          }

          .cookie-consent-banner__cta--secondary {
            padding: 9px 13px;
            border: 2px solid #3A4649;
            background-color: transparent;
            color: #333;
            text-decoration:none;
            border-radius: 25px;
            margin: 5px 10px;
          }

          .cookie-consent__agree:hover {
            color: #fff;
          }

          .cookie-consent-banner__cta--secondary:hover {
            border-color: #333;
            background-color: transparent;
          }


        @media screen and (max-width: 750px){
            .cookie-consent-banner__inner{
                display: block;
            }
            .cookie-consent-banner__copy{
                width: 100%;
                margin-bottom: 10px;
            }
            .cookie-consent-banner__description {
                text-align: unset;
            }
        }

        </style>

<script>

    window.addEventListener('DOMContentLoaded',(e) => {


        const body = document.querySelector('body')
        const selectedLocal = '<?= session('lang') ?>'
        const dropDownMenu = document.querySelector('.dropdown-menu1')
        const langueSwitcher = document.querySelector('.langueSwitcher')
        if(selectedLocal === 'ar'){

            body.style.fontFamily="'Tajawal', sans-serif; !important";

        }


    })


const holders = document.querySelectorAll('.translate') || null;
const items = document.querySelectorAll('.translates') || null;
const headers = document.querySelectorAll('.modal-header') || null;

const LangLocal = '<?= session('lang') ?>';


if(LangLocal === 'ar'){


      holders.forEach(item => {

           item.setAttribute('dir','rtl');
           item.style.marginRight='15px';

      })

      items.forEach(item => {

        item.setAttribute('dir','rtl');

     });

}

</script>
</body>

</html>

