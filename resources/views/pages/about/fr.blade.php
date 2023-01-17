
@extends('v2.layouts.default')

@section('title', __("general.Qui sommes-nous ?") )

@section('custom_head')

    <style>
        body {

            background-image:linear-gradient(
               to bottom,
               rgba(0,0,0,0.3),
               transparent
             ),url({{url('images/bg-register.jpg')}});
            background-size:cover;

            background-repeat:no-repeat;
           }

           .card-title{

              font-size:14px;
              line-height:150%;
           }

           .card-text {

              text-align: justify;
              line-height: 190%;
           }

           .img-container {

            width:60%;
           }

           @media screen and (max-width: 767px){

                .card-title{

                     text-align:center !important;
                     font-size:20px;
                }

                .card-text {

                     text-align:center;
                }

                .titles{

                    text-align:center;

                }
           }
    </style>

@endsection

@section('content')

<section style="overflow: hidden;">
    <div class="container m-auto mt-5 mb-5">
        <h2 class="mt-5 translate titles">{{ __('general.Qui sommes-nous ?') }}</h2>
        <div class="row">

          <div class="col-12 mt-5">
            <div class="card animate__animated animate__backInRight mb-3 shadow" style="background-color: rgb(250, 250, 250);">
            <div class="row g-1">
                <div class="col-12 col-md-8">
                <div class="d-flex card-body flex-column mt-4">
                    <h5 class="card-title fw-bold text-muted translate">{{ __('general.Découvrez Multilist') }}</h5>
                    <p class="card-text text-muted translate">

                        {{ __('general.naissance') }}
                    </p>
                </div>
                </div>
                <div class="col img-container">
                    <img src="{{ asset('images/agreement.png') }}" class="d-flex p-3 m-auto" alt="naissance multilist" width="200">
                </div>
            </div>
            </div>
          </div>


          <div class="col-12 mt-5">
            <div class="card animate__delay-1s animate__animated animate__backInLeft mb-3 shadow" style="background-color: rgb(250, 250, 250);">
            <div class="row g-1">
                <div class="col img-container">
                    <img src="{{ asset('images/app.png') }}" class="d-flex p-3 m-auto mt-4" alt="plateforme multilist complète" width="200">
                </div>
                <div class="col-12 col-md-8">
                <div class="d-flex card-body flex-column mt-4">
                    <h5 class="card-title fw-bold text-muted translate">{{ __('general.promesse') }}</h5>
                    <p class="card-text text-muted translate">
                      {{ __('general.promessetext') }}
                    </p>
                </div>
                </div>
            </div>
            </div>
          </div>

          <div class="col-12 mt-5">
            <div class="card animate__delay-2s animate__animated animate__backInRight mb-3 shadow" style="background-color: rgb(250, 250, 250);">
            <div class="row g-1">
                <div class="col-12 col-md-8">
                <div class="d-flex card-body flex-column mt-4">
                    <h5 class="card-title fw-bold text-muted translate">
                        {{ __('general.principle') }}
                    </h5>
                    <p class="card-text text-muted translate">
                        {{ __('general.principletext') }}
                    </p>
                </div>
                </div>
                <div class="col img-container">
                    <img src="{{ asset('images/real-estate.png') }}" class="d-flex p-3 m-auto" alt="..." width="200">
                </div>
            </div>
            </div>
          </div>

          <div class="col-12 mt-5">
            <div class="card animate__delay-3s animate__animated animate__backInLeft mb-3 shadow" style="background-color: rgb(250, 250, 250);">
            <div class="row g-1">
                <div class="col img-container">
                    <img src="{{ asset('images/participant.png') }}" class="d-flex p-3 m-auto" alt="..." width="200">
                </div>
                <div class="col-12 col-md-8">
                <div class="d-flex card-body flex-column mt-4">
                    <h5 class="card-title fw-bold text-muted translate">
                        {{ __('general.ciblage') }}
                    </h5>
                    <p class="card-text text-muted translate">
                       {{ __('general.ciblagetext') }}
                    </p>
                </div>
                </div>
            </div>
            </div>
          </div>


          <div class="col-12 mt-5">
            <div class="card animate__delay-4s animate__animated animate__backInRight mb-3 shadow" style="background-color: rgb(250, 250, 250);">
            <div class="row g-1">
                <div class="col-12 col-md-8">
                <div class="d-flex card-body flex-column mt-4">
                    <h5 class="card-title fw-bold text-muted translate">
                        {{ __('general.penalopie') }}
                    </h5>
                    <p class="card-text text-muted translate">
                            {{ __('general.penalopietext') }}
                    </p>
                </div>
                </div>
                <div class="col img-container">
                    <img src="{{ asset('images/realtor.png') }}" class="d-flex p-3 m-auto" alt="..." width="200">
                </div>
            </div>
            </div>
          </div>

        </div>
    </div>
</section>
@endsection
