@extends('v2.layouts.dashboard')

@section('title', 'Localisations')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Localisations : </h1>
    </div>
    <section id="app">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-regions-tab" data-bs-toggle="pill" data-bs-target="#pills-regions" type="button" role="tab" aria-controls="pills-regions" aria-selected="true">Régions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-provinces-tab" data-bs-toggle="pill" data-bs-target="#pills-provinces" type="button" role="tab" aria-controls="pills-provinces" aria-selected="false">Provinces</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-cities-tab" data-bs-toggle="pill" data-bs-target="#pills-cities" type="button" role="tab" aria-controls="pills-cities" aria-selected="false">Villes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-neighborhoods-tab" data-bs-toggle="pill" data-bs-target="#pills-neighborhoods" type="button" role="tab" aria-controls="pills-neighborhoods" aria-selected="false">Quartiers</button>
                    </li>
              </ul>
            </div>
            <div class="col-12">
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane active" id="pills-regions" role="tabpanel"
                        aria-labelledby="pills-regions-tab">
                        <div class="row">

                            <div class="col-12">
                                <div class="card">

                                <div class="card-body needs-validation" ref="div1">

                                    <h5 class="card-title">Ajouter ou modifier une Région : </h5>

                                    <div class="form-group" hidden>
                                        <label for="my-select"><small>Id :</small></label>
                                        <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Nom :</small></label>
                                        <input class="form-control" :class="errors.name ? 'is-invalid' : ''" type="text"
                                            v-model="form.name" min="1">
                                        <div class="invalid-feedback">
                                            @{{ errors.name?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Pays :</small></label>
                                        <select class="form-select select2init" :class="errors.country_id ? 'is-invalid' : ''" v-model="form.country_id" >
                                            <option :value="null">Choisir un Pays</option>
                                            <option v-for="v in countries" :value="v.id">@{{v.name}}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.country_id?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>coordinates(MULTYPOLYGON JSON) :</small></label>
                                        <textarea class="form-control"
                                            v-model="form.coordinates" min="1">
                                        </textarea>
                                    </div>

                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2" :disabled="loading"
                                            @click="save0()">
                                            Sauvegarder
                                            <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                                        <div class="col-sm-2">
                                            <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                                <option :value="10">10</option>
                                                <option :value="15">15</option>
                                                <option :value="20">20</option>
                                                <option :value="50">50</option>
                                                <option :value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="row col-sm-4" style="margin-left: auto;">
                                            <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                                <i class="bi bi-search"></i>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="form-control w-100" v-model="search" placeholder="Rechercher ..."
                                                    @keyup.enter="loadFilter()" type="text" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                                        @selectedchanged="selectedChanged">
                                        <template #action="props">
                                            <button class="btn p-0 m-0 me-2" @click="edit(props.row.value),goto('div1')">
                                                <i class="fas fa-edit text-success"></i>
                                            </button>
                                            <button class="btn p-0 m-0 me-2" @click="deleteRegion(props.row.value)">
                                                <i class="fas fa-trash-can text-success"></i>
                                            </button>
                                        </template>
                                    </datatable-component>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-provinces" role="tabpanel"
                        aria-labelledby="pills-provinces-tab">
                        <div class="row">

                            <div class="col-12">
                                <div class="card">

                                <div class="card-body needs-validation" ref="div1">

                                    <h5 class="card-title">Ajouter ou modifier une Province : </h5>

                                    <div class="form-group" hidden>
                                        <label for="my-select"><small>Id :</small></label>
                                        <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Nom :</small></label>
                                        <input class="form-control" :class="errors.name ? 'is-invalid' : ''" type="text"
                                            v-model="form.name" min="1">
                                        <div class="invalid-feedback">
                                            @{{ errors.name?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Région :</small></label>
                                        <select class="form-select select2init" :class="errors.region_id ? 'is-invalid' : ''" v-model="form.region_id" >
                                            <option :value="null">Choisir une Région</option>
                                            <option v-for="v in regions" :value="v.id">@{{v.name}}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.region_id?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>coordinates(MULTYPOLYGON JSON) :</small></label>
                                        <textarea class="form-control"
                                            v-model="form.coordinates" min="1">
                                        </textarea>
                                    </div>

                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2" :disabled="loading"
                                            @click="save0()">
                                            Sauvegarder
                                            <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                                        <div class="col-sm-2">
                                            <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                                <option :value="10">10</option>
                                                <option :value="15">15</option>
                                                <option :value="20">20</option>
                                                <option :value="50">50</option>
                                                <option :value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="row col-sm-4" style="margin-left: auto;">
                                            <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                                <i class="bi bi-search"></i>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="form-control w-100" v-model="search" placeholder="Rechercher ..."
                                                    @keyup.enter="loadFilter()" type="text" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                                        @selectedchanged="selectedChanged">
                                        <template #action="props">
                                            <button class="btn p-0 m-0 me-2" @click="edit(props.row.value),goto('div1')">
                                                <i class="fas fa-edit text-success"></i>
                                            </button>
                                            <button class="btn p-0 m-0 me-2" @click="deleteProvince(props.row.value)">
                                                <i class="fas fa-trash-can text-success"></i>
                                            </button>
                                        </template>
                                    </datatable-component>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="pills-cities" role="tabpanel"
                        aria-labelledby="pills-cities-tab">
                        <div class="row">

                            <div class="col-12">
                                <div class="card">

                                <div class="card-body needs-validation" ref="div1">

                                    <h5 class="card-title">Ajouter ou modifier une Ville : </h5>

                                    <div class="form-group" hidden>
                                        <label for="my-select"><small>Id :</small></label>
                                        <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Nom :</small></label>
                                        <input class="form-control" :class="errors.name ? 'is-invalid' : ''" type="text"
                                            v-model="form.name" min="1">
                                        <div class="invalid-feedback">
                                            @{{ errors.name?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Région :</small></label>
                                        <select class="form-select select2init" v-model="region_id" @change="changeRegion">
                                            <option :value="null">Choisir une Région</option>
                                            <option v-for="v in regions" :value="v.id">@{{v.name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2" v-if="region_id!=null">
                                        <label><small>Province :</small></label>
                                        <select class="form-select select2init" :class="errors.province_id ? 'is-invalid' : ''" v-model="form.province_id" >
                                            <option :value="null">Choisir une Province</option>
                                            <option v-for="v in provinces" :value="v.id">@{{v.name}}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.province_id?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>coordinates(MULTYPOLYGON JSON) :</small></label>
                                        <textarea class="form-control"
                                            v-model="form.coordinates" min="1">
                                        </textarea>
                                    </div>

                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2" :disabled="loading"
                                            @click="save0()">
                                            Sauvegarder
                                            <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                                        <div class="col-sm-2">
                                            <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                                <option :value="10">10</option>
                                                <option :value="15">15</option>
                                                <option :value="20">20</option>
                                                <option :value="50">50</option>
                                                <option :value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="row col-sm-4" style="margin-left: auto;">
                                            <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                                <i class="bi bi-search"></i>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="form-control w-100" v-model="search" placeholder="Rechercher ..."
                                                    @keyup.enter="loadFilter()" type="text" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                                        @selectedchanged="selectedChanged">
                                        <template #action="props">
                                            <button class="btn p-0 m-0 me-2" @click="edit(props.row.value),goto('div1')">
                                                <i class="fas fa-edit text-success"></i>
                                            </button>
                                            <button class="btn p-0 m-0 me-2" @click="deleteCity(props.row.value)">
                                                <i class="fas fa-trash-can text-success"></i>
                                            </button>
                                        </template>
                                    </datatable-component>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-neighborhoods" role="tabpanel"
                        aria-labelledby="pills-neighborhoods-tab">
                        <div class="row">

                            <div class="col-12">
                                <div class="card">

                                <div class="card-body needs-validation" ref="div1">

                                    <h5 class="card-title">Ajouter ou modifier un Quartier : </h5>

                                    <div class="form-group" hidden>
                                        <label for="my-select"><small>Id :</small></label>
                                        <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Nom :</small></label>
                                        <input class="form-control" :class="errors.name ? 'is-invalid' : ''" type="text"
                                            v-model="form.name" min="1">
                                        <div class="invalid-feedback">
                                            @{{ errors.name?.join('<br/>') }}
                                        </div>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>Région :</small></label>
                                        <select class="form-select select2init" v-model="region_id" @change="changeRegion" >
                                            <option :value="null">Choisir une Région :</option>
                                            <option v-for="v in regions" :value="v.id">@{{v.name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2" v-if="region_id!=null">
                                        <label><small>Province :</small></label>
                                        <select class="form-select select2init" v-model="province_id" @change="changeProvince" >
                                            <option :value="null">Choisir une Province  :</option>
                                            <option v-for="v in provinces" :value="v.id">@{{v.name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2" v-if="province_id!=null">
                                        <label><small>Ville :</small></label>
                                        <select class="form-select select2init" v-model="form.city_id" >
                                            <option :value="null">Choisir une ville :</option>
                                            <option v-for="v in cities" :value="v.id">@{{v.name}}</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-2">
                                        <label><small>coordinates(MULTYPOLYGON JSON) :</small></label>
                                        <textarea class="form-control"
                                            v-model="form.coordinates" min="1">
                                        </textarea>
                                    </div>

                                    <div class="d-flex flex-row-reverse">
                                        <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2" :disabled="loading"
                                            @click="save0()">
                                            Sauvegarder
                                            <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </button>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                                        <div class="col-sm-2">
                                            <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                                <option :value="10">10</option>
                                                <option :value="15">15</option>
                                                <option :value="20">20</option>
                                                <option :value="50">50</option>
                                                <option :value="100">100</option>
                                            </select>
                                        </div>
                                        <div class="row col-sm-4" style="margin-left: auto;">
                                            <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                                <i class="bi bi-search"></i>
                                            </label>
                                            <div class="col-sm-10">
                                                <input class="form-control w-100" v-model="search" placeholder="Rechercher ..."
                                                    @keyup.enter="loadFilter()" type="text" name="">
                                            </div>
                                        </div>
                                    </div>
                                    <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                                        @selectedchanged="selectedChanged">
                                        <template #action="props">
                                            <button class="btn p-0 m-0 me-2" @click="edit(props.row.value),goto('div1')">
                                                <i class="fas fa-edit text-success"></i>
                                            </button>
                                            <button class="btn p-0 m-0 me-2" @click="deleteNeighborhood(props.row.value)">
                                                <i class="fas fa-trash-can text-success"></i>
                                            </button>
                                        </template>
                                    </datatable-component>
                                </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">

        let appReg = Vue.createApp({
            data() {

                return {
                    search: '',
                    countries : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'regions_datatable',
                        api: '/api/v2/regions/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Pays',
                                field: 'country',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'action',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 10,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                    form: {
                        id: null,
                        name: '',
                        coordinates: '',
                        country_id: null,
                    },
                }
            },
            watch: {
                'datatable.selected': function(val) {
                    this.form = {
                        id: val.id,
                        name: val.name,
                        country_id: val.country_id,
                        coordinates: val.coordinates?JSON.stringify(val.coordinates):null,
                    };
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {
                axios.post('/api/v2/region/getCountries')
                .then(response => {
                    if(response.data.success==true){
                      this.countries = response.data.data;
                    }
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            methods: {

                goto(refName) {
             var element = this.$refs[refName];
             var top = element.offsetTop;

              window.scrollTo(0, top);
                },

              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'regions.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'regions.name',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'regions.country_id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },

                 save0(){


                    if(!this.validateForm()){ this.loading = false; return; }

                    if(false) {
                        this.loading = false;

                        return;
                    }
                    swal("Voulez-vous vraiment ajouter ou modifier cette région?", {
                        buttons: ["Non", "Oui"],
                    }).then((val)=>{

                        if(val===true){

                            this.save();
                        }
                    });

                    },
                save() {
                    // create
                    this.loading = true;
                    this.errors = {};

                    if(!this.validateForm()){ this.loading = false; return; }

                    if (this.datatable.selected.id) {
                        // post to /api/v2/standings/update
                        axios.post('/api/v2/region/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    country_id: null,
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("La région a été modifiée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                console.log(error);
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                    // update
                    else {
                        // post to /api/v2/standings/create
                        axios.post('/api/v2/region/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    country_id: null,
                                  };
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("La région a été ajoutée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    console.log(error);
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },

                deleteRegion(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer cette région ?",
                                text: "Vous allez supprimer la région séléctionnée",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/region/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("La région a été supprimé avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object')
                                    this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                }
                            });


                },

                selectedChanged(selected) {
                    if (selected)
                        this.datatable.selected = {
                            ...selected
                        };
                    else
                        this.datatable.selected = {
                            id: null,
                            name: '',
                            coordinates: '',
                            country_id: null,
                        }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.name || this.form.name.trim()==""){
                      if(!this.errors.name) this.errors.name = [];
                      this.errors.name.push('Le champ nom est requis.');
                      r = false;
                  }
                  if(!this.form.country_id){
                      if(!this.errors.country_id) this.errors.country_id = [];
                      this.errors.country_id.push('Le champ pays est requis.');
                      r = false;
                  }
                  return r;
                }
            }
        }).mount('#pills-regions');


        let appProvince = Vue.createApp({
            data() {

                return {
                    search: '',
                    regions : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'procinces_datatable',
                        api: '/api/v2/provinces/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Région',
                                field: 'region',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'action',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 10,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                    form: {
                        id: null,
                        name: '',
                        coordinates: '',
                        region_id: null,
                    },
                }
            },
            watch: {
                'datatable.selected': function(val) {
                    this.form = {
                        id: val.id,
                        name: val.name,
                        region_id: val.region_id,
                        coordinates: val.coordinates?JSON.stringify(val.coordinates):null,
                    };
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {
                axios.post('/api/v2/province/getRegions')
                .then(response => {
                    if(response.data.success==true){
                      this.regions = response.data.data;
                    }
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            methods: {
                // Scroll up to from in edit
                goto(refName) {
             var element = this.$refs[refName];
             var top = element.offsetTop;

              window.scrollTo(0, top);
                },

              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'provinces.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'provinces.name',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'provinces.region_id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },

                 save0(){


                    if(!this.validateForm()){ this.loading = false; return; }

                    if(false) {
                        this.loading = false;

                        return;
                    }
                    swal("Voulez-vous vraiment ajouter ou modifier cette province?", {
                        buttons: ["Non", "Oui"],
                    }).then((val)=>{

                        if(val===true){

                            this.save();
                        }
                    });

                    },

                save() {
                    // create
                    this.loading = true;
                    this.errors = {};

                    if(!this.validateForm()){ this.loading = false; return; }

                    if (this.datatable.selected.id) {
                        // post to /api/v2/standings/update
                        axios.post('/api/v2/province/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    region_id: null,
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("La province a été modifiée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                console.log(error);
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                    // update
                    else {
                        // post to /api/v2/standings/create
                        axios.post('/api/v2/province/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    region_id: null,
                                  };
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("La province a été ajoutée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    console.log(error);
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },

                 deleteProvince(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer cette province ?",
                                text: "Vous allez supprimer la province séléctionnée",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/province/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("La province a été supprimé avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object')
                                    this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                }
                            });


                },

                selectedChanged(selected) {
                    if (selected)
                        this.datatable.selected = {
                            ...selected
                        };
                    else
                        this.datatable.selected = {
                            id: null,
                            name: '',
                            coordinates: '',
                            region_id: null,
                        }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.name || this.form.name.trim()==""){
                      if(!this.errors.name) this.errors.name = [];
                      this.errors.name.push('Le champ nom est requis.');
                      r = false;
                  }
                  if(!this.form.region_id){
                      if(!this.errors.region_id) this.errors.region_id = [];
                      this.errors.region_id.push('Le champ région est requis.');
                      r = false;
                  }
                  return r;
                }
            }
        }).mount('#pills-provinces');

        let appCity = Vue.createApp({
            data() {

                return {
                    search: '',
                    regions : [],
                    provinces : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'cities_datatable',
                        api: '/api/v2/cities/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Province',
                                field: 'province',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'action',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 10,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                    form: {
                        id: null,
                        name: '',
                        coordinates: '',
                        province_id: null,
                    },
                    region_id:null,
                }
            },
            watch: {
                'datatable.selected': function(val) {

                    this.region_id = null;
                    this.form = {
                        id: val.id,
                        name: val.name,
                        province_id: val.province_id,
                        coordinates: val.coordinates?JSON.stringify(val.coordinates):null,
                    };
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {
                axios.post('/api/v2/province/getRegions')
                .then(response => {
                    if(response.data.success==true){
                      this.regions = response.data.data;
                    }
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            methods: {

            // Scroll up to from in edit
             goto(refName) {
             var element = this.$refs[refName];
             var top = element.offsetTop;

              window.scrollTo(0, top);
                },

                changeRegion(){
                    axios.post('/api/v2/province/getProvinces',{id:this.region_id})
                    .then(response => {
                        if(response.data.success==true){
                        this.provinces = response.data.data;
                        }
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
                },
              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'cities.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cities.name',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cities.province_id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },
                 save0(){


                    if(!this.validateForm()){ this.loading = false; return; }

                    if(false) {
                        this.loading = false;

                        return;
                    }
                    swal("Voulez-vous vraiment ajouter ou modifier cette ville?", {
                        buttons: ["Non", "Oui"],
                    }).then((val)=>{

                        if(val===true){

                            this.save();
                        }
                    });

                    },
                save() {
                    // create
                    this.loading = true;
                    this.errors = {};

                    if(!this.validateForm()){ this.loading = false; return; }

                    if (this.datatable.selected.id) {
                        // post to /api/v2/standings/update
                        axios.post('/api/v2/city/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                    this.region_id = null;
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    province_id: null,
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("La ville a été modifiée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                console.log(error);
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                    // update
                    else {
                        // post to /api/v2/standings/create
                        axios.post('/api/v2/city/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                    this.region_id = null;
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    province_id: null,
                                  };
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("La ville a été ajoutée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    console.log(error);
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },

                deleteCity(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer cette ville ?",
                                text: "Vous allez supprimer la ville séléctionnée",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/city/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("La ville a été supprimé avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object')
                                    this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                }
                            });


                },

                selectedChanged(selected) {
                    if (selected)
                        this.datatable.selected = {
                            ...selected
                        };
                    else{
                        this.region_id = null;
                        this.datatable.selected = {
                            id: null,
                            name: '',
                            coordinates: '',
                            province_id: null,
                        }
                    }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.name || this.form.name.trim()==""){
                      if(!this.errors.name) this.errors.name = [];
                      this.errors.name.push('Le champ nom est requis.');
                      r = false;
                  }
                  if(!this.form.province_id){
                      if(!this.errors.province_id) this.errors.province_id = [];
                      this.errors.province_id.push('Le champ province est requis.');
                      r = false;
                  }
                  return r;
                }
            }
        }).mount('#pills-cities');

        let appNeighborhoods = Vue.createApp({
            data() {

                return {
                    search: '',
                    regions : [],
                    provinces : [],
                    cities : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'neighborhoods_datatable',
                        api: '/api/v2/neighborhoods/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Ville',
                                field: 'city',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'action',
                                sortable: false,
                                searchable: false,
                                customize: true
                            }
                        ],
                        rows: [],
                        filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 10,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                    form: {
                        id: null,
                        name: '',
                        coordinates: '',
                        city_id: null,
                    },
                    province_id: null,
                    region_id:null,
                }
            },
            watch: {
                'datatable.selected': function(val) {
                    //
                    this.region_id = null;
                    this.province_id = null;
                    this.form = {
                        id: val.id,
                        name: val.name,
                        city_id: val.city_id,
                        coordinates: val.coordinates?JSON.stringify(val.coordinates):null,
                    };
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {
                axios.post('/api/v2/province/getRegions')
                .then(response => {
                    if(response.data.success==true){
                      this.regions = response.data.data;
                    }
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            methods: {
                changeRegion(){
                    axios.post('/api/v2/province/getProvinces',{id:this.region_id})
                    .then(response => {
                        if(response.data.success==true){
                        this.provinces = response.data.data;
                        }
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
                },
                changeProvince(){
                    axios.post('/api/v2/city/getCities',{id:this.province_id})
                    .then(response => {
                        if(response.data.success==true){
                            this.cities = response.data.data;
                        }
                    })
                    .catch(error => {
                        console.log(error.response.data);
                    });
                },
              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'neighborhoods.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'neighborhoods.name',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'neighborhoods.city_id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },

                              save0(){


                    if(!this.validateForm()){ this.loading = false; return; }

                    if(false) {
                        this.loading = false;

                        return;
                    }
                    swal("Voulez-vous vraiment ajouter ou modifier ce quartier?", {
                        buttons: ["Non", "Oui"],
                    }).then((val)=>{

                        if(val===true){

                            this.save();
                        }
                    });

                    },

                save() {
                    // create
                    this.loading = true;
                    this.errors = {};

                    if(!this.validateForm()){ this.loading = false; return; }

                    if (this.datatable.selected.id) {
                        // post to /api/v2/standings/update
                        axios.post('/api/v2/neighborhood/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                    this.region_id = null;
                                    this.province_id = null;
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    city_id: null,
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("Le quartier a été modifiée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                console.log(error);
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                    // update
                    else {
                        // post to /api/v2/standings/create
                        axios.post('/api/v2/neighborhood/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                    this.region_id = null;
                                    this.province_id = null;
                                  this.datatable.selected = {
                                    id: null,
                                    name: '',
                                    coordinates: '',
                                    city_id: null,
                                  };
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("Le quartier a été ajoutée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    console.log(error);
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },

                deleteNeighborhood(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer ce quartier ?",
                                text: "Vous allez supprimer le quartier séléctionné",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/neighborhood/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("Le quartier a été supprimé avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object')
                                    this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                }
                            });


                },

                selectedChanged(selected) {
                    if (selected)
                        this.datatable.selected = {
                            ...selected
                        };
                    else{
                        this.region_id = null;
                        this.province_id = null;
                        this.datatable.selected = {
                            id: null,
                            name: '',
                            coordinates: '',
                            city_id: null,
                        }
                    }

                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.name || this.form.name.trim()==""){
                      if(!this.errors.name) this.errors.name = [];
                      this.errors.name.push('Le champ nom est requis.');
                      r = false;
                  }
                  if(!this.form.city_id){
                      if(!this.errors.city_id) this.errors.city_id = [];
                      this.errors.city_id.push('Le champ ville est requis.');
                      r = false;
                  }
                  return r;
                }
            }
        }).mount('#pills-neighborhoods');


    </script>

@endsection
