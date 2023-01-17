@extends('v2.layouts.dashboard')

@section('title', 'Liste des utilisateurs')

@section('custom_head')

    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    {{-- <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script> --}}

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>


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

        .coins-balance {
            background: #fcfcfc;
            color: #f2da3d;
            width: 100%;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .coins-balance .coins-nbr {
            font-weight: 600;
            font-size: 100px;
        }

        .coins-balance .coins-txt {
            font-size: 20px;
            margin-left: 5px;
        }

        .plan {
            background: #f5f5f5;
            color: rgb(48, 48, 48);
            width: 100%;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: 5px;
            border-style: solid;
            border-width: 1px;
            border-color: #cccccc;
            height: 150px;
            font-size: 14px;
            margin-bottom: 20px;
            cursor: pointer;
            overflow: hidden;
        }

        .plan.selected {
            background: #3974ff;
            border-color: #3064dd;
            color: white;
        }

        .plan:hover {
            border-color: #4b75d9;
        }
    </style>

@endsection

@section('content')

    <div class="pagetitle">
        <h1>Liste des utilisateurs :</h1>
    </div>

    <section class="section" id="app">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tableau des utilisateurs :</h5>
                        <div class="row mb-2">


                            <div class="col-12">
                                <fieldset class="filter_cnt">

                                    <legend>Filter:</legend>

                                    <div class="row">


                                    <div class="row col-sm-4">
                                        <label class="col-sm-4 col-form-label">Etat :</label>
                                        <div class="col-sm-8">
                                            <select @change="datatableSearch" v-model="filter.status" class="form-select"
                                                aria-label="Default select example">
                                                <option :value="null" selected>Tous</option>
                                                <option v-for="s of status_arr2" :value="s.val">
                                                    @{{ s.desc }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    @if (auth()->user()->hasRole('admin'))
                                    <div class="row col-sm-4">
                                        <label class="col-sm-4 col-form-label">Type d'utilisateur</label>
                                        <div class="col-sm-8">
                                            <select @change="datatableSearch" v-model="filter.usertypes" class="form-select">
                                                <option :value="null" selected>Tous</option>
                                                <option v-for="u of userstype_arr" :value="u.val">@{{u.desc}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    @if (auth()->user()->hasRole('moderator') || auth()->user()->hasRole('moderator manager') ||  auth()->user()->hasRole('ced') ||  auth()->user()->hasRole('ced manager'))
                                    <div class="row col-sm-4">
                                        <label class="col-sm-4 col-form-label">Type d'utilisateur</label>
                                        <div class="col-sm-8">
                                            <select @change="datatableSearch" v-model="filter.usertype" class="form-select">
                                                <option :value="null" selected>Tous</option>
                                                <option v-for="u of usertype_arr" :value="u.val">@{{u.desc}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif

                                    </div>


                                </fieldset>
                            </div>

                            <div class=" col-sm-2">
                                <select v-model="datatable.pagination.per_page" style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;" class="form-select" aria-label="Default select example">
                                    <option :value="20" selected>20</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="250">250</option>
                                    <option :value="500">500</option>
                                </select>
                            </div>

                            <div class="ms-auto col-lg-3 mt-2 mt-lg-0">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-search "></i>
                                    <input class="form-control ms-2 w-100" v-model="search" @keyup.enter="datatableSearch()"
                                        placeholder="Rechercher...">
                                </div>
                            </div>



                        </div>




                        <datatable-component :datatable="datatable" @selectedchanged="selectedChanged">
                            <template #status="props">
                                <div :class="'status_box s_' + props.column.value">@{{ getUserStatus(props.column.value) }}</div>
                            </template>
                            <template #user_type_designation="props">

                                <span v-if="props.column.value == 'admin'" class="rounded-text"
                                    style="background: 	#ec2525">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'moderator'" class="rounded-text"
                                    style="background: 	#f0f016">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'comercial'" class="rounded-text"
                                    style="background: #bdeb34">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'ced'" class="rounded-text"
                                    style="background: 	#20c153">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'agency'" class="rounded-text"
                                    style="background: #A233FF">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'particular'" class="rounded-text"
                                    style="background: 	#1e62ba">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'promoter'" class="rounded-text"
                                    style="background: 	#7c23ca">@{{ props.column.value }}</span>
                                <span v-else="" class="rounded-text"
                                    style="background: #262626">@{{ props.column.value ?? '-' }}</span>
                            </template>

                            <template #coins="props">

                                <span v-if="props.column.value == '0'"
                                    style="color: 	#ec2525; font-weight:bold;">@{{ props.column.value }} <i class="fa-solid fa-coins"></i>
                                </span>
                                <span v-else-if="props.column.value > '0'"
                                    style="color: 	#039c17; font-weight:bold;">@{{ props.column.value }} <i class="fa-solid fa-coins text-warning"></i>
                                </span>
                            </template>

                            <template #auth_type_designation="props">
                                <span v-if="props.column.value == 'normal'" class="rounded-text"
                                    style="background: rgb(241, 203, 53)">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'google'" class="rounded-text"
                                    style="background: #4285F4">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'facebook'" class="rounded-text"
                                    style="background: 	#4267B2">@{{ props.column.value }}</span>
                                <span v-else="" class="rounded-text"
                                    style="background: #262626">@{{ props.column.value ?? '-' }}</span>
                            </template>
                            <template #action="props">
                                <button class="btn p-0 m-0 me-2" title="Modifier" @click="editUser(props.row.value)">
                                    <i class="fas fa-edit text-success"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" title="Réglages" @click="userState(props.row.value)">
                                    <i class="fa-solid fa-gear "></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" title="Contrat" @click="userContract(props.row.value)">
                                    <i class="fa-solid fa-file-circle-check text-primary"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" title="Transactions" @click="userCoins(props.row.value)">
                                    <i class="fa-solid fa-coins text-warning"></i>
                                </button>
                                <button class="btn p-0 m-0 me-2" title="Switch to this account" @click='switchAccount(props.row.value)'>
                                  <i class="fa-solid fa-sign-in text-info"></i>
                                </button>
                                <button class="d-none btn p-0 m-0" title="Supprimé"
                                    @click="deleteUser(props.row.key,props.row.value)">
                                    <i class="fa-solid fa-circle-minus text-danger"></i>
                                </button>
                            </template>
                        </datatable-component>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div id="app-update-user">
        <popup-component :title="'Modifier l\'utilisateur'" :loading="loading" v-model:display="display" />
        <div class=" d-none popup-component-container">

            <section class="section">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Informations principales : </h5>
                                <div class="form-group">
                                    <label for="my-input"><small>Prénom :</small></label>
                                    <input v-model="user.firstname" :class="errors.firstname ? 'is-invalid' : ''"
                                        class="form-control" placeholder="Prénom" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.firstname?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Nom :</small></label>
                                    <input v-model="user.lastname" :class="errors.lastname ? 'is-invalid' : ''"
                                        class="form-control" placeholder="Nom de famille" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.lastname?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Username :</small></label>
                                    <input v-model="user.username" :class="errors.username ? 'is-invalid' : ''"
                                        class="form-control" placeholder="Username" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.username?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>E-mail :</small></label>
                                    <input v-model="user.email" :class="errors.email ? 'is-invalid' : ''"
                                        class="form-control" placeholder="E-mail" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.email?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Téléphone :</small></label>
                                    <input v-model="user.phone" :class="errors.phone ? 'is-invalid' : ''"
                                        class="form-control" type="text" id="phone">
                                    <div :class="errors.phone ? 'invalid-feedback invalidfeed':'invalid-feedback'">
                                        <span v-html="errors.phone?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Date de naissance :</small></label>
                                    <input v-model="user.birthdate" :class="errors.birthday ? 'is-invalid' : ''"
                                        class="form-control" placeholder="Date de naissance" type="date">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.birthday?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for=""><small>Type d'utilisateur :</small></label>
                                    <select name="property" v-model="user.usertype"
                                        :class="errors.usertype ? 'is-invalid' : ''" class="form-select"
                                        id="property-select">
                                        <option :value="null">Choisir un type d'utilisateur :</option>
                                        <option v-for="(utype,key) in userTypes" :value="utype.id">
                                            @{{ utype.designation }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.usertype?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for=""><small>CED :</small></label>
                                    <select name="property" :class="errors.assigned_ced ? 'is-invalid' : ''"
                                        v-model="user.assigned_ced" class="form-select" id="property-select">
                                        <option :value="null" selected>Choisir un CED :</option>
                                        <option v-for="(ced,key) in ceds" :value="ced.id">@{{ ced.username }}
                                        </option>
                                    </select>
                                </div>

                                {{-- <div class="form-group mt-2">
                                                    <label for=""><small>Password</small></label>
                                                    <input v-model="user.password" class="form-control" placeholder="Password"
                                                        type="password">
                                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Plus d'informations : </h5>
                                <div class="form-group mt-2">
                                    <label for=""><small>Image utilisateur :</small></label>
                                    <upload-files-component v-model:files="medias.userImage" :error="errors.user_image_id"
                                        type="images" :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                        :multiple="false" />
                                    <div class="invalid-feedback">
                                        <span v-html="errors.user_image_id?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-select"><small>Genre :</small></label>
                                    <select id="my-select" v-model="user.gender"
                                        :class="errors.gender ? 'is-invalid' : ''" class="form-control">
                                        <option :value="null" selected>Choisir un genre</option>
                                        <option value="male">Masculin</option>
                                        <option value="female">Féminin</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.gender?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-textarea"><small>Bio :</small></label>
                                    <textarea id="my-textarea" v-model="user.bio" :class="errors.bio ? 'is-invalid' : ''" class="form-control"
                                        rows="3"></textarea>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.bio?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2" v-for="(cnt,key) in contacts" :key="key">
                                    <label for="my-input"><small>@{{ cnt.type }} :</small></label>

                                    <div class="contact phone-contact">
                                        <div class="form-group mt-2 d-flex" v-for="(itm,key2) in cnt.values"
                                            :key="'phoneitem' + key + '_' + key2">
                                            <input class="form-control"
                                                :class="errors[`${cnt.type}.${key2}.value`] ? 'is-invalid' : ''"
                                                :placeholder="cnt.placeholder" v-model="itm.value" type="text">
                                            <button type="button" class="btn btn-danger ms-2"
                                                @click="deleteContact(cnt.type,key2)">
                                                x
                                            </button>
                                        </div>
                                        <button type="button" class="btn btn-success mt-2 w-100"
                                            @click="addAnotherContact(cnt.type)">add another @{{ cnt.itemName }}
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section" v-if="pro_users_types.indexOf(user.usertype) != -1">

                <div class="pagetitle">
                    <h1>Utilisateur pro :</h1>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Pro informations : </h5>
                                <div class="form-group">
                                    <label for="my-input"><small>Nom de l'entreprise :</small></label>
                                    <input v-model="user.company" :class="errors.company ? 'is-invalid' : ''"
                                        class="form-control" placeholder="Nom de l'entreprise" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.company?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Ville :</small></label>
                                    <select name="property" :class="errors.assigned_ced ? 'is-invalid' : ''"
                                        v-model="user.city" class="form-select" id="property-select">
                                        <option :value="null" selected>Choisir une ville</option>
                                        <option v-for="(city,key) in cities" :value="city.id">
                                            @{{ city.name }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.company_city?.join('<br/>')"></span>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Adresse :</small></label>
                                    <textarea v-model="user.address" class="form-control" :class="errors.company_address"
                                        placeholder="Adresse de l'entreprise" type="text"></textarea>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.company_address?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Site Internet :</small></label>
                                    <input v-model="user.website" class="form-control" :class="errors.company_address"
                                        placeholder="Adresse web" type="text">
                                    <div class="invalid-feedback">
                                        <span v-html="errors.company_address?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2 d-none">
                                    <label for=""><small>Image de l'entreprise :</small></label>
                                    <upload-files-component v-model:files="medias.companyImage"
                                        :error="errors.company_image_id ? true : false" type="images"
                                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                                </div>
                                <div class="form-group media-form-group mt-2">
                                    <label for=""><small>Bannière :</small></label>
                                    <upload-files-component v-model:files="medias.companyBanner"
                                        :error="errors.company_banner_id ? true : false" type="images"
                                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false" />
                                </div>

                                <div class="form-group mt-2">
                                    <label for="my-input"><small>Vidéo intégrée :</small></label>
                                    <textarea v-model="user.company_videoembed" :class="errors.company_videoembed" class="form-control"
                                        placeholder="embedded video">
                                                </textarea>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.company_videoembed?.join('<br/>')"></span>
                                    </div>
                                </div>

                                <div class="form-group media-form-group mt-2  d-none">
                                    <label for=""><small>Vidéo :</small></label>
                                    <upload-files-component v-model:files="medias.companyVideo"
                                        :error="errors.company_video_id ? true : false" type="videos"
                                        :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="false" />
                                </div>
                                <div class="form-group media-form-group mt-2  d-none">
                                    <label for=""><small>Audio :</small></label>
                                    <upload-files-component v-model:files="medias.companyAudio"
                                        :error="errors.company_audio_id ? true : false" type="audios"
                                        :allowed-extensions="['mpeg', 'mpga', 'mp3', 'wav', 'aac']"
                                        :multiple="false" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </section>

            <div class="row">
                <div class="col-12 d-flex">
                    <button class="btn btn-primary d-flex align-center justify-content-center ms-auto"
                        @click="updateUser">
                        <span>Sauvegarder</span>
                        <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        </popup-component>
    </div>

    <div id="app-user-state">
        <popup-component :title="'Etat d\'utilisateur'" :loading="loading" v-model:display="display" />
        <section class="popup-component-container d-none section">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Etat d'utilisateur</h5>
                            <div class="form-group">
                                <label for="my-select"><small>Etat :</small></label>
                                <select id="my-select" v-model="user_status"
                                    :class="errors.user_status ? 'is-invalid' : ''" class="form-control">
                                    <option value="">Sélectionner un état</option>
                                    <option v-for="(st,key) in statusObjs" :value="key">@{{ key }}
                                        @{{ st }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-html="errors.user_status?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <button type="button" @click="updateUserStatus()"
                                    class="btn btn-success ms-auto">Sauvegarder</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Changer le mot de passe</h5>
                            <!-- input password and cofirm password -->
                            <div class="form-group">
                                <label for="my-input"><small>Mot de passe</small></label>
                                <input class="form-control" v-model="resetPassword.password"
                                    :class="errors.password ? 'is-invalid' : ''" placeholder="Mot de passe"
                                    type="password">

                                <div class="invalid-feedback">
                                    <span v-html="errors.password?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-input"><small>Confirmer le mot de passe</small></label>
                                <input class="form-control" v-model="resetPassword.password_confirmation"
                                    placeholder="Confirmer le mot de passe"
                                    :class="errors.password_confirmation ? 'is-invalid' : ''" type="password">
                                <div class="invalid-feedback">
                                    <span v-html="errors.password_confirmation?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <button type="button" @click="saveNewPassword()"
                                    class="btn btn-success ms-auto">Sauvegarder</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </popup-component>
    </div>

    <div id="app-user-contract">
        <popup-component :title="'Contrat d\'utilisateur'" :loading="loading" v-model:display="display" />
        <section class="popup-component-container d-none section">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mt-4 w-100" v-if="contract.id !== null">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-success" @click="newContract">
                                        Nouvelle contrat
                                    </button>
                                </div>
                            </div>
                            <h5 class="card-title"><small>Contrat informations :</small></h5>
                            <div class="form-group">
                                <label for="my-select"><small>Commercial :</small></label>
                                <select id="my-select" :class="errors.assigned_user ? 'is-invalid' : ''"
                                    v-model="contract.comercial" class="form-control">
                                    <option value=""> Choisir un commercial </option>
                                    <option v-for="com in commercials" :value="com.id">@{{ com.username }}
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    <span v-html="errors.assigned_user?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="form-group mt-2">
                                <label for="my-select"><small>Plan :</small></label>
                                <div class="row">
                                    <div class="col-lg-2 col-6" v-for="plan in plans">
                                        <div class="plan" @click="selectPlan(plan)"
                                            :class="selectedPlan == plan ? 'selected' : ''">
                                            <div class="d-flex align-items-center"><strong>Nom :</strong><span
                                                    class="ms-auto">@{{ getPlanName(plan) }}</span></div>
                                            <div class="d-flex align-items-center"><strong>Annonces :</strong><span
                                                    class="ms-auto">@{{ plan.ads_nbr }}</span></div>
                                            <div class="d-flex align-items-center"><strong>Coins :</strong><span
                                                    class="ms-auto">@{{ plan.ltc_nbr }} ltc</span></div>
                                            <div class="d-flex align-items-center"><strong>price :</strong><span
                                                    class="ms-auto">@{{ plan.price }} dh</span></div>
                                            <div class="d-flex align-items-center"><strong>Durée :</strong><span
                                                    class="ms-auto">@{{ plan.duration }} jours</span></div>
                                        </div>
                                    </div>
                                    <div v-if="plans.length == 0" class="col-lg-2 col-6">
                                        <div class="plan">
                                            <div v-if="loading" class="spinner-border spinner-border-sm m-auto"
                                                role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <div v-else class="mx-auto">
                                                <span>Aucun plan</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="span text-danger">
                                    <span v-html="errors.plan_id?.join('<br/>')"></span>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Nombre d'annonces :</small></label>
                                    <input type="number" placeholder="Nombre d'annonces"
                                        :class="errors.ads_nbr ? 'is-invalid' : ''" min="1"
                                        v-model="contract.nbr_annonces" class="form-control" required>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.ads_nbr?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Coins :</small></label>
                                    <input type="number" placeholder="Coins" :class="errors.ltc_nbr ? 'is-invalid' : ''"
                                        min="1" v-model="contract.coins" class="form-control" required>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.ltc_nbr?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Prix :</small></label>
                                    <input type="number" placeholder="Prix" min="1"
                                        :class="errors.price ? 'is-invalid' : ''" v-model="contract.price"
                                        class="form-control" v-model="" required>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.price?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Date de début :</small></label>
                                    <input type="date" placeholder="" v-model="contract.start_date"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Durée :</small></label>
                                    <input type="number" placeholder="Durée par jours"
                                        :class="errors.duration ? 'is-invalid' : ''" min="1"
                                        v-model="contract.duration" class="form-control" required>
                                    <div class="invalid-feedback">
                                        <span v-html="errors.duration?.join('<br/>')"></span>
                                    </div>
                                </div>
                                <div class="form-group mt-2">
                                    <label><small>Commentaire :</small></label>
                                    <textarea class="form-control" placeholder="Commentaire" v-model="contract.comment" rows="3" required></textarea>
                                </div>
                                <div class="form-group mt-2 d-none">
                                    <label>Contrat scanné :</label>
                                    <upload-files-component v-model:files="contract_files" type="images"
                                        :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']" :multiple="false">
                                    </upload-files-component>
                                </div>
                                <div class="d-flex flex-row-reverse">
                                    <button type="submit" class="btn btn-success mt-2"
                                        @click="saveContract">Sauvegarder</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        </popup-component>
    </div>

    <div id="app-user-coins">
        <popup-component :title="'Les coins d\'utilisateur'" v-model:display="display" :loading="loading" />
        <section class="popup-component-container d-none section">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Solde : </h5>
                            <div class="coins-balance">
                                <span>
                                    <span class="coins-nbr">@{{ coins.current }}</span>
                                    <span class="coins-txt">ltc</span>
                                </span>
                            </div>
                            <h5 class="card-title">Nouvelle transaction : </h5>
                            <div class="mb-3">
                                <label for="" class="form-label">Nombre de coins :</label>
                                <div class="d-flex items-app-center justify-content-center">
                                    <input type="number" :class="errors.coins ? 'is-invalid' : ''" class="form-control"
                                        v-model="coins.new">
                                    <button
                                        class="btn btn-danger btn-sm ms-2 d-flex align-items-center justify-content-center"
                                        @click="coins.new-=100">
                                        <i style="font-size: 8px" class="fa me-1 fa-minus"></i>100
                                    </button>
                                    <button
                                        class="btn btn-primary btn-sm ms-2 d-flex align-items-center justify-content-center"
                                        @click="coins.new+=100">
                                        <i style="font-size: 8px" class="fa me-1 fa-plus"></i>100
                                    </button>
                                </div>
                                <div class="text-danger">
                                    <span v-html="errors.coins?.join('<br/>')"></span>
                                </div>
                            </div>
                            <div class="d-flex mt-2">
                                <button type="button" class="btn btn-success w-100 ms-auto"
                                    @click="newTransaction">Confirmer</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Transactions : </h5>
                            <fieldset class="filter_cnt">

                            <legend>Filtrer:</legend>

                            <div class="row">



                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Date :</label>
                                    <div class="col-sm-8">
                                        <div class="inner-addon right-addon">
                                            <i class="fa fa-calendar glyphicon"></i>
                                            <input type="text" id="datepicker" class="form-control" />
                                        </div>
                                    </div>
                                </div>




                            </div>



                        </fieldset>

                            <datatable-component :ref="datatable.key" :datatable="datatable"></datatable-component>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </popup-component>
    </div>


@endsection

@section('custom_foot')

    <script type="text/javascript">
        let app = Vue.createApp({
            data() {
                return {
                    pro_users_types: [4, 3],
                    search: '',
                    userTypes: [],
                    usertype_arr: usertype_arr,
                    userstype_arr: usertypes,
                    filter: {
                        status: null,
                        usertype : null,
                        usertypes : null,

                    },
                    datatable: {
                        key: 'create_users_datatable',
                        api: '/api/v2/user/filter',
                        selectable_multiple: true,
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: false,
                                hide: false
                            },
                            {
                                name: 'Prénom',
                                field: 'firstname',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: true
                            },
                            {
                                name: 'Nom',
                                field: 'lastname',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: true
                            },
                            {
                                name: 'Utilisateur',
                                field: 'username',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: false
                            },
                            {
                                name: 'Email',
                                field: 'email',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: false
                            },
                            {
                                name: 'Téléphone',
                                field: 'phone',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Entreprise',
                                field: 'company',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: false
                            },
                            {
                                name: 'Type d\'utilisateur',
                                field: 'user_type_designation',
                                type: 'string',
                                sortable: true,
                                searchable: true,
                                hide: false,
                                customize: true
                            },
                            {
                                name: 'Auth type',
                                field: 'auth_type_designation',
                                type: 'string',
                                sortable: true,
                                searchable: false,
                                hide: false,
                                customize: true
                            },
                            {
                                name: 'Annonces',
                                field: 'nbr_ads',
                                type: 'number',
                                sortable: true,
                                searchable: false,
                                hide: false
                            },
                            {
                                name: 'Coins',
                                field: 'coins',
                                type: 'number',
                                sortable: true,
                                searchable: true,
                                customize : true
                            },
                            {
                                name: 'Date d\'inscription',
                                field: 'created_at',
                                type: 'date',
                                sortable: true,
                                searchable: true,
                                hide: false
                            },
                            {
                                name: 'Date de modification',
                                field: 'updated_at',
                                type: 'date',
                                sortable: true,
                                searchable: true,
                                hide: true
                            },
                            {
                                name: 'Statut',
                                field: 'status',
                                type: 'foreign',
                                sortable: true,
                                searchable: true,
                                customize: true,
                                filtrable: true,
                                join: []
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
                        input_filters: [],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 30,
                            total: 0,
                            links: []
                        },
                        loadingrows: [],
                        show_controls: {
                            settings: true,
                            export_xlsx: true,
                            export_json: true,
                            search_input: true,
                            pagination_selection: true,
                            pagination_buttons: true,
                            filters: true
                        }
                    },
                    errors: {}
                }
            },
            computed: {
                status_arr2: function() {
                    return status_arr2;
                },
            },
            components: {
                'DatatableComponent': DatatableComponent,
            },
            watch: {},
            created() {
                // find column statut in datatable
                let column = this.datatable.columns.find(column => column.field == 'status');
                let column2 = this.datatable.columns.find(column => column.field == 'usertype');
                if (column) {
                    column.join = [];
                    this.status_arr2.forEach(status => {
                        column.join.push({
                            label: status.desc,
                            value: status.val
                        });
                    });
                }
                if (column2) {
                    column2.join = [];
                    this.userTypes.forEach(status => {
                        column2.join.push({
                            label: userTypes.designation,
                            value: userTypes.val
                        });
                    });
                }
            },
            mounted() {
                this.getUserTypes();
            },
            methods: {

                getUserTypes() {
                    axios.get('/api/v2/user/types').then(response => {
                        if (response.data.data) {
                            console.log('resp',response.data.data);
                            this.userTypes = response.data.data;
                            // add loaded types types to appUpdateUser vue object
                            // appUpdateUser.userTypes = this.userTypes.filter((ut) => {
                            //     return [4, 5, 6].indexOf(ut.id) != -1
                            //     // return true;
                            // });
                            appUpdateUser.userTypes = this.userTypes;

                        }
                    }).catch(error => {
                        console.log(error);
                    });
                },
                getUserStatus(id) {

                    let status_obj = {};
                    status_arr2.forEach(function(status) {
                        status_obj[status.val] = status.desc;
                    });

                    return status_obj[id] ?? '-';
                },

                userTypeById(id) {
                    let userType = this.userTypes.find(userType => userType.id == id);
                    return userType ? userType.designation : '';
                },
                datatableSearch() {
                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [{
                                type: 'where',
                                col: 'id',
                                val: `%${this.search}%`,
                                op: '='
                            },
                            {
                                type: 'orWhere',
                                col: 'firstname',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'lastname',
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
                                col: 'users.email',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'users.phone',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'usertype',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            }
                        ]
                    }];

                    if (this.filter.status)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'users.status',
                            val: `${this.filter.status}`,
                            op: '='
                        });

                        if (this.filter.usertype)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'usertype',
                            val: `${this.filter.usertype}`,
                            op: '='
                        });

                        if (this.filter.usertypes)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'usertype',
                            val: `${this.filter.usertypes}`,
                            op: '='
                        });

                        console.log('filters',this.filter.usertype);



                },

                getUser(user){



                    localStorage.setItem('profile_id',user.id);
                    window.location.href='/admin/profile';


                },
                editUser(user) {
                    appUpdateUser.userId = user.id;
                    appUpdateUser.display = true;
                },
                userState(user) {
                    appUserState.userId = user.id;
                    appUserState.display = true;
                },
                userContract(user) {
                    appUserContract.userId = user.id;
                    appUserContract.display = true;
                },
                userCoins(user) {
                    appUserCoins.userId = user.id;
                    appUserCoins.display = true;
                },
                switchAccount(data){

                    console.log(data)
                    if(data.usertype != 1){
                        window.switchedAccountFrom = JSON.parse(localStorage.getItem('auth')).email
                        window.switchedAccountName = JSON.parse(localStorage.getItem('auth')).username
                        axios({
                        method: 'post',
                        url: '/api/v2/switchUser',
                        data: {
                            email: data.email,
                        }
                        })
                        .then(function (response) {
                            console.log('response data: ',response.data)
                            let data = response.data;
                            let token = data.data.token;
                            let auth = data.data.auth;
                            let status = data.success

                            // store the token & auth
                            localStorage.setItem('token', token);
                            localStorage.setItem('auth', JSON.stringify(auth));
                            if(status == true){
                                localStorage.setItem('switchedAccount', window.switchedAccountFrom);
                                localStorage.setItem('switchedAccountName', window.switchedAccountName);
                                localStorage.setItem('switchedAccountSuccess', true);
                            }

                            window.location = '/dashboard';
                        });
                    }else{
                        Swal.fire({
                        icon: 'info',
                        title: 'Oops...',
                        text: "Vous ne pouvez pas basculer vers ce type de compte !",
                        })
                    }

                },
                deleteUser(key, user) {
                    // url : /api/v2/user/updatestate/{id}
                    this.datatable.loadingrows.push(key);

                    axios.post('/api/v2/user/updatestate/' + user.id, {
                            status: "70" // status supprimé
                        })
                        .then(response => {
                            if (response.data.success) {

                                // remove all loaded rows from datatable loadingrows array
                                this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                    loaded_row => loaded_row != key
                                );

                                this.datatable.rows.forEach((row) => {
                                    if (row.id == user.id) {
                                        row.status = "70";
                                        return;
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            // remove all loaded rows from datatable loadingrows array
                            this.datatable.loadingrows = this.datatable.loadingrows.filter(
                                loaded_row => loaded_row != key
                            );
                            console.log(error);
                        });
                },
                selectedChanged(rows) {
                    console.log(rows);
                }
            },
        }).mount('#app');

        let appUpdateUser = Vue.createApp({
            data() {
                return {
                    pro_users_types: [4, 3],
                    loading: false,
                    userId: -1,
                    display: false,
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
                        usertype: '',
                        user_image_id: '',
                        gender: '',
                        bio: '',
                        assigned_ced: '',
                        phones: [],
                        emails: [],
                        whatsapp: [],
                        company: '',
                        city: '',
                        address: '',
                        website: '',
                        company_image_id: '',
                        company_banner_id: '',
                        company_video_id: ''
                    },
                    errors: {},
                    assigned_ced: '',
                    ceds: [],
                    cities: [],
                    loadings: {
                        ceds: false
                    }
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent,
                'PopupComponent': PopupComponent
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
                userId: function(newVal, oldVal) {
                    this.resetForm();
                    if (newVal > 0) {
                        this.loadUser();
                    } else {
                        this.display = false;
                    }
                },
                errors: {
                    handler: function(newVal, oldVal) {
                        if (Object.keys(newVal).length > 0) {
                            // scroll to the top of the page
                            // get #app-update-user .popup-body
                            let popupBody = document.querySelector('#app-update-user .popup-body');
                            popupBody.scrollTop = 0;
                            this.loading = false;
                        }
                    },
                    deep: true
                },
                ceds: []
            },
            mounted() {
                //this.loadUserTypes();
                this.loadCeds();
                this.loadCities();
                this.watchUserAttributes();
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
                loadUser() {
                    if (this.userId == -1) {
                        this.display = false;
                        return;
                    }

                    this.loading = true;

                    axios.get(`/api/v2/user/${this.userId}`)
                        .then(response => {

                            this.loading = false;

                            if (response.data.success) {
                                this.user = response.data.data;

                                if (this.user.avatar?.id)
                                    this.medias.userImage[0] = {
                                        id: this.user.avatar.id,
                                        name: `${this.user.avatar.path}${this.user.avatar.filename}.${this.user.avatar.extension}`
                                    }

                                if (this.user.image?.id)
                                    this.medias.companyImage[0] = {
                                        id: this.user.image.id,
                                        name: `${this.user.image.path}${this.user.image.filename}.${this.user.image.extension}`
                                    }

                                if (this.user.audio?.id)
                                    this.medias.companyAudio[0] = {
                                        id: this.user.audio.id,
                                        name: `${this.user.audio.path}${this.user.audio.filename}.${this.user.audio.extension}`
                                    }

                                if (this.user.video?.id)
                                    this.medias.companyVideo[0] = {
                                        id: this.user.video.id,
                                        name: `${this.user.video.path}${this.user.video.filename}.${this.user.video.extension}`
                                    }

                                if (this.user.probannerimg?.id)
                                    this.medias.companyBanner[0] = {
                                        id: this.user.probannerimg.id,
                                        name: `${this.user.probannerimg.path}${this.user.probannerimg.filename}.${this.user.probannerimg.extension}`
                                    }

                                if (this.user.contacts) {

                                    let contacts = this.user.contacts;

                                    let phonesContacts = this.contacts.find(contact => contact.type ==
                                        'phones');
                                    let emailsContacts = this.contacts.find(contact => contact.type ==
                                        'emails');
                                    let whatsappContacts = this.contacts.find(contact => contact.type ==
                                        'whatsapp');

                                    console.log(phonesContacts);
                                    console.log(emailsContacts);
                                    console.log(whatsappContacts);

                                    for (let i = 0; i < contacts.length; i++) {
                                        let contact = contacts[i];

                                        if (contact.type == 'phone') {
                                            phonesContacts.values.push({
                                                id: contact.id,
                                                value: contact.value
                                            });
                                        } else if (contact.type == 'email') {
                                            emailsContacts.values.push({
                                                id: contact.id,
                                                value: contact.value
                                            });
                                        } else if (contact.type == 'whatsapp') {
                                            whatsappContacts.values.push({
                                                id: contact.id,
                                                value: contact.value
                                            });
                                        }

                                    }
                                }
                            }
                        })
                        .catch(error => {
                            this.loading = false;

                            console.log(error);
                            this.display = false;
                        });

                },
                updateUser() {

                    if (this.loading) {
                        return;
                    }

                    // check if errors is empty
                    if (!this.updateUserValidation()) {
                        return;
                    }

                    swal({
                            title: "Êtes-vous sûr?",
                            text: "Modifier les informations de l'utilisateur",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                // using acios post uset to /api/v2/user/update/{id}
                                this.loading = true;

                                axios.post('/api/v2/user/update/' + this.userId, this.user)
                                    .then(response => {

                                        this.loading = false;

                                        if (response.data.success) {

                                            if(response.data?.data?.user)
                                                this.user = response.data.data.user;

                                            this.display = false;

                                            app.datatable.rows.forEach((row) => {
                                                if (row.id == this.userId) {

                                                    // loop for each key in the row
                                                    for(let key in row) {
                                                        if(key == 'id')
                                                            continue;
                                                        if(!this.user[key])
                                                            continue;
                                                        row[key] = this.user[key];
                                                    }

                                                    return;
                                                }
                                            });

                                            this.resetForm();
                                            this.userId = -1;

                                            displayToast("L'utilisateur est modifiée avec succès", "green");
                                        } else {
                                            this.errors = response.data.message;
                                            displayToast(response.data.message??'Quelque chose s\'est mal passé', "red");
                                        }

                                    })
                                    .catch(error => {
                                        this.loading = false;
                                        this.errors = error.response.data.error;
                                        var err = error.response.data.error;
                                        displayToast(err, '#842029');
                                    });
                            }
                        });


                },
                updateUserValidation() {
                    this.errors = {};
                    // validation

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
                        this.errors.email.push('E-mail est obligatoire');
                    }
                    // email should respect this regix ^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$
                    if (!this.user.email.match(/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/)) {
                        if (!this.errors.email)
                            this.errors.email = [];
                        this.errors.email.push('E-mail doit être valide');
                    }

                    if (!this.user.phone) {
                        if (!this.errors.phone)
                            this.errors.phone = [];
                        this.errors.phone.push('Téléphone est requis');
                    }
                    // phone should respect this regix ^[0-9]{6,20}$
                /*     if (!this.user.phone.match(/^[0-9]{6,20}$/)) {
                        if (!this.errors.phone)
                            this.errors.phone = [];
                        this.errors.phone.push(
                            'Téléphone doit contenir uniquement des chiffres, au minimum 6 caractères et au maximum 20'
                            );
                    } */


                    if (!this.user.usertype) {
                        if (!this.errors.usertype)
                            this.errors.usertype = [];
                        this.errors.usertype.push('Le type d\'utilisateur est obligatoire');
                    }


                    if (this.pro_users_types.indexOf(this.user.usertype) != -1) {
                        if (!this.user.company) {
                            if (!this.errors.company)
                                this.errors.company = [];
                            this.errors.company.push('Le nom de l\'entreprise est obligatoire');
                        }
                    }


                    if (Object.keys(this.errors).length > 0) {
                        return false;
                    }

                    return true;
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
                        usertype: '',
                        user_image_id: '',
                        gender: '',
                        bio: '',
                        phones: [],
                        emails: [],
                        whatsapp: [],
                        company: '',
                        city: '',
                        address: '',
                        website: '',
                        company_image_id: '',
                        company_banner_id: '',
                        company_video_id: ''
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
                    this.errors = {};
                },
                getUserStatus(id) {
                    return status_obj[id] ?? '-';
                },
                loadUserTypes() {
                    // url : /api/v2/user/types
                    axios.get('/api/v2/user/types')
                        .then(response => {
                            console.log(response.data);
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
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });

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
                }
            }
        }).mount('#app-update-user');

        let appUserState = Vue.createApp({
            data() {
                return {
                    userId: -1,
                    display: false,
                    loading: false,
                    resetPassword: {
                        password: '',
                        password_confirmation: ''
                    },
                    user_status: null,
                    errors: {}
                }
            },
            components: {
                'PopupComponent': PopupComponent
            },
            computed: {
                statusObjs: function() {

                    let status_obj = {};
                    status_arr2.forEach(function(status) {
                        status_obj[status.val] = status.desc;
                    });

                    return status_obj;



                }
            },
            watch: {
                userId(newVal, oldVal) {
                    if (newVal != -1) {
                        this.display = true;
                        this.resetForm();
                        this.loadUserStatus();
                    } else {
                        this.display = false;
                    }
                }
            },
            mounted() {},
            methods: {
                saveNewPassword() {
                    if (this.loading) {
                        return;
                    }

                    this.errors = {};

                    // check if password is not null
                    if (!this.resetPassword.password) {
                        if (!this.errors.password) {
                            this.errors.password = [];
                        }
                        this.errors.password.push('Mot de passe est obligatoire');
                    }

                    // check if password and password_confirmation are equal
                    if (this.resetPassword.password != this.resetPassword.password_confirmation) {
                        if (!this.errors.password_confirmation) {
                            this.errors.password_confirmation = [];
                        }
                        this.errors.password_confirmation.push(
                            'Le mot de passe et la confirmation doivent être identiques');
                    }

                    // check if password is at least 6 characters long
                    if (this.resetPassword.password.length < 8) {
                        if (!this.errors.password) {
                            this.errors.password = [];
                        }
                        this.errors.password.push('Le mot de passe doit contenir au moins 8 caractères');
                    }

                    // check if errors is empty
                    if (Object.keys(this.errors).length > 0) {
                        return;
                    }

                    // using axios post uset to /api/v2/user/changepassword/{id}
//
                    swal({
                            title: "Êtes-vous sûr?",
                            text: "Vous êtes sur le point de modifier le mot de passe de cet utilisateur",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                this.loading = true;

                                axios.post('/api/v2/user/changepassword/' + this.userId, this.resetPassword)
                                    .then(response => {


                                        this.loading = false;
                                        this.resetPassword = {
                                            password: '',
                                            password_confirmation: ''
                                        };
                                        this.resetForm();

                                        // swal("Le mot de passe est mis à jour avec succès", {
                                        //     icon: "success",
                                        // });
                                        displayToast("Le mot de passe est mis à jour avec succès", "green");

                                    })
                                    .catch(error => {
                                        this.loading = false;
                                        var err = error.response.data.error;
                                        displayToast(err, '#842029');
                                    });


                            }
                        });

                },
                getUserStatus(id) {
                    return status_obj[id] ?? '-';
                },
                loadUserStatus() {
                    // url : /api/v2/user/status/{id}
                    this.loading = true;
                    axios.get('/api/v2/user/status/' + this.userId)
                        .then(response => {
                            this.loading = false;
                            console.log(response);
                            if (response.data.success) {
                                this.user_status = response.data.data;
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            console.log(error);
                        });
                },
                updateUserStatus() {

                    if (this.loading) {
                        return;
                    }

                    this.errors = {};

                    console.log(this.errors, this.user_status);

                    if (!this.user_status) {
                        if (!this.errors.user_status) {
                            this.errors.user_status = [];
                        }
                        this.errors.user_status.push('Selectionner un statut');
                    }

                    // check if suer_status in statusObjs
                    if (!this.statusObjs[this.user_status]) {
                        if (!this.errors.user_status) {
                            this.errors.user_status = [];
                        }
                        this.errors.user_status.push('Statut invalide');
                    }

                    if (Object.keys(this.errors).length > 0) {
                        return;
                    }

                    swal({
                            title: "Êtes-vous sûr?",
                            text: "Vous êtes sur le point de changer l'état de cet utilisateur",
                            icon: "warning",
                            buttons: true,
                            dangerMode: true
                        })
                        .then((willDelete) => {
                            if (willDelete) {

                                this.loading = true;

                                // url : /api/v2/user/updatestate/{id}
                                axios.post('/api/v2/user/updatestate/' + this.userId, {
                                        status: this.user_status
                                    })
                                    .then(response => {

                                        // swal("L'etat d'utilisateur est modifiée avec succès", "", "success")
                                        //     .then((value) => {

                                        this.loading = false;
                                        if (response.data.success) {
                                            this.display = false;
                                            app.datatable.rows.forEach((row) => {
                                                if (row.id == this.userId) {
                                                    row.status = this.user_status;
                                                    return;
                                                }
                                            })
                                            this.resetForm();
                                            this.userId = -1;
                                        }

                                        displayToast("L'état d'utilisateur est modifiée avec succès",
                                            "green");

                                        // });

                                    })
                                    .catch(error => {
                                        this.loading = false;
                                        var err = error.response.data.error;
                                        displayToast(err, '#842029');
                                    });
                            }
                        });

                },
                resetForm() {
                    this.resetPassword = {
                        password: '',
                        password_confirmation: ''
                    };
                    this.user_status = null;
                }
            }
        }).mount('#app-user-state');

        let appUserContract = Vue.createApp({
            data() {
                return {
                    userId: -1,
                    display: false,
                    loadings: {
                        user_last_contract: false,
                        commercials: false,
                        plans: false,
                        save_contract: false
                    },
                    contract: {
                        id: null,
                        comercial: '',
                        plan: '',
                        nbr_annonces: '',
                        coins: '',
                        price: '',
                        start_date: '',
                        duration: '',
                        comment: '',
                        contract_file: ''
                    },
                    contract_files: [],
                    commercials: [],
                    plans: [],
                    selectedPlan: {},
                    errors: {}
                }
            },
            computed: {
                loading: function() {
                    // loop through loading and return true if one is true else false
                    for (let key in this.loadings) {
                        if (this.loadings[key]) {
                            return true;
                        }
                    }
                    return false;
                }
            },
            components: {
                'PopupComponent': PopupComponent,
                "uploadFilesComponent": uploadFilesComponent,
            },
            watch: {
                userId(newVal, oldVal) {
                    if (newVal != -1) {
                        this.display = true;
                        this.resetForm();
                        this.loadUserLastContract();
                    } else {
                        this.display = false;
                    }
                },
                contract_files(newVal, oldVal) {
                    if (newVal.length > 0) {
                        this.contract.contract_file = newVal[0].id;
                    }
                },
                errors: {
                    handler: function(newVal, oldVal) {
                        if (Object.keys(newVal).length > 0) {
                            // scroll to the top of the page
                            // get #app-update-user .popup-body
                            let popupBody = document.querySelector('#app-user-contract .popup-body');
                            popupBody.scrollTop = 0;
                            this.loading = false;
                        }
                    },
                    deep: true
                }
            },
            mounted() {
                this.resetForm();
                this.loadCommercials();
                this.loadPlans();
                this.watchContractAttributes();
            },
            methods: {
                loadUserLastContract() {

                    // url : /api/v2/user/lastcontract/{id}
                    this.loadings.user_last_contract = true;
                    axios.get('/api/v2/user/lastcontract/' + this.userId)
                        .then(response => {
                            this.loadings.user_last_contract = false;
                            console.log(response);
                            if (response.data.success) {
                                if (response.data.data.id) {
                                    console.log(response.data.data);

                                    this.contract.id = response.data.data.id;
                                    this.contract.comercial = response.data.data.assigned_user;
                                    this.contract.plan = response.data.data.plan_id;
                                    this.contract.nbr_annonces = response.data.data.ads_nbr;
                                    this.contract.coins = response.data.data.ltc_nbr;
                                    this.contract.price = response.data.data.price;
                                    this.contract.start_date = moment(response.data.data.date).format(
                                        'YYYY-MM-DD');
                                    this.contract.duration = response.data.data.duration;
                                    this.contract.comment = response.data.data.comment;

                                    let contract_file = response.data.data.contract_file;


                                    if (contract_file?.id) {
                                        console.log(contract_file);
                                        this.contract_files[0] = {
                                            id: contract_file.id,
                                            name: `${contract_file.path}${contract_file.filename}.${contract_file.extension}`
                                        };
                                    } else {
                                        this.contract.contract_file = contract_file;
                                    }


                                    // selected plan
                                    this.selectedPlan = this.plans.find(plan => plan.id == this.contract
                                        .plan);

                                } else {
                                    this.resetForm();
                                }
                            }
                        })
                        .catch(error => {
                            this.loadings.user_last_contract = false;
                            console.log(error);
                        });
                },
                loadCommercials() {

                    // using users filter load onliy user type comercials
                    this.loadings.commercials = true;
                    axios.post('/api/v2/user/filter/', {
                            where: [{
                                type: 'where',
                                col: 'usertype',
                                val: '7',
                                op: '='
                            }]
                        })
                        .then(response => {
                            this.loadings.commercials = false;
                            console.log("load commercials ", response);
                            if (response.data.success) {
                                this.commercials = response.data.data;
                            }
                        })
                        .catch(error => {
                            this.loadings.commercials = false;
                            console.log(error);
                        });

                },
                loadPlans() {

                    this.loadings.plans = true;
                    axios.post('/api/v2/plans/filter/', {
                            where: []
                        })
                        .then(response => {
                            this.loadings.plans = false;
                            console.log(response);
                            if (response.data.success) {
                                this.plans = response.data.data;
                            }
                        })
                        .catch(error => {
                            this.loadings.plans = false;
                            console.log(error);
                        });

                },
                saveContract() {

                    if (this.contract.id == null) {
                        this.createContract();
                    } else {
                        this.updateContract();
                    }

                },
                createContract() {
                    if (this.loadings.save_contract) {
                        return;
                    }

                    if (!this.createContratValidation()) {
                        return;
                    }

                    // url : featecontract/{id}
                    this.loadings.save_contract = true;
                    axios.post('/api/v2/user/createcontract/' + this.userId, {
                            assigned_user: this.contract.comercial,
                            plan_id: this.contract.plan,
                            ads_nbr: this.contract.nbr_annonces,
                            ltc_nbr: this.contract.coins,
                            price: this.contract.price,
                            date: this.contract.start_date,
                            duration: this.contract.duration,
                            comment: this.contract.comment,
                            contract_file: this.contract.contract_file
                        })
                        .then(response => {
                            this.loadings.save_contract = false;

                            if (response.data.success) {

                                // swal({
                                //     title: "Contrat a été créé avec succès",
                                //     text: "",
                                //     icon: "success",
                                //     button: false,
                                //     timer: 3000
                                // }).then(() => {

                                // this.contract = response.data.data;
                                this.display = false;

                                app.datatable.rows.forEach(row => {
                                    if (row.id == this.userId) {
                                        if (response.data?.data?.user_new_balance) {
                                            row.coins = response.data?.data
                                                ?.user_new_balance;
                                        }
                                    }
                                });

                                appUserCoins.userId = this.userId;
                                appUserCoins.loadData();

                                this.resetForm();

                                this.userId = -1;

                                displayToast("Contrat a été créé avec succès", "green");

                                // });

                            }
                        })
                        .catch(error => {
                            this.loadings.save_contract = false;
                            this.errors = error.response.data.error;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });
                },
                createContratValidation() {

                    this.errors = {};

                    /*if (!this.contract.comercial) {
                        if (!this.errors.assigned_user) {
                            this.errors.assigned_user = [];
                        }
                        this.errors.assigned_user.push('Veuillez choisir un commercial');
                    }*/

                    if (!this.contract.nbr_annonces) {
                        if (!this.errors.ads_nbr) {
                            this.errors.ads_nbr = [];
                        }
                        this.errors.ads_nbr.push('Veuillez entrer le nombre d\'annonces');
                    }
                    // contract nbr_annonces must be greater than 0
                    if (this.contract.nbr_annonces <= 0) {
                        if (!this.errors.ads_nbr) {
                            this.errors.ads_nbr = [];
                        }
                        this.errors.ads_nbr.push('Veuillez entrer un nombre d\'annonces supérieur à 0');
                    }

                    /*if (!this.contract.coins) {
                        if (!this.errors.ltc_nbr) {
                            this.errors.ltc_nbr = [];
                        }
                        this.errors.ltc_nbr.push('Veuillez entrer le nombre de coins');
                    }
                    // contract coins must be greater than 0
                    if (this.contract.coins <= 0) {
                        if (!this.errors.ltc_nbr) {
                            this.errors.ltc_nbr = [];
                        }
                        this.errors.ltc_nbr.push('Veuillez entrer un nombre de coins supérieur à 0');
                    }*/

                    /*if (!this.contract.price) {
                        if (!this.errors.price) {
                            this.errors.price = [];
                        }
                        this.errors.price.push('Veuillez entrer le prix');
                    }
                    // contract price must be greater than 0
                    if (this.contract.price <= 0) {
                        if (!this.errors.price) {
                            this.errors.price = [];
                        }
                        this.errors.price.push('Le prix doit être supérieur à 0');
                    }*/

                    if (!this.contract.duration) {
                        if (!this.errors.duration) {
                            this.errors.duration = [];
                        }
                        this.errors.duration.push('Veuillez entrer la durée');
                    }
                    // contract duration should be greater than 0
                    if (this.contract.duration <= 0) {
                        if (!this.errors.duration) {
                            this.errors.duration = [];
                        }
                        this.errors.duration.push('La durée doit être supérieure à 0');
                    }


                    if (!this.contract.start_date) {
                        if (!this.errors.date) {
                            this.errors.date = [];
                        }
                        this.errors.date.push('Veuillez entrer la date de début');
                    }
                    // start date should be greater than today
                    if (this.contract.start_date < new Date()) {
                        if (!this.errors.date) {
                            this.errors.date = [];
                        }
                        this.errors.date.push('La date de début doit être supérieure à la date d\'aujourd\'hui');
                    }

                    if (Object.keys(this.errors).length > 0) {
                        return false;
                    }

                    return true;

                },
                updateContract() {
                    if (this.loadings.save_contract) {
                        return;
                    }

                    if (!this.updateContratValidation()) {
                        return;
                    }

                    // swal confirm OUI/NON
                    swal({
                            title: 'Êtes-vous sûr ?',
                            text: 'Vous allez modifier le contrat',
                            type: 'warning',
                            buttons: true
                        }).then((response) => {

                            if (!response)
                                return;

                            // url : /api/v2/user/updatecontract/{id}
                            this.loadings.save_contract = true;
                            axios.post('/api/v2/user/updatecontract/' + this.userId, {
                                    id: this.contract.id,
                                    assigned_user: this.contract.comercial,
                                    plan_id: this.contract.plan,
                                    ads_nbr: this.contract.nbr_annonces,
                                    ltc_nbr: this.contract.coins,
                                    price: this.contract.price,
                                    date: this.contract.start_date,
                                    duration: this.contract.duration,
                                    comment: this.contract.comment,
                                    contract_file: this.contract.contract_file
                                })
                                .then(response => {

                                    this.loadings.save_contract = false;

                                    // swal({
                                    //     title: "Contrat mis à jour",
                                    //     text: "",
                                    //     icon: "success",
                                    //     button: false,
                                    //     timer: 3000
                                    // }).then(() => {

                                    console.log(response);
                                    if (response.data.success) {
                                        // this.contract = response.data.data;

                                        this.display = false;

                                        app.datatable.rows.forEach(row => {
                                            if (row.id == this.userId) {
                                                if (response.data?.data
                                                    ?.user_new_balance) {
                                                    row.coins = response.data?.data
                                                        ?.user_new_balance;
                                                }
                                            }
                                        });

                                        appUserCoins.loadData();

                                        this.resetForm();
                                        this.userId = -1;
                                    }


                                    displayToast("Contrat mis à jour", "green");
                                    // });

                                });
                        })
                        .catch(error => {
                            this.loadings.save_contract = false;
                            this.errors = error.response.data.error;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });

                },
                updateContratValidation() {

                    this.errors = {};

                    /*if (!this.contract.comercial) {
                        if (!this.errors.assigned_user) {
                            this.errors.assigned_user = [];
                        }
                        this.errors.assigned_user.push('Veuillez choisir un commercial');
                    }*/

                    if (!this.contract.nbr_annonces) {
                        if (!this.errors.ads_nbr) {
                            this.errors.ads_nbr = [];
                        }
                        this.errors.ads_nbr.push('Veuillez entrer le nombre d\'annonces');
                    }
                    // contract nbr_annonces must be greater than 0
                    if (this.contract.nbr_annonces <= 0) {
                        if (!this.errors.ads_nbr) {
                            this.errors.ads_nbr = [];
                        }
                        this.errors.ads_nbr.push('Veuillez entrer un nombre d\'annonces supérieur à 0');
                    }

                    if (!this.contract.coins) {
                        if (!this.errors.ltc_nbr) {
                            this.errors.ltc_nbr = [];
                        }
                        this.errors.ltc_nbr.push('Veuillez entrer le nombre de coins');
                    }
                    // contract coins must be greater than 0
                    if (this.contract.coins <= 0) {
                        if (!this.errors.ltc_nbr) {
                            this.errors.ltc_nbr = [];
                        }
                        this.errors.ltc_nbr.push('Veuillez entrer un nombre de coins supérieur à 0');
                    }

                    if (!this.contract.price) {
                        if (!this.errors.price) {
                            this.errors.price = [];
                        }
                        this.errors.price.push('Veuillez entrer le prix');
                    }
                    // contract price must be greater than 0
                    if (this.contract.price <= 0) {
                        if (!this.errors.price) {
                            this.errors.price = [];
                        }
                        this.errors.price.push('Le prix doit être supérieur à 0');
                    }

                    if (!this.contract.duration) {
                        if (!this.errors.duration) {
                            this.errors.duration = [];
                        }
                        this.errors.duration.push('Veuillez entrer la durée');
                    }


                    if (!this.contract.start_date) {
                        if (!this.errors.date) {
                            this.errors.date = [];
                        }
                        this.errors.date.push('Veuillez entrer la date de début');
                    }
                    // start date should be greater than today
                    if (this.contract.start_date < new Date()) {
                        if (!this.errors.date) {
                            this.errors.date = [];
                        }
                        this.errors.date.push('La date de début doit être supérieure à la date d\'aujourd\'hui');
                    }

                    if (Object.keys(this.errors).length > 0) {
                        return false;
                    }

                    return true;

                },
                resetForm() {
                    this.contract = {
                        id: null,
                        comercial: '',
                        plan: '',
                        nbr_annonces: '',
                        coins: '',
                        price: '',
                        start_date: '',
                        duration: '',
                        comment: '',
                        contract_file: ''
                    };
                    this.selectedPlan = {};
                    this.contract.start_date = moment().format('YYYY-MM-DD');
                    this.contract_files = [];
                    this.errors = {};
                },
                selectPlan(plan) {

                    if (this.selectedPlan == plan) {
                        this.selectedPlan = {};
                    } else {
                        this.selectedPlan = plan;
                        this.contract.plan = this.selectedPlan.id;
                        this.contract.nbr_annonces = this.selectedPlan.ads_nbr;
                        this.contract.coins = this.selectedPlan.ltc_nbr;
                        this.contract.price = this.selectedPlan.price;
                        this.contract.duration = this.selectedPlan.duration;
                    }

                },
                newContract() {

                    // swal confirm OUI/NON
                    swal({
                        title: 'Êtes-vous sûr ?',
                        text: 'Vous allez créer un nouveau contrat',
                        type: 'warning',
                        buttons: true
                    }).then((response) => {
                        if (response) {
                            this.resetForm();
                        }
                    });

                },
                getPlanName(plan) {
                    if (plan) {
                        return plan?.description ?? 'paln numero ' + plan.id;
                    }
                    return '-';
                },
                watchContractAttributes() {

                    // loop through all contract attributes and watch for changes
                    for (var key in this.contract) {
                        // watch for changes
                        this.$watch('contract.' + key, function(newValue, oldValue) {
                            if (newValue != oldValue) {
                                this.errors = {};
                            }
                        });
                    }

                }
            },
        }).mount('#app-user-contract');

        let appUserCoins = Vue.createApp({
            data() {
                return {
                    userId: -1,
                    display: false,
                    filter: {
                        startDate: null,
                        endDate: null,
                    },
                    datatable: {
                        key: 'transaction_datatable',
                        api: '/api/v2/transactions/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'User id',
                                field: 'user_id',
                                type: 'number',
                                hide: true
                            },
                            {
                                name: 'Date',
                                field: 'datetime',
                                type: 'datetime',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'User',
                                field: 'username',
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
                                name: 'Amount',
                                field: 'amount',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Notes',
                                field: 'notes',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            // {
                            //     name: 'Action',
                            //     field: 'action',
                            //     type: 'action',
                            //     sortable: false,
                            //     searchable: false,
                            //     customize: true
                            // }
                        ],
                        rows: [],
                        filters: [{
                            type: 'where',
                            col: 'user_id',
                            val: `-1`,
                            op: '='
                        }],
                        sort: {
                            column: '',
                            order: ''
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 30,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            settings: true,
                            export_xlsx: true,
                            export_json: false,
                            search_input: true,
                            pagination_selection: true,
                            pagination_buttons: true,
                            filters: false
                        }
                    },
                    loading: false,
                    coins: {
                        new: 0,
                        current: '-'
                    },
                    errors: {}
                }
            },
            components: {
                'PopupComponent': PopupComponent,
                'DatatableComponent': DatatableComponent
            },
            watch: {
                userId(newVal, oldVal) {
                    console.log("new id_user", newVal);
                    if (newVal != -1) {
                        this.loadData();
                    } else {
                        this.disabled = false;
                    }
                }
            },
            filter: {
                momentDate: function(value) {
                    return moment(value).format('YYYY-MM-DD');
                }
            },
            mounted() {
                new Lightpick({
                    field: document.getElementById('datepicker'),
                    singleDate: false,
                    numberOfMonths: 2,
                    footer: true,
                    onSelect: (start, end) => {
                        this.filter.startDate = start ? start.format("YYYY-MM-DD") : null;
                        this.filter.endDate = end ? end.format("YYYY-MM-DD") : null;
                        if ((this.filter.startDate && this.filter.endDate) || (!this.filter.startDate &&
                                !this.filter.endDate)) this.loadFilter();
                    }
                });
            },
            methods: {
                loadData() {
                    this.resetForm();
                    this.lodaUserTransactions();
                    this.getUserCoins();
                },
                lodaUserTransactions() {
                    this.datatable.filters = [{
                        type: 'where',
                        col: 'user_id',
                        val: `${this.userId}`,
                        op: '='
                    }];
                    this.$refs[this.datatable.key].loadData();
                },
                getUserCoins() {

                    // get request to user/coins/{id}
                    this.loading = true;
                    axios.get('/api/v2/user/coins/' + this.userId)
                        .then(response => {
                            this.loading = false;
                            console.log('tis is response',response);
                            if (response.data.success) {
                                this.coins.current = response.data.data;

                                app.datatable.rows.forEach((row) => {
                                    if (row.id == this.userId) {
                                        row.coins = this.coins.current;
                                    }
                                })
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });

                },
                loadFilter() {
                    if (this.filter.startDate && this.filter.endDate)
                        this.datatable.filters.push({
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'datetime',
                                    val: `${this.filter.startDate}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'datetime',
                                    val: `${this.filter.endDate}`,
                                    op: '<'
                                },
                            ]
                        });
                },
                newTransaction() {
                    if (this.loading) {
                        return;
                    }

                    // url : /api/v2/transactions/create

                    // id
                    // user_id
                    // amount
                    // type
                    // notes
                    // datetime

                    this.errors = {};

                    if (!this.coins.new) {
                        if (!this.errors.coins) {
                            this.errors.coins = [];
                        }
                        this.errors.coins.push('Veuillez saisir un montant valide');
                        return;
                    }

                    if (this.coins.new == 0) {
                        if (!this.errors.coins) {
                            this.errors.coins = [];
                        }
                        this.errors.coins.push('Veuillez saisir un montant valide');
                    }

                    if (this.coins.current + this.coins.new < 0) {
                        if (!this.errors.coins) {
                            this.errors.coins = [];
                        }
                        this.errors.coins.push('Vous ne pouvez pas avoir un solde négatif');
                    }

                    if (this.errors.coins) {
                        return;
                    }

                    this.loading = true;
                    axios.post('/api/v2/transactions/create', {
                            user_id: this.userId,
                            amount: this.coins.new,
                            notes: 'new transaction'
                        })
                        .then(response => {

                            console.log()
                            this.loading = false;
                            if (response.data.success) {
                                // swal("Transaction effectuée", "", "success");
                                this.coins.new = 0;
                                this.getUserCoins();
                                this.lodaUserTransactions();
                                displayToast("Transaction effectuée", "green");
                            } else {
                                // swal("Transaction échouée", "", "error")
                                displayToast("Transaction échouée", "red");
                            }


                        })
                        .catch(error => {
                            this.loading = false;
                            // swal("Transaction échouée", "", "error")
                            console.log(error);
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });
                },
                resetForm() {
                    this.coins = {
                        new: 0,
                        current: '--'
                    };
                    this.datatable.rows = [];
                }
            },
        }).mount('#app-user-coins');
    </script>

@endsection
