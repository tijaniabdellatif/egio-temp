<template>
    <div class="filter-section site-section" id="filter-app">

            <div class="filter-form-container">

                <h1 style="text-shadow: rgb(55 55 55 / 34%) -1px 1px 1px;letter-spacing: 2px;">{{__('general.Quel est votre projet ?')}}</h1>

                <div class="multilist-nav-tab" id="filter-form">

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
                    
                    <h2 class="type-item" :class="multilistType == 'officelist' ? 'active' : ''" id="officelist">
                        <a href="/officelist" class="">
                            <img src="/images/officelist-logo-white.png" alt="Officelist : immobilier professionnel" class="type-title">
                            <div class="bg-rect"></div>
                        </a>
                    </h2>
                </div>
            </div>

        </div>
</template>

<script>
export default {
    data(){
        return {
            test: "hello vue!"
        }
    }
}
</script>