@extends('v2.layouts.dashboard')

@section('title', 'Catégories')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Catégories : </h1>
    </div>
    <section id="app">
        <div class="row ">


            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body needs-validation" ref="div1">

                        <h5 class="card-title">Ajouter ou modifier une catégorie : </h5>

                        <div class="form-group" hidden>
                            <label for="my-select"><small>Id :</small></label>
                            <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Titre :</small></label>
                            <input class="form-control" :class="errors.title ? 'is-invalid' : ''" type="text"
                                v-model="form.title" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.title?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Parent :</small></label>
                            <select class="form-select select2init" :class="errors.parent_cat ? 'is-invalid' : ''" v-model="form.parent_cat" >
                              <option :value="null">Choisir une catégorie</option>
                              <option v-for="v in parents" :value="v.id">@{{v.title}}</option>
                            </select>
                            <div class="invalid-feedback">
                                @{{ errors.parent_cat?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Type :</small></label>
                            <select class="form-select select2init" :class="errors.type ? 'is-invalid' : ''" v-model="form.type" >
                              <option :value="null">Choisir un type</option>
                              <option v-for="v in types" :value="v">@{{v}}</option>
                            </select>
                            <div class="invalid-feedback">
                                @{{ errors.type?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>État :</small></label>
                            <select class="form-select select2init" :class="errors.status ? 'is-invalid' : ''" v-model="form.status" >
                              <option v-for="v in status_arr" :value="v.val">@{{v.desc}}</option>
                            </select>
                            <div class="invalid-feedback">
                                @{{ errors.status?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Keywords :</small></label>
                            <textarea class="form-control"
                                v-model="form.keywords">
                            </textarea>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>C'est un projet ? &nbsp</small></label>
                            <input class="form-check-input" type="checkbox" name="is_project" id="is_project" v-model="form.is_project">
                            <label class="form-check-label" for="gridCheck2">
                            <div class="invalid-feedback">
                                @{{ errors.status?.join('<br/>') }}
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
                            <button type="submit" class="btn btn-secondary d-flex align-center justify-content-center mt-2" :disabled="kloading"
                                @click="syncKeywords()">
                                Apply keywords
                                <div class="spinner-border spinner-border-sm ms-2" v-if="kloading" role="status">
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
                            <template #status="props">
                                <div :class="'status_box s_'+props.column.value">@{{status_obj[props.column.value]}}</div>
                            </template>
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="edit(props.row.value),goto('div1')">
                                    <i class="fas fa-edit text-success"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" @click="deleteCat(props.row.value)">
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
                    status_obj : status_obj,
                    status_arr : status_arr2,
                    types: cat_types,
                    parents : [],
                    errors: {},
                    errorText: '',
                    loading: false,
                    kloading: false,
                    filter: {
                        status:null,
                    },
                    datatable: {
                        key: 'cats_datatable',
                        api: '/api/v2/cats/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Titre',
                                field: 'title',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Parent',
                                field: 'parent',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Type',
                                field: 'type',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "État",
                                field: 'status',
                                type: 'custom',
                                sortable: true,
                                searchable: true,
                                customize: true
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
                        title: '',
                        parent_cat: null,
                        type: null,
                        status: '10',
                        keywords:'',
                        is_project:false
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
            mounted() {
              axios.post('/api/v2/cats/getParents')
                .then(response => {
                    if(response.data.success==true){
                      this.parents = response.data.data;
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
                                col: 'cats.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cats.title',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cats.parent_cat',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cats.type',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                    if(this.filter.status)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'cats.status',
                            val: `${this.filter.status}`,
                            op: '='
                        });
                },

                // Confirmation before add a new cat
                save0(){

                    if(!this.validateForm()){ this.loading = false; return; }

                    if(false) {
                        this.loading = false;

                        return;
                    }
                    swal("Voulez-vous vraiment ajouter ou modifier cette catégorie?", {
                        buttons: ["Non", "Oui"],
                    }).then((val)=>{

                        if(val===true){

                            this.save();
                        }
                    });

                },
                syncKeywords(){
                    this.kloading = true;
                    if (!this.datatable.selected.id) {
                        /*axios.post('/api/v2/items/SyncAdsKeywordsByCategory', {catid:this.datatable.selected.id})
                            .then(response => {
                                this.kloading = false;
                                displayToast("done",'#0f5132');
                            })
                            .catch(error => {
                                this.kloading = false;
                                displayToast('error','#842029');
                            });*/
                            swal("Sélectionner une catégorie");
                    }
                    else{
                        axios.post('/api/v2/items/SyncAdsKeywordsByCategory', {catid:this.datatable.selected.id})
                            .then(response => {
                                this.kloading = false;
                                displayToast("done",'#0f5132');
                            })
                            .catch(error => {
                                this.kloading = false;
                                displayToast('error','#842029');
                            });
                    }
                },
                save() {
                    // create
                    this.loading = true;
                    this.errors = {};

                    if(!this.validateForm()){ this.loading = false; return; }

                    if (this.datatable.selected.id) {
                        // post to /api/v2/cats/update
                        axios.post('/api/v2/cats/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    title: '',
                                    parent_cat: null,
                                    type: null,
                                    status: '10',
                                    keywords: '',
                                    is_project:false,
                                  }
                                  for(let i =0 ; i < this.datatable.rows.length ; i++){
                                      if(this.datatable.rows[i].id==response.data.data.id){
                                        this.datatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("La catégorie a été modifiée avec succès",'#0f5132');
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
                        // post to /api/v2/cats/create
                        axios.post('/api/v2/cats/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.datatable.selected = {
                                    id: null,
                                    title: '',
                                    parent_cat: null,
                                    type: null,
                                    status: '10',
                                    keywords: '',
                                    is_project:false
                                  }
                                  //this.datatable.rows.unshift(response.data.data);
                                  this.datatable.rows.push(response.data.data);
                                  displayToast("La catégorie a été ajoutée avec succès",'#0f5132');
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

                 deleteCat(value) {

                    swal({
                                title: "Voulez-vous vraiment supprimer cette catégorie ?",
                                text: "Vous allez supprimer la catégorie séléctionnée",
                                icon: "error",
                                buttons: true,
                                dangerMode: true
                            })
                            .then((willDelete) => {
                                if (willDelete) {

                    if (value.id) {
                        // post to /api/v2/standings/delete
                        axios.post('/api/v2/cats/delete', value)
                            .then(response => {
                                this.loading = false;
                                console.log(response);
                                if(response.data.success==true){

                                    const index = this.datatable.rows.indexOf(value);
                                    if (index > -1) { // only splice array when item is found
                                        this.datatable.rows.splice(index, 1); // 2nd parameter means remove one item only
                                    }

                                    displayToast("La catégorie a été supprimé avec succès",'#0f5132');
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
                          title: '',
                          parent_cat: null,
                          type: null,
                          status: '10',
                          keywords: '',
                          is_project:false
                        }
                },
                edit(value) {
                    this.datatable.selected = value;
                },
                validateForm(){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.title || this.form.title.trim()==""){
                      if(!this.errors.title) this.errors.title = [];
                      this.errors.title.push('Le champ titre est requis');
                      r = false;
                  }
                  if(this.form.parent_cat==null){
                      if(!this.errors.parent_cat) this.errors.parent_cat = [];
                      this.errors.parent_cat.push('Le champ catégorie parent est requis.');
                      r = false;
                  }
                  if(this.form.type==null){
                      if(!this.errors.type) this.errors.type = [];
                      this.errors.type.push('Le champ type est requis.');
                      r = false;
                  }
                  return r;
                }


            }
        }).mount('#app')
    </script>

@endsection
