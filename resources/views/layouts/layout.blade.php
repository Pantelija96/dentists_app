<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to ADN @yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/line-awesome.min.css') }}">
    @yield('additionalPluginCSS')
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
@yield('additionalPageCSS')


<!-- endinject -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/Slike/Logo.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">

</head>

<body class="{{ session('theme', 'dark') === 'dark' ? 'layout-dark' : 'layout-light' }} side-menu">
<div class="mobile-author-actions"></div>
<header class="header-top">
    <nav class="navbar navbar-light">
        <div class="navbar-left">
            <div class="logo-area">
                <a class="navbar-brand" href="@if(auth()->user()->role != 'user') {{route('admin.dashboard')}} @else {{ route ('user.dashboard') }} @endif">
                    <img class="dark" src="{{ asset('assets/Slike/Logo.png') }}" alt="logo">
                    <img class="light" src="{{ asset('assets/Slike/Logo.png') }}" alt="logo">
                </a>
            </div>
            <div class="top-menu">

                <div class="hexadash-top-menu position-relative">
                </div>

            </div>
        </div>
        <!-- ends: navbar-left -->
        <div class="navbar-right">
            <ul class="navbar-right__menu">
                <li class="">
                    <a href="#" class="il-light-gray" style="margin-right: 5px;" id="nav-links">About Us</a>
                </li>
                <li class="">
                    <a href="#" class="il-light-gray" style="margin-right: 5px;" id="nav-links">Our Services</a>
                </li>
                @if(auth()->user()->role == 'super_admin')
                    <li>
                        <div class="dropdown  dropdown-hover">

                            <a class="il-light-gray" id="nav-links" href="" id="nav-links">
                                System Settings
                                <img src="{{ asset('assets/Slike/svg/chevron-down.svg') }}" alt="chevron-down" class="svg">
                            </a>

                            <div class="dropdown-default">
                                <a class="dropdown-item @yield('regionsActive')" href="{{ route('regions.index') }}">Regions</a>
                                <a class="dropdown-item @yield('deliveryActive')" href="{{ route('delivery.index') }}">Delivery options</a>
                                <a class="dropdown-item @yield('statusActive')" href="{{ route('workstatus.index') }}">Work order statuses</a>
                                <a class="dropdown-item @yield('typesActive')" href="{{ route('worktype.index') }}">Work types</a>
                                <a class="dropdown-item @yield('materialsActive')" href="{{ route('materials.index') }}">Work order materials</a>
                            </div>
                        </div>
                    </li>
                @endif
                <li class="nav-notification">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle @if(auth()->user()->number_of_notifications > 0) icon-active @endif">
                            <img class="svg" src="{{ asset('assets/img/svg/alarm.svg')}}" alt="img">
                        </a>
                        <div class="dropdown-parent-wrapper">
                            <div class="dropdown-wrapper">
                                <h2 class="dropdown-wrapper__title">Notifications <span class="badge-circle badge-warning ms-1">{{ auth()->user()->number_of_notifications }}</span></h2>
                                @if(isset($notifications))
                                    <ul>
                                        @foreach($notifications as $notification)
                                            <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                                                <div class="nav-notification__details">
                                                    <p>
                                                        <a href="" class="subject stretched-link text-truncate" style="max-width: 180px;">{{ $notification->message }}</a>
                                                    </p>
                                                    <p>
                                                        <span class="time-posted">{{ $notification->created_at->format('F d, Y') }}</span>
                                                    </p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                <!-- ends: .nav-notification -->
                <li class="nav-settings">
                    <div class="enable-dark-mode" style="height: 20px; width: 20px;">
                        <img src="{{ asset('assets/Slike/ikonice/DarkModeButton.png')}}" alt="" class="light-mode svg" style="width: 20px; vertical-align: unset;">
                    </div>
                </li>
                <!-- ends: .nav-support -->
                <li class="nav-flag-select">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/img/flag.png')}}" alt="" class="rounded-circle"></a>
                        <div class="dropdown-parent-wrapper">
                            <div class="dropdown-wrapper dropdown-wrapper--small">
                                <a href=""><img src="{{ asset('assets/img/eng.png')}}" alt=""> English</a>
                                <a href=""><img src="{{ asset('assets/Slike/ikonice/serbia.png')}}" alt=""> Serbian</a>
                                <a href=""><img src="{{ asset('assets/Slike/ikonice/croatia.png')}}" alt=""> Croatian</a>
                            </div>
                        </div>

                    </div>
                </li>
                <!-- ends: .nav-flag-select -->
                <li class="nav-author">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/Slike/ikonice/profile-avatar.png') }}" alt="" class="rounded-circle">
                            <span class="nav-item__title">{{ auth()->user()->first_name}}<i class="las la-angle-down nav-item__arrow"></i></span>
                        </a>
                        <div class="dropdown-parent-wrapper">
                            <div class="dropdown-wrapper">
                                <div class="nav-author__info">
                                    <div class="author-img">
                                        <img src="{{ asset('assets/Slike/ikonice/profile-avatar.png') }}" alt="" class="rounded-circle">
                                    </div>
                                    <div>
                                        <h6>{{ auth()->user()->first_name}}  {{  auth()->user()->last_name }}</h6>
                                        <span>{{ auth()->user()->country }}, {{auth()->user()->city}}</span>
                                    </div>
                                </div>
                                <div class="nav-author__options">
                                    <ul>
                                        <li>
                                            <a href="@if(auth()->user()->role == 'admin' || auth()->user()->role == 'super-admin') {{ route('admin.dashboard') }} @else {{ route('user.dashboard') }} @endif">
                                                <i class="uil uil-dashboard"></i> My Dashboard</a>
                                        </li>
                                        {{--                                                <li style="display: none;">--}}
                                        {{--                                                    <a href="">--}}
                                        {{--                                                        <i class="uil uil-user"></i> Profile</a>--}}
                                        {{--                                                </li>--}}
                                        {{--                                                <li style="display: none;">--}}
                                        {{--                                                    <a href="">--}}
                                        {{--                                                        <i class="uil uil-key-skeleton"></i> Billing</a>--}}
                                        {{--                                                </li>--}}
                                    </ul>
                                    <a href="{{ route('logout') }}" class="nav-author__signout">
                                        <i class="uil uil-sign-out-alt"></i> Log Out</a>
                                </div>
                            </div>
                            <!-- ends: .dropdown-wrapper -->
                        </div>
                    </div>
                </li>
                <!-- ends: .nav-author -->
            </ul>
            <!-- ends: .navbar-right__menu -->
            <div class="navbar-right__mobileAction d-md-none">
                <a href="#" class="btn-author-action">
                    <img class="svg" src="{{ asset('assets/img/svg/more-vertical.svg')}}" alt="more-vertical"></a>
            </div>
        </div>
        <!-- ends: .navbar-right -->
    </nav>
</header>

@yield('content')

<div id="overlayer">
    <div class="loader-overlay">
        <div class="dm-spin-dots spin-lg">
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
            <span class="spin-dot badge-dot dot-primary"></span>
        </div>
    </div>
</div>
<div class="overlay-dark-sidebar"></div>
<div class="customizer-overlay"></div>

<!-- inject:js-->
<script src="{{ asset('assets/assets/vendor_assets/js/jquery/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/jquery/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@yield('additionalPluginJS')
<script src="{{ asset('assets/scripts/layout.js') }}"></script>
@yield('additionalPageJS')
@include('layouts.partials.sweet-alert')

<!-- endinject-->
</body>

</html>
