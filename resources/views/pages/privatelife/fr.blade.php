<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/v2/bootstrap.min.css') }}">

    <!--load all Font Awesome styles -->
    <link href=" {{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('/assets/css/v2/styles.css') }}" rel="stylesheet" type="text/css">

    <style>


        .footer{
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 1fr 5px;
            margin:0 25px 0 25px;


         }
        footer {

            overflow:hidden;
            width:100%;
            grid-row:2/2;
            grid-column: 1/1;
            border-radius: 30px;
        }

        .custom{

            border-bottom-right-radius:30px;
            border-bottom-left-radius:30px;

        }

        .menu {

            display:flex;
            justify-content:space-around;
            align-items:center;
            width:15%;
        }


    </style>
    <title>Qui somme nous</title>
</head>
<body>

    <div id="header" class="header">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('/images/logo.png') }}" alt="">
            </a>
        </div>

        <a class="btn-new-announcement" href="/deposer-annonce" style="background: #f00;">
            <i class="fa-regular fa-plus"></i>
            <span>Rejoignez nous</span>
        </a>



        <div class="menu">

            <a href="#" class="right-menu-item">
                <span>Vie privée</span>
            </a>
            <a href="#" class="right-menu-item">
                <span>infos légales</span>
            </a>

        </div>

    </div>

    <main>

    </main>

    <div class="footer">
        <footer>
            <div class="row" style="background-color: #323232;">
                <div class="d-flex mb-2">
                    <div style="width:100%;height: 5px;background-color: rgb(181, 36, 131);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(246, 77, 75);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(243, 190, 46);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(84, 194, 27);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(0, 83, 125);"></div>

                </div>
                <div class="col-12 col-md-4 d-flex">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo" class="img-fluid" style="margin: auto;width: 250px;height: 100px;">
                </div>
                <div class="col-12 col-md-4 px-3" style="border-left: 2px solid rgb(246, 77, 75) ;">
                    <p class="text-light fs-5 fw-bold">À propos </p>
                    <div class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-light px-2"  href="/qui-sommes-nous">Qui sommes-nous ?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light px-2" href="#">Vie privée</a>
                        </li>
                    </div>
                </div>
                <div class="col-12 col-md-4 px-3">
                    <p class="fs-5 fw-bold text-light">
                        Retrouvez-nous sur
                    </p>
                    <div class="px-3 mb-3">
                        <a href="#" class="mx-1 fs-4 text-light"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="mx-1 fs-4 text-light"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#" class="mx-1 fs-4 text-light"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="custom bg-dark p-2 d-flex justify-content-center border-0 border-top border-light">
                <a href="#" class="m-1 nav-link text-light" style="border-left:1px solid #fff ;"> <span class="ms-1">Infos légales et GCU</span></a>
                <a href="#" class="m-1 nav-link text-light" style="border-left:1px solid #fff ;"> <span class="ms-1">Règles de diffusion</span> </a>
                <a href="#" class="m-1 nav-link text-light" style="border-left:1px solid #fff ;"> <span class="ms-1">Cookies</span> </a>
            </div>

            <div class="text-center p-4">
                © 2022 Copyright:
                <a class="text-reset fw-bold" href="#">multilist</a>
            </div>
        </footer>
    </div>


    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

</body>
</html>


