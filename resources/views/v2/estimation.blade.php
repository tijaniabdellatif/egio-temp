@extends('v2.layouts.default')

@section('title', 'Déposer une annonce')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/estimation.styles.css') }}">


    <script>

        (function($) {
            let defaults = {
                data: [],
                obj:{},
                state:false

            };
            let methods = {
                init: async function() {
                    methods.manageTabs();
                    methods.getdata();
                    methods.manageEvent();
                    methods.sendRequest();

                },

        cleanHTML: function (rawHTML) {
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

                    $('.multisteps-form').hide();

                    const data = await methods.axiosExpression();
                    const filtredData = methods.getUnique(data.data.data);

                    defaults.state = true;
                    $('.loader').attr('data-item-id',defaults.state)
                    if(defaults.state){
                            $('.loader').show();

                    }

                    $.each(filtredData,(index,item) => {

                        let templateRegion = `<option value="${item.Region}">${item.Region}</option>`;

                        $('#select1').append(templateRegion).select2();
                        $('#select2').select2();

                    })

                    defaults.state = false;
                    $('.loader').attr('data-item-id',defaults.state);
                    $('.loader').hide();
                    $('.multisteps-form').show();


                let newData = [...data.data.data]

                let selectedValue = ""


                $('#select1').change(function(e){

                    selectedValue = e.target.value

                    defaults.obj = {
                        region:selectedValue,
                        commune:'',
                        surface:'',
                        type:''

                    }
                     defaults.data.push(defaults.obj);



                    const filtred = newData.filter(item =>  {
                       return item.Region === selectedValue;
                    })

                    $.each(filtred,(index,item) => {
                        let templateCommune = `<option value="${item.Commune}">${item.Commune}</option>`;
                        $('#select2').append(templateCommune).select2();
                    })
                })
             },


             manageEvent:function(){

                        $("#select2").change(function(e){
                            let value = $(this).val();
                             defaults.data.map((item,i) => {
                                  item.commune = value;
                            });

                        })

                        $('input:radio[name=type]').click(function(e){
                            defaults.data.map((item,i) => {
                                item.type = e.target.value;
                          });
                        });


                        $('#surface').on('input',function(){

                            defaults.data.map((item,i) => {
                                item.surface = this.value;
                          });

                        });


             },



             sendRequest:async function(){


                  let mainData = defaults.data;

                  $('#estimate').on('click',function(){

                    defaults.state = true;
                    $('.loader').attr('data-item-id',defaults.state);

                    if(defaults.state){

                         $('.multisteps-form').hide();
                    }
                    $('.loader').show();
                    $('.information').css('display','flex');
                    const stringed = JSON.stringify(defaults.data[0]);

                    console.log('stringed',stringed);

                    $.ajax({
                        type: 'POST',
                        url: "http://34.170.22.84:8000/",
                        data: stringed,
                        headers:{'Access-Control-Allow-Headers':'*','Access-Control-Allow-Origin':"*",'Content-Type':'application/json; ; charset=UTF-8'},
                        dataType: 'json',
                        success: function(data){


                              $('.result').html(`<span class="merged">${data.prix}</span>`)
                        },
                        error : function(error){

                            swal("error", "Erreur serveur", "error");

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

                $('#select1').on('select2:select', function (e) {
                    var data = e.params.data.text;
                    if(data.value == ""){

                    firstStepBtn.disabled = true
                    }else{
                        firstStepBtn.disabled = false
                    }

                });

            })




    </script>



    {{-- <script src="/assets/vendor/jquery.min.js"></script> --}}
    <script src='/js/script.js'></script>

    <script src="/assets/vendor/sweetalert.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/components/ml-select-components.vue.css') }}">
    <script src="{{ asset('js/components/ml-select-components.vue.js') }}"></script>

    {{-- select2 --}}

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>


     .holder {

            display:grid;
            grid-template-columns: 1fr 1fr;
            margin:2rem 0 2rem 0.75rem;

     }

     .title_holder{
        width:100%;
        height:150px;
        background: url('{{ asset('images/pic.jpg') }}');
        display:flex;
        justify-content:center;
        align-items:center;
        background-size:cover;
        box-shadow:inset 0 0 0 2000px rgba(0, 0, 0, 0.4);
        border-radius:15px;

     }
     .multisteps-form__title{

        text-align:center;
        font-size: 25px;
        font-weight: 600;
        color: #fff;
        padding: 5px 20px;
        border-radius: 10px;
        text-shadow: 0 0 5px #bcbbbb;
     }

     .grasp{

        width:100%;

     }

     .grasp-content{

            background: rgb(243, 190, 46,0.4);
            padding:2rem;
            border-radius: 15px;

     }


     /*
         bg-screen layout
     */


      .bg-screen{

        width:100%;
        height: auto;
        display:grid;
        grid-template-columns: 1fr;
        grid-template-rows: 100px 1fr 1fr;
        grid-gap:15px;
      }



      .banner {

           width:98%;
           display: flex;
           justify-content: center;
           align-items: center;
           background: #F64D4B;
           justify-self: center;
           border-radius: 15px;


      }

      .banner-items{

          display: flex;
          justify-content: space-evenly;
          align-content: center;
          list-style:none;
          width:70%;
      }

      .banner-items li{

             font-size:18px;
             font-weight:700;
             position: relative;
             cursor: pointer;
             color:white;
             opacity:1;
             transition:opacity 0.3s ease-in-out;
             display:block;
             border:0.5px solid white;
             padding:15px;
             z-index: 1;

      }

      .banner-items li:hover{

        opacity:0.9;
      }


      .banner-items li::before{

            content:"";
            position:absolute;
            width:0;
            height:7px;
            border-radius: 15px;
            top:57px;
            right:0;
            background: white;
            transition: all 250ms ease-in-out;


      }

      .banner-items li::after{

        content:"";
        position:absolute;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background: rgba(0,0,0,0.4);
        z-index:-1;
        transform: scaleX(0);
        transform-origin:left;
        transition:transform 200ms ease-in;
  }

  .actived {

    background: rgba(0,0,0,0.4) !important;
}
  .banner-items li:hover::after{

     transform:scaleX(1);
}



      .banner-items li:hover::before{


        width:100%;



  }

      .above{
          width:100%;

          background-color: red;
      }

      .below{

        width:100%;
        background-color:blue;
      }

</style>

@endsection

@section('content')

 {{--  <div class="bg-screen">

     <div class="banner">
        <ul class="banner-items">
            <li class="banner-tab actived">
                <i class="fa-solid fa-sign-hanging"></i>
                <span class="title-item">Estimer votre bien</span>
            </li>
            <li class="banner-tab">
                <i class="fa-solid fa-circle-info"></i>
                <span class="title-item">Informations Supplémentaires</span>
            </li>
        </ul>
     </div>
     <div class="above">Bloc 1</div>
     <div class="below">Bloc 2</div>

 </div>  --}}

 <div class="loader" data-item-id="false"></div>
 <div class="result"></div>

<div class="multisteps-form my-5 pt-5">
    <h1 class="text-center mb-3">Tout commence par une bonne estimation</h1>

    <!--progress bar-->
    <div class="row">
      <div class="col-12 col-lg-8 m-auto mb-5 py-2">
        <div class="multisteps-form__progress">
          <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
            <i class="multilist-icons multilist-location me-1"></i>
            Géolocalisation
        </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-regular fa-building me-1"></i>
            Type
          </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-solid fa-house-circle-check me-1"></i>
            Informations
          </button>
        <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-solid fa-list-check me-1"></i>
            Caractéristiques
        </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
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
            <div class="title_holder">
                <h3 class="multisteps-form__title">{{ ucfirst('adresse '.ucfirst('du bien').' '.ucfirst('immobilier')) }}</h3>
            </div>

            <div class="multisteps-form__content">

            <div class="holder">
                <div class="form-row mt-4">
                    <div class="col-12 col-sm-6 mb-3">
                        <h5>Selectioner la ville</h5>
                        <select value="" id="select1" class="w-100 mb-3" name="region">
                            <option selected="selected" disabled>Selectioner la region</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 mb-3">
                        <h5>Selectioner le quartier</h5>
                        <select id="select2" class="w-100 mb-3" name="commune">
                            <option value="" selected="selected" disabled>Selectioner la commune</option>
                        </select>
                    </div>
                  </div>

                  <div class="form-row mt-4 grasp">
                     <div class="grasp-content">
                                <p>
                                    Il faut garder à l’esprit que votre logement a
                                    une valeur de marché, qui ne correspond pas toujours au prix auquel
                                    vous l’avez acheté. En effet, cette valeur dépend de
                                     l’évolution des prix de l’immobilier, de l’offre et
                                      de la demande au moment de la mise en vente.
                                </p>
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

          <!--single form panel-->

          <!--single form panel-->

        <!--single form panel-->

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


/* selected cards radio */

{{-- increments script --}}



<script type="text/javascript" defer>


const headers = {
        'Access-Control-Allow-Origin' : 'http://20.16.204.168:5000/',
        'Content-Type':"application/json",
        'Access-Control-Allow-Headers': 'Content-Type, Accept',
        'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS'

    }

.container {
  max-width: 900px;
  margin: 0 auto;
}


//     fetch('http://20.16.204.168:5000/auto_data',{mode:"no-cors",headers})
//   .then(response => console.log(response))
//   .catch(error => console.log(error))

label {
    width: 100%;
}

.card-input-element {
    display: none;
}

.card-input {
    margin: 10px;
    padding: 00px;
}

.card-input:hover {
    cursor: pointer;
    box-shadow: 2px 2px 8px 0px #6c757d;
    transition: .3s all ease-in-out;
}

.card-input-element:checked + .card-input {
     box-shadow: 0 0 1px 1px #2ecc71;
     border: none;
     background-color: rgb(0, 83, 125);
     color:#fff;
 }

.select2-container {
    width: 300px !important;
}

.panel-default{
    width: 150px;
    height: 50px;
    border: 3px solid rgb(246, 77, 75);
    border-radius: 11px;
    text-align: center;
    align-items: center;
    justify-content: center;
    display: flex;
}


</style>

<script src="{{ asset('js/estimation-script.js') }}" defer></script>

