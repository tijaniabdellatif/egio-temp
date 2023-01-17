@extends('v2.layouts.default')

@section('title', 'Conditions')

@section('custom_head')
<script src="{{ asset('js/anime.min.js') }}"></script>

<link rel="stylesheet" href="{{ secure_asset('assets/css/v2/conditions.styles.css') }}">


@endsection

@section('content')

<div id="stepping">

     <section class="stepper-wrapper">

        <div class="stepper">
            <div class="stepper-progress">
                <div class="stepper-bar" :style="'width:'+ stepProgress"></div>
            </div>

            <div class="stepper-item" v-for="item in 4" :key="item">
               <div class="stepper-item-counter" :class="{'current-1': step==item,'success-1':step > item}">
                  <img class="icon-success" :class="{'success-2':step > item}" src="{{ secure_asset("assets/img/check.svg") }}" alt="" />
                  <span class="number" :class="{'current-2': step==item,'success-3':step > item}">
                        @{{ item }}
                  </span>
               </div>

               <span class="stepper-item-title" :class="{'current-3': step==item,'success-4':step > item}">
                 Step @{{ item }}
               </span>
            </div>

        </div>


        <div class="stepper-content" v-for="item in 4" :key="item">
            <div class="stepper-pan" v-if="step == item">
              <input type="text" placeholder="your name" />
            </div>
        </div>


        <div class="controls">
            <button class="p-control" @click="step--" :disabled="step == 1">prev</button>
            <button class="p-control green" @click="step++" :disabled="step == 4">after</button>
        </div>


     </section>


</div>
    <script>

        let stepper = createApp({

            data(){

                return {

                    step:1

                }

            },

            computed:{

                  stepProgress(){

                      return (100 / 3) * (this.step - 1) + "%";
                  }
            }






        }).mount('#stepping')

        console.log(stepper)
    </script>
@endsection

@section('custom_foot')


@endsection
