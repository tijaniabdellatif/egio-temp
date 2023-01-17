@extends('v2.layouts.dashboard')

@section('title', 'Cycle de vie')

@section('custom_head')

@endsection

@section('content')
<div class="pagetitle">
    <h1>Cycle de vie :</h1>
</div>
<section id="app" class="section">
    <div class="row">
        <div class="col-lg-6">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modifier le cycle de vie</h5>

                    <!-- General Form Elements -->
                    <div>

                        <div v-if="saveLoading" style="text-align:center;">
                            <div class="spinner-border spinner-border-sm ms-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Max d'annonces par utilisateur :</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" v-model="data.max_user_ads" min="1">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Max d'images par annonce</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" v-model="data.max_ad_img" min="1">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Max de videos par annonce :</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" v-model="data.max_ad_video" min="1">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Max d'audios par annonce :</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" v-model="data.max_ad_audio" min="1">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Les annonce s'expirent après :</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="data.ads_expire_duration" min="1">
                                    <span class="input-group-text">jours</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Les utilisateurs s'expirent après : </label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="data.users_expire_duration" min="1">
                                    <span class="input-group-text">jours</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Taille max des images : </label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="data.image_max_size">
                                    <span class="input-group-text">ko</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Taille max des videos : </label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="data.video_max_size">
                                    <span class="input-group-text">ko</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Taille max des audios : </label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="number" class="form-control" v-model="data.audio_max_size">
                                    <span class="input-group-text">ko</span>
                                </div>
                            </div>
                        </div>



                        <div class="row mb-3">
                            <div class="col-sm-12 d-flex align-items-center justify-content-center" style="text-align: center;">
                                <button type="submit" class="btn btn-primary" @click="save()" :disabled="saveLoading">Enregistrer</button>
                                <div class="spinner-border spinner-border-sm ms-2" v-if="saveLoading" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End General Form Elements -->

                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@section('custom_foot')

<script type="text/javascript">
    let app = Vue.createApp({
        data() {
            return {
                loading:false,
                saveLoading:false,
                data:{
                    max_user_ads: null,
                    max_ad_img: null,
                    max_ad_video: null,
                    max_ad_audio: null,
                    ads_expire_duration: null,
                    users_expire_duration: null,
                    image_max_size: null,
                    video_max_size: null,
                    audio_max_size: null,
                },
            }
        },
        watch: {},
        components: {},
        mounted() {
            this.loadData();
        },
        methods: {
            loadData(){
                var config = {
                    method: 'post',
                    url: `/api/v2/cycle/data`
                };


                this.loading = true;

                this.data.max_user_ads = null;
                this.data.max_ad_img = null;
                this.data.max_ad_video = null;
                this.data.max_ad_audio = null;
                this.data.ads_expire_duration = null;
                this.data.users_expire_duration = null;
                this.data.image_max_size = null;
                this.data.video_max_size = null;
                this.data.audio_max_size = null;

                axios(config)
                    .then((response) => {
                        this.loading = false;
                        if (response.data.success == true) {
                            this.data.max_user_ads = response.data.data.max_user_ads;
                            this.data.max_ad_img = response.data.data.max_ad_img;
                            this.data.max_ad_video = response.data.data.max_ad_video;
                            this.data.max_ad_audio = response.data.data.max_ad_audio;
                            this.data.ads_expire_duration = response.data.data.ads_expire_duration;
                            this.data.users_expire_duration = response.data.data.users_expire_duration;
                            this.data.image_max_size = response.data.data.image_max_size;
                            this.data.video_max_size = response.data.data.video_max_size;
                            this.data.audio_max_size = response.data.data.audio_max_size;
                        }
                    })
                    .catch((error) => {
                        this.loading = false;
                        var err = error.response.data.error;
                        displayToast(err,'#842029');
                    });
            },
            save(){

                var config = {
                    method: 'post',
                    url: `/api/v2/cycle/save`,
                    data: this.data
                };

                this.saveLoading = true;

                axios(config)
                    .then((response) => {
                        this.saveLoading = false;
                        console.log(response);
                        if (response.data.success == true) {
                            this.loadData();
                            swal({
                                text: "Cycle de vie modifié avec succès",
                                icon: "success",
                                button: true,
                            });
                        }
                    })
                    .catch((error) => {
                        this.saveLoading = false;
                        var err = error.response.data.error;
                        displayToast(err,'#842029');
                    });

            }
        }
    }).mount('#app')
</script>

@endsection
