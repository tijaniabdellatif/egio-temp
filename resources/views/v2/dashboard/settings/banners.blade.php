@extends('v2.layouts.dashboard')

@section('title', 'Gestion des bannières')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Gestion des bannières</h1>
    </div>
    <section class="section" id="app">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label" style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="Rechercher"
                                        @keyup.enter="datatableSearch()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component :datatable="datatable" @loaded="datatableLoaded">
                            <template #active="props">
                                <div :class="props.column.value==1?'status_box s_10':'status_box s_70'">@{{props.column.value==1?'actif':'inactif'}}</div>
                            </template>
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" @click="showEdit(props.row.value)">
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
            <h5 class="modal-title">Modifier Bannière : @{{formEdit.posistion}}</h5>
            <button type="button" class="btn-close" @click="hideEdit"></button>
            </div>
            <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                <div class="row mb-3">
                    <label for="inputLoc" class="col-sm-4 col-form-label">HTML code</label>
                    <div class="col-sm-8 ">
                        <textarea  class="form-control" v-model="formEdit.html_code"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">État</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" v-model="formEdit.active">
                      <label class="form-check-label">
                        Actif
                      </label>
                    </div>
                  </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="hideEdit">Close</button>
                <button id="updatePlace" type="submit" class="btn btn-primary" @click="save()">Modifier</button>
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
        let bannerapp = Vue.createApp({
            data() {
                return {
                    search: '',
                    filter: {},
                    users: [],
                    posistions:['Billboard','left_side_banner','right_side_banner'],
                    editModal: false,
                    globalloader: false,
                    formEdit:{
                        id:null,
                        posistion:'',
                        html_code:'',
                        active: false,
                    },
                    datatable: {
                        key: 'banners_datatable',
                        api: '/api/v2/banners/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Type de bannière',
                                field: 'position',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: "État",
                                field: 'active',
                                type: 'custom',
                                sortable: false,
                                searchable: false,
                                customize: true
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
                            per_page: 20,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            pagination_buttons: true
                        },
                    }
                }
            },
            components: {
                'DatatableComponent': DatatableComponent
            },
            watch: {
                filter: {
                    handler(value) {
                        // this.datatable.filters = [{
                        //     type: 'where',
                        //     col: 'id',
                        //     val: `%${this.search}%`,
                        //     op: 'LIKE'
                        // }];
                    },
                    deep: true
                }
            },
            mounted() {},
            methods: {
                datatableSearch() {
                    this.loadFilter();
                },
                datatableLoaded(rows) {

                    // collect all is_user from rows
                    /*let users = [];

                    rows.forEach(row => {
                        users.push(row.id_user);
                    });

                    // get all users
                    let laravelWhere = [];
                    // users.forEach((user, key) => {
                    //     laravelWhere.push({
                    //         type: key == 0 ? 'where' : 'orWhere',
                    //         col: 'id',
                    //         val: user,
                    //         op: '='
                    //     });
                    // });

                    // convert laravelObject to json string
                    let laravelWhereJson = JSON.stringify(laravelWhere);

                    formData = new FormData();
                    formData.append('where', laravelWhereJson);

                    var config = {
                        method: 'post',
                        url: `/api/v2/user/filter`,
                        data: formData
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success) {
                                this.users = response.data.data;
                                console.log(this.users);
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });*/

                },
                showEdit(data){
                    this.editModal = true;
                    this.formEdit = data;
                    this.formEdit.active = data.active==true||data.active==1?true:false;
                },
                hideEdit(){
                    this.editModal = false;
                    this.formEdit = {
                        id:null,
                        posistion:'',
                        html_code:'',
                        active: false,
                    };
                },
                save(){
                    if(this.formEdit.id){
                        var config = {
                            method: 'post',
                            url: `/api/v2/banners/edit`,
                            data: this.formEdit
                        };
                        console.log(this.formEdit);
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
                                col: 'posistion',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                        ]
                    }];
                }
            },
        }).mount('#app')
    </script>

@endsection
