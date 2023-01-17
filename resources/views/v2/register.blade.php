@extends('v2.layouts.default')

@section('title', __("general.S'inscrire") . ' | Multilist')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/auth.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/add.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />


    <style>
        body {
            /*background: url(/images/login-bg.webp);*/
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        ::placeholder{

            padding:0 10px 0 10px;
        }

        .iti {
            width: 100%;
             !important
        }


        .invalid {
            border: 1px solid red;
        }

        .invalidfeed {
            display: block;
            height: auto;
        }
    </style>



@endsection

@section('content')

    <div class="container">

        <div class="login-container shadow" id="login-app" data-aos="flip-right">
            <h1 class="login-title" @if (Session('lang') == 'ar')
            dir="rtl"
            @else
            dir="ltr"
            @endif>
            {{ __("general.S'inscrire") }}</h1>
            <div id="form" class="login-form">

                <div class="form-group translates" @if (Session('lang')=='ar')
                        dir="rtl"
                    @else
                        dir="ltr"
                    @endif>
                    <label class="mb-2">{{ __("general.Vous êtes") }}</label>
                    <div style="margin-left: 20px;">

                      <div class="">
                        <input id="particulier" class="mx-2" type="radio" value="5" v-model="userType">
                        <label class="form-check-label" for="particulier">
                            {{ __("general.Particulier") }}
                        </label>
                      </div>
                      <div class="">
                        <input id="agence" class="mx-2" type="radio" value="4" v-model="userType">
                        <label class="form-check-label" for="agence">
                            {{ __("general.Agence immobilière") }}
                        </label>
                      </div>
                      <div class=" disabled">
                        <input id="promoteur" class="mx-2" type="radio" value="3" v-model="userType" >
                        <label class="form-check-label" for="promoteur">
                            {{ __("general.Promoteur immobilier") }}
                        </label>
                      </div>
                    </div>
                </div>

                {{-- form-group companyName --}}
                <div v-if="userType!=5" class="form-group translates" @if (Session('lang')=='ar')
                    dir="rtl"
                @else
                    dir="ltr"
                @endif>
                    <label for="companyName">{{ __("general.Nom d'entreprise") }}</label>
                    <input type="text" class="form-control" :class="errors.companyName ? 'is-invalid' : ''"
                        v-model="companyName" id="companyName">
                    <div class="invalid-feedback">
                        @{{ errors.companyName?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group firstname --}}
                <div class="form-group translates">
                    <label for="firstname">{{ __('general.Votre prénom') }} </label>
                    <input type="text" class="form-control" :class="errors.firstname ? 'is-invalid' : ''"
                        v-model="firstname" id="firstname">
                    <div class="invalid-feedback">
                        @{{ errors.firstname?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group lastname --}}
                <div class="form-group translates">
                    <label for="lastname">{{ __('general.votre nom') }} </label>
                    <input type="text" class="form-control" :class="errors.lastname ? 'is-invalid' : ''"
                        v-model="lastname" id="lastname">


                      <div class="invalid-feedback">
                        @{{ errors.lastname?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group username --}}
                <div class="form-group translates">
                    <label for="username">{{ __("general.Nom d'utilisateur") }} </label>
                    <input type="text" class="form-control" :class="errors.username ? 'is-invalid' : ''"
                        v-model="username" id="username">
                    <div class="invalid-feedback">
                        @{{ errors.username?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group email --}}
                <div class="form-group translates">
                    <label for="email">{{ __('general.Votre email') }}</label>
                    <input type="email" class="form-control" :class="errors.email ? 'is-invalid' : ''" v-model="email"
                        id="email">
                    <div class="invalid-feedback">
                        @{{ errors.email?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group phone --}}
                <div class="form-group translates">
                    <label for="phone">{{ __('general.Votre téléphone') }} </label>
                    <input type="tel" class="form-control" :class="errors.phone ? 'is-invalid' : ''" v-model="phone"
                        id="phone">
                    <div :class="errors.phone ? 'invalid-feedback invalidfeed' : 'invalid-feedback'">
                        @{{ errors.phone?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group password --}}
                <div class="form-group translates">
                    <label for="password">{{ __('general.Entrez votre mot de passe') }} </label>
                    <input type="password" class="form-control" :class="errors.password ? 'is-invalid' : ''"
                        v-model="password" id="password">

                    <div class="invalid-feedback">
                        @{{ errors.password?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group password2 --}}
                <div class="form-group translates">
                    <label for="password2">{{ __('general.Confirmez votre mot de passe') }} </label>
                    <input type="password" class="form-control" :class="errors.password2 ? 'is-invalid' : ''"
                        v-model="password2" id="password2">
                    <div class="invalid-feedback">
                        @{{ errors.password2?.join('<br />') }}
                    </div>
                </div>

                {{-- form-group submit --}}
                <div class="form-group translates">
                    <button @click="register()" type="submit" id="btn-login" class="btn btn-primary">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                <span class="sr-only">{{ __('general.En cours') }} ...</span>
                            </div>
                            <span @if (Session('lang') == 'ar')
                            dir="rtl"
                            @else
                            dir="ltr"
                            @endif id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                {{ __("general.S'inscrire") }}
                                <i class="fa-solid fa-check mx-2"></i>
                            </span>
                        </div>
                    </button>
                </div>

            </div>
        </div>
    </div>


@endsection

@section('custom_foot')



    <script type="text/javascript">
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    username: '',
                    email: '',
                    phone: '',
                    companyName: '',
                    firstname: '',
                    lastname: '',
                    password: '',
                    password2: '',
                    userType: 5,
                    loadings: {
                        login: false,
                    },
                    errors: {},
                    error: ""
                }
            },
            methods: {
                register() {
                    this.loadings.login = true;
                    axios.post('/api/v2/register', {
                            companyName: this.companyName,
                            userType: this.userType,
                            firstname: this.firstname,
                            lastname: this.lastname,
                            username: this.username,
                            email: this.email,
                            phone: this.phone,
                            password: this.password,
                            password2: this.password2,
                        })
                        .then(response => {
                            this.loadings.login = false;
                            window.location = '/login?registred=true';
                        })
                        .catch(err => {
                            this.loadings.login = false;
                            const {
                                response: {
                                    data: {
                                        error
                                    }
                                }
                            } = err;
                            this.errors = error;

                            console.log('errors', error.phone);


                        });
                }
            },
            mounted() {

                var input = document.querySelector("#phone");
                window.intlTelInput(input, {
                    allowDropdown: true,
                    initialCountry: 'MA',
                    excludeCountries: ['EH'],
                    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js',
                    preferredCountries: ['MA', 'FR', 'BE', 'UK', 'US', 'AE', 'CA', 'NL', 'ES', 'DE', 'IT',
                        'GB', 'CH', 'CI', 'SN', 'DZ', 'MR', 'TN', 'PT', 'TR', 'SA', 'SE', 'GA', 'LU',
                        'QA', 'IN', 'NO', 'GN', 'CG', 'ML', 'EG', 'IL', 'IE', 'RO', 'RE', 'CM', 'DK',
                        'HU'
                    ],

                              });

                var iti = window.intlTelInputGlobals.getInstance(input);

                input.addEventListener('input', function() {
                    var fullNumber = iti.getNumber();
                    document.getElementById('phone').value = fullNumber;
                });

            }

        }).mount('#login-app')
    </script>


    <script>
    const elements = document.querySelectorAll('.translates');
    const localLang = '<?= session('lang') ?>';
    if(localLang === 'ar'){
    elements.forEach(item => {

           item.setAttribute('dir','rtl');
      })
    }
    </script>

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
    AOS.init()
    </script>

@endsection
