@extends('v2.layouts.dashboard')

@section('title', 'Catalogue des plans d\'abonnement')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Plans : </h1>
    </div>
    <section id="app">
        <div class="row ">


            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body needs-validation" ref="div1">

                        <h5 class="card-title">Ajouter ou modifier des plans : </h5>
                        <div class="form-group" hidden>
                            <label for="my-select"><small>Id :</small></label>
                            <input disabled type="text" v-model="plan.id" name="" id=""
                                class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label><small>Nombre d'annonces :</small></label>
                            <input class="form-control" :class="error.ads_nbr ? 'is-invalid' : ''" type="number"
                                v-model="plan.ads_nbr" min="1">
                            <div class="invalid-feedback">
                               <span v-html="error.ads_nbr?.join('<br/>')"></span>
                            </div>
                        </div>
                        <div class="form-group mt-2">
                            <label><small>Coins (Listcoins) :</small></label>
                            <div class="input-group">
                            <input type="number" class="form-control" :class="error.ltc_nbr ? 'is-invalid' : ''"
                                v-model="plan.ltc_nbr" min="1">
                                <span class="input-group-text">LTC</span>
                            <div class="invalid-feedback">
                               <span v-html="error.ltc_nbr?.join('<br/>')"></span>
                            </div>
                        </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Prix :</small></label>
                            <div class="input-group">
                            <input type="number" class="form-control" :class="error.price ? 'is-invalid' : ''"
                                v-model="plan.price" min="1">
                                <span class="input-group-text">DHS</span>
                            <div class="invalid-feedback">
                               <span v-html="error.price?.join('<br/>')"></span>
                            </div>
                        </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Duration :</small></label>
                            <div class="input-group">
                            <input type="number" class="form-control" :class="error.duration ? 'is-invalid' : ''"
                                v-model="plan.duration" v-model="plan.price" min="1">
                                <span class="input-group-text">Jours</span>
                            <div class="invalid-feedback">
                               <span v-html="error.duration?.join('<br/>')"></span>
                            </div>
                        </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Description :</small></label>
                            <textarea class="form-control" :class="error.description ? 'is-invalid' : ''" v-model="plan.description" rows="3"
                                required></textarea>
                            <div class="invalid-feedback">
                               <span v-html="error.description?.join('<br/>')"></span>
                            </div>
                        </div>
                        <div class="d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2"
                                @click="save()">
                                Sauvegarder
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <hr>
                        <datatable-component ref="datatable" :datatable="datatable" @loaded="datatableLoaded"
                            @selectedchanged="selectedChanged">
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" title="Edit" @click="edit(props.row.value),goto('div1')">
                                    <i class="fas fa-edit text-success"></i>
                                </button>
                                <button class="d-none btn p-0 m-0"  >
                                    <i class="fa-solid fa-circle-minus text-danger"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" @click="deletePlan(props.row.value)">
                                    <i class="fas fa-trash-can text-success"></i>
                                </button>
                            </template>
                        </datatable-component>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        let app = Vue.createApp({
            data() {

                return {
                    search: '',
                    error: {},
                    loading: false,
                    datatable: {
                        key: 'table_plans',
                        api: '/api/v2/plans/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'ads',
                                field: 'ads_nbr',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'ltc',
                                field: 'ltc_nbr',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'prix',
                                field: 'price',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'durée',
                                field: 'duration',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'description',
                                field: 'description',
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
                            per_page: 6,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                        selectable: true,
                        selected: {}
                    },
                    plan: {
                        id: '',
                        ads_nbr: '',
                        ltc_nbr: '',
                        price: '',
                        duration: '',
                        description: ''
                    },
                }
            },
            watch: {
                'datatable.selected': function(val) {
                    this.plan = {
                        ...val
                    };
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            mounted() {},
            methods: {

                // Scroll to form in modification
                goto(refName) {
             var element = this.$refs[refName];
             var top = element.offsetTop;

              window.scrollTo(0, top);
                },


                save() {

                    if (this.datatable.selected.id) {

                        if(!this.updatePlanValidation()){
                            return;
                        }

                        // post to /api/v2/plans/update
                        swal({
                                title: "Voulez-vous vraiment modifier ce plan ?",
                                text: "Vous allez mettre à jour le plan sélectionné",
                                icon: "warning",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willUpdate) => {
                                if (willUpdate) {

                                    // create
                                    this.loading = true;
                                    this.error = {};


                                    axios.post('/api/v2/plans/update', this.plan)
                                        .then(response => {
                                            this.loading = false;
                                            console.log(response.data);
                                            this.datatable.selected = {
                                                id: '',
                                                ads_nbr: '',
                                                ltc_nbr: '',
                                                price: '',
                                                duration: '',
                                                description: ''
                                            }
                                            this.$refs.datatable.loadData();

                                            // displayToast("Plan mis à jour avec succès","green");
                                            swal("Plan mis à jour avec succès", {
                                                icon: "success"
                                            });

                                        })
                                        .catch(error => {
                                            this.loading = false;
                                            this.error = error.response.data.error;
                                            console.log(error.response.data);
                                        });

                                }
                            });

                    }
                    // update
                    else {

                        if(!this.createPlanValidation()){
                            return;
                        }

                        // post to /api/v2/plans/create
                        axios.post('/api/v2/plans/create', this.plan)
                            .then(response => {
                                this.loading = false;

                                console.log(response.data);
                                this.datatable.selected = {
                                    id: '',
                                    ads_nbr: '',
                                    ltc_nbr: '',
                                    price: '',
                                    duration: '',
                                    description: ''
                                }
                                this.$refs.datatable.loadData();

                                // displayToast("Plan créé avec succès","green");
                                swal({
                                    title: "Le plan a été créé avec succès",
                                    text: "",
                                    icon: "success",
                                    button: false,
                                    timer: 3000
                                });
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.error = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },

                deletePlan(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer ce plan ?",
                                text: "Vous allez supprimer le plan séléctionné",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/plans/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("Le plan a été supprimé avec succès",'#0f5132');
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
                            id: '',
                            ads_nbr: '',
                            ltc_nbr: '',
                            price: '',
                            duration: '',
                            description: ''
                        }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                updatePlanValidation(){

                    this.error = {};

                    if(this.plan.ads_nbr === ''){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre d'annonces");
                    }

                    // check if number
                    if(isNaN(this.plan.ads_nbr)){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre");
                    }
                    // check ads_nbr is greater than 0
                    if(this.plan.ads_nbr <= 0){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre supérieur à 0");
                    }


                    // check if coins is empty
                    if(this.plan.ltc_nbr === ''){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre de coins");
                    }
                    // check if number
                    if(isNaN(this.plan.ltc_nbr)){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre");
                    }
                    // check ltc_nbr is greater than 0
                    if(this.plan.ltc_nbr <= 0){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    // check if price is empty
                    if(this.plan.price === ''){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un prix");
                    }
                    // check if number
                    if(isNaN(this.plan.price)){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un nombre");
                    }
                    // check price is greater than 0
                    if(this.plan.price <= 0){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    // check if duration is empty
                    if(this.plan.duration === ''){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer une durée");
                    }
                    // check if number
                    if(isNaN(this.plan.duration)){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer un nombre");
                    }
                    // check duration is greater than 0
                    if(this.plan.duration <= 0){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    if(Object.keys(this.error).length === 0){
                        return true;
                    }

                    return false;

                },
                createPlanValidation(){

                    this.error = {};

                    if(this.plan.ads_nbr === ''){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre d'annonces");
                    }

                    // check if number
                    if(isNaN(this.plan.ads_nbr)){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre");
                    }

                    // check ads_nbr is greater than 0
                    if(this.plan.ads_nbr <= 0){
                        if(!Array.isArray(this.error.ads_nbr)){
                            this.error.ads_nbr = [];
                        }

                        this.error.ads_nbr.push("Veuillez entrer un nombre supérieur à 0");
                    }


                    // check if coins is empty
                    if(this.plan.ltc_nbr === ''){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre de coins");
                    }
                    // check if number
                    if(isNaN(this.plan.ltc_nbr)){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre");
                    }
                    // check ltc_nbr is greater than 0
                    if(this.plan.ltc_nbr <= 0){
                        if(!Array.isArray(this.error.ltc_nbr)){
                            this.error.ltc_nbr = [];
                        }

                        this.error.ltc_nbr.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    // check if price is empty
                    if(this.plan.price === ''){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un prix");
                    }
                    // check if number
                    if(isNaN(this.plan.price)){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un nombre");
                    }
                    // check price is greater than 0
                    if(this.plan.price <= 0){
                        if(!Array.isArray(this.error.price)){
                            this.error.price = [];
                        }

                        this.error.price.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    // check if duration is empty
                    if(this.plan.duration === ''){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer une durée");
                    }
                    // check if number
                    if(isNaN(this.plan.duration)){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer un nombre");
                    }
                    // check duration is greater than 0
                    if(this.plan.duration <= 0){
                        if(!Array.isArray(this.error.duration)){
                            this.error.duration = [];
                        }

                        this.error.duration.push("Veuillez entrer un nombre supérieur à 0");
                    }

                    if(Object.keys(this.error).length === 0){
                        return true;
                    }

                    return false;
                }
            }
        }).mount('#app')
    </script>

@endsection
