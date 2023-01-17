@extends('v2.layouts.default')

@section('title', substr($title, 0, 90))

@section('desc' , substr($title, 0, 155) )

@section('custom_head')

<script src="https://kit.fontawesome.com/9245f43cf7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/item.styles.css') }}">
    <script src="{{ asset('js/anime.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <link rel="stylesheet" href=" {{ asset('css/components/sliderDetails.vue.css') }}">
    <script src="{{ asset('js/components/sliderDetails.vue.js') }}"></script>

    <script src="{{ asset('js/components/slider.vue.js') }}"></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/popup-components.vue.css') }}">
    <script src="{{ asset('js/components/popup-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/multilist_calendar.vue.css') }}">
    <script src="{{ asset('js/components/multilist_calendar.vue.js') }}"></script>

    <script src="{{ asset('js/uploadFiles.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/add.styles.css') }}">

    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>

    <style>
        .invalid{

            border :1px solid red;
        }

        .invalidfeed{

             display: block;
             height:auto;
        }

         #signaler {

            font-size: 10px !important;
            padding: 12px;
         }

         #flexible{

            height:1px;
         }

         @media screen and (max-width: 525px){

                #custom-card{

                        padding:10px;
                }

                #custom-btn{

                    transform:translateY(-18px) !important;
                }

         }
        @media screen and (min-width: 400px){
            #signaler{

                font-size:12px !important;
                padding:10px;

            }

            #flexible{



                height:100px;
               display:flex;
               justify-content:flex-start;;


            }
        }



    </style>


@endsection

@section('content')
    <div class="container-md">
        <section class="details d-none" id="item-App">

            <div class="row equal">

                <div class="col-12 col-lg-9">
                    <div class="item">
                        <section class="media" id="media-slider">
                            <div class="media-item">
                                <div class="media-selector">
                                    <div class="media-btn" @click="selectedMedia='photos'">
                                        <i class="fa fa-camera"></i>
                                        <div class="label translates" :class="obj.vr_link || obj.videos?.length > 0 ? 'br' : ''">
                                            @{{ obj.images?.length }} {{ __('general.photos') }}</div>
                                    </div>

                                    <div class="media-btn" v-if="obj.videos?.length>0" @click="selectedMedia='video'">
                                        <i class="fa fa-circle-play"></i>
                                        <div class="label translates" :class="obj.vr_link ? 'br' : ''">{{ __('general.Vidéos') }}</div>
                                    </div>

                                    <div class="media-btn" v-if="obj.vr_link" @click="selectedMedia='3d'">
                                        <i class="fa-solid fa-vr-cardboard"></i>
                                        <div class="label translates">{{ __('general.3d visite') }}</div>
                                    </div>
                                </div>

                                <div style="height: 100%;" v-if="selectedMedia=='photos'">
                                    <Sliderd :images="obj.images ?? []"></Sliderd>
                                </div>

                                <div v-if="obj.videos&&obj.videos[0]&&selectedMedia=='video'">
                                    <video controls style="width: 100%;height: 400px;">
                                        <source :src="'/storage/' + obj.videos[0].name" type="">
                                    </video>
                                </div>

                                <div v-if="obj.vr_link&&selectedMedia=='3d'">
                                    <iframe :src="obj.vr_link" style="width:100%;height:400px;">

                                    </iframe>
                                </div>

                            </div>
                        </section>

                        <section class="item-main-info">
                            <div class="item-date">
                                <span class="publication-date">
                                    <i class="fa-regular fa-calendar-alt me-2"></i>
                                    <span class="translates">{{ __('general.Publié le') }} @{{ formatDateTime(obj.published_at) }}</span>
                                </span>
                            </div>
                            <div class="item-title-mobile">
                                @{{ obj.title }}
                            </div>
                            <div class="d-flex w-100 align-items-center">
                                <div class="item-main-info-left">
                                    <div class="item-title">
                                        @{{ obj.title }}
                                    </div>
                                    <div class="item-price" class="translates">
                                        <div v-if="obj.is_project==1&&obj.price > 0" style="font-size: 10px;">{{ __('general.À partir de') }}</div>
                                        @{{ price(obj) }}
                                    </div>
                                </div>
                                <div class="action mstart-auto">
                                    <button class="btn favorite" title="ajouter aux favoris" @click="toggleFavoris(obj.id)">
                                        <i :class="favoris.find((e) => e == obj.id) ? 'fas primary-c' : 'far'"
                                            class="fa-star"></i>
                                    </button>
                                    <button title="Partager votre annonce" style="color: var(--primary-color)" type="button" class="btn" data-bs-toggle="modal" data-bs-target="#shareModal">
                                        <i class="fa-solid fa-share-nodes"></i>
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade"  id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered m-auto" style="hight:300px;width:350px">
                                        <div class="modal-content" style="height: 278px;background:transparent;padding:1rem">
                                            <div style="background: #fff;height:243px;box-shadow:1px 1px 6px 5px #00000021;border-radius: 15px;                                            ">

                                                <div class="modal-header p-3" @if (Session::get('lang') === 'ar')
                                                    dir="rtl"
                                                @endif>
                                                    <h6 class="modal-title text-center m-auto d-flex align-items-center" id="shareModalLabel"><i class="fa-solid fa-share-nodes mx-2"></i>{{ __('general.Partager votre annonce') }}</h6>
                                                    <button type="button" class="btn-close me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body d-flex justify-content-around align-items-center mt-3">

                                                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target='_blank'>
                                                                <i style="color:rgb(52, 52, 201);font-size: 3rem !important;" class="fa-brands fa-facebook fa-xl"></i>
                                                            </a>

                                                            <a href="https://wa.me/?text={{ $url }}" target='_blank'>
                                                                <i style="color:rgb(41, 173, 41);font-size: 3rem !important;" class="fa-brands fa-whatsapp fa-xl"></i>
                                                            </a>

                                                            <a href="https://telegram.me/share/url?url={{ $url }}" target='_blank'>
                                                                <i style="color:#0088cc;font-size: 3rem !important;" class="fa-brands fa-telegram fa-xl"></i>
                                                            </a>

                                                    </div>
                                                    <div class="cp-background d-flex justify-content-center align-items-center">
                                                        <center>
                                                        <input class="mb-1" readonly style="border:none;width: 310px;position: relative;background-color:#ececec;" type="text" name="url" id="url-text" value="">
                                                        <button id="clipboard" type="button" class="btn btn-primary mx-2" @if (Session::get('lang')== "ar")
                                                            dir="rtl"
                                                        @endif>
                                                        <i class="fa fa-copy me-1"></i>
                                                            {{ __("general.copier URL") }}
                                                        </button>
                                                          <p class="mt-1" id="url" style="font-size:14px"> </p>
                                                        </center>
                                                    </div>


                                            </div>



                                        </div>
                                        </div>
                                    </div>
                                    <!-- Modal end -->

                                    <script>



                                    </script>
                                    <style>
                                        div#social-links {
                                            margin: 0 auto;
                                            max-width: 500px;
                                        }
                                        div#social-links ul li {
                                            display: inline-block;
                                        }
                                        div#social-links ul li a {
                                            padding: 20px;
                                            border: 1px solid #ccc;
                                            margin: 1px;
                                            font-size: 30px;
                                            color: #222;
                                            background-color: #ccc;
                                        }
                                    </style>
                                    @if (Auth()->user())
                                    <button data-bs-toggle="modal" id="signaler" data-bs-target="#abusModal" type="button" class="btn btn-outline-danger" v-if="obj.id_user!=currentUserId">
                                        <i class="fa fa-circle-exclamation"></i>
                                        {{__('general.Signaler un abus')}}
                                    </button>
                                    @else

                                    @endif
                                </div>
                            </div>
                        </section>

                        <section class="item-section owner-info-mobile d-flex d-lg-none" id="owner-info-mobile">

                            <div class="multiads-btns-cnt" v-if="obj.id_user==currentUserId">
                                <button class="btn multiads-btn boost"
                                @click="boostAd(obj)">
                                    <i class="fas fa-rocket"></i> {{ __('general.Booster') }}
                                </button>
                                <div style="display: flex; flex: 1 1 0%;">
                                    <button class="btn multiads-btn"
                                    @click="updateAd(obj)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn multiads-btn delete"
                                    @click="deleteAd()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            {{--<div v-if="obj.id_user==currentUserId" class="d-flex justify-content-evenly owner-social">
                                <button type="button" class="btn btn-light btn-sm"
                                    @click="updateAd(obj)" aria-hidden="true">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm"
                                    @click="boostAd(obj)" aria-hidden="true">
                                    <i class="fa fa-rocket" style="color:blue;"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm"
                                    @click="deleteAd()" aria-hidden="true">
                                    <i class="fa fa-trash" style="color:red;"></i>
                                </button>
                            </div>--}}
                            <div class="d-flex justify-content-around owner-social" style="padding: 10px 3px;">

                                <div class="owner-info" style="padding-right: 5px;border-right: 1px solid #cecece4f;">
                                    <img v-if="obj.usertype==4||obj.usertype==3" class="avatar img-fluid"
                                        :src="obj.avatar ? '/storage' + obj.avatar :
                                            '/images/default-logo.png'" />
                                    <div v-if="obj.usertype==4||obj.usertype==3" class="ms-1 d-flex flex-column">
                                        <div class="owner-name">
                                            <a :href="'/profile/' + obj.id_user">
                                                <h1>@{{ obj.company ?? obj.username }}</h1>
                                            </a>
                                        </div>

                                    </div>
                                </div>

                                <button @click="showemail=!showemail;" class="btn owner-phone tagManager-phone rounded-circle">
                                    <i class="fas fa-xs fa-envelope"></i>
                                </button>
                                <a v-if="obj.c_phone||obj.user_phone" class="btn owner-phone tagManager-phone"
                                    :href="'tel: ' + (obj.c_phone ?? obj.user_phone)" target="_blank">
                                    <i class="fas fa-xs fa-phone"></i>
                                </a>
                                <a v-if="obj.c_phone2" class="btn owner-phone tagManager-phone" :href="'tel: ' + obj.c_phone2"
                                    target="_blank">
                                    <i class="fas fa-xs fa-phone"></i>
                                </a>
                                <a v-if="obj.c_wtsp" class="btn owner-whatsapp tagManager-wtsp"
                                    :href="'https://wa.me/' + obj.c_wtsp +
                                        '?text=Je suis intéressé par votre annonce: {{ request()->getHost() }}/item/' +
                                        obj.id"
                                    target="_blank">
                                    <i class="fa-brands fa-xl fa-whatsapp"></i>
                                </a>
                                <a v-if="obj.c_telegram" class="btn owner-whatsapp tagManager-wtsp"
                                    :href="'https://telegram.me/' + obj.c_telegram +
                                        '?text=Je suis intéressé par votre annonce: {{ request()->getHost() }}/item/' +
                                        obj.id"
                                    target="_blank">
                                    <i class="fa-brands fa-xs fa-telegram"></i>
                                </a>
                            </div>
                            <div class="sendmail" v-if="showemail">
                                <div class="mb-3 translates">
                                    <input type="text" name="" id=""  :class="errors.name ? 'is-invalid' : ''" class="form-control form-control-sm"
                                        v-model="emailForm.name" placeholder="{{ __('general.Votre nom complet') }}">
                                        <div class="invalid-feedback">
                                            @{{ errors.name?.join('<br/>') }}
                                        </div>
                                </div>
                                <div class="mb-3 translates">
                                    <input type="text" name="" id="" :class="errors.email ? 'is-invalid' : ''"
                                        class="form-control form-control-sm" v-model="emailForm.email"
                                        placeholder="{{ __('general.Votre email') }}">
                                        <div class="invalid-feedback">
                                            @{{ errors.email?.join('<br/>') }}
                                        </div>
                                </div>
                                <div class="mb-3 translates">
                                    <input type="text" name="" id="" :class="errors.phone ? 'is-invalid' : ''"
                                        class="form-control form-control-sm" placeholder="{{ __('general.Votre téléphone') }}"
                                        v-model="emailForm.phone">
                                        <div :class="errors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                            @{{ errors.phone?.join('<br />') }}
                                        </div>
                                </div>
                                <div class="mb-2">
                                    <textarea name="" id="" rows="5" class="form-control form-control-sm" :class="errors.message ? 'is-invalid' : ''"
                                        v-model="emailForm.message" placeholder="{{ __('general.Votre message')}}"></textarea>
                                        <div class="invalid-feedback">
                                            @{{ errors.message?.join('<br/>') }}
                                        </div>
                                </div>
                                <div class="">
                                    <div class="conditions-item">
                                         <span class="translates">{{ __('general.enCliquant') }}</span>
                                    </div>
                                    <button @click="sendEmail()" class="btn btn-sm tagManager-sendMail" :disabled="emailLoading||obj.id_user=='{{ Auth::user()?Auth::user()->id??'':'' }}'">
                                        <i class="fa fa-paper-plane me-2" aria-hidden="true"></i>
                                        {{ __('general.Contacter') }}
                                        <div class="spinner-border spinner-border-sm ms-2" v-if="emailLoading"
                                            role="status">
                                            <span class="sr-only">{{ __('general.Loading...') }}</span>
                                        </div>
                                    </button>
                                    <button @click="showemail=false;" style="background-color: #dc3545;" class="btn btn-sm tagManager-sendMail mt-2">
                                        <i class="fa fa-close me-2" aria-hidden="true"></i>
                                        {{ __('general.Annuler') }}
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section class="item-detailed-info" @if(Session::get('lang') === 'ar') dir="rtl" @endif>
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-location"></i>
                                            {{ __('general.Localisation') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ localisation(obj) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-category"></i>
                                            {{ __('general.Catégorie') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.categorie }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.ref" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="fa-solid fa-hashtag"></i>
                                            {{ __('general.Référence') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ '#'+obj.ref }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.bedrooms" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-bed"></i>
                                            {{ __('general.Chambres') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.bedrooms }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.bathrooms" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-bath"></i>
                                            {{ __('general.Salles de bain') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.bathrooms }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.surface" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-surface"></i>
                                            {{ __('general.Superficie') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.surface2 ? 'de ' : '' }} @{{ obj.surface }}m² @{{ obj.surface2 ? (' à ' + obj.surface2) : '' }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.built_year" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-date"></i>
                                            {{ __('general.Date de construction') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.built_year }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.price_surface" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-price-per-meter"></i>
                                            {{ __('general.prix/m²') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.price_surface }}DH/m²
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.standing_des" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            {{ __('general.Standing') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.standing_des }}
                                        </span>
                                    </div>
                                </div>
                                <div v-if="obj.etage||obj.etage==0" class="col-12 col-lg-6">
                                    <div class="detailed-info-item">
                                        <span class="label">
                                            <i class="multilist-icons multilist-stairs"></i>
                                            {{ __('general.Étage') }}
                                        </span>
                                        <span class="value mstart-auto">
                                            @{{ obj.etage!=0?obj.etage!=-1?obj.etage:'RDJ':'RDC' + (obj.etage_total ? ('/' + obj.etage_total) : '') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="item-more-info">
                            <div v-if="obj.climatise==1" class="more-info-item1">
                                <i class="multilist-icons multilist-snowflake"></i>
                                <span class="value">
                                    {{ __('general.Climatisé') }}
                                </span>
                            </div>
                            <div v-if="obj.jardin==1" class="more-info-item1">
                                <i class="multilist-icons multilist-tree"></i>
                                <span class="value">
                                    {{ __('general.Jardin') }} @{{ obj.jardin_surface ? ': ' + obj.jardin_surface + 'm²' : '' }}
                                </span>
                            </div>
                            <div v-if="obj.piscine==1" class="more-info-item1">
                                <i class="multilist-icons multilist-pool"></i>
                                <span class="value">
                                    {{ __("general.Piscine") }}
                                </span>
                            </div>
                            <div v-if="obj.parking==1" class="more-info-item1">
                                <i class="multilist-icons multilist-parking"></i>
                                <span class="value">
                                    {{ __('general.Parking') }}
                                </span>
                            </div>
                            <div v-if="obj.meuble==1" class="more-info-item1">
                                <i class="multilist-icons multilist-sofa"></i>
                                <span class="value">
                                    {{ __('general.Meublé') }}
                                </span>
                            </div>
                            <div v-if="obj.terrace==1" class="more-info-item1">
                                <i class="multilist-icons multilist-terrasse"></i>
                                <span class="value">
                                    {{ __('general.Terrasse') }} @{{ obj.terrace_surface ? ': ' + obj.terrace_surface + 'm²' : '' }}
                                </span>
                            </div>
                            <div v-if="obj.syndic==1" class="more-info-item1">
                                <i class="multilist-icons multilist-syndic"></i>
                                <span class="value">
                                    {{ __('general.Syndic') }}
                                </span>
                            </div>
                            <div v-if="obj.cave==1" class="more-info-item1">
                                <i class="multilist-icons multilist-cave"></i>
                                <span class="value">
                                    {{ __("general.Cave") }}
                                </span>
                            </div>
                            <div v-if="obj.ascenseur==1" class="more-info-item1">
                                <i class="multilist-icons multilist-elevator"></i>
                                <span class="value">
                                    {{ __('general.Ascenseur') }}
                                </span>
                            </div>
                            <div v-if="obj.securite==1" class="more-info-item1">
                                <i class="multilist-icons multilist-camera"></i>
                                <span class="value">
                                    {{ __('general.Sécurité') }}
                                </span>
                            </div>
                            <div v-if="obj.balcony==1" class="more-info-item1">
                                <i class="multilist-icons multilist-terrasse"></i>
                                <span class="value">
                                    Balcon
                                </span>
                            </div>
                            <div v-if="obj.green_spaces==1" class="more-info-item1">
                                <i class="multilist-icons multilist-green_spaces"></i>
                                <span class="value">
                                    Espaces verts
                                </span>
                            </div>
                            <div v-if="obj.guardian==1" class="more-info-item1">
                                <i class="multilist-icons multilist-guardian"></i>
                                <span class="value">
                                    Gardiennage
                                </span>
                            </div>
                            <div v-if="obj.automation==1" class="more-info-item1">
                                <i class="multilist-icons multilist-automation"></i>
                                <span class="value">
                                    Domotique
                                </span>
                            </div>
                            <div v-if="obj.sea_view==1" class="more-info-item1">
                                <i class="multilist-icons multilist-sea_view"></i>
                                <span class="value">
                                    Vue sur mer
                                </span>
                            </div>
                            <div v-if="obj.box==1" class="more-info-item1">
                                <i class="multilist-icons multilist-box"></i>
                                <span class="value">
                                    Box
                                </span>
                            </div>
                            <div v-if="obj.equipped_kitchen==1" class="more-info-item1">
                                <i class="multilist-icons multilist-equipped_kitchen"></i>
                                <span class="value">
                                    Cuisine équipée
                                </span>
                            </div>
                            <div v-if="obj.soundproof==1" class="more-info-item1">
                                <i class="multilist-icons multilist-soundproof"></i>
                                <span class="value">
                                    Isolation phonique
                                </span>
                            </div>
                            <div v-if="obj.thermalinsulation==1" class="more-info-item1">
                                <i class="multilist-icons multilist-thermalinsulation"></i>
                                <span class="value">
                                    Isolation thermique
                                </span>
                            </div>
                            <div v-if="obj.playground==1" class="more-info-item1">
                                <i class="multilist-icons multilist-playground"></i>
                                <span class="value">
                                    Aire de jeux
                                </span>
                            </div>
                            <div v-if="obj.gym==1" class="more-info-item1">
                                <i class="multilist-icons multilist-gym"></i>
                                <span class="value">
                                    Salle de fitness
                                </span>
                            </div>
                            <div v-if="obj.Chimney==1" class="more-info-item1">
                                <i class="multilist-icons multilist-Chimney"></i>
                                <span class="value">
                                    Cheminée
                                </span>
                            </div>
                            <div v-if="obj.sportterrains==1" class="more-info-item1">
                                <i class="multilist-icons multilist-sportterrains"></i>
                                <span class="value">
                                    Terrains de sport
                                </span>
                            </div>
                            <div v-if="obj.library==1" class="more-info-item1">
                                <i class="multilist-icons multilist-library"></i>
                                <span class="value">
                                    Bibliothèque
                                </span>
                            </div>
                            <div v-if="obj.double_orientation==1" class="more-info-item1">
                                <i class="multilist-icons multilist-double_orientation"></i>
                                <span class="value">
                                    Double orientation
                                </span>
                            </div>
                            <div v-if="obj.intercom==1" class="more-info-item1">
                                <i class="multilist-icons multilist-intercom"></i>
                                <span class="value">
                                    Interphone
                                </span>
                            </div>
                            <div v-if="obj.garage==1" class="more-info-item1">
                                <i class="multilist-icons multilist-garage"></i>
                                <span class="value">
                                    Garage
                                </span>
                            </div>
                            <div v-if="obj.double_glazing==1" class="more-info-item1">
                                <i class="multilist-icons multilist-double_glazing"></i>
                                <span class="value">
                                    Double vitrage
                                </span>
                            </div>
                        </section>

                        <section class="item-section description-item translates">
                            <div class="section-title description-title">
                                {{  __('general.déscription') }}
                            </div>
                            <div class="description-item-content">
                                <p class="description-item-content-paragraph ln5" id="description-item-content-paragraph">
                                    <pre style="white-space: pre-wrap;font-family: inherit;">@{{ seeAll == false ? obj.description?.substring(0, 300) : obj.description }}</pre> <span v-if="obj.description?.length>300&&seeAll==false">...
                                        <span class="seeMore translates" @click="seeAll=true">{{ __('general.Voir plus') }}</span></span>
                                </p>
                                <div v-if="seeAll" @click="seeAll=false" class="seeMore">{{ __("general.Voir moins") }}</div>

                            </div>
                        </section>



                        {{-- modal end --}}
                        <!-- Button trigger modal -->

                            <!-- Modal -->
                            <div class="modal fade shadow" id="abusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title text-center" id="ModalLabel">
                                        {{ __('general.Quel est le problème avec cette annonce?')}}
                                    </h5>
                                    <div class="translates">
                                        <button type="button" class="btn-close translates" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    </div>
                                    <div class="modal-body translates">
                                        <h5 class="text-secondary">{{ __('general.Entrer votre e-mail') }} :</h5>

                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="fa-sharp fa-solid fa-envelope"></i></span>
                                            <input v-model="abusForm.email" type="email" class="form-control" placeholder="{{ __('general.Votre email') }}" aria-label="Email" aria-describedby="basic-addon1" :class="abusErrors.email ? 'is-invalid' : ''">
                                            <div class="invalid-feedback">
                                                @{{ abusErrors.email?.join('<br/>') }}
                                            </div>
                                        </div>
                                        <div class="mb-3 translates">
                                            <h5 class="mt-3">{{ __('general.Sélectionner le type d’abus') }} :</h5>
                                            <select v-model="abusForm.type" class="form-select translates" aria-label="Type select" :class="abusErrors.type ? 'is-invalid' : ''">
                                                <option value="Fraude">{{ __('general.Fraude') }}</option>
                                                <option value="Doublon">{{__('general.Doublon')}}</option>
                                                <option value="Mauvaise catégorie">{{ __('general.Mauvaise catégorie') }}</option>
                                                <option value="Mauvaise photo">{{ __('general.Mauvaise photo') }}</option>
                                                <option value="Mauvais prix">{{ __('general.Mauvais prix') }}</option>
                                                <option value="Faux numéro">{{ __('general.Faux numéro') }}</option>

                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ abusErrors.type?.join('<br/>') }}
                                            </div>

                                        </div>
                                        <h5 class="text-secondary">
                                            <i class="fa-regular fa-message"></i>
                                            {{ __('general.Enter votre message') }}
                                        </h5>
                                        <div class="mb-3">
                                            <label for="FormControlTextarea1" class="form-label"> {{ __('general.Message') }} :</label>
                                            <textarea name="" v-model="abusForm.message" class="form-control" id="FormControlTextarea1" placeholder="{{ __("general.Décrivez un petit peu l'abus signalé") }}" rows="4" :class="abusErrors.message ? 'is-invalid' : ''"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ abusErrors.message?.join('<br/>') }}
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"> {{ __('general.Annuler') }} </button>
                                    <button @click="sendAbus" type="button" class="btn border" style="color: #0d6efd">
                                        <i class="fa-regular fa-paper-plane"></i>
                                        {{ __('general.Envoyer') }}
                                    </button>

                                    </div>
                                </div>
                                </div>
                            </div>
                            {{-- modal end --}}

                        <section class="item-section map-item d-none" id="loc-section">
                            <div class=" section-title description-title translates">
                                {{ __('general.Localisation') }}
                            </div>
                            <div class="map-item-content">
                                <div id='map_cnt1' style="position:relative;">
                                    <div id="map-filter-group"></div>
                                    <div id='map1' class="mb-3" style="width: 100%;height:300px;"></div>
                                </div>
                            </div>
                            <div class="share_location_cnt">
                                <a :href="`https://www.waze.com/ul?ll=${obj.loclat}%2C${obj.loclng}&navigate=yes&zoom=17`"
                                    target="_blank">
                                        <i class="fa-brands fa-waze"></i>
                                    Waze
                                </a>
                            </div>
                        </section>

                        <section v-if="obj.nearbyPlaces&&obj.nearbyPlaces.length>0"
                            class="item-section item-detailed-info ">
                            <div class=" section-title description-title">
                                {{ __('general.Lieux à proximité') }}
                            </div>
                            <div class="row">
                                <div v-for="n of obj.nearbyPlaces" class="col-12">
                                    <div class="detailed-info-item" style="display: flex;">
                                        <span class="label" style="flex: 1;">
                                            <i v-if="n.icon" :class="'fa-solid fa-' + n.icon"></i>
                                            @{{ n.designation }}
                                        </span>
                                        <span class="value" style="flex: 1;">
                                            @{{ n.title }}
                                        </span>
                                        <span class="value" style="flex: 1;">
                                            @{{ n.distance }} m
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section v-if="obj.parent_cat==5" class="item-section booking-item" id="booking-section">
                            <div class="section-title description-title">
                                <h5 class="translates">{{ __('general.Résérver') }}</h5>
                            </div>

                            <ml-calendar :id="id" v-on:date-click="dateClick"></ml-calendar>

                            <!-- booking Modal -->
                            <div class="modal" :class="bookingModal?'show':''">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ __('general.Résérver') }}</h5>
                                            <button type="button" class="btn-close" @click="bookingModal=false;"
                                                ></button>
                                        </div>
                                        <div class="modal-body contact-owner"
                                            style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;max-width: 520px;margin: auto;">

                                            <div class="sendmail">
                                                <div class="mb-3">
                                                    <div class="inner-addon right-addon">
                                                        <i class="fa-regular fa-calendar-alt glyphicon"></i>
                                                        <input type="text" name="" id="datepicker" :class="bookingErrors.startDate||bookingErrors.endDate ? 'is-invalid' : ''" class="form-control form-control-sm" placeholder="{{ __('general.Date') }}">
                                                        <div class="invalid-feedback">
                                                            @{{ bookingErrors.startDate?.join('<br/>') }} <br>
                                                            @{{ bookingErrors.endDate?.join('<br/>') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="" id="" :class="bookingErrors.name ? 'is-invalid' : ''" class="form-control form-control-sm"
                                                        v-model="bookingForm.name" placeholder="{{ __('general.Votre nom complet') }}">
                                                        <div class="invalid-feedback">
                                                            @{{ bookingErrors.name?.join('<br/>') }}
                                                        </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="" id="" :class="bookingErrors.email ? 'is-invalid' : ''"
                                                        class="form-control form-control-sm" v-model="bookingForm.email"
                                                        placeholder="{{ __('general.Votre email') }}">
                                                        <div class="invalid-feedback">
                                                            @{{ bookingErrors.email?.join('<br/>') }}
                                                        </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="" id="" :class="bookingErrors.phone ? 'is-invalid' : ''"
                                                        class="form-control form-control-sm" placeholder="{{ __('general.Votre téléphone') }}"
                                                        v-model="bookingForm.phone">
                                                        <div :class="bookingErrors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                                            @{{ bookingErrors.phone?.join('<br />') }}
                                                        </div>
                                                </div>
                                                <div class="">
                                                    <div class="conditions-item translates">
                                                        {{ __('general.enCliquant') }}
                                                    </div>
                                                    <button @click="addBoooking()" class="btn btn-sm" :disabled="bookLoading||obj.id_user=='{{ Auth::user()?Auth::user()->id??'':'' }}'">
                                                        <i class="fa fa-paper-plane me-2" aria-hidden="true"></i>
                                                        {{ __('general.Valider') }}
                                                        <div class="spinner-border spinner-border-sm ms-2" v-if="bookLoading"
                                                            role="status">
                                                            <span class="sr-only">{{ __('general.Loading...') }}</span>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" @click="bookingModal=false;"
                                                >{{ __('general.Annuler') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End booking Modal-->

                        </section>

                        <section :class="similarItems.length>0&&(!obj.is_project||obj.is_project==0)?'':'d-none'" class="item-section">
                            <div class="section-heading translates">
                                <h1>{{ __('general.Annonces similaires') }}</h1>
                                <div class="heading-underline"></div>
                            </div>
                            <div class="slide noselect" id="slide-similarads">

                                <button class="mov-btn-next">
                                    <i class="fa-solid fa-angle-right"></i>
                                </button>
                                <button class="mov-btn-prev">
                                    <i class="fa-solid fa-angle-left"></i>
                                </button>
                                <div class="slide-item" v-for="s of similarItems" @click="toItemPage($event,s)">

                                    <div class="multiads">
                                        <div class="img media">
                                            <Slider :images="s.images"></Slider>
                                        </div>
                                        <div class="content">
                                            <i :class="favoris.find((e) => e == s.id) ? 'fas primary-c' : 'far'"
                                                class=" btn fa-star favori-ico" @click="toggleFavoris(s.id)"></i>
                                            <div class="title">
                                                <a href="#">
                                                    <h1> @{{ s.title }}</h1>
                                                </a>
                                            </div>
                                            <div class="desc" style="font-size: 14px;">
                                                <span>@{{ s.categorie }}</span>
                                            </div>
                                            <div class="location" style="color: gray;">
                                                <span>
                                                    <span><i style="font-size: 28px;" class="multilist-icons multilist-location"></i> @{{ localisation(s) }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="price">
                                                <span> @{{ price(s) }} </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="similarItems">
                                <div v-for="s of similarItems" class="multiads" @click="toItemPage($event,s)">
                                    <div class="img media">
                                        <Slider :images="s.images"></Slider>
                                    </div>
                                    <div class="content">

                                        <i :class="favoris.find((e) => e == s.id) ? 'fas primary-c' : 'far'"
                                            class=" btn fa-star favori-ico" @click="toggleFavoris(s.id)"></i>
                                        <div class="title">
                                            <a href="#">
                                                <h1> @{{ s.title }}</h1>
                                            </a>
                                        </div>
                                        <div class="desc" style="font-size: 14px;">
                                            <span>@{{ s.categorie }}</span>
                                        </div>
                                        <div class="location" style="color: gray;">
                                            <span>
                                                <span><i class="multilist-icons multilist-location"></i> @{{ localisation(s) }}
                                                </span>
                                            </span>
                                        </div>
                                        <div class="price" style="margin-left: auto;color:red;">
                                            <span> @{{ price(s) }} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="seeMoreAds" :href="'/list?categorie=' + obj.catid + '&city=1' + obj.loccity">Voir
                                plus...</a>--}}
                        </section>
                        <section v-if="dispos.length>0&&obj.is_project==1" class="item-section">
                            <div class="section-heading">
                                <h1>{{ __('general.LES DISPONIBILITÉS DU PROJET') }}</h1>
                                <div class="heading-underline"></div>
                            </div>
                            <div class="Projects_dispo">
                                <a v-for="l of dispos" class="list-item" @click="toItemPage($event,l)">
                                    <div class="list-item-container" style="position: relative;">
                                        <div v-if="l.premium" class="ribbon"><span>{{ __('general.PREMIUM') }}</span></div>

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
                                                    class=" btn fa-star favori-ico" @click="toggleFavoris(l.id)"></i>
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
                                                        <i style="font-size: 28px;" class="multilist-icons multilist-location"></i>
                                                        @{{ localisation(l) }}
                                                    </span>
                                                </div>

                                                <div class="price">
                                                    <span>@{{ price(l) }}</span>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="item-info">
                                            <span class="publication-date">
                                                <i class="fa-regular fa-calendar-alt me-2"></i>
                                                <span class="translates">{{ __('general.Publié le') }}  @{{ formatDateTime(l.date) }}</span>
                                            </span>
                                            <span class="features">

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

                                            </span>
                                        </div>

                                    </div>
                                </a>
                            </div>
                        </section>

                        <div  id="flexible" class="translates">
                            <a v-if="obj.categorie" :href="'/list?categorie=' + obj.catid"><span class="badge rounded-pill">#@{{ obj.categorie }}</span></a>
                            <a v-if="obj.city" :href="'/list?categorie=' + obj.catid + '&city=' + obj.loccity" ><span class="badge rounded-pill">#@{{ obj.city }}</span></a>
                            <a v-if="obj.neighborhood" :href="'/list?categorie=' + obj.catid + '&city=' + obj.loccity + '&neighborhood=' + obj.locdept" style="margin: 5px;"><span class="badge rounded-pill">#@{{ obj.neighborhood }}</span></a>
                            <a v-if="obj.type" :href="'/list?type=' + obj.type"><span class="badge rounded-pill">#@{{ obj.type }}</span></a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-3 contact-owner-container d-none d-lg-block">
                    <div class="contact-owner">
                        <div class="owner-info">
                            <img v-if="obj.usertype==4||obj.usertype==3" class="avatar img-fluid"
                                :src="obj.avatar ? '/storage' + obj.avatar :
                                    '/images/default-logo.png'" />
                            <div class="d-flex flex-column">
                                <div v-if="obj.usertype==4||obj.usertype==3" class="owner-name">
                                    <a :href="'/profile/' + obj.id_user">
                                        <h1>@{{ obj.company ?? obj.username }}</h1>
                                    </a>
                                </div>

                                <div v-if="obj.id_user==currentUserId" class="status_box" :class="'s_' + obj.status"
                                    style="margin-top: 10px;">@{{ displayStatus(obj.status) }}
                                </div>

                                {{--<div style="margin-top: 5%;">
                                    <button type="button" class="btn btn-light btn-sm" v-if="obj.id_user==currentUserId"
                                        @click="updateAd(obj)" aria-hidden="true" title="Modifier l'annonce">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" v-if="obj.id_user==currentUserId"
                                        @click="boostAd(obj)" aria-hidden="true" title="Booster l'annonce">
                                        <i class="fa fa-rocket" style="color:blue;"></i>
                                    </button>
                                    <button type="button" class="btn btn-light btn-sm" v-if="obj.id_user==currentUserId"
                                        @click="deleteAd()" aria-hidden="true" title="Supprimer l'annonce">
                                        <i class="fa fa-trash" style="color:red;"></i>
                                    </button>
                                </div>--}}

                                <div class="multiads-btns-cnt" v-if="obj.id_user==currentUserId">
                                    <button class="btn multiads-btn boost"
                                    @click="boostAd(obj)">
                                        <i class="fas fa-rocket"></i> {{ __('general.Booster') }}
                                    </button>
                                    <div style="display: flex; flex: 1 1 0%;">
                                        <button class="btn multiads-btn"
                                        @click="updateAd(obj)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn multiads-btn delete"
                                        @click="deleteAd()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>


                                <div v-if="obj.c_phone||obj.user_phone" @click="displayPhone()" class="owner-phone tagManager-phone translates"  :class="showPhone?'active-phone':''">
                                    <i class="multilist-icons multilist-phone-white" aria-hidden="true"></i>
                                    <h2 v-if="!showPhone">{{ __('general.Afficher le numéro') }}</h2>
                                    <a :href="'tel: ' + (obj.c_phone ?? obj.user_phone)" style="color: inherit;"
                                        v-if="showPhone">@{{ obj.c_phone ?? obj.user_phone }}</a>
                                </div>
                                <div v-if="obj.c_phone2" @click="displayPhone2()" class="owner-phone tagManager-phone" :class="showPhone2?'active-phone':''">
                                    <i class="multilist-icons multilist-phone-white" aria-hidden="true"></i>
                                    <h2 v-if="!showPhone2">{{ __('general.Afficher le numéro 2')}}</h2>
                                    <a :href="'tel: ' + obj.c_phone2" style="color: inherit;" v-if="showPhone2">@{{ obj.c_phone2 }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around owner-social">
                            <a v-if="obj.c_wtsp" @click="addClick('wtsp')" class="btn owner-whatsapp tagManager-wtsp"
                                :href="'https://wa.me/' + obj.c_wtsp +
                                    '?text=Je suis intéressé par votre annonce: {{ request()->getHost() }}/item/' + obj
                                    .id"
                                target="_blank">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a v-if="obj.c_telegram" class="btn owner-whatsapp tagManager-wtsp"
                                :href="'https://telegram.me/' + obj.c_telegram +
                                    '?text=Je suis intéressé par votre annonce: {{ request()->getHost() }}/item/' + obj
                                    .id"
                                target="_blank">
                                <i class="fa-brands fa-telegram"></i>
                            </a>
                        </div>
                        <div class="sendmail">
                            <div class="mb-3 translates">
                                <input type="text" name="" id=""
                                    :class="errors.name ? 'is-invalid' : ''" class="form-control form-control-sm"
                                    v-model="emailForm.name" placeholder="{{ __('general.Votre nom complet') }}">
                                <div class="invalid-feedback">
                                    @{{ errors.name?.join('<br/>') }}
                                </div>
                            </div>
                            <div class="mb-3 translates">
                                <input type="text" name="" id=""
                                    :class="errors.email ? 'is-invalid' : ''" class="form-control form-control-sm"
                                    v-model="emailForm.email" placeholder="{{ __('general.Votre email') }}">
                                <div class="invalid-feedback">
                                    @{{ errors.email?.join('<br/>') }}
                                </div>
                            </div>
                            <div class="mb-3 translates">
                                <input type="text" name="" id=""
                                    :class="errors.phone ? 'is-invalid' : ''" class="form-control form-control-sm"
                                    v-model="emailForm.phone" placeholder="{{ __('general.Votre téléphone') }}">
                                    <div :class="errors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                        @{{ errors.phone?.join('<br />') }}
                                    </div>
                            </div>
                            <div class="mb-2 translates">
                                <textarea name="" id="" rows="5" :class="errors.message ? 'is-invalid' : ''"
                                    class="form-control form-control-sm" v-model="emailForm.message" placeholder="{{ __('general.Votre message') }}"></textarea>
                                <div class="invalid-feedback">
                                    @{{ errors.message?.join('<br/>') }}
                                </div>
                            </div>
                            <div class="translates">
                                <div class="conditions-item">
                                   {{__('general.enCliquant')}}
                                </div>
                                <button @click="sendEmail()" class="btn btn-sm tagManager-sendMail" :disabled="emailLoading||obj.id_user=='{{ Auth::user()?Auth::user()->id??'':'' }}'">
                                    <i class="fa fa-paper-plane me-2" aria-hidden="true"></i>
                                    {{ __('general.Contacter') }}
                                    <div class="spinner-border spinner-border-sm ms-2" v-if="emailLoading"
                                        role="status">
                                        <span class="sr-only">{{ __('general.Loading...') }}</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section class="more-items">
        </section>
    </div>

    @if (Auth()->user())
        <div id="booster-app" class="d-none">
            <popsup-component :title="`Booster l'annonce`" v-model:display="display">
                <div class="s_popup-component-container d-none">
                    <section class="section">
                        <div class="card">
                            <div class="card-body" id="custom-card">

                                <button type="button" class="btn btn-primary" style="  background-color: #EE9522; !important; border-color: whitesmoke;
                                " disabled>
                                    <i class="fa-solid fa-coins"></i> @{{  coins }} LTC
                                  </button>

                                  <button type="button" class="btn btn-primary" style="  background-color: #6518ca; !important; border-color: whitesmoke;
                                " @click="Recharger()">
                                    <i class="fa-solid fa-circle-plus"></i> {{ __('general.Recharger') }}
                                  </button>


                                <div v-for="option in options">
                                    <fieldset v-if="option.options.length!=0" class="filter_cnt">
                                        <legend>@{{ option.designation }}</legend>
                                        <div class="option-desc" style="text-align: center;">@{{ option.description }}</div>
                                        <div class="option_cards_cnt">
                                            <div class="option_card" v-for="op in option.options"
                                                :class="form.option == op.id ? 'active' : ''"
                                                @click="form.option==op.id?form.option=null:form.option=op.id">
                                                <div class="option_title">@{{ op.designation }}</div>
                                                <div class="option_price"><strong>@{{ op.price }} <i
                                                            class="multilist-icons multilist-listcoin"></i></strong></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div style="text-align: center; padding-top: 20px;">
                                <button class="btn btn-success" id='custom-btn' @click="save0" :disabled="loader">
                                    <span>{{ __('general.Sauvegarder') }}</span>
                                    <div class="spinner-border spinner-border-sm ms-2" v-if="loader" role="status">
                                        <span class="sr-only">{{ __('general.Loading...') }}</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </popsup-component>
        </div>

        <div id="updateAd-app" class="d-none">
            <popup-component :title="`Modifier l'annonce`" v-model:display="display">
                <div class="container-md" style="max-width: 850px;padding-bottom: 100px;">

                    <section id="add-section" class="add-section ">

                        <div class="section-title page-title">{{ __('general.Modifier l\'annonce') }}</div>

                        <div class="">

                            @if (Auth()->user()->usertype != 3)
                                <section class="item-section" id="type-section">
                                    <div class="section-heading">
                                        <h1>{{ __('general.Type') }}:</h1>
                                        <div class="heading-underline"></div>
                                    </div>

                                    <div class="type-cards-container">
                                        <div v-for="t of types" class="type-card" :class="t == type ? 'active-card' : ''"
                                            @click="selectType(t)">
                                            @{{ t }}
                                        </div>
                                    </div>
                                </section>

                                <section class="item-section" id="category-section">
                                    <div class="section-heading">
                                        <h1>Catégorie:</h1>
                                        <div class="heading-underline"></div>
                                    </div>

                                    <select name="catid" class="form-select select2init"
                                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                        v-model="form.catid">
                                        <option :value="null">Choisir une Catégorie</option>
                                        <template v-for="cat in categories">
                                            <option v-if="cat.is_project!=1" :value="cat.id">@{{ cat.title }}
                                            </option>
                                        </template>
                                    </select>

                                    {{--<div class="cat-cards-container">
                                        <template v-for="c in categories">
                                            <div v-if="c.type==type" class="cat-card"
                                                :class="c.id == form.catid ? 'active-card' : ''" @click="selectCat(c.id)">
                                                @{{ c.title }}</div>
                                        </template>
                                    </div>--}}
                                </section>
                            @endif

                            <section class="item-section" id="loc-section">
                                <div class="section-heading">
                                    <h1>Localisation <span style="color: red">*</span> :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div class="">
                                    <div class="row">
                                        <div class="form-group col-6 mb-4">
                                            <label for="">Ville :</label>

                                            <div>

                                            <ml-select :options="cities" value="id" label="name" @change="changeCity()"
                                                v-model:selected-option="form.loccity"
                                                :mls-class="errors.loccity ? 'is-invalid form-control translate notMobile' : 'form-control translate notMobile'"
                                                mls-placeholder="{{ __('general.select') }}" />

                                            </div>
                                            {{-- <select class="form-select" id="" v-model="form.loccity"
                                                @change="changeCity()">
                                                <option :value="null">Sélectionnez une ville</option>
                                                <option v-for="c of cities" :value="c.id">@{{ c.name }}
                                                </option>
                                            </select>--}}

                                            <div class="invalid-feedback" style="display:block">
                                                @{{ errors.loccity?.join('<br/>') }}
                                            </div>

                                        </div>

                                        <div class="form-group col-6 mb-4">
                                            <label for="">Quartier :</label>

                                            <div>
                                            <ml-select :options="neighborhood" :hidden="!form.loccity" value="id"
                                                label="name" @change="changeDept()" v-model:selected-option="form.locdept"
                                                :mls-class="errors.locdept ? 'is-invalid form-control notMobile' : 'form-control notMobile w-100'"
                                                mls-placeholder="{{ __('general.Sélectionner un quartier') }}" />
                                            </div>

                                            {{-- <select class="form-select" id="" v-model="form.locdept"
                                                :disabled="form.loccity == null" @change="changeDept()">
                                                <option :value="null">Sélectionnez un quartier</option>
                                                <option :value="-1">Autre</option>
                                                <option v-for="n of neighborhood" :value="n.id">
                                                    @{{ n.name }}</option>
                                            </select> --}}

                                            <div class="invalid-feedback" style="display:block">
                                                @{{ errors.locdept?.join('<br/>') }}
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-6 mb-4" id="dept2_cnt" v-if="form.locdept==0">
                                        <label class="col-sm-4 col-form-label">Ajouter un quartier</label>
                                        <div class="col-sm-8">
                                            <input id="locdept2" name="dept2" type="text" class="form-control"
                                                maxlength="199" v-model="form.locdept2">
                                        </div>
                                    </div>

                                    <button id="btn-locate" class="d-none btn" @click="locateMap()">
                                        <i class="fas fa-location-dot"></i>
                                        Localiser sur la map
                                    </button>

                                    <button id="btn-locate-cancel" class="d-none btn" @click="cancelLocateMap()">
                                        <i class="fa-solid fa-close"></i>
                                        Annuler la localisation sur la map
                                    </button>


                                    <div id='map_cnt' class="d-none">
                                        <div id="map-filter-group"></div>
                                        <input type="text" id="mapSearch" class="mapSearch" placeholder="(lat,long)">
                                        <div id='map' class="mb-3" style="width: 100%;height:400px;"></div>
                                    </div>
                                </div>
                            </section>

                            <section class="item-section" id="features-section">
                                <div class="section-heading">
                                    <h1>Caractéristiques:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div class="row">

                                    <div v-if="showField('rooms')" class="col-12 d-flex flex-column">
                                        <label for="">Pieces :</label>
                                        <div class="features">
                                            <template v-if="!customRooms">
                                                <span v-for="p in pieces"
                                                    @click="form.rooms==p?(form.rooms=null):(form.rooms=p)"
                                                    class="selectable-item"
                                                    :class="p == form.rooms ? 'selected' : ''">@{{ p }}</span>
                                                <span class="selectable-item"
                                                    @click="customRooms=true;form.rooms=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.rooms" class="form-control"
                                                    placeholder="Pieces" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customRooms=false;form.rooms=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showField('bedrooms')" class="col-12 d-flex flex-column">
                                        <label for="">Chambres :</label>
                                        <div class="features">
                                            <template v-if="!customBedrooms">
                                                <span v-for="p in pieces"
                                                    @click="form.bedrooms==p?(form.bedrooms=null):(form.bedrooms=p)"
                                                    class="selectable-item"
                                                    :class="p == form.bedrooms ? 'selected' : ''">@{{ p }}</span>
                                                <span class="selectable-item"
                                                    @click="customBedrooms=true;form.bedrooms=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.bedrooms" class="form-control"
                                                    placeholder="Chambres" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customBedrooms=false;form.bedrooms=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showField('bathrooms')" class="col-12 d-flex flex-column">
                                        <label for="">Salles de bain :</label>
                                        <div class="features">
                                            <template v-if="!customWc">
                                                <span v-for="p in pieces" @click="form.wc==p?(form.wc=null):(form.wc=p)"
                                                    class="selectable-item"
                                                    :class="p == form.wc ? 'selected' : ''">@{{ p }}</span>
                                                <span class="selectable-item"
                                                    @click="customWc=true;form.wc=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.wc" class="form-control"
                                                    placeholder="Salles de bain" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customWc=false;form.wc=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showField('etage')" class="col-12 d-flex flex-column">
                                        <label for="">Étage :</label>
                                        <div class="features">
                                            <template v-if="!customEtage">
                                                <span v-for="p in etages" @click="form.etage==p?(form.etage=null):(form.etage=p)"
                                                    class="selectable-item"
                                                    :class="p == form.etage ? 'selected' : ''">@{{ p!=0?p!=-1?p:'RDJ':'RDC' }}</span>
                                                <span class="selectable-item" @click="customEtage=true;form.etage=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.etage" class="form-control"
                                                    placeholder="Étage" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customEtage=false;form.etage=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showField('etage_total')" class="col-12 d-flex flex-column">
                                        <label for="">{{ __("general.Les étages total de l'immeuble") }} :</label>
                                        <div class="features">
                                            <template v-if="!customEtageTotal">
                                                <span v-for="p in pieces"
                                                    @click="form.etage_total==p?(form.etage_total=null):(form.etage_total=p)"
                                                    class="selectable-item"
                                                    :class="p == form.etage_total ? 'selected' : ''">@{{ p }}</span>
                                                <span class="selectable-item"
                                                    @click="customEtageTotal=true;form.etage_total=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.etage_total" class="form-control"
                                                    placeholder="Étage" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customEtageTotal=false;form.etage_total=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="showField('contriction_date')" class="col-12 d-flex flex-column">
                                        <label for="">L'année de construction :</label>
                                        <div class="features">
                                            <template v-if="!customContrictionDate">
                                                <span v-for="p in years"
                                                    @click="form.contriction_date==p?(form.contriction_date=null):(form.contriction_date=p)"
                                                    class="selectable-item"
                                                    :class="p == form.contriction_date ? 'selected' : ''">@{{ p }}</span>
                                                <span class="selectable-item"
                                                    @click="customContrictionDate=true;form.contriction_date=null;">Autre</span>
                                            </template>
                                            <div class="other" v-else>
                                                <input type="number" v-model="form.contriction_date"
                                                    class="form-control" placeholder="Année" />
                                                <button class="btn btn-sm text-danger"
                                                    @click="customContrictionDate=false;form.contriction_date=null;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Media --->
                                    <div v-if="showField('standing')" class="col-12 d-flex flex-column" v-if="standings.length>0">
                                        <label for="">Standing :</label>
                                        <div class="features">
                                            <span v-for="p in standings"
                                                @click="form.standing==p.id?(form.standing=null):(form.standing=p.id)"
                                                class="selectable-item"
                                                :class="p.id == form.standing ? 'selected' : ''">@{{ p.designation }}</span>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex flex-column">
                                        <label for="">Autre :</label>
                                        <div class="item-more-info">
                                            <div v-if="showField('climatise')" :class="form.climatise == true ? 'selected' : ''"
                                                @click="form.climatise=!form.climatise;" class="more-info-item">
                                                <i class="multilist-icons multilist-snowflake"></i>
                                                <span class="value">
                                                    Climatisé
                                                </span>
                                            </div>
                                            <div v-if="showField('jardin')" :class="form.jardin == true ? 'selected' : ''"
                                                @click="form.jardin=!form.jardin;" class="more-info-item">
                                                <i class="multilist-icons multilist-tree"></i>
                                                <span class="value">
                                                    Jardin
                                                </span>
                                            </div>
                                            <div v-if="showField('piscine')" :class="form.piscine == true ? 'selected' : ''"
                                                @click="form.piscine=!form.piscine;" class="more-info-item">
                                                <i class="multilist-icons multilist-pool"></i>
                                                <span class="value">
                                                    Piscine
                                                </span>
                                            </div>
                                            <div v-if="showField('parking')" :class="form.parking == true ? 'selected' : ''"
                                                @click="form.parking=!form.parking;" class="more-info-item">
                                                <i class="multilist-icons multilist-parking"></i>
                                                <span class="value">
                                                    Parking
                                                </span>
                                            </div>
                                            <div v-if="showField('meuble')" :class="form.meuble == true ? 'selected' : ''"
                                                @click="form.meuble=!form.meuble;" class="more-info-item">
                                                <i class="multilist-icons multilist-sofa"></i>
                                                <span class="value">
                                                    Meublé
                                                </span>
                                            </div>
                                            <div v-if="showField('terrace')" :class="form.terrace == true ? 'selected' : ''"
                                                @click="form.terrace=!form.terrace;" class="more-info-item">
                                                <i class="multilist-icons multilist-terrasse"></i>
                                                <span class="value">
                                                    Terrasse
                                                </span>
                                            </div>
                                            <div v-if="showField('syndic')" :class="form.syndic == true ? 'selected' : ''"
                                                @click="form.syndic=!form.syndic;" class="more-info-item">
                                                <i class="multilist-icons multilist-syndic"></i>
                                                <span class="value">
                                                    syndic
                                                </span>
                                            </div>
                                            <div v-if="showField('cave')" :class="form.cave == true ? 'selected' : ''"
                                                @click="form.cave=!form.cave;" class="more-info-item">
                                                <i class="multilist-icons multilist-cave"></i>
                                                <span class="value">
                                                    Cave
                                                </span>
                                            </div>
                                            <div v-if="showField('ascenseur')" :class="form.ascenseur == true ? 'selected' : ''"
                                                @click="form.ascenseur=!form.ascenseur;" class="more-info-item">
                                                <i class="multilist-icons multilist-elevator"></i>
                                                <span class="value">
                                                    Ascenseur
                                                </span>
                                            </div>
                                            <div v-if="showField('securite')" :class="form.securite == true ? 'selected' : ''"
                                                @click="form.securite=!form.securite;" class="more-info-item">
                                                <i class="multilist-icons multilist-camera"></i>
                                                <span class="value">
                                                    Sécurité
                                                </span>
                                            </div>
                                            <div v-if="showField('balcony')" :class="form.balcony == true ? 'selected' : ''"
                                                @click="form.balcony=!form.balcony;" class="more-info-item">
                                                <i class="multilist-icons multilist-terrasse"></i>
                                                <span class="value">
                                                    {{ __('general.balcon') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('guardian')" :class="form.guardian == true ? 'selected' : ''"
                                                @click="form.guardian=!form.guardian;" class="more-info-item">
                                                <i class="multilist-icons multilist-guardian"></i>
                                                <span class="value">
                                                    {{ __('general.Gardiennage') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('green_spaces')" :class="form.green_spaces == true ? 'selected' : ''"
                                                @click="form.green_spaces=!form.green_spaces;" class="more-info-item">
                                                <i class="multilist-icons multilist-green_spaces"></i>
                                                <span class="value" style="text-align:center;">
                                                    {{ __('general.Espaces verts') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('automation')" :class="form.automation == true ? 'selected' : ''"
                                                @click="form.automation=!form.automation;" class="more-info-item">
                                                <i class="multilist-icons multilist-automation"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.domotique') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('sea_view')" :class="form.sea_view == true ? 'selected' : ''"
                                                @click="form.sea_view=!form.sea_view;" class="more-info-item">
                                                <i class="multilist-icons multilist-sea_view"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.Vue sur mer') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('box')" :class="form.box == true ? 'selected' : ''"
                                                @click="form.box=!form.box;" class="more-info-item">
                                                <i class="multilist-icons multilist-box"></i>
                                                <span class="value">
                                                    Box
                                                </span>
                                            </div>
                                            <div v-if="showField('equipped_kitchen')" :class="form.equipped_kitchen == true ? 'selected' : ''"
                                                @click="form.equipped_kitchen=!form.equipped_kitchen;" class="more-info-item">
                                                <i class="multilist-icons multilist-equipped_kitchen"></i>

                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.Cuisine équipée') }}
                                                </span>


                                            </div>
                                            <div v-if="showField('soundproof')" :class="form.soundproof == true ? 'selected' : ''"
                                                @click="form.soundproof=!form.soundproof;" class="more-info-item">
                                                <i class="multilist-icons multilist-soundproof"></i>

                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.isolation phonique') }}
                                                </span>

                                            </div>
                                            <div v-if="showField('thermalinsulation')" :class="form.thermalinsulation == true ? 'selected' : ''"
                                                @click="form.thermalinsulation=!form.thermalinsulation;" class="more-info-item">
                                                <i class="multilist-icons multilist-thermalinsulation"></i>

                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.isolation thérmique') }}
                                                </span>

                                            </div>
                                            <div v-if="showField('playground')" :class="form.playground == true ? 'selected' : ''"
                                                @click="form.playground=!form.playground;" class="more-info-item">
                                                <i class="multilist-icons multilist-playground"></i>

                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.aire de jeux') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('gym')" :class="form.gym == true ? 'selected' : ''"
                                                @click="form.gym=!form.gym;" class="more-info-item">
                                                <i class="multilist-icons multilist-gym"></i>

                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.salle de fitness') }}
                                                </span>

                                            </div>
                                            <div v-if="showField('Chimney')" :class="form.Chimney == true ? 'selected' : ''"
                                                @click="form.Chimney=!form.Chimney;" class="more-info-item">
                                                <i class="multilist-icons multilist-Chimney"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.cheminée') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('sportterrains')" :class="form.sportterrains == true ? 'selected' : ''"
                                                @click="form.sportterrains=!form.sportterrains;" class="more-info-item">
                                                <i class="multilist-icons multilist-sportterrains"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.terrains de sport') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('library')" :class="form.library == true ? 'selected' : ''"
                                                @click="form.library=!form.library;" class="more-info-item">
                                                <i class="multilist-icons multilist-library"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.bibliotheque') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('double_orientation')" :class="form.double_orientation == true ? 'selected' : ''"
                                                @click="form.double_orientation=!form.double_orientation;" class="more-info-item">
                                                <i class="multilist-icons multilist-double_orientation"></i>
                                                <span class="value">
                                                    {{ __('general.double orientation') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('intercom')" :class="form.intercom == true ? 'selected' : ''"
                                                @click="form.intercom=!form.intercom;" class="more-info-item">
                                                <i class="multilist-icons multilist-intercom"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.interphone') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('garage')" :class="form.garage == true ? 'selected' : ''"
                                                @click="form.garage=!form.garage;" class="more-info-item">
                                                <i class="multilist-icons multilist-garage"></i>
                                                <span class="value" style="text-align: center;">
                                                    {{ __('general.garage') }}
                                                </span>
                                            </div>
                                            <div v-if="showField('double_glazing')" :class="form.double_glazing == true ? 'selected' : ''"
                                                @click="form.double_glazing=!form.double_glazing;" class="more-info-item">
                                                <i class="multilist-icons multilist-double_glazing"></i>
                                                <span class="value" style="text-align: center;">
                                                   {{ __('general.double vitrage')}}
                                                </span>
                                            </div>




                                        </div>
                                    </div>

                                </div>

                            </section>

                            <section class="item-section" id="media-section">
                                <div class="section-heading">
                                    <h1>Media:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputRooms" class="col-sm-12 col-form-label">Images (png/jpeg)</label>
                                    <div class="col-sm-12">
                                        <small id="imageMaxSize" class="form-text text-muted">*Taille max
                                            {{ $imageSize }} MO</small>
                                        <upload-files-component v-model:files="form.images" type="images"
                                            :max="50" :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                            :multiple="true" />
                                    </div>
                                </div>

                                {{--  {{ $imageSize }}  --}}
                                <div class="row mb-3">
                                    <label for="inputRooms" class="col-sm-12 col-form-label">Vidéos (mp4)</label>
                                    <div class="col-sm-12">
                                        <small id="imageMaxSize" class="form-text text-muted">*Taille max
                                            {{ $videoSize }} MO</small>
                                        <upload-files-component v-model:files="form.videos" type="videos"
                                            :max="50" :allowed-extensions="['mp4', 'mov', 'ogg']"
                                            :multiple="true" />
                                    </div>
                                </div>

                            </section>

                            <section class="item-section" id="places-section">
                                <div class="section-heading">
                                    <h1>Lieux à promimité:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-multi" @click="AddPlaceModal">
                                        <i class="bi bi-plus me-1"></i> Ajouter un Lieu
                                    </button>
                                    <table id="nearbyPlaces">
                                        <tr v-for="place in form.nearbyPlaces">
                                            <td style="width:70%;">@{{ place.title }} <span class="distance">

                                                    @{{ place.distance }}M</span></td>
                                            <td style="width:30%;">
                                                <i class="fas fa-edit table-action" @click="updatePlaceModal(place)"></i>
                                                <i class="fa-solid fa-trash table-action"
                                                    @click="deletePlace(place.id)"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </section>

                            <section class="item-section" id="info-section">
                                <div class="section-heading">
                                    <h1>Informations générales:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div>
                                    <!-- General Form Elements -->
                                    <div class="row mb-3">
                                        <label for="inputSurface" class="col-sm-4 col-form-label">Superficie:</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input name="surface" type="number" class="form-control"
                                                    :class="errors.surface ? 'is-invalid' : ''" v-model="form.surface">
                                                <span class="input-group-text">m²</span>
                                                <div class="invalid-feedback">
                                                    @{{ errors.surface?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputPrice" class="col-sm-4 col-form-label">Prix <span style="color: red">*</span> :</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input name="price" type="number" class="form-control"
                                                    :class="errors.price ? 'is-invalid' : ''" v-model="form.price">
                                                <select name="price_curr" class="inputtypeselect"
                                                    v-model="form.price_curr">
                                                    <option value="DHS">DHS</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.price?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputPrice" class="col-sm-4 col-form-label">Prix/m²:</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input name="price_m" type="number" class="form-control"
                                                    :class="errors.price_m ? 'is-invalid' : ''" v-model="form.price_m">
                                                <select name="price_curr" class="inputtypeselect"
                                                    v-model="form.price_curr">
                                                    <option value="DHS">DHS</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.price_m?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputTitle" class="col-sm-4 col-form-label">Titre <span style="color: red">*</span> :</label>
                                        <div class="col-sm-8">
                                            <input name="title" type="text" class="form-control"
                                                :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                            <div class="invalid-feedback">
                                                @{{ errors.title?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRef" class="col-sm-4 col-form-label">Référence:</label>
                                        <div class="col-sm-8">
                                            <input name="ref" type="text" class="form-control"
                                                :class="errors.ref ? 'is-invalid' : ''" v-model="form.ref">
                                            <div class="invalid-feedback">
                                                @{{ errors.ref?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputDesc" class="col-sm-4 col-form-label">Descripton <span style="color: red">*</span> :</label>
                                        <div class="col-sm-8">
                                            <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 200px"
                                                v-model="form.description"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ errors.description?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <p style="font-size: 10px"><span style="color: red">*</span> : Champs obligatoires</p>
                            </section>

                            <section class="item-section" id="places-section">
                                <div class="section-heading">
                                    <h1>Information de contact:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div>
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Télephone:</label>
                                        <div class="col-sm-8">
                                            <select name="phone" class="form-select select2init"
                                                :class="errors.phone ? 'is-invalid' : ''" id="phones-select"
                                                v-model="form.phone">
                                                <option v-for="phone in userphones" :value="phone.id">

                                                    @{{ phone.value }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.phone?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Télephone 2:</label>
                                        <div class="col-sm-8">
                                            <select name="phone2" class="form-select select2init"
                                                :class="errors.phone2 ? 'is-invalid' : ''" id="phones-select"
                                                id="phones2-select" v-model="form.phone2">
                                                <option :value="null">Numéro de télephone</option>
                                                <option v-for="phone in userphones" :value="phone.id">
                                                    @{{ phone.value }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.phone2?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Whatsapp:</label>
                                        <div class="col-sm-8">
                                            <select name="wtsp" class="form-select select2init"
                                                :class="errors.dept2 ? 'is-invalid' : ''" id="wtsp-select"
                                                v-model="form.wtsp">
                                                <option :value="null">Numéro de whatsapp</option>
                                                <option v-for="wtsp in userWtsps" :value="wtsp.id">
                                                    @{{ wtsp.value }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.wtsp?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Email:</label>
                                        <div class="col-sm-8">
                                            <select name="email" class="form-select select2init"
                                                :class="errors.email ? 'is-invalid' : ''" id="emails-select"
                                                v-model="form.email">
                                                <option v-for="email in userEmails" :value="email.id">
                                                    @{{ email.value }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.email?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>

                            <div v-if="form.catid" class="card-body" style="text-align: center; padding-top: 20px;">
                                <button type="submit" class="btn btn-save" @click="save0('30')" :disabled="loading">
                                    Publier
                                    <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                                <button type="submit" class="btn btn-secondary" @click="save0('20')"
                                    :disabled="loading">
                                    Enregister Brouillon
                                    <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                                <button type="submit" class="btn btn-danger" @click="deleteAd"
                                    :disabled="loading">
                                    Supprimer
                                </button>
                            </div>

                        </div>

                        <!-- add place Modal -->
                        <div class="modal fade modal-close" id="addPlaceModal" data-id="addPlaceModal">
                            <div class="modal-dialog modal-lg">
                                <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ajouter lieu</h5>
                                        <button type="button" class="btn-close modal-close"
                                            data-id="addPlaceModal"></button>
                                    </div>
                                    <div class="modal-body"
                                        style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                        <div class="row mb-3">
                                            <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                            <div class="col-sm-8">
                                                <input id="place-title" name="place_title" type="text"
                                                    class="form-control" v-model="placesForm.title">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                            <div class="col-sm-8 ">
                                                <div class="input-group ">
                                                    <input name="place_distance" type="number" class="form-control"
                                                        v-model="placesForm.distance">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">type de lieu</label>
                                            <div class="col-sm-8">
                                                <select id="place-types" name="place_types" class="form-select"
                                                    v-model="placesForm.type">
                                                    <option :value="null">Choisir une type</option>
                                                    <option v-for="type in place_types" :value="type.id">

                                                        @{{ type.designation }}</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary modal-close"
                                            data-id="addPlaceModal">Close</button>
                                        <button id="addPlace" type="submit" class="btn btn-primary" @click="addPlace">
                                            Ajouter
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End add place Modal-->

                        <!-- UPDATE place Modal -->
                        <div class="modal fade modal-close" id="updatePlaceModal" data-id="updatePlaceModal">
                            <div class="modal-dialog modal-lg">
                                <form onsubmit="event.preventDefault()" id="updatePlaceForm" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Modifier lieu</h5>
                                        <button type="button" class="btn-close modal-close"
                                            data-id="updatePlaceModal"></button>
                                    </div>
                                    <div class="modal-body"
                                        style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                        <div class="row mb-3">
                                            <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                            <div class="col-sm-8">
                                                <input id="place-title" name="place_title" type="text"
                                                    class="form-control" v-model="placesFormUpdate.title">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                            <div class="col-sm-8 ">
                                                <div class="input-group ">
                                                    <input name="place_distance" type="number" class="form-control"
                                                        v-model="placesFormUpdate.distance">
                                                    <span class="input-group-text">m</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">type de lieu</label>
                                            <div class="col-sm-8">
                                                <select id="place-types" name="place_types" class="form-select"
                                                    v-model="placesFormUpdate.type">
                                                    <option :value="null">Choisir une type</option>
                                                    <option v-for="type in place_types" :value="type.id">

                                                        @{{ type.designation }}</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary modal-close"
                                            data-id="updatePlaceModal">Close</button>
                                        <button id="updatePlace" type="submit" class="btn btn-primary"
                                            @click="updatePlace">Modifier</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End UPDATE place Modal-->
                    </section>

                </div>
            </popup-component>
        </div>
    @endif

    <script>
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);

        const id = '{{ $id ?? '' }}';
        let picker = null;
        const currentUserId = '{{ Auth::user() ? Auth::user()->id ?? '' : '' }}';
        let itemApp = createApp({
            data() {

                return {
                    id: id,
                    status_arr: status_arr,
                    obj: {},
                    bookingModal:false,
                    showemail: false,
                    currentUserId: currentUserId,
                    similarItems: [],
                    dispos: [],
                    selectedMedia: 'photos', // photos || video || 3d
                    showPhone: false,
                    showPhone2: false,
                    seeAll: false,
                    bookingForm: {
                        ad_id: id,
                        name: "",
                        email: "",
                        phone: "",
                        startDate: "",
                        endDate: "",
                    },
                    emailForm: {
                        ad_id: id,
                        name: "",
                        email: "",
                        phone: "",
                        message: "",
                    },
                    abusForm: {
                        ad_id: id,
                        email: "",
                        type: "",
                        message: "",
                    },
                    emailLoading: false,
                    bookLoading: false,
                    errors: {},
                    bookingErrors: {},
                    abusErrors: {},
                    favoris: localStorage.favoris ? JSON.parse(localStorage.favoris) : [],
                }
            },
            computed: {},
            mounted() {
                this.loadData();
            },
            components: {
                'Sliderd': Sliderd,
                'Slider': Slider,
                'MlCalendar': MlCalendar,
            },
            watch: {
                emailForm: {
                    handler(newval) {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('emailForm.' + key, (val, oldVal) => {
                                delete this.errors[key];
                            });
                        }
                    },
                    deep: true
                }
            },
            methods: {
                dateClick(d){
                    this.bookingErrors = {};
                    this.bookingForm.startDate = d.date;
                    this.bookingForm.endDate = d.date;
                    picker = new Lightpick({
                        field: document.getElementById('datepicker'),
                        singleDate: false,
                        numberOfMonths: 2,
                        footer: true,
                        disableDates:d.disableDates,
                        onSelect: (start, end) => {
                            let validation = true;
                            for(v of d.disableDates){
                                if(moment(v)>=start&&moment(v)<=end){
                                    validation = false;
                                    picker.reset();
                                    break;
                                }
                            }
                            if(validation){
                                this.bookingForm.startDate = start ? start.format("YYYY-MM-DD") : null;
                                this.bookingForm.endDate = end ? end.format("YYYY-MM-DD") : null;
                            }
                        }
                    });
                    picker.setStartDate(d.date);
                    picker.setEndDate(d.date);
                    this.bookingModal = true;
                },
                addBoooking(){
                    this.bookingErrors = {};
                    this.bookLoading = true;
                    axios.post("/api/v2/addBooking", this.bookingForm)
                        .then((response) => {
                            this.bookLoading = false;
                            this.bookingModal = false;
                            swal("Votre message a été bien envoyé!", "", "success");
                        })
                        .catch(error => {
                            this.bookLoading = false;
                            console.log(error.response.data);
                            if (typeof error.response.data.error === 'object') this.bookingErrors = error.response.data
                                .error;
                            else swal(error.response.data.error, "", "error");
                        });
                },
                sendAbus(){

                    axios.post('/api/v2/email/reportAbus', this.abusForm)
                    .then((response) => {
                            swal("Votre message a été bien envoyé!", "", "success");
                        })
                        .catch(error => {
                            if (typeof error.response.data.error === 'object') this.abusErrors = error.response.data
                                .error;
                            else swal("Something wrong!", "", "error");
                            console.log('errors',this.abusErrors)
                        });


                },
                redirect() {
                    swal("Veuillez vous connecter pour pouvoir signaler cette annonce", "", "error");
                    var url = "../../login"
                    window.location=url;

                },
                loadData() {
                    axios.get("/api/v2/getItem?id=" + id)
                        .then((response) => {
                            this.obj = response.data.data;
                            console.log(this.obj);
                            this.addClick('hit');
                            if (!this.obj.is_project || this.obj.is_project == 0) this.similarAds();
                            else this.disposAds();
                            if (this.obj.loclat && this.obj.loclng) {
                                document.querySelector('#loc-section').classList.remove('d-none');
                                const filterGroup = document.getElementById('map-filter-group');
                                filterGroup.innerHTML = '';
                                var map = new maplibregl.Map({
                                    container: 'map1',
                                    style: mapStyle,
                                    hash: false,
                                    maxBounds: [
                                        [-17.8, 20],
                                        [0, 36.1]
                                    ],
                                    minZoom: 5,
                                    center: [this.obj.loclng, this.obj
                                        .loclat

                                    ], // starting position [lng, lat]
                                    zoom: 14 // starting zoom
                                });

                                const layers = ["school", "hospital", "grocery"];
                                layers.forEach(myFunction);


                                function myFunction(value, index, array) {
                                    const inputCnt = document.createElement('div');
                                    inputCnt.class = 'map-filter-input-cnt';
                                    filterGroup.appendChild(inputCnt);
                                    const input = document.createElement('input');
                                    input.type = 'checkbox';
                                    input.id = value;
                                    input.checked = false;
                                    inputCnt.appendChild(input);

                                    const label = document.createElement('label');
                                    label.setAttribute('for', value);
                                    label.textContent = value;
                                    inputCnt.appendChild(label);

                                    // When the checkbox changes, update the visibility of the layer.
                                    input.addEventListener('change', (e) => {
                                        map.setLayoutProperty(
                                            value,
                                            'visibility',
                                            e.target.checked ? 'visible' : 'none'
                                        );
                                    });
                                }

                                map.on('load', () => {

                                    var el = document.createElement('img');
                                    el.className = 'marker';
                                    el.src = '/assets/img/marker.png';
                                    el.style.width = '30px';
                                    el.style.height = '40px';
                                    el.style.top = '-20px';

                                    var marker = new maplibregl.Marker({
                                            element: el,
                                            draggable: false
                                        })
                                        .setLngLat([this.obj.loclng, this.obj.loclat])
                                        .addTo(map);
                                });

                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                similarAds() {
                    axios.post("/api/v2/similarItems", {
                            type: this.obj?.type,
                            cat_id: this.obj?.catid,
                            price: this.obj?.price,
                            city: this.obj?.loccity,
                            id: this.obj?.id,
                        })
                        .then((response) => {
                            this.similarItems = response.data.data;
                            slide('slide-similarads', 5000);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                disposAds() {
                    axios.get("/api/v2/disposItems?id=" + id)
                        .then((response) => {
                            this.dispos = response.data.data;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                toggleFavoris(id) {



                    if (localStorage.getItem('favoris')) {
                        if (Array.isArray(JSON.parse(localStorage.getItem('favoris')))) {
                            ids = JSON.parse(localStorage.getItem('favoris'));
                            const idIndex = ids.indexOf(id);
                            console.log('idIndex',idIndex);
                            console.log('ids',ids);
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
                                        'Annulé',
                                        'Vous avez annuler l\'action de l\'ajout',
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
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
                displayPhone() {
                    if (this.showPhone == false) {
                        this.addClick("phone");
                    }
                    this.showPhone = true;
                },
                displayPhone2() {
                    if (this.showPhone2 == false) {
                        this.addClick("phone");
                    }
                    this.showPhone2 = true;
                },
                addClick(type) {
                    axios.post("/api/v2/addClick", {
                            type: type,
                            id: id,
                        })
                        .then((response) => {
                            console.log(response);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                sendEmail() {
                    this.emailLoading = true;
                    axios.post("/api/v2/email/createAdsEmail", this.emailForm)
                        .then((response) => {
                            this.emailLoading = false;
                            this.showemail = false;
                            swal("Votre message a été bien envoyé!", "", "success");
                        })
                        .catch(error => {
                            this.emailLoading = false;
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else swal("Something wrong!", "", "error");
                        });
                },

                displayStatus(val) {
                    for (const s of status_arr) {
                        if (s.val == val) return s.desc;
                    }
                    return '';
                },
                updateAd(ad) {
                    updateAdApp.display = true;
                    updateAdApp.type = ad.type;
                    updateAdApp.loadAd(ad.id);
                },
                boostAd(ad, ad_id) {
                    boostAdApp.form.id = ad.id;
                    boostAdApp.form.option = null;
                    boostAdApp.getAdOption();
                    boostAdApp.getUserCoins();
                    boostAdApp.display = true;
                },
                deleteAd() {
                    swal("Voulez-vous vraiment supprimer cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val === true) {
                            var config = {
                                method: 'post',
                                data: {
                                    id: this.obj.id
                                },
                                url: `/api/v2/deleteAd`
                            };
                            axios(config)
                                .then((response) => {
                                    this.loading = false;
                                    if (response.data.success == true) {
                                        displayToast("L'annonce a été supprimé avec succès", '#0f5132');
                                        document.location.href = "/myitems";
                                    }
                                })
                                .catch(error => {
                                    this.loading = false;
                                    this.errorText = error.response.data.error;
                                    displayToast(this.errorText, '#842029');
                                });
                        }
                    });
                },
            }
        }).mount('#item-App');
        document.querySelector('#item-App').classList.remove("d-none");

        //BOOSTER ADS ------------------------------------------------------------------
        let boostAdApp = Vue.createApp({
            data() {
                return {
                    display: false,
                    loader: false,
                    globalloader: false,
                    error: '',
                    options: [],
                    coins: 0,
                    oldOption: null,
                    form: {
                        id: null,
                        option: null,
                    },
                }
            },
            components: {
                'PopsupComponent': PopsupComponent,
            },
            mounted() {
                this.loadOptions();
                this.getUserCoins();
            },
            methods: {
                loadOptions() {
                    var config = {
                        method: 'post',
                        url: `/api/v2/options/getAll`
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                this.options = response.data.data;
                                console.log('options',this.options);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                getAdOption() {
                    this.globalloader = true;
                    var config = {
                        method: 'post',
                        data: {
                            id: this.form.id
                        },
                        url: `/api/v2/items/getAdOption`
                    };
                    axios(config)
                        .then((response) => {
                            console.log(response);
                            this.globalloader = false;
                            if (response.data.success == true) {
                                this.form.option = response.data.data;
                                this.oldOption = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.globalloader = false;
                            this.display = false;
                            this.error = error.response.data.error;
                            swal(this.error, "", "error");
                        });
                },
                getUserCoins() {
                    var config = {
                        method: 'get',
                        url: '/api/v2/UserCoins/getAll'
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                this.coins = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });

                },
                save0() {
                    if (this.oldOption == this.form.option) return;
                    if (!this.form.option) return;
                    swal("Voulez-vous vraiment booster cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val == true) {
                            this.save();
                        }
                    });
                },
                save() {
                    //if(this.oldOption==this.form.option) return;
                    //if(!this.form.option) return;
                    this.loader = true;
                    this.error = '';
                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/boostAd`
                    };
                    axios(config)
                        .then((response) => {
                            this.loader = false;
                            console.log(response.data);
                            if (response.data.success == true) {
                                this.display = false;
                                swal("{{ __('general.Votre annonce a été boostée avec succès') }}", "", "success");
                            }
                        })
                        .catch(error => {
                            this.loader = false;
                            this.error = error.response.data.error;
                            if (this.error == "User has not enough balance") this.error =
                                "Votre listcoins solde est insuffisant, veuillez acheter des listcoins!";
                            swal(this.error, "", "error");
                        });
                },
                Recharger() {
                    swal("Cette option sera disponible bientôt !", "", "warning");
                },
            },
        }).mount('#booster-app');

        console.log(boostAdApp);
        document.querySelector('#booster-app').classList.remove("d-none");

        let updateAdApp = createApp({
            data() {
                return {
                    display: false,
                    errors: {},
                    types: ['vente', 'location', 'vacance'],
                    type: null,
                    categories: [],
                    cities: [],
                    neighborhood: [
                        {
                        id: null,
                        name: "Sélectionnez un quartier"
                    }, {
                        id: 0,
                        name: "Autre"
                    }
                    ],
                    standings: [],
                    place_types: [],
                    userphones: [],
                    userWtsps: [],
                    userEmails: [],
                    pieces: [1, 2, 3, 4, 5],
                    etages: [-1, 0, 1, 2, 3, 4, 5],
                    surfaces: [60, 70, 80, 90, 100, 120],
                    years: [
                        new Date().getFullYear() - 5, new Date().getFullYear() - 4,
                        new Date().getFullYear() - 3, new Date().getFullYear() - 2,
                        new Date().getFullYear() - 1, new Date().getFullYear()
                    ],
                    customRooms: false,
                    customSurface: false,
                    customBedrooms: false,
                    customWc: false,
                    customEtage: false,
                    customEtageTotal: false,
                    customContrictionDate: false,
                    placesForm: {
                        id: 0,
                        title: '',
                        distance: '',
                        type: '',
                    },
                    placesFormUpdate: {
                        id: null,
                        title: '',
                        distance: '',
                        types: null,
                    },
                    form: {
                        id: null,
                        user_id: null,
                        catid: null,
                        loccity: null,
                        locdept: null,
                        locdept2: null,
                        lat: null,
                        long: null,
                        standing: null,
                        rooms: null,

                        bedrooms: null,
                        wc: null,
                        contriction_date: null,

                        price_m: null,
                        etage: null,

                        etage_total: null,
                        surface: null,
                        climatise: false,

                        jardin: false,
                        piscine: false,

                        parking: false,
                        meuble: false,

                        terrace: false,
                        syndic: false,

                        cave: false,
                        ascenseur: false,

                        securite: false,
                        groundfloor: false,
                        gardenlevel: false,
                        balcony: false,
                        green_spaces: false,
                        guardian: false,
                        automation: false,
                        sea_view: false,
                        box: false,
                        equipped_kitchen: false,
                        soundproof: false,
                        thermalinsulation: false,
                        playground: false,
                        gym: false,
                        Chimney: false,
                        sportterrains: false,
                        library: false,
                        double_orientation: false,
                        intercom: false,
                        garage: false,
                        double_glazing: false,
                        images: [],
                        videos: [],
                        nearbyPlaces: [],
                        title: '',
                        ref: '',
                        description: '',
                        price: null,
                        price_curr: 'DHS',
                        phone: -1,
                        email: -1,
                        phone2: null,
                        wtsp: null,
                    },
                }
            },
            computed: {},
            mounted() {
                this.loadData();
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent,
                'PopupComponent': PopupComponent,
                "MlSelect": MlSelect
            },
            methods: {
                loadAd(id) {
                    this.form = {
                        id: null,
                        catid: null,
                        loccity: null,
                        locdept: null,
                        locdept2: null,
                        lat: null,
                        long: null,
                        standing: null,
                        rooms: null,

                        bedrooms: null,
                        wc: null,
                        contriction_date: null,

                        price_m: null,
                        etage: null,

                        etage_total: null,
                        surface: null,
                        climatise: false,

                        jardin: false,
                        piscine: false,

                        parking: false,
                        meuble: false,

                        terrace: false,
                        syndic: false,

                        cave: false,
                        ascenseur: false,

                        securite: false,
                        groundfloor: false,
                        gardenlevel: false,
                        balcony: false,
                        green_spaces: false,
                        guardian: false,
                        automation: false,
                        sea_view: false,
                        box: false,
                        equipped_kitchen: false,
                        soundproof: false,
                        thermalinsulation: false,
                        playground: false,
                        gym: false,
                        Chimney: false,
                        sportterrains: false,
                        library: false,
                        double_orientation: false,
                        intercom: false,
                        garage: false,
                        double_glazing: false,
                        images: [],
                        videos: [],
                        nearbyPlaces: [],
                        title: '',
                        ref: '',
                        description: '',
                        price: null,
                        price_curr: 'DHS',
                        phone: -1,
                        email: -1,
                        phone2: null,
                        wtsp: null,
                    };
                    axios.post("/api/v2/items/getAdById", {
                            id: id
                        }).then(response => {
                            this.globalloader = false;
                            const data = response.data;
                            if (data.success) {
                                this.form.images = data.data.images;
                                this.form.videos = data.data.videos;
                                this.form.audios = data.data.audios;
                                this.form.loccity = data.data.ad.loccity;
                                this.form.locdept = data.data.ad.locdept;
                                this.form.locdept2 = data.data.ad.locdept2;
                                for (const np of data.data.nearby_places) {
                                    this.placesForm.id++;
                                    this.placesForm.title = np.title;
                                    this.placesForm.distance = np.distance;
                                    this.placesForm.types = np.id_place_type;
                                    this.placesForm.lat = np.lat;
                                    this.placesForm.long = np.lng;
                                    this.form.nearbyPlaces.push(this.placesForm);
                                }
                                if (data.data.ad) {
                                    this.form.id = data.data.ad.id;
                                    this.form.user_id = data.data.ad.id_user;
                                    this.form.title = data.data.ad.title;
                                    this.form.description = data.data.ad.description;
                                    this.form.catid = data.data.ad.catid;
                                    this.form.price = data.data.ad.price;
                                    this.form.price_curr = data.data.ad.price_curr;
                                    this.form.phone = data.data.ad.phone;
                                    this.form.phone2 = data.data.ad.phone2;
                                    this.form.wtsp = data.data.ad.wtsp;
                                    this.form.email = data.data.ad.email;
                                    this.form.is_project = data.data.ad.is_project == 1 ? true : false;
                                    this.form.terrace = data.data.ad.terrace == 1 ? true : false;
                                    this.form.surfaceTerrace = data.data.ad.terrace_surface;
                                    this.form.project_priority = data.data.ad.project_priority;
                                    this.form.meuble = data.data.ad.meuble == 1 ? true : false;
                                    this.form.jardin = data.data.ad.jardin == 1 ? true : false;
                                    this.form.surfaceJardin = data.data.ad.jardin_surface;
                                    this.form.climatise = data.data.ad.climatise == 1 ? true : false;
                                    this.form.syndic = data.data.ad.syndic == 1 ? true : false;
                                    this.form.cave = data.data.ad.cave == 1 ? true : false;
                                    this.form.ascenseur = data.data.ad.ascenseur == 1 ? true : false;
                                    this.form.securite = data.data.ad.securite == 1 ? true : false;
                                    this.form.groundfloor = data.data.ad.groundfloor == 1 ? true : false;
                                    this.form.gardenlevel = data.data.ad.gardenlevel == 1 ? true : false;
                                    this.form.balcony = data.data.ad.balcony == 1 ? true : false;
                                    this.form.green_spaces = data.data.ad.green_spaces == 1 ? true : false;
                                    this.form.guardian = data.data.ad.guardian == 1 ? true : false;
                                    this.form.automation = data.data.ad.automation == 1 ? true : false;
                                    this.form.sea_view = data.data.ad.sea_view == 1 ? true : false;
                                    this.form.box = data.data.ad.box == 1 ? true : false;
                                    this.form.equipped_kitchen = data.data.ad.equipped_kitchen == 1 ? true : false;
                                    this.form.soundproof = data.data.ad.soundproof == 1 ? true : false;
                                    this.form.thermalinsulation = data.data.ad.thermalinsulation == 1 ? true : false;
                                    this.form.playground = data.data.ad.playground == 1 ? true : false;
                                    this.form.gym = data.data.ad.gym == 1 ? true : false;
                                    this.form.Chimney = data.data.ad.Chimney == 1 ? true : false;
                                    this.form.sportterrains = data.data.ad.sportterrains == 1 ? true : false;
                                    this.form.library = data.data.ad.library == 1 ? true : false;
                                    this.form.double_orientation = data.data.ad.double_orientation == 1 ? true : false;
                                    this.form.intercom = data.data.ad.intercom == 1 ? true : false;
                                    this.form.garage = data.data.ad.garage == 1 ? true : false;
                                    this.form.double_glazing = data.data.ad.double_glazing == 1 ? true : false;
                                    this.form.proprety_type = data.data.ad.property_type;
                                    this.form.standing = data.data.ad.standing;
                                    this.form.ref = data.data.ad.ref;
                                    this.form.vr_link = data.data.ad.vr_link;
                                    this.form.rooms = data.data.ad.rooms;
                                    this.form.bedrooms = data.data.ad.bedrooms;
                                    this.form.wc = data.data.ad.bathrooms;
                                    this.form.parking = data.data.ad.parking;
                                    this.form.contriction_date = data.data.ad.built_year;
                                    this.form.price_m = data.data.ad.price_surface;
                                    this.form.surface = data.data.ad.surface;
                                    this.form.long = data.data.ad.loclng;
                                    this.form.lat = data.data.ad.loclat;
                                    this.changeCity(true);
                                    this.changeDept(true);
                                }
                                this.globalloader = false;
                                this.contactloader = false;
                            } else {
                                this.globalloader = false;
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            this.globalloader = false;
                            this.contactloader = false;
                            console.log(error);
                            /*var err = error.response.data.error;
                            displayToast(err,'#842029');*/
                        });
                },
                loadData() {
                    axios.get('/api/v2/multilistfilterfrom')
                        .then(response => {
                            let data = response.data.data;
                            this.categories = data.categories;
                            this.cities = data.cities;
                            this.standings = data.standings;
                            this.place_types = data.place_types;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    axios.get('/api/v2/getUserContacts?user_id=' + currentUserId)
                        .then(response => {
                            let data = response.data.data;
                            this.userphones = data.userphones;
                            this.userWtsps = data.userWtsps;
                            this.userEmails = data.userEmails;
                            console.log('userContacts', data);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                showField(field){
                    const selectedcat = findObjInArrById(this.categories,this.form.catid);
                    if(selectedcat){
                        if(selectedcat.fields){
                            if(selectedcat.fields.split(',').indexOf(field)!=-1){
                                return true;
                            }
                        }
                    }
                    return false;
                },
                AddPlaceModal() {
                    const i = this.placesForm.id;
                    this.placesForm = {
                        id: i,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    };
                    showModal('addPlaceModal');
                },
                addPlace() {
                    this.placesForm.id++;
                    this.form.nearbyPlaces.push(this.placesForm);
                    hideModal('addPlaceModal');
                },
                updatePlace() {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == this.placesFormUpdate.id) {
                                this.form.nearbyPlaces[i] = this.placesFormUpdate;
                            }
                    }
                    hideModal('updatePlaceModal');
                },
                updatePlaceModal(place) {
                    this.placesFormUpdate = place;
                    showModal('updatePlaceModal');
                },
                deletePlace(id) {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == id) this.form.nearbyPlaces.splice(i, 1);
                    }
                },
                changeCity(c = false) {
                    if (c == false) {
                        this.form.locdept = null;
                        this.form.lat = null;
                        this.form.long = null;
                    }
                    axios.post("{{ route('api.loadDeptsByCity') }}", {
                            city: this.form.loccity
                        }).then(response => {
                            const data = response.data;
                            if (data.success) {
                                this.neighborhood = [{
                                    id: null,
                                    name: "Sélectionnez un quartier"
                                }, {
                                    id: 0,
                                    name: "Autre"
                                }].concat(data.data);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                },
                changeDept(c = false) {
                    document.querySelector('#dept2_cnt')?.classList.add('d-none');
                    document.querySelector('#map_cnt').classList.add('d-none');
                    document.querySelector('#btn-locate').classList.add('d-none');
                    document.querySelector('#btn-locate-cancel').classList.add('d-none');
                    document.querySelector('#map').innerHTML = '';
                    if (this.form.locdept == 0) {
                        document.querySelector('#dept2_cnt')?.classList.remove('d-none');
                        let lngLat = null
                        if (c) {
                            if(this.form.long&&this.form.lat)
                            lngLat = [this.form.long, this.form.lat];
                        } else {
                            this.form.lat = null;
                            this.form.long = null;
                        }
                        if (lngLat) {
                            this.form.lat = lngLat[1];
                            this.form.long = lngLat[0];
                            document.querySelector('#map_cnt').classList.remove('d-none');
                            const filterGroup = document.getElementById('map-filter-group');
                            filterGroup.innerHTML = '';
                            var map = new maplibregl.Map({
                                container: 'map',
                                style: mapStyle,
                                hash: false,
                                maxBounds: [
                                    [-17.8, 20],
                                    [0, 36.1]
                                ],
                                minZoom: 5,
                                center: [lngLat[0], lngLat[1]], // starting position [lng, lat]
                                zoom: 11 // starting zoom
                            });

                            /*const layers = ["school", "hospital", "grocery"];
                            layers.forEach(myFunction);


                            function myFunction(value, index, array) {
                                const inputCnt = document.createElement('div');
                                inputCnt.class = 'map-filter-input-cnt';
                                filterGroup.appendChild(inputCnt);
                                const input = document.createElement('input');
                                input.type = 'checkbox';
                                input.id = value;
                                input.checked = false;
                                inputCnt.appendChild(input);

                                const label = document.createElement('label');
                                label.setAttribute('for', value);
                                label.textContent = value;
                                inputCnt.appendChild(label);

                                // When the checkbox changes, update the visibility of the layer.
                                input.addEventListener('change', (e) => {
                                    map.setLayoutProperty(
                                        value,
                                        'visibility',
                                        e.target.checked ? 'visible' : 'none'
                                    );
                                });
                            }*/
                            map.on('load', () => {
                                var el = document.createElement('img');
                                el.className = 'marker';
                                el.src = '/assets/img/marker.png';
                                el.style.width = '30px';
                                el.style.height = '40px';
                                el.style.top = '-20px';
                                var marker = new maplibregl.Marker({
                                        element: el,
                                        draggable: true
                                    })
                                    .setLngLat(lngLat)
                                    .addTo(map);
                                document.querySelector('#mapSearch').addEventListener('keypress', (e) => {
                                    if (e.which == 13) {
                                        var lng = e.target.value.split(",")[1];
                                        var lat = e.target.value.split(",")[0];
                                        console.log(lng, lat);
                                            this.form.lat = lat;
                                            this.form.long = lng;
                                            marker.setLngLat([lng, lat]);

                                    }
                                });
                            });
                        } else {
                            document.querySelector('#btn-locate').classList.remove('d-none');
                        }
                    } else {
                        axios.post("{{ route('api.loadDeptCoordinates') }}", {
                            dept: this.form.locdept
                        }).then(response => {
                            const coordinatesData = response.data.data;
                            const selected_dept = findObjInArrById(this.neighborhood, this.form.locdept);
                            selected_dept.coordinates = coordinatesData?.coordinates;
                            selected_dept.coordinates = coordinatesData?.dCoordinates;
                            var coordinates = null;
                            var lngLat = null;
                            if (selected_dept.coordinates) coordinates = selected_dept.coordinates;
                            else if (selected_dept.dCoordinates) coordinates = selected_dept.dCoordinates;

                            if (selected_dept.coordinates) lngLat = centroid(selected_dept.coordinates.coordinates);
                            else if (selected_dept.lat && selected_dept.lng) lngLat = [selected_dept.lng, selected_dept
                                .lat
                            ];
                            else if (selected_dept.dCoordinates) lngLat = centroid(selected_dept.dCoordinates
                                .coordinates);
                            if (c) {
                                lngLat = [this.form.long, this.form.lat];
                            } else {
                                this.form.lat = null;
                                this.form.long = null;
                            }
                            if (lngLat) {
                                this.form.lat = lngLat[1];
                                this.form.long = lngLat[0];
                                document.querySelector('#map_cnt').classList.remove('d-none');
                                const filterGroup = document.getElementById('map-filter-group');
                                filterGroup.innerHTML = '';
                                var map = new maplibregl.Map({
                                    container: 'map',
                                    style: mapStyle,
                                    hash: false,
                                    maxBounds: [
                                        [-17.8, 20],
                                        [0, 36.1]
                                    ],
                                    minZoom: 5,
                                    center: [lngLat[0], lngLat[1]], // starting position [lng, lat]
                                    zoom: 11 // starting zoom
                                });

                                /*const layers = ["school", "hospital", "grocery"];
                                layers.forEach(myFunction);


                                function myFunction(value, index, array) {
                                    const inputCnt = document.createElement('div');
                                    inputCnt.class = 'map-filter-input-cnt';
                                    filterGroup.appendChild(inputCnt);
                                    const input = document.createElement('input');
                                    input.type = 'checkbox';
                                    input.id = value;
                                    input.checked = false;
                                    inputCnt.appendChild(input);

                                    const label = document.createElement('label');
                                    label.setAttribute('for', value);
                                    label.textContent = value;
                                    inputCnt.appendChild(label);

                                    // When the checkbox changes, update the visibility of the layer.
                                    input.addEventListener('change', (e) => {
                                        map.setLayoutProperty(
                                            value,
                                            'visibility',
                                            e.target.checked ? 'visible' : 'none'
                                        );
                                    });
                                }*/
                                map.on('load', () => {
                                    var el = document.createElement('img');
                                    el.className = 'marker';
                                    el.src = '/assets/img/marker.png';
                                    el.style.width = '30px';
                                    el.style.height = '40px';
                                    el.style.top = '-20px';
                                    var marker = new maplibregl.Marker({
                                            element: el,
                                            draggable: true
                                        })
                                        .setLngLat(lngLat)
                                        .addTo(map);
                                    document.querySelector('#mapSearch').addEventListener('keypress', (e) => {
                                        if (e.which == 13) {
                                            var lng = e.target.value.split(",")[1];
                                            var lat = e.target.value.split(",")[0];
                                            console.log(lng, lat);
                                            if (coordinates) {
                                                var polygon = coordinates.coordinates[0][0];
                                                if (inside([lng, lat], polygon) == false) {
                                                    swal("error", "", "error");
                                                } else {
                                                    this.form.lat = lat;
                                                    this.form.long = lng;
                                                    marker.setLngLat([lng, lat]);
                                                }
                                            } else {
                                                this.form.lat = lat;
                                                this.form.long = lng;
                                                marker.setLngLat([lng, lat]);
                                            }

                                        }
                                    });
                                    marker.on('dragend', () => {
                                        var lngLatNew = marker.getLngLat();
                                        if (coordinates) {
                                            var polygon = coordinates.coordinates[0][0];
                                            if (inside([lngLatNew.lng, lngLatNew.lat], polygon) ==
                                                false) {
                                                this.form.lat = lngLat[1];
                                                this.form.long = lngLat[0];
                                                marker.setLngLat(lngLat);
                                                swal("error", "", "error");
                                            } else {
                                                this.form.lat = lngLatNew.lat;
                                                this.form.long = lngLatNew.lng;
                                            }
                                        } else {
                                            this.form.lat = lngLatNew.lat;
                                            this.form.long = lngLatNew.lng;
                                        }
                                    });
                                    if (coordinates) {
                                        map.addSource('selected_place', {
                                            'type': 'geojson',
                                            'data': {
                                                'type': 'FeatureCollection',
                                                'features': [{
                                                    'type': 'Feature',
                                                    'geometry': coordinates
                                                }]
                                            }
                                        });
                                        map.addLayer({
                                            'id': 'place-boundary',
                                            'type': 'fill',
                                            'source': 'selected_place',
                                            'paint': {
                                                'fill-color': '#198754',
                                                'fill-opacity': 0.4
                                            },
                                            'filter': ['==', '$type', 'Polygon']
                                        });
                                    }
                                });
                            } else if (this.form.locdept) {
                                document.querySelector('#btn-locate').classList.remove('d-none');
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                    }
                },
                cancelLocateMap() {
                    this.form.lat = null;
                    this.form.long = null;
                    document.querySelector('#map').innerHTML = '';
                    document.querySelector('#map_cnt').classList.add('d-none');
                    document.querySelector('#btn-locate').classList.remove('d-none');
                    document.querySelector('#btn-locate-cancel').classList.add('d-none');
                },
                locateMap() {
                    document.querySelector('#btn-locate').classList.add('d-none');
                    document.querySelector('#btn-locate-cancel').classList.remove('d-none');
                    document.querySelector('#map_cnt').classList.remove('d-none');
                    document.querySelector('#map').innerHTML = '';
                    this.form.lat = 33.5731104;
                    this.form.long = -7.5898434;
                    let lngLat = [this.form.long, this.form.lat];
                    const filterGroup = document.getElementById('map-filter-group');
                    filterGroup.innerHTML = '';
                    var map = new maplibregl.Map({
                        container: 'map',
                        style: mapStyle,
                        hash: false,
                        maxBounds: [
                            [-17.8, 20],
                            [0, 36.1]
                        ],
                        minZoom: 5,
                        center: [lngLat[0], lngLat[1]], // starting position [lng, lat]
                        zoom: 11 // starting zoom
                    });

                    const layers = ["school", "hospital", "grocery"];
                    layers.forEach(myFunction);


                    function myFunction(value, index, array) {
                        const inputCnt = document.createElement('div');
                        inputCnt.class = 'map-filter-input-cnt';
                        filterGroup.appendChild(inputCnt);
                        const input = document.createElement('input');
                        input.type = 'checkbox';
                        input.id = value;
                        input.checked = false;
                        inputCnt.appendChild(input);

                        const label = document.createElement('label');
                        label.setAttribute('for', value);
                        label.textContent = value;
                        inputCnt.appendChild(label);

                        // When the checkbox changes, update the visibility of the layer.
                        input.addEventListener('change', (e) => {
                            map.setLayoutProperty(
                                value,
                                'visibility',
                                e.target.checked ? 'visible' : 'none'
                            );
                        });
                    }
                    map.on('load', () => {
                        var el = document.createElement('img');
                        el.className = 'marker';
                        el.src = '/assets/img/marker.png';
                        el.style.width = '30px';
                        el.style.height = '40px';
                        el.style.top = '-20px';
                        var marker = new maplibregl.Marker({
                                element: el,
                                draggable: true
                            })
                            .setLngLat(lngLat)
                            .addTo(map);
                        document.querySelector('#mapSearch').addEventListener('keypress', (e) => {
                            if (e.which == 13) {
                                var lng = e.target.value.split(",")[1];
                                var lat = e.target.value.split(",")[0];
                                this.form.lat = lat;
                                this.form.long = lng;
                                marker.setLngLat([lng, lat]);
                            }
                        });
                        marker.on('dragend', () => {
                            var lngLatNew = marker.getLngLat();
                            this.form.lat = lngLatNew.lat;
                            this.form.long = lngLatNew.lng;
                        });
                    });

                },
                selectType(val) {
                    this.form.catid = null;
                    if (val == this.type) {
                        this.type = null;
                    } else {
                        this.type = val;
                        window.location.href = '#category-section';
                    }
                },
                selectCat(val) {
                    if (val == this.form.catid) {
                        this.form.catid = null;
                    } else {
                        this.form.catid = val;
                        window.location.href = '#loc-section';
                    }
                },
                save0(status) {
                    this.save(status);
                },
                save(status) {
                    this.loading = true;
                    this.errors = {};
                    this.error = '';
                    this.form.status = status;
                    if (!this.validateForm()) {
                        this.loading = false;
                        return;
                    }
                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/updateAd`
                    };
                    axios(config)
                        .then((response) => {
                            this.loading = false;
                            console.log(response.data);
                            if (response.data.success == true) {
                                this.display = false;
                                window.location.reload();
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (typeof error.response.data.error === 'object') {this.errors = error.response.data

                                .error;
                                // element which needs to be scrolled to
                                var element = document.querySelector("#category-section");
                                // scroll to element
                                element.scrollIntoView();
                                swal("Veuillez bien vérifier les champs obligatoires !", "", "error");
                            }else {
                                this.errorText = error.response.data.error;
                                displayToast(this.errorText, '#842029');
                            }
                        });
                },
                deleteAd() {
                    swal("Voulez-vous vraiment supprimer cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val === true) {
                            var config = {
                                method: 'post',
                                data: {
                                    id: this.form.id
                                },
                                url: `/api/v2/deleteAd`
                            };
                            axios(config)
                                .then((response) => {
                                    this.loading = false;
                                    if (response.data.success == true) {
                                        for (let i = 0; i < myadsApp.ads.length; i++) {
                                            if (myadsApp.ads[i].id == response.data.data) {
                                                myadsApp.ads.splice(i, 1);
                                            }
                                        }
                                        this.display = false;
                                        displayToast("L'annonce a été supprimé avec succès", '#0f5132');
                                    }
                                })
                                .catch(error => {
                                    this.loading = false;
                                    if (typeof error.response.data.error === 'object') this.errors =

                                        error.response.data.error;
                                    else {
                                        this.errorText = error.response.data.error;
                                        displayToast(this.errorText, '#842029');
                                    }
                                });
                        }
                    });
                },
                validateForm() {
                    this.errors = {};
                    let r = true;
                    return r;
                }
            }
        }).mount('#updateAd-app');
        document.querySelector('#updateAd-app').classList.remove("d-none");


    </script>


<script>

document.addEventListener("DOMContentLoaded", function(event) {

setTimeout(() => {


var $url = $(location).attr('href');
$("#url-text").val($url);
$('#clipboard').on('click', function() {

  $("#url-text").val($url).select();
  document.execCommand("copy");
  $("#url").text("👍");
})

}, 1000);
});
</script>
<style>
    @media (max-width: 425px){
    .tagManager-phone{
        max-width: 35px;
        height: 35px;
        border-radius: 50% !important;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid var(--primary-color);
    }
    }

</style>

@endsection

@section('custom_foot')


<script src=" {{ asset('assets/js/v2/item.scripts.js') }}"></script>
<script src=" {{ asset('assets/js/share.js') }}"></script>





@endsection
