@extends('v2.dashboard')

@section('title1',__("general.Mes annonces"))

@section('custom_head1')

    <script src="/assets/vendor/jquery.min.js"></script>
    <script src="{{ asset('js/components/slider.vue.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/popup-components.vue.css') }}">
    <script src="{{ asset('js/components/popup-components.vue.js') }}"></script>

    <script src="{{ asset('js/anime.min.js') }}"></script>

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/myitems.styles.css') }}">

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/add.styles.css') }}">

    <script src="{{ asset('js/uploadFiles.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/components/multilist-filter.component.vue.css') }}">
    <script src="{{ asset('js/components/multilist-filter-component.vue.js') }}"></script>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>


    <style>

        @media screen and (max-width: 426px){

            .btns-recharge{

                 display: flex !important;
                 width:100% !important;
                 justify-content: center;
                 align-items: center;

            }


        }

    </style>




@endsection

@section('content1')


    <div class="d-none" id="myads-app">
        <section id="filter-bar" class="filter-bar site-section" data-v-app="">
            <div class="search-input">
                <label class="col-sm-4 col-form-label">{{__('general.Rechercher')}}</label>
                <div class="col-sm-12">
                    <input type="text" class="form-control" @keyup.enter="searchInput()" v-model="filters.search" @keyup.enter="search()"
                    placeholder="{{__('general.Rechercher')}}">
                </div>

            </div>
            <div class="search-input">
                <div class="row">
                    <label class="col-sm-4 col-form-label">{{__('general.État')}}</label>
                    <div class="col-sm-12">
                        <select @change="search" v-model="filters.status" class="form-select"
                            aria-label="Default select example">
                            <option :value="null">{{__('general.tous')}}</option>
                            <template v-for="s of status_arr">
                                <option :value="s.val">@{{ s.desc }}</option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            <div class="filter-buttons">

                <button @click="showFilter()" class="filter-button more-filter-button">
                    <i class="fa-solid fa-sliders me-2"></i>
                    {{__('general.Filtres')}}
                </button>

            </div>

            <div class="filter-buttons mobile-filter-buttons">
                <button @click="showFilter()" class="filter-button more-filter-button">
                    <i class="fa-solid fa-sliders me-2"></i>
                </button>
            </div>

        </section>

        <multilist-filter-component v-on:submitfilter="submitfilter">
        </multilist-filter-component>

        <div style="text-align: right;margin-top: 10px;">
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


        <div class="border-bottom mb-2 d-flex align-items-center justify-content-between pb-2">
            <span style="font-size: 12px;">@{{ searchTotal }} {{ __('general.Sur total de') }} @{{ total }}</span>
            <div class="search-input">
                <div style="display:flex;align-items: center;">
                    <div class="sort-label"><i class="fas fa-arrow-down-wide-short"></i></div>
                    <div>
                        <select @change="changeSort()" class="sort-select" v-model="sort">
                            <option :value="null">{{ __('general.Date') }}</option>
                            <option value="price">{{ __('general.Prix croissant') }}</option>
                            <option value="priceD">{{__('general.Prix décroissant')}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        @if (Auth()->user()->usertype != 3)
            <div class="multiads_cnt">

                <div v-for="a of ads" class="multiads" @click="toItemPage($event,a)">
                    <div class="img media">
                        <Slider :images="a.images"></Slider>
                    </div>
                    <div class="content">
                        <div class="multiads-section">
                            <span class="publication-date">
                                <i class="fa-regular fa-calendar-alt me-2"></i><span class="translates">Ajouté le @{{ formatDateTime(a.date) }}</span>
                            </span>
                            <div class="status_box" :class="'s_' + a.status">@{{ displayStatus(a.status) }}</div>
                            <div v-if="a.ref" style="color: gray;font-size: 11px;">@{{ '#'+a.ref }}</div>
                            <div class="title">
                                <a>
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

                        <div class="multiads-section">
                            <div class="multiads-cards-cnt">
                                <div class="multiads-card" title="vues"> <i class="fas fa-eye"></i> @{{ a.views }}
                                </div> <!-- views -->
                                <div class="multiads-card" title="whatsapp"> <i class="fa-brands fa-whatsapp"></i>
                                    @{{ a.wtsps }} </div> <!-- whatsapp -->
                                <div class="multiads-card" title="téléphone"> <i class="fas fa-phone"></i>
                                    @{{ a.phones }} </div> <!-- phones -->
                                <div class="multiads-card" title="emails"> <i class="fas fa-envelope"></i>
                                    @{{ a.emails }} </div> <!-- emails -->
                            </div>
                        </div>

                        <div class="multiads-option" v-if="a.current_option">
                            <div class="current_option_card">
                                <div class="option_name"><i class="fas fa-rocket" style="color: var(--primary-color)"></i> @{{  a.current_option }}</div>
                                <div class="option_date">va expirer le @{{ formatDateTime(a.option_expired_date) }}</div>
                            </div>
                        </div>

                        <div class="multiads-btns-cnt">
                            <button class="btn multiads-btn boost" @click="boost(a.id)"> <i class="fas fa-rocket"></i>
                                {{__('general.Booster')}} </button>
                            <div style="display: flex; flex: 1;">
                                <button class="btn multiads-btn" @click="updateAd(a)"> <i class="fas fa-edit"></i> </button>
                                <button v-if="a.status!='70'" class="btn multiads-btn delete" @click="deleteAd(a.id)"> <i
                                        class="fas fa-trash"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

        @if (Auth()->user()->usertype == 3)
            <div class="Projects_cnt">

                <div class="list-container" style="margin-top: 80px;">
                    <a v-for="l of projects" class="list-item" @click="toItemPage($event,l)">
                        <div class="list-item-container" style="position: relative;">
                            <div class="item-body">
                                <div class="media">
                                    <Slider :images="l.images"></Slider>
                                </div>
                                <div class="content">
                                    <div class="status_box" :class="'s_' + l.status">@{{ displayStatus(l.status) }}</div>
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
                                            <i class="multilist-icons multilist-location"></i>
                                            @{{ localisation(l) }}
                                        </span>
                                    </div>
                                    <div class="price">
                                        <span>@{{ price(l) }}</span>
                                    </div>
                                    <div class="multiads-section">
                                        <div class="multiads-cards-cnt">
                                            <div class="multiads-card" title="vues"> <i class="fas fa-eye"></i>
                                                @{{ l.views }} </div> <!-- views -->
                                            <div class="multiads-card" title="whatsapp"> <i
                                                    class="fa-brands fa-whatsapp"></i> @{{ l.wtsps }} </div>
                                            <!-- whatsapp -->
                                            <div class="multiads-card" title="téléphone"> <i class="fas fa-phone"></i>
                                                @{{ l.phones }} </div> <!-- phones -->
                                            <div class="multiads-card" title="emails"> <i class="fas fa-envelope"></i>
                                                @{{ l.emails }} </div> <!-- emails -->
                                        </div>
                                    </div>

                                    <div class="multiads-btns-cnt">
                                        <button class="btn multiads-btn" @click="updateAd(l)"> <i
                                            class="fas fa-edit"></i> Modifier </button>
                                        <button class="btn multiads-btn" @click="addDispoAd(l)"> <i
                                            class="fas fa-add"></i> Ajouter disponibilité </button>
                                    </div>
                                </div>
                            </div>
                            <div class="item-info btn" style="margin: 0;border-radius: 0;">
                                <span class="publication-date">
                                    <i class="fa-regular fa-calendar-alt me-2"></i>
                                    <span>{{__('general.Publié le')}} @{{ formatDateTime(l.date) }}</span>
                                </span>
                                <span class="features">

                                    <button @click="showDispos(l)" class="btn btn-showdispos">{{__('general.voir les disponibilités')}} <i
                                            v-if="l.showDispos" class="fas fa-caret-up"></i><i v-else
                                            class="fas fa-caret-down"></i></button>

                                </span>
                            </div>
                            <div v-if="l.showDispos" class="item-dispos btn" style="cursor: auto;">
                                <div class="item-dispos-items-cnt slide" id="slide-dispos">
                                    <button class="btn mov-btn-next">
                                        <i class="fa-solid fa-angle-right"></i>
                                    </button>
                                    <button class="btn mov-btn-prev">
                                        <i class="fa-solid fa-angle-left"></i>
                                    </button>
                                    <div v-for="a of dispos" class="slide-item">
                                        <div class="multiads" @click="toItemPage($event,a)"
                                            style="text-align: start;cursor: pointer;width:285px;">
                                            <div class="img media" style="width: 100%;height: 200px;">
                                                <Slider :images="a.images"></Slider>
                                            </div>
                                            <div class="content">
                                                <div class="multiads-section">
                                                    <div class="status_box" :class="'s_' + a.status">
                                                        @{{ displayStatus(a.status) }}
                                                    </div>
                                                    <div class="title" style="margin-bottom: 10px;">
                                                        <h1 style="font-size: 14px;margin-bottom: 0;">
                                                            @{{ a.title }}</h1>
                                                    </div>
                                                    <div style="font-size: 11px;">
                                                        <span>@{{ a.categorie }}</span>
                                                    </div>
                                                    <div class="location" style="color: gray;font-size: 10px;">
                                                        <span>
                                                            <i class="multilist-icons multilist-location"></i>
                                                            <span> @{{ localisation(a) }} </span>
                                                        </span>
                                                    </div>
                                                    <div class="price">
                                                        <span> @{{ price(a) }} </span>
                                                    </div>
                                                </div>

                                                <div class="multiads-section">
                                                    <div class="multiads-cards-cnt">
                                                        <div class="multiads-card" style="font-size: 10px;margin: 2px;"
                                                            title="vues"> <i class="fas fa-eye"></i>
                                                            @{{ a.views }} </div> <!-- views -->
                                                        <div class="multiads-card" style="font-size: 10px;margin: 2px;"
                                                            title="whatsapp"> <i class="fa-brands fa-whatsapp"></i>
                                                            @{{ a.wtsps }} </div> <!-- whatsapp -->
                                                        <div class="multiads-card" style="font-size: 10px;margin: 2px;"
                                                            title="téléphone"> <i class="fas fa-phone"></i>
                                                            @{{ a.phones }} </div> <!-- phones -->
                                                        <div class="multiads-card" style="font-size: 10px;margin: 2px;"
                                                            title="emails"> <i class="fas fa-envelope"></i>
                                                            @{{ a.emails }} </div> <!-- emails -->
                                                    </div>
                                                </div>

                                                <div class="multiads-btns-cnt">
                                                    <div style="display: flex; flex: 1;">
                                                        <button class="btn multiads-btn" style="padding: 3px;"
                                                            @click="updateAd(a)"> <i class="fas fa-edit"></i> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-if="dispos.length == 0&&!dispoLoader" class="text-muted"
                                        style="text-align: center;">
                                        <span>{{__('general.Aucun disponibilité trouvé')}}</span>
                                    </div>

                                    <div v-if="dispoLoader" class="loader"></div>

                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
        @endif

        @if (Auth()->user()->usertype != 3)
            <div v-if="ads.length == 0&&!loader" class="text-muted" style="text-align: center;">
                <span>{{__('general.Aucun résultat trouvé')}}</span>
            </div>
        @endif

        @if (Auth()->user()->usertype == 3)
            <div v-if="projects.length == 0&&!loader" class="text-muted" style="text-align: center;">
                <span>{{__('general.Aucun résultat trouvé')}}</span>
            </div>
        @endif

        <div v-if="loader" class="loader"></div>

    </div>

    <div id="booster-app" class="d-none">
        <popsup-component :title="`Booster l'annonce`" v-model:display="display">
            <div class="s_popup-component-container d-none">
                <section class="section">
                    <div class="card" style="margin: auto;padding: 30px 0;">
                        <div class="card-body">

                            <div class="btns-recharge">
                                <button type="button" class="btn btn-primary recharge" style=" font-size:14px; background-color: #EE9522; !important; border-color: whitesmoke;
                            " disabled>
                                <i class="fa-solid fa-coins"></i> @{{  coins }} LTC
                              </button>

                              <button type="button" class="btn btn-primary recharge" style=" font-size:14px; background-color: #6518ca; !important; border-color: whitesmoke;
                            " @click="Recharger()">
                                <i class="fa-solid fa-circle-plus"></i> {{__('general.Recharger')}}
                              </button>
                            </div>



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
                            <button class="btn btn-success" @click="save0" :disabled="loader">
                                <span>{{__('general.Sauvegarder')}}</span>
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loader" role="status">
                                    <span class="sr-only">Loading...</span>
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

                    <div class="section-title page-title">{{__("general.Modifier l'annonce")}}</div>

                    <div class="">

                        @if (Auth()->user()->usertype != 3)
                            <section class="item-section" id="type-section">
                                <div class="section-heading">
                                    <h1>Type:</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <div class="type-cards-container">
                                    <div v-for="t of types" class="type-card" :class="t == type ? 'active-card' : ''"
                                        @click="selectType(t)">
                                        @{{ t }}
                                    </div>
                                </div>
                            </section>

                            @if(Session::get('lang') === 'ar')
                            <section v-if="type === 'location'" class="item-section translate" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='location'" :value="cat.id">@{{ cat.property_type_ar }}
                                        </option>



                                    </template>
                                </select>

                                {{--<div class="cat-cards-container">
                                    <template v-for="c in categories">
                                        <div v-if="c.type==type" class="cat-card" :class="c.id == form.catid ? 'active-card' : ''"
                                            @click="selectCat(c.id)">@{{ c.title }}</div>
                                    </template>
                                </div>--}}
                            </section>

                            <section v-if="type === 'vente'" class="item-section translate" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='vente'" :value="cat.id">@{{ cat.property_type_ar }}
                                        </option>



                                    </template>
                                </select>

                            </section>

                            <section v-if="type === 'vacance'" class="item-section translate" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='vacance'" :value="cat.id">@{{ cat.property_type_ar }}
                                        </option>



                                    </template>
                                </select>
                            </section>

                            @else
                            <section v-if="type === 'location'" class="item-section" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='location'" :value="cat.id">@{{ cat.property_type }}
                                        </option>
                                    </template>
                                </select>
                            </section>

                            <section v-if="type === 'vente'" class="item-section" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='vente'" :value="cat.id">@{{ cat.property_type }}
                                        </option>
                                    </template>
                                </select>

                            </section>

                            <section v-if="type === 'vacance'" class="item-section" id="category-section">
                                <div class="section-heading translate">
                                    <h1>{{ __('general.Catégorie') }} :</h1>
                                    <div class="heading-underline"></div>
                                </div>

                                <select name="catid" class="form-select select2init translate"
                                    :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                    v-model="form.catid">
                                    <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                                    <template v-for="cat in categories">
                                        <option v-if="cat.is_project!=1 && cat.type==='vacance'" :value="cat.id">@{{ cat.property_type }}
                                        </option>
                                    </template>
                                </select>
                            </section>
                            @endif
                        @endif

                        <section class="item-section" id="loc-section">
                            <div class="section-heading">
                                <h1>{{ __('general.Localisation') }} <span style="color: red">*</span> :</h1>
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

                                        {{-- <div>
                                         <select class="form-select" id="" v-model="form.loccity"
                                            @change="changeCity()">
                                            <option :value="null">Sélectionnez une ville</option>
                                            <option v-for="c of cities" :value="c.id">@{{ c.name }}
                                            </option>
                                        </select>
                                    </div> --}}

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

                            <p style="font-size: 10px"><span style="color: red">*</span> : Champs obligatoires</p>

                        </section>

                        <section v-if="form.catid" class="item-section" id="features-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Caractéristiques') }} :</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div class="row" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

                                <div v-if="showField('rooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Pieces') }} :</label>
                                    <div class="features">
                                        <template v-if="!customRooms">
                                            <span v-for="p in pieces" @click="form.rooms==p?(form.rooms=null):(form.rooms=p)"
                                                class="selectable-item"
                                                :class="p == form.rooms ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customRooms=true;form.rooms=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.rooms" class="form-control"
                                                placeholder="{{ __('general.Pieces') }}" />
                                            <button class="btn btn-sm text-danger" @click="customRooms=false;form.rooms=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('bedrooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Chambres') }} :</label>
                                    <div class="features">
                                        <template v-if="!customBedrooms">
                                            <span v-for="p in pieces"
                                                @click="form.bedrooms==p?(form.bedrooms=null):(form.bedrooms=p)"
                                                class="selectable-item"
                                                :class="p == form.bedrooms ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customBedrooms=true;form.bedrooms=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.bedrooms" class="form-control"
                                                placeholder="{{ __('general.Chambres') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customBedrooms=false;form.bedrooms=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('bathrooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Salles de bain') }} :</label>
                                    <div class="features">
                                        <template v-if="!customWc">
                                            <span v-for="p in pieces" @click="form.wc==p?(form.wc=null):(form.wc=p)"
                                                class="selectable-item"
                                                :class="p == form.wc ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customWc=true;form.wc=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.wc" class="form-control"
                                                placeholder="{{ __('general.Salles de bain') }}" />
                                            <button class="btn btn-sm text-danger" @click="customWc=false;form.wc=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Étage') }} :</label>
                                    <div class="features">
                                        <template v-if="!customEtage">
                                            <span v-for="p in etages" @click="form.etage==p?(form.etage=null):(form.etage=p)"
                                                class="selectable-item"
                                                :class="p == form.etage ? 'selected' : ''">@{{ p!=0?p!=-1?p:'RDJ':'RDC' }}</span>
                                            <span class="selectable-item" @click="customEtage=true;form.etage=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.etage" class="form-control"
                                                placeholder="{{ __('general.Étage') }}" />
                                            <button class="btn btn-sm text-danger" @click="customEtage=false;form.etage=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage_total')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Les étages total de l\'immeuble') }} :</label>
                                    <div class="features">
                                        <template v-if="!customEtageTotal">
                                            <span v-for="p in pieces"
                                                @click="form.etage_total==p?(form.etage_total=null):(form.etage_total=p)"
                                                class="selectable-item"
                                                :class="p == form.etage_total ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customEtageTotal=true;form.etage_total=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.etage_total" class="form-control"
                                                placeholder="{{ __('general.Étage') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customEtageTotal=false;form.etage_total=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('contriction_date')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.L\'année de construction') }} :</label>
                                    <div class="features">
                                        <template v-if="!customContrictionDate">
                                            <span v-for="p in years"
                                                @click="form.contriction_date==p?(form.contriction_date=null):(form.contriction_date=p)"
                                                class="selectable-item"
                                                :class="p == form.contriction_date ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customContrictionDate=true;form.contriction_date=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.contriction_date" class="form-control"
                                                placeholder="{{ __('general.L\'année de construction') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customContrictionDate=false;form.contriction_date=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('Standing')" class="col-12 d-flex flex-column" v-if="standings.length>0">
                                    <label for="">{{ __('general.Standing') }} :</label>
                                    <div class="features">
                                        <span v-for="p in standings"
                                            @click="form.standing==p.id?(form.standing=null):(form.standing=p.id)"
                                            class="selectable-item"
                                            :class="p.id == form.standing ? 'selected' : ''">@{{ p.designation }}</span>
                                    </div>
                                </div>


                                <div class="centred"></div>

                                <div class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Autre') }} :</label>
                                    <div class="item-more-info">
                                        <div v-if="showField('climatise')" :class="form.climatise == true ? 'selected' : ''"
                                            @click="form.climatise=!form.climatise;" class="more-info-item">
                                            <i class="multilist-icons multilist-snowflake"></i>
                                            <span class="value">
                                                {{ __('general.Climatisé') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('jardin')" :class="form.jardin == true ? 'selected' : ''" @click="form.jardin=!form.jardin;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-tree"></i>
                                            <span class="value">
                                                {{ __('general.Jardin') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('piscine')" :class="form.piscine == true ? 'selected' : ''" @click="form.piscine=!form.piscine;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-pool"></i>
                                            <span class="value">
                                                {{ __('general.Piscine') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('parking')" :class="form.parking == true ? 'selected' : ''" @click="form.parking=!form.parking;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-parking"></i>
                                            <span class="value">
                                                {{ __('general.Parking') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('meuble')" :class="form.meuble == true ? 'selected' : ''" @click="form.meuble=!form.meuble;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-sofa"></i>
                                            <span class="value">
                                                {{ __('general.Meublé') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('terrace')" :class="form.terrace == true ? 'selected' : ''" @click="form.terrace=!form.terrace;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-terrasse"></i>
                                            <span class="value">
                                                {{ __('general.Terrasse') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('syndic')" :class="form.syndic == true ? 'selected' : ''" @click="form.syndic=!form.syndic;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-syndic"></i>
                                            <span class="value" style="text-align: center;">
                                                {{__('general.Syndic')}}
                                            </span>
                                        </div>
                                        <div v-if="showField('cave')" :class="form.cave == true ? 'selected' : ''" @click="form.cave=!form.cave;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-cave"></i>
                                            <span class="value">
                                                {{ __('general.Cave') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('ascenseur')" :class="form.ascenseur == true ? 'selected' : ''"
                                            @click="form.ascenseur=!form.ascenseur;" class="more-info-item">
                                            <i class="multilist-icons multilist-elevator"></i>
                                            <span class="value">
                                                {{ __('general.Ascenseur') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('securite')" :class="form.securite == true ? 'selected' : ''"
                                            @click="form.securite=!form.securite;" class="more-info-item">
                                            <i class="multilist-icons multilist-camera"></i>
                                            <span class="value">
                                                {{ __('general.Sécurité') }}
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

                        <section v-if="form.catid" class="item-section" id="media-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Media') }} :</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputRooms" class="col-sm-12 col-form-label">{{ __('general.Images') }} (png/jpeg)</label>
                                <small id="videoMaxSize" class="form-text text-muted"> {{ $imageSize }} MO  * {{ __('general.Taille max') }}
                                    </small>

                                <div class="col-sm-12">
                                    <upload-files-component v-model:files="form.images" type="images" :max="50"
                                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="true" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputRooms" class="col-sm-12 col-form-label">{{ __('general.Vidéos') }} (mp4)</label>
                                <small id="videoMaxSize" class="form-text text-muted"> {{ $videoSize }} MO * {{ __('general.Taille max') }}</small>


                                <div class="col-sm-12">
                                    <upload-files-component v-model:files="form.videos" type="videos" :max="50"
                                        :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                                </div>
                            </div>

                        </section>

                        <section v-if="form.catid" class="item-section" id="places-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Lieux à proximité') }}:</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div class="translate">
                                <button type="button" class="btn btn-multi" @click="AddPlaceModal">
                                    <i class="bi bi-plus me-1"></i> {{ __('general.Ajouter lieu') }}
                                </button>
                                <table id="nearbyPlaces">
                                    <tr v-for="place in form.nearbyPlaces">
                                        <td style="width:70%;">@{{ place.title }} <span class="distance">
                                                @{{ place.distance }}M</span></td>
                                        <td style="width:30%;">
                                            <i class="fas fa-edit table-action" @click="updatePlaceModal(place)"></i>
                                            <i class="fa-solid fa-trash table-action" @click="deletePlace(place.id)"></i>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </section>

                        <section v-if="form.catid" class="item-section" id="info-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Informations générales') }} :</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div>
                                <!-- General Form Elements -->
                                <div class="row mb-3 translate">
                                    <label for="inputSurface" class="col-sm-4 col-form-label">{{ __('general.Superficie') }}:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="surface" type="number" class="form-control translate"
                                                :class="errors.surface ? 'is-invalid' : ''" v-model="form.surface">
                                            <span class="input-group-text">{{ __('general.m²') }}</span>
                                            <div class="invalid-feedback">
                                                @{{ errors.surface?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputPrice" class="col-sm-4 col-form-label">{{__('general.Prix')}} <span style="color: red">*</span> :</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="price" type="number" class="form-control"
                                                :class="errors.price ? 'is-invalid' : ''" v-model="form.price">
                                            <select name="price_curr" class="inputtypeselect" v-model="form.price_curr">
                                                <option value="DHS" style="@if(Session::get('lang') === 'ar')  text-align:center;   @endif">{{ __('general.DHS') }}</option>
                                                <option value="EUR" style="@if(Session::get('lang') === 'ar')  text-align:center;   @endif">{{ __('general.EUR') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.price?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputPrice" class="col-sm-4 col-form-label">{{ __('general.prix/m²') }}:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="price_m" type="number" class="form-control"
                                                :class="errors.price_m ? 'is-invalid' : ''" v-model="form.price_m">
                                            <select name="price_curr" class="inputtypeselect" v-model="form.price_curr">
                                                <option value="DHS" style="@if(Session::get('lang') === 'fr')  text-align:center;   @endif">{{ __('general.DHS') }}</option>
                                                <option value="EUR" style="@if(Session::get('lang') === 'fr')  text-align:center;   @endif">{{ __('general.EUR') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.price?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">{{ __('general.Titre') }} <span style="color: red">*</span> :</label>
                                    <div class="col-sm-8">
                                        <input name="title" type="text" class="form-control"
                                            :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                        <div class="invalid-feedback">
                                            @{{ errors.title?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputRef" class="col-sm-4 col-form-label">{{ __('general.Référence') }}:</label>
                                    <div class="col-sm-8">
                                        <input name="ref" type="text" class="form-control"
                                            :class="errors.ref ? 'is-invalid' : ''" v-model="form.ref">
                                        <div class="invalid-feedback">
                                            @{{ errors.ref?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputDesc" class="col-sm-4 col-form-label">{{ __('general.déscription') }} <span style="color: red">*</span> :</label>
                                    <div class="col-sm-8">
                                        <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 200px"
                                            v-model="form.description"></textarea>

                                        <button type="button" class="btn btn-multi d-none" @click="generateDesc()" style="margin-top: 10px;margin-left: auto; @if(Session::get('lang') === 'ar')  float:left; @endif">
                                            <i class="bi bi-plus me-1"></i>
                                            {{ __('general.Générer description auto') }}
                                        </button>
                                        <div class="invalid-feedback">
                                            @{{ errors.description?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <p style="font-size: 10px"><span style="color: red">*</span> : Champs obligatoires</p>
                        </section>

                        <section v-if="form.catid&&form.user_id" class="item-section translate" id="places-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Informations de contact') }}:</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.TÉLÉPHONE') }} :</label>
                                    <div class="col-sm-8">
                                        <select name="phone" class="form-select select2init"
                                            :class="errors.phone ? 'is-invalid' : ''" id="phones-select" v-model="form.phone">
                                            <option v-for="phone in userphones" :value="phone.id">@{{ phone.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.phone?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Téléphone 2') }} :</label>
                                    <div class="col-sm-8">
                                        <select name="phone2" class="form-select select2init"
                                            :class="errors.phone2 ? 'is-invalid' : ''" id="phones-select" id="phones2-select"
                                            v-model="form.phone2">
                                            <option :value="null">{{ __('general.Numéro de téléphone') }}</option>
                                            <option v-for="phone in userphones" :value="phone.id">@{{ phone.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.phone2?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Numéro whatsapp') }} :</label>
                                    <div class="col-sm-8">
                                        <select name="wtsp" class="form-select select2init"
                                            :class="errors.dept2 ? 'is-invalid' : ''" id="wtsp-select" v-model="form.wtsp">
                                            <option :value="null">{{ __('general.Numéro whatsapp') }}</option>
                                            <option v-for="wtsp in userWtsps" :value="wtsp.id">@{{ wtsp.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.wtsp?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Email') }}:</label>
                                    <div class="col-sm-8">
                                        <select name="email" class="form-select select2init"
                                            :class="errors.email ? 'is-invalid' : ''" id="emails-select" v-model="form.email">
                                            <option v-for="email in userEmails" :value="email.id">@{{ email.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.email?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>

                        <section v-if="form.catid&&!form.user_id" class="item-section translate" id="places-section">
                            <div class="section-heading">
                                <h1>{{ __('general.Créer mon compte') }}:</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div class="login-form">
                                {{-- form-group firstname --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="firstname">{{ __('general.Prénom') }} :</label>
                                    <input type="text" class="form-control" :class="errors.firstname ? 'is-invalid' : ''"
                                        id="firstname" v-model="registerForm.firstname" placeholder="{{ __('general.Prénom') }}">
                                    <div class="invalid-feedback">
                                        @{{ errors.firstname?.join('<br/>') }}
                                    </div>
                                </div>

                                {{-- form-group lastname --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="lastname">{{ __('general.votre nom') }} :</label>
                                    <input type="text" class="form-control" :class="errors.lastname ? 'is-invalid' : ''"
                                        v-model="registerForm.lastname" placeholder="{{ __('general.votre nom') }}">
                                    <div class="invalid-feedback">
                                        @{{ errors.lastname?.join('<br/>') }}
                                    </div>
                                </div>
                                {{-- form-group username --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="username">{{ __('general.Nom d\'utilisateur') }} :</label>
                                    <input type="text" class="form-control" :class="errors.username ? 'is-invalid' : ''"
                                        v-model="registerForm.username" placeholder="{{ __('general.Nom d\'utilisateur') }} ">
                                    <div class="invalid-feedback">
                                        @{{ errors.username?.join('<br/>') }}
                                    </div>
                                </div>
                                {{-- form-group email --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="email">{{ __('general.Email') }} :</label>
                                    <input type="email" class="form-control" :class="errors.email ? 'is-invalid' : ''"
                                        v-model="registerForm.email" placeholder="{{ __('general.Email') }}">
                                    <div class="invalid-feedback">
                                        @{{ errors.email?.join('<br/>') }}
                                    </div>
                                </div>
                                {{-- form-group phone --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="phone">{{ __('general.Numéro de téléphone') }} :</label>
                                    <input type="phone" class="form-control" :class="errors.phone ? 'is-invalid' : ''"
                                        v-model="registerForm.phone" id="phone3" ref="phone3">
                                    <div class="invalid-feedback">
                                        @{{ errors.phone?.join('<br/>') }}
                                    </div>
                                </div>
                                {{-- form-group password --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="password">{{ __('general.Mot de passe') }} :</label>
                                    <input type="password" class="form-control" :class="errors.password ? 'is-invalid' : ''"
                                        v-model="registerForm.password" placeholder="{{ __('general.Mot de passe') }}">
                                    <div class="invalid-feedback">
                                        @{{ errors.password?.join('<br/>') }}
                                    </div>
                                </div>
                                {{-- form-group password2 --}}
                                <div class="form-group" style="padding: 10px;">
                                    <label for="password2">{{ __('general.Confirmez votre mot de passe') }} :</label>
                                    <input type="password" class="form-control" :class="errors.password2 ? 'is-invalid' : ''"
                                        v-model="registerForm.password2" placeholder="{{ __('general.Confirmez votre mot de passe') }}"
                                        placeholder="Enter password">
                                    <div class="invalid-feedback">
                                        @{{ errors.password2?.join('<br/>') }}
                                    </div>
                                </div>
                            </div>


                        </section>

                        <div v-if="form.catid" class="card-body" style="text-align: center; padding-top: 20px;">
                            <button type="submit" class="btn btn-save" @click="save0('30')" :disabled="loading">
                                {{ __('general.Publier') }}
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">{{ __('general.Loading...') }}</span>
                                </div>
                            </button>
                            <button type="submit" class="btn btn-secondary" @click="save0('20')" :disabled="loading">
                                {{ __('general.Enregister le Brouillon') }}
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">{{ __('general.Loading...') }}</span>
                                </div>
                            </button>

                            <button type="submit" class="btn btn-danger" @click="deleteAd" :disabled="loading">
                                {{ __('general.supprimer') }}
                            </button>
                        </div>

                    </div>

                    <!-- add place Modal -->
                    <div class="modal fade modal-close" id="addPlaceModal" data-id="addPlaceModal">
                        <div class="modal-dialog modal-lg">
                            <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{__('general.Ajouter lieu')}}</h5>
                                    <button type="button" class="btn-close modal-close"
                                        data-id="addPlaceModal"></button>
                                </div>
                                <div class="modal-body"
                                    style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                    <div class="row mb-3">
                                        <label for="inputTitle" class="col-sm-4 col-form-label">{{__('general.Nom de lieu')}}</label>
                                        <div class="col-sm-8">
                                            <input id="place-title" name="place_title" type="text"
                                                class="form-control" v-model="placesForm.title">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputLoc" class="col-sm-4 col-form-label">{{__('general.Distance')}}</label>
                                        <div class="col-sm-8 ">
                                            <div class="input-group ">
                                                <input name="place_distance" type="number" class="form-control"
                                                    v-model="placesForm.distance">
                                                <span class="input-group-text">m</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">{{__('general.Type de lieu')}}</label>
                                        <div class="col-sm-8">
                                            <select id="place-types" name="place_types" class="form-select"
                                                v-model="placesForm.type">
                                                <option :value="null">{{__('general.Choisir un type')}}</option>
                                                <option v-for="type in place_types" :value="type.id">
                                                    @{{ type.designation }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary modal-close"
                                        data-id="addPlaceModal">{{__('general.close')}}</button>
                                    <button id="addPlace" type="submit" class="btn btn-primary" @click="addPlace">
                                        {{__('general.Ajouter')}}
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
                                    <h5 class="modal-title">{{__('general.Nom de lieu')}}</h5>
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
                                        <label for="inputLoc" class="col-sm-4 col-form-label">{{__('general.Distance')}}</label>
                                        <div class="col-sm-8 ">
                                            <div class="input-group ">
                                                <input name="place_distance" type="number" class="form-control"
                                                    v-model="placesFormUpdate.distance">
                                                <span class="input-group-text">m</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">{{__('general.type de lieu')}}</label>
                                        <div class="col-sm-8">
                                            <select id="place-types" name="place_types" class="form-select"
                                                v-model="placesFormUpdate.type">
                                                <option :value="null">{{__('general.Choisir un type')}}</option>
                                                <option v-for="type in place_types" :value="type.id">
                                                    @{{ type.designation }}</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary modal-close"
                                        data-id="updatePlaceModal">{{__('general.Close')}}</button>
                                    <button id="updatePlace" type="submit" class="btn btn-primary"
                                        @click="updatePlace">{{__('general.Modifier')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End UPDATE place Modal-->
                </section>

            </div>
        </popup-component>
    </div>

    <div id="addDispo-app" class="d-none">
        <popup-component :title="`Ajouter un disponibilité`" v-model:display="display">
            <div class="container-md" style="max-width: 850px;padding-bottom: 100px;">
                <section class="add-section " @if(Session::get('lang') === 'ar') dir="rtl" @endif>

                    <div class="">

                        <section v-if="form.catid" class="item-section" id="features-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Caractéristiques') }} </h1>
                                <div class="heading-underline"></div>
                            </div>

                            @if(Session::get('lang') === 'ar')
                            <div class="row" dir="rtl">

                                <div v-if="showField('rooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Pieces') }} </label>
                                    <div class="features">
                                        <template v-if="!customRooms">
                                            <span v-for="p in pieces" @click="form.rooms==p?(form.rooms=null):(form.rooms=p)"
                                                class="selectable-item"
                                                :class="p == form.rooms ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customRooms=true;form.rooms=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.rooms" class="form-control"
                                                placeholder="{{ __('general.Pieces') }}" />
                                            <button class="btn btn-sm text-danger" @click="customRooms=false;form.rooms=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('bedrooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Chambres') }} </label>
                                    <div class="features">
                                        <template v-if="!customBedrooms">
                                            <span v-for="p in pieces"
                                                @click="form.bedrooms==p?(form.bedrooms=null):(form.bedrooms=p)"
                                                class="selectable-item"
                                                :class="p == form.bedrooms ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customBedrooms=true;form.bedrooms=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.bedrooms" class="form-control"
                                                placeholder="{{ __('general.Chambres') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customBedrooms=false;form.bedrooms=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('bathrooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Salles de bain') }} :</label>
                                    <div class="features">
                                        <template v-if="!customWc">
                                            <span v-for="p in pieces" @click="form.wc==p?(form.wc=null):(form.wc=p)"
                                                class="selectable-item"
                                                :class="p == form.wc ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customWc=true;form.wc=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.wc" class="form-control"
                                                placeholder="{{ __('general.Salles de bain') }}" />
                                            <button class="btn btn-sm text-danger" @click="customWc=false;form.wc=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Étage') }} </label>
                                    <div class="features">
                                        <template v-if="!customEtage">
                                            <span v-for="p in etages" @click="form.etage==p?(form.etage=null):(form.etage=p)"
                                                class="selectable-item"
                                                :class="p == form.etage ? 'selected' : ''">@{{ p!=0?p!=-1?p:'RDJ':'RDC' }}</span>
                                            <span class="selectable-item" @click="customEtage=true;form.etage=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.etage" class="form-control"
                                                placeholder="{{ __('general.Étage') }}" />
                                            <button class="btn btn-sm text-danger" @click="customEtage=false;form.etage=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage_total')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Les étages total de l\'immeuble') }} :</label>
                                    <div class="features">
                                        <template v-if="!customEtageTotal">
                                            <span v-for="p in pieces"
                                                @click="form.etage_total==p?(form.etage_total=null):(form.etage_total=p)"
                                                class="selectable-item"
                                                :class="p == form.etage_total ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customEtageTotal=true;form.etage_total=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.etage_total" class="form-control"
                                                placeholder="{{ __('general.Étage') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customEtageTotal=false;form.etage_total=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('contriction_date')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.L\'année de construction') }} </label>
                                    <div class="features">
                                        <template v-if="!customContrictionDate">
                                            <span v-for="p in years"
                                                @click="form.contriction_date==p?(form.contriction_date=null):(form.contriction_date=p)"
                                                class="selectable-item"
                                                :class="p == form.contriction_date ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item"
                                                @click="customContrictionDate=true;form.contriction_date=null;">{{ __('general.Autre') }}</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.contriction_date" class="form-control"
                                                placeholder="{{ __('general.L\'année de construction') }}" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customContrictionDate=false;form.contriction_date=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('Standing')" class="col-12 d-flex flex-column" v-if="standings.length>0">
                                    <label for="">{{ __('general.Standing') }} :</label>
                                    <div class="features">
                                        <span v-for="p in standings"
                                            @click="form.standing==p.id?(form.standing=null):(form.standing=p.id)"
                                            class="selectable-item"
                                            :class="p.id == form.standing ? 'selected' : ''">@{{ p.designation }}</span>
                                    </div>
                                </div>


                                <div class="centred"></div>

                                <div class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Autre') }} </label>
                                    <div class="item-more-info">
                                        <div v-if="showField('climatise')" :class="form.climatise == true ? 'selected' : ''"
                                            @click="form.climatise=!form.climatise;" class="more-info-item">
                                            <i class="multilist-icons multilist-snowflake"></i>
                                            <span class="value">
                                                {{ __('general.Climatisé') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('jardin')" :class="form.jardin == true ? 'selected' : ''" @click="form.jardin=!form.jardin;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-tree"></i>
                                            <span class="value">
                                                {{ __('general.Jardin') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('piscine')" :class="form.piscine == true ? 'selected' : ''" @click="form.piscine=!form.piscine;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-pool"></i>
                                            <span class="value">
                                                {{ __('general.Piscine') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('parking')" :class="form.parking == true ? 'selected' : ''" @click="form.parking=!form.parking;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-parking"></i>
                                            <span class="value">
                                                {{ __('general.Parking') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('meuble')" :class="form.meuble == true ? 'selected' : ''" @click="form.meuble=!form.meuble;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-sofa"></i>
                                            <span class="value">
                                                {{ __('general.Meublé') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('terrace')" :class="form.terrace == true ? 'selected' : ''" @click="form.terrace=!form.terrace;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-terrasse"></i>
                                            <span class="value">
                                                {{ __('general.Terrasse') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('syndic')" :class="form.syndic == true ? 'selected' : ''" @click="form.syndic=!form.syndic;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-syndic"></i>
                                            <span class="value" style="text-align: center;">
                                                {{__('general.Syndic')}}
                                            </span>
                                        </div>
                                        <div v-if="showField('cave')" :class="form.cave == true ? 'selected' : ''" @click="form.cave=!form.cave;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-cave"></i>
                                            <span class="value">
                                                {{ __('general.Cave') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('ascenseur')" :class="form.ascenseur == true ? 'selected' : ''"
                                            @click="form.ascenseur=!form.ascenseur;" class="more-info-item">
                                            <i class="multilist-icons multilist-elevator"></i>
                                            <span class="value">
                                                {{ __('general.Ascenseur') }}
                                            </span>
                                        </div>
                                        <div v-if="showField('securite')" :class="form.securite == true ? 'selected' : ''"
                                            @click="form.securite=!form.securite;" class="more-info-item">
                                            <i class="multilist-icons multilist-camera"></i>
                                            <span class="value">
                                                {{ __('general.Sécurité') }}
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
                            @else
                            <div class="row">

                                <div v-if="showField('rooms')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __('general.Pieces') }}</label>
                                    <div class="features">
                                        <template v-if="!customRooms">
                                            <span v-for="p in pieces" @click="form.rooms==p?(form.rooms=null):(form.rooms=p)"
                                                class="selectable-item"
                                                :class="p == form.rooms ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customRooms=true;form.rooms=null;">Autre</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.rooms" class="form-control"
                                                placeholder="{{ __('general.Pieces') }}" />
                                            <button class="btn btn-sm text-danger" @click="customRooms=false;form.rooms=null;">
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
                                    <label for="">Salles de bain </label>
                                    <div class="features">
                                        <template v-if="!customWc">
                                            <span v-for="p in pieces" @click="form.wc==p?(form.wc=null):(form.wc=p)"
                                                class="selectable-item"
                                                :class="p == form.wc ? 'selected' : ''">@{{ p }}</span>
                                            <span class="selectable-item" @click="customWc=true;form.wc=null;">Autre</span>
                                        </template>
                                        <div class="other" v-else>
                                            <input type="number" v-model="form.wc" class="form-control"
                                                placeholder="Salles de bain" />
                                            <button class="btn btn-sm text-danger" @click="customWc=false;form.wc=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage')" class="col-12 d-flex flex-column">
                                    <label for="">Étage </label>
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
                                            <button class="btn btn-sm text-danger" @click="customEtage=false;form.etage=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('etage_total')" class="col-12 d-flex flex-column">
                                    <label for="">{{ __("general.Les étages total de l'immeuble") }} </label>
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
                                    <label for="">{{ __("general.L'année de construction") }} </label>
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
                                            <input type="number" v-model="form.contriction_date" class="form-control"
                                                placeholder="Année" />
                                            <button class="btn btn-sm text-danger"
                                                @click="customContrictionDate=false;form.contriction_date=null;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="showField('Standing')" class="col-12 d-flex flex-column" v-if="standings.length>0">
                                    <label for="">Standing :</label>
                                    <div class="features">
                                        <span v-for="p in standings"
                                            @click="form.standing==p.id?(form.standing=null):(form.standing=p.id)"
                                            class="selectable-item"
                                            :class="p.id == form.standing ? 'selected' : ''">@{{ p.designation }}</span>
                                    </div>
                                </div>


                                <div class="centred"></div>

                                <div class="col-12 d-flex flex-column">
                                    <label for="">Autre </label>
                                    <div class="item-more-info">
                                        <div v-if="showField('climatise')" :class="form.climatise == true ? 'selected' : ''"
                                            @click="form.climatise=!form.climatise;" class="more-info-item">
                                            <i class="multilist-icons multilist-snowflake"></i>
                                            <span class="value">
                                                Climatisé
                                            </span>
                                        </div>
                                        <div v-if="showField('jardin')" :class="form.jardin == true ? 'selected' : ''" @click="form.jardin=!form.jardin;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-tree"></i>
                                            <span class="value">
                                                Jardin
                                            </span>
                                        </div>
                                        <div v-if="showField('piscine')" :class="form.piscine == true ? 'selected' : ''" @click="form.piscine=!form.piscine;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-pool"></i>
                                            <span class="value">
                                                Piscine
                                            </span>
                                        </div>
                                        <div v-if="showField('parking')" :class="form.parking == true ? 'selected' : ''" @click="form.parking=!form.parking;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-parking"></i>
                                            <span class="value">
                                                Parking
                                            </span>
                                        </div>
                                        <div v-if="showField('meuble')" :class="form.meuble == true ? 'selected' : ''" @click="form.meuble=!form.meuble;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-sofa"></i>
                                            <span class="value">
                                                Meublé
                                            </span>
                                        </div>
                                        <div v-if="showField('terrace')" :class="form.terrace == true ? 'selected' : ''" @click="form.terrace=!form.terrace;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-terrasse"></i>
                                            <span class="value">
                                                Terrasse
                                            </span>
                                        </div>
                                        <div v-if="showField('syndic')" :class="form.syndic == true ? 'selected' : ''" @click="form.syndic=!form.syndic;"
                                            class="more-info-item">
                                            <i class="multilist-icons multilist-syndic"></i>
                                            <span class="value">
                                                Syndic
                                            </span>
                                        </div>
                                        <div v-if="showField('cave')" :class="form.cave == true ? 'selected' : ''" @click="form.cave=!form.cave;"
                                            class="more-info-item">
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
                            @endif

                        </section>

                        <section v-if="form.catid" class="item-section" id="media-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Media') }} </h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputRooms" class="col-sm-12 col-form-label">{{ __('general.Images') }} (png/jpeg)</label>
                                <small @if (Session('lang') == 'ar')
                                    dir="rtl"
                                @else
                                    dir="ltr"
                                @endif id="videoMaxSize" class="form-text text-muted"> {{ $imageSize }} {{ __('general.MO') }}  * {{ __('general.Taille max') }}
                                    </small>

                                <div class="col-sm-12">
                                    <upload-files-component v-model:files="form.images" type="images" :max="50"
                                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="true" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputRooms" class="col-sm-12 col-form-label">{{ __('general.Vidéos') }} (mp4)</label>
                                <small @if (Session('lang') == 'ar')
                                dir="rtl"
                                @else
                                    dir="ltr"
                                @endif id="videoMaxSize" class="form-text text-muted"> {{ $videoSize }} {{ __('general.MO') }} * {{ __('general.Taille max') }}</small>


                                <div class="col-sm-12">
                                    <upload-files-component v-model:files="form.videos" type="videos" :max="50"
                                        :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                                </div>
                            </div>

                        </section>

                        <section v-if="form.catid" class="item-section" id="places-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Informations générales') }} </h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div>
                                <!-- General Form Elements -->
                                <div class="row mb-3 translate">
                                    <label for="inputSurface" class="col-sm-4 col-form-label">{{ __('general.Superficie') }}:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="surface" type="number" class="form-control translate"
                                                :class="errors.surface ? 'is-invalid' : ''" v-model="form.surface">
                                            <span class="input-group-text">{{ __('general.m²') }}</span>
                                            <div class="invalid-feedback">
                                                @{{ errors.surface?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputPrice" class="col-sm-4 col-form-label">{{__('general.Prix')}}:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="price" type="number" class="form-control"
                                                :class="errors.price ? 'is-invalid' : ''" v-model="form.price">
                                            <select name="price_curr" class="inputtypeselect" v-model="form.price_curr">
                                                <option value="DHS" style="@if(Session::get('lang') === 'ar')  text-align:center;   @endif">{{ __('general.DHS') }}</option>
                                                <option value="EUR" style="@if(Session::get('lang') === 'ar')  text-align:center;   @endif">{{ __('general.EUR') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.price?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputPrice" class="col-sm-4 col-form-label">{{ __('general.prix/m²') }}:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="price_m" type="number" class="form-control"
                                                :class="errors.price_m ? 'is-invalid' : ''" v-model="form.price_m">
                                            <select name="price_curr" class="inputtypeselect" v-model="form.price_curr">
                                                <option value="DHS" style="@if(Session::get('lang') === 'fr')  text-align:center;   @endif">{{ __('general.DHS') }}</option>
                                                <option value="EUR" style="@if(Session::get('lang') === 'fr')  text-align:center;   @endif">{{ __('general.EUR') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.price?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">{{ __('general.Titre') }}:</label>
                                    <div class="col-sm-8">
                                        <input name="title" type="text" class="form-control"
                                            :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                        <div class="invalid-feedback">
                                            @{{ errors.title?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputRef" class="col-sm-4 col-form-label">{{ __('general.Référence') }}:</label>
                                    <div class="col-sm-8">
                                        <input name="ref" type="text" class="form-control"
                                            :class="errors.ref ? 'is-invalid' : ''" v-model="form.ref">
                                        <div class="invalid-feedback">
                                            @{{ errors.ref?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3 translate">
                                    <label for="inputDesc" class="col-sm-4 col-form-label">{{ __('general.déscription') }}:</label>
                                    <div class="col-sm-8">
                                        <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 200px"
                                            v-model="form.description"></textarea>

                                        {{-- <button type="button" class="btn btn-multi" @click="generateDesc()" style="margin-top: 10px;margin-left: auto; @if(Session::get('lang') === 'ar')  float:left; @endif">
                                            <i class="bi bi-plus me-1"></i>
                                            {{ __('general.Générer description auto') }}
                                        </button>--}}
                                        <div class="invalid-feedback">
                                            @{{ errors.description?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>

                        <section v-if="form.catid&&form.user_id" class="item-section translate mb-3" id="places-section">
                            <div class="section-heading translate">
                                <h1>{{ __('general.Informations de contact') }}</h1>
                                <div class="heading-underline"></div>
                            </div>

                            <div>
                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.TÉLÉPHONE') }} :</label>
                                    <div class="col-sm-8">
                                        <select name="phone" class="form-select select2init"
                                            :class="errors.phone ? 'is-invalid' : ''" id="phones-select" v-model="form.phone">
                                            <option v-for="phone in userphones" :value="phone.id">@{{ phone.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.phone?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Téléphone 2') }} :</label>
                                    <div class="col-sm-8">
                                        <select name="phone2" class="form-select select2init"
                                            :class="errors.phone2 ? 'is-invalid' : ''" id="phones-select" id="phones2-select"
                                            v-model="form.phone2">
                                            <option :value="null">{{ __('general.Numéro de téléphone') }}</option>
                                            <option v-for="phone in userphones" :value="phone.id">@{{ phone.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.phone2?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Numéro whatsapp') }} </label>
                                    <div class="col-sm-8">
                                        <select name="wtsp" class="form-select select2init"
                                            :class="errors.dept2 ? 'is-invalid' : ''" id="wtsp-select" v-model="form.wtsp">
                                            <option :value="null">{{ __('general.Numéro whatsapp') }}</option>
                                            <option v-for="wtsp in userWtsps" :value="wtsp.id">@{{ wtsp.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.wtsp?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">{{ __('general.Email') }}</label>
                                    <div class="col-sm-8">
                                        <select name="email" class="form-select select2init"
                                            :class="errors.email ? 'is-invalid' : ''" id="emails-select" v-model="form.email">
                                            <option v-for="email in userEmails" :value="email.id">@{{ email.value }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.email?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>

                        <div v-if="form.catid" class="card-body mb-5" style="text-align: center; padding-top: 20px;">
                            <button type="submit" class="btn btn-save" @click="save0('30')" :disabled="loading">
                                {{ __('general.Publier') }}
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">{{ __('general.Loading...') }}</span>
                                </div>
                            </button>
                            <button type="submit" class="btn btn-secondary" @click="save0('20')" :disabled="loading">
                                {{ __('general.Enregister le Brouillon') }}
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">{{ __('general.Loading...') }}</span>
                                </div>
                            </button>
                        </div>

                    </div>

                </section>
            </div>
        </popup-component>
    </div>


    <script>
        var lastScrollTop = 0;
        const userType = '{{ Auth()->user()->usertype }}';
        let myadsApp = createApp({
            data() {
                return {
                    status_arr: status_arr,
                    loader: false,
                    loading: false,
                    dispoLoader: false,
                    searchTotal: 0,
                    total: 0,
                    ads: [],
                    projects: [],
                    dispos: [],
                    sort: null,
                    searchItems: {},
                    Object: Object,
                    filters: {
                        search: '',
                        from: 0,
                        count: 20,
                        status: null,
                    }
                }
            },
            computed: {},
            mounted() {
                multilistPopup();
                //loadData
                if (userType != 3) this.loadData();
                else this.loadPData();
                var search = window.location.search.split('?')[1]?.split('&');
                this.searchItems = {};
                if (search)
                    for (const s of search) {
                        if (s.split('=')[1]) {

                            if (s.split('=')[0] == "search") {
                                this.filters.search = decodeURI(s.split('=')[1]);
                            }

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
                            } else this.searchItems[s.split('=')[0]] = s.split('=')[1];
                        }
                    }
                //infinite scroll
                document.getElementById('main-scroll').onscroll = () => {
                    let id_down = false;
                    let scale = 500;
                    const thiselem = document.getElementById('main-scroll');
                    if (document.documentElement.clientWidth <= 767) {
                        scale = 700;
                    }
                    if (thiselem.scrollTop > lastScrollTop) id_down = true;
                    lastScrollTop = thiselem.scrollTop;
                    let bottomOfWindow = thiselem.scrollTop + thiselem.clientHeight >
                        thiselem.scrollHeight - scale;
                    if (id_down && bottomOfWindow) {
                        if (userType != 3) {
                            console.log(0);
                            if (this.loader == false && this.searchTotal > this.ads.length) {
                                this.filters.from += this.filters.count;
                                console.log(1);
                                this.loadData();
                            }
                        }
                        else {
                            if (this.loader == false && this.searchTotal > this.projects.length) {
                                this.filters.from += this.filters.count;
                                this.loadPData();
                            }
                        }
                    }
                    /*let id_down = false;
                    if (window.pageYOffset > lastScrollTop) id_down = true;
                    lastScrollTop = window.pageYOffset;
                    let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight > document
                        .documentElement.offsetHeight - 100;
                    if (bottomOfWindow) {
                        if (userType != 3) {

                            if (id_down && this.loader == false && this.searchTotal > this.ads.length) {
                                this.filters.from += this.filters.count;
                                if (userType != 3) this.loadData();
                                else this.loadPData();
                            }
                        } else {
                            if (id_down && this.loader == false && this.searchTotal > this.projects.length) {
                                this.filters.from += this.filters.count;
                                if (userType != 3) this.loadData();
                                else this.loadPData();
                            }
                        }
                    }*/
                };
            },
            components: {
                'Slider': Slider,
                'MultilistFilterComponent': MultilistFilterComponent
            },
            methods: {
                searchInput() {
                    // redirect to the listing page
                    if (multilistType == "multilist") window.location.href = '/myitems?search='+this.filters.search;
                    else window.location.href = '/' + multilistType + '/myitems?search='+this.filters.search;
                },
                changeSort(){
                    this.from = 0;
                    this.list = [];
                    this.loadListing();
                },
                openFilter(elem = null) {
                    this.showFilter(elem);
                },
                clearFilter() {
                    window.location.href = '/myitems';
                },
                submitfilter(data) {
                    let listing_url = '/' + addObjToUrl(data.filter, 'myitems');

                    localStorage.setItem('latestsearch',listing_url);

                    // redirect to the listing page
                    window.location.href = listing_url.replace(/\/\//g, '/');
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
                loadData() {
                    console.log('test');
                    this.loader = true;
                    let urlParams = '';
                    const constUrl = `from=${this.filters.from}&count=${this.filters.count}` + (
                        multilistTypeObj.id ? `&univer=${multilistTypeObj.id}` : '') + (
                        this.sort ? `&sort=${this.sort}` : '') + (this.filters.status ? `&status=${this.filters.status}` : '');
                    if (window.location.search) urlParams = window.location.search.replace('?','&') + '&' + constUrl;
                    else urlParams = '&' + constUrl;
                    axios.get(`/api/v2/myAds?id={{ Auth::user()->id }}` + urlParams)
                        .then((response) => {
                            this.loader = false;
                            this.ads = this.ads.concat(response.data.data.data);
                            this.searchTotal = response.data.data.searchTotal;
                            this.total = response.data.data.total;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                loadPData() {
                    this.loader = true;
                    let urlParams = '';
                    const constUrl = `from=${this.filters.from}&count=${this.filters.count}&search=${this.filters.search}` + (
                        multilistTypeObj.id ? `&univer=${multilistTypeObj.id}` : '') + (
                        this.sort ? `&sort=${this.sort}` : '') + (this.filters.status ? `&status=${this.filters.status}` : '');
                    if (window.location.search) urlParams = window.location.search.replace('?','&') + '&' + constUrl;
                    else urlParams = '&' + constUrl;
                    axios.get(`/api/v2/profileProjects?id={{ Auth::user()->id }}` + urlParams)
                        .then((response) => {
                            this.loader = false;
                            this.projects = this.projects.concat(response.data.data.data);
                            this.searchTotal = response.data.data.searchTotal;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                showDispos(obj) {
                    if (obj.showDispos) obj.showDispos = false;
                    else {
                        obj.showDispos = true;
                        this.dispoLoader = true;
                        axios.get(`/api/v2/profileProjectDispos?id=${obj.id}`)
                            .then((response) => {
                                this.dispoLoader = false;
                                this.dispos = response.data.data.data;
                                setTimeout(() => {
                                    slide('slide-dispos', 5000);
                                }, 100);
                            })
                            .catch(error => {
                                this.dispoLoader = false;
                                console.log(error);
                            });
                    }
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
                displayStatus(val) {
                    for (const s of status_arr) {
                        if (s.val == val) return s.desc;
                    }
                    return '';
                },
                toItemPage(e, l) {
                    if (e.target.classList.contains('btn') || e.target.parentElement.classList.contains('btn')) {
                        return;
                    }
                    //window.location.href = '/item/'+l.id+'/'+l.title;
                    window.open((multilistType!="multilist"?'/'+multilistType:'') + '/item/' + l.id, '_blank');
                },
                search() {
                    this.ads = [];
                    this.filters.from = 0;
                    this.filters.count = 20;
                    if (userType != 3) this.loadData();
                    else this.loadPData();
                },
                boost(ad_id) {
                    boostAdApp.form.id = ad_id;
                    boostAdApp.form.option = null;
                    boostAdApp.getAdOption();
                    boostAdApp.getUserCoins();
                    boostAdApp.display = true;
                },
                updateAd(ad) {
                    console.log(ad);
                    updateAdApp.type = ad.type;
                    updateAdApp.display = true;
                    updateAdApp.loadAd(ad.id);
                },
                addDispoAd(ad) {
                    addDispoApp.form.catid = ad.catid;
                    addDispoApp.form.loccity = ad.loccity;
                    addDispoApp.form.locdept = ad.locdept;
                    addDispoApp.form.locdept2 = ad.locdept2;
                    addDispoApp.form.lat = ad.loclat;
                    addDispoApp.form.long = ad.loclng;
                    addDispoApp.form.parent_project = ad.id;
                    addDispoApp.display = true;
                },
                deleteAd(ad_id) {
                    swal("Voulez-vous vraiment supprimer cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val === true) {
                            var config = {
                                method: 'post',
                                data: {
                                    id: ad_id
                                },
                                url: `/api/v2/deleteAd`
                            };
                            axios(config)
                                .then((response) => {
                                    this.loading = false;
                                    if (response.data.success == true) {
                                        for (let i = 0; i < this.ads.length; i++) {
                                            if (this.ads[i].id == response.data.data) {
                                                this.ads[i].status = '70';
                                            }
                                        }
                                        displayToast("L'annonce a été supprimée avec succès",
                                            '#0f5132');
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
            }
        }).mount('#myads-app');
        document.querySelector('#myads-app').classList.remove("d-none");
        //BOOSTER ADS ------------------------------------------------------------------
        let boostAdApp = Vue.createApp({
            data() {
                return {
                    display: false,
                    loader: false,
                    globalloader: false,
                    error: '',
                    options: [],
                    coins:0,
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
                                console.log('coins',this.coins);
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
                                swal("Votre annonce a été boostée avec succès", "", "success");
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
        document.querySelector('#booster-app').classList.remove("d-none");
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);
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
                        groundfloor: false,
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
                        price_m: null,
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
                        groundfloor: false,
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
                        price_m: null,
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
                                console.log("locdept",this.form.locdept);
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
                                    this.form.price_m = data.data.ad.price_surface;
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
                    axios.get('/api/v2/getUserContacts?user_id={{ Auth::user()->id }}')
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
                                document.querySelector('#btn-locate-cancel').classList.remove('d-none');
                                document.querySelector('#btn-locate').classList.add('d-none');
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
                                    document.querySelector('#btn-locate-cancel').classList.remove('d-none');
                                    document.querySelector('#btn-locate').classList.add('d-none');
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
                        input.checked = true;
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
                                for (let i = 0; i < myadsApp.ads.length; i++) {
                                    if (myadsApp.ads[i].id == response.data.data.id) {
                                        myadsApp.ads[i] = response.data.data;
                                    }
                                }
                                this.display = false;
                                displayToast("L'annonce a été modifiée avec succès", '#0f5132');
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

        const id = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        let addDispoApp = createApp({
            data() {
                return {
                    display: false,
                    id: id,
                    loading: false,
                    descLoading: false,
                    errors: {},
                    currlang:'{{Session::get("lang")}}',
                types: [{fr:'vente',ar:'شراء',val:'vente'},{fr:'location',ar:'كراء',val:'location'},/*{fr:'vacance',ar:'سفر',val:'vacance'}*/],
                    type: 'vente',
                    categories: [],
                    cities: [],
                    neighborhood: [
                    {
                        id: null,
                        name: "Sélectionnez un quartier"
                    }],
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
                        surface2: null,
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
                        user_id: null,
                        is_child: true,
                        parent_project: null,
                    },
                    registerForm: {
                        username: '',
                        email: '',
                        phone: '',
                        firstname: '',
                        lastname: '',
                        password: '',
                        password2: '',
                    }
                }
            },
            computed: {},

            mounted() {
                this.loadData();

               /*  var input = window.phone3;
                console.log('input',input)
                window.intlTelInput(input, {
                allowDropdown:true,
                initialCountry:'MA',
                excludeCountries:['EH'],
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js',
                preferredCountries : ['MA','FR','BE','UK','US','AE','CA','NL','ES','DE','IT','GB','CH','CI','SN','DZ','MR','TN','PT','TR','SA','SE','GA','LU','QA','IN','NO','GN','CG','ML','EG','IL','IE','RO','RE','CM','DK','HU'],

                });

                var iti = window.intlTelInputGlobals.getInstance(input);

                input.addEventListener('input', function() {
                    var fullNumber = iti.getNumber();
                    document.getElementById('phone3').value = fullNumber;
                }); */

            },
            components: {
                "uploadFilesComponent": uploadFilesComponent,
                "MlSelect": MlSelect,
                'PopupComponent': PopupComponent,
            },
            watch: {
                form: {
                    handler(newval) {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('form.' + key, (val, oldVal) => {
                                delete this.errors[key];
                            });
                        }
                    },
                    deep: true
                },
                registerForm: {
                    handler(newval) {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('registerForm.' + key, (val, oldVal) => {
                                delete this.errors[key];
                            });
                        }
                    },

                    deep: true

                }
            },
            methods: {
                loadData() {
                    axios.get('/api/v2/multilistfilterfrom')
                        .then(response => {
                            let data = response.data.data;

                            this.categories = data.categories;
                            this.cities = [{
                                id: null,
                                name: "{{ __('general.select') }}"
                            }].concat(data.cities);
                            this.standings = data.standings;
                            this.place_types = data.place_types;

                        })
                        .catch(error => {
                            console.log(error);
                        });
                    if (id) {
                        this.form.user_id = id;
                        axios.get('/api/v2/getUserContacts?user_id=' + this.form.user_id)
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
                    }
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
                save0(status) {
                    this.save(status);
                },
                save(status) {

                    this.loading = true;
                    this.errors = {};
                    this.error = '';
                    this.form.status = status;

                    //if(!this.validateForm()){ this.loading = false; return; }

                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/addAd`
                    };
                    axios(config)
                        .then((response) => {
                            this.loading = false;
                            console.log(response.data);
                            if (response.data.success == true) {
                                swal("Annonce ajoutée!", "", "success").then(() => {
                                    window.location.href = '/item/' + response.data.data;
                                });
                                setTimeout(() => {
                                    window.location.href = '/item/' + response.data.data;
                                }, 5000);
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else {
                                this.errorText = error.response.data.error;
                                displayToast(error, '#842029');
                            }
                        });
                },
                /* validateForm(){
                    this.errors={};
                    let r = true;
                    return r;
                }, */
            }
        }).mount('#addDispo-app');
        document.querySelector('#addDispo-app').classList.remove("d-none");
    </script>
@endsection
@section('custom_foot1')
@endsection
