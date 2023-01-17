const MultilistFilterComponent = {
    /*html*/
    template: `
    <div class="multilist-popup" id="filter-popup" >
        <div class="multilist-popup-gray-bg"></div>
            <div class="multilist-popup-container">
                <div class="multilist-popup-content">

                    <div class="multilist-popup-header">
                        <span class="label">{{filterComponentLang['Filtrer']}}</span>
                        <button class="btn p-0 close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>


                    <div class="multilist-popup-footer-mobile">
                        <button class="btn btn-submit" @click="submitfilter">
                            {{filterComponentLang['Filtrer']}}
                        </button>
                    </div>

                    <div class="multilist-popup-body">

                        <div class="multilist-nav-tab">

                            <div class="multilist-nav-items">
                                <button v-if="multilistTypeObj.id!=5" class="multilist-nav-button" :class="filter.type == 'vente'?'active':''" @click="initFilter();filter.type = 'vente';" pan="multilist-popup-1">{{filterComponentLang['Acheter']}}</button>
                                <button v-if="multilistTypeObj.id!=5&&multilistTypeObj.id!=3" :class="filter.type == 'location'?'active':''" class="multilist-nav-button" @click="initFilter();filter.type = 'location'" pan="multilist-popup-1">{{filterComponentLang['Louer']}}</button>
                                <button v-if="multilistTypeObj.id==5||multilistTypeObj.id==null" :class="filter.type == 'vacance'?'active':''" class="multilist-nav-button" @click="initFilter();filter.type = 'vacance'" pan="multilist-popup-1">{{filterComponentLang['Voyager']}}</button>
                                <div class="active-underline"></div>
                            </div>

                            <div class="multilist-tab-container">
                                <div class="multilist-tab-pane">
                                    <div class="row" >
                                        <div class="filter-select-group">
                                            <div class="form-group mb-4" id="filter-city">
                                                <label for="">{{filterComponentLang['Ville']}}</label>
                                                <select class="form-select onlyMobile" v-model="filter.city" id="" @change="loadQuartiers(filter.city)" >

                                                    <option v-for="v in filter_form.villes" :value="v.id" >{{ v.name }}</option>
                                                </select>
                                                <ml-select @change="loadQuartiers(filter.city)" :options="filter_form.villes" value="id" label="name" v-model:selected-option="filter.city" mls-class="form-control notMobile" v-model:mls-placeholder="filterComponentLang['Sélectionner une ville']" />
                                            </div>
                                            <div class="form-group mb-4" id="filter-neighborhood" >
                                                <label for="">{{filterComponentLang['Quartier']}}</label>
                                                <select class="form-select onlyMobile"  v-model="filter.neighborhood" >

                                                    <option v-for="q in filter_form.quartiers" :value="q.id" >{{ q.name }}</option>
                                                </select>
                                                <ml-select :options="filter_form.quartiers" value="id" label="name" v-model:selected-option="filter.neighborhood" mls-class="form-control notMobile" v-model:mls-placeholder="filterComponentLang['Sélectionnez un quartier']" />
                                            </div>
                                            <div class="form-group mb-4" id="filter-categorie" >
                                                <label>{{filterComponentLang['Catégorie']}}</label>
                                                <select class="form-select onlyMobile" v-model="filter.categorie" >
                                                    <option :value="null" >{{filterComponentLang['Sélectionner une catégorie']}}</option>
                                                    <template v-for="c in filter_form.categories" >
                                                        <option v-if="c.type == filter.type" :value="c.id" >{{ c.property_type }}</option>
                                                    </template>
                                                </select>
                                                <ml-select :options="filter_form.categories.filter(c => c.type == filter.type)" value="id" label="property_type" v-model:selected-option="filter.categorie" mls-class="form-control notMobile" v-model:mls-placeholder="filterComponentLang['Sélectionner une catégorie']" />
                                            </div>
                                        </div>
                                        <div class="form-group col-12 mb-4" id="filter-price" >
                                            <label>{{filterComponentLang['Prix']}}</label>
                                            <div class="row select-filter-row">
                                                <div class="col-6 d-flex item-center" id="filter-min_price">
                                                    <template v-if="!filter_form.customMinPrice" >
                                                        <select v-model="filter.min_price" class="form-control" @change="autre($event,'min_price')" placeholder="Min">
                                                            <option :value="null" >{{filterComponentLang['Min']}}</option>
                                                            <option v-for="pm in filter_form.prix.min" >{{ pm }}</option>
                                                            <option value="autre">{{filterComponentLang['Autre']}}</option>
                                                        </select>
                                                    </template>
                                                    <template v-else>
                                                        <input type="number" v-model="filter.min_price" min="0" class="form-control" placeholder="Min" />
                                                        <button class="btn btn-sm text-danger" @click="custom('min_price',false);filter.min_price=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                                <div class="col-6 d-flex item-center d-flex item-center" id="filter-max_price">
                                                    <template v-if="!filter_form.customMaxPrice" >
                                                        <select v-model="filter.max_price" class="form-control"  @change="autre($event,'max_price')" placeholder="Max">
                                                            <option :value="null" >{{filterComponentLang['Max']}}</option>
                                                            <option v-for="pmx in filter_form.prix.max" >{{ pmx }}</option>
                                                            <option value="autre">{{filterComponentLang['Autre']}}</option>
                                                        </select>
                                                    </template>
                                                    <template v-else >
                                                        <input type="number" v-model="filter.max_price" min="0" class="form-control" placeholder="Max" />
                                                        <button class="btn btn-sm text-danger" @click="custom('max_price',false);filter.max_price=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-12 mb-4"  id="filter-surface">
                                            <label>{{filterComponentLang['Surface']}}</label>
                                            <div class="row select-filter-row">
                                                <div class="col-6 d-flex item-center d-flex item-center" id="filter-min_surface">
                                                    <template v-if="!filter_form.customMinSurface" >
                                                        <select v-model="filter.min_surface" class="form-control"  @change="autre($event,'min_surface')" placeholder="Min">
                                                            <option :value="null" >{{filterComponentLang['Min']}}</option>
                                                            <option v-for="sm in filter_form.surface.min" >{{ sm }}</option>
                                                            <option value="autre">{{filterComponentLang['Autre']}}</option>
                                                        </select>
                                                    </template>
                                                    <template v-else>
                                                        <input type="number" v-model="filter.min_surface" min="0" class="form-control" placeholder="Min" />
                                                        <button class="btn btn-sm text-danger" @click="custom('min_surface',false);filter.min_surface=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                                <div class="col-6 d-flex item-center" id="filter-max_surface">
                                                    <template v-if="!filter_form.customMaxSurface" >
                                                        <select v-model="filter.max_surface" @change="autre($event,'max_surface')" class="form-control" placeholder="Max">
                                                            <option :value="null" >{{filterComponentLang['Max']}}</option>
                                                            <option v-for="smx in filter_form.surface.min" >{{ smx }}</option>
                                                            <option value="autre">{{filterComponentLang['Autre']}}</option>
                                                        </select>
                                                    </template>
                                                    <template v-else>
                                                        <input type="number" v-model="filter.max_surface" class="form-control" placeholder="Max" />
                                                        <button class="btn btn-sm text-danger" @click="custom('max_surface',false);filter.max_surface=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex flex-column" id="filter-rooms">
                                            <label for="">{{filterComponentLang['Pieces']}}</label>
                                            <div class="features" >
                                                <template v-if="!filter_form.customPieces" >
                                                    <span v-for="p in filter_form.pieces" @click="filter.rooms=p" class="selectable-item" :class="p==filter.rooms?'selected':''"  >{{ p }}</span>
                                                    <span class="selectable-item" @click="custom('rooms',true)" >+</span>
                                                </template>
                                                <template v-else >
                                                    <input type="number" v-model="filter.rooms" class="form-control" placeholder="Pieces" />
                                                    <button class="btn btn-sm text-danger" @click="custom('rooms',false);filter.rooms=null" >
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex flex-column"  id="filter-bedrooms">
                                        <label for="">{{filterComponentLang['Chambres']}}</label>
                                            <div class="features" >
                                                <template v-if="!filter_form.customChambres" >
                                                    <span v-for="c in filter_form.chambres" @click="filter.bedrooms=c" class="selectable-item" :class="c==filter.bedrooms?'selected':''"  >{{ c }}</span>
                                                    <span class="selectable-item" @click="custom('bedrooms',true)" >+</span>
                                                </template>
                                                <template v-else >
                                                    <input type="number" v-model="filter.bedrooms" class="form-control" placeholder="Chambres" />
                                                    <button class="btn btn-sm text-danger" @click="custom('bedrooms',false);filter.bedrooms=null" >
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="col-12"  id="filter-bathrooms">
                                            <div class="" >
                                                <label for="">{{filterComponentLang['Salles de bain']}}</label>
                                                <div class="features" >
                                                    <template v-if="!filter_form.customSallesDeBain" >
                                                        <span v-for="sb in filter_form.salles_de_bain" @click="filter.bathrooms=sb" class="selectable-item" :class="sb==filter.bathrooms?'selected':''"  >{{ sb }}</span>
                                                        <span class="selectable-item" @click="custom('bathrooms',true)" >+</span>
                                                    </template>
                                                    <template v-else >
                                                        <input type="number" v-model="filter.bathrooms" class="form-control" placeholder="Salles de bain" />
                                                        <button class="btn btn-sm text-danger" @click="custom('bathrooms',false);filter.bathrooms=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12"  id="filter-age">
                                            <div class="" >
                                                <label for="">{{filterComponentLang['Année de construction']}}</label>
                                                <div class="features" >
                                                    <template v-if="!filter_form.customAge" >
                                                        <span v-for="age in filter_form.age" @click="filter.age=age" class="selectable-item" :class="age==filter.age?'selected':''"  >{{ age }}</span>
                                                        <span class="selectable-item" @click="custom('age',true)" >+</span>
                                                    </template>
                                                    <template v-else >
                                                        <input type="number" v-model="filter.age" class="form-control" placeholder="Age" />
                                                        <button class="btn btn-sm text-danger" @click="custom('age',false);filter.age=null" >
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="false" class="col-12" >
                                            <label for="">{{filterComponentLang['Équipements']}}</label>
                                            <div class="row p-3" >
                                                <div class="col-4 mb-3"  id="filter-jardin">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.jardin" >
                                                            {{filterComponentLang['Jardin']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-piscine">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.piscine" >
                                                            {{filterComponentLang['Piscine']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-parking">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.parking" >
                                                            {{filterComponentLang['Parking']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-meuble">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.meuble" >
                                                            {{filterComponentLang['Meublé']}}
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-4 mb-3"  id="filter-clime">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.clime" >
                                                            {{filterComponentLang['Climatisé']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-terrasse">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.terrasse" >
                                                            {{filterComponentLang['Terrasse']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-syndic">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.syndic" >
                                                            {{filterComponentLang['Syndic']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-cave">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.cave" >
                                                            {{filterComponentLang['Cave']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-ascenseur">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.ascenseur" >
                                                            {{filterComponentLang['Ascenseur']}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-4 mb-3"  id="filter-security">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" class="form-check-input" v-model="filter.security" >
                                                            {{filterComponentLang['Sécurité']}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="multilist-popup-footer">
                        <button class="btn btn-submit" @click="submitfilter">
                            {{filterComponentLang['Filtrer']}}
                        </button>
                    </div>


                </div>
            </div>
        </div>
    `,
    data() {
        return {
            filterComponentLang: filterComponentLang,
            multilistTypeObj: multilistTypeObj,
            filter_form: {
                villes: [],
                quartiers: [{ id: null, name: filterComponentLang['Sélectionnez un quartier'] }],
                categories: [],
                standings: [],
                prix: {
                    min: [
                        100000, 150000, 200000, 250000, 300000, 400000, 500000,
                        750000, 1000000, 1250000, 1500000, 2000000, 2500000,
                    ],
                    max: [
                        100000, 150000, 200000, 250000, 300000, 400000, 500000,
                        750000, 1000000, 1250000, 1500000, 2000000, 2500000,
                    ],
                },
                surface: {
                    min: [50, 100, 150, 200, 250, 300, 350, 400],
                    max: [50, 100, 150, 200, 250, 300, 350, 400],
                },
                pieces: [0, 1, 2, 3, 4, 5],
                chambres: [0, 1, 2, 3, 4, 5],
                salles_de_bain: [0, 1, 2, 3, 4, 5],
                age: [
                    new Date().getFullYear() - 5,
                    new Date().getFullYear() - 4,
                    new Date().getFullYear() - 3,
                    new Date().getFullYear() - 2,
                    new Date().getFullYear() - 1,
                    new Date().getFullYear(),
                ],
                customMinPrice: false,
                customMaxPrice: false,
                customMinSurface: false,
                customMaxSurface: false,
                customPieces: false,
                customChambres: false,
                customSallesDeBain: false,
                customAge: false,
            },
            filter: {},
        };
    },
    mounted() {
        this.initFilter();

        this.filter.univer = multilistTypeObj.id;

        this.filter.type = "vente";

        this.loadingData();
    },
    components: {
        MlSelect: MlSelect,
    },
    methods: {
        loadingData() {
            let params = "?";

            if (this.filter.univer) {
                params += "univer=" + this.filter.univer + "&";
            }

            // remove last &
            params = params.substring(0, params.length - 1);

            // remove ? if no params added
            if (params.length < 2) {
                params = "";
            }

            axios
                .get("/api/v2/multilistfilterfrom" + params)
                .then((response) => {
                    let data = response.data.data;

                    this.filter_form.categories = [
                        { id: null, title: this.filterComponentLang['Sélectionner une catégorie'] },
                    ].concat(data.categories);
                    this.filter_form.villes = [
                        { id: null, name: this.filterComponentLang['Sélectionner une ville'] },
                    ].concat(data.cities);
                    this.filter_form.standings = data.standings;

                    var search = window.location.search
                        .split("?")[1]
                        ?.split("&");
                    this.searchItems = [];

                    if(typeof city_slug !== 'undefined'){
                        if(city_slug) this.filter['city'] = city_slug;
                    }
                    if(typeof neighborhood_slug !== 'undefined'){
                        if(neighborhood_slug) this.filter['neighborhood'] = neighborhood_slug;
                    }
                    if(typeof categorie_slug !== 'undefined'){
                        if(categorie_slug) this.filter['categorie'] = categorie_slug;
                    }
                    if(typeof type_slug !== 'undefined'){
                        if(type_slug) this.filter['type'] = type_slug;
                    }
                    if (search)
                        for (const s of search) {
                            if (s.split("=")[1])
                                this.filter[s.split("=")[0]] = s.split("=")[1];
                        }

                    console.log('this.filter',this.filter);

                })
                .catch((error) => {
                    console.log(error);
                });
        },
        submitfilter() {
            let filter = Object.assign({}, this.filter);

            if (filter.jardin === null) delete filter.jardin;

            if (filter.piscine === null) delete filter.piscine;

            if (filter.parking === null) delete filter.parking;

            if (filter.meuble === null) delete filter.meuble;

            if (filter.security === null) delete filter.security;

            if (filter.clime === null) delete filter.clime;

            if (filter.terrasse === null) delete filter.terrasse;

            if (filter.cave === null) delete filter.cave;

            if (filter.syndic === null) delete filter.syndic;

            if (filter.ascenseur === null) delete filter.ascenseur;

            delete filter.univer;

            this.$emit("submitfilter", { filter: filter });
        },
        loadQuartiers(city) {
            //this.filter_form.quartiers = [];
            this.filter_form.quartiers = [
                { id: null, name: this.filterComponentLang['Sélectionnez un quartier'] },
            ];
            this.filter.neighborhood = null;

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
                    this.filter_form.quartiers = [
                        { id: null, name: this.filterComponentLang['Sélectionnez un quartier'] },
                    ].concat(data);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        initFilter() {
            this.filter = {
                type: null,
                categorie: null,
                univer: null,
                standing: null,
                city: null,
                neighborhood: null,
                min_price: null,
                max_price: null,
                min_surface: null,
                max_surface: null,
                rooms: null,
                bedrooms: null,
                bathrooms: null,
                age: null,
                jardin: null,
                piscine: null,
                parking: null,
                meuble: null,
                security: null,
                clime: null,
                terrasse: null,
                syndic: null,
                cave: null,
                ascenseur: null,
                search: null,
            };
        },
        autre(e, type) {
            if (e.target.value == "autre") {
                this.custom(type);
            }
        },
        custom(type, enable = true) {
            if (type === "min_price") this.filter_form.customMinPrice = enable;
            else if (type === "max_price")
                this.filter_form.customMaxPrice = enable;
            else if (type === "min_surface")
                this.filter_form.customMinSurface = enable;
            else if (type === "max_surface")
                this.filter_form.customMaxSurface = enable;
            else if (type === "rooms") this.filter_form.customPieces = enable;
            else if (type === "bedrooms")
                this.filter_form.customChambres = enable;
            else if (type === "bathrooms")
                this.filter_form.customSallesDeBain = enable;
            else if (type === "age") this.filter_form.customAge = enable;
        },
    },
};

