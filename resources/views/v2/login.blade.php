@extends('v2.layouts.default')

@section('title', __("general.Se connecter") . ' | Multilist')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/auth.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/add.styles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />


    <style>

        body {

            /*background: url(/images/login-bg.webp);*/
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;

           }
        .account-item {
            cursor: pointer;
            background: #0000000d;
            padding: 10px;
            width: 100%;
            overflow: hidden;
            transition: .3s ease-in-out;
        }

        .account-item:hover {
            background: #0000001c;
        }
    </style>

@endsection

@section('content')

    <div class="container" id="container">

        <div class="login-container shadow" id="login-app" data-aos="flip-left">
            <h1 class="login-title">
                {{ __('general.Se connecter') }}
            </h1>

            {{-- if user is registred display a success message --}}
            <div class="alert alert-success" v-if="registred">
                <strong>
                   {{__('general.signup msg')}}.
                </strong>
            </div>

            <div v-if="errors.message" >
                <div class="alert alert-danger" role="alert">
                    <strong>{{ __('general.Erreur') }} !</strong> @{{ errors.message }}
                </div>
            </div>

            @if(isset($error))
                <div class="alert alert-danger" role="alert">
                    <strong>{{ __('general.Erreur') }} !</strong> {{ error }}
                </div>
            @endif

            <div class="login-form">
                {{-- form-group email --}}
                <div class="form-group" id="login-form-email">
                    <label for="email"> {{ __('general.Email') }} </label>
                    <input v-model="email" type="text" class="form-control" :class="errors.email?'is-invalid' : ''" id="email">
                    <div class="invalid-feedback d-flex flex-column" v-if="errors.email">
                        <span v-for="error in errors.email">@{{ error }}</span>
                    </div>
                </div>
                {{-- form-group password --}}
                <div class="form-group">
                    <label for="password">{{ __('general.Entrez votre mot de passe') }}</label>
                    <input @keyup.enter="login" v-model="password" type="password" class="form-control" :class="errors.password ? 'is-invalid' : ''" id="password"
                        >
                    <div class="invalid-feedback d-flex flex-column" v-if="errors.password">
                        <span v-for="error in errors.password">@{{ error }}</span>
                    </div>
                </div>
                {{-- forget password  --}}
                <div class="d-flex mt-2" >
                    <a class="ms-auto" style="color: gray;font-size: 12px;" :href="`{{ route('v2.resetPassword') }}?email=${email}`">{{ __("general.Mot de passe oublié") }}?</a>
                </div>
                {{-- form-group submit --}}
                <div class="form-group">
                    <button @click="login()" type="submit" id="btn-login" class="btn btn-primary">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                <span class="sr-only">{{ __('general.Loading...') }}</span>
                            </div>
                            <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                <i class="fa-solid fa-arrow-right-to-bracket mx-2"></i>
                               {{__('general.Se connecter')}}
                            </span>
                        </div>
                    </button>
                </div>
                <div class="my-3" style="border-top: 1px solid rgba(0, 0, 0, 0.6)"></div>
                    <a href="{{ url('auth/google') }}">
                        <div class="loginBtn loginBtn--google" style="text-align: center">
                            <span><i class="fab fa-google"></i></span><span style="flex: 1;">{{ __('general.Se connecter avec google') }}</span>
                        </div>
                    </a>
                    <a href="{{ url('redirect/facebook') }}">
                        <div class="loginBtn loginBtn--facebook" style="text-align: center">
                            <span><i class="fab fa-facebook"></i></span><span style="flex: 1;">{{ __('general.Se connecter avec facebook') }}</span>
                        </div>
                    </a>
                    {{-- <a href="{{ url('redirect/instagram') }}">
                        <div class="loginBtn" style="text-align: center;background-color:#fb3958">
                            <span><i class="fab fa-instagram"></i></span><span style="flex: 1;">Se connecter avec instagram (TEST)</span>
                        </div>
                    </a> --}}
            </div>

        </div>
    </div>


    <style>
        .loginBtn {
            box-sizing: border-box;
            position: relative;
            /* width: 13em;  - apply for fixed size */
            margin: 0.2em;
            padding: 0 15px;
            border: none;
            text-align: left;
            line-height: 37px;
            white-space: nowrap;
            border-radius: 0.2em;
            font-size: 16px;
            color: #fff;
            display: flex;
            align-items: center;
            }
            .loginBtn:before {
            content: "";
            box-sizing: border-box;
            position: absolute;
            top: 0;
            left: 0;
            width: 34px;
            height: 100%;
            }
            .loginBtn:focus {
            outline: none;
            }
            .loginBtn:active {
            box-shadow: inset 0 0 0 32px rgba(0, 0, 0, 0.1);
            }
            /* Google */
            .loginBtn--google {
            /*font-family: "Roboto", Roboto, arial, sans-serif;*/
            background: #DD4B39;
            }
            .loginBtn--google:hover,
            .loginBtn--google:focus {
            background: #E74B37;
            cursor: pointer;
            }
            .loginBtn--facebook {
            /*font-family: "Roboto", Roboto, arial, sans-serif;*/
            background: #0474e8;
            }
            .loginBtn--facebook:hover,
            .loginBtn--facebook:focus {
            background: #2473e1;
            cursor: pointer;
            }
    </style>

    <script type="text/javascript">
        createApp({
            data() {
                return {
                    email: '',
                    password: '',
                    loadings: {
                        login: false,
                    },
                    display_testing_accounts: false,
                    errors : {},
                    registred:false
                }
            },
            watch: {
                // watch email
                email(newValue, oldValue) {
                    if (newValue == "test") {
                        this.display_testing_accounts = true;
                    } else {
                        this.display_testing_accounts = false;
                    }

                    // clear email error
                    this.errors.email = null;
                },
                // watch password
                password(newValue, oldValue) {
                    // clear password errors
                    this.errors.password = null;
                }
            },
            mounted() {
                // check url has registred
                let url = new URL(window.location.href);
                if (url.searchParams.get("registred")) {
                    this.registred = true;
                }
            },
            methods: {
                login() {

                    if(!this.loginValidation()){
                        return;
                    }

                    var data = new FormData();
                    data.append('email', this.email);
                    data.append('password', this.password);

                    var config = {
                        method: 'post',
                        url: '/api/v2/login',
                        data: data
                    };

                    this.loadings.login = true;

                    axios(config)
                        .then((response) => {
                            // response.data.data
                            let data = response.data.data;
                            let token = data.token;
                            let auth = data.auth;

                            // store the token & auth
                            localStorage.setItem('token', token);
                            localStorage.setItem('auth', JSON.stringify(auth));

                            // console.log(response.data);

                            window.location = '/dashboard';

                            this.loadings.login = false;
                        })
                        .catch((error) => {
                            // error.data
                            error = error?.response?.data?.error??"Quelque chose s'est mal passé";
                            console.log(error);
                            // check if error is a string
                            if (typeof error == 'string') {
                                this.errors.message = error;
                            } else {
                                // check if error is an array
                                if (error instanceof Array) {
                                    this.errors.messages = error;
                                } else {
                                    // check if error is an object
                                    if (typeof error == 'object') {
                                        this.errors = error;
                                    }
                                }
                            }
                            this.loadings.login = false;
                        });
                },
                selectAccount(authtype) {
                    // request to http://127.0.0.1:8000/api/v2/logintest

                    var config = {
                        method: 'post',
                        url: '/api/v2/logintest',
                        data: {
                            usertype: authtype.id,
                        }
                    };

                    this.loadings.login = true;

                    axios(config)
                        .then((response) => {
                            // response.data.data
                            let data = response.data.data;
                            let token = data.token;
                            let auth = data.auth;

                            // store the token & auth
                            localStorage.setItem('token', token);
                            localStorage.setItem('auth', JSON.stringify(auth));

                            window.location = '/';

                            this.loadings.login = false;
                        })
                        .catch((error) => {
                            console.log(error);
                            this.loadings.login = false;
                        });

                },
                loginValidation(){
                    this.errors = {};

                    if(this.email == ""){
                        if(!this.errors.email){
                            this.errors.email = [];
                        }

                        this.errors.email.push("Le champ email est oblogatoire");
                    }
                    // check if email is valid
                    // if(!this.email.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
                    //     if(!this.errors.email){
                    //         this.errors.email = [];
                    //     }

                    //     this.errors.email.push("Email is invalid");
                    // }

                    if(this.password == ""){
                        if(!this.errors.password){
                            this.errors.password = [];
                        }

                        this.errors.password.push("Le champ mot de passe est obligatoire");
                    }

                    if(Object.keys(this.errors).length > 0){
                        return false;
                    }

                    return true;
                }
            },
        }).mount('#login-app');
    </script>


    <script>
        const container = document.querySelector('#container');

        const selectedLocal = '<?= session('lang') ?>';

        if(selectedLocal === 'fr'){

              container.setAttribute('dir','ltr')
        }

        if(selectedLocal === 'ar'){

             container.setAttribute('dir','rtl');
        }
    </script>



    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
    AOS.init();
    </script>
@endsection

@section('custom_foot')

@endsection
