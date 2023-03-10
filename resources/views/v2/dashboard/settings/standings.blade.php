@extends('v2.layouts.dashboard')

@section('title', 'Standings')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Standings : </h1>
    </div>
    <section id="app">
        <div class="row ">


            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body needs-validation" ref="div1">

                        <h5 class="card-title">Ajouter ou modifier un standing : </h5>

                        <div class="form-group" hidden>
                            <label for="my-select"><small>Id :</small></label>
                            <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Désignation :</small></label>
                            <input class="form-control" :class="errors.designation ? 'is-invalid' : ''" type="text"
                                v-model="form.designation" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.designation?.join('<br/>') }}
                            </div>
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
                                </select>
                            </div>
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="Rechercher"
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
                                <button class="btn p-0 m-0 me-2" @click="deleteStanding(props.row.value)">
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
        // id
        // ads_nbr
        // ltc_nbr
        // vocal
        // price
        // duration
        // description

        let app = Vue.createApp({
            data() {

                return {
                    search: '',
                    parents : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'standings_datatable',
                        api: '/api/v2/standings/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Désignation',
                                field: 'designation',
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
                        designation: '',
                    },
                }
            },
            watch: {
                'datatable.selected': function(val) {
                    this.form = {
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

              loadFilter(){

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'designation',
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
                    swal("Voulez-vous vraiment ajouter ou modifier ce standing?", {
                        buttons: ["Non", "Oui"],
                    })
                    .then((val)=>{

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
                        axios.post('/api/v2/standings/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    designation: '',
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("Le standing a été modifiée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
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
                        axios.post('/api/v2/standings/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    designation: '',
                                  }
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("Le standing a été ajoutée avec succès",'#0f5132');
                                }
                            })
                            .catch(error => {
                                this.loading = false;
                                if(typeof error.response.data.error === 'object') this.errors = error.response.data.error;
                                else{
                                    var err = error.response.data.error;
                                    displayToast(err,'#842029');
                                }
                            });
                    }
                },



                deleteStanding(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer ce standing ?",
                                text: "Vous allez supprimer le standing séléctionné",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/standings/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("Le standing a été supprimé avec succès",'#0f5132');
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
                          designation: '',
                        }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.designation || this.form.designation.trim()==""){
                      if(!this.errors.designation) this.errors.designation = [];
                      this.errors.designation.push('Le champ désignation est requis.');
                      r = false;
                  }
                  return r;
                }
            }
        }).mount('#app')
    </script>

@endsection
