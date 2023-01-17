@extends('v2.layouts.default')

@section('title')
    @yield('title1')
@endsection

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/dashboard.styles.css') }}">

    @yield('custom_head1')

@endsection

@section('content')


    <div class="dashboard_container">

        <div class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dashboard')?'active':'' }}" href="/dashboard">
                        <i class="fa-solid fa-gauge mx-2"></i><span>{{__('general.Dashboard')}}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('myitems')?'active':'' }}" href="/myitems">
                        <i class="fa-solid fa-bullhorn mx-2"></i><span>@if(Auth()->user()->usertype != 3) {{__('general.Mes annonces')}} @else Mes projets @endif</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('myprofile')?'active':'' }}" href="/myprofile">
                        <i class="fa-solid fa-user mx-2"></i><span>{{__('general.Mon profile')}}</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('mpd')?'active':'' }}" href="/reset-password">
                        <i class="fa-solid fa-gear mx-2"></i><span>{{ __('general.Modifier mot de passe') }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('myemails')?'active':'' }}" href="/myemails">
                        <i class="fa-solid fa-envelope mx-2"></i><span>{{__('general.Mes emails')}}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('bookings')?'active':'' }}" href="/bookings">
                        <i class="fa-solid fa-calendar-days mx-2"></i><span>{{ __('general.Les Réservation') }}</span>
                    </a>
                </li>
                @if(Auth()->user()->usertype != 3)
                <li class="nav-item">
                    <a  class="nav-link {{ Request::is('mytransactions')?'active':'' }}" href="/mytransactions">
                        <i class="fa-solid fa-right-left mx-2"></i><span>{{ __('general.Mes Opérations') }}</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="main" id="main-scroll">
            @yield('content1')
        </div>
    </div>


<script>

</script>
@endsection


@section('custom_foot')
    @yield('custom_foot1')
@endsection
