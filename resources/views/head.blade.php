<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Tableau de bord Multilist - @yield('title')</title>

    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- pusher -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <!-- sweetAlert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>


    <!--load all Font Awesome styles -->
    <link href=" {{ asset('/assets/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
    <link href=" {{ asset('/assets/css/v2/styles.css') }}" rel="stylesheet" type="text/css">
    <!-- animate css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Favicons -->
     <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon.ico')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.ico')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico')}}">
    <link href="/storage/assets/favicon.png" rel="apple-touch-icon">

    <!-- axios packages -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>

        // check if token exists and pass it to axios package
        if (localStorage.getItem('token')) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
            axios.defaults.withCredentials = true;
        }

        // check if window.Laravel.csrfToken exists and pass it to axios package
        if (window.Laravel.csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;

            console.log(window.Laravel.csrfToken);
        }

        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    </script>

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link href="/assets/vendor/select2.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;600;800;900&display=swap"
        rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- multilist additional CSS File -->
    <link href="/css/style.css" rel="stylesheet">

    <!-- upload files -->
    <script src="{{ asset('js/uploadFiles.js') }}"></script>

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <!-- cdn vuejs3 link tag -->
    <script src="{{ asset('js/vue.global.js') }}"></script>

    <!-- sweetalert -->
    <script src="/assets/vendor/sweetalert.min.js"></script>
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <!-- export excel -->
    <script type="text/javascript" src="{{ asset('js/excellentexport.min.js') }}"></script>


    <!-- highlight js ( to highlight text, used in datatables ) -->
    <link rel="stylesheet"
            href="{{ asset('css/highlight/a11y-dark.min.css') }}" />
            <script src="{{ asset('js/highlight/highlight.min.js') }}"></script>
    <script src="{{ asset('js/highlight/languages/javascript.min.js') }}"></script>
    <script src="{{ asset('js/highlight/languages/json.min.js') }}"></script>

    <!-- formatter/beautify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.4/beautify.min.js"></script>



    <link rel="stylesheet" href="{{ asset('css/components/popup-components.vue.css') }}">
    <script src="{{ asset('js/components/popup-components.vue.js') }}"></script>


    @yield('custom_head')

</head>
