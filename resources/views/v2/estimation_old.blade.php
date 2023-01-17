@extends('v2.layouts.default')

@section('title', 'Déposer une annonce')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/estimation.styles.css') }}">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js"></script>
    <script>

        (function($) {

            let defaults = {
                data: [],
                obj: {},
                state: false

            };
            let methods = {
                init: async function() {
                    methods.getdata();
                    methods.manageEvent();
                    methods.sendRequest();

                },


                cleanHTML: function(rawHTML) {
                    rawHTML = rawHTML.replaceAll(">", "{s}");
                    rawHTML = rawHTML.replaceAll("<", "{i}");
                    rawHTML = rawHTML.replaceAll("&", "{a}");
                    rawHTML = rawHTML.replaceAll("#", "{h}");
                    rawHTML = rawHTML.replaceAll('"', "{q}");
                    rawHTML = rawHTML.replaceAll("%", "{p}");
                    rawHTML = rawHTML.replaceAll("/", "{c}");
                    rawHTML = rawHTML.replaceAll("\\", "{b}");

                    return rawHTML;
                },

                axiosExpression: function() {
                    return new Promise((resolve, reject) => {
                        axios.get('http://localhost:8000/api/testing').then(res => {
                            resolve(res);
                        }).catch(err => {
                            reject(err);
                        })
                    })
                },



                getUnique: (arr) => {
                    let filtred = arr.reduce((resArr, currentArr) => {
                        let other = resArr.some((ele) => currentArr.Region === ele.Region)
                        if (!other) resArr.push(currentArr)
                        return resArr
                    }, []);
                    return filtred;
                },

                getdata: async function(){

                    const data = await methods.axiosExpression();
                    const filtredData = methods.getUnique(data.data.data);

                    defaults.state = true;
                    $('.loader').attr('data-item-id',defaults.state)
                    if(defaults.state){

                            $('.loader').show();

                    }

                    $.each(filtredData, (index, item) => {

                        let templateRegion = `<option value="${item.Region}">${item.Region}</option>`;

                        $('#select1').append(templateRegion).select2();
                        $('#select2').select2();

                    })

                    defaults.state = false;
                    $('.loader').attr('data-item-id', defaults.state);
                    $('.loader').hide();
                    $('.multisteps-form').show();


                    let newData = [...data.data.data]

                    let selectedValue = ""


                    $('#select1').change(function(e) {

                        selectedValue = e.target.value

                        defaults.obj = {
                            region: selectedValue,
                            commune: '',
                            surface: '',
                            type: ''

                        }
                        defaults.data.push(defaults.obj);



                        const filtred = newData.filter(item => {
                            return item.Region === selectedValue;
                        })

                        $.each(filtred, (index, item) => {
                            let templateCommune =
                                `<option value="${item.Commune}">${item.Commune}</option>`;
                            $('#select2').append(templateCommune).select2();
                        })
                    })
                },


                manageEvent: function() {

                    $("#select2").change(function(e) {
                        let value = $(this).val();
                        defaults.data.map((item, i) => {
                            item.commune = value;
                        });

                    })

                    $('input:radio[name=type]').click(function(e) {
                        defaults.data.map((item, i) => {
                            item.type = e.target.value;
                        });
                    });


                    $('#surface').on('input', function() {

                        defaults.data.map((item, i) => {
                            item.surface = this.value;
                        });

                    });


                },

                sendRequest: async function() {


                    let mainData = defaults.data;

                    $('#estimate').on('click', function() {

                        defaults.state = true;
                        $('.loader').attr('data-item-id', defaults.state);

                        if (defaults.state) {

                         $('.multisteps-form').hide();
                    }
                    $('.loader').show();
                    $('.information').css('display','flex');
                    const stringed = JSON.stringify(defaults.data[0]);

                        $.ajax({
                            type: 'POST',
                            url: "http://35.222.100.187:8000/",
                            data: stringed,
                            headers: {
                                'Access-Control-Allow-Headers': '*',
                                'Access-Control-Allow-Origin': "*",
                                'Content-Type': 'application/json; ; charset=UTF-8'
                            },
                            dataType: 'json',
                            success: function(data) {

                                $('.loader').show();
                                $('.result').html(
                                    `<span class="merged">${data.prix}</span>`)
                            },
                            error: function(error) {

                                console.log(error);
                            },
                        });
                  })
             }
            };

            $.fn.Map = function(options) {
                var t = [];
                if (methods[options]) {
                    return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
                } else if (typeof options === "object" || !options) {
                    return methods.init.apply(this, arguments);
                }
            };

        })(jQuery);
    </script>
    <script>
        $(document).ready(function() {


            $().Map('init');

        })
    </script>

    <script>

    // // // increments function

    function incrementValue(e) {
        e.preventDefault();
        var fieldName = $(e.target).data('field');
        var parent = $(e.target).closest('div');
        var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

        if (!isNaN(currentVal)) {
            parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
        } else {
            parent.find('input[name=' + fieldName + ']').val(0);
        }
    }

    function decrementValue(e) {
        e.preventDefault();
        var fieldName = $(e.target).data('field');
        var parent = $(e.target).closest('div');
        var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

        if (!isNaN(currentVal) && currentVal > 0) {
            parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
        } else {
            parent.find('input[name=' + fieldName + ']').val(0);
        }
    }

        $('.input-group').on('click', '.button-plus', function(e) {
            incrementValue(e);
        });

        $('.input-group').on('click', '.button-minus', function(e) {
            decrementValue(e);
        });


            $().Map('init');

        })
    </script>

    <script>
            // validations

            $(document).ready(function(){


                const region = document.querySelector('select[name="region"]')
                const commune = document.querySelector('select[name="commune"]')
                const firstStepBtn = document.querySelector('#firestStep')
                const typeBtn = document.querySelector('button[name="typeBtn"]')
                const typeRadio = document.querySelector('radio[name="type"]')
                const appartInfo = document.querySelector('#appart')


                $('#select1').on('select2:select', function (e) {
                    var data = e.params.data.text;
                    if(data.value == ""){
                        commune.disabled = true
                    }else{
                      commune.disabled = false
                    }

                });

                $('#select2').on('select2:select', function (e) {
                    var data = e.params.data.text;
                    if(data.value == ""){
                      firstStepBtn.disabled = true
                    }else{
                      firstStepBtn.disabled = false
                      typeBtn.disabled = false
                    }

                });
                $('#secondStepBtn').on('click',function (){
                  var value = $("input[type=radio][name=type]:checked").val()
                  if(value == 'appart'){
                    $('#appart').css('display','block')
                  }else{
                    $('#appart').css('display','none')
                  }
                })

            })

    </script>



    {{-- <script src="/assets/vendor/jquery.min.js"></script> --}}
    <script src='/js/script.js'></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    {{-- select2 --}}
    <link href="/assets/vendor/select2.min.css" rel="stylesheet" />
    <script src="/assets/vendor/select2.min.js"></script>

    <style>
        @import url("https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css");

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid black;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .information {


            display: flex;
            width: 100%;
            min-height: 700px;
            justify-content: center;
            align-items: center;
            align-items: center;
            justify-content: center;
            padding: 0 20px;


        }

        .cardt {

            display: flex;
            justify-content: space-around;
            align-items: center;
            color: white;
            background: #F3BE2E;
            padding: 0 20px;
            margin: 0 10px;
            width: calc(33% - 20px);
            height: 250px;
            border-radius: 15px;



        }

        .flag {

            font-size: 32px;
            background: #00537D;
            color: whitesmoke;
            border-radius: 50%;
            text-align: center;
            transition: .3s linear;
            cursor: pointer;
            align-self: start;


        }

        .cardt:hover .flag {

            background: none;
            color: #f44336;
            transform: scale(1.6);

        }



      @media screen and (max-width: 868px){

        .information{

              display:grid;
              grid-template-columns:1fr 1fr;
              place-items:center;
              grid-gap:20px;
              margin:1rem 1rem 1rem 1rem;
        }

            .cardt {

                width: 350px;
            }

            .cardt:nth-child(5) {
                grid-column: span 2;
            }

        }


        @media screen and (max-width: 797px) {

            .information {

                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .cardt {

                width: 75%;
            }


        }
    </style>

@endsection

@section('content')
 <div class="loader" data-item-id="false"></div>
 <div class="result"></div>
 <div class="information" style="display: none !important">

        <div class="cardt">
            <i class='fi-rr-meh-rolling-eyes flag'></i>
            <h5>Region</h5>
            <p>Tanger Assilah</p>
        </div>

        <div class="cardt">
            <i class='fi-rr-meh-rolling-eyes flag'></i>
            <h5>Commune </h5>
            <p>Tanger</p>
        </div>

        <div class="cardt">
            <i class='fi-rr-meh-rolling-eyes flag'></i>
            <h5>Type du bien :</h5>
            <p>Villa</p>
        </div>

        <div class="cardt">
            <i class='fi-rr-meh-rolling-eyes flag'></i>
            <h5>Surface :</h5>
            <p>Tanger</p>
        </div>

        <div class="cardt">
            <i class='fi-rr-meh-rolling-eyes flag'></i>
            <h5>Additional information</h5>
            <p> bla bla </p>
        </div>
    </div>
    <div class="multisteps-form my-5 pt-5">
        <h1 class="text-center mb-3">Tout commence par une bonne estimation</h1>
        <!--progress bar-->
        <div class="row">
            <div class="col-12 col-lg-8 m-auto mb-5 py-2">
                <div class="multisteps-form__progress">
                    <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
                        <div class="multisteps-form__progress-icon" >
                            <i class="multilist-icons multilist-location me-1"></i>
                        </div>
                        Géolocalisation
                    </button>
                    <button disabled='true' name="typeBtn" class="multisteps-form__progress-btn" type="button" title="">
                        <i class="fa-regular fa-building me-1"></i>
                        Type
                    </button>
                    <button disabled="true" name="infoBtn" class="multisteps-form__progress-btn" type="button" title="">
                        <i class="fa-solid fa-house-circle-check me-1"></i>
                        Informations
                    </button>
                    <button disabled="true" name="caractBtn" class="multisteps-form__progress-btn" type="button" title="">
                        <i class="fa-solid fa-list-check me-1"></i>
                        Caractéristiques
                    </button>
                    <button disabled="true" name="resBtn" class="multisteps-form__progress-btn" type="button" title="">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        Résultat
                    </button>
                </div>
            </div>
        </div>
        <!--form panels-->

        <div class="row">
            <div class="col-12 col-lg-8 m-auto">
                <form class="multisteps-form__form">
                    <!--single form panel-->
                    <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
                        <h3 class="multisteps-form__title">Adresse du bien immobilier: </h3>
                        <div class="multisteps-form__content">
                            <div class="form-row mt-4">
                <div class="d-flex">
                    <div class="col-6 d-flex justify-items-center flex-column">
                        <div class="col-12 col-sm-6 mb-3 mt-3">
                        <h5>Selectioner la ville</h5>
                        <select id="select1" class="w-100 mb-3" name="region">
                            <option selected="selected" disabled>Selectioner la region</option>
                        </select>
                        </div>
                        <div class="col-12 col-sm-6 mt-3">
                            <h5>Selectioner le quartier</h5>
                            <select id="select2" class="w-100 mb-3" name="commune">
                                <option selected="selected" disabled>Selectioner la commune</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center col-6">
                        <img style="width: 200px" src="{{ asset('assets/img/home_localisation.png') }}" alt="">
                    </div>


                </div>



              </div>

              <div class="button-row d-flex mt-4">
                <button disabled="true" id="firestStep" class="btn btn-primary ml-auto js-btn-next stepper-btn" type="button" title="Suivant">
                    Suivant
                    <i class="fa-solid fa-angle-right"></i>
                </button>
              </div>
            </div>
          </div>
          <!--single form panel-->
          <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
            <h3 class="multisteps-form__title">De quel type de bien s'agit-il ? </h3>
            <div class="multisteps-form__content">

              <div class="container">

                <div class="row mt-5">
                    <h5>Selectioner le type de bien </h5>

                    <div class="col-md-4 col-lg-3 col-sm-4">
                      <label>
                        <input value="bureaux" type="radio" name="type" class="card-input-element" />

                          <div class="panel panel-default card-input p-2" style="background-image: url(http://localhost:8000/assets/img/bureau.png)">
                            <div class="panel-heading font-weight-bold">
                                <span class="bg-light rounded p-1">
                                    Bureau
                                </span>
                            </div>
                            <div class="panel-body">

                            </div>
                          </div>

                      </label>

                    </div>
                    <div class="col-md-4 col-lg-3 col-sm-4">

                      <label>
                        <input value="appart" type="radio" name="type" class="card-input-element" />

                          <div class="panel panel-default card-input p-2" style="background-image: url(http://localhost:8000/assets/img/appartement.png)">
                            <div class="panel-heading font-weight-bold">
                                <span class="rounded p-1 bg-light">
                                    Appartement
                                </span>
                            </div>
                            <div class="panel-body">

                            </div>
                          </div>
                      </label>

                    </div>
                    <div class="col-md-4 col-lg-3 col-sm-4">

                        <label>
                          <input value="maison" type="radio" name="type" class="card-input-element" />

                            <div class="panel panel-default card-input p-2" style="background-image: url(http://localhost:8000/assets/img/maison.png)">
                              <div class="panel-heading font-weight-bold">
                                <span class="rounded p-1 bg-light">Maison</span>
                              </div>
                              <div class="panel-body">
                               </div>
                            </div>
                        </label>

                    </div>
                    <div class="col-md-4 col-lg-3 col-sm-4">

                        <label>
                          <input type="radio" name="type" class="card-input-element" />

                            <div class="panel panel-default card-input p-2" style="background-image: url(http://localhost:8000/assets/img/villa.png)">
                              <div class="panel-heading font-weight-bold">
                                <span class="p-1 rounded bg-light">
                                    Villa
                                </span>
                              </div>
                              <div class="panel-body">

                </div>
              </div>

                    </div>
                </div>

                <div class="row mt-3">
                    <div class="button-row d-flex mt-4 col-12">
                      <button class="btn btn-primary js-btn-prev mx-1 stepper-btn" type="button" title="Précédent">
                        <i class="fa-solid fa-angle-left"></i>
                        Précédent
                    </button>
                      <button id="secondStepBtn" class="btn btn-primary js-btn-next mx-1 stepper-btn" type="button" title="Suivant">
                        Suivant
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <!--single form panel-->
          <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
            <h3 class="multisteps-form__title">
                Surface et chambres :
            </h3>
            <div class="multisteps-form__content">
              <div class="row">
                <div class="col-12 col-md-6 mt-4" id="appart" style="display:none">
                    <div class="card shadow-sm">
                      <div class="card-body">
                        <h5 class="card-title">
                            <i class="fa-solid fa-stairs me-1"></i>
                          Combien y a-t-il d'étages dans votre immeuble et à quel étage êtes vous ?
                        </h5>
                    <label for="" class="my-2">Surface bati</label>
                    <div class="input-group flex-nowrap">
                        <input type="number" class="form-control" placeholder="Surface bati" aria-label="surface" aria-describedby="addon-wrapping">
                        <span class="input-group-text" id="addon-wrapping">m²</span>
                    </div>
                    <label for="" class="my-2">Etage de l'appartement</label>
                    <div class="input-group flex-nowrap">
                        <input type="number" class="form-control" placeholder="Etage" aria-label="Etage" aria-describedby="addon-wrapping">
                        <span class="input-group-text" id="addon-wrapping"><i class="fa-solid fa-stairs"></i></span>
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 mt-4">
              <div class="card shadow-sm">
                  <div class="card-body">
                      <label for="" class="mb-2">Surface du bien</label>
                      <div class="input-group flex-nowrap">
                          <input type="number" class="form-control" placeholder="Surface" aria-label="surface" aria-describedby="addon-wrapping">
                          <span class="input-group-text" id="addon-wrapping">m²</span>
                      </div>
                  </div>
            </div>
          </div>
            <div class="col-12 col-md-6 mt-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                      <h5 class="card-title">
                        <i class="fa-solid fa-grip me-1"></i>
                        Nombre de chambres
                      </h5>
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-dark">Salons</p>
                            </div>
                            <div class="input-group increments justify-content-end align-items-center">
                                <input type="button" value="-" class="button-minus border rounded-circle  icon-shape icon-sm mx-1 " data-field="quantity">
                                <input type="number" disabled step="1" max="10" value="1" name="quantity" class="quantity-field border-0 text-center w-25">
                                <input type="button" value="+" class="button-plus border rounded-circle icon-shape icon-sm " data-field="quantity">
                            </div>
                            </div>
                        </div>

                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between">
                                                     <div>
                                                          <p class="text-dark">Chambres</p>
                                                     </div>
                                                     <div
                                                        class="input-group increments justify-content-end align-items-center">
                                                          <input type="button" value="-"
                                                            class="button-minus border rounded-circle  icon-shape icon-sm mx-1 "
                                                            data-field="quantity">
                                                          <input type="number" disabled step="1" max="10"
                                                            value="1" name="quantity"
                                                            class="quantity-field border-0 text-center w-25">
                                                          <input type="button" value="+"
                                                            class="button-plus border rounded-circle icon-shape icon-sm lh-0"
                                                            data-field="quantity">
                                                     </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <p class="text-dark">Salles de bain</p>
                                                        </div>
                                                        <div
                                                        class="input-group increments justify-content-end align-items-center">
                                                          <input type="button" value="-"
                                                            class="button-minus border rounded-circle  icon-shape icon-sm mx-1 lh-0"
                                                            data-field="quantity">
                                                          <input type="number" disabled step="1" max="10"
                                                            value="1" name="quantity"
                                                            class="quantity-field border-0 text-center w-25">
                                                          <input type="button" value="+"
                                                            class="button-plus border rounded-circle icon-shape icon-sm lh-0"
                                                            data-field="quantity">
                                                        </div>
                                                    </div>
                                                </div>


                                        </div>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="button-row d-flex mt-4 col-12">
                                    <button class="btn btn-primary js-btn-prev mx-1 stepper-btn" type="button"
                                        title="Précédent">Précédent</button>
                                    <button class="btn btn-primary stepper-btn mx-1 js-btn-next" type="button"
                                        title="Suivant">Suivant</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--single form panel-->
                    <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
                        <h3 class="multisteps-form__title">
                            Caractéristiques du bien
                        </h3>

                        {{-- <div class="d-flex flex-wrap">
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                    <i class="fa-solid fa-snowflake"></i>
                                    Climatisé
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                    <i class="fa-solid fa-tree"></i>
                                    Jardin
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">
                                    <i class="fa-solid fa-person-swimming"></i>
                                    Piscine
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                    <i class="fa-solid fa-square-parking"></i>
                                    Parking
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
                                    <label class="form-check-label" for="flexCheckDefault5">
                                    <i class="fa-solid fa-couch"></i>
                                    Meublé
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
                                    <label class="form-check-label" for="flexCheckDefault6">
                                    <i class="fa-solid fa-chair"></i>
                                    Terrasse
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch ">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
                                    <label class="form-check-label" for="flexCheckDefault7">
                                    <i class="fa-solid fa-person"></i>
                                    Syndic
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault8">
                                    <label class="form-check-label" for="flexCheckDefault8">
                                    <i class="fa-solid fa-door-open"></i>
                                    Cave
                                    </label>
                                </div>
                            </div>
                            <div class="m-2 p-3 text-light rounded-pill" style="background-color: rgb(181, 36, 131)">
                                <div class="form-check form-switch fs-5">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault9">
                                    <label class="form-check-label" for="flexCheckDefault9">
                                    <i class="fa-solid fa-elevator"></i>
                                    Ascenseur
                                    </label>
                                </div>
                            </div>

                        </div> --}}

                        <div class="d-flex">

                            <div class="col-md-6 mx-2 m-auto">
                                <div class="card mt-5" style="margin:50px 0">
                                    <!-- Default panel contents -->
                                    <div class="card-header">Selectioner les caractéristiques</div>

                                    <ul class="list-group list-group-flush rounded">

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-square-parking"></i>
                                            Parking
                                            <label class="switch">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-couch"></i>
                                            Meublé
                                            <label class="switch ">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-chair"></i>
                                            Terrasse
                                            <label class="switch ">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>

                                    </ul>

                                </div>
                            </div>

        <div class="col-md-6 mx-2 m-auto">
            <div class="card mt-5" style="margin:50px 0">
                <!-- Default panel contents -->
            <div class="card-header">Selectioner les caractéristiques</div>
                <ul class="list-group list-group-flush">

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-person"></i>
                                            Syndic
                                            <label class="switch ">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-door-open"></i>
                                            Cave
                                            <label class="switch ">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>

                                        <li class="list-group-item">
                                            <i class="fa-solid fa-elevator"></i>
                                            Ascenseur
                                            <label class="switch ">
                                                <input type="checkbox" class="danger">
                                                <span class="slider"></span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
=======
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="button-row d-flex mt-4 col-12">
                  <button class="btn btn-primary js-btn-prev mx-1 stepper-btn" type="button" title="Précédent">
                    <i class="fa-solid fa-angle-left"></i>
                    Précédent
                </button>
                  <button class="btn btn-primary stepper-btn mx-1 js-btn-next" type="button" title="Suivant">
                    Suivant
                    <i class="fa-solid fa-angle-right"></i>
                </button>
                </div>
              </div>
            </div>
          </div>
        <!--single form panel-->
        <div class="multisteps-form__panel shadow mx-auto p-4 rounded bg-white" data-animation="scaleIn">

            <div class="multisteps-form__content m-auto" style="max-width: 600px">

                <div class="estimation-result">
                    <h2 class="text-center mb-5">
                        <i class="fa-solid fa-circle-check" style="color: rgb(84, 194, 27)"></i>
                        Votre estimation est prête
                    </h2>
                    <div class="d-flex">
                        <div class="mb-3 mx-2 w-100">
                            <label for="exampleFormControlInput1" class="form-label">Votre nom</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Enter votre nom">
                          </div>
                          <div class="mb-3 mx-2 w-100">
                            <label for="exampleFormControlInput1" class="form-label">Votre prénom</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Entrer votre prénom">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Votre adresse e-mail</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                    <p>Votre numéro de téléphone</p>

                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Votre numéro de téléphone " aria-label="phone" aria-describedby="addon-wrapping">
                    </div>

              </div>
            <!--single form panel-->
        <div class="multisteps-form__panel shadow p-4 rounded bg-white" data-animation="scaleIn">
            <h3 class="multisteps-form__title">Additional Comments</h3>

                <div class="form-row mt-4">
                <textarea class="multisteps-form__textarea form-control" placeholder="Additional Comments and Requirements"></textarea>
                </div>
                <div class="button-row d-flex mt-4">
                <button class="btn btn-primary js-btn-prev stepper-btn" type="button" title="Prev">Prev</button>
                <button class="btn btn-success mx-1" class="estimate" id="estimate" type="button" title="Envoyer">Consulter le Résultat</button>
                </div>
            </div>
            </div>
        </form>
       </div>
    </div>
  </div>

@endsection

@section('custom_foot')

<style>
    .multisteps-form__progress {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
}
.multisteps-form__progress-btn {
  transition-property: all;
  transition-duration: 0.15s;
  transition-timing-function: linear;
  transition-delay: 0s;
  position: relative;
  padding-top: 20px;
  color: rgba(108, 117, 125, 0.7);
  text-indent: -9999px;
  border: none;
  background-color: transparent;
  outline: none !important;
  cursor: pointer;
}
@media (min-width: 500px) {
  .multisteps-form__progress-btn {
    text-indent: 0;
  }
}
.multisteps-form__progress-btn:before {
  position: absolute;
  top: 0;
  left: 50%;
  display: block;
  width: 13px;
  height: 13px;
  content: '';
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
  transition: all 0.15s linear 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
  border: 2px solid currentColor;
  border-radius: 50%;
  background-color: #fff;
  box-sizing: border-box;
  z-index: 3;
}
.multisteps-form__progress-btn:after {
  position: absolute;
  top: 5px;
  left: calc(-50% - 13px / 2);
  transition-property: all;
  transition-duration: 0.15s;
  transition-timing-function: linear;
  transition-delay: 0s;
  display: block;
  width: 100%;
  height: 2px;
  content: '';
  background-color: currentColor;
  z-index: 1;
}
.multisteps-form__progress-btn:first-child:after {
  display: none;
}
.multisteps-form__progress-btn.js-active {
  color: rgb(246, 77, 75);
}
.multisteps-form__progress-btn.js-active:before {
  -webkit-transform: translateX(-50%) scale(1.2);
          transform: translateX(-50%) scale(1.2);
  background-color: currentColor;
}
.multisteps-form__form {
  position: relative;
}
.multisteps-form__panel {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 0;
  opacity: 0;
  visibility: hidden;
}
.multisteps-form__panel.js-active {
  height: auto;
  opacity: 1;
  visibility: visible;
}

.multisteps-form__panel[data-animation="scaleIn"] {
  -webkit-transform: scale(0.9);
          transform: scale(0.9);
}
.multisteps-form__panel[data-animation="scaleIn"].js-active {
  transition-property: all;
  transition-duration: 0.2s;
  transition-timing-function: linear;
  transition-delay: 0s;
  -webkit-transform: scale(1);
          transform: scale(1);
}
.select2-results__options{
    display: flex;
    flex-direction: column;
}
.stepper-btn{
    background-color: rgb(84, 194, 27) !important;
    border: none !important;
    color: #fff;
    width: 130px
}

icon-shape {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    vertical-align: middle;
}

.icon-sm {
    width: 2rem;
    height: 2rem;

}

.increments{
    width: 200px !important;
}


/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
  float:right;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 3rem;

}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  border-radius: 3rem;
  transition: .4s;
}

input.default:checked + .slider {
  background-color: #444;
}
input.primary:checked + .slider {
  background-color: #2196F3;
}
input.success:checked + .slider {
  background-color: #8bc34a;
}
input.info:checked + .slider {
  background-color: #3de0f5;
}
input.warning:checked + .slider {
  background-color: #FFC107;
}
input.danger:checked + .slider {
  background-color: #f44336;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <script src="{{ asset('js/estimation-script.js') }}" defer></script>

    {{-- increments script --}}



    <script type="text/javascript" defer>
        const headers = {
            'Access-Control-Allow-Origin': 'http://20.16.204.168:5000/',
            'Content-Type': "application/json",
            'Access-Control-Allow-Headers': 'Content-Type, Accept',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS'

        }



        //     fetch('http://20.16.204.168:5000/auto_data',{mode:"no-cors",headers})
        //   .then(response => console.log(response))
        //   .catch(error => console.log(error))

        //     fetch('https://jsonplaceholder.typicode.com/todos/1', {

        //         method : "get",
        //         mode: 'no-cors',
        // }).then(response => {


.card-input-element:checked + .card-input {
     box-shadow: 0 0 1px 1px #2ecc71;
     border: none;
     background-color: rgb(0, 83, 125);
     color:rgb(246, 77, 75);
 }

        //     }).catch(error => {

.panel-default{
    width: 150px;
    height: 150px;
    border: 3px solid rgb(246, 77, 75);
    border-radius: 11px;
    text-align: center;
    align-items: center;
    justify-content: center;
    display: flex;
    background-origin: content-box;
    background-size: cover;
    background-size: 90% 80%;
    background-position: center;
    background-repeat: no-repeat;
}
.panel-heading{
    font-weight: 900;
    font-size: 1.3rem;
    border-radius:5px;
    width:110%;
    font-weight: 900;
    background-color: #cecece17;
    border-radius: 5px;
    width: 100%;
    color: #dc3545;
    height: 135%;
    align-items: flex-end;
    display: flex;
    text-overflow: clip;
    text-shadow: 2px 1px 1px #f9fafb;
    justify-content: center;
}
.panel-heading span{
    border: 1px solid #dc3545;
    background-color: #fff;
    font-size: 1rem;
    width: 120px
}




</script>
