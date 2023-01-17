<?php

$seo_of_this_page = [];

if ($multilistType == '') {
    $multilistType = 'multilist';
}

// convert $seo to object
$seo = json_decode($seo);

if ($seo) {
    // loop through seo
    foreach ($seo as $key => $value) {
        if ($value->name == $multilistType) {
            $seo_of_this_page = $value;
        }
    }
}

?>
<!doctype html>
<html lang="en">

<head>

    <title>@yield('title') | multilist</title>

    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>


    <script>

        window.addEventListener('DOMContentLoaded',(e) => {

              document.querySelector('body').setAttribute('style','color:black');
        })
    </script>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- Swiper  --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <!-- animate css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- axios -->
    <script src="{{ asset('assets/js/axios.min.js') }}"></script>
    <!-- vuejs -->
    <script src="{{ asset('assets/js/vue.global.js') }}"></script>

    <script>
        let multilistType = '{{ $multilistType }}';

        let multilistTypeObj = {};

        // Multilist - 0
        // Homelist - 1
        // Officelist - 2
        // primelist - 3
        // landlist - 4
        // booklist - 5

        multilistTypeObj = {};

        let multilistTypeObjs = [
            {
                id: null,
                name: 'multilist',
                title: 'Multilist',
                description: 'Multilist',
                keywords: 'Multilist',
                image: '/images/logo.png',
                color: '#00a8ff',
            }, {
                id: 1,
                name: 'homelist',
                title: 'Homelist',
                description: 'Homelist',
                keywords: 'Homelist',
                image: '/images/logo.png',
                color: '#00a8ff',
            }, {
                id: 2,
                name: 'officelist',
                title: 'Officelist',
                description: 'Officelist',
                keywords: 'Officelist',
                image: '/images/logo.png'
            }, {
                id: 3,
                name: 'primelist',
                title: 'Primelist',
                description: 'Primelist',
                keywords: 'Primelist',
                image: '/images/primelist-logo.png'
            }, {
                id: 4,
                name: 'landlist',
                title: 'Landlist',
                description: 'Landlist',
                keywords: 'Landlist',
                image: '/images/logo.png'
            }, {
                id: 5,
                name: 'booklist',
                title: 'Booklist',
                description: 'Booklist',
                keywords: 'Booklist',
                image: '/images/logo.png'
            }
        ];

        let colors = [];

        <?php
            foreach ($seo as $key => $value) {
                echo "colors.push({
                    name: '{$value->name}',
                    color: '{$value->main_color}'
                });";
            }
        ?>

        // set the color attribute of the multilist type
        // loop through colors
        for (let i = 0; i < colors.length; i++) {
            let color = colors[i];

            // loop through multilist type objs
            for (let j = 0; j < multilistTypeObjs.length; j++) {
                let multilistTypeObj = multilistTypeObjs[j];
                if (color.name == multilistTypeObj.name) {
                    multilistTypeObj.color = color.color;
                }
            }
        }


        // get multilistTypeObj by name
        multilistTypeObj = multilistTypeObjs.find(obj => obj.name == multilistType);
    </script>

    <!-- Bootstrap CSS v5.0.2 -->
    <link rel="stylesheet" href="{{ asset('assets/css/v2/bootstrap.min.css') }}">

    <!--load all Font Awesome styles -->
    <link href=" {{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('/assets/css/v2/styles.css') }}" rel="stylesheet" type="text/css">

    <style>


        footer{

            overflow-x: hidden;
        }

    </style>

    <script type="text/javascript" >
        // check if token exists and pass it to axios package
        if (localStorage.getItem('token')) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('token');
        }

        // check if window.Laravel.csrfToken exists and pass it to axios package
        if (window.Laravel.csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
        }
    </script>
    <!-- script -->
    <script src="{{ asset('assets/js/v2/scripts.js') }}"></script>

    <style>
        :root {
            --primary-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->main_color) {
                echo $seo_of_this_page->main_color;
            } else {
                echo 'red';
            }

            ?>;

            --secondary-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->secondary_color) {
                echo $seo_of_this_page->secondary_color;
            } else {
                echo '#fff';
            }

            ?>;

            --third-color: <?php

            if ($seo_of_this_page && $seo_of_this_page->third_color) {
                echo $seo_of_this_page->third_color;
            } else {
                echo '#fff';
            }

            ?>;

            <?php

            foreach ($seo as $key => $value) {
                echo '--' . $value->name . '-main-color: ' . $value->main_color . ';';
                echo '--' . $value->name . '-secondary-color: ' . $value->secondary_color . ';';
                echo '--' . $value->name . '-third-color: ' . $value->third_color . ';';
            }

            ?>
        }
    </style>

    <script>
        let = {
            createApp
        } = Vue;
    </script>

    @yield('custom_head')

</head>

<body>

    <div id="header" class="header">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img :src="multilistTypeObj?.image?multilistTypeObj.image:'/images/logo.png'" alt="">
            </a>
        </div>


        <div class="menu" id="menu">

            <h4 class="title-anfa" style="color:#333">Anfa Realties Leads</h4>
        </div>

    </div>

    <div class="underline">
        <div class="d-flex mb-2">
            <div style="width:100%;height: 5px;background-color: rgb(181, 36, 131);"></div>
            <div style="width:100%;height: 5px;background-color: rgb(246, 77, 75);"></div>
            <div style="width:100%;height: 5px;background-color: rgb(243, 190, 46);"></div>
            <div style="width:100%;height: 5px;background-color: rgb(84, 194, 27);"></div>
            <div style="width:100%;height: 5px;background-color: rgb(0, 83, 125);"></div>

    </div>
    </div>



    <section class="container-anfa">

        <h4 class="anfa-title">Choose your CSV file to transform</h4>

        <form method="POST" enctype="multipart/form-data" action='{{ route('leads.transform') }}'>
            @csrf
            <div class="form-control w-auto anfa-items">
                <input type="file" id="file" hidden="hidden" name="file" />
                <button type="button" id="custom-btn">Choose a File</button>
                <span id="custom-text">No file chosen, Yet</span>
             </div>

             <div class="transform">
                <button type="submit" id="custom-button" >Transform to Json</button>
             </div>
        </form>

    </section>


    <style>

        .container-anfa{

             display: flex;
             flex-direction:column;
             justify-content: center;
             align-items: center;
        }

        #custom-btn, #custom-button{

              padding:10px;
              color:white;
              background-color: #54C21B;
              border:none;
              border-radius:5px;
              cursor:pointer;
              margin:14px;
        }

        .anfa-title{

              padding:15px;
        }

        .anfa-items{

             margin:14px 0 15px 0;
             display: flex;
             align-items:center;
             justify-content:space-evenly;
        }

        #custom-text{
              color:rgba(0,0,0,0.7);
        }
    </style>


    <script>

        const FileBtn =document.querySelector('#file');
        const customFile =document.querySelector('#custom-btn');
        const customText =document.querySelector('#custom-text');

        customFile.addEventListener('click',(e) => {

            FileBtn.click();
        });

        FileBtn.addEventListener('change',(e) => {

              if(FileBtn.value){

                   customText.innerHTML = FileBtn.value.match(/[\/\\]([\w\d\s\.\-\(\)]+)$/)[1];
              }
              else {

                  customText.innerHTML = 'No file chosen, Yet';
              }

        });


    </script>

    <!-- Bootstrap JavaScript Libraries -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script> --}}
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script> --}}
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <script>
        let = {
            createApp
        } = Vue;
    </script>

    <script>

        const currUserId = '{{Auth()->user()?Auth()->user()->id:""}}';
        let headerApp = createApp({
            data() {
                return {
                    rightMenuIsActive: false,
                    dropDownActive: false,
                    auth: null,
                    multilistTypeObj:multilistTypeObj,
                }
            },
            mounted() {

                if (currUserId)
                    axios.get('/api/v2/getHeaderUser/' + currUserId)
                        .then((response) => {
                            this.auth = response.data.data;
                        })
                        .catch((error) => {
                            console.log(error);
                        });

                document.addEventListener('click',(e)=> {
                    this.dropDownActive = false;
                });

            },
            updated(){
                //document.querySelector('#menu').classList.remove('d-none');
            },
            methods: {
                activeRightMenu() {
                    this.rightMenuIsActive = !this.rightMenuIsActive;
                },
                activeDropDown(e) {
                    e.stopPropagation();
                    this.dropDownActive = !this.dropDownActive;
                },
                stopPropagation(e){
                    e.stopPropagation();
                },
                signOut(){
                    axios.post('/api/v2/logout')
                        .then(function(response) {

                            localStorage.removeItem('auth');
                            localStorage.removeItem('token');


                            document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";


                            window.location.href = '/';
                            console.log(response);
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                    // clear jwt token from storage and cookies
                    /*localStorage.removeItem('auth');
                    localStorage.removeItem('token');
                    document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";*/
                }
            }
        }).mount('#header');

        hidephone();
    </script>

    @yield('custom_foot')


        <footer class="text-center text-lg-start bg-white text-muted">
            <div class="row">
                <div class="d-flex mb-2">
                    <div style="width:100%;height: 5px;background-color: rgb(181, 36, 131);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(246, 77, 75);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(243, 190, 46);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(84, 194, 27);"></div>
                    <div style="width:100%;height: 5px;background-color: rgb(0, 83, 125);"></div>

        </div>
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>Rejoignez nous sur les réseaux sociaux : </span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div>
                <a href="https://www.facebook.com/Multilist.group/" class="me-4 text-reset">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCNjQB9JoAbAmQJDXrDIjn4w" class="me-4 text-reset">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://www.linkedin.com/company/multilist-immo/about/" class="me-4 text-reset">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-instagram"></i>
                </a>

            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="border-bottom">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <div class="col-12 col-md-12 d-flex">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="margin: auto">
                        </div>
                        <p>
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quisquam eligendi cum ipsam,
                            excepturi commodi
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Contact
                        </h6>
                        <p><i class="fas fa-home me-3"></i> Casablanca, 1111 , morocco</p>
                        <p>
                            <i class="fas fa-envelope me-3"></i>
                            info@example.com
                        </p>
                        <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                        <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->


                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <p>
                            <a href="/aboutus" class="text-reset">Qui sommes-nous ?</a>
                        </p>
                        <p>
                            <a href="/privacy" class="text-reset">Vie privée</a>
                        </p>
                        <p>
                            <a href="/info" class="text-reset">Infos légales et GCU</a>
                        </p>
                        <p>
                            <a href="/roles" class="text-reset">Règles de diffusion</a>
                        </p>
                        <p>
                            <a href="/cookies" class="text-reset">Cookies</a>
                        </p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>

        <div class="bg-dark p-2 d-flex justify-content-center border-0 border-top border-light" id="copyright">
            © 2022 Copyright:
            <a class="text-reset fw-bold" href="#">multilist</a>
        </div>

        <script>
            const paragraph = `
            <p style="color: #fff;margin: 0;font-size: 12px;">
              Copyright &copy; ${new Date().getFullYear()} Multilist
            </p>
          `;

            document.getElementById('copyright').innerHTML = paragraph;
        </script>


</footer>




</body>

</html>
