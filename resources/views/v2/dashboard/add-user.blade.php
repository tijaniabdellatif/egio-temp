@extends('v2.layouts.dashboard')

@section('title', 'Ajouter un utilisateur')

@section('custom_head')

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>

    <style>
        .media-form-group {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .iti { width: 100%;  !important}

       .invalid{

        border :1px solid red;
        }

        .invalidfeed{

        display: block;
        height:auto;
        }

        .media-form-group .user-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin-right: 10px;
            background: rgb(231, 231, 231);
            margin: auto;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
        }

        legend.scheduler-border {
            width: inherit;
            /* Or auto */
            padding: 0 10px;
            /* To give a bit of padding on the left and right */
            border-bottom: none;
        }

        .contact {
            padding: 10px;
            border: 1px solid rgb(194, 194, 194);
            background: rgb(250, 250, 250);
            position: relative;
            border-radius: 5px;
        }

        .contact.is-invalid {
            border-color: #dc3545;
        }

        .media-form-group .company-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin-right: 10px;
            background: rgb(231, 231, 231);
            margin: auto;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .media-form-group .company-banner {
            width: 100%;
            height: 130px;
            background: rgb(231, 231, 231);
            margin: auto;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }

        .media-form-group .company-video {
            width: 100%;
            height: 130px;
            background: rgb(231, 231, 231);
            margin: auto;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .media-form-group .company-video video {
            height: 100%;
        }
    </style>

@endsection

@section('content')

    <div class="pagetitle">
        <h1>Ajouter un utilisateur</h1>
        <hr>
    </div>

    <div id="app" ref="div1">

        <section class="section">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informations principales : </h5>
                            <div class="form-group">
                                <label for="my-input"><small>Prénom :</small></label>
                                <input v-model="user.firstname" :class="errors.firstname ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Le prénom" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.firstname?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Nom</small></label>
                                <input v-model="user.lastname" :class="errors.lastname ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Le nom" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.lastname?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Nom d'utilisateur</small></label>
                                <input v-model="user.username" :class="errors.username ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Nom d'utilisateur" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.username?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Email</small></label>
                                <input v-model="user.email" :class="errors.email ? 'is-invalid' : ''" class="form-control"
                                    placeholder="Email" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.email?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Téléphone</small></label>
                                <input v-model="user.phone" :class="errors.phone ? 'is-invalid' : ''" class="form-control"
                                 type="text" id="phone">
                                <div :class="errors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                    <span v-html="errors.phone?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Date de naissance</small></label>
                                <input v-model="user.birthdate" :class="errors.birthdate ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Date de naissance" type="date">
                                <div class="invalid-feedback">
                                    <span v-html="errors.birthdate?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for=""><small>Type d'utilisateur :</small></label>
                                <select name="user_type_id" :class="errors.user_type_id ? 'is-invalid' : ''"
                                    v-model="user.user_type_id" class="form-select" id="user_type_id-select">
                                    <option value="" selected>Choisir un type d'utilisateur:</option>
                                    <option v-for="(utype,key) in userTypes" :value="utype.id">@{{ utype.designation }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-html="errors.user_type_id?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for=""><small>CED :</small></label>
                                <select name="property" :class="errors.assigned_ced ? 'is-invalid' : ''"
                                    v-model="user.assigned_ced" class="form-select" id="property-select">
                                    <option value="" selected>Choisir un CED</option>
                                    <option v-for="(ced,key) in ceds" :value="ced.id">@{{ ced.username }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for=""><small>Mot de passe :</small></label>
                                <input v-model="user.password" :class="errors.password ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Mot de passe" type="password">
                                <div class="invalid-feedback">
                                    <span v-html="errors.password?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for=""><small>Confirmer le mot de passe :</small></label>
                                <input v-model="user.password2" :class="errors.password2 ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Confirmer le mot de passe" type="password">
                                <div class="invalid-feedback">
                                    <span v-html="errors.password2?.join('<br/>')"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Plus d'informations : </h5>
                            <div class="form-group mt-2">
                                <label for=""><small>User image</small></label>
                                <upload-files-component v-model:files="medias.userImage"
                                    :error="errors.user_image_id ? true : false" type="images"
                                    :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                                <div class="invalid-feedback">
                                    <span v-html="errors.user_image_id?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-select"><small>Le genre :</small></label>
                                <select id="my-select" :class="errors.gender ? 'is-invalid' : ''" v-model="user.gender"
                                    class="form-control">
                                    <option value="" selected>Sélectionnez un genre</option>
                                    <option value="male">Homme</option>
                                    <option value="female">Femme</option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-html="errors.gender?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-textarea"><small>Bio :</small></label>
                                <textarea id="my-textarea" :class="errors.bio ? 'is-invalid' : ''" v-model="user.bio" class="form-control"
                                    rows="3"></textarea>
                                <div class="invalid-feedback">
                                    <span v-html="errors.bio?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2" v-for="(cnt,key) in contacts" :key="key">
                                <label for="my-input"><small>@{{ cnt.type }} :</small></label>
                                <div class="contact phone-contact"
                                    :class="errors[`${cnt.type}.${key}.value`] ? 'is-invalid' : ''">
                                    <div class="form-group mt-2 d-flex" v-for="(itm,key2) in cnt.values"
                                        :key="'phoneitem' + key + '_' + key2">
                                        <input class="form-control"
                                            :class="errors[`${cnt.type}.${key2}.value`] ? 'is-invalid' : ''"
                                            :placeholder="cnt.placeholder" v-model="itm.value" type="text">
                                        <button type="button" class="btn btn-danger ms-2"
                                            @click="deleteContact(cnt.type,key2)">x</button>
                                    </div>
                                    <button type="button" class="btn btn-success mt-2 w-100"
                                        @click="addAnotherContact(cnt.type)">Ajouter @{{ cnt.itemName }}</button>
                                </div>
                                <div class="invalid-feedback">
                                    <span v-html="errors.test?.join('<br/>')"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- only for users who have proUsersTypes-->
        <section class="section" v-if="pro_users_types.indexOf(user.user_type_id) != -1">

            <div class="pagetitle">
                <h1>Utilisateur pro :</h1>
                <hr>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pro informations :</h5>
                            <div class="form-group">
                                <label for="my-input"><small>Nom de l'entreprise :</small></label>
                                <input v-model="user.company_name" :class="errors.company_name ? 'is-invalid' : ''"
                                    class="form-control" placeholder="Nom de l'entreprise" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_name?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Ville</small></label>
                                <select name="property" :class="errors.assigned_ced ? 'is-invalid' : ''"
                                    v-model="user.company_city" class="form-select" id="property-select">
                                    <option value="null" selected>Choisir une ville</option>
                                    <option v-for="(city,key) in cities" :value="city.id">@{{ city.name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_city?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Adresse :</small></label>
                                <textarea v-model="user.company_address" :class="errors.company_address ? 'is-invalid' : ''" class="form-control"
                                    placeholder="Adresse de l'entreprise" type="text"></textarea>
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_address?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Site Internet :</small></label>
                                <input v-model="user.company_website" :class="errors.company_address"
                                    class="form-control" placeholder="Site Internet" type="text">
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_address?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for=""><small>Image de l'entreprise :</small></label>
                                <upload-files-component v-model:files="medias.companyImage"
                                    :error="errors.company_image_id ? true : false" type="images"
                                    :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_image_id?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group media-form-group mt-2 d-none">
                                <label for=""><small>Bannière :</small></label>
                                <upload-files-component v-model:files="medias.companyBanner"
                                    :error="errors.company_banner_id ? true : false" type="images"
                                    :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_banner_id?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Vidéo intégrée :</small></label>
                                <textarea v-model="user.company_videoembed" :class="errors.company_videoembed" class="form-control"
                                    placeholder="Embedded video">
                                </textarea>
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_videoembed?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group media-form-group mt-2 d-none">
                                <label for=""><small>Vidéo :</small></label>
                                <upload-files-component v-model:files="medias.companyVideo"
                                    :error="errors.company_video_id ? true : false" type="videos"
                                    :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="false" />
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_video_id?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group media-form-group mt-2 d-none">
                                <label for=""><small>Audio :</small></label>
                                <upload-files-component v-model:files="medias.companyAudio"
                                    :error="errors.company_audio_id ? true : false" type="audios"
                                    :allowed-extensions="['mpeg', 'mpga', 'mp3', 'wav', 'aac']" :multiple="false" />
                                <div class="invalid-feedback">
                                    <span v-html="errors.company_audio_id?.join('<br/>')"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <div class="row">
            <div class="col-12 d-flex">
                <button type="submit" class="btn btn-success d-flex align-center justify-content-center mt-2"
                    @click="createUser()">
                    Créer
                    <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- created user modal -->
        <div class="modal fade" id="createdUserModal" tabindex="-1" role="dialog"
            aria-labelledby="createdUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createdUserModalLabel">L'utilisateur a créé avec succès</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>L'utilisateur a été créé avec succès</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('custom_foot')

    <script type="text/javascript">
        let app = Vue.createApp({
            data() {
                return {
                    pro_users_types: [4, 3],
                    contacts: [{
                            type: 'phones',
                            placeholder: '+212 060000000',
                            itemName: 'phone',
                            values: []
                        },
                        {
                            type: 'emails',
                            placeholder: 'email@gmail.com',
                            itemName: 'email',
                            values: []
                        },
                        {
                            type: 'whatsapp',
                            placeholder: '+212 060000000',
                            itemName: 'whatsapp',
                            values: []
                        }
                    ],
                    medias: {
                        userImage: [],
                        companyImage: [],
                        companyBanner: [],
                        companyVideo: [],
                        companyAudio: []
                    },
                    userTypes: [],
                    user: {
                        firstname: '',
                        lastname: '',
                        username: '',
                        email: '',
                        phone: '',
                        password: '',
                        birthdate: '',
                        user_type_id: '',
                        user_image_id: '',
                        assigned_ced: '',
                        gender: '',
                        bio: '',
                        phones: [],
                        emails: [],
                        whatsapp: [],
                        company_name: '',
                        company_city: '',
                        company_address: '',
                        company_website: '',
                        company_image_id: '',
                        company_banner_id: '',
                        company_video_id: '',
                        company_videoembed: '',
                    },
                    ceds: [],
                    cities: [],
                    errors: {},
                    loading: false,
                    loadings: {
                        ceds: false
                    }
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent
            },
            computed: {
                userStatus: function() {
                    return status_obj;
                }
            },
            watch: {
                medias: {
                    handler: function(newVal, oldVal) {
                        if (newVal.userImage.length > 0) {
                            this.user.user_image_id = newVal.userImage[0].id;
                        }
                        if (newVal.companyImage.length > 0) {
                            this.user.company_image_id = newVal.companyImage[0].id;
                        }
                        if (newVal.companyBanner.length > 0) {
                            this.user.company_banner_id = newVal.companyBanner[0].id;
                        }
                        if (newVal.companyVideo.length > 0) {
                            this.user.company_video_id = newVal.companyVideo[0].id;
                        }
                        if (newVal.companyAudio.length > 0) {
                            this.user.company_audio_id = newVal.companyAudio[0].id;
                        }
                    },
                    deep: true
                },
                contacts: {
                    handler: function(newVal, oldVal) {
                        this.user.phones = [];
                        this.user.emails = [];
                        this.user.whatsapp = [];
                        for (let i = 0; i < this.contacts.length; i++) {
                            if (this.contacts[i].type == 'phones') {
                                this.user.phones.push(...this.contacts[i].values);
                            }
                            if (this.contacts[i].type == 'emails') {
                                this.user.emails.push(...this.contacts[i].values);
                            }
                            if (this.contacts[i].type == 'whatsapp') {
                                this.user.whatsapp.push(...this.contacts[i].values);
                            }
                        }
                    },
                    deep: true
                },
                errors: {
                    handler: function(newVal, oldVal) {
                        if (Object.keys(newVal).length > 0) {
                            // scroll to the top of the page
                            //window.scrollTo(0, 0);
                            this.loading = false;
                        }
                    },
                    deep: true
                },
                user: {
                    handler: function(newVal, oldVal) {},
                    deep: true
                }
            },
            mounted() {
                this.loadUserTypes();
                this.loadCeds();
                this.loadCities();
                this.watchUserAttributes();

                              // International phone input

                var input = document.querySelector("#phone");
                window.intlTelInput(input, {
                allowDropdown:true,
                initialCountry:'MA',
                excludeCountries:['EH'],
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js',
                preferredCountries : ['MA','FR','BE','UK','US','AE','CA','NL','ES','DE','IT','GB','CH','CI','SN','DZ','MR','TN','PT','TR','SA','SE','GA','LU','QA','IN','NO','GN','CG','ML','EG','IL','IE','RO','RE','CM','DK','HU','DZ'],

  });

                var iti = window.intlTelInputGlobals.getInstance(input);

                input.addEventListener('input', function() {
                    var fullNumber = iti.getNumber();
                    document.getElementById('phone').value = fullNumber;
                });
            },
            methods: {

                createUser() {

                    if (this.loading)
                        return;

                    if (!this.createUserValidation()) {
                        return;
                    }

                    this.loading = true;
                    // using acios post uset to /api/v2/user/create

                    axios.post('/api/v2/user/create', this.user)
                        .then(response => {
                            this.loading = false;
                            console.log(response);
                            this.resetForm();

                            // redirect to the user list
                            // displayToast("Plan créé avec succès","green");
                            // window.location.href = '/admin/users';

                            // swal("L'utilisateur est créé avec succès", "", "success")
                            // .then((value) => {
                            //     window.location.href = '/admin/users';
                            // });

                            // swal to display "L'utilisateur est créé avec succès" with success icon and no buttons and it will close after 3sec
                            // swal({
                            //     title: "L'utilisateur est créé avec succès",
                            //     text: "",
                            //     icon: "success",
                            //     button: false,
                            //     timer: 3000,
                            //     type: "success"
                            // }).then(function() {
                            //     window.location.href = '/admin/users';
                            // });

                            displayToast("Utilisateur créé avec succès", 'green');

                            // set time out and redirect
                            setTimeout(function() {
                                window.location.href = '/admin/users';
                            }, 5000);

                        })
                        .catch((error) => {
                            this.loading = false;
                            window.scrollTo(0, top);
                            console.log(error);
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else {
                                this.errorText = error.response.data.error;
                                displayToast(this.errorText, '#842029');
                            }
                        });

                },
                loadCeds() {

                    // using users filter load onliy user type comercials
                    this.loadings.ceds = true;
                    axios.post('/api/v2/user/filter/', {
                            where: [{
                                type: 'where',
                                subWhere: [{
                                        type: 'where',
                                        col: 'usertype',
                                        val: '9',
                                        op: '='
                                    },
                                    {
                                        type: 'orWhere',
                                        col: 'usertype',
                                        val: '8',
                                        op: '='
                                    },
                                ],
                            }]
                        })
                        .then(response => {
                            this.loadings.ceds = false;
                            console.log("load ceds ", response);
                            if (response.data.success) {
                                this.ceds = response.data.data;
                            }
                        })
                        .catch(error => {
                            this.loadings.ceds = false;
                            console.log(error);
                        });

                },
                resetForm() {
                    this.user = {
                        firstname: '',
                        lastname: '',
                        username: '',
                        email: '',
                        phone: '',
                        password: '',
                        birthdate: '',
                        user_type_id: '',
                        user_image_id: '',
                        gender: '',
                        bio: '',
                        phones: [],
                        emails: [],
                        whatsapp: [],
                        company_name: '',
                        company_city: '',
                        company_address: '',
                        company_website: '',
                        company_image_id: '',
                        company_banner_id: '',
                        company_video_id: '',
                        company_videoembed: ''
                    };
                    this.medias = {
                        userImage: [],
                        companyImage: [],
                        companyBanner: [],
                        companyVideo: [],
                        companyAudio: []
                    };
                    this.contacts = [{
                            type: 'phones',
                            placeholder: '+212 060000000',
                            itemName: 'phone',
                            values: []
                        },
                        {
                            type: 'emails',
                            placeholder: 'email@gmail.com',
                            itemName: 'email',
                            values: []
                        },
                        {
                            type: 'whatsapp',
                            placeholder: '+212 060000000',
                            itemName: 'whatsapp',
                            values: []
                        }
                    ];
                },
                getUserStatus(id) {
                    return status_obj[id] ?? '-';
                },
                loadUserTypes() {
                    // url : /api/v2/user/types
                    axios.get('/api/v2/user/types')
                        .then(response => {
                            if (response.data.success) {
                                this.userTypes = response.data.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });

                },
                importImage(input, elem) {

                    // filre reader on click on div and set it as background
                    $(input).click();
                    $(input).change(function() {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(elem).css('background-image',
                                `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });

                },
                importVideo(input, elem) {

                    // filre reader on click on div and set it a video source to elem
                    $(input).click();
                    $(input).change(function() {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $(elem).attr('src', `${e.target.result}`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });

                },
                deleteContact(type, key) {

                    // search for the type in contacts and remove value from values at key
                    this.contacts.forEach(function(cnt, key2) {
                        if (cnt.type == type) {
                            cnt.values.splice(key, 1);
                        }
                    });

                },
                addAnotherContact(type) {

                    // serach for the type in the contacts array and get values array
                    let values = this.contacts.filter(cnt => cnt.type === type)[0].values;

                    // add a new contact
                    values.push({
                        value: ''
                    });

                },
                createUserValidation() {
                    this.errors = {};
                    // validation

                    if (!this.user.firstname) {
                        if (!this.errors.firstname)
                            this.errors.firstname = [];
                        this.errors.firstname.push('Le prénom est obligatoire');
                    }
                    // first name should be at least 3 characters
                    if (this.user.firstname.length < 3) {
                        if (!this.errors.firstname)
                            this.errors.firstname = [];
                        this.errors.firstname.push('Le prénom doit contenir au moins 3 caractères');
                    }
                    // first name should respect this regix ^[a-zA-Z\-\s]+$
                    if (!this.user.firstname.match(/^[a-zA-Z\-\s]+$/)) {
                        if (!this.errors.firstname)
                            this.errors.firstname = [];
                        this.errors.firstname.push('Le prénom doit contenir uniquement des lettres');
                    }
                    // max length is 20 characters
                    if (this.user.firstname.length > 20) {
                        if (!this.errors.firstname)
                            this.errors.firstname = [];
                        this.errors.firstname.push('Le prénom doit contenir au maximum 20 caractères');
                    }

                    if (!this.user.lastname) {
                        if (!this.errors.lastname)
                            this.errors.lastname = [];
                        this.errors.lastname.push('Le nom est obligatoire');
                    }
                    // last name should be alt least 3 characters
                    if (this.user.lastname.length < 3) {
                        if (!this.errors.lastname)
                            this.errors.lastname = [];
                        this.errors.lastname.push('Le nom doit contenir au moins 3 caractères');
                    }
                    // last name should respect this regix ^[a-zA-Z\-\s]+$
                    if (!this.user.lastname.match(/^[a-zA-Z\-\s]+$/)) {
                        if (!this.errors.lastname)
                            this.errors.lastname = [];
                        this.errors.lastname.push('Le nom doit contenir uniquement des lettres');
                    }
                    // max length is 20 characters
                    if (this.user.username.length > 20) {
                        if (!this.errors.username)
                            this.errors.username = [];
                        this.errors.username.push('Le nom d\'utilisateur ne doit pas dépasser 20 caractères');
                    }

                    if (!this.user.username) {
                        if (!this.errors.username)
                            this.errors.username = [];
                        this.errors.username.push('Le nom d\'utilisateur est nécessaire');
                    }
                    // username should be alt least 3 characters
                    if (this.user.username.length < 3) {
                        if (!this.errors.username)
                            this.errors.username = [];
                        this.errors.username.push('Le nom d\'utilisateur doit contenir au moins 3 caractères');
                    }
                    // username should respect this regix ^[a-zA-Z]+[a-zA-Z0-9\-_]*$ ( should start with a letter and can contain letters, numbers, dashes, underscores)
                    if (!this.user.username.match(/^[a-zA-Z]+[a-zA-Z0-9\-_]*$/)) {
                        if (!this.errors.username)
                            this.errors.username = [];
                        this.errors.username.push(
                            'Le nom d\'utilisateur devrait commencer par une lettre et peut contenir des lettres, des chiffres, des tirets, des soulignements'
                        );
                    }
                    // username max length is 20 characters
                    if (this.user.username.length > 20) {
                        if (!this.errors.username)
                            this.errors.username = [];
                        this.errors.username.push('Le nom d\'utilisateur ne doit pas dépasser 20 caractères');
                    }

                    if (!this.user.email) {
                        if (!this.errors.email)
                            this.errors.email = [];
                        this.errors.email.push('L\'email est obligatoire');
                    }
                    // email should respect this regix ^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$
                    if (!this.user.email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                        if (!this.errors.email)
                            this.errors.email = [];
                        this.errors.email.push('L\'email doit être valide');
                    }

                    if (!this.user.phone) {
                        if (!this.errors.phone)
                            this.errors.phone = [];
                        this.errors.phone.push('Le téléphone est obligatoire');
                    }

                    // phone should respect this regix ^[0-9]{6,20}$
                   /*  if (!this.user.phone.match(/^[0-9]{6,20}$/)) {
                        if (!this.errors.phone)
                            this.errors.phone = [];
                        this.errors.phone.push(
                            'Téléphone doit contenir uniquement des chiffres, au minimum 6 caractères et au maximum 20'
                        );
                    } */


                    // password at least 8 characters
                    if (this.user.password.length < 8) {
                        if (!this.errors.password)
                            this.errors.password = [];
                        this.errors.password.push('Le mot de passe doit contenir au moins 8 caractères');
                    }

                    if (this.user.password2 != this.user.password) {
                        if (!this.errors.password2)
                            this.errors.password2 = [];
                        this.errors.password2.push('Le mot de passe doit être le même');
                    }

                    if (!this.user.user_type_id) {
                        if (!this.errors.user_type_id)
                            this.errors.user_type_id = [];
                        this.errors.user_type_id.push('Le type d\'utilisateur est obligatoire');
                    }



                    if (this.pro_users_types.indexOf(this.user.user_type_id) != -1) {

                        if (!this.user.company_name) {
                            if (!this.errors.company_name)
                                this.errors.company_name = [];
                            this.errors.company_name.push('Le nom de l\'entreprise est requis');
                        }
                        // company name should be at least 3 characters
                        if (this.user.company_name.length < 3) {
                            if (!this.errors.company_name)
                                this.errors.company_name = [];
                            this.errors.company_name.push(
                                'Le nom de l\'entreprise doit contenir au moins 3 caractères');
                        }
                        // company name should respect this regix ^[a-zA-Z\-\s_0-9]+$
                        if (!this.user.company_name.match(/^[a-zA-Z\-\s_0-9]+$/)) {
                            if (!this.errors.company_name)
                                this.errors.company_name = [];
                            this.errors.company_name.push(
                                'Le nom de l\'entreprise doit contenir uniquement des lettres, des chiffres, des tirets, des espaces et des underscores'
                            );
                        }
                    }

                    // check if errors is empty
                    if (Object.keys(this.errors).length > 0) {
                        return false;
                    }

                    return true;
                },
                watchUserAttributes() {
                    // loop throgh user object
                    for (let key in this.user) {
                        // watch for changes
                        this.$watch(`user.${key}`, function(newValue, oldValue) {
                            // if value is changed
                            if (newValue != oldValue) {
                                // if key is in the errors object
                                if (this.errors[key]) {
                                    // remove key from errors object
                                    delete this.errors[key];
                                }
                            }
                        });
                    }
                },
                loadCities() {

                    // using users filter load onliy user type comercials
                    this.loadings.cities = true;
                    axios.post('/api/v2/cities/filter/', {
                            where: [{
                                type: 'where',
                                col: 'usertype',
                                val: '8',
                                op: '='
                            }]
                        })
                        .then(response => {
                            this.loadings.cities = false;
                            console.log("load cities ", response);
                            if (response.data.success) {
                                this.cities = response.data.data;
                            }
                        })
                        .catch(error => {
                            this.loadings.cities = false;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });

                },
            },
        }).mount('#app');
    </script>


@endsection
