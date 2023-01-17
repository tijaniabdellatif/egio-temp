@extends('v2.layouts.default')

@section('title', __("general.Estimer votre bien"))

@section('custom_head')


<script src="/assets/vendor/jquery.min.js"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/estimation.styles.css') }}">

    <script src="{{ asset('js/anime.min.js') }}"></script>
    <script src='/js/script.js'></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <style>

        footer {

            display:block;
        }
    </style>

@endsection

@section('content')


<div id="estimation">
    <div class="container-estimation">
        <div class="div-1">
         <p class="indication-content mb-5"  @if (Session('lang')=='ar')
         dir="rtl"
     @else
         dir="ltr"
     @endif>
             <span class="div-1-content fs-5">
                {{__('general.estimation title1')}}
             </span>
         </p>

         <div class="indication">
             <div class="items" data-aos="fade-up" data-aos-delay="50">
                 <div class="icon-item">
                     <img src="{{ asset('images/time.svg') }}" alt="time" />
                 </div>
                 <div class="content-item">
                     <h4>{{ __('general.Estimation à la demande') }}</h4>
                     <p class="content-item-text">
                         {{__('general.Découvrez en une fraction de seconde le prix de vente de votre bien selon votre emplacement')}}
                     </p>
                 </div>
             </div>
             <div class="items" data-aos="fade-up" data-aos-delay="200">
                 <div class="icon-item">
                     <img src="{{ asset('images/check.svg') }}" alt="time" />
                 </div>
                 <div class="content-item">
                     <h4>{{ __('general.Concise et précise') }}</h4>
                     <p class="content-item-text">
                         {{__('general.L’estimation reflète la réalité du marché de votre emplacement,')}}
                     </p>
                 </div>
             </div>

             <div class="items" data-aos="fade-up">
                 <div class="icon-item" data-aos-delay="500">

                     <img src="{{ asset('images/mono.svg') }}" alt="time" />
                 </div>
                 <div class="content-item">
                     <h4>{{ __('general.Contenu régulier') }}</h4>
                     <p class="content-item-text">
                        {{__('general.Les estimations sont mise à jour selon l’évolution du prix du marché immobilier.')}}
                     </p>
                 </div>
             </div>

         </div>
  </div>
    </div>




    <div class="container-how">

        <div class="banner-estimation"
        data-aos="fade-in">
            <span class="banner-content-emp">75%</span>
            <div>
                <p class="banner-content fs-5 fs-resp">
                    {{ __('general.estimation title2') }}
                </p>
            </div>
         </div>

     <div class="immerge" @if (Session('lang') == 'ar')
            dir="rtl"
        @else
            dir="ltr"
        @endif>
         <div class="how-content fs-4" data-aos-offset="20" data-aos="fade-right" data-aos-mirror="true">
             <h5 class="fs-resp">{{ __('general.Comment faire estimer mon bien ?') }} </h5>
             <p class="how-text fs-5 fs-resp">
                 {{ __('general.Comment faire estimer mon bien - body') }}
             </p>
         </div>
         <div class="how-content fs-4" data-aos="fade-right" data-aos-mirror="true" data-aos-duration="1500">
             <h5>{{ __('general.Nos sources de données ?') }} </h5>
             <p class="how-text fs-5">
               {{__('general.Nos sources de données sont trés fiables, en se basant sur la répartition régionales au Maroc:')}}
             </p>
         </div>
     </div>

     <div class="immerge mb-5" @if (Session('lang') == 'ar')
         dir="rtl"
     @else
         dir="ltr"
     @endif>

         <div class="how-indication fs-4" data-aos="fade-left" data-aos-mirror="true" data-aos-duration="1500">
             <img src="{{ asset('images/address.svg') }}" alt="logo1" />
             <p class="fs-5 fs-resp">{{ __('general.Votre region et votre commune décident le prix de votre bien') }}</p>
         </div>
         <div class="how-indication fs-4" data-aos="fade-right" data-aos-mirror="true" data-aos-duration="1300">
            <img src="{{ asset('images/adjust.svg') }}" alt="logo1" />
            <p class="fs-5 fs-resp">{{ __('general.Votre estimation est basé sur des parametres bien défini') }}</p>
         </div>
         <div class="how-indication fs-4" data-aos="fade-up" data-aos-mirror="true" data-aos-duration="1100">
            <img src="{{ asset('images/activity.svg') }}" alt="logo1" />
            <p class="fs-5 fs-resp">{{ __('general.Votre activités reflete votre estimation, chaque parametre est pris en considération') }}</p>
         </div>
         <div class="how-indication fs-4" data-aos="fade-down" data-aos-mirror="true" data-aos-duration="1000">
            <img src="{{ asset('images/analyze.svg') }}" alt="logo1" />
            <p class="fs-5 fs-resp">{{ __('general.Nous analysons les parametres afin de vous donner la meilleur estimation') }}</p>
         </div>
     </div>

        <div class="goto" data-aos="flip-left" @if (Session('lang') == 'ar')
            dir="rtl"
        @else
            dir="ltr"
        @endif>
            <div style="transform: translateY(10%);margin: 2rem auto;"
             class="how-goto shadow-lg" @if (Session('lang') == 'ar')
                dir="rtl"
            @else
                dir="ltr"
            @endif>
                <div>

                    <h5 class="estimate-title">{{ __('general.Commencer votre estimation') }}</h5>
                    <div class="divider"></div>
                    <p class="goto-text fs-5 fs-resp">
                        {{__('general.Il faut garder à l’esprit que votre logement a une valeur de marché,qui ne correspond pas toujours au prix auquel vous l’avez acheté.En effet, cette valeur dépend de l’évolution des prix de l’immobilier, de l’offre et de la demande au moment de la mise en vente.')}}
                    </p>
                </div>

                <div style="margin-left: 20px;">
                    {{-- <img id="goto" style="width: 160px;cursor: pointer;" src="/images/estimate.png" alt=""> --}}
                    <button id="goto" class="btn-est">
                        <span> Estimez votre bien</span>
                        <em></em>
                    </button>
                </div>


            </div>


        </div>
    </div>



</div>

<div id="estimate" class="d-none">


        <div class="estimation-head" v-if="price == null" >

            <div class="estimation-steps-container">
                <div class="estimation-steps">
                    <div class="step-progress">
                        <div class="step-progress-bar" :style="'width:' + step_progress"></div>
                    </div>
                    <div class="step-item" v-for="(step_item,i) in estimation_steps" @click="goToStep(i)">
                        <div class="step-number" :class="(step == i ? 'active' : '') + ' ' + (step > i ? 'actived' : '')">
                            <i :class="step_item.icon"></i>
                        </div>
                        <div class="step-title">@{{ step_item.title }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="estimation-form">

                <div class="errors" v-for="error in errors">
                    <div class="error">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>@{{ error }}</span>
                    </div>
                </div>

                <transition name="fade">
                    <template v-if="step==0">
                        {{-- adresse --}}
                        <div class="step-section">
                            <div class="step-section-heading mt-5">
                                <h2>Quelle est l'adresse de votre bien à estimer ?</h2>
                            </div>
                            <div class="step-section-body m-auto">
                                <div class="row">
                                    <div class="form-group col-12" id="filter-city">
                                        <label for="">Region :</label>
                                        <ml-select :options="regions" value="region" label="region"
                                            v-model:selected-option="address_form.region" mls-class="form-control"
                                            mls-placeholder="Sélectionnez une région" />
                                    </div>
                                    <div class="form-group col-12" id="filter-neighborhood">
                                        <label for="">Ville :</label>
                                        <ml-select :options="communes" value="name" label="name"
                                            v-model:selected-option="address_form.commune" mls-class="form-control"
                                            mls-placeholder="sélectionner une commune" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </transition>

                <transition name="fade">
                    <template v-if="step==1">
                        {{-- type de bien --}}
                        <div class="step-section">
                            <div class="step-section-heading my-5">
                                <h2>Quel type de bien souhaitez-vous estimer ?</h2>
                            </div>
                            <div class="step-section-body m-auto">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <div class="type-container">
                                            <template v-for="type in types">

                                                <div class="type-item hoverable" :class="type_form.type == type ? 'active' : ''"
                                                    @click="selectType(type)">
                                                    {{-- <div class="type-item-title">
                                                        @{{ type.title }}
                                                    </div> --}}
                                                    <span v-html="type.icon"></span>
                                                    <div>

                                                    </div>

                                                </div>

                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </transition>

                <transition name="fade">
                    <template v-if="step==2">
                        {{-- surface --}}
                        <div class="step-section">
                            <div class="step-section-heading my-3">
                                <h2>Quelle est la surface de votre bien ?</h2>
                            </div>
                            <div class="step-section-body m-auto">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <div class="surface-container">
                                            <template v-if="autre_surface == false">
                                                <template v-for="surface in surfaces">

                                                    <div class="surface-item hoverable"
                                                        :class="surface_form.surface == surface ? 'active' : ''"
                                                        @click="selectSurface(surface)">
                                                        <div class="surface-item-title">
                                                            <i class="fa-solid fa-ruler-combined m-1"></i>
                                                            @{{ surface }}m²
                                                        </div>
                                                    </div>
                                                </template>

                                                <div class="surface-item" @click="autre_surface = ture">
                                                    <div class="surface-item-title">
                                                        Autre
                                                    </div>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <input type="number" class="form-control me-2" min="10"
                                                        v-model="surface_form.surface">m²
                                                    <button class="btn ms-3" @click="autre_surface = false">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </template>
                </transition>

                <transition name="fade">
                    <template v-if="step==3">
                        {{-- nombre de pièces (Salons,Chambres,Salles de bain) --}}
                        <div class="step-section">
                            <div class="step-section-heading mt-5">
                                <h2>Combien de pièces comporte votre bien ?</h2>
                            </div>
                            <div class="step-section-body m-auto">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <div class="rooms-container">
                                            <div class="room-item">
                                                <div class="room-item-title">
                                                    Salons :
                                                </div>
                                                <div class="room-item-value">
                                                    <button @click="decrementRoom('salon')">-</button>
                                                    <span>@{{ rooms_form.salon }}</span>
                                                    <button @click="incrementRoom('salon')">+</button>
                                                </div>
                                            </div>
                                            <div class="room-item">
                                                <div class="room-item-title">
                                                    Chambres :
                                                </div>
                                                <div class="room-item-value">
                                                    <button @click="decrementRoom('chambre')">-</button>
                                                    <span>@{{ rooms_form.chambre }}</span>
                                                    <button @click="incrementRoom('chambre')">+</button>
                                                </div>
                                            </div>
                                            <div class="room-item">
                                                <div class="room-item-title">
                                                    Salles de bain :
                                                </div>
                                                <div class="room-item-value">
                                                    <button @click="decrementRoom('salle_de_bain')">-</button>
                                                    <span>@{{ rooms_form.salle_de_bain }}</span>
                                                    <button @click="incrementRoom('salle_de_bain')">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </template>
                </transition>

                <transition name="fade">
                    <template v-if="step==4">
                        {{-- caracteristics (parking,meublé,terrasse,syndic,cave,ascenseur) --}}
                        <div class="step-section">
                            <div class="step-section-heading mt-5">
                                <h2>Quelles sont les caractéristiques de votre bien ?</h2>
                            </div>
                            <div class="step-section-body m-auto">
                                <div class="caracteristic-container">
                                    <template v-for="cr in caracteristics">
                                        <div class="caracteristic-item hoverable"
                                            :class="caracteristics_form[cr.name] ? 'active' : ''"
                                            @click="caracteristics_form[cr.name]=!caracteristics_form[cr.name]">
                                            <i :class="cr.icon"></i>
                                            <div class="caracteristic-item-title">
                                                @{{ cr.title }}
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </transition>

                <transition name="fade">
                    <template v-if="step==5">

                        <template v-if="price==null">
                            <div class="step-section steppers">
                                <div class="calculate-container">
                                    <img src="{{ asset('images/loader.gif') }}">
                                    <div class="processing-text">
                                        <div id="processing">
                                            <i class="fas fa-spinner fa-spin me-2"></i>
                                            Votre requête est en cours de traitement ...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>

                            <template v-if="is_logged_in">
                                <div class="step-section" id="step-section">
                                    <div class="step-section-heading mt-5">
                                        <i class="fa-solid fa-circle-check mx-2" style="color: #4cc6d6"></i>
                                        Résultat d'estimation
                                    </div>
                                    <div class="step-section-body m-auto">

                                        <section>
                                            <div class="line">
                                                <div class="point">
                                                    <span>
                                                        <i class="fa-solid fa-angles-down mx-1 text-warning"></i>
                                                        Le prix bas @{{ price - 30847 }} DH
                                                    </span>
                                                </div>
                                                <div class="point">
                                                    <span>
                                                        <i class="fa-solid fa-coins mx-1"></i>
                                                        Le prix estimé @{{ price }} DH
                                                    </span>

                                                </div>
                                                <div class="point">
                                                    <span>
                                                        <i class="fa-solid fa-angles-up mx-1 text-success"></i>
                                                        Le prix haut @{{ price + 30182 }} DH
                                                    </span>

                                                </div>
                                            </div>
                                            </section>

                                            <section class="ratings-form">
                                                <form class="ratings">
                                                    <p style="margin-top: 2.2rem;font-size:1.4rem">
                                                        Que pensez-vous de cette estimation ?
                                                    </p>
                                                    <div class="rating__stars">
                                                        <input id="rating-1" class="rating__input rating__input-1" type="radio" name="rating" value="1">
                                                        <input id="rating-2" class="rating__input rating__input-2" type="radio" name="rating" value="2">
                                                        <input id="rating-3" class="rating__input rating__input-3" type="radio" name="rating" value="3">
                                                        <input id="rating-4" class="rating__input rating__input-4" type="radio" name="rating" value="4">
                                                        <input id="rating-5" class="rating__input rating__input-5" type="radio" name="rating" value="5">
                                                        <label class="rating__label" for="rating-1">
                                                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                                                <g transform="translate(16,16)">
                                                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
                                                                </g>
                                                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <g transform="translate(16,16) rotate(180)">
                                                                        <polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
                                                                        <polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
                                                                    </g>
                                                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <span class="rating__sr">1 star—Terrible</span>
                                                        </label>
                                                        <label class="rating__label" for="rating-2">
                                                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                                                <g transform="translate(16,16)">
                                                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
                                                                </g>
                                                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <g transform="translate(16,16) rotate(180)">
                                                                        <polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
                                                                        <polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
                                                                    </g>
                                                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <span class="rating__sr">2 stars—Bad</span>
                                                        </label>
                                                        <label class="rating__label" for="rating-3">
                                                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                                                <g transform="translate(16,16)">
                                                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
                                                                </g>
                                                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <g transform="translate(16,16) rotate(180)">
                                                                        <polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
                                                                        <polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
                                                                    </g>
                                                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <span class="rating__sr">3 stars—OK</span>
                                                        </label>
                                                        <label class="rating__label" for="rating-4">
                                                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                                                <g transform="translate(16,16)">
                                                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
                                                                </g>
                                                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <g transform="translate(16,16) rotate(180)">
                                                                        <polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
                                                                        <polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
                                                                    </g>
                                                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <span class="rating__sr">4 stars—Good</span>
                                                        </label>
                                                        <label class="rating__label" for="rating-5">
                                                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
                                                                <g transform="translate(16,16)">
                                                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
                                                                </g>
                                                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <g transform="translate(16,16) rotate(180)">
                                                                        <polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
                                                                        <polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
                                                                    </g>
                                                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
                                                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
                                                                    </g>
                                                                </g>
                                                            </svg>
                                                            <span class="rating__sr">5 stars—Excellent</span>
                                                        </label>
                                                        <p class="rating__display" data-rating="1" hidden>Terrible</p>
                                                        <p class="rating__display" data-rating="2" hidden>Bad</p>
                                                        <p class="rating__display" data-rating="3" hidden>OK</p>
                                                        <p class="rating__display" data-rating="4" hidden>Good</p>
                                                        <p class="rating__display" data-rating="5" hidden>Excellent</p>
                                                    </div>
                                                </form>
                                            </section>
                                            <h3 style="text-align: center;letter-spacing: 2;">
                                                <i class="fa-regular fa-comments mx-2"></i>
                                                Commentaire
                                            </h3>
                                            <section class="comment">
                                                <textarea name="comment" id="" cols="5" rows="5"></textarea>
                                                <button class="btn-c">
                                                    <i class="fa-regular fa-paper-plane mx-1"></i>
                                                    Envoyer
                                                </button>
                                        </section>


                                        {{-- price price-min price-max --}}
                                        {{-- <div class="price-container">
                                            <div class="price-range">
                                                <div class="price-min">
                                                    <div class="price-label" >Le prix bas</div>
                                                    <div class="price-value" >@{{ price - 30847 }} DH</div>
                                                </div>
                                                <div class="estimate-price">
                                                    <div class="price-label" >Le prix estimé</div>
                                                    <div class="price-value" >@{{ price }} DH</div>
                                                </div>
                                                <div class="price-max">
                                                    <div class="price-label" >Le prix haut</div>
                                                    <div class="price-value" >@{{ price + 30182 }} DH</div>
                                                </div>
                                            </div>
                                            <div class="price-range-line">
                                                <div class="price-position-left" ></div>
                                                <div class="price-position-center" ></div>
                                                <div class="price-position-right" ></div>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="price-ratin" > --}}
                                            {{-- <h2>Que pensez-vous de cette estimation ?</h2>
                                            <div class="rating-container">
                                                <div class="rating-item down" :class="rating.thumbs_down?'active':''" @click="ratingThumbsDown()">
                                                    <i class="fas fa-thumbs-down"></i>
                                                </div>
                                                <div class="rating-value" >
                                                    @{{ rating.value + '%' }}
                                                </div>
                                                <div class="rating-item up" :class="rating.thumbs_up?'active':''" @click="ratingThumbsUp()">
                                                    <i class="fas fa-thumbs-up"></i>
                                                </div>
                                            </div>
                                            <div class="form-group mt-2 d-flex">
                                                <button class="btn btn-primary btn-send mx-auto" @click="sendRating">
                                                    Envoyer la note
                                                    <i class="fa fa-envelope fa-lg ms-2" aria-hidden="true"></i>
                                                </button>
                                            </div> --}}


                                        {{-- </div> --}}




                                    </div>
                            </template>
                            <template v-else>

                                <div class="step-section">
                                    <div class="d-flex">

                                    <div class="col-md-6 p-5">
                                        <div class="step-section-heading mt-5">

                                            <h2 style="font-size:2.1rem"><img style="width:30px" src="{{ asset('images/estimation.png') }}" alt=""> Votre estimation est prête!</h2>
                                        </div>

                                        <div class="">
                                            <div class="info">
                                                <h5>
                                                    Quelques informations sont nécessaires pour vous envoyer
                                                </h5>
                                            </div>
                                            <div class="row user-info">
                                                <div class="form-group mb-2 col-6">
                                                    <label for="first-name">Votre prénom</label>
                                                    <input type="first-name" class="form-control" id="first-name"
                                                        v-model="userinfos_form.firstname">
                                                </div>
                                                <div class="form-group mb-2 col-6">
                                                    <label for="lastname">Votre nom</label>
                                                    <input type="lastname" class="form-control" id="lastname"
                                                        v-model="userinfos_form.lastname">
                                                </div>
                                                <div class="form-group my-5 col">
                                                    <span class="text-muted d-flex justify-items-center align-items-center">
                                                        <i class="fa-solid fa-circle-info mx-3 fs-5"></i>
                                                        Vos coordonnées (email, numéro de téléphone) ne seront jamais transmises à nos partenaires ou autres tiers sans votre autorisation.

                                                    </span>
                                                </div>
                                                <div class="form-group mb-2 col-12">
                                                    <label for="email">Votre email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        v-model="userinfos_form.email">
                                                </div>
                                                <div class="form-group mb-2 col-12">
                                                    <label for="phone">Votre numéro de téléphone</label>
                                                    <input type="phone" class="form-control" id="phone"
                                                        v-model="userinfos_form.phone">
                                                </div>
                                                <div class="form-group mt-3 col">
                                                    <p class="text-muted">En cliquant sur « Obtenir mon estimation », vous acceptez nos conditions générales d'utilisation <u>MULTILIST</u> .</p>
                                                </div>
                                                <div class="form-group mt-2 d-flex">
                                                    <button class="btn btn-primary btn-send mx-auto" id="send"  @click="estimation">
                                                        Obtenir mon estimation
                                                        {{-- <i class="fa fa-envelope ms-2"></i> --}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <img style="position: fixed;height: 100vh;width: 100%;clip-path: polygon(5% 0%, 100% 0%, 75% 100%, 0% 100%);" src="{{ asset('images/morocco_real_estate.png') }}" alt="">
                                    </div>

                                </div>
                                </div>



                            </template>

                        </template>

                    </template>
                </transition>

                <div class="controls fixed" v-if="price == null">
                    <div class="controls-left">
                        <button class="btn btn-primary btn-left" :disabled="step <= 0" @click="prevStep()">
                            <i class="fa fa-chevron-left me-2"></i>
                            Précédent
                        </button>
                    </div>
                    <div class="controls-right">
                        <button class="btn btn-primary btn-right" :disabled="step >= estimation_steps.length - 1"
                            @click="nextStep()">
                            Suivant
                            <i class="fa fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

@section('custom_foot')

<script>

    $(document).ready(function(){


      $('#send').on('click',function(){


             console.log('send');


      })


         $('#goto').on('click',function(){

             $('#estimation').addClass('d-none');
             $('#estimate').removeClass('d-none');
             window.scrollTo({
              top: 0,
              left: 0,
              behavior: 'smooth'
          });

         });


    })
  </script>

    <script>
        const id = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        let addApp = createApp({
            data() {
                return {
                    id: id,
                    step: -1,
                    estimation_steps: [{
                            name: 'address',
                            title: 'Adresse',
                            icon: 'fas fa-map-marker-alt',
                        },
                        {
                            name: 'type',
                            title: 'Type de bien',
                            icon: 'fas fa-building',
                        },
                        {
                            name: 'Surface',
                            title: 'Surface',
                            icon: 'fas fa-ruler-combined',
                        },
                        {
                            name: 'rooms',
                            title: 'Chambres',
                            icon: 'fas fa-bed',
                        },
                        {
                            name: 'caracteristics',
                            title: 'Caractéristiques',
                            icon: 'fa-solid fa-list-check',
                        },
                        {
                            name: 'result',
                            title: 'Resultat',
                            icon: 'fa-solid fa-circle-check',
                        }
                    ],
                    address_form: {
                        city: null,
                        neighborhood: null,
                        street: null,
                        number: null,
                        postal_code: null,
                        region: null,
                        commune: null
                    },
                    type_form: {
                        type: null
                    },
                    surface_form: {
                        surface: null
                    },
                    rooms_form: {
                        salon: 0,
                        chambre: 0,
                        salle_de_bain: 0,
                    },
                    caracteristics_form: {
                        parking: false,
                        meuble: false,
                        terrasse: false,
                        syndic: false,
                        cave: false,
                        ascenseur: false,
                    },
                    userinfos_form: {
                        firstname: null,
                        lastname: null,
                        email: null,
                        phone: null
                    },
                    price: null,
                    villes: [],
                    quartiers: [],
                    types: [{
                            name: 'villa',
                            title: 'Villa',
                            icon: '<span class="radio-rect__info"><svg width="56px" height="56px" viewBox="0 0 56 56" version="1.1" xmlns="http://www.w3.org/2000/svg" class="radio-rect__icon path__fill"><title>Hotel Particulier</title><defs><polygon id="path-1" points="0 35.9854 52 35.9854 52 0.0004 0 0.0004"></polygon></defs><g id="assets" stroke="none" stroke-width="1" fill="none" fill-rule="fill" opacity="0.6"><g id="Hotel"><g id="Page-1" transform="translate(2.000000, 11.000000)"><g id="Clip-5"></g><path d="M48.0001,13.9854 L36.9561,13.9854 L36.0001,13.9854 L36.0001,10.9854 C36.0001,10.7384 35.9081,10.5004 35.7431,10.3164 L31.8461,5.9854 L44.4341,5.9854 L49.2331,13.9854 L48.0001,13.9854 Z M47.0001,33.9854 L36.0001,33.9854 L36.0001,15.9854 L36.9561,15.9854 L47.0001,15.9854 L47.0001,33.9854 Z M31.0001,33.9854 L31.0001,22.9854 C31.0001,21.8824 30.1031,20.9854 29.0001,20.9854 L23.0001,20.9854 C21.8971,20.9854 21.0001,21.8824 21.0001,22.9854 L21.0001,33.9854 L18.0001,33.9854 L18.0001,11.3694 L26.0001,2.4804 L34.0001,11.3694 L34.0001,33.9854 L31.0001,33.9854 Z M23.0001,33.9854 L29.0001,33.9854 L29.0001,22.9854 L23.0001,22.9854 L23.0001,33.9854 Z M5.0001,15.9854 L15.0431,15.9854 L16.0001,15.9854 L16.0001,33.9854 L5.0001,33.9854 L5.0001,15.9854 Z M7.5661,5.9854 L20.1551,5.9854 L16.2571,10.3164 C16.0911,10.5004 16.0001,10.7384 16.0001,10.9854 L16.0001,13.9854 L15.0431,13.9854 L4.0001,13.9854 L2.7661,13.9854 L7.5661,5.9854 Z M51.8571,14.4704 L45.8571,4.4704 C45.6771,4.1694 45.3521,3.9854 45.0001,3.9854 L30.0451,3.9854 L26.7431,0.3164 C26.3641,-0.1056 25.6361,-0.1056 25.2571,0.3164 L21.9551,3.9854 L7.0001,3.9854 C6.6491,3.9854 6.3231,4.1694 6.1431,4.4704 L0.1431,14.4704 C-0.0429,14.7794 -0.0479,15.1644 0.1301,15.4784 C0.3071,15.7914 0.6401,15.9854 1.0001,15.9854 L3.0001,15.9854 L3.0001,34.9854 C3.0001,35.5384 3.4481,35.9854 4.0001,35.9854 L17.0001,35.9854 L21.0001,35.9854 L21.5791,35.9854 L22.0001,35.9854 L30.0001,35.9854 L30.4211,35.9854 L35.0001,35.9854 L48.0001,35.9854 C48.5531,35.9854 49.0001,35.5384 49.0001,34.9854 L49.0001,15.9854 L51.0001,15.9854 C51.3601,15.9854 51.6921,15.7914 51.8701,15.4784 C52.0481,15.1644 52.0431,14.7794 51.8571,14.4704 L51.8571,14.4704 Z" id="Fill-1" fill="#393939"></path><path d="M9,28.9854 L12,28.9854 L12,22.9854 L9,22.9854 L9,28.9854 Z M13,20.9854 L8,20.9854 C7.448,20.9854 7,21.4324 7,21.9854 L7,29.9854 C7,30.5384 7.448,30.9854 8,30.9854 L13,30.9854 C13.552,30.9854 14,30.5384 14,29.9854 L14,21.9854 C14,21.4324 13.552,20.9854 13,20.9854 L13,20.9854 Z" id="Fill-4" fill="#393939"></path><path d="M40,28.9854 L43,28.9854 L43,22.9854 L40,22.9854 L40,28.9854 Z M39,30.9854 L44,30.9854 C44.553,30.9854 45,30.5384 45,29.9854 L45,21.9854 C45,21.4324 44.553,20.9854 44,20.9854 L39,20.9854 C38.447,20.9854 38,21.4324 38,21.9854 L38,29.9854 C38,30.5384 38.447,30.9854 39,30.9854 L39,30.9854 Z" id="Fill-6" fill="#393939"></path><path d="M22.1265,14.4854 C22.5715,12.7624 24.1395,11.4854 26.0005,11.4854 C27.8605,11.4854 29.4285,12.7624 29.8735,14.4854 L22.1265,14.4854 Z M26.0005,9.4854 C22.6915,9.4854 20.0005,12.1764 20.0005,15.4854 C20.0005,16.0374 20.4475,16.4854 21.0005,16.4854 L31.0005,16.4854 C31.5525,16.4854 32.0005,16.0374 32.0005,15.4854 C32.0005,12.1764 29.3085,9.4854 26.0005,9.4854 L26.0005,9.4854 Z" id="Fill-7" fill="#393939"></path></g></g></g></svg><span class="radio-rect__label">Villa</span></span>'
                        },
                        {
                            name: 'appart',
                            title: 'Appartement',
                            icon: '<span class="radio-rect__info"><svg width="56px" height="56px" viewBox="0 0 56 56" version="1.1" xmlns="http://www.w3.org/2000/svg" class="radio-rect__icon path__stroke__checked polyline__stroke__checked"><title>Appartement</title><g id="assets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" opacity="0.6" stroke-linejoin="round"><g id="Appartment" stroke="#393939" stroke-width="2"><g id="Page-1" transform="translate(10.000000, 7.000000)"><path d="M4,41 L4,1 C4,0.448 4.448,0 5,0 L31,0 C31.553,0 32,0.448 32,1 L32,41 L21,41 L21,33 C21,32.447 20.553,32 20,32 L16,32 C15.448,32 15,32.447 15,33 L15,41 L4,41 Z" id="Stroke-1" stroke-linecap="round"></path><path d="M11,12 L11,9" id="Stroke-3" stroke-linecap="square"></path><path d="M18,12 L18,9" id="Stroke-5" stroke-linecap="square"></path><path d="M25,12 L25,9" id="Stroke-7" stroke-linecap="square"></path><path d="M11,23 L11,20" id="Stroke-9" stroke-linecap="square"></path><path d="M18,23 L18,20" id="Stroke-11" stroke-linecap="square"></path><path d="M25,23 L25,20" id="Stroke-13" stroke-linecap="square"></path><path d="M0,41 L36,41" id="Stroke-15" stroke-linecap="round"></path></g></g></g></svg><span class="radio-rect__label radio-rect__label--checked">Appartement</span></span>'
                        },
                        {
                            name: 'maison',
                            title: 'Maisons',
                            icon: `<span class="radio-rect__info"><svg width="56px" height="56px" viewBox="0 0 56 56" version="1.1" xmlns="http://www.w3.org/2000/svg" class="radio-rect__icon path__stroke polyline__stroke"><title>Maison</title><g id="assets" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" opacity="0.6" stroke-linejoin="round"><g id="House" stroke-width="2" stroke="#393939"><g id="Page-1" transform="translate(8.000000, 13.000000)"><polyline id="Stroke-1" points="-7.27196081e-14 13 20 0 40 13"></polyline><path d="M6,9 L6,31 L16,31 L16,22 C16,21.447 16.448,21 17,21 L23,21 C23.553,21 24,21.447 24,22 L24,31 L34,31 L34,9" id="Stroke-3"></path></g></g></g></svg><span class="radio-rect__label">Maison</span></span>`
                        }
                    ],
                    caracteristics: [{
                            name: 'parking',
                            title: 'Parking',
                            icon: 'multilist-icons multilist-parking'
                        },
                        {
                            name: 'meuble',
                            title: 'Meublé',
                            icon: 'multilist-icons multilist-sofa'
                        },
                        {
                            name: 'terrasse',
                            title: 'Terrasse',
                            icon: 'multilist-icons multilist-terrasse'
                        },
                        {
                            name: 'syndic',
                            title: 'Syndic',
                            icon: 'multilist-icons multilist-syndic'
                        },
                        {
                            name: 'cave',
                            title: 'Cave',
                            icon: 'multilist-icons multilist-cave'
                        },
                        {
                            name: 'ascenseur',
                            title: 'Ascenseur',
                            icon: 'multilist-icons multilist-elevator'
                        }
                    ],
                    regions: [],
                    communes: [],
                    surfaces: [50, 100, 150, 200, 250, 300, 350, 400],
                    autre_surface: false,
                    copyOfPrevStepSection: null,
                    processing: false,
                    is_logged_in: false,
                    errors: [],
                    rating : {
                        value : 82,
                        thumbs_up : false,
                        thumbs_down : false
                    }
                }
            },
            computed: {
                step_progress() {
                    return (this.step / (this.estimation_steps.length - 1)) * 100 + '%';
                },
            },
            mounted() {
                this.step = -1;
                this.goToStep(0);
                this.loadVilles();
                this.loadRegions();

                // check if user is logged in
                if (this.id == '') {
                    this.is_logged_in = false;
                }

                // get user from blade
                this.userinfos_form.firstname = '{{ Auth()->user() ? Auth()->user()->firstname : '' }}';
                this.userinfos_form.lastname = '{{ Auth()->user() ? Auth()->user()->lastname : '' }}';
                this.userinfos_form.email = '{{ Auth()->user() ? Auth()->user()->email : '' }}';
                this.userinfos_form.phone = '{{ Auth()->user() ? Auth()->user()->phone : '' }}';

            },
            components: {
                "MlSelect": MlSelect
            },
            watch: {
                // address_form.region
                'address_form.region': function(val) {
                    if (val) {
                        this.communes = this.regions.find(r => r.region == val).communes;
                    } else {
                        this.communes = [];
                    }
                },
            },
            methods: {
                scrollToStepProgress(step) {
                    // get the scroll container estimation-steps-container
                    let estimation_steps_container = document.querySelector('.estimation-steps-container');

                    // get the step element step-item
                    let step_items = document.querySelectorAll('.step-item');
                    let step_item = step_items[step];

                    // scroll to the step element
                    estimation_steps_container.scrollTo({
                        top: 0,
                        left: step_item.offsetLeft,
                        behavior: 'smooth'
                    });

                    // scroll to top of window
                    window.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });
                },
                nextStep() {
                    if (this.step < this.estimation_steps.length - 1) {
                        this.goToStep(this.step + 1);
                    }
                },
                prevStep() {
                    if (this.step > 0) {
                        this.goToStep(this.step - 1);
                    }
                },
                goToStep(step) {

                    // validate step's

                    this.errors = [];

                    // if the step is 1, validate the address_form
                    if (step == 1) {
                        // check if region is selected
                        if (!this.address_form.region) {
                            this.errors.push('Veuillez sélectionner une région');
                            return;
                        }

                        // check if commune is selected
                        if (!this.address_form.commune) {
                            this.errors.push('Veuillez sélectionner une commune');
                            return;
                        }
                    }
                    // validate type_form.type
                    else if (step == 2) {
                        if (!this.type_form.type) {
                            this.errors.push('Veuillez sélectionner un type');
                            return;
                        }
                    }
                    // validate surface_form.surface
                    else if (step == 3) {
                        if (!this.surface_form.surface) {
                            this.errors.push('Veuillez sélectionner une surface');
                            return;
                        }
                    }
                    // validate rooms_form
                    else if (step == 4) {
                         if (this.rooms_form.salon == 0 && this.rooms_form.chambre == 0 && this.rooms_form.salle_de_bain == 0) {
                            this.errors.push('Veuillez sélectionner au moins une pièce');
                            return;
                        }
                    }
                    // validate caracteristics_form
                    else if (step == 5) {
                         if (this.caracteristics_form.parking == false && this.caracteristics_form.meuble == false && this.caracteristics_form.terrasse == false && this.caracteristics_form.syndic == false && this.caracteristics_form.cave == false && this.caracteristics_form.ascenseur == false) {
                            this.errors.push('Veuillez sélectionner au moins une caractéristique');
                            return;
                         }
                    }

                    // check if the step is valid
                    if (step >= 0 && step < this.estimation_steps.length && step <= this.step + 1) {
                        this.step = step;
                        this.scrollToStepProgress(this.step);

                        // check if the last step
                        if (this.step == this.estimation_steps.length - 1) {
                            this.submit();
                        }

                    }

                },
                loadQuartiers(city) {
                    this.quartiers = [{
                        id: null,
                        name: "sélectionner un quartier"
                    }];

                    this.neighborhood = null;

                    if (!city) {
                        return;
                    }

                    // load quartiers
                    axios
                        .get(
                            "/api/v2/multilistfilterfrom/neighborhoodsbycity?city=" +
                            city
                        )
                        .then((response) => {
                            let data = response.data.data;
                            this.quartiers = [{
                                id: null,
                                name: "sélectionner un quartier"
                            }, ].concat(data);
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                loadVilles() {
                    // post 'city/allCities'

                    axios
                        .get(
                            "/api/v2/city/allCities"
                        )
                        .then((response) => {
                            let data = response.data.data;
                            this.villes = [{
                                id: null,
                                name: "sélectionner une ville"
                            }].concat(data);
                            console.log('rep:',response.data.data)
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                loadRegions() {
                    axios
                        .get(
                            "/api/v2/regions"
                        )
                        .then((response) => {
                            let data = response.data.data;
                            this.regions = data;
                            this.address_form.region = null;
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },
                loadTypes() {},
                selectType(type) {
                    if (this.type_form.type == type) {
                        this.type_form.type = null;
                        return;
                    }

                    this.type_form.type = type;
                },
                selectSurface(surface) {
                    if (this.surface_form.surface == surface) {
                        this.surface_form.surface = null;
                        return;
                    }

                    this.surface_form.surface = surface;
                },
                incrementRoom(room) {
                    this.rooms_form[room]++;
                },
                decrementRoom(room) {
                    if (this.rooms_form[room] > 0) {
                        this.rooms_form[room]--;
                    }
                },
                changeProcessingText(text) {
                    // get the element processing
                    let processing = document.querySelector('#processing');

                    // set the text
                    processing.innerText = text;

                    // using anime.js to move the element from buttom to top
                    anime({
                        targets: processing,
                        translateY: -100,
                        easing: 'easeInOutQuad',
                        duration: 1000,
                        complete: function(anim) {
                            // set the text
                            processing.innerText = text;

                            // move the element back to buttom
                            anime({
                                targets: processing,
                                translateY: 0,
                                easing: 'easeInOutQuad',
                                duration: 1000,
                            });
                        }
                    });



                },
                result(price) {
                    this.price = price;
                },
                resultError() {
                    this.price = null;
                },
                ratingThumbsDown(){
                    this.rating.thumbs_up = false;
                    this.rating.thumbs_down = true;

                    // animate the thumbs down
                    // get the element .rating-item.up
                    let thumbsDown = document.querySelector('.rating-item.down');

                    // using anime.js add some movement to the element
                    anime({
                        targets: thumbsDown,
                        scale: 1.2,
                        rotate: 10,
                        easing: 'easeInOutQuad',
                        duration: 200,
                        complete: function(anim) {
                            // move the element back to normal
                            anime({
                                targets: thumbsDown,
                                scale: 1,
                                rotate: 0,
                                easing: 'easeInOutQuad',
                                duration: 200,
                            });
                        }
                    });

                },
                ratingThumbsUp(){
                    this.rating.thumbs_up = true;
                    this.rating.thumbs_down = false;

                    // animate the thumbs up
                    // get the element .rating-item.up
                    let thumbsUp = document.querySelector('.rating-item.up');

                    // using anime.js add some movement to the element
                    anime({
                        targets: thumbsUp,
                        scale: 1.2,
                        rotate: -10,
                        easing: 'easeInOutQuad',
                        duration: 200,
                        complete: function(anim) {
                            // move the element back to normal
                            anime({
                                targets: thumbsUp,
                                scale: 1,
                                rotate: 0,
                                easing: 'easeInOutQuad',
                                duration: 200,
                            });
                        }
                    });
                },
                submit() {

                    this.price = null;

                    // processing text's with icons
                    let processing_texts = [{
                            text: 'Nous calculons la valeur de votre bien 👌',
                            icon: 'fa-solid fa-calculator'
                        },
                        {
                            text: 'Nous veillerons de vous donner la meilleure estimation 💪',
                            icon: 'fa-solid fa-clock'
                        },
                        {
                            text: 'Le processus peut prendre quelques minutes 🕐',
                            icon: 'fa-solid fa-hard-hat'
                        },
                        {
                            text: 'Détendez vous 😁',
                            icon: 'fa-solid fa-coffee'
                        },
                        {
                            text: 'On a presque terminé...',
                            icon: 'fa-solid fa-hourglass'
                        },

                    ];

                    // set the text's with animation
                    let animateProcessingText = (index) => {
                        anime({
                            targets: processing,
                            translateY: [0, 10],
                            opacity: [1, 0],
                            easing: 'easeInOutQuad',
                            duration: 100,
                            delay: 3000,
                            complete: function(anim) {
                                // set the text
                                processing.innerHTML =
                                    `<i class="${processing_texts[index].icon} me-2"></i> ${processing_texts[index].text}...`;

                                // move the element back to buttom
                                anime({
                                    targets: processing,
                                    translateY: [10, 0],
                                    opacity: [0, 1],
                                    easing: 'easeInOutQuad',
                                    duration: 100,
                                    complete: () => {
                                        animateProcessingText((index + 1) %
                                            processing_texts.length);
                                    }
                                });
                            }
                        });
                    }

                    var data = {
                        region: this.address_form.region ?? 'Casablanca-Settat',
                        commune: this.address_form.commune ?? 0,
                        surface: this.surface_form.surface ?? 0,
                        type: this.type_form.type?.name ?? 'appart'
                    };

                    var config = {
                        method: 'post',
                        url: 'https://multilistia.com:8000',
                        headers: {
                            'Access-Control-Allow-Origin': '*',
                            'Content-Type': 'application/json; charset=UTF-8',
                            'Access-Control-Allow-Headers': '*',
                            'Accept': 'application/json',


                        },
                        data: data
                    };

                    let waitElem = setInterval(() => {
                        let processing = document.querySelector('#processing');
                        if (processing) {
                            clearInterval(waitElem);
                            animateProcessingText(0);

                            axios(config)
                            .then((response) => {
                                let price = response.data.prix;
                                let finalData = {
                                    region: data.region,
                                    commune: data.commune,
                                    surface: data.surface,
                                    type: data.type,
                                    estimated_price: price
                                }

                                console.log('this is response : ',response)
                                if(response.status == 200){
                                    localStorage.setItem('estimated_data',JSON.stringify(finalData))
                                }

                                // check if the price is valid number
                                if (!isNaN(price)) {

                                    this.price = price;

                                    if (this.is_logged_in) {
                                        console.log('logged in')
                                        this.estimation();
                                    }else{
                                        console.log('logged out')
                                    }

                                    return;
                                }


                            })
                            .catch(function(error) {
                                console.log(error);
                            });
                        }
                    }, 5000);



                },
                estimation() {

                    this.errors = [];


                    if (!this.userinfos_form.email) {
                        this.errors.push('Saisir votre email');
                        return;
                    }
                    if (!this.userinfos_form.firstname) {
                        this.errors.push('Saisir votre prénom');
                        return;
                    }
                    if (!this.userinfos_form.lastname) {
                        this.errors.push('Saisir votre nom');
                        return;
                    }
                    if (!this.userinfos_form.phone) {
                        this.errors.push('Saisir votre téléphone');
                        return;
                    }


                    console.log('this.user',this.userinfos_form);


                    this.is_logged_in = true;

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                }
            }
        }).mount('#estimate');
    </script>




    <script>
        // watch the window size
        window.addEventListener('resize', () => {

            fixControls();
            hideEstimationHead();

        });

        fixControls();
        hideEstimationHead();

        function fixControls() {
            // get the .controls
            let controls = document.querySelector('.controls');

            // console.log(window.innerHeight);
            // if the height of the window is less than 1200px remove the class .fixed
            if (window.innerHeight < 400) {
                controls.classList.remove('fixed');
            } else {
                controls.classList.add('fixed');
            }
        }

        // function to hide .estimation-head if the screen is less than 500px
        function hideEstimationHead() {
            // get the .estimation-head
            let estimation_head = document.querySelector('.estimation-head');

            console.log(window.innerWidth, estimation_head);

            // if the height of the window is less than 500px remove the class .fixed
            if (window.innerHeight < 400) {
                estimation_head.classList.add('d-none');
            } else {
                estimation_head.classList.remove('d-none');
            }
        }
    </script>


<script src="https://unpkg.com/aos@next/dist/aos.js"></script>

<script>
    AOS.init({
    duration: 1200,
    })
</script>

<style>
@media screen and (max-width: 600px) {
    .fs-resp{
        /* margin: 2rem auto !important; */
        font-size: 15px !important;
    }
    .how-content{
        margin-top: 5rem;
    }
    .how-goto{
        margin-top: 7rem !important;
        margin-bottom: 3rem !important;
    }
}

.radio-rect__info {
    align-items: center;
    color: #3a3a3a;
    cursor: pointer;
    display: inline-flex;
    flex-direction: column;
    justify-content: center;
    text-align: center;
}


.btn-est {
    cursor: pointer;
    background-color: #1375e6e6;
    width: 285px;
    height: 64px;
    line-height: 64px;
    position: absolute;
    left: 70%;
    top: 99%;
    transform: translate(-50%, -50%);
    z-index: 0;
    font-size: .3rem !important;
    box-shadow: 0px 0px 17px 1px rgb(0 0 0 / 34%);
}

.btn-est span {
  color: #fff;
  display: block;
  padding-left: 35px;
  text-transform: uppercase;
  font: bold 15px/66px Arial;
  transform: scaleX(0.6);
  letter-spacing: 3px;
  transform-origin: center left;
  transition: color 0.3s ease;
  position: relative;
  z-index: 1;
}
.btn-est em {
  position: absolute;
  height: 1px;
  background: #fff;
  width: 35%;
  right: 13px;
  top: 50%;
  transform: scaleX(0.25);
  transform-origin: center right;
  transition: all 0.3s ease;
  z-index: 1;
}
.btn-est:before,
.btn-est:after {
  content: '';
  background: #0da9fd;
  height: 51%;
  width: 0;
  position: absolute;
  transition: 0.3s cubic-bezier(0.785, 0.135, 0.15, 0.86);
}
.btn-est:before {
  top: 0;
  left: 0;
  right: auto;
}
.btn-est:after {
  bottom: 0;
  right: 0;
  left: auto;
}
.btn-est:hover:before {
  width: 100%;
  right: 0;
  left: auto;
}
.btn-est:hover:after {
  width: 100%;
  left: 0;
  right: auto;
}
.btn-est:hover span {
  color: #000;
}
.btn-est:hover em {
  background: #000;
  transform: scaleX(1);
}


</style>

@endsection

