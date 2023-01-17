@extends('v2.layouts.default')

@section('title', __("general.Réinitialiser le mot de passe"))

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/auth.styles.css') }}">


    <style>
        @media  screen and (max-width: 500px) {


            #container-content{

                height:80vh !important;
            }

        }
    </style>

@endsection

@section('content')

    <div class="container" id="container-content" style="height:63.4vh;display:flex;justify-content:center;align-items:center;margin-top:150px;">
        <div class="login-container" id="login-app">
            <h1 class="login-title">{{__('general.Réinitialiser le mot de passe')}}</h1>
            <div class="alert alert-success" v-if="display_verification_code && step == 1">
                <strong>{{ __('general.reset pwd body1') }} <a role="button" @click="sendEmail"
                    class="link-primary text-decoration-underline pointer">{{ __('general.ici') }}</a> {{ __('general.pour le renvoyer.') }}
            </div>
            <div v-if="errors.length>0" class="alert alert-danger" role="alert">
                <template v-for="er in errors">
                    <strong>@{{ er }}</strong><br>
                </template>
            </div>
            <div class="login-form">
                <template v-if="step == 0">
                    <div class="form-group">
                        <label for="email">{{__('general.Email')}}</label>
                        <input type="email" class="form-control" v-model="email" id="email"
                            placeholder="{{ __('general.Entrez votre email') }}">
                    </div>
                    <div class="form-group">
                        <button @click="sendEmail()" type="submit" id="btn-login" class="btn btn-primary">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                    <span class="sr-only">{{ ('general.Loading...') }}</span>
                                </div>
                                <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                    {{ __('general.Envoyer') }}
                                </span>
                            </div>
                        </button>
                    </div>
                </template>
                <template v-else-if="step == 1">
                    {{-- form-group code --}}
                    <div class="form-group">
                        <label for="verification-code">Le code de vérification</label>
                        <input type="text" id="verification-code" name="verification-code" class="form-control"
                            v-model="formated_code" placeholder="X-X-X-X">
                    </div>
                    {{-- form-group submit --}}
                    <div class="form-group">
                        <button @click="verifyCode()" type="submit" id="btn-login" class="btn btn-primary">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-grow-sm" v-if="loadings.verification" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                    Vérifier
                                </span>
                            </div>
                        </button>
                    </div>
                </template>
                <template v-else-if="step == 2">
                    {{-- form-group password --}}
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe :</label>
                        <input type="password" class="form-control" v-model="password" id="password"
                            placeholder="Entrez le nouveau mot de passe">
                    </div>
                    <div class="form-group">
                        <label for="password">Confirmer le mot de passe :</label>
                        <input type="password" class="form-control" v-model="password_confirmation"
                            id="password_confirmation" placeholder="Confirmez le mot de passe">
                    </div>
                    {{-- form-group submit --}}
                    <div class="form-group">
                        <button @click="submitPassword()" type="submit" id="btn-login" class="btn btn-primary">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                    Valider
                                </span>
                            </div>
                        </button>
                    </div>
                </template>

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
                    email: '',
                    password: '',
                    password_confirmation: '',
                    code: '',
                    loadings: {
                        renitialize_password: false,
                        verification: false
                    },
                    step: 0,
                    errors: [],
                    display_verification_code: true
                }
            },
            computed: {
                formated_code: {
                    get() {
                        let code = this.code;

                        if (!code)
                            code = '';

                        // if code is greater than 4 we will make it 4
                        if (code.length > 4) {
                            code = code.substring(0, 4);
                        }
                        // if code is less than 4 we will make it 4
                        else if (code.length < 4) {
                            code = code.padEnd(4, '0');
                        }

                        // add - between each number
                        code = code.split('').join('-');

                        return code;
                    },
                    set(value) {

                        // get the cursor position
                        let start = document.activeElement.selectionStart - 1;

                        console.log(start);

                        this.code = value.split('-').join('');

                        // get just the 4 chars
                        this.code = this.code.substring(0, 4);

                        setTimeout(() => {
                            // set the cursor position
                            document.activeElement.selectionStart = start + 2;
                            document.activeElement.selectionEnd = start + 1 + 2;

                            // get the selected character
                            let selected = document.activeElement.value.substring(start, start + 1);
                            if (selected == '-') {
                                document.activeElement.selectionStart++;
                                document.activeElement.selectionEnd++;
                            }

                        }, 10);

                    }
                }
            },
            mounted() {
                // check if url contain id and code
                // js get query string from url
                let url = new URL(window.location.href);

                let id = url.searchParams.get('id');
                let code = url.searchParams.get('code');
                if (id && code) {
                    this.step = 2;
                    this.email = id;
                    this.code = code;

                    this.display_verification_code = false;
                }

                // check if url contains email
                let email = url.searchParams.get('email');
                if (email) {
                    this.email = email;
                }
            },
            methods: {
                verifyCode() {
                    this.errors = [];
                    this.loadings.verification = true;

                    axios.post('/api/v2/verifycode', {
                        code: this.code.substring(0, 4),
                        email: this.email
                    }).then(response => {

                        this.loadings.verification = false;

                        if (response.data.status == 'success') {
                            this.step = 2;
                        } else {
                            this.setError(error);
                        }
                    }).catch(error => {
                        this.loadings.verification = false;
                        error = error.response.data.error;
                        this.setError(error);
                    });

                },
                submitPassword() {
                    this.errors = [];
                    this.loadings.login = true;

                    axios.post('/api/v2/resetpassword', {
                            code: this.code.substring(0, 4),
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation
                        })
                        .then(response => {
                            this.loadings.login = false;
                            if (response.data.status == 'success') {
                                window.location = '/login';
                            } else {
                                this.setError(error);
                            }
                        })
                        .catch(error => {
                            this.loadings.login = false;
                            error = error.response.data.error;
                            this.setError(error);
                        });
                },
                sendEmail() {
                    this.loadings.login = true;
                    this.errors = [];

                    axios.post('/api/v2/requestresetpassword', {
                            email: this.email,
                        })
                        .then(response => {
                            this.loadings.login = false;
                            if (response.data.status == 'success') {
                                this.step = 1;
                            } else {
                                let error = response.data.error;
                                this.setError(error);
                            }
                        })
                        .catch(error => {
                            this.loadings.login = false;
                            error = error.response.data.error;
                            this.setError(error);
                        });
                },
                setError(error) {
                    if (Array.isArray(error)) {
                        this.errors.push(...error);
                    } else if (typeof error == 'object') {
                        for (let key in error) {
                            // if error is an array
                            if (Array.isArray(error[key])) {
                                this.errors.push(...error[key]);
                            } else {
                                this.errors.push(error[key]);
                            }
                        }
                    } else {
                        this.errors.push(error);
                    }
                }
            }
        }).mount('#login-app')



        const localLanguageArabe = '<?= session('lang') ?>'

        if(localLanguageArabe == 'ar'){

            document.querySelector('#login-app').setAttribute('dir',"rtl");

        }

    </script>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>



            const params = new Proxy(new URLSearchParams(window.location.search), {
                get: (searchParams, prop) => searchParams.get(prop),
              });

              if(params.id){
                Swal.fire({
                    title: 'Saisir votre email',
                    text:"Pour des raisons de sécurité, veuillez saisir votre email",
                    input: 'email',
                    inputAttributes: {
                      autocapitalize: 'off',
                      name:'email',

                    },
                    allowOutsideClick:false,
                    showCancelButton: false,
                    confirmButtonText: 'Valider',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                      return axios.post('/api/v2/check',{id:params.id})
                        .then(response => {

                            let value = $('input[name="email"]').val()

                         if(response.data.data[0].email !== value){

                            Swal.showValidationMessage(
                                `l'email suivant n'est pas autorisé à effectuer cette opération`
                              )
                         }

                         return response.data.data[0];

                        })
                        .catch(error => {
                          Swal.showValidationMessage(
                            `l'email suivant n'est pas autorisé à effectuer cette opération`
                          )
                        })
                    },

                  }).then((result) => {
                    if (result.isConfirmed) {

                        console.log(result);

                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmation effectuée avec succès 📧',
                            text: `L'email ${result.value.email} est confirmé 👍`,
                            footer: "<p style='color: black;margin: 0;font-size: 12px;display: flex;align-items: center;padding: 0 10px;margin-right: auto;justify-content: center; text-align:center;'>Copyright © 2022 Multilist</p>"
                          })

                    }
                  })
              }









    </script>

@endsection
