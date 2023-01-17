@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')

    <link rel="stylesheet" href="{{ asset('css/components/upload-files-component.vue.css') }}">
    <script src="{{ asset('js/components/upload-files-component.vue.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>

    <script src='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.js'></script>
    <link href='https://unpkg.com/maplibre-gl@2.1.9/dist/maplibre-gl.css' rel='stylesheet' />

@endsection

@section('content')
    <!-- ======= main ======= -->
    <div id="add-ad">

        <div class="pagetitle">
            <h1>Ajouter une annonce</h1>
        </div><!-- End Page Title -->

        <section class="section">
            <div id="addForm" class="needs-validation">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-6">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Général</h5>

                                <!-- General Form Elements -->
                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Titre :</label>
                                    <div class="col-sm-8">
                                        <input name="title" type="text" class="form-control"
                                            :class="errors.title ? 'is-invalid' : ''" v-model="form.title">
                                        <div class="invalid-feedback">
                                            @{{ errors.title?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputDesc" class="col-sm-4 col-form-label">Descripton :</label>
                                    <div class="col-sm-8">
                                        <textarea name="desc" class="form-control" :class="errors.description ? 'is-invalid' : ''" style="height: 100px"
                                            v-model="form.description"></textarea>
                                        <div class="invalid-feedback">
                                            @{{ errors.description?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputPrice" class="col-sm-4 col-form-label">Prix :</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input name="price" type="number" class="form-control"
                                                :class="errors.price ? 'is-invalid' : ''" v-model="form.price"
                                                min="0">
                                            <select name="price_curr" class="inputtypeselect" v-model="form.price_curr">
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
                                    <label class="col-sm-4 col-form-label">Catégorie :</label>
                                    <div class="col-sm-8">
                                        <select name="catid" class="form-select select2init"
                                            :class="errors.catid ? 'is-invalid' : ''" id="categories-select"
                                            v-model="form.catid">
                                            <option :value="null">Choisir une Catégorie</option>
                                            <option v-for="cat in categories" :value="cat.id">@{{ cat.title }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.catid?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Ville :</label>
                                    <div class="col-sm-8">
                                        <select name="city" class="form-select select2init"
                                            :class="errors.city ? 'is-invalid' : ''" id="cities-select"
                                            @change="changeCity()" v-model="form.city">
                                            <option :value="null">Choisir une ville</option>
                                            <option v-for="city in cities" :value="city.id">@{{ city.name }}
                                            </option>
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
                                    <label class="col-sm-4 col-form-label">Quartier :</label>
                                    <div class="col-sm-8">
                                        <select name="dept" class="form-select select2init"
                                            :class="errors.dept ? 'is-invalid' : ''" id="depts-select"
                                            @change="changeDept()" v-model="form.dept">
                                            <option :value="null">Choisir un quartier</option>
                                            <option :value="-1">Autre</option>
                                            <option v-for="dept in neighborhoods" :value="dept.id">
                                                @{{ dept.name }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.dept?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="dept2_cnt" v-if="form.dept==-1">
                                    <label class="col-sm-4 col-form-label">Ajouter un quartie :r</label>
                                    <div class="col-sm-8">
                                        <input id="locdept2" name="dept2" type="text" class="form-control"
                                            :class="errors.dept2 ? 'is-invalid' : ''" maxlength="199" v-model="form.dept2">
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
                                <h5 class="card-title">Media :</h5>

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Video Youtube</label>
                                    <div class="col-sm-8">
                                        <textarea name="videoembed" class="form-control" style="height: 100px;"
                                            :class="errors.video_embed ? 'is-invalid' : ''" v-model="form.video_embed"></textarea>
                                        <div class="invalid-feedback">
                                            @{{ errors.video_embed?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputRooms" class="col-sm-12 col-form-label">Images (png/jpeg) :</label>
                                    <div class="col-sm-12">
                                        <upload-files-component :error="errors.images ? true : false"
                                            v-model:files="form.images" type="images" :max="50"
                                            :allowed-extensions="['jpg', 'jpeg', 'png', 'webp','JPG']" :multiple="true" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputRooms" class="col-sm-12 col-form-label">Vidéos (mp4) :</label>
                                    <div class="col-sm-12">
                                        <upload-files-component :error="errors.videos ? true : false"
                                            v-model:files="form.videos" type="videos" :max="50"
                                            :allowed-extensions="['mp4', 'mov', 'ogg']" :multiple="true" />
                                    </div>
                                </div>

                                <div class="row mb-3" v-if="false">
                                    <label for="inputRooms" class="col-sm-12 col-form-label">Audios (mp3) :</label>
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
                                <h5 class="card-title">Contact :</h5>

                                <!-- Contact Form Elements -->

                                <div class="row mb-3">
                                    <label class="col-sm-4 col-form-label">Utilisateur ID</label>
                                    <div class="col-sm-8">
                                        <input id="userid2" name="userid" type="number" class="form-control"
                                            :class="errors.userid ? 'is-invalid' : ''" @input="loadUserContacts()"
                                            v-model="userid" min="1">
                                        <div class="invalid-feedback">
                                            @{{ errors.userid?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div v-if="contactloader==true" style="text-align: center;">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>

                                <div v-if="contactloader==false">
                                    <div class="row mb-3">
                                        <label class="col-sm-4 col-form-label">Télephone :</label>
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
                                        <label class="col-sm-4 col-form-label">Télephone 2 :</label>
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
                                        <label class="col-sm-4 col-form-label">Whatsapp :</label>
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
                                        <label class="col-sm-4 col-form-label">Email :</label>
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
                                <h5 class="card-title">Autre :</h5>

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
                                                v-model="form.project_priority_id">
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
                                                :allowed-extensions="['jpg', 'jpeg', 'png', 'webp','JPG']"
                                                :multiple="true" />
                                        </div>
                                    </div>



                                </div>

                                <div class="row mb-3">
                                    <label for="inputVr" class="col-sm-4 col-form-label">Lien de visite virtuelle</label>
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



                                <div class="row mb-3" v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                    <label class="col-sm-4 col-form-label">Standing</label>
                                    <div class="col-sm-8">
                                        <select name="property" :class="errors.standing ? 'is-invalid' : ''"
                                            class="form-select select2init" id="standing-select" v-model="form.standing">
                                            <option :value="null">Choisir Standing</option>
                                            <option v-for="standing in standings" :value="standing.id">
                                                @{{ standing.designation }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            @{{ errors.standing?.join('<br/>') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="inputs-cnt-grid" v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                    <div class="row mb-3" style="margin:0;">
                                        <label for="inputRooms" class="col-sm-4 col-form-label">Pièces</label>
                                        <div class="col-sm-8">
                                            <input name="rooms" type="number"
                                                :class="errors.rooms ? 'is-invalid' : ''" class="form-control"
                                                v-model="form.rooms" min="0">
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
                                                v-model="form.bedrooms" min="0">
                                            <div class="invalid-feedback">
                                                @{{ errors.bedrooms?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" style="margin:0;"
                                        v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)||isCatChild(5)">
                                        <label for="inputBathrooms" class="col-sm-4 col-form-label">Salles de
                                            bains</label>
                                        <div class="col-sm-8">
                                            <input name="bathrooms" type="number" :class="errors.wc ? 'is-invalid' : ''"
                                                class="form-control" v-model="form.wc" min="0">
                                            <div class="invalid-feedback">
                                                @{{ errors.wc?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" style="margin:0;"
                                        v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)||isCatChild(5)">
                                        <label for="inputBathrooms" class="col-sm-4 col-form-label">Parkings</label>
                                        <div class="col-sm-8">
                                            <input name="parking" type="number"
                                                :class="errors.parking ? 'is-invalid' : ''" class="form-control"
                                                v-model="form.parking" min="0">
                                            <div class="invalid-feedback">
                                                @{{ errors.parking?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" style="margin:0;"
                                        v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <label for="inputBathrooms" class="col-sm-4 col-form-label"
                                            style="
                            overflow: hidden;
                            white-space: nowrap;">Date
                                            de construction</label>
                                        <div class="col-sm-8">
                                            <input name="built_year" type="number"
                                                :class="errors.contriction_date ? 'is-invalid' : ''" class="form-control"
                                                v-model="form.contriction_date" placeholder="2022" min="0">
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
                                                v-model="form.price_m" min="0">
                                            <div class="invalid-feedback">
                                                @{{ errors.price_m?.join('<br/>') }}
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row mb-3" style="margin:0;"
                                        v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                        <label for="inputBathrooms" class="col-sm-4 col-form-label">Piscine</label>
                                        <div class="col-sm-8">
                                            <input name="piscine" type="number"
                                                :class="errors.piscine ? 'is-invalid' : ''" class="form-control"
                                                v-model="form.piscine" min="0">
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
                                                    v-model="form.surface" min="0">
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


                                <div class="row mb-3" v-if="isCatChild(1)||isCatChild(5)||isCatChild(3)">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="meuble" id="meuble"
                                            v-model="form.meuble">
                                        <label class="form-check-label" for="gridCheck1">
                                            Meublé
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="terrace" id="terrace"
                                            v-model="form.terrace">
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
                                        <input class="form-check-input" type="checkbox" name="jardin" id="jardin"
                                            v-model="form.jardin">
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
                                        <input class="form-check-input" type="checkbox" name="climatise" id="climatise"
                                            v-model="form.climatise">
                                        <label class="form-check-label" for="gridCheck1">
                                            Climatisé
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="syndic" id="syndic"
                                            v-model="form.syndic">
                                        <label class="form-check-label" for="gridCheck1">
                                            Syndic
                                        </label>
                                    </div>

                                    <div class="form-check"
                                        v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)||isCatChild(5)">
                                        <input class="form-check-input" type="checkbox" name="cave" id="cave"
                                            v-model="form.cave">
                                        <label class="form-check-label" for="gridCheck1">
                                            La cave
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="ascenseur" id="ascenseur"
                                            v-model="form.ascenseur">
                                        <label class="form-check-label" for="gridCheck1">
                                            Ascenseur
                                        </label>
                                    </div>

                                    <div class="form-check" v-if="isCatChild(1)||isCatChild(2)||isCatChild(3)">
                                        <input class="form-check-input" type="checkbox" name="securite" id="securite"
                                            v-model="form.securite">
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
                                            id="gardenlevel" v-model="form.gardenlevel">
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
                                            <i class="bi bi-plus me-1"></i> Ajouter lieu
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
                        <button class="btn btn-success" @click="save">Ajouter</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- add place Modal -->
        <div class="modal fade modal-close" id="addPlaceModal" data-id="addPlaceModal">
            <div class="modal-dialog modal-lg">
                <form onsubmit="event.preventDefault()" id="addPlaceForm" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter lieu</h5>
                        <button type="button" class="btn-close modal-close" data-id="addPlaceModal"></button>
                    </div>
                    <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

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
                                    <input id="place-distance" name="place_distance" type="number" class="form-control"
                                        v-model="placesForm.distance">
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
                                    <option v-for="type in place_types" :value="type.id">@{{ type.designation }}
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-close"
                            data-id="addPlaceModal">Close</button>
                        <button id="addPlace" type="submit" class="btn btn-primary" @click="addPlace"
                            :disabled="loading">
                            Ajouter
                            <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
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
                    <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">

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
                                    <input id="place-distance" name="place_distance" type="number" class="form-control"
                                        v-model="placesFormUpdate.distance">
                                    <span class="input-group-text">m</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-4 col-form-label">type de lieu</label>
                            <div class="col-sm-8">
                                <select id="place-types" name="place_types" class="form-select"
                                    v-model="placesFormUpdate.type">
                                    <option :value="null">Choisir un type</option>
                                    <option v-for="type in place_types" :value="type.id">@{{ type.designation }}
                                    </option>
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
                <div>Operation en cours...</div>
            </div>
        </div>

    </div>

@endsection

@section('custom_foot')
    <script type="text/javascript">
        maplibregl.setRTLTextPlugin('https://unpkg.com/@mapbox/mapbox-gl-rtl-text@0.2.3/mapbox-gl-rtl-text.min.js', null,
            true);

        var addAdd = Vue.createApp({
            data() {
                return {
                    errors: {},
                    errorText: '',
                    loading: false,
                    userid: null,
                    globalloader: false,
                    contactloader: false,
                    deptloader: false,
                    categories: [],
                    priorities: [],
                    cities: [],
                    neighborhoods: [],
                    proprety_types: [],
                    standings: [],
                    place_types: [],
                    userphones: [],
                    userEmails: [],
                    userWtsps: [],
                    form: {
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
                        project_priority: null,
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
                        surface: null,
                        surface2: null,
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
                "uploadFilesComponent": uploadFilesComponent
            },
            computed: {},
            watch: {
                form: {
                    handler(newval) {
                        for (const key of Object.keys(this.errors)) {
                            this.$watch('form.' + key, (val, oldVal) => {
                                console.log(this.errors, this.errors[key]);
                                delete this.errors[key];
                            });
                        }
                    },
                    deep: true
                }
            },
            mounted() {
                this.userid = '{{ $user->id }}';
                if (this.userid) this.loadPage();
                $('.modal-close').click(function() {
                    hideModal($(this).attr('data-id'));
                }).children().click(function() {
                    return false;
                });
            },
            methods: {
                loadPage() {
                    this.globalloader = true;
                    this.contactloader = true;
                    axios.post("{{ route('api.adspage.initPage') }}", {
                            id: this.userid
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
                                this.neighborhoods = data.neighborhoods;
                                this.priorities = data.project_priority;
                                this.userWtsps = data.userWtsps;
                                this.globalloader = false;
                                this.contactloader = false;
                                $('#userid2').addClass('multilist_valid');
                            } else {
                                this.globalloader = false;
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            this.globalloader = false;
                            this.contactloader = false;
                        });
                },
                loadUserContacts() {
                    this.contactloader = true;
                    $('#userid2').removeClass('multilist_valid');
                    $('#userid2').removeClass('multilist_invalid');
                    axios.post("{{ route('api.users.getById') }}", {
                            id: this.userid
                        }).then(response => {
                            const data = response.data;
                            $('#userid2').removeClass('multilist_valid');
                            $('#userid2').removeClass('multilist_invalid');
                            if (data.success) {
                                this.form.phone = -1;
                                this.form.phone2 = null;
                                this.form.wtsp = null;
                                this.form.email = -1;
                                if (data.user) {
                                    this.userphones = data.userphones;
                                    this.userEmails = data.userEmails;
                                    this.userWtsps = data.userWtsps;
                                    this.contactloader = false;
                                    $('#userid2').addClass('multilist_valid');
                                } else {
                                    this.contactloader = false;
                                    $('#userid2').addClass('multilist_invalid');
                                }
                            } else {
                                $('#userid2').addClass('multilist_invalid');
                                this.contactloader = false;
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.contactloader = false;
                            $('#userid2').addClass('multilist_invalid');
                        });
                },
                changeCity() {
                    this.form.dept = null;
                    this.form.lat = null;
                    this.form.long = null;
                    this.deptloader = true;

                    axios.post("{{ route('api.adspage.loadDeptsByCity') }}", {
                            city: this.form.city
                        }).then(response => {
                            const data = response.data;
                            console.log('data', data)
                            if (data.success) {
                                this.neighborhoods = data.data;
                                this.deptloader = false;
                                //$("#depts-select").select2();
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            this.deptloader = false;
                        });
                },
                changeDept() {
                    $('#dept2_cnt').hide();
                    $('#map_cnt').hide();
                    $('#map').html('');
                    this.form.lat = null;
                    this.form.long = null;
                    if (this.form.dept == '-1') $('#dept2_cnt').show();
                    else {
                        const selected_dept = findObjInArrById(this.neighborhoods, this.form.dept);
                        var coordinates = null;
                        var lngLat = null;
                        if (selected_dept.coordinates) coordinates = selected_dept.coordinates;
                        else if (selected_dept.dCoordinates) coordinates = selected_dept.dCoordinates;

                        if(selected_dept.coordinates) lngLat = centroid(selected_dept.coordinates.coordinates);
                        else if(selected_dept.lat&&selected_dept.lng) lngLat = [selected_dept.lng,selected_dept.lat];
                        else if(selected_dept.dCoordinates) lngLat = centroid(selected_dept.dCoordinates.coordinates);
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

                        map.on('load', ()=> {

                            var el = document.createElement('img');
                            el.className = 'marker';
                            el.src = '/assets/img/marker.png';
                            el.style.width = '30px';
                            el.style.height = '40px';
                            el.style.top = '-20px';

                            var marker = new maplibregl.Marker(
                                {
                                    element: el,
                                    draggable: true
                                }
                            )
                            .setLngLat(lngLat)
                            .addTo(map);

                            $('#mapSearch').on('keypress',(e)=> {
                                if(e.which == 13) {
                                    var lng = e.target.value.split(",")[1];
                                    var lat = e.target.value.split(",")[0];


                                    console.log(lng,lat);

                                    if(coordinates) {
                                        var polygon = coordinates.coordinates[0][0];
                                        if(inside([  lng , lat ], polygon)==false){
                                            swal("error", "", "error");
                                        }
                                        else{
                                            this.form.lat = lat;
                                            this.form.long = lng;
                                            marker.setLngLat([lng,lat]);
                                        }
                                    }
                                    else{
                                        this.form.lat = lat;
                                        this.form.long = lng;
                                        marker.setLngLat([lng,lat]);
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
                isCatChild(idParent) {
                    var obj = findObjInArrById(this.categories, this.form.catid);
                    return obj && obj.parent_cat == idParent;
                },


                save() {

                    this.loading = true;
                    this.errors = {};
                    this.error = '';
                    this.form.userid = this.userid;

                    setTimeout(() => {
                        window.scrollTo(0, 0);
                    }, 400)

                    //if(!this.validateForm()){ this.loading = false; return; }

                    var config = {
                        method: 'post',
                        data: this.form,
                        url: `/api/v2/items/addAd`
                    };
                    axios(config)
                        .then((response) => {
                            this.loading = false;


                            if (response.data.success == true) {
                                swal("Annonce ajoutée!", "", "success").then(() => {
                                    window.location.href = '/admin/items';
                                });
                                setTimeout(() => {
                                    window.location.href = '/admin/items';
                                }, 5000);
                            }
                        })
                        .catch(error => {
                            this.loading = false;
                            if (typeof error.response.data.error === 'object') this.errors = error.response.data
                                .error;
                            else {
                                this.errorText = error.response.data.error;
                                displayToast(this.errorText, '#842029');
                            }
                        });
                },


                /*  validateForm(){
                     this.errors=[];
                     window.scrollTo(0,0);
                     let r = true;
                     if(!this.form.title || this.form.title.trim()=="" ){
                         if(!this.errors.title) this.errors.title = [];
                         this.errors.title.push('titre est obligatoir.');
                         r = false;
                     }
                     if(onlyAlphabets.test(this.form.title)==false){
                         if(!this.errors.title) this.errors.title = [];
                         this.errors.title.push('titre doit ne doit contenir que les alphabets');
                         r = false;
                     }
                     if(!this.form.description || this.form.description.trim()==""){
                         if(!this.errors.description) this.errors.description = [];
                         this.errors.description.push('déscription est obligatoir.');
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
                     if(!this.form.userid || this.form.userid.toString().trim()==""){
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
        }, ).mount('#add-ad')
    </script>

@endsection
