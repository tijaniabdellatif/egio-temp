@extends('v2.layouts.dashboard')

@section('title', 'Liens')

@section('custom_head')

@endsection

@section('content')
<div class="pagetitle">
    <h1>Les liens :</h1>
</div>
<section id="app" class="section">
    <div class="row">
        <div class="col-lg-6">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ajouter ou modifier un lien :</h5>

                    <!-- General Form Elements -->
                    <div>

                        <div v-if="saveLoading" style="text-align:center;">
                            <div class="spinner-border spinner-border-sm ms-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Facebook :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.facebook">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Instargram :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.instagram">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Twitter :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.twitter">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">LinkedIn :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.linkedin">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">Youtube :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.youtube">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-6 col-form-label">TikTok :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" v-model="data.tiktok">
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
                    facebook: null,
                    instagram: null,
                    twitter: null,
                    linkedin: null,
                    youtube: null,
                    tiktok: null,
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

                this.data.facebook = null;
                this.data.instagram = null;
                this.data.twitter = null;
                this.data.linkedin = null;
                this.data.youtube = null;
                this.data.tiktok = null;

                axios(config)
                    .then((response) => {
                        this.loading = false;
                        if (response.data.success == true) {
                            this.data.facebook = response.data.data.facebook;
                            this.data.instagram = response.data.data.instagram;
                            this.data.twitter = response.data.data.twitter;
                            this.data.linkedin = response.data.data.linkedin;
                            this.data.youtube = response.data.data.youtube;
                            this.data.tiktok = response.data.data.tiktok;
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
                    url: `/api/v2/links/save`,
                    data: this.data
                };

                this.saveLoading = true;

                axios(config)
                    .then((response) => {
                        this.saveLoading = false;
                        console.log(response);
                        if (response.data.success == true) {
                            this.loadData();
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
