@extends('v2.layouts.default')

@section('title', 'login')

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/auth.styles.css') }}">

    <style>
        .account-item {
            cursor: pointer;
            background: #0000000d;
            padding: 10px;
            width: 100%;
            overflow: hidden;
            transition: .3s ease-in-out;
        }
        .account-item:hover {
            background: #0000001c;
        }
    </style>

@endsection

@section('content')

    <div class="container">

        <div class="login-container" id="login-app">
            <h1 class="login-title">CacheTest</h1>



            <div class="login-form" >
                {{-- form-group username --}}


                 <p>{{ $user[0]->username }}</p>
                 
                </div>

  


            </div>
        </div>
    </div>

@endsection

@section('custom_foot')



@endsection
