@extends('v2.dashboard')

@section('title1', "Modification mot de passe")

@section('custom_head1')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/profile.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/myprofile.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/editprofile.styles.css') }}">



     <style>

        .form-grid1{

            display: grid;
            margin-bottom: 20px;
        }
     </style>





@endsection

@section('content1')

<div id="profile" class="" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

    <div class="login-container">
        <div class="login-form">

            <div class="section-heading">
                <h1>{{__('general.infosmdp')}} :</h1>
                <div class="heading-underline"></div>
            </div>

            <div class="form-grid">

                {{-- form-group firstname --}}
                <div class="form-group">
                    <label for="firstname">{{__('general.Prénom')}} :</label>
                    <input type="text" v-model="form.firstname" class="form-control" id="firstname" placeholder="{{__('general.Votre prénom')}}" disabled>

                </div>

                {{-- form-group lastname --}}
                <div class="form-group">
                    <label for="lastname">{{__('general.Nom')}} :</label>
                    <input type="text" v-model="form.lastname" class="form-control"
                        id="lastname" placeholder="{{__('general.votre nom')}}" disabled>
                </div>
                {{-- form-group username --}}
                <div class="form-group">
                    <label for="username">{{__("general.Nom d'utilisateur")}} :</label>
                    <input type="text" class="form-control"
                        id="username" v-model="form.username" placeholder="Votre nom d'utilisateur" disabled>

                </div>
                {{-- form-group email --}}
                <div class="form-group">
                    <label for="email">{{__("general.Email")}} :</label>
                    <input type="email" v-model="form.email" class="form-control"
                       id="email" placeholder="{{__('general.Email')}}" disabled>

                </div>
                {{-- form-group company --}}


            {{-- form-group submit --}}

        </div>
    </div>

    <div class="login-container">
        <div class="login-form">

            <div class="section-heading">
                <h1>{{ __('general.Modifier mot de passe') }}</h1>
                <div class="heading-underline"></div>
            </div>

            <div class="form-grid1">


                <form>
                    <div class="form-group">
                        <label for="old">{{ __('general.mdpactu') }} : </label>
                        <input type="password" class="form-control"
                           id="old_password" name="old_password" v-model="old_password" placeholder="{{ __('general.mdpactu') }}"
                           :class="errors.old_password ? 'is-invalid' : ''"
                           >
                           <div class="invalid-feedback" >
                            @{{ errors.old_password?.join('<br />') }}
                        </div>

                           {{--  <div class="invalid-feedback">
                            @{{ errors.lastname?.join('<br />') }}
                        </div>  --}}
                    </div>



                    <div class="form-group">
                        <label for="password">{{__('general.nvmdp')}} : </label>
                        <input type="password" class="form-control"
                           id="password" name="password" v-model="password" placeholder="{{__('general.nvmdp')}}"
                           :class="errors.password ? 'is-invalid' : ''">

                           <div class="invalid-feedback">
                            @{{ errors.password?.join('<br />') }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">{{ __('general.Confirmez votre mot de passe') }}: </label>
                        <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation" v-model="password_confirmation" placeholder="{{ __('general.Confirmez votre mot de passe') }}"
                           :class="errors.password ? 'is-invalid' : ''">
                           <div class="invalid-feedback">
                           <span  v-if="errors.password === 'Le mot de passe ne correspond pas à la confirmation.'">
                            @{{ errors.password?.join('<br />') }}
                          </span>


                        </div>
                    </div>

                    <div class='form-group'>
                        <button @click.prevent="updatePassword()" type="submit" id="btn-login" class="btn btn-primary">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-grow-sm" v-if="loadings.login" role="status">
                                    <span class="sr-only">{{__('general.En cours')}}...</span>
                                </div>
                                <span id="loading-text" :class="loadings.login ? 'ms-2' : ''">
                                    <i class="fa-solid fa-gears" v-if="!loadings.login"></i> {{__('general.Modifier')}}
                                </span>
                            </div>
                        </button>
                    </div>
                </form>
        </div>
    </div>

</div>



<script src="/assets/vendor/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const id = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        let profileApp = createApp({
            data() {
                return {
                    form: {
                        id: '{{ Auth::user()->id }}',
                        username: '',
                        email: '',
                        firstname: '',
                        lastname: '',
                    },


                        old_password:'',
                        password:'',
                        password_confirmation:'',

                    loadings: {
                        login: false,
                    },
                    errors: {},
                    error: "",
                    loadings: {
                        login: false,
                    },
                }
            },

            mounted() {
                if (!id) window.location.href = "/";
                this.id = id;
                this.loadData();
            },
            methods: {
                loadData() {
                        axios.get(`api/v2/editProfileData?id=${this.form.id}`).then(response => {

                            const {id,username,lastname,email,firstname} = response.data.data;

                            this.form = {
                                 id:id,
                                 username:username,
                                 firstname:firstname,
                                 lastname:lastname,
                                 email:email

                            };


                        }).catch(error => {


                             console.log(error);
                        })
               },

               updatePassword(){

                   this.loadings.login = true;
                   axios.post('/api/v2/updatePassword',{

                       old_password:this.old_password,
                       password:this.password,
                       password_confirmation:this.password_confirmation
                   }).then(response => {
                    this.loadings.login = false;
                    if (response.data.success === true) {

                        console.log(response.data);

                        Swal.fire({
                            title: 'Modification du mot de passe est effectuée avec succès 👌',
                            confirmButtonText: 'Valider',
                          }).then((result) => {

                            if (result.isConfirmed) {

                                window.location.href="/dashboard";

                            }
                          })
                    }


                   }).catch(error =>{

                    console.log(error);

                    this.loadings.login = false;
                       if(typeof error.response?.data?.error === 'object'){

                          this.errors = error.response.data.error;

                       }


                   })
               }
        }
        }).mount('#profile');

    </script>
@endsection


@section('custom_foot1')

@endsection
