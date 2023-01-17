@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Ajouter une contrat</h1>
    </div>
    <section class="section" id="app">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">utilisateur</h5>
                        <div class="form-group">
                            <label for="my-select"><small>Id</small></label>
                            <select id="my-select" class="form-select" name="">
                                <option>Text</option>
                            </select>
                        </div>
                        <div class="form-group mt-2" >
                            <label for=""><small>username</small></label>
                            <input type="text" disabled class="form-control">
                        </div>
                        <div class="form-group mt-2" >
                            <label for=""><small>email</small></label>
                            <input type="text" disabled class="form-control">
                        </div>
                        <div class="form-group mt-2" >
                            <label for=""><small>phone</small></label>
                            <input type="text" disabled class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('custom_foot')

    <script type="text/javascript">
        Vue.createApp({
            data() {
                return {}
            },
            components: {},
            watch: {},
            mounted() {},
            methods: {

            },
        }).mount('#app')
    </script>

@endsection
