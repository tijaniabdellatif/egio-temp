@extends('v2.layouts.dashboard')

@section('title', 'Ajouter une annonce')

@section('custom_head')
    <div id="add-ad">

    </div>
@endsection

@section('content')

@endsection

@section('custom_foot')
    <script type="text/javascript">
        Vue.createApp({
            data() {
                return {
                    message: "test"
                }
            },
            computed: {},
            watch: {},
            mounted() {},
            methods: {},
        }, ).mount('#add-ad')
    </script>

@endsection
