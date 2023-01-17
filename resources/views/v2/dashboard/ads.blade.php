@extends('v2.layouts.dashboard')

@section('title', 'Annonces')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

    <link rel="stylesheet" type="text/css" href="/assets/vendor/lightpick/css/lightpick.css">
    <script src="/assets/vendor/lightpick/lightpick.js"></script>

@endsection

@section('content')

    <div class="pagetitle">
        <h1>Liste des annonces :</h1>
    </div>
    <section class="section" id="app">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <fieldset class="filter_cnt">

                            <legend>Filtrer:</legend>

                            <div class="row">

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">État :</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.status" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">Tous</option>
                                            <option v-for="s of status_arr" :value="s.val">@{{ s.desc }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Date :</label>
                                    <div class="col-sm-8">
                                        <div class="inner-addon right-addon">
                                            <i class="fa fa-calendar glyphicon"></i>
                                            <input type="text" id="datepicker" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Type d'utilisateur :</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.usertype" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">Tous</option>
                                            <option v-for="u of usertype_arr" :value="u.val">@{{ u.desc }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Univers :</label>
                                    <div class="col-sm-8">
                                        <select @change="loadFilter" v-model="filter.univers" class="form-select"
                                            aria-label="Default select example">
                                            <option :value="null">Tous</option>
                                            <option v-for="u of univers_arr" :value="u.val">@{{ u.desc }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row col-sm-4">

                                    <div class="form-check">
                                        <div class="col-sm-8">
                                        <input @change="loadFilter" class="form-check-input" type="checkbox"
                                            id="is_project" v-model="filter.is_project">
                                        </div>
                                        <label class="col-sm-4 col-form-label">
                                            Projets
                                        </label>
                                    </div>

                                </div>

                            </div>



                        </fieldset>
                        <div class="row" style="margin-top: 20px;margin-bottom: 10px;">
                            <div class=" col-sm-2">
                                <select v-model="datatable.pagination.per_page"
                                    style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;"
                                    class="form-select" aria-label="Default select example">
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="250">250</option>
                                    <option :value="500">500</option>
                                </select>
                            </div>
                            <div class="row col-sm-4" style="margin-left: auto;">
                                <label for="inputTitle" class="col-sm-2 col-form-label"
                                    style="text-align: end;padding: 3px 0;font-size: 13px;">
                                    <i class="bi bi-search"></i>
                                </label>
                                <div class="col-sm-10">
                                    <input class="form-control w-100" v-model="search" placeholder="Rechercher"
                                        @keyup.enter="loadFilter()" type="text" name="">
                                </div>
                            </div>
                        </div>
                        <datatable-component :datatable="datatable" @loaded="datatableLoaded">
                            <template #id="props">
                                <span v-if="props.row.value.is_project==1" style="color: red;font-size:17px;">P</span>
                                &nbsp;
                                <a :href="'{{ url("././item/") }}/' + props.column.value" target="_blank" title="Visualiser l'annonce" id='trig1' target="_blank"> <span style="color: rgb(91, 89, 233); font-weight:600">@{{ props.column.value }}</span></a>

                            </template>
                            <template #title="props">
                               <span style="color: #000; font-weight:600">@{{ props.column.value }}</span>
                            </template>
                            <template #univers="props">
                                <span v-if="props.column.value==1" style="background-color:#F64D4B; font-size:12px;" class="rounded-text">Homelist</span>
                                <span v-if="props.column.value==2" style="background-color:#02527B; font-size:12px;" class="rounded-text">Officelist</span>
                                <span v-if="props.column.value==3" style="background-color:#F3BE2E; font-size:12px;" class="rounded-text">Primelist</span>
                                <span v-if="props.column.value==4" style="background-color:#54C21B; font-size:12px;" class="rounded-text">Landlist</span>
                                <span v-if="props.column.value==5" style="background-color:#B52483; font-size:12px;" class="rounded-text">Booklist</span>
                            </template>
                            <template #status="props">
                                <div :class="'status_box s_' + props.column.value">@{{ status_obj[props.column.value] }}</div>
                            </template>
                            <template #action="props">
                                @if (auth()->user()->hasAnyPermission(['Update-ads']))
                                    <button class="btn p-0 m-0 me-2" title="éditer" @click="editAds(props.row.value)">
                                        <i class="fas fa-edit text-success"></i>
                                    </button>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['Boost-ads']))
                                    <button class="btn p-0 m-0 me-2" title="booster l'annonce"
                                        @click="editOptions(props.row.value)">
                                        <i class="fas fa-rocket text-primary"></i>
                                    </button>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['SeeStates-ads']))
                                    <button class="btn p-0 m-0 me-2" title="statistiques"
                                        @click="seeStates(props.row.value)">
                                        <i class="fas fa-chart-line text-primary"></i>
                                    </button>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['Changestatus-ads']))
                                    <button class="btn p-0 m-0 me-2" title="modifier l'état d'annonce"
                                        @click="changeAdsStatus(props.row.value)">
                                        <i class="fas fa-gear"></i>
                                    </button>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['Adddsipo-ads']))
                                    <button v-if="props.row.value.is_project==1" class="btn p-0 m-0 me-2"
                                        title="Ajouter les disponibilité de projet" @click="addDispo(props.row.value)">
                                        <i class="fas fa-circle-plus text-success"></i>
                                    </button>
                                @endif
                            </template>

                            <template #usertype="props">
                                <span v-if="props.column.value == 'super admin'" class="rounded-text"
                                    style="background: 	#db541a">@{{ props.column.value }}</span>
                                <span v-else-if="props.column.value == 'admin'" class="rounded-text"
                                    style="background: 	#ecbd25">@{{ props.column.value }}</span>
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
                        </datatable-component>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="update-ad">
        <popup-component :title="`Éditer l'annonce`" v-model:display="display" />
        <div class="popup-component-container d-none">
            <section class="section">
                <div id="addForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Général</h5>

                                    <!-- General Form Elements -->
                                    <div class="row mb-3">
                                        <label for="inputTitle" class="col-sm-4 col-form-label">Titre</label>
                                        <div class="col-sm-8">
                                            <input name="title" type="text" class="form-control"
                                                :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                            <div class="invalid-feedback">
                                                @{{ errors.title?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputDesc" class="col-sm-4 col-form-label">Descripton</label>
                                        <div class="col-sm-8">
                                            <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 100px"
                                                v-model="form.description"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ errors.description?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputPrice" class="col-sm-4 col-form-label">Prix</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input name="price" type="number" class="form-control"
                                                    :class="errors.price ? 'is-invalid' : ''" v-model="form.price">
                                                <select name="price_curr" class="inputtypeselect"
                                                    v-model="form.price_curr">
                                                    <option value="DHS">DHS</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.price?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Catégorie</label>
                                        <div class="col-sm-8">
                                            <select name="catid" class="form-select select2init"
                                                :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                                v-model="form.catid">
                                                <option :value="null">Choisir une Catégorie</option>
                                                <option v-for="cat in categories" :value="cat.id">
                                                    @{{ cat.title }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.catid?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Ville</label>
                                        <div class="col-sm-8">
                                            <select name="city" :class="errors.city ? 'is-invalid' : ''"
                                                class="form-select select2init" id="cities-select" @change="changeCity()"
                                                v-model="form.city">
                                                <option selected :value="null">Choisir une ville</option>
                                                <option v-for="city in cities" :value="city.id">
                                                    @{{ city.name }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.city?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div style="text-align: center;" v-if="deptloader">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="dept_cnt" v-if="!deptloader && form.city">
                                        <label class="col-sm-4 col-form-label">Quartier</label>
                                        <div class="col-sm-8">
                                            <select name="dept" :class="errors.dept ? 'is-invalid' : ''"
                                                class="form-select select2init" id="depts-select" @change="changeDept()"
                                                v-model="form.dept">
                                                <option selected :value="null">Choisir un quartier</option>
                                                <option :value="-1">Autre</option>
                                                <option v-for="dept in districts" :value="dept.id">
                                                    @{{ dept.name }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.dept?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="dept2_cnt" v-if="form.dept==-1">
                                        <label class="col-sm-4 col-form-label">Ajouter un quartier</label>
                                        <div class="col-sm-8">
                                            <input id="locdept2" name="dept2" type="text"
                                                :class="errors.dept2 ? 'is-invalid' : ''" class="form-control"
                                                maxlength="199" v-model="form.dept2">
                                            <div class="invalid-feedback">
                                                @{{ errors.dept2?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div id='map_cnt' style="position:relative;display:none;">
                                        <div id="map-filter-group"></div>
                                        <input type="text" id="mapSearch" class="mapSearch" placeholder="(lat,long)">
                                        <div id='map' class="mb-3" style="width: 100%;height:250px;"></div>
                                    </div>

                                    <!-- End General Form Elements -->

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Media</h5>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Vidéo Youtube</label>
                                        <div class="col-sm-8">
                                            <textarea name="videoembed" :class="errors.video_embed ? 'is-invalid' : ''" class="form-control"
                                                style="height: 100px;" v-model="form.video_embed"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ errors.video_embed?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Images (png/jpeg)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.images ? true : false"
                                                v-model:files="form.images" type="images" :max="50"
                                                :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                                :multiple="true" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Vidéos (mp4)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.videos ? true : false"
                                                v-model:files="form.videos" type="videos" :max="50"
                                                :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Audios (mp3)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.audios ? true : false"
                                                v-model:files="form.audios" type="audios" :max="50"
                                                :allowed-extensions="['mpeg', 'mpga', 'mp3', 'wav', 'aac']"
                                                :multiple="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Contact</h5>

                                    <!-- Contact Form Elements -->


                                    <div v-if="contactloader==true" style="text-align: center;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div v-if="contactloader==false">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Télephone</label>
                                            <div class="col-sm-8">
                                                <select name="phone" class="form-select select2init"
                                                    :class="errors.phone ? 'is-invalid' : ''" id="phones-select"
                                                    v-model="form.phone">
                                                    <option v-for="phone in userphones" :value="phone.id">
                                                        @{{ phone.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.phone?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Télephone 2</label>
                                            <div class="col-sm-8">
                                                <select name="phone2" class="form-select select2init"
                                                    :class="errors.phone2 ? 'is-invalid' : ''" id="phones-select"
                                                    id="phones2-select" v-model="form.phone2">
                                                    <option :value="null">Numéro de télephone</option>
                                                    <option v-for="phone in userphones" :value="phone.id">
                                                        @{{ phone.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.phone2?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Whatsapp</label>
                                            <div class="col-sm-8">
                                                <select name="wtsp" class="form-select select2init"
                                                    :class="errors.dept2 ? 'is-invalid' : ''" id="wtsp-select"
                                                    v-model="form.wtsp">
                                                    <option :value="null">Numéro de whatsapp</option>
                                                    <option v-for="wtsp in userWtsps" :value="wtsp.id">
                                                        @{{ wtsp.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.wtsp?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Email</label>
                                            <div class="col-sm-8">
                                                <select name="email" class="form-select select2init"
                                                    :class="errors.email ? 'is-invalid' : ''" id="emails-select"
                                                    v-model="form.email">
                                                    <option v-for="email in userEmails" :value="email.id">
                                                        @{{ email.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.email?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                    <!-- End Contact Form Elements -->

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Autre</h5>

                                    <!-- Advanced Form Elements -->

                                    <div class="row mb-3">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_project"
                                                id="is_project" v-model="form.is_project">
                                            <label class="form-check-label" for="gridCheck2">
                                                C'est un projet?
                                            </label>
                                        </div>



                                    </div>

                                    <div v-if="form.is_project">

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Priorité du projet</label>
                                            <div class="col-sm-8">
                                                <select name="project_priority_id" class="form-select select2init"
                                                    :class="errors.priorities ? 'is-invalid' : ''" id="priorities-select"
                                                    v-model="form.project_priority">
                                                    <option :value="null">Choisir la priorité</option>
                                                    <option v-for="projectP in priorities" :value="projectP.id">
                                                        @{{ projectP.designation }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.priorities?.join('<br/>') }}
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="inputRooms" class="col-sm-12 col-form-label">image de couverture
                                                (png/jpeg)</label>
                                            <div class="col-sm-12">
                                                <upload-files-component v-model:files="form.bg_image" type="images"
                                                    :max="1"
                                                    :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                                    :multiple="true" />
                                            </div>
                                        </div>




                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputVr" class="col-sm-4 col-form-label">Lien de visite
                                            virtuelle</label>
                                        <div class="col-sm-8">
                                            <input name="vr" type="text" class="form-control"
                                                v-model="form.vr_link">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRef" class="col-sm-4 col-form-label">Référence</label>
                                        <div class="col-sm-8">
                                            <input name="ref" type="text" :class="errors.ref ? 'is-invalid' : ''"
                                                class="form-control" v-model="form.ref">
                                            <div class="invalid-feedback">
                                                @{{ errors.ref?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Type de bien</label>
                                <div class="col-sm-8">
                                    <select name="property" :class="errors.proprety_type ? 'is-invalid' : ''" class="form-select select2init" id="property-select" v-model="form.proprety_type" >
                                        <option :value="null">Choisir un Type de bien</option>
                                        <option v-for="type in proprety_types" :value="type.id">@{{ type.designation }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @{{ errors.proprety_type?.join('<br/>') }}
                                    </div>
                                </div>
                            </div> --}}

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Standing</label>
                                        <div class="col-sm-8">
                                            <select name="property" :class="errors.standing ? 'is-invalid' : ''"
                                                class="form-select select2init" id="standing-select"
                                                v-model="form.standing">
                                                <option :value="null">Choisir Standing</option>
                                                <option v-for="standing in standings" :value="standing.id">
                                                    @{{ standing.designation }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.standing?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inputs-cnt-grid">
                                        <div class="row mb-3" style="margin:0;">
                                            <label for="inputRooms" class="col-sm-4 col-form-label">Pièces</label>
                                            <div class="col-sm-8">
                                                <input name="rooms" type="number"
                                                    :class="errors.rooms ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.rooms">
                                                <div class="invalid-feedback">
                                                    @{{ errors.rooms?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBedrooms" class="col-sm-4 col-form-label">Chambres</label>
                                            <div class="col-sm-8">
                                                <input name="bedrooms" type="number"
                                                    :class="errors.bedrooms ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.bedrooms">
                                                <div class="invalid-feedback">
                                                    @{{ errors.bedrooms?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Salles de
                                                bains</label>
                                            <div class="col-sm-8">
                                                <input name="bathrooms" type="number"
                                                    :class="errors.wc ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.wc">
                                                <div class="invalid-feedback">
                                                    @{{ errors.wc?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Parkings</label>
                                            <div class="col-sm-8">
                                                <input name="parking" type="number"
                                                    :class="errors.parking ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.parking">
                                                <div class="invalid-feedback">
                                                    @{{ errors.parking?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Date de
                                                construction</label>
                                            <div class="col-sm-8">
                                                <input name="built_year" type="number"
                                                    :class="errors.contriction_date ? 'is-invalid' : ''"
                                                    class="form-control" v-model="form.contriction_date"
                                                    placeholder="2022">
                                                <div class="invalid-feedback">
                                                    @{{ errors.contriction_date?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;" v-if="!isCatChild(5)&&form.catid!=null">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Prix/m²</label>
                                            <div class="col-sm-8">
                                                <input name="price_surface" type="number"
                                                    :class="errors.price_m ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.price_m">
                                                <div class="invalid-feedback">
                                                    @{{ errors.price_m?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Jardin</label>
                                            <div class="col-sm-8">
                                                <input name="jardin" type="number"
                                                    :class="errors.jardin ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.jardin">
                                                <div class="invalid-feedback">
                                                    @{{ errors.jardin?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Piscine</label>
                                            <div class="col-sm-8">
                                                <input name="piscine" type="number"
                                                    :class="errors.piscine ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.piscine">
                                                <div class="invalid-feedback">
                                                    @{{ errors.piscine?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="inputs-cnt-grid">

                                        <div class="row mb-3" style="margin:0;">
                                            <label for="inputSurface" class="col-sm-4 col-form-label">Supérficie <span
                                                    v-if="form.is_project">De</span></label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surface" type="number"
                                                        :class="errors.surface ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surface">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    @{{ errors.surface?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;" v-if="form.is_project">
                                            <label for="inputSurface" class="col-sm-4 col-form-label">À</label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surface2" type="number"
                                                        :class="errors.surface2 ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surface2">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    @{{ errors.surface2?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-3">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="meuble"
                                                id="meuble" v-model="form.meuble">
                                            <label class="form-check-label" for="gridCheck1">
                                                Meublé
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="terrace"
                                                id="terrace" v-model="form.terrace">
                                            <label class="form-check-label" for="gridCheck1">
                                                Terrasse
                                            </label>
                                        </div>

                                        <div class="inputs-cnt-grid">
                                            <div class="row mb-3" v-if="form.terrace">
                                                <label for="inputBathrooms"
                                                    class="col-sm-4 col-form-label">Surface</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group ">
                                                        <input name="surfaceTerrace" type="number"
                                                            :class="errors.surfaceTerrace ? 'is-invalid' : ''"
                                                            class="form-control" v-model="form.surfaceTerrace">
                                                        <span class="input-group-text">m²</span>
                                                        <div class="invalid-feedback">
                                                            @{{ errors.surfaceTerrace?.join('<br/>') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)||isCatChild(5)">
                                            <input class="form-check-input" type="checkbox" name="jardin"
                                                id="jardin" v-model="form.jardin">
                                            <label class="form-check-label" for="gridCheck1">
                                                Jardin
                                            </label>
                                        </div>

                                        <div class="inputs-cnt-grid">
                                            <div class="row mb-3" v-if="form.jardin">
                                                <label for="inputBathrooms"
                                                    class="col-sm-4 col-form-label">Surface</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group ">
                                                        <input name="surfaceJardin" type="number"
                                                            :class="errors.surfaceJardin ? 'is-invalid' : ''"
                                                            class="form-control" v-model="form.surfaceJardin">
                                                        <span class="input-group-text">m²</span>
                                                        <div class="invalid-feedback">
                                                            @{{ errors.surfaceJardin?.join('<br/>') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="climatise"
                                                id="climatise" v-model="form.climatise">
                                            <label class="form-check-label" for="gridCheck1">
                                                Climatisé
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="syndic"
                                                id="syndic" v-model="form.syndic">
                                            <label class="form-check-label" for="gridCheck1">
                                                Syndic
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="cave"
                                                id="cave" v-model="form.cave">
                                            <label class="form-check-label" for="gridCheck1">
                                                La cave
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="ascenseur"
                                                id="ascenseur" v-model="form.ascenseur">
                                            <label class="form-check-label" for="gridCheck1">
                                                Ascenseur
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="securite"
                                                id="securite" v-model="form.securite">
                                            <label class="form-check-label" for="gridCheck1">
                                                Sécurité
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="groundfloor"
                                                id="groundfloor" v-model="form.groundfloor">
                                            <label class="form-check-label" for="gridCheck1">
                                                Rez de chaussée
                                            </label>
                                        </div>

                                        <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <input class="form-check-input" type="checkbox" name="gardenlevel"
                                                id="gerdenlevel" v-model="form.gardenlevel">
                                            <label class="form-check-label" for="gridCheck1">
                                                Rez de jardin
                                            </label>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <fieldset class="filter_cnt">
                                            <legend>Lieux à proximité</legend>
                                            <button id="showaddplacemodal" type="button" class="btn btn-multi"
                                                @click="AddPlaceModal"
                                                style="
                                            padding: 5px;
                                            margin: 5px 0;
                                            margin-left: auto;
                                            display: block;
                                            font-size: 12px;">
                                                <i class="bi bi-plus me-1"></i> Ajouter Lieu
                                            </button>
                                            <table id="nearbyPlaces">
                                                <tr v-for="place in form.nearbyPlaces">
                                                    <td style="width:70%;">@{{ place.title }} <span class="distance">
                                                            @{{ place.distance }}M</span></td>
                                                    <td style="width:30%;">
                                                        <i class="bi bi-pencil-square table-action showUpdatePlaceModal"
                                                            @click="updatePlaceModal(place)"></i>
                                                        <i class="bi bi-trash table-action deletePlace"
                                                            @click="deletePlace(place.id)"></i>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>

                                    <!-- End General Form Elements -->


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center;padding-top:20px;">
                            <button type="submit" class="btn btn-success" @click="save0()">Enregistrer</button>
                            <button onclick="event.preventDefault();" class="btn btn-outline-success">Annuler</button>
                        </div>
                    </div>
                </div>

                <!-- add place Modal -->
                <div class="modal fade modal-close" id="addPlaceModal" data-id="addPlaceModal">
                    <div class="modal-dialog modal-lg">
                        <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter lieu</h5>
                                <button type="button" class="btn-close modal-close" data-id="addPlaceModal"></button>
                            </div>
                            <div class="modal-body"
                                style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                    <div class="col-sm-8">
                                        <input id="place-title" name="place_title" type="text" class="form-control"
                                            v-model="placesForm.title">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                    <div class="col-sm-8 ">
                                        <div class="input-group ">
                                            <input id="place-distance" name="place_distance" type="number"
                                                class="form-control" v-model="placesForm.distance">
                                            <span class="input-group-text">m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">type de lieu</label>
                                    <div class="col-sm-8">
                                        <select id="place-types" name="place_types" class="form-select"
                                            v-model="placesForm.type">
                                            <option :value="null">Choisir une type</option>
                                            <option v-for="type in place_types" :value="type.id">
                                                @{{ type.designation }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary modal-close"
                                    data-id="addPlaceModal">Fermer</button>
                                <button id="addPlace" type="submit" class="btn btn-primary"
                                    @click="addPlace">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End add place Modal-->

                <!-- UPDATE place Modal -->
                <div class="modal fade modal-close" id="updatePlaceModal" data-id="updatePlaceModal">
                    <div class="modal-dialog modal-lg">
                        <form onsubmit="event.preventDefault()" id="updatePlaceForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier lieu</h5>
                                <button type="button" class="btn-close modal-close" data-id="updatePlaceModal"></button>
                            </div>
                            <div class="modal-body"
                                style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                    <div class="col-sm-8">
                                        <input id="place-title" name="place_title" type="text" class="form-control"
                                            v-model="placesFormUpdate.title">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                    <div class="col-sm-8 ">
                                        <div class="input-group ">
                                            <input id="place-distance" name="place_distance" type="number"
                                                class="form-control" v-model="placesFormUpdate.distance">
                                            <span class="input-group-text">m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">type de lieu</label>
                                    <div class="col-sm-8">
                                        <select id="place-types" name="place_types" class="form-select"
                                            v-model="placesFormUpdate.type">
                                            <option :value="null">Choisir une type</option>
                                            <option v-for="type in place_types" :value="type.id">
                                                @{{ type.designation }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary modal-close"
                                    data-id="updatePlaceModal">Close</button>
                                <button id="updatePlace" type="submit" class="btn btn-primary"
                                    @click="updatePlace">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End UPDATE place Modal-->

                <div v-if="globalloader==true" id="globalLoader" class="globalLoader">
                    <div
                        style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">En cours...</span>
                        </div>
                        <div>Operation en cours...</div>
                    </div>
                </div>
            </section>
        </div>
        </popup-component>
    </div>

    <div id="addDispo-ad">
        <popup-component :title="`Éditer l'annonce`" v-model:display="display" />
        <div class="popup-component-container d-none">
            <section class="section">
                <div id="addForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Général</h5>

                                    <!-- General Form Elements -->
                                    <div class="row mb-3">
                                        <label for="inputTitle" class="col-sm-4 col-form-label">Titre</label>
                                        <div class="col-sm-8">
                                            <input name="title" type="text" class="form-control"
                                                :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                            <div class="invalid-feedback">
                                                @{{ errors.title?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputDesc" class="col-sm-4 col-form-label">Description</label>
                                        <div class="col-sm-8">
                                            <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 100px"
                                                v-model="form.description"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ errors.description?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputPrice" class="col-sm-4 col-form-label">Prix</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input name="price" type="number" class="form-control"
                                                    :class="errors.price ? 'is-invalid' : ''" v-model="form.price">
                                                <select name="price_curr" class="inputtypeselect"
                                                    v-model="form.price_curr">
                                                    <option value="DHS">DHS</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.price?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Catégorie</label>
                                        <div class="col-sm-8">
                                            <select name="catid" class="form-select select2init"
                                                :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                                v-model="form.catid">
                                                <option :value="null">Choisir une Catégorie</option>
                                                <option v-for="cat in categories" :value="cat.id">
                                                    @{{ cat.title }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.catid?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Ville</label>
                                        <div class="col-sm-8">
                                            <select name="city" :class="errors.city ? 'is-invalid' : ''"
                                                class="form-select select2init" id="cities-select" @change="changeCity()"
                                                v-model="form.city">
                                                <option selected :value="null">Choisir une ville</option>
                                                <option v-for="city in cities" :value="city.id">
                                                    @{{ city.name }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.city?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div style="text-align: center;" v-if="deptloader">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="dept_cnt" v-if="!deptloader && form.city">
                                        <label class="col-sm-4 col-form-label">Quartier</label>
                                        <div class="col-sm-8">
                                            <select name="dept" :class="errors.dept ? 'is-invalid' : ''"
                                                class="form-select select2init" id="depts-select" @change="changeDept()"
                                                v-model="form.dept">
                                                <option selected :value="null">Choisir un quartier</option>
                                                <option :value="-1">Autre</option>
                                                <option v-for="dept in districts" :value="dept.id">
                                                    @{{ dept.name }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.dept?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="dept2_cnt" v-if="form.dept==-1">
                                        <label class="col-sm-4 col-form-label">Ajouter un quartier</label>
                                        <div class="col-sm-8">
                                            <input id="locdept2" name="dept2" type="text"
                                                :class="errors.dept2 ? 'is-invalid' : ''" class="form-control"
                                                maxlength="199" v-model="form.dept2">
                                            <div class="invalid-feedback">
                                                @{{ errors.dept2?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div id='mapDispo_cnt' style="position:relative;display:none;">
                                        <div id="mapDispo-filter-group"></div>
                                        <input type="text" id="mapSearch" class="mapSearch" placeholder="(lat,long)">
                                        <div id='mapDispo' class="mb-3" style="width: 100%;height:250px;"></div>
                                    </div>

                                    <!-- End General Form Elements -->

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Media</h5>

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Video Youtube</label>
                                        <div class="col-sm-8">
                                            <textarea name="videoembed" :class="errors.video_embed ? 'is-invalid' : ''" class="form-control"
                                                style="height: 100px;" v-model="form.video_embed"></textarea>
                                            <div class="invalid-feedback">
                                                @{{ errors.video_embed?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Images (png/jpeg)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.images ? true : false"
                                                v-model:files="form.images" type="images" :max="50"
                                                :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                                :multiple="true" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Vidéos (mp4)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.videos ? true : false"
                                                v-model:files="form.videos" type="videos" :max="50"
                                                :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRooms" class="col-sm-12 col-form-label">Audios (mp3)</label>
                                        <div class="col-sm-12">
                                            <upload-files-component :error="errors.audios ? true : false"
                                                v-model:files="form.audios" type="audios" :max="50"
                                                :allowed-extensions="['mpeg', 'mpga', 'mp3', 'wav', 'aac']"
                                                :multiple="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Contact</h5>

                                    <!-- Contact Form Elements -->


                                    <div v-if="contactloader==true" style="text-align: center;">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>

                                    <div v-if="contactloader==false">
                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Télephone</label>
                                            <div class="col-sm-8">
                                                <select name="phone" class="form-select select2init"
                                                    :class="errors.phone ? 'is-invalid' : ''" id="phones-select"
                                                    v-model="form.phone">
                                                    <option v-for="phone in userphones" :value="phone.id">
                                                        @{{ phone.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.phone?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Télephone 2</label>
                                            <div class="col-sm-8">
                                                <select name="phone2" class="form-select select2init"
                                                    :class="errors.phone2 ? 'is-invalid' : ''" id="phones-select"
                                                    id="phones2-select" v-model="form.phone2">
                                                    <option :value="null">Numéro de télephone</option>
                                                    <option v-for="phone in userphones" :value="phone.id">
                                                        @{{ phone.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.phone2?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Whatsapp</label>
                                            <div class="col-sm-8">
                                                <select name="wtsp" class="form-select select2init"
                                                    :class="errors.dept2 ? 'is-invalid' : ''" id="wtsp-select"
                                                    v-model="form.wtsp">
                                                    <option :value="null">Numéro de whatsapp</option>
                                                    <option v-for="wtsp in userWtsps" :value="wtsp.id">
                                                        @{{ wtsp.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.wtsp?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label class="col-sm-4 col-form-label">Email</label>
                                            <div class="col-sm-8">
                                                <select name="email" class="form-select select2init"
                                                    :class="errors.email ? 'is-invalid' : ''" id="emails-select"
                                                    v-model="form.email">
                                                    <option v-for="email in userEmails" :value="email.id">
                                                        @{{ email.value }}</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    @{{ errors.email?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                    <!-- End Contact Form Elements -->

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Autre</h5>

                                    <!-- Advanced Form Elements -->



                                    <div v-if="form.is_project">

                                        <div class="row mb-3">
                                            <label for="inputRooms" class="col-sm-12 col-form-label">image de couverture
                                                (png/jpeg)</label>
                                            <div class="col-sm-12">
                                                <upload-files-component v-model:files="form.bg_image" type="images"
                                                    :max="1"
                                                    :allowed-extensions="['jpg', 'jpeg', 'png', 'webp']"
                                                    :multiple="true" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputVr" class="col-sm-4 col-form-label">Lien de visite
                                            virtuelle</label>
                                        <div class="col-sm-8">
                                            <input name="vr" type="text" class="form-control"
                                                v-model="form.vr_link">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputRef" class="col-sm-4 col-form-label">Référence</label>
                                        <div class="col-sm-8">
                                            <input name="ref" type="text" :class="errors.ref ? 'is-invalid' : ''"
                                                class="form-control" v-model="form.ref">
                                            <div class="invalid-feedback">
                                                @{{ errors.ref?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Type de bien</label>
                                <div class="col-sm-8">
                                    <select name="property" :class="errors.proprety_type ? 'is-invalid' : ''" class="form-select select2init" id="property-select" v-model="form.proprety_type" >
                                        <option :value="null">Choisir un Type de bien</option>
                                        <option v-for="type in proprety_types" :value="type.id">@{{ type.designation }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @{{ errors.proprety_type?.join('<br/>') }}
                                    </div>
                                </div>
                            </div> --}}

                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Standing</label>
                                        <div class="col-sm-8">
                                            <select name="property" :class="errors.standing ? 'is-invalid' : ''"
                                                class="form-select select2init" id="standing-select"
                                                v-model="form.standing">
                                                <option :value="null">Choisir Standing</option>
                                                <option v-for="standing in standings" :value="standing.id">
                                                    @{{ standing.designation }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                @{{ errors.standing?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="inputs-cnt-grid">
                                        <div class="row mb-3" style="margin:0;">
                                            <label for="inputRooms" class="col-sm-4 col-form-label">Pièces</label>
                                            <div class="col-sm-8">
                                                <input name="rooms" type="number"
                                                    :class="errors.rooms ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.rooms">
                                                <div class="invalid-feedback">
                                                    @{{ errors.rooms?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBedrooms" class="col-sm-4 col-form-label">Chambres</label>
                                            <div class="col-sm-8">
                                                <input name="bedrooms" type="number"
                                                    :class="errors.bedrooms ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.bedrooms">
                                                <div class="invalid-feedback">
                                                    @{{ errors.bedrooms?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Salles de
                                                bain</label>
                                            <div class="col-sm-8">
                                                <input name="bathrooms" type="number"
                                                    :class="errors.wc ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.wc">
                                                <div class="invalid-feedback">
                                                    @{{ errors.wc?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Parkings</label>
                                            <div class="col-sm-8">
                                                <input name="parking" type="number"
                                                    :class="errors.parking ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.parking">
                                                <div class="invalid-feedback">
                                                    @{{ errors.parking?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Date de
                                                construction</label>
                                            <div class="col-sm-8">
                                                <input name="built_year" type="number"
                                                    :class="errors.contriction_date ? 'is-invalid' : ''"
                                                    class="form-control" v-model="form.contriction_date"
                                                    placeholder="2022">
                                                <div class="invalid-feedback">
                                                    @{{ errors.contriction_date?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;" v-if="!isCatChild(5)&&form.catid!=null">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Prix/m²</label>
                                            <div class="col-sm-8">
                                                <input name="price_surface" type="number"
                                                    :class="errors.price_m ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.price_m">
                                                <div class="invalid-feedback">
                                                    @{{ errors.price_m?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Jardin</label>
                                            <div class="col-sm-8">
                                                <input name="jardin" type="number"
                                                    :class="errors.jardin ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.jardin">
                                                <div class="invalid-feedback">
                                                    @{{ errors.jardin?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;"
                                            v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Piscine</label>
                                            <div class="col-sm-8">
                                                <input name="piscine" type="number"
                                                    :class="errors.piscine ? 'is-invalid' : ''" class="form-control"
                                                    v-model="form.piscine">
                                                <div class="invalid-feedback">
                                                    @{{ errors.piscine?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="inputs-cnt-grid">

                                        <div class="row mb-3" style="margin:0;">
                                            <label for="inputSurface" class="col-sm-4 col-form-label">Supérficie <span
                                                    v-if="form.is_project">De</span></label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surface" type="number"
                                                        :class="errors.surface ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surface">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    @{{ errors.surface?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3" style="margin:0;" v-if="form.is_project">
                                            <label for="inputSurface" class="col-sm-4 col-form-label">À</label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surface2" type="number"
                                                        :class="errors.surface2 ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surface2">
                                                    <span class="input-group-text">m²</span>
                                                </div>
                                                <div class="invalid-feedback">
                                                    @{{ errors.surface2?.join('<br/>') }}
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="terrace"
                                            id="terrace" v-model="form.terrace">
                                        <label class="form-check-label" for="gridCheck1">
                                            Terrasse
                                        </label>
                                    </div>

                                    <div class="inputs-cnt-grid">
                                        <div class="row mb-3" v-if="form.terrace">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Surface</label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surfaceTerrace" type="number"
                                                        :class="errors.surface ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surfaceTerrace">
                                                    <span class="input-group-text">m²</span>
                                                    <div class="invalid-feedback">
                                                        @{{ errors.surface2?.join('<br/>') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-check"
                                        v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)||isCatChild(5)">
                                        <input class="form-check-input" type="checkbox" name="jardin"
                                            id="jardin" v-model="form.jardin">
                                        <label class="form-check-label" for="gridCheck1">
                                            Jardin
                                        </label>
                                    </div>

                                    <div class="inputs-cnt-grid">
                                        <div class="row mb-3" v-if="form.jardin">
                                            <label for="inputBathrooms" class="col-sm-4 col-form-label">Surface</label>
                                            <div class="col-sm-8">
                                                <div class="input-group ">
                                                    <input name="surfaceJardin" type="number"
                                                        :class="errors.surface ? 'is-invalid' : ''" class="form-control"
                                                        v-model="form.surfaceJardin">
                                                    <span class="input-group-text">m²</span>
                                                    <div class="invalid-feedback">
                                                        @{{ errors.surface?.join('<br/>') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="climatise"
                                            id="climatise" v-model="form.climatise">
                                        <label class="form-check-label" for="gridCheck1">
                                            Climatisé
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="syndic"
                                            id="syndic" v-model="form.syndic">
                                        <label class="form-check-label" for="gridCheck1">
                                            Syndic
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="cave"
                                            id="cave" v-model="form.cave">
                                        <label class="form-check-label" for="gridCheck1">
                                            La cave
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="ascenseur"
                                            id="ascenseur" v-model="form.ascenseur">
                                        <label class="form-check-label" for="gridCheck1">
                                            Ascenseur
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="securite"
                                            id="securite" v-model="form.securite">
                                        <label class="form-check-label" for="gridCheck1">
                                            Sécurité
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="groundfloor"
                                            id="groundfloor" v-model="form.groundfloor">
                                        <label class="form-check-label" for="gridCheck1">
                                            Rez de chaussée
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="gardenlevel"
                                            id="gerdenlevel" v-model="form.gardenlevel">
                                        <label class="form-check-label" for="gridCheck1">
                                            Rez de jardin
                                        </label>
                                    </div>



                                    <div class="row mb-3">
                                        <fieldset class="filter_cnt">
                                            <legend>Lieux à proximité</legend>
                                            <button id="showaddplacemodal" type="button" class="btn btn-multi"
                                                @click="AddPlaceModal"
                                                style="
                                            padding: 5px;
                                            margin: 5px 0;
                                            margin-left: auto;
                                            display: block;
                                            font-size: 12px;">
                                                <i class="bi bi-plus me-1"></i> Ajouter Lieu
                                            </button>
                                            <table id="nearbyPlaces">
                                                <tr v-for="place in form.nearbyPlaces">
                                                    <td style="width:70%;">@{{ place.title }} <span
                                                            class="distance"> @{{ place.distance }}M</span></td>
                                                    <td style="width:30%;">
                                                        <i class="bi bi-pencil-square table-action showUpdatePlaceModal"
                                                            @click="updatePlaceModal(place)"></i>
                                                        <i class="bi bi-trash table-action deletePlace"
                                                            @click="deletePlace(place.id)"></i>
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                    </div>

                                    <!-- End General Form Elements -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" style="text-align: center;padding-top:20px;">
                            <button type="submit" class="btn btn-success" @click="save0()">Enregistrer</button>
                            <button onclick="event.preventDefault();" class="btn btn-outline-success">Annuler</button>
                        </div>
                    </div>
                </div>

                <!-- add place Modal -->
                <div class="modal fade modal-close" id="addPlaceModal" data-id="addPlaceModal">
                    <div class="modal-dialog modal-lg">
                        <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajouter lieu</h5>
                                <button type="button" class="btn-close modal-close" data-id="addPlaceModal"></button>
                            </div>
                            <div class="modal-body"
                                style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                    <div class="col-sm-8">
                                        <input id="place-title" name="place_title" type="text"
                                            class="form-control" v-model="placesForm.title">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                    <div class="col-sm-8 ">
                                        <div class="input-group ">
                                            <input id="place-distance" name="place_distance" type="number"
                                                class="form-control" v-model="placesForm.distance">
                                            <span class="input-group-text">m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">type de lieu</label>
                                    <div class="col-sm-8">
                                        <select id="place-types" name="place_types" class="form-select"
                                            v-model="placesForm.type">
                                            <option :value="null">Choisir une type</option>
                                            <option v-for="type in place_types" :value="type.id">
                                                @{{ type.designation }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary modal-close"
                                    data-id="addPlaceModal">Close</button>
                                <button id="addPlace" type="submit" class="btn btn-primary"
                                    @click="addPlace">Ajouter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End add place Modal-->

                <!-- UPDATE place Modal -->
                <div class="modal fade modal-close" id="updatePlaceModal" data-id="updatePlaceModal">
                    <div class="modal-dialog modal-lg">
                        <form onsubmit="event.preventDefault()" id="updatePlaceForm" class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modifier lieu</h5>
                                <button type="button" class="btn-close modal-close"
                                    data-id="updatePlaceModal"></button>
                            </div>
                            <div class="modal-body"
                                style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Nom de lieu</label>
                                    <div class="col-sm-8">
                                        <input id="place-title" name="place_title" type="text"
                                            class="form-control" v-model="placesFormUpdate.title">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputLoc" class="col-sm-4 col-form-label">Distance</label>
                                    <div class="col-sm-8 ">
                                        <div class="input-group ">
                                            <input id="place-distance" name="place_distance" type="number"
                                                class="form-control" v-model="placesFormUpdate.distance">
                                            <span class="input-group-text">m</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">type de lieu</label>
                                    <div class="col-sm-8">
                                        <select id="place-types" name="place_types" class="form-select"
                                            v-model="placesFormUpdate.type">
                                            <option :value="null">Choisir une type</option>
                                            <option v-for="type in place_types" :value="type.id">
                                                @{{ type.designation }}</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary modal-close"
                                    data-id="updatePlaceModal">Close</button>
                                <button id="updatePlace" type="submit" class="btn btn-primary"
                                    @click="updatePlace">Modifier</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End UPDATE place Modal-->

                <div v-if="globalloader==true" id="globalLoader" class="globalLoader">
                    <div
                        style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div>Opération en cours...</div>
                    </div>
                </div>
            </section>
        </div>
        </popup-component>
    </div>

    <div id="changestatus-app">
        <popsup-component :title="`Changer l'état d'annonce`" v-model:display="display">
            <div class="s_popup-component-container d-none">
                <section class="section">
                    <div class="card" style="width: 400px;margin: auto;padding: 30px 0;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <select class="form-select" aria-label="Default select example"
                                        v-model="form.status">
                                        <option value="10">Publiée</option>
                                        <option value="20">Brouillons</option>
                                        <option value="30">En revue</option>
                                        <option value="40">Attente de paiment</option>
                                        <option value="50">Rejetée</option>
                                        <option value="60">Expirée</option>
                                        <option value="70">Supprimée</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center; padding-top: 20px;">
                            <button class="btn btn-success" @click="save0" :disabled="loader">
                                <span>Sauvegarder</span>
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </popsup-component>
    </div>

    <div id="boostad-app">
        <popsup-component :title="`Booster l'annonce`" v-model:display="display">
            <div class="s_popup-component-container d-none">
                <section class="section">
                    <div class="card" style="width: 400px;margin: auto;padding: 30px 0;">
                        <div class="card-body">
                            <div class="row">
                                <div v-for="option in options">
                                    <fieldset v-if="option.options.length!=0" class="filter_cnt">
                                        <legend>@{{ option.designation }}</legend>
                                        <div class="col-sm-12">
                                            <div v-for="op in option.options" class="form-check">
                                                <input class="form-check-input" type="radio" :name="optionsRadio"
                                                    :value="op.id" v-model="form.option">
                                                <label class="form-check-label">
                                                    @{{ op.designation }}
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                            </div>
                        </div>
                        <div style="text-align: center; padding-top: 20px;">
                            <button class="btn btn-success" @click="save0" :disabled="loader">
                                <span>Sauvegarder</span>
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </popsup-component>


        <popsup-component :title="`Booster l'annonce`" v-model:display="display">
            <div class="s_popup-component-container d-none">
                <section class="section">
                    <div class="card" style="width: 400px;margin: auto;padding: 30px 0;">
                        <div class="card-body">
                            <div class="row">
                                <div v-for="option in options">
                                    <fieldset v-if="option.options.length!=0" class="filter_cnt">
                                        <legend>@{{ option.designation }}</legend>
                                        <div class="col-sm-12">
                                            <div v-for="op in option.options" class="form-check">
                                                <input class="form-check-input" type="radio" :name="optionsRadio"
                                                    :value="op.id" v-model="form.option">
                                                <label class="form-check-label">
                                                    @{{ op.designation }}
                                                </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                            </div>
                        </div>
                        <div style="text-align: center; padding-top: 20px;">
                            <button class="btn btn-success" @click="save0" :disabled="loader">
                                <span>Sauvegarder</span>
                                <div class="spinner-border spinner-border-sm ms-2" v-if="loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </popsup-component>

        <div v-if="globalloader==true" id="globalLoader" class="globalLoader">
            <div
                style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Opération en cours...</div>
            </div>
        </div>
    </div>

    <div id="states-app">
        <popup-component :title="`Statistique de l'annonce`" v-model:display="display">
            <div class="popup-component-container d-none">
                <section class="section">

                    <div class=" col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="dashboard_filter_cnt">
                                    <select v-model="statesFilter.periode" @change="changeStatesFilter()">
                                        <option :value="0">Aujourd'hui</option>
                                        <option :value="7">7 derniers jours</option>
                                        <option :value="30">30 derniers jours</option>
                                        <option :value="90">90 derniers jours</option>
                                        <option :value="365">365 derniers jours</option>
                                        <option :value="-1">Période personnalisée</option>
                                    </select>
                                </div>


                                <div class="date_cnt_dashboard" v-if="statesFilter.periode==-1">
                                    <input type="date" v-model="statesFilter.dateFrom">
                                    <input type="date" v-model="statesFilter.dateTo">
                                    <button @click="validateStatesFilter()">Valider</button>
                                </div>

                                <div class="tilet-stats-row">

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #f3be2e;">
                                            <div class="icon">
                                                <i class="bi bi-eye"></i>
                                            </div>
                                            <div class="count">@{{ views }}</div>
                                            <h3>Vues</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #d9534f;">
                                            <div class="icon">
                                                <i class="bi bi-envelope"></i>
                                            </div>
                                            <div class="count">@{{ emails }}</div>
                                            <h3>Email</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #058dc7;">
                                            <div class="icon">
                                                <i class="bi bi-telephone"></i>
                                            </div>
                                            <div class="count">@{{ phones }}</div>
                                            <h3>Télephone</h3>
                                        </div>
                                    </div>

                                    <div class="tilet-stats-col">
                                        <div class="tile-stats" style="background-color: #25d366;">
                                            <div class="icon">
                                                <i class="bi bi-whatsapp"></i>
                                            </div>
                                            <div class="count">@{{ wtsps }}</div>
                                            <h3>Whatsapp</h3>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class=" col-sm-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="dashboard_filter_cnt">
                                    <select v-model="emailsFilter.periode" @change="changeEmailsFilter()">
                                        <option :value="0">Aujourd'hui</option>
                                        <option :value="7">7 derniers jours</option>
                                        <option :value="30">30 derniers jours</option>
                                        <option :value="90">90 derniers jours</option>
                                        <option :value="365">365 derniers jours</option>
                                        <option :value="-1">Période personnalisée</option>
                                    </select>
                                </div>


                                <div class="date_cnt_dashboard" v-if="emailsFilter.periode==-1">
                                    <input type="date" v-model="emailsFilter.dateFrom">
                                    <input type="date" v-model="emailsFilter.dateTo">
                                    <button @click="validateEmailsFilter()">Valider</button>
                                </div>

                                <h5 class="card-title">Emails : </h5>
                                <datatable-component :datatable="datatable"></datatable-component>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </popup-component>
    </div>


@endsection

@section('custom_foot')

    <script type="text/javascript">
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);

        let itemsApp = Vue.createApp({
            data() {
                return {
                    search: '',
                    status_obj: status_obj,
                    status_arr: status_arr,
                    usertype_arr: usertype_arr,
                    univers_arr : univers_arr,
                    filter: {
                        status: null,
                        startDate: null,
                        endDate: null,
                        usertype:null,
                        univers:null,
                        is_project:null
                    },
                    users: [],
                    updateAd: {
                        id: null,
                        display: false,
                    },
                    datatable: {
                        key: 'ads_datatable',
                        api: '/api/v2/items/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true,
                                customize: true
                            },
                            {
                                name: 'Titre',
                                field: 'title',
                                type: 'string',
                                sortable: false,
                                searchable: true,
                                customize:true
                            },
                            {
                                name: 'Catégorie',
                                field: 'category',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Univers',
                                field: 'univers',
                                type: 'int',
                                sortable: false,
                                searchable: true,
                                customize:true
                            },
                            {
                                name: 'Nombre media',
                                field: 'nbr_media',
                                type: 'string',
                                sortable: false,
                                searchable: false
                            },
                            {
                                name: 'Ville',
                                field: 'city',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Quartier',
                                field: 'district',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Utilisateur',
                                field: 'user',
                                type: 'string',
                                sortable: false,
                                searchable: true
                            },
                            {
                                name: 'Type d\'utilisateur',
                                field: 'usertype',
                                type: 'string',
                                sortable: false,
                                hide:false,
                                searchable: true,
                                customize:true
                            },
                            {
                                name: 'likes',
                                field: 'likes',
                                type: 'string',
                                sortable: false,
                                hide: true,
                                searchable: true
                            },
                            {
                                name: 'Référence',
                                field: 'ref',
                                type: 'string',
                                sortable: false,
                                hide: true,
                                searchable: true
                            },
                            {
                                name: "Date d'expiration",
                                field: 'expiredate',
                                type: 'string',
                                sortable: false,
                                hide: true,
                                searchable: true
                            },
                            {
                                name: 'Date',
                                field: 'published_at',
                                type: 'date',
                                sortable: true,
                                searchable: false
                            },
                            {
                                name: "État",
                                field: 'status',
                                type: 'custom',
                                sortable: true,
                                searchable: true,
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
                            column: 'ads.id',
                            order: 'DESC'
                        },
                        pagination: {
                            enabled: true,
                            page: 1,
                            per_page: 20,
                            total: 0,
                            links: []
                        },
                        show_controls: {
                            settings: true,
                            export_xlsx: true,
                            export_json: true,
                            pagination_buttons: true
                        }
                    }
                }
            },
            components: {
                'DatatableComponent': DatatableComponent,
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
                datatableLoaded(rows) {

                },
                loadFilter() {

                    this.datatable.filters = [{
                        type: 'where',
                        subWhere: [
                            {
                                type: 'where',
                                col: 'ads.title',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'ads.id',
                                val: `${this.search}`,
                                op: '='
                            },
                            {
                                type: 'orWhere',
                                col: 'cats.title',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'ads.id_user',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'ads.parent_project',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'ads.status',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'ads.is_project',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'user_type.id',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'cats.parent_cat',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            },
                            {
                                type: 'orWhere',
                                col: 'users.username',
                                val: `%${this.search}%`,
                                op: 'LIKE'
                            }
                        ]
                    }, ];
                    if (this.filter.status)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'ads.status',
                            val: `${this.filter.status}`,
                            op: '='
                        });
                        if (this.filter.is_project)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'ads.is_project',
                            val: 1,
                            op: '='
                        });
                        if (this.filter.usertype)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'user_type.id',
                            val: `${this.filter.usertype}`,
                            op: '='
                        });
                        if (this.filter.univers)
                        this.datatable.filters.push({
                            type: 'where',
                            col: 'cats.parent_cat',
                            val: `${this.filter.univers}`,
                            op: '='
                        });
                    if (this.filter.startDate && this.filter.endDate)
                        this.datatable.filters.push({
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'ads.published_at',
                                    val: `${this.filter.startDate}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'ads.published_at',
                                    val: `${this.filter.endDate}`,
                                    op: '<'
                                },
                            ]
                        });
                    console.log('filters',this.datatable.filters);
                },
                convertDate(value) {
                    const date = new Date(value);
                    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString().substr(0, 5);
                },
                editAds(ad) {
                    updateModal.form.id = ad.id;

                    updateModal.loadData();
                    updateModal.errors = [];
                    updateModal.display = true;
                },
                changeAdsStatus(ad) {
                    changeStatusModal.form.id = ad.id;
                    changeStatusModal.form.status = ad.status;
                    changeStatusModal.oldStatus = ad.status;
                    changeStatusModal.display = true;
                },
                editOptions(ad) {
                    boostAdModal.form.id = ad.id;
                    boostAdModal.form.option = null;
                    boostAdModal.getAdOption();
                    boostAdModal.display = true;
                },
                seeStates(ad) {
                    statesModal.id = ad.id;
                    statesModal.loadData();
                    statesModal.display = true;
                },
                addDispo(ad) {
                    addDispoModal.form.userid = ad.id_user;
                    addDispoModal.form.parent_project = ad.id;
                    addDispoModal.errors = [];
                    addDispoModal.loadPage();
                    addDispoModal.display = true;
                },
            },
        }).mount('#app');

        //CHANGE STATUS ------------------------------------------------------------------

        let changeStatusModal = Vue.createApp({
            data() {
                return {
                    display: false,
                    loader: false,
                    error: '',
                    oldStatus: null,
                    form: {
                        id: null,
                        status: null,
                    },
                    same: false
                }
            },
            components: {
                'PopsupComponent': PopsupComponent,
            },

            computed: {

                same: function() {

                    console.log('computed')

                }
            },

            methods: {
                save0() {


                    if (this.oldStatus == this.form.status) {
                        displayToast("Choisissez un état différent !", '#842029');
                        return;
                    }
                    if (!this.form.status) return;
                    swal("Voulez-vous vraiment modifier l'état de cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val == true) {
                            this.save();
                        }
                    });
                },
                save() {
                    //if(this.oldStatus==this.form.status) return;
                    //if(!this.form.status) return;
                    this.loader = true;
                    this.error = '';


                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/items/updateStatus`
                    };
                    axios(config)
                        .then((response) => {
                            this.loader = false;
                            console.log('response is : ', response.data);
                            if (response.data.success == true) {
                                this.display = false;
                                this.same = false;
                                for (let i = 0; i < itemsApp.datatable.rows.length; i++) {
                                    if (itemsApp.datatable.rows[i].id == response.data.data.id) {
                                        itemsApp.datatable.rows[i].status = response.data.data.status;
                                    }
                                }
                                displayToast("L'annonce a été modifiée avec succès", '#0f5132');
                            }
                        })
                        .catch(error => {
                            this.loader = false;
                            this.error = error.response.data.error;
                            displayToast(this.error, '#842029');
                        });
                },
            }
        }).mount('#changestatus-app');

        //BOOST AD ------------------------------------------------------------------

        let boostAdModal = Vue.createApp({
            data() {
                return {
                    display: false,
                    loader: false,
                    globalloader: false,
                    error: '',
                    options: [],
                    oldOption: null,
                    form: {
                        id: null,
                        option: null,
                    },
                }
            },
            components: {
                'PopsupComponent': PopsupComponent,
            },
            mounted() {
                this.loadOptions();
            },
            methods: {
                loadOptions() {
                    var config = {
                        method: 'post',
                        url: `/api/v2/options/getAll`
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                this.options = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                },

                getAdOption() {
                    this.globalloader = true;
                    var config = {
                        method: 'post',
                        data: {
                            id: this.form.id
                        },
                        url: `/api/v2/items/getAdOption`
                    };
                    axios(config)
                        .then((response) => {
                            console.log(response);
                            this.globalloader = false;
                            if (response.data.success == true) {
                                this.form.option = response.data.data;
                                this.oldOption = response.data.data;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                            this.globalloader = false;
                            this.display = false;
                            this.error = error.response.data.error;
                            displayToast(this.error, '#842029');
                        });
                },
                save0() {
                    if (this.oldOption == this.form.option) {
                        displayToast("Cette option de boost est déjà affectée à cette annonce", '#842029');
                        return;
                    }
                    if (!this.form.option) return;
                    swal("Voulez-vous vraiment booster cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val == true) {
                            this.save();
                        }
                    });
                },
                save() {
                    //if(this.oldOption==this.form.option) return;
                    //if(!this.form.option) return;
                    this.loader = true;
                    this.error = '';

                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/items/updateOptions`
                    };
                    axios(config)
                        .then((response) => {
                            this.loader = false;
                            console.log(response.data);
                            if (response.data.success == true) {
                                this.display = false;
                                displayToast("Les options ont été modifiée avec succès", '#0f5132');
                            }
                        })
                        .catch(error => {
                            this.loader = false;
                            this.error = error.response.data.error;
                            displayToast(this.error, '#842029');
                        });
                },
            },
        }).mount('#boostad-app');

        //STATES MODAL ------------------------------------------------------------------

        let statesModal = Vue.createApp({
            data() {
                return {
                    display: false,
                    id: null,
                    views: null,
                    wtsps: null,
                    emails: null,
                    phones: null,
                    statesFilter: {
                        periode: 30,
                        dateFrom: null,
                        dateTo: null
                    },
                    emailsFilter: {
                        periode: 30,
                        dateFrom: null,
                        dateTo: null
                    },
                    datatable: {
                        key: 'ads_emails_datatable',
                        api: '/api/v2/emails/filter',
                        columns: [{
                                name: '#',
                                field: 'id',
                                type: 'number',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: "ID d'annonce",
                                field: 'ad_id',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Nom',
                                field: 'name',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'E-mail',
                                field: 'email',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Téléphone',
                                field: 'phone',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Message',
                                field: 'message',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
                            {
                                name: 'Date',
                                field: 'date',
                                type: 'string',
                                sortable: true,
                                searchable: true
                            },
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
                            per_page: 30,
                            total: 0,
                            links: []
                        }
                    },

                }
            },
            components: {
                'PopupComponent': PopupComponent,
                'DatatableComponent': DatatableComponent
            },
            watch: {


            },
            mounted() {},
            methods: {
                loadData() {
                    this.views = null;
                    this.wtsps = null;
                    this.emails = null;
                    this.phones = null;
                    this.statesFilter.periode = 30;
                    this.emailsFilter.periode = 30;
                    this.loadStates();
                    this.loadEmails();
                },
                loadStates() {
                    if (this.statesFilter.periode != -1) {
                        const dateFrom = new Date();
                        const dateTo = new Date();
                        dateFrom.setDate(dateFrom.getDate() - this.statesFilter.periode);
                        dateTo.setDate(dateTo.getDate() + 1);
                        this.statesFilter.dateFrom = dateFrom.toISOString().split('T')[0];
                        this.statesFilter.dateTo = dateTo.toISOString().split('T')[0];
                    }
                    var config = {
                        method: 'post',
                        data: {
                            id: this.id,
                            dateFrom: this.statesFilter.dateFrom,
                            dateTo: this.statesFilter.dateTo
                        },
                        url: `/api/v2/items/statesByAd`
                    };
                    axios(config)
                        .then((response) => {
                            if (response.data.success == true) {
                                this.views = response.data.data.views;
                                this.wtsps = response.data.data.wtsps;
                                this.emails = response.data.data.emails;
                                this.phones = response.data.data.phones;
                            }
                        })
                        .catch((error) => {
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });
                },
                loadEmails() {
                    if (this.emailsFilter.periode != -1) {
                        const dateFrom = new Date();
                        const dateTo = new Date();
                        dateFrom.setDate(dateFrom.getDate() - this.emailsFilter.periode);
                        dateTo.setDate(dateTo.getDate() + 1);
                        this.emailsFilter.dateFrom = dateFrom.toISOString().split('T')[0];
                        this.emailsFilter.dateTo = dateTo.toISOString().split('T')[0];
                    }
                    this.datatable.filters = [{
                            type: 'where',
                            subWhere: [{
                                    type: 'where',
                                    col: 'date',
                                    val: `${this.emailsFilter.dateFrom}`,
                                    op: '>'
                                },
                                {
                                    type: 'where',
                                    col: 'date',
                                    val: `${this.emailsFilter.dateTo}`,
                                    op: '<'
                                },
                            ]
                        },
                        {
                            type: 'where',
                            col: 'ad_id',
                            val: `${this.id}`,
                            op: '='
                        },
                    ];
                },
                changeStatesFilter() {
                    this.views = null;
                    this.wtsps = null;
                    this.emails = null;
                    this.phones = null;
                    if (this.statesFilter.periode == -1) {
                        this.statesFilter.dateFrom = null;
                        this.statesFilter.dateTo = null;
                    } else {
                        this.loadStates();
                    }
                },
                changeEmailsFilter() {
                    if (this.emailsFilter.periode == -1) {
                        this.emailsFilter.dateFrom = null;
                        this.emailsFilter.dateTo = null;
                    } else {
                        this.loadEmails();
                    }
                },
                validateStatesFilter() {
                    if (this.statesFilter.dateFrom != null && this.statesFilter.dateTo) {
                        this.loadStates();
                    }
                },
                validateEmailsFilter() {
                    if (this.emailsFilter.dateFrom != null && this.emailsFilter.dateTo) {
                        this.loadEmails();
                    }
                }
            },
        }).mount('#states-app');

        //UPDATE MODAL ------------------------------------------------------------------

        let updateModal = Vue.createApp({
            data() {
                return {
                    errors: {},
                    errorText: '',
                    display: false,
                    globalloader: false,
                    contactloader: false,
                    deptloader: false,
                    changed: false,
                    categories: [],
                    priorities: [],
                    cities: [],
                    districts: [],
                    proprety_types: [],
                    standings: [],
                    place_types: [],
                    userphones: [],
                    userEmails: [],
                    userWtsps: [],
                    oldForm: {},
                    form: {
                        id: null,
                        title: '',
                        description: '',
                        price: '',
                        price_curr: 'DHS',
                        catid: null,
                        city: null,
                        dept: null,
                        lat: null,
                        long: null,
                        dept2: '',
                        userid: null,
                        phone: -1,
                        phone2: null,
                        wtsp: null,
                        email: -1,
                        is_project: false,
                        project_priority_id: null,
                        meuble: false,
                        terrace: false,
                        surfaceTerrace: null,
                        jardin: false,
                        surfaceJardin: null,
                        proprety_type: null,
                        standing: null,
                        ref: '',
                        vr_link: '',
                        rooms: null,
                        bedrooms: null,
                        wc: null,
                        parking: null,
                        contriction_date: null,
                        price_m: null,
                        piscine: null,
                        jardin: null,
                        surface: null,
                        surface2: null,
                        groundfloor: false,
                        gardenlevel: false,
                        nearbyPlaces: [],
                        video_embed: '',
                        bg_image: [],
                        images: [],
                        videos: [],
                        audios: [],
                        status: '10',
                    },
                    placesForm: {
                        id: 0,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    },
                    placesFormUpdate: {
                        id: null,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    },
                    selecttest: [{
                        name: 'choose',
                        value: null
                    }, {
                        name: 'A',
                        value: 1
                    }, {
                        name: 'B',
                        value: 2
                    }],
                    selecttestModel: null,
                    map: null,
                    marker: null,
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent,
                'PopupComponent': PopupComponent,
            },
            computed: {

            },
            watch: {
                form: {
                    handler() {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('form.' + key, () => {
                                delete this.errors[key];
                            });
                        }
                    },
                    deep: true,

                }
            },
            mounted() {
                this.loadPage();
                $('.modal-close').click(function() {
                    hideModal($(this).attr('data-id'));
                }).children().click(function() {
                    return false;
                });
            },
            methods: {
                loadPage() {
                    this.contactloader = true;
                    axios.post("{{ route('api.adspage.initPage') }}", {
                            id: null
                        }).then(response => {
                            const data = response.data;



                            if (data.success) {
                                this.categories = data.cats;
                                this.cities = data.cities;
                                this.place_types = data.places_type;
                                this.priorities = data.project_priority;
                                this.districts = data.dept;
                                this.standings = data.standings;
                                this.proprety_types = data.types;
                                this.contactloader = false;





                            } else {
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.globalloader = false;
                            this.contactloader = false;
                        });
                },
                loadData() {
                    this.globalloader = true;
                    this.form = {
                        id: this.form.id,
                        title: '',
                        description: '',
                        price: '',
                        price_curr: 'DHS',
                        catid: null,
                        city: null,
                        dept: null,
                        lat: null,
                        long: null,
                        dept2: '',
                        userid: null,
                        phone: -1,
                        phone2: null,
                        wtsp: null,
                        email: -1,
                        is_project: false,
                        project_priority_id: null,
                        meuble: false,
                        terrace: false,
                        surfaceTerrace: null,
                        jardin: false,
                        surfaceJardin: null,
                        climatise: false,
                        syndic: false,
                        cave: false,
                        ascenseur: false,
                        securite: false,
                        groundfloor: false,
                        gardenlevel: false,
                        proprety_type: null,
                        standing: null,
                        ref: '',
                        vr_link: '',
                        rooms: null,
                        bedrooms: null,
                        wc: null,
                        parking: null,
                        contriction_date: null,
                        price_m: null,
                        piscine: null,
                        jardin: null,
                        surface: null,
                        surface2: null,
                        nearbyPlaces: [],
                        video_embed: '',
                        bg_image: [],
                        images: [],
                        videos: [],
                        audios: [],
                        status: '10',
                    };
                    axios.post("/api/v2/items/getAdById", {
                            id: this.form.id
                        }).then(response => {
                            console.log('my response');
                            this.globalloader = false;
                            const data = response.data;

                            if (data.success) {
                                this.userphones = data.data.userPhones;
                                this.userEmails = data.data.userEmails;
                                this.userWtsps = data.data.userWtsps;
                                this.form.images = data.data.images;
                                this.form.videos = data.data.videos;
                                this.form.audios = data.data.audios;
                                this.form.city = data.data.ad.loccity;
                                this.form.dept = data.data.ad.locdept;


                                for (const np of data.data.nearby_places) {
                                    this.placesForm.id++;
                                    this.placesForm.title = np.title;
                                    this.placesForm.distance = np.distance;
                                    this.placesForm.types = np.id_place_type;
                                    this.placesForm.lat = np.lat;
                                    this.placesForm.long = np.lng;
                                    this.form.nearbyPlaces.push(this.placesForm);
                                }
                                if (data.data.ad) {


                                    this.form.title = data.data.ad.title;
                                    this.form.description = data.data.ad.description;
                                    this.form.catid = data.data.ad.catid;
                                    this.form.price = data.data.ad.price;
                                    this.form.price_curr = data.data.ad.price_curr;
                                    this.form.userid = data.data.ad.id_user;
                                    this.form.phone = data.data.ad.phone;
                                    this.form.phone2 = data.data.ad.phone2;
                                    this.form.wtsp = data.data.ad.wtsp;
                                    this.form.email = data.data.ad.email;
                                    this.form.is_project = data.data.ad.is_project == 1 ? true : false;
                                    this.form.terrace = data.data.ad.terrace == 1 ? true : false;
                                    this.form.surfaceTerrace = data.data.ad.terrace_surface;
                                    this.form.project_priority = data.data.ad.project_priority;
                                    this.form.meuble = data.data.ad.meuble == 1 ? true : false;
                                    this.form.jardin = data.data.ad.jardin == 1 ? true : false;
                                    this.form.surfaceJardin = data.data.ad.jardin_surface;
                                    this.form.climatise = data.data.ad.climatise == 1 ? true : false;
                                    this.form.syndic = data.data.ad.syndic == 1 ? true : false;
                                    this.form.cave = data.data.ad.cave == 1 ? true : false;
                                    this.form.ascenseur = data.data.ad.ascenseur == 1 ? true : false;
                                    this.form.securite = data.data.ad.securite == 1 ? true : false;
                                    this.form.groundfloor = data.data.ad.groundfloor == 1 ? true : false;
                                    this.form.gardenlevel = data.data.ad.gardenlevel == 1 ? true : false;
                                    this.form.proprety_type = data.data.ad.property_type;
                                    this.form.standing = data.data.ad.standing;
                                    this.form.ref = data.data.ad.ref;
                                    this.form.vr_link = data.data.ad.vr_link;
                                    this.form.rooms = data.data.ad.rooms;
                                    this.form.bedrooms = data.data.ad.bedrooms;
                                    this.form.wc = data.data.ad.bathrooms;
                                    this.form.parking = data.data.ad.parking;
                                    this.form.contriction_date = data.data.ad.built_year;
                                    this.form.price_m = data.data.ad.price_surface;
                                    this.form.surface = data.data.ad.surface;
                                    this.form.long = data.data.ad.loclng;
                                    this.form.lat = data.data.ad.loclat;
                                    //this.form.city= data.data.ad.loccity;
                                    //this.form.dept= data.data.ad.locdept2;
                                    this.form.video_embed = data.data.ad.videoembed;
                                    this.form.status = data.data.ad.status;
                                    this.form.bg_image = data.data.bg_image;
                                    this.oldForm = this.form;



                                    this.changeCity(true);
                                }


                                this.globalloader = false;
                                this.contactloader = false;
                            } else {
                                this.globalloader = false;
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            this.globalloader = false;
                            this.contactloader = false;
                            console.log(error);
                            /*var err = error.response.data.error;
                            displayToast(err,'#842029');*/
                        });
                },
                changeCity(edit = false) {
                    if (edit == false) {
                        this.form.dept = null;
                        this.form.lat = null;
                        this.form.long = null;
                    }
                    this.deptloader = true;
                    axios.post("{{ route('api.adspage.loadDeptsByCity') }}", {
                            city: this.form.city
                        }).then(response => {
                            const data = response.data;
                            if (data.success) {
                                this.districts = data.data;
                                this.deptloader = false;
                                if (edit) this.changeDept(true);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.deptloader = false;
                        });
                },
                changeDept(edit = false) {
                    $('#dept2_cnt').hide();
                    $('#map_cnt').hide();
                    $('#map').html('');
                    if (edit == false) {
                        this.form.lat = null;
                        this.form.long = null;
                    }
                    if (this.form.dept == '-1') $('#dept2_cnt').show();
                    else {
                        const selected_dept = findObjInArrById(this.districts, this.form.dept);
                        console.log(selected_dept);
                        var coordinates = null;
                        var lngLat = null;
                        if (this.form.lat && this.form.long) var lngLat = edit == false ? null : [this.form.long,
                            this.form.lat
                        ];
                        else {
                            if (selected_dept.coordinates) lngLat = centroid(selected_dept.coordinates.coordinates);
                            else if (selected_dept.lat && selected_dept.lng) lngLat = [selected_dept.lng,
                                selected_dept.lat
                            ];
                            else if (selected_dept.dCoordinates) lngLat = centroid(selected_dept.dCoordinates
                                .coordinates);
                        }
                        if (selected_dept.coordinates) coordinates = selected_dept.coordinates;
                        else if (selected_dept.dCoordinates) coordinates = selected_dept.dCoordinates;

                        if (lngLat) {
                            this.form.lat = lngLat[1];
                            this.form.long = lngLat[0];
                            $('#map_cnt').show();
                            const filterGroup = document.getElementById('map-filter-group');
                            filterGroup.innerHTML = '';
                            var map = new maplibregl.Map({
                                container: 'map',
                                style: mapStyle,
                                hash: false,
                                maxBounds: [
                                    [-17.8, 20],
                                    [0, 36.1]
                                ],
                                minZoom: 5,
                                center: [lngLat[0], lngLat[1]], // starting position [lng, lat]
                                zoom: 11 // starting zoom
                            });



                            const layers = ["school", "hospital", "grocery"];
                            layers.forEach(myFunction);


                        function myFunction(value, index, array) {
                            const inputCnt = document.createElement('div');
                            inputCnt.class = 'map-filter-input-cnt';
                            filterGroup.appendChild(inputCnt);
                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.id = value;
                            input.checked = false;
                            inputCnt.appendChild(input);

                                const label = document.createElement('label');
                                label.setAttribute('for', value);
                                label.textContent = value;
                                inputCnt.appendChild(label);

                                // When the checkbox changes, update the visibility of the layer.
                                input.addEventListener('change', (e) => {
                                    map.setLayoutProperty(
                                        value,
                                        'visibility',
                                        e.target.checked ? 'visible' : 'none'
                                    );
                                });
                            }

                            map.on('load', () => {

                                var el = document.createElement('img');
                                el.className = 'marker';
                                el.src = '/assets/img/marker.png';
                                el.style.width = '30px';
                                el.style.height = '40px';
                                el.style.top = '-20px';

                                var marker = new maplibregl.Marker({
                                        element: el,
                                        draggable: true
                                    })
                                    .setLngLat(lngLat)
                                    .addTo(map);

                                $('#mapSearch').on('keypress', (e) => {
                                    if (e.which == 13) {
                                        var lng = e.target.value.split(",")[1];
                                        var lat = e.target.value.split(",")[0];


                                        console.log(lng, lat);

                                        if (coordinates) {
                                            var polygon = coordinates.coordinates[0][0];
                                            if (inside([lng, lat], polygon) == false) {
                                                swal("error", "", "error");
                                            } else {
                                                this.form.lat = lat;
                                                this.form.long = lng;
                                                marker.setLngLat([lng, lat]);
                                            }
                                        } else {
                                            this.form.lat = lat;
                                            this.form.long = lng;
                                            marker.setLngLat([lng, lat]);
                                        }

                                    }
                                });

                                marker.on('dragend', () => {
                                    var lngLatNew = marker.getLngLat();

                                    if (coordinates) {
                                        var polygon = coordinates.coordinates[0][0];
                                        if (inside([lngLatNew.lng, lngLatNew.lat], polygon) ==
                                            false) {
                                            this.form.lat = lngLat[1];
                                            this.form.long = lngLat[0];
                                            marker.setLngLat(lngLat);
                                            swal("error", "", "error");
                                        } else {
                                            this.form.lat = lngLatNew.lat;
                                            this.form.long = lngLatNew.lng;
                                        }
                                    } else {
                                        this.form.lat = lngLatNew.lat;
                                        this.form.long = lngLatNew.lng;
                                    }
                                });

                                if (coordinates) {
                                    map.addSource('selected_place', {
                                        'type': 'geojson',
                                        'data': {
                                            'type': 'FeatureCollection',
                                            'features': [{
                                                'type': 'Feature',
                                                'geometry': coordinates
                                            }]
                                        }
                                    });
                                    map.addLayer({
                                        'id': 'place-boundary',
                                        'type': 'fill',
                                        'source': 'selected_place',
                                        'paint': {
                                            'fill-color': '#198754',
                                            'fill-opacity': 0.4
                                        },
                                        'filter': ['==', '$type', 'Polygon']
                                    });
                                }

                            });

                        }

                    }
                },
                AddPlaceModal() {
                    const i = this.placesForm.id;
                    this.placesForm = {
                        id: i,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    };
                    showModal('addPlaceModal');
                },
                addPlace() {
                    this.placesForm.id++;
                    this.form.nearbyPlaces.push(this.placesForm);
                    hideModal('addPlaceModal');
                },
                updatePlace() {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == this.placesFormUpdate.id) {
                                this.form.nearbyPlaces[i] = this.placesFormUpdate;
                            }
                    }
                    hideModal('updatePlaceModal');
                },
                updatePlaceModal(place) {
                    this.placesFormUpdate = place;
                    showModal('updatePlaceModal');
                },
                deletePlace(id) {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == id) this.form.nearbyPlaces.splice(i, 1);
                    }
                },
                save0() {


                    if (JSON.stringify(this.oldForm) !== JSON.stringify(this.form)) {


                        alert('can not be updated');
                        return false;
                    }

                    if (false) {

                        this.globalloader = false;


                        return;
                    }
                    swal("voulez-vous vraiment modifier cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {

                        if (val === true) {

                            this.save();
                        }
                    });
                },
                save() {


                    this.globalloader = true;
                    this.errors = {};
                    this.error = '';



                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/items/updateAd`
                    };
                    axios(config)
                        .then((response) => {


                            if (response.data.success == true) {
                                this.display = false;
                                for (let i = 0; i < itemsApp.datatable.rows.length; i++) {
                                    if (itemsApp.datatable.rows[i].id == response.data.data.id) {
                                        itemsApp.datatable.rows[i] = response.data.data;
                                    }
                                }
                                displayToast("L'annonce a été modifiée avec succès", '#0f5132');
                            }
                        })
                        .catch(error => {
                            this.globalloader = false;
                            console.log('error', error);
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else {
                                this.errorText = error.response.data.error;
                                displayToast(this.errorText, '#842029');
                            }
                        });
                },
                isCatChild(idParent) {
                    var obj = findObjInArrById(this.categories, this.form.catid);
                    return obj && obj.parent_cat == idParent;
                },
                /*  validateForm(){
                     this.errors=[];
                     $('#update-ad .popup-body').scrollTop(0);
                     let r = true;
                     if(!this.form.title || this.form.title.trim()==""){
                         if(!this.errors.title) this.errors.title = [];
                         this.errors.title.push('title');
                     }
                     if(onlyAlphabets.test(this.form.title)==false){
                         if(!this.errors.title) this.errors.title = [];
                         this.errors.title.push('titre doit ne doit contenir que les alphabets');
                         r = false;
                     }
                     if(!this.form.description || this.form.description.trim()==""){
                         if(!this.errors.description) this.errors.description = [];
                         this.errors.description.push('The description field is required.');
                         r = false;
                     }
                     if(!this.form.price){
                         if(!this.errors.price) this.errors.price = [];
                         this.errors.price.push('The price field is required.');
                         r = false;
                     }
                     if(this.form.catid==null){
                         if(!this.errors.catid) this.errors.catid = [];
                         this.errors.catid.push('The catid field is required.');
                         r = false;
                     }
                     if(this.form.city==null){
                         if(!this.errors.city) this.errors.city = [];
                         this.errors.city.push('The city field is required.');
                         r = false;
                     }
                     if(this.form.dept==null){
                         if(!this.errors.dept) this.errors.dept = [];
                         this.errors.dept.push('The dept field is required.');
                         r = false;
                     }
                     if(!this.form.userid){
                         if(!this.errors.userid) this.errors.userid = [];
                         this.errors.userid.push('The userid field is required.');
                         r = false;
                     }
                     else if(isNaN(this.form.userid)){
                         if(!this.errors.userid) this.errors.userid = [];
                         this.errors.userid.push('The userid must be an integer.');
                         r = false;
                     }
                     if(this.form.phone==null){
                         if(!this.errors.phone) this.errors.phone = [];
                         this.errors.phone.push('The phone field is required.');
                         r = false;
                     }
                     if(this.form.email==null){
                         if(!this.errors.email) this.errors.email = [];
                         this.errors.email.push('The email field is required.');
                         r = false;
                     }
                     if(isNaN(this.form.rooms)){
                         if(!this.errors.rooms) this.errors.rooms = [];
                         this.errors.rooms.push('The rooms must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.bedrooms)){
                         if(!this.errors.bedrooms) this.errors.bedrooms = [];
                         this.errors.bedrooms.push('The bedrooms must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.wc)){
                         if(!this.errors.wc) this.errors.wc = [];
                         this.errors.wc.push('The wc must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.parking)){
                         if(!this.errors.parking) this.errors.parking = [];
                         this.errors.parking.push('The parking must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.contriction_date)){
                         if(!this.errors.contriction_date) this.errors.contriction_date = [];
                         this.errors.contriction_date.push('The contriction_date must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.price_m)){
                         if(!this.errors.price_m) this.errors.price_m = [];
                         this.errors.price_m.push('The price_m must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.piscine)){
                         if(!this.errors.piscine) this.errors.piscine = [];
                         this.errors.piscine.push('The piscine must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.jardin)){
                         if(!this.errors.jardin) this.errors.jardin = [];
                         this.errors.jardin.push('The jardin must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.surface)){
                         if(!this.errors.surface) this.errors.surface = [];
                         this.errors.surface.push('The surface must be an integer.');
                         r = false;
                     }
                     if(isNaN(this.form.surface2)){
                         if(!this.errors.surface2) this.errors.surface2 = [];
                         this.errors.surface2.push('The surface2 must be an integer.');
                         r = false;
                     }
                     return r;
                 } */
            },
        }, ).mount('#update-ad')

        //Add Dispo MODAL ------------------------------------------------------------------

        let addDispoModal = Vue.createApp({
            data() {
                return {
                    errors: {},
                    errorText: '',
                    display: false,
                    globalloader: false,
                    contactloader: false,
                    deptloader: false,
                    categories: [],
                    cities: [],
                    districts: [],
                    proprety_types: [],
                    standings: [],
                    place_types: [],
                    userphones: [],
                    userEmails: [],
                    userWtsps: [],
                    form: {
                        id: null,
                        title: '',
                        description: '',
                        price: '',
                        price_curr: 'DHS',
                        catid: null,
                        city: null,
                        dept: null,
                        lat: null,
                        long: null,
                        dept2: '',
                        userid: null,
                        parent_project: null,
                        phone: -1,
                        phone2: null,
                        wtsp: null,
                        email: -1,
                        is_project: false,
                        meuble: false,
                        groundfloor: false,
                        gardenlevel: false,
                        proprety_type: null,
                        standing: null,
                        ref: '',
                        vr_link: '',
                        rooms: null,
                        bedrooms: null,
                        wc: null,
                        parking: null,
                        contriction_date: null,
                        price_m: null,
                        piscine: null,
                        jardin: null,
                        surface: null,
                        surface2: null,
                        surface: '',
                        nearbyPlaces: [],
                        video_embed: '',
                        bg_image: [],
                        images: [],
                        videos: [],
                        audios: [],
                        status: '10',
                    },
                    placesForm: {
                        id: 0,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    },
                    placesFormUpdate: {
                        id: null,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    },
                    selecttest: [{
                        name: 'choose',
                        value: null
                    }, {
                        name: 'A',
                        value: 1
                    }, {
                        name: 'B',
                        value: 2
                    }],
                    selecttestModel: null,
                    map: null,
                    marker: null,
                }
            },
            components: {
                "uploadFilesComponent": uploadFilesComponent,
                'PopupComponent': PopupComponent,
            },
            computed: {},
            watch: {
                form: {
                    handler() {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('form.' + key, () => {
                                delete this.errors[key];
                            });
                        }
                    },
                    deep: true
                },


            },
            mounted() {},
            methods: {
                loadPage() {
                    this.contactloader = true;
                    axios.post("{{ route('api.adspage.initPage') }}", {
                            id: this.form.userid
                        }).then(response => {
                            const data = response.data;
                            if (data.success) {
                                this.categories = data.cats;
                                this.cities = data.cities;
                                this.place_types = data.places_type;
                                this.standings = data.standings;
                                this.proprety_types = data.types;
                                this.userphones = data.userphones;
                                this.userEmails = data.userEmails;
                                this.userWtsps = data.userWtsps;
                                this.contactloader = false;
                            } else {
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.globalloader = false;
                            this.contactloader = false;
                        });
                },
                changeCity(edit = false) {
                    if (edit == false) {
                        this.form.dept = null;
                        this.form.lat = null;
                        this.form.long = null;
                    }
                    this.deptloader = true;
                    axios.post("{{ route('api.adspage.loadDeptsByCity') }}", {
                            city: this.form.city
                        }).then(response => {
                            const data = response.data;
                            if (data.success) {
                                this.districts = data.data;
                                this.deptloader = false;
                                if (edit) this.changeDept(true);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.deptloader = false;
                        });
                },
                changeDept(edit = false) {
                    $('#dept2_cnt').hide();
                    $('#map_cnt').hide();
                    $('#map').html('');
                    if (edit == false) {
                        this.form.lat = null;
                        this.form.long = null;
                    }
                    if (this.form.dept == '-1') $('#dept2_cnt').show();
                    else {
                        const selected_dept = findObjInArrById(this.districts, this.form.dept);
                        var coordinates = null;
                        var lngLat = edit == false ? null : [this.form.long, this.form.lat];
                        if (selected_dept.coordinates) coordinates = selected_dept.coordinates;
                        else if (selected_dept.dCoordinates) coordinates = selected_dept.dCoordinates;

                        if (edit == false) {
                            if (selected_dept.coordinates) lngLat = centroid(selected_dept.coordinates.coordinates);
                            else if (selected_dept.lat && selected_dept.lng) lngLat = [selected_dept.lng,
                                selected_dept.lat
                            ];
                            else if (selected_dept.dCoordinates) lngLat = centroid(selected_dept.dCoordinates
                                .coordinates);
                        }
                        if (lngLat) {
                            this.form.lat = lngLat[1];
                            this.form.long = lngLat[0];
                            $('#mapDispo_cnt').show();

                            const filterGroup = document.getElementById('mapDispo-filter-group');
                            filterGroup.innerHTML = '';
                            var map = new maplibregl.Map({
                                container: 'mapDispo',
                                style: mapStyle,
                                hash: false,
                                maxBounds: [
                                    [-17.8, 20],
                                    [0, 36.1]
                                ],
                                minZoom: 5,
                                center: [lngLat[0], lngLat[1]], // starting position [lng, lat]
                                zoom: 11 // starting zoom
                            });



                            const layers = ["school", "hospital", "grocery"];
                            layers.forEach(myFunction);


                        function myFunction(value, index, array) {
                            const inputCnt = document.createElement('div');
                            inputCnt.class = 'mapDispo-filter-input-cnt';
                            filterGroup.appendChild(inputCnt);
                            const input = document.createElement('input');
                            input.type = 'checkbox';
                            input.id = value;
                            input.checked = false;
                            inputCnt.appendChild(input);

                                const label = document.createElement('label');
                                label.setAttribute('for', value);
                                label.textContent = value;
                                inputCnt.appendChild(label);

                                // When the checkbox changes, update the visibility of the layer.
                                input.addEventListener('change', (e) => {
                                    map.setLayoutProperty(
                                        value,
                                        'visibility',
                                        e.target.checked ? 'visible' : 'none'
                                    );
                                });
                            }

                            map.on('load', () => {

                                var el = document.createElement('img');
                                el.className = 'marker';
                                el.src = '/assets/img/marker.png';
                                el.style.width = '30px';
                                el.style.height = '40px';
                                el.style.top = '-20px';

                                var marker = new maplibregl.Marker({
                                        element: el,
                                        draggable: true
                                    })
                                    .setLngLat(lngLat)
                                    .addTo(map);

                                $('#mapSearch').on('keypress', (e) => {
                                    if (e.which == 13) {
                                        var lng = e.target.value.split(",")[1];
                                        var lat = e.target.value.split(",")[0];


                                        console.log(lng, lat);

                                        if (coordinates) {
                                            var polygon = coordinates.coordinates[0][0];
                                            if (inside([lng, lat], polygon) == false) {
                                                swal("error", "", "error");
                                            } else {
                                                this.form.lat = lat;
                                                this.form.long = lng;
                                                marker.setLngLat([lng, lat]);
                                            }
                                        } else {
                                            this.form.lat = lat;
                                            this.form.long = lng;
                                            marker.setLngLat([lng, lat]);
                                        }

                                    }
                                });

                                marker.on('dragend', () => {
                                    var lngLatNew = marker.getLngLat();

                                    if (coordinates) {
                                        var polygon = coordinates.coordinates[0][0];
                                        if (inside([lngLatNew.lng, lngLatNew.lat], polygon) ==
                                            false) {
                                            this.form.lat = lngLat[1];
                                            this.form.long = lngLat[0];
                                            marker.setLngLat(lngLat);
                                            swal("error", "", "error");
                                        } else {
                                            this.form.lat = lngLatNew.lat;
                                            this.form.long = lngLatNew.lng;
                                        }
                                    } else {
                                        this.form.lat = lngLatNew.lat;
                                        this.form.long = lngLatNew.lng;
                                    }
                                });

                                if (coordinates) {
                                    map.addSource('selected_place', {
                                        'type': 'geojson',
                                        'data': {
                                            'type': 'FeatureCollection',
                                            'features': [{
                                                'type': 'Feature',
                                                'geometry': coordinates
                                            }]
                                        }
                                    });
                                    map.addLayer({
                                        'id': 'place-boundary',
                                        'type': 'fill',
                                        'source': 'selected_place',
                                        'paint': {
                                            'fill-color': '#198754',
                                            'fill-opacity': 0.4
                                        },
                                        'filter': ['==', '$type', 'Polygon']
                                    });
                                }

                            });

                        }

                    }
                },
                AddPlaceModal() {
                    const i = this.placesForm.id;
                    this.placesForm = {
                        id: i,
                        title: '',
                        distance: '',
                        types: null,
                        lat: '',
                        long: ''
                    };
                    showModal('addPlaceModal');
                },
                addPlace() {
                    this.placesForm.id++;
                    this.form.nearbyPlaces.push(this.placesForm);
                    hideModal('addPlaceModal');
                },
                updatePlace() {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == this.placesFormUpdate.id) {
                                this.form.nearbyPlaces[i] = this.placesFormUpdate;
                            }
                    }
                    hideModal('updatePlaceModal');
                },
                updatePlaceModal(place) {
                    this.placesFormUpdate = place;
                    showModal('updatePlaceModal');
                },
                deletePlace(id) {
                    for (let i = 0; i < this.form.nearbyPlaces.length; i++) {
                        const place = this.form.nearbyPlaces[i];
                        if (place)
                            if (place.id == id) this.form.nearbyPlaces.splice(i, 1);
                    }
                },
                save0() {
                    if (!this.validateForm()) {
                        this.globalloader = false;
                        return;
                    }
                    swal("voulez-vous vraiment ajouter cette annonce?", {
                        buttons: ["Non", "Oui"],
                    }).then((val) => {
                        if (val == true) {
                            this.save();
                        }
                    });
                },
                save() {
                    this.globalloader = true;
                    this.errors = {};
                    this.error = '';
                    console.log('gg', this.form);

                    //if(!this.validateForm()){ this.globalloader=false; return; }
                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/items/addAd`
                    };
                    axios(config)
                        .then((response) => {
                            this.globalloader = false;
                            console.log(response.data);
                            if (response.data.success == true) {
                                this.display = false;
                                itemsApp.datatable.rows.unshift(response.data.data);
                                displayToast("L'annonce a été ajoutée avec succès", '#0f5132');
                            }
                        })
                        .catch(error => {
                            this.globalloader = false;
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else {
                                this.errorText = error.response.data.error;
                                displayToast(this.errorText, '#842029');
                            }
                        });
                },
                isCatChild(idParent) {
                    var obj = findObjInArrById(this.categories, this.form.catid);
                    return obj && obj.parent_cat == idParent;
                },
                validateForm() {
                    this.errors = [];
                    $('#update-ad .popup-body').scrollTop(0);
                    let r = true;
                    if (!this.form.title || this.form.title.trim() == "") {
                        if (!this.errors.title) this.errors.title = [];
                        this.errors.title.push('Le titre est requis.');
                        r = false;
                    }
                     {{--  if (this.form.title === '') {
                        if (!this.errors.title) this.errors.title = [];
                        this.errors.title.push('Le tit');
                        r = false;
                    }    --}}
                    if (!this.form.description || this.form.description.trim() == "") {
                        if (!this.errors.description) this.errors.description = [];
                        this.errors.description.push('La description est requise.');
                        r = false;
                    }
                    {{--  if (!this.form.price) {
                        if (!this.errors.price) this.errors.price = [];
                        this.errors.price.push('Le prix est requis.');
                        r = false;
                    }  --}}
                    if (this.form.catid == null) {
                        if (!this.errors.catid) this.errors.catid = [];
                        this.errors.catid.push('La catégorie est requise.');
                        r = false;
                    }
                    if (this.form.city == null) {
                        if (!this.errors.city) this.errors.city = [];
                        this.errors.city.push('La ville est requise.');
                        r = false;
                    }
                    if (this.form.dept == null) {
                        if (!this.errors.dept) this.errors.dept = [];
                        this.errors.dept.push('Le quartier est requis.');
                        r = false;
                    }
                    if (!this.form.userid) {
                        if (!this.errors.userid) this.errors.userid = [];
                        this.errors.userid.push('Le userid est requis.');
                        r = false;
                    } else if (isNaN(this.form.userid)) {
                        if (!this.errors.userid) this.errors.userid = [];
                        this.errors.userid.push('Le userid doit être un entier.');
                        r = false;
                    }
                    if (this.form.phone == null) {
                        if (!this.errors.phone) this.errors.phone = [];
                        this.errors.phone.push('Le téléphone est requis.');
                        r = false;
                    }
                    if (this.form.email == null) {
                        if (!this.errors.email) this.errors.email = [];
                        this.errors.email.push('L\'email est requis.');
                        r = false;
                    }
                    if (isNaN(this.form.rooms)) {
                        if (!this.errors.rooms) this.errors.rooms = [];
                        this.errors.rooms.push('Le nombre des pièces doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.bedrooms)) {
                        if (!this.errors.bedrooms) this.errors.bedrooms = [];
                        this.errors.bedrooms.push('Le nombre des chambres doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.wc)) {
                        if (!this.errors.wc) this.errors.wc = [];
                        this.errors.wc.push('Le nombre des salles de bains doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.parking)) {
                        if (!this.errors.parking) this.errors.parking = [];
                        this.errors.parking.push('Le nombre des parkings doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.contriction_date)) {
                        if (!this.errors.contriction_date) this.errors.contriction_date = [];
                        this.errors.contriction_date.push('La date de construction doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.price_m)) {
                        if (!this.errors.price_m) this.errors.price_m = [];
                        this.errors.price_m.push('Le prix du m² doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.piscine)) {
                        if (!this.errors.piscine) this.errors.piscine = [];
                        this.errors.piscine.push('Le nombre des piscines doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.jardin)) {
                        if (!this.errors.jardin) this.errors.jardin = [];
                        this.errors.jardin.push('Le nombre des jardins doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.surface)) {
                        if (!this.errors.surface) this.errors.surface = [];
                        this.errors.surface.push('La supérficie doit être un entier.');
                        r = false;
                    }
                    if (isNaN(this.form.surface2)) {
                        if (!this.errors.surface2) this.errors.surface2 = [];
                        this.errors.surface2.push('La surface doit être un entier.');
                        r = false;
                    }
                    return r;
                }
            },
        }, ).mount('#addDispo-ad')
    </script>

@endsection
