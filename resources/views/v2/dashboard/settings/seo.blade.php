@extends('v2.layouts.dashboard')

@section('title', 'SEO')

@section('custom_head')

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

@endsection

@section('content')

    <section id="app" class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Seo</h5>

                        <div class="mb-3">
                            <label for="" class="form-label">Univers :</label>
                            <select class="form-select" v-model="type" aria-label="Default select example">
                                <option value="">Aucun</option>
                                <option v-for="(mt,key) in multilist_types" :value="mt">@{{ mt.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Couleur principale :</label>
                            <input type="color" v-model="type.main_color" class="form-control" name=""
                                id="" aria-describedby="helpId" placeholder="">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Couleur secondaire :</label>
                            <input type="color" v-model="type.secondary_color" class="form-control" name=""
                                id="" aria-describedby="helpId" placeholder="">
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Troisième couleur :</label>
                            <input type="color" v-model="type.third_color" class="form-control" name=""
                                id="" aria-describedby="helpId" placeholder="">
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Logo :</label>
                            <upload-files-component v-model:files="logo_image" type="images" :multiple="false"
                                :max="1" :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                :multiple="true" />
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Icone :</label>
                            <upload-files-component v-model:files="icon_image" type="images" :multiple="false"
                                :max="1" :allowed-extensions="['ico']" :multiple="true" />
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Contexte principal :</label>
                            <upload-files-component v-model:files="main_bg_image" type="images" :multiple="false"
                                :max="1" :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                :multiple="true" />
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Meta Title :</label>
                            <textarea class="form-control" v-model="type.meta_tags" name="" id="" rows="3"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="" class="form-label">Meta Description :</label>
                            <textarea class="form-control" v-model="type.meta_desc" name="" id="" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 d-flex justify-content-center" @click="save">

                            <div v-if="loadings.save" class="spinner-border spinner-border-sm me-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>

                            Sauvegarder
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        // Changer meta title pour chaque univer
        // Changer logo pour chaque univer
        // Changer les couleurs de chaque univer
        // Changer fav icon de  chaque univer
        // Changer Meta dec pour chaque univer
        // Google analytics pour chaque univer
        // Google site code pour chaque univer
        // Ajouter additional script on head pour chaque univer

        let app = Vue.createApp({
            data() {
                return {
                    loadings: {
                        save: false
                    },
                    type: {
                        name: '',
                        main_color: '',
                        secondary_color: '',
                        third_color: '',
                        logo: '',
                        icon: '',
                        main_bg: '',
                        meta_tags: '',
                        meta_desc: '',
                    },
                    multilist_types: [{
                            name: 'multilist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                        {
                            name: 'booklist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                        {
                            name: 'homelist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                        {
                            name: 'primelist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                        {
                            name: 'landlist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                        {
                            name: 'officelist',
                            main_color: '',
                            secondary_color: '',
                            third_color: '',
                            logo: '',
                            icon: '',
                            main_bg: '',
                            meta_tags: '',
                            meta_desc: '',
                        },
                    ],
                    logo_image: [],
                    icon_image: [],
                    main_bg_image: [],
                }
            },
            watch: {
                logo_image() {
                    this.type.logo = this.logo_image[0];
                },
                icon_image() {
                    this.type.icon = this.icon_image[0];
                },
                main_bg_image() {
                    this.type.main_bg = this.main_bg_image[0];
                },
                /* watch multilist_types deeply */
                multilist_types: {
                    handler(newVal, oldVal) {
                        // this.type = newVal[0];
                    },
                    deep: true
                },
                /* watch type deeply */
                type: {
                    handler(newVal, oldVal) {
                        if (!newVal) {
                            return;
                        }

                        this.logo_image = [];
                        this.icon_image = [];
                        this.main_bg_image = [];

                        if (newVal.logo) {
                            this.logo_image = [newVal.logo];
                        }
                        if (newVal.icon) {
                            this.icon_image = [newVal.icon];
                        }
                        if (newVal.main_bg) {
                            this.main_bg_image = [newVal.main_bg];
                        }
                    },
                    deep: true
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent
            },
            mounted() {
                this.type = this.multilist_types[0];
                this.load();
            },
            methods: {
                load() {
                    // get /api/v2/settings/seo
                    axios.get('/api/v2/settings/seo')
                        .then(response => {

                            this.multilist_types = JSON.parse(response.data.data) ?? this.multilist_types;
                            this.type = this.multilist_types[0];


                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                save() {
                    // convert this.multilist_types to json

                    swal({
                        title: 'Êtes-vous sûr ?',
                        text: 'Vous allez modifier seo paramètres',
                        type: 'warning',
                        buttons: true
                    }).then((response) => {


                        if (!response)
                            return;

                        let multilist_types = JSON.stringify(this.multilist_types);

                        this.loadings.save = true;

                        // send data to server
                        axios.post('/api/v2/settings/seo', {
                            seo: multilist_types
                        }).then(response => {
                            this.loadings.save = false;

                            swal({
                                title: 'Success',
                                text: 'Seo paramètres modifiés avec succès',
                                type: 'success',
                                buttons: false,
                                icon:"success",
                                timer: 2000
                            });

                        });

                    });

                }
            }
        }).mount('#app')
    </script>

@endsection
