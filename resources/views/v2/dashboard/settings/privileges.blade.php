@extends('v2.layouts.dashboard')

@section('title', 'Privilèges')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

<div class="pagetitle">
        <h1>Gestion des Privilèges</h1>
    </div>
    <section class="section" id="app">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="Search"
                                        @keyup.enter="datatableSearch()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component :datatable="datatable" @loaded="datatableLoaded">
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="showEdit(props.row.value)">
                                    <i class="fas fa-edit text-success"></i>
                                </button>
                            </template>
                        </datatable-component>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ajouter ou modifier un utilisateur : </h5>

                        <div class="form-group" hidden>
                            <label for="my-select"><small>Id :</small></label>
                            <input disabled type="text" v-model="form.id" name="" id="" class="form-control">
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Prénom :</small></label>
                            <input class="form-control" :class="errors.firstname ? 'is-invalid' : ''" type="text"
                                v-model="form.firstname" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.firstname?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Nom :</small></label>
                            <input class="form-control" :class="errors.lastname ? 'is-invalid' : ''" type="text"
                                v-model="form.lastname" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.lastname?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Nom d'utlisateur :</small></label>
                            <input class="form-control" :class="errors.username ? 'is-invalid' : ''" type="text"
                                v-model="form.username" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.username?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Email :</small></label>
                            <input class="form-control" :class="errors.email ? 'is-invalid' : ''" type="text"
                                v-model="form.email" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.email?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Type d'utilisateur :</small></label>
                            <select class="form-select select2init" :class="errors.usertype ? 'is-invalid' : ''" v-model="form.usertype" >
                            <option :value="null">Choisir un type</option>
                            <option v-for="u in user_types" :value="u.id">@{{u.designation}}</option>
                            </select>
                            <div class="invalid-feedback">
                                @{{ errors.usertype?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Mot de passe :</small></label>
                            <input class="form-control" :class="errors.password ? 'is-invalid' : ''" type="password" :disabled="form.id"
                                v-model="form.password" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.password?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <label><small>Confirmer le mot de passe :</small></label>
                            <input class="form-control" :class="errors.cpassword ? 'is-invalid' : ''" type="password" :disabled="form.id"
                                v-model="form.cpassword" min="1">
                            <div class="invalid-feedback">
                                @{{ errors.cpassword?.join('<br/>') }}
                            </div>
                        </div>

                        <div class="d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2" :disabled="loading"
                                @click="saveuser()">
                                Sauvegarder
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <hr>
                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class=" col-sm-2">
                                <select v-model="userdatatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                    <option :value="10">10</option>
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="250">250</option>
                                </select>
                            </div>
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="usersearch" placeholder="Search"
                                        @keyup.enter="userdatatableSearch()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component :datatable="userdatatable" @loaded="userdatatableLoaded" @selectedchanged="selectedChanged">
                            <template #user_type_designation="props" >
                                <span v-if="props.column.value == 'admin'" class="rounded-text" style="background: 	#ecbd25" >@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'moderator manager'" class="rounded-text" style="background: 	#f0f016" >@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'moderator'" class="rounded-text" style="background: 	#f0f016" >@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'comercial'" class="rounded-text" style="background: #bdeb34" >@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'ced manager'" class="rounded-text" style="background: 	#20c153" >@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'ced'" class="rounded-text" style="background: 	#20c153" >@{{ props.column.value }}</span>
                                <span v-else="" class="rounded-text" style="background: #262626" >@{{ props.column.value??'-' }}</span>
                            </template>
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="edituser(props.row.value)">
                                    <i class="fas fa-edit text-success"></i>
                                </button>
                            </template>
                        </datatable-component>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
    <div class="modal" style="display: block;" v-if="editModal" @click.self="hideEdit">
        <div class="modal-dialog modal-lg">
        <form onsubmit="event.preventDefault()" id="updatePlaceForm" class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Modifier le Privilège de type : @{{formEdit.name}}</h5>
            <button type="button" class="btn-close" @click="hideEdit"></button>
            </div>
            <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><small>Actions :</small></label>
                            <div class="columns-editor-container">
                                <div v-for="d of data" class="column-editor-item">
                                    <input type="checkbox" class="me-2" name="checkbox" id="checkbox" v-model="d.checked">
                                    <div class="column-editor-item-name">@{{d.name}}</div>
                                    <!-- checkbox show/hide -->
                                </div>
                            </div>
                        </div>
                   </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="hideEdit">Fermer</button>
                <button id="updatePlace" type="submit" class="btn btn-primary" @click="save()">Enregistrer</button>
            </div>
        </form>
        </div>
        </div>
        <!-- End Edit Modal-->

        <div v-if="globalloader==true" id="globalLoader" class="globalLoader">
            <div style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Operation en cours...</div>
            </div>
        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        var appPrivileges = Vue.createApp({
            data() {
                return {
                    search: '',
                    errors:{},
                    usersearch: '',
                    filter: {},
                    users: [],
                    user_types: [],
                    editModal: false,
                    globalloader: false,
                    modalLoader: false,
                    loading:false,
                    formEdit:{
                        id:null,
                        name:'',
                    },
                    form:{
                        id: null,
                        firstname: '',
                        lastname: '',
                        username: '',
                        email: '',
                        usertype: null,
                        password: '',
                        cpassword: '',
                    },
                    data:[],
                    datatable: {
                        key: 'banners_datatable',
                        api: '/api/v2/privillege/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: "Type d'utilisateur",
                                field: 'name',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'custom',
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
                            enabled: false,
                            page: 1,
                            per_page: 2000,
                            total: 0,
                            links: []
                        }
                    },
                    userdatatable: {
                        key: 'banners_datatable',
                        api: '/api/v2/internUsers/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: "Nom d'utilisateur",
                                field: 'username',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Type d\'utilisateur',
                                field : 'user_type_designation',
                                type : 'string',
                                sortable : true,
                                searchable : true,
                                hide : false,
                                customize : true
                            },
                            {
                                name: 'Action',
                                field: 'action',
                                type: 'custom',
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
                    }
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            watch: {
                'userdatatable.selected': function(val) {
                    val.password = "";
                    this.form = {
                        ...val
                    };
                }
            },
            mounted() {
                axios.get('/api/v2/user/types').then(response => {
                    if (response.data.data) {
                        this.user_types = [];
                        for(const d of response.data.data){
                            if(d.role_id != null) this.user_types.push(d);
                        }
                    }
                }).catch(error => {
                    console.log(error);
                });
            },
            methods: {
                datatableSearch() {
                    this.loadFilter();
                },
                userdatatableSearch() {
                    this.userloadFilter();
                },
                datatableLoaded(rows) {
                },
                userdatatableLoaded(rows) {
                },
                showEdit(data){
                    this.editModal = true;
                    this.formEdit.id = data.id;
                    this.formEdit.name = data.name;
                    var config = {
                        method: 'post',
                        url: `/api/v2/privillege/GetPermmissionsByRole`,
                        data: {id:this.formEdit.id}
                    };
                    this.modalLoader = true;
                    this.data = [];
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                 for(const d of response.data.data){
                                     this.data.push({id:d.id,name:d.name,checked:d.checked==1?true:false});
                                 }
                                 console.log(this.data);
                                this.modalLoader = false;
                            }
                        })
                        .catch((error) => {
                            this.modalLoader = false;
                            var err = error.response.data.error;
                            displayToast(err,'#842029');
                        });
                },
                hideEdit(){
                    this.editModal = false;
                    this.formEdit = {
                        id:null,
                        name:'',
                    };
                },
                save(){
                    if(this.formEdit.id){
                        var config = {
                            method: 'post',
                            url: `/api/v2/privillege/addPermissions`,
                            data: {id:this.formEdit.id,data:this.data}
                        };
                        this.globalloader = true;
                        axios(config)
                            .then((response) => {
                                if (response.data.success == true) {
                                    this.hideEdit();
                                    this.loadFilter();
                                    this.globalloader = false;
                                }
                            })
                            .catch((error) => {
                                this.globalloader = false;
                                var err = error.response.data.error;
                                displayToast(err,'#842029');
                            });
                    }
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
                                col: 'name',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },
                userloadFilter(){
                    this.userdatatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'username',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'fisrtname',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'lastname',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                },
                saveuser() {
                    // create
                    this.loading = true;
                    this.errors = {};



                    if (this.userdatatable.selected.id) {
                        if(!this.validateForm(false)){ this.loading = false; return; }
                        // post to /api/v2/standings/update
                        axios.post('/api/v2/internuser/update', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.userdatatable.selected = {
                                    id: null,
                                    firstname: '',
                                    lastname: '',
                                    username: '',
                                    email: '',
                                    usertype: null,
                                    password: '',
                                    cpassword: '',
                                  }
                                  for(let i =0 ; i < this.userdatatable.rows.length ; i++){
                                      if(this.userdatatable.rows[i].id==response.data.data.id){
                                        this.userdatatable.rows[i] = response.data.data;
                                      }
                                  }
                                  displayToast("L'utilisateur a été modifiée avec succès",'#0f5132');
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
                        if(!this.validateForm(true)){ this.loading = false; return; }
                        // post to /api/v2/standings/create
                        axios.post('/api/v2/internuser/create', this.form)
                            .then(response => {
                                this.loading = false;
                                if(response.data.success==true){
                                  this.userdatatable.selected = {
                                    id: null,
                                    firstname: '',
                                    lastname: '',
                                    username: '',
                                    email: '',
                                    usertype: null,
                                    password: '',
                                    cpassword: '',
                                  }
                                  this.userdatatable.rows.unshift(response.data.data);
                                  //this.userdatatable.rows.push(response.data.data);
                                  displayToast("L'utilisateur a été ajoutée avec succès",'#0f5132');
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
                selectedChanged(selected) {
                    selected.password = "";
                    if (selected)
                        this.userdatatable.selected = {
                            ...selected
                        };
                    else
                        this.userdatatable.selected = {
                            id: null,
                            firstname: '',
                            lastname: '',
                            username: '',
                            email: '',
                            usertype: null,
                            password: '',
                            cpassword: '',
                        }
                },
                edituser(value) {
                    this.userdatatable.selected = value;
                },
                validateForm(e=true){
                    this.errors=[];
                    window.scrollTo(0,0);
                  let r = true;
                  if(!this.form.firstname || this.form.firstname.trim()==""){
                      if(!this.errors.firstname) this.errors.firstname = [];
                      this.errors.firstname.push('The firstname field is required.');
                      r = false;
                  }
                  if(!this.form.lastname || this.form.lastname.trim()==""){
                      if(!this.errors.lastname) this.errors.lastname = [];
                      this.errors.lastname.push('The lastname field is required.');
                      r = false;
                  }
                  if(!this.form.username || this.form.username.trim()==""){
                      if(!this.errors.username) this.errors.username = [];
                      this.errors.username.push('The username field is required.');
                      r = false;
                  }
                  if(!this.form.email || this.form.email.trim()==""){
                      if(!this.errors.email) this.errors.email = [];
                      this.errors.email.push('The email field is required.');
                      r = false;
                  }
                  if(e){
                    if(!this.form.password || this.form.password.trim()==""){
                        if(!this.errors.password) this.errors.password = [];
                        this.errors.password.push('The password field is required.');
                        r = false;
                    }
                    if(!this.form.password == this.form.cpassword){
                        if(!this.errors.cpassword) this.errors.cpassword = [];
                        this.errors.cpassword.push('The passwords must be the same.');
                        r = false;
                    }
                   }
                  return r;
                }
            },
        }).mount('#app')
    </script>

@endsection
