@extends('v2.layouts.default')

@section('title', 'Déposer une annonce')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/add.styles.css') }}">

    <script src="/assets/vendor/jquery.min.js"></script>
    <script src='/js/script.js'></script>

    <script src="{{ asset('js/uploadFiles.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>


    <style>

        .value {

            text-align:center;
        }
    </style>

@endsection

@section('content')

    <div class="container-md" style="max-width: 850px;">

        <section id="add-section" class="add-section " @if(Session::get('lang') === 'ar') dir="rtl" @endif>

            <div class="section-title page-title">{{ __('general.Nouveau projet') }}</div>

            <div class="">

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
                            <option v-if="cat.is_project==1 && cat.type==='location'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">
                                @{{ cat.property_type_ar }}
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
                        <h1>{{ __('general.Catégorie') }}</h1>
                        <div class="heading-underline"></div>
                    </div>

                    <select name="catid" class="form-select select2init translate"
                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                        v-model="form.catid">
                        <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                        <template v-for="cat in categories">
                            <option v-if="cat.is_project==1 && cat.type==='vente'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">@{{ cat.property_type_ar }}
                            </option>



                        </template>
                    </select>

                </section>

                <section v-if="type === 'vacance'" class="item-section translate" id="category-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Catégorie') }} </h1>
                        <div class="heading-underline"></div>
                    </div>

                    <select name="catid" class="form-select select2init translate"
                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                        v-model="form.catid">
                        <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                        <template v-for="cat in categories">
                            <option v-if="cat.is_project==1 && cat.type==='vacance'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">@{{ cat.property_type_ar }}
                            </option>



                        </template>
                    </select>
                </section>

                @else
                <section v-if="type === 'location'" class="item-section" id="category-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Catégorie') }} </h1>
                        <div class="heading-underline"></div>
                    </div>

                    <select name="catid" class="form-select select2init translate"
                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                        v-model="form.catid">
                        <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                        <template v-for="cat in categories">
                            <option v-if="cat.is_project==1 && cat.type==='location'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">@{{ cat.property_type }}
                            </option>
                        </template>
                    </select>
                </section>

                <section v-if="type === 'vente'" class="item-section" id="category-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Catégorie') }} </h1>
                        <div class="heading-underline"></div>
                    </div>

                    <select name="catid" class="form-select select2init translate"
                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                        v-model="form.catid">
                        <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                        <template v-for="cat in categories">
                            <option v-if="cat.is_project==1 && cat.type==='vente'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">@{{ cat.property_type }}
                            </option>
                        </template>
                    </select>

                </section>

                <section v-if="type === 'vacance'" class="item-section" id="category-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Catégorie') }} </h1>
                        <div class="heading-underline"></div>
                    </div>

                    <select name="catid" class="form-select select2init translate"
                        :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                        v-model="form.catid">
                        <option :value="null">{{ __('general.Choisir une categorie') }}</option>
                        <template v-for="cat in categories">
                            <option v-if="cat.is_project==1 && cat.type==='vacance'" :value="cat.id"
                            :id="cat.property_type=='Appartement'?'apartment':cat.property_type">@{{ cat.property_type }}
                            </option>
                        </template>
                    </select>
                </section>
                @endif

                <section v-if="form.catid" class="item-section" id="loc-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Localisation') }}</h1>
                        <div class="heading-underline"></div>
                    </div>

                    <div class="">
                        <div class="row localisation-row">
                            <div class="form-group mb-4">
                                @if(Session::get('lang') === 'ar')
                                <label for="" class="translate" style="float:right">{{ __('general.Ville') }} :</label>
                                @endif

                                @if(Session::get('lang') === 'fr')
                                <label for="" class="translate">{{ __('general.Ville') }} :</label>
                                @endif

                                <div>


                                <ml-select :options="cities" value="id" label="name" @change="changeCity()"
                                    v-model:selected-option="form.loccity"
                                    :mls-class="errors.loccity ? 'is-invalid form-control translate notMobile' : 'form-control translate notMobile'"
                                    mls-placeholder="{{ __('general.select') }}" />

                                </div>

                                <div>
                                    <select v-model="form.loccity" class="form-select onlyMobile" @change="changeCity()">
                                        <option v-for="ct of cities" :value="ct.id">@{{ ct.name }}</option>
                                    </select>
                                </div>

                                <div class="invalid-feedback">
                                    @{{ errors.loccity?.join('<br/>') }}
                                </div>
                            </div>



                            <div class="form-group mb-4">
                                @if(Session::get('lang') === 'ar')
                                <label for="" class="translate" style="float:right">{{ __('general.Quartier') }} :</label>
                                @endif

                                @if(Session::get('lang') === 'fr')
                                <label for="" class="translate">{{ __('general.Quartier') }} :</label>
                                @endif

                                <div>

                                    <ml-select :options="neighborhood" :disabled="form.loccity == null" value="id"
                                        label="name" @change="changeDept()" v-model:selected-option="form.locdept"
                                        :mls-class="errors.locdept ? 'is-invalid form-control notMobile' : 'form-control notMobile'"
                                        mls-placeholder="{{ __('general.Sélectionner un quartier') }}" />

                                </div>

                                <div>
                                    <select v-model="form.locdept" class="form-select onlyMobile" :disabled="form.loccity == null" @change="changeDept()">
                                        <option v-for="nb of neighborhood" :value="nb.id">@{{ nb.name }}</option>
                                    </select>
                                </div>

                                <div class="invalid-feedback">
                                    @{{ errors.locdept?.join('<br/>') }}
                                </div>
                            </div>
                        </div>

                        @if(Session::get('lang')==='ar')
                        <div class="col-6 mb-4" id="dept2_cnt" v-if="form.locdept==-1" style="display:flex; flex-direction:column">
                            <label class="col-sm-4 col-form-label" style="align-self:flex-end; margin-right:64px;">{{ __('general.Autre quartier') }}</label>
                            <div class="col-sm-8">
                                <input id="locdept2" name="dept2" type="text" class="form-control" maxlength="199"
                                    v-model="form.locdept2">
                            </div>
                        </div>
                        @endif
                        @if(Session::get('lang') === 'fr')
                        <div class="col-6 mb-4" id="dept2_cnt" v-if="form.locdept==-1">
                            <label class="col-sm-4 col-form-label">{{ __('general.Autre quartier') }}</label>
                            <div class="col-sm-8">
                                <input id="locdept2" name="dept2" type="text" class="form-control" maxlength="199"
                                    v-model="form.locdept2">
                            </div>
                        </div>

                        @endif

                        <button id="btn-locate" class="d-none btn" @click="locateMap()">
                            <i class="fas fa-location-dot"></i>
                            {{ __('general.Localiser sur la map') }}
                        </button>

                        <button id="btn-locate-cancel" class="d-none btn" @click="cancelLocateMap()">
                            <i class="fa-solid fa-close"></i>
                            {{ __('general.Annuler la localisation sur la map') }}
                        </button>


                        <div id='map_cnt' class="d-none">
                            <div id="map-filter-group"></div>
                            <input type="text" id="mapSearch" class="mapSearch" placeholder="(lat,long)">
                            <div id='map' class="mb-3" style="width: 100%;height:400px;"></div>
                        </div>
                    </div>
                </section>




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
                        <h1>{{ __('general.Lieux à proximité') }}</h1>
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

                <section v-if="form.catid" class="item-section" id="places-section">
                    <div class="section-heading translate">
                        <h1>{{ __('general.Informations générales') }} </h1>
                        <div class="heading-underline"></div>
                    </div>

                    <div>
                        <!-- General Form Elements -->
                        <div class="row mb-3 translate">
                            <label for="inputSurface" class="col-sm-4 col-form-label">{{ __('general.Superficie') }}:</label>
                            <div class="row" style="margin-left: auto;">
                                <div class="row col-6">
                                    <label class="col-2">{{ __('general.De') }} :</label>
                                    <div class="col-10">
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
                                <div class="row col-6">
                                    <label class="col-2">{{ __('general.To') }} :</label>
                                    <div class="col-10">
                                        <div class="input-group">
                                            <input name="surface2" type="number" class="form-control translate"
                                                :class="errors.surface2 ? 'is-invalid' : ''" v-model="form.surface2">
                                            <span class="input-group-text">{{ __('general.m²') }}</span>
                                            <div class="invalid-feedback">
                                                @{{ errors.surface2?.join('<br/>') }}
                                            </div>
                                        </div>
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

                <section v-if="form.catid&&!form.user_id" class="item-section translate" id="places-section">
                    <div class="section-heading">
                        <h1>{{ __('general.Créer mon compte') }}</h1>
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
                            <label for="lastname">{{ __('general.votre nom') }} </label>
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
                            <label for="email">{{ __('general.Email') }} </label>
                            <input type="email" class="form-control" :class="errors.email ? 'is-invalid' : ''"
                                v-model="registerForm.email" placeholder="{{ __('general.Email') }}">
                            <div class="invalid-feedback">
                                @{{ errors.email?.join('<br/>') }}
                            </div>
                        </div>
                        {{-- form-group phone --}}
                        <div class="form-group" style="padding: 10px;">
                            <label for="phone">{{ __('general.Numéro de téléphone') }} </label>
                            <input type="phone" class="form-control" :class="errors.phone ? 'is-invalid' : ''"
                                v-model="registerForm.phone" id="phone3" ref="phone3">
                            <div class="invalid-feedback">
                                @{{ errors.phone?.join('<br/>') }}
                            </div>
                        </div>
                        {{-- form-group password --}}
                        <div class="form-group" style="padding: 10px;">
                            <label for="password">{{ __('general.Mot de passe') }} </label>
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

            <!-- add place Modal -->
            <div class="modal fade modal-close" id="addPlaceModal" @click.self="hideAddPlaceModal"
                data-id="addPlaceModal">
                <div class="modal-dialog modal-lg">
                    <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                        <div class="modal-header" style="@if(Session::get('lang')==='ar') display:flex; flex-direction:row !important; @endif">
                            <h5 class="modal-title">{{ __('general.Ajouter lieu') }}</h5>
                            <button type="button" class="btn-close modal-close" @click="hideAddPlaceModal"
                                data-id="addPlaceModal"></button>
                        </div>
                        <div class="modal-body"
                            style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                            <div class="row mb-3 translate">
                                <label for="inputTitle" class="col-sm-4 col-form-label">{{ __('general.Nom de lieu') }}</label>
                                <div class="col-sm-8">
                                    <input id="place-title" name="place_title" type="text" class="form-control"
                                        v-model="placesForm.title">
                                </div>
                            </div>

                            <div class="row mb-3 translate">
                                <label for="inputLoc" class="col-sm-4 col-form-label">{{ __('general.Distance') }}</label>
                                <div class="col-sm-8 ">
                                    <div class="input-group ">
                                        <input name="place_distance" type="number" class="form-control"
                                            v-model="placesForm.distance">
                                        <span class="input-group-text">{{ __('general.Mètre') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3 translate">
                                <label class="col-sm-4 col-form-label">{{ __('general.Type de lieu') }}</label>
                                <div class="col-sm-8">
                                    <select id="place-types" name="place_types" class="form-select"
                                        v-model="placesForm.type">
                                        <option :value="null">{{ __('general.Choisir un type') }}</option>
                                        <option v-for="type in place_types" :value="type.id">@{{ type.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer translate">
                            <button type="button" class="btn btn-secondary modal-close" @click="hideAddPlaceModal"
                                data-id="addPlaceModal">{{ __('general.close') }}</button>
                            <button id="addPlace" type="submit" class="btn btn-primary" @click="addPlace">
                                {{ __('general.Ajouter') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End add place Modal-->

            <!-- UPDATE place Modal -->
            <div class="modal fade modal-close" @click.self="hideUpdatePlaceModal" id="updatePlaceModal"
                data-id="updatePlaceModal">
                <div class="modal-dialog modal-lg">
                    <form onsubmit="event.preventDefault()" id="updatePlaceForm" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('general.Modifier lieu') }}</h5>
                            <button type="button" class="btn-close modal-close" @click="hideUpdatePlaceModal"
                                data-id="updatePlaceModal"></button>
                        </div>
                        <div class="modal-body"
                            style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                            <div class="row mb-3">
                                <label for="inputTitle" class="col-sm-4 col-form-label">{{ __('general.Nom de lieu') }}</label>
                                <div class="col-sm-8">
                                    <input id="place-title" name="place_title" type="text" class="form-control"
                                        v-model="placesFormUpdate.title">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputLoc" class="col-sm-4 col-form-label">{{ __('general.Distance') }}</label>
                                <div class="col-sm-8 ">
                                    <div class="input-group ">
                                        <input name="place_distance" type="number" class="form-control"
                                            v-model="placesFormUpdate.distance">
                                        <span class="input-group-text">{{ __('general.Mètre') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">{{ __('general.Type de lieu') }}</label>
                                <div class="col-sm-8">
                                    <select id="place-types" name="place_types" class="form-select"
                                        v-model="placesFormUpdate.type">
                                        <option :value="null">{{ __('general.Choisir une type') }}</option>
                                        <option v-for="type in place_types" :value="type.id">@{{ type.designation }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary modal-close" @click="hideUpdatePlaceModal"
                                data-id="updatePlaceModal">{{ __('general.Close') }}</button>
                            <button id="updatePlace" type="submit" class="btn btn-primary"
                                @click="updatePlace">{{ __('general.Modifier') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End UPDATE place Modal-->

            <!-- desc loader Modal -->
            <div class="modal show" v-if="descLoading" >
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-body"
                            style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                            <div class="step-section steppers" style="text-align: center;">
                                <div class="calculate-container">
                                    <img src="{{ asset('images/loader.gif') }}">
                                    <div class="processing-text">
                                        <div id="processing">
                                            <i class="fas fa-spinner fa-spin me-2"></i>
                                            {{ __('general.Votre requête est en cours de traitement ...') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">{{ __('general.Annuler') }}</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </div>

    <script>
        const elements = document.querySelectorAll('.translate');

    const localLang = '<?= session('lang') ?>';

    elements.forEach(item => {

        if(localLang === 'ar'){


            item.setAttribute('dir','rtl');
            item.style.marginRight='15px';


        }

        if(localLang === 'fr'){

            item.classList.remove('translate');
        }


    })



    </script>

@endsection

@section('custom_foot')


    <script>
        window.phone3 = document.querySelector('#phone3');
        const controller = new AbortController();

        const id = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);
        let addApp = createApp({
            data() {
                return {
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
                        is_project: true,
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
                "MlSelect": MlSelect
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
                generateDesc(){
                    swal("Cela prend plus d'une minute!", {
                        buttons: ["Annuler", "Générer la description"],
                    }).then((val) => {
                        if (val === true) {
                            let validation = true;
                            if(!this.form.surface){
                                validation = false;
                            }
                            if(!this.form.loccity){
                                validation = false;
                            }
                            if(!this.form.locdept&&!this.form.locdept2){
                                validation = false;
                            }
                            if(!this.form.catid){
                                validation = false;
                            }
                            if(!this.form.price){
                                validation = false;
                            }
                            if(!validation){
                                swal("Les champs suivants sont obligatoires", "ville, quartier, catégorie, superficie, prix", "error");
                                return;
                            }
                            const categorie = $('#categories-select  option:selected').attr('id');
                            axios.get("/api/v2/city/getCityById?id=" + this.form.loccity)
                                .then((cityResponse) => {
                                    const cityname = cityResponse.data.data.name;
                                    if(this.form.locdept){
                                        axios.get("/api/v2/neighborhood/getNeighborhoodById?id=" + this.form.locdept)
                                            .then((deptResponse) => {
                                                const deptname = deptResponse.data.data.name;
                                                const config = {
                                                    method: 'post',
                                                    //data: '- location: anfa- city: casablanca- category: villa- bedrooms: 3- bathroom: 2- area: 100m²- date of construction: 2020- price: 8000000 DH/m²- floor: 2nd- type of transaction: sale',
                                                    data:{
                                                        "location": deptname,
                                                        "city": cityname,
                                                        "category": categorie,
                                                        "bedrooms": this.form.bedrooms??0 ,
                                                        "bathroom": this.form.bedrooms??0 ,
                                                        "area": this.form.surface+"m²" ,
                                                        "date of construction": this.form.contriction_date??0,
                                                        "price": this.form.price+" DH/m²",
                                                        "floor": this.form.etage??0,
                                                        "type of transaction": this.type=='vente'?'sale':this.type=='location'?'rent':this.type=='vacance'?'vacation':''
                                                    },
                                                    url: `http://34.68.149.32:5000/`,
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        "Access-Control-Allow-Origin": "*"
                                                    },
                                                    responseType: 'text',
                                                    signal: controller.signal
                                                };
                                                this.descLoading = true;
                                                axios(config).then(response => {
                                                    this.form.description = response.data.description;
                                                    this.descLoading = false;
                                                })
                                                .catch((error)=> {
                                                    this.descLoading = false;
                                                    swal("", "Erreur de serveur", "error");
                                                });
                                            })
                                            .catch(error => {
                                                console.log(error);
                                            });
                                    }
                                    else{
                                        const deptname = this.form.locdept2;
                                        const config = {
                                            method: 'post',
                                            //data: '- location: anfa- city: casablanca- category: villa- bedrooms: 3- bathroom: 2- area: 100m²- date of construction: 2020- price: 8000000 DH/m²- floor: 2nd- type of transaction: sale',
                                            data:{
                                                "location": deptname,
                                                "city": cityname,
                                                "category": categorie,
                                                "bedrooms": this.form.bedrooms??0 ,
                                                "bathroom": this.form.bedrooms??0 ,
                                                "area": this.form.surface+"m²" ,
                                                "date of construction": this.form.contriction_date??0,
                                                "price": this.form.price+" DH/m²",
                                                "floor": this.form.etage??0,
                                                "type of transaction": this.type=='vente'?'sale':this.type=='location'?'rent':this.type=='vacance'?'vacation':''
                                            },
                                            url: `http://34.68.149.32:5000/`,
                                            headers: {
                                                'Content-Type': 'application/json',
                                                "Access-Control-Allow-Origin": "*"
                                            },
                                            responseType: 'text',
                                            signal: controller.signal
                                        };
                                        this.descLoading = true;
                                        axios(config).then(response => {
                                            this.form.description = response.data.description;
                                            this.descLoading = false;
                                        })
                                        .catch((error)=> {
                                            this.descLoading = false;
                                            swal("", "Erreur de serveur", "error");
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.log(error);
                                    swal("", "Erreur de serveur", "error");
                                });
                        }
                    });
                },
                cancelGenerateDesc(){
                    this.descLoading = false;
                    controller.abort();
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
                hideAddPlaceModal() {
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
                hideUpdatePlaceModal() {
                    hideModal('updatePlaceModal');
                },
                deletePlace(id) {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == id) this.form.nearbyPlaces.splice(i, 1);
                    }
                },
                changeCity() {
                    this.form.locdept = null;
                    this.form.lat = null;
                    this.form.long = null;
                    this.neighborhood = [{
                        id: null,
                        name: "Sélectionnez un quartier"
                    }, {
                        id: -1,
                        name: "Autre"
                    }];
                    axios.post("{{ route('api.loadDeptsByCity') }}", {
                            city: this.form.loccity
                        }).then(response => {
                            const data = response.data;
                            if (data.success) {
                                this.neighborhood = [{
                                    id: null,
                                    name: "Sélectionnez un quartier"
                                }, {
                                    id: -1,
                                    name: "Autre"
                                }].concat(data.data);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                },
                changeDept() {
                    document.querySelector('#dept2_cnt')?.classList.add('d-none');
                    document.querySelector('#map_cnt').classList.add('d-none');
                    document.querySelector('#btn-locate').classList.add('d-none');
                    document.querySelector('#btn-locate-cancel').classList.add('d-none');
                    document.querySelector('#map').innerHTML = '';
                    this.form.lat = null;
                    this.form.long = null;
                    if (this.form.locdept == '-1') {
                        document.querySelector('#dept2_cnt')?.classList.remove('d-none');
                        document.querySelector('#btn-locate').classList.remove('d-none');
                    } else {
                        const selected_dept = findObjInArrById(this.neighborhood, this.form.locdept);
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

                    console.log(this.type);
                },

                selectCat(val) {
                    if (val == this.form.catid) {
                        this.form.catid = null;
                    } else {
                        this.form.catid = val;
                        window.location.href = '#loc-section';
                    }
                    console.log ('type :',this.form.catid)
                },
                save0(status) {
                    if (this.form.user_id) {
                        this.save(status);
                    } else {
                        this.loading = true;
                        this.errors = {};
                        this.error = '';
                        axios.post('/api/v2/registerAndLogin', {
                                firstname: this.registerForm.firstname,
                                lastname: this.registerForm.lastname,
                                username: this.registerForm.username,
                                email: this.registerForm.email,
                                phone: this.registerForm.phone,
                                password: this.registerForm.password,
                                password2: this.registerForm.password2,
                                loccity: this.form.loccity,
                                locdept: this.form.locdept,
                                price: this.form.price,
                                title: this.form.title,
                                description: this.form.description,
                            })
                            .then(response => {
                                let data = response.data.data;
                                let token = data.token;
                                let auth = data.auth;
                                // store the token & auth
                                localStorage.setItem('token', token);
                                localStorage.setItem('auth', JSON.stringify(auth));
                                this.form.user_id = auth.id;
                                this.save(status);
                            })
                            .catch(error => {
                                this.loading = false;
                                if (typeof error.response.data.error === 'object') this.errors = error.response
                                    .data.error;
                                else {
                                    this.errorText = error.response.data.error;
                                    displayToast(this.errorText, '#842029');
                                }
                            });
                    }
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
        }).mount('#add-section');


    </script>





@endsection
