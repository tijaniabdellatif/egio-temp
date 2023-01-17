@extends('v2.dashboard')

@section('title1', __("general.Modifier mon profile"))

@section('custom_head1')

    <script src="/assets/vendor/jquery.min.js"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/editprofile.styles.css') }}">

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    <script src="{{ asset('js/uploadFiles.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>


    <style>
        .contact {
            padding: 10px;
            border: 1px solid rgb(194, 194, 194);
            background: rgb(250, 250, 250);
            position: relative;
        }

        .iti { width: 100%;  !important}

        .invalid{

        border :1px solid red;
        }

        .invalidfeed{

         display: block;
         height:auto;
        }

    </style>

@endsection

@section('content1')


    <div class="d-none" id="editprofile-app">

        <div class="login-container">
            <div class="login-form">

                <div class="section-heading">
                    <h1>{{__('general.Informations principales')}}</h1>
                    <div class="heading-underline"></div>
                </div>

                <div class="form-grid">

                    {{-- form-group firstname --}}
                    <div class="form-group">
                        <label for="firstname">{{__('general.Prénom')}} :</label>
                        <input type="text" class="form-control" :class="errors.firstname ? 'is-invalid' : ''"
                            v-model="form.firstname" id="firstname" placeholder="{{__('general.Votre prénom')}}">
                        <div class="invalid-feedback">
                            @{{ errors.firstname?.join('<br />') }}
                        </div>
                    </div>

                    {{-- form-group lastname --}}
                    <div class="form-group">
                        <label for="lastname">{{__('general.votre nom')}} :</label>
                        <input type="text" class="form-control" :class="errors.lastname ? 'is-invalid' : ''"
                            v-model="form.lastname" id="lastname" placeholder="{{__('general.votre nom')}}">
                        <div class="invalid-feedback">
                            @{{ errors.lastname?.join('<br />') }}
                        </div>
                    </div>
                    {{-- form-group username --}}
                    <div class="form-group">
                        <label for="username">{{__("general.Nom d'utilisateur")}} :</label>
                        <input type="text" class="form-control" :class="errors.username ? 'is-invalid' : ''"
                            v-model="form.username" id="username" placeholder="Votre nom d'utilisateur">
                        <div class="invalid-feedback">
                            @{{ errors.username?.join('<br />') }}
                        </div>
                    </div>
                    {{-- form-group email --}}
                    <div class="form-group">
                        <label for="email">{{__("general.Email")}} :</label>
                        <input type="email" class="form-control" :class="errors.email ? 'is-invalid' : ''"
                            v-model="form.email" id="email" placeholder="{{__('general.Email')}}">
                        <div class="invalid-feedback">
                            @{{ errors.email?.join('<br />') }}
                        </div>
                    </div>
                    {{-- form-group company --}}
                    <div v-if="usertype==4||usertype==3" class="form-group">
                        <label for="company">{{__("general.Nom de l'entrepris")}} :</label>
                        <input type="text" class="form-control" :class="errors.company ? 'is-invalid' : ''"
                            v-model="form.company" id="company" placeholder="{{__("general.Nom de l'entrepris")}}">
                        <div class="invalid-feedback">
                            @{{ errors.company?.join('<br />') }}
                        </div>
                    </div>
                    {{-- form-group phone --}}
                    <div class="form-group">
                        <label for="phone">{{__('general.TÉLÉPHONE')}} :</label>
                        <input type="phone" class="form-control" v-model="form.phone" id="phone" :class="errors.phone ? 'is-invalid' : ''">
                            <div :class="errors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                @{{ errors.phone?.join('<br />') }}
                            </div>

                    </div>

                </div>

                <div class="section-heading">
                    <h1>{{__("general.Plus d'informations")}} </h1>
                    <div class="heading-underline"></div>
                </div>

                {{-- form-group bio --}}
                <div class="form-group">
                    <label for="phone">{{__("general.Bio")}} :</label>
                    <textarea name="bio" class="form-control" v-model="form.bio" placeholder="Votre bio ..."></textarea>
                </div>

                <div v-if="usertype==4||usertype==3" class="form-grid">

                    {{-- form-group website --}}
                    <div class="form-group">
                        <label for="website">{{__('general.Site web')}}</label>
                        <input type="text" class="form-control" :class="errors.website ? 'is-invalid' : ''"
                            v-model="form.website" id="website" placeholder="Votre site web">
                        <div class="invalid-feedback">
                            @{{ errors.website?.join('<br />') }}
                        </div>
                    </div>
                    {{-- form-group city --}}
                    <div class="form-group">
                        <label for="website">{{__("general.Ville")}}</label>
                        <ml-select v-if="cities.length>0" :options="cities" value="id" label="name"
                            v-model:selected-option="form.city" mls-class="form-control"
                            mls-placeholder="sélectionner une ville" v-model="form.city" />
                        <div class="invalid-feedback">
                            @{{ errors.website?.join('<br />') }}
                        </div>
                    </div>

                </div><br/>

                <div class="section-heading">
                    <h1>{{__('general.Informations de contact')}}</h1>
                    <div class="heading-underline"></div>
                </div>

                <div class="form-group mt-2" v-for="(cnt,key) in contacts" :key="key">
                    <label for="my-input"><small>@{{ cnt.type }} :</small></label>

                    <div class="contact phone-contact">
                        <div class="form-group mt-2 d-flex" v-for="(itm,key2) in cnt.values"
                            :key="'phoneitem' + key + '_' + key2">
                            <input class="form-control" :class="errors[`${cnt.type}.${key2}.value`] ? 'is-invalid' : ''"
                                :placeholder="cnt.placeholder" v-model="itm.value" type="text">
                            <button type="button" class="btn btn-danger contact ms-2"
                                @click="deleteContact(cnt.type,key2)">
                                x
                            </button>
                        </div>
                        <button type="button" class="btn btn-success contact mt-2" @click="addAnotherContact(cnt.type)">
                            <i class="fas fa-plus"></i>
                            {{__('general.Ajouter')}} @{{ cnt.itemName }}
                        </button>
                    </div>

                </div>

                <div v-if="usertype==4||usertype==3" class="section-heading">
                    <h1>{{__('general.Media')}}</h1>
                    <div class="heading-underline"></div>
                </div>

                <div v-if="usertype==4||usertype==3" class="form-group mt-2">
                    <label for=""><small>{{__("general.Avatar")}} </small></label>
                    <upload-files-component v-model:files="form.userImage" type="images"
                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                </div>

                <div v-if="usertype==4||usertype==3" class="form-group mt-2">
                    <label for=""><small>{{__('general.Image de couverture')}} :</small></label>
                    <upload-files-component v-model:files="form.bannerImg" type="images"
                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                </div>

                {{-- form-group submit --}}
                <div class="form-group">
                    <button @click="save()" type="submit" id="btn-login" class="btn btn-primary">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                <span class="sr-only">{{__('general.En cours')}}...</span>
                            </div>
                            <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                {{__('general.Modifier')}}
                            </span>
                        </div>
                    </button>
                </div>
            </div>
        </div>

    </div>



    <script>
        let editprofileApp = createApp({
            data() {
                return {
                    form: {
                        id: '{{ Auth::user()->id }}',
                        username: '',
                        email: '',
                        phone: '',
                        firstname: '',
                        lastname: '',
                        company: '',
                        website: '',
                        bio: '',
                        city: null,
                        userImage: [],
                        bannerImg: []
                    },
                    usertype: null,
                    loadings: {
                        login: false,
                    },
                    errors: {},
                    error: "",
                    cities: [],
                    contacts: [{
                            type: `{{__("general.Numéro de téléphone additionnel")}}`,
                            placeholder: `{{__("general.Numéro de téléphone additionnel")}}`,
                            itemName: 'Téléphone',
                            values: []
                        },
                        {
                            type: `{{__("general.Email")}}`,
                            placeholder: 'email@gmail.com',
                            itemName: 'Email',
                            values: []
                        },
                        {
                            type: `{{__("general.Whatsapp")}}`,
                            placeholder: `{{__("general.Numéro de whatsapp additionnel")}}`,
                            itemName: 'Whatsapp',
                            values: []
                        }
                    ],
                }
            },
            computed: {},
            mounted() {

                axios.get('api/v2/editProfileData?id=' + this.form.id).then((response) => {
                    const data = response.data.data;
                    this.form = {
                        id: '{{ Auth::user()->id }}',
                        username: data.username,
                        email: data.email,
                        phone: data.phone,
                        firstname: data.firstname,
                        lastname: data.lastname,
                        company: data.company,
                        website: data.website,
                        bio: data.bio,
                        city: data.city,
                        userImage: [],
                        bannerImg: []
                    };

                    this.usertype = data.usertype;
                    if (data.idAvatar) this.form.userImage = [{
                        id: data.idAvatar,
                        name: data.nameAvatar
                    }];
                    if (data.idBanner) this.form.bannerImg = [{
                        id: data.idBanner,
                        name: data.nameBanner
                    }];
                    if (data.userPhones)
                        for (const d of data.userPhones) {
                            this.contacts[0].values.push({
                                value: d
                            });
                        }
                    if (data.userEmails)
                        for (const d of data.userEmails) {
                            this.contacts[1].values.push({
                                value: d
                            });
                        }
                    if (data.userWtsps)
                        for (const d of data.userWtsps) {
                            this.contacts[2].values.push({
                                value: d
                            });
                        }

                }).catch((error) => {
                    console.log(error);
                });
                axios.post('api/v2/city/allCities').then((response) => {
                    this.cities = [{
                        id: null,
                        name: "sélectionner une ville"
                    }].concat(response.data.data);
                }).catch((error) => {
                    console.log(error);
                });

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
                    ;
                    document.getElementById('phone').value = fullNumber;
                });





            },
            components: {
                'ml-select': MlSelect,
                "uploadFilesComponent": uploadFilesComponent,
            },
            methods: {
                save() {
                    this.form.company_banner_id = this.form.bannerImg[0] ? this.form.bannerImg[0].id : null;
                    this.form.user_image_id = this.form.userImage[0] ? this.form.userImage[0].id : null;
                    this.form.phones = this.contacts[0].values;
                    this.form.emails = this.contacts[1].values;
                    this.form.whatsapp = this.contacts[2].values;
                    this.loadings.login = true;
                    axios.post('/api/v2/editProfile', this.form).then((response) => {
                        this.loadings.login = false;
                        if (response.data.success == true) {
                            Swal.fire({
                                title: 'Modification réussie 👌',
                                confirmButtonText: 'Mon profil',
                              }).then((result) => {

                                if (result.isConfirmed) {

                                    window.location.href="/myprofile";

                                }
                              })
                        }

                    }).catch((error) => {
                        this.loadings.login = false;
                        if (typeof error.response?.data?.error === 'object') this.errors = error.response
                            .data.error;
                        else {
                            this.errorText = error.response?.data?.error;
                            displayToast(error, '#842029');
                        }
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
            },
        }).mount('#editprofile-app');
        document.querySelector('#editprofile-app').classList.remove("d-none");
    </script>


@endsection


@section('custom_foot1')

@endsection
