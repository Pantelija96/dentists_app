<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to ADN!</title>

    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">


    <!-- endinject -->

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/Slike/Logo.png') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="{{ session('theme', 'dark') === 'dark' ? 'layout-dark' : 'layout-light' }} side-menu">
<div class="mobile-author-actions"></div>
<header class="header-top">
    <nav class="navbar navbar-light">
        <div class="navbar-left">
            <div class="logo-area">
                <a class="navbar-brand" href="{{ url('/') }}">
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
                    <a href="" class="il-light-gray" style="margin-right: 5px;">About Us {{ session('theme') }}</a>
                </li>
                <li class="">
                    <a href="" class="il-light-gray" style="padding-right: 10px;">Our Services</a>
                </li>
                <!-- ends: .nav-notification -->
                <li class="nav-settings">
                    <div class="enable-dark-mode" style="height: 20px; width: 20px;">
                        <img src="{{ asset('assets/Slike/ikonice/DarkModeButton.png') }}" alt="" class="light-mode svg" style="width: 20px; vertical-align: unset;">
                    </div>
                </li>
                <!-- ends: .nav-support -->
                <li class="nav-flag-select">
                    <div class="dropdown-custom">
                        <a href="javascript:;" class="nav-item-toggle"><img src="{{ asset('assets/Slike/ikonice/english.png') }}" alt="" class="rounded-circle"></a>
                        <div class="dropdown-parent-wrapper">
                            <div class="dropdown-wrapper dropdown-wrapper--small">
                                <a href="#"><img src="{{ asset('assets/Slike/ikonice/english.png') }}" alt=""> English</a>
                            </div>
                        </div>

                    </div>
                </li>
                <!-- ends: .nav-flag-select -->
                <li class="nav-author">
{{--                    <a href="#" class="il-light-gray">Login</a>--}}
                    <a href="{{ route('showLogin') }}" class="il-light-gray">Login</a>
                </li>
                <!-- ends: .nav-author -->
            </ul>
            <!-- ends: .navbar-right__menu -->
            <div class="navbar-right__mobileAction d-md-none">
                <a href="#" class="btn-author-action">
                    <img class="svg" src="{{ asset('assets/Slike/svg/more-vertical.svg') }}" alt="more-vertical"></a>
            </div>
        </div>
        <!-- ends: .navbar-right -->
    </nav>
</header>

<section class="dental-hero">
    <div class="dental-hero-inner">
        <!-- Leva kolona - tekst -->
        <div class="dental-hero-content">
            <h1 class="dental-hero-title">
                Dental Design,<br>
                3D Printing &amp; Milling
            </h1>

            <p class="dental-hero-subtitle">
                Your Smile,<br>
                Designed by Innovation.
            </p>

            <a href="{{ route('showLogin') }}" class="btn btn-hero-block dental-hero-btn">
{{--            <a href="#}" class="btn btn-hero-block dental-hero-btn">--}}
                Upload your work
            </a>
        </div>

        <!-- Desna kolona - kartica sa slikom -->
        <div class="dental-hero-visual">
            <div class="dental-hero-card" style="opacity: 0;">
                <img src="{{ asset('assets/Slike/hero-video.png') }}" alt="Dental 3D model" />
            </div>
        </div>
    </div>
</section>

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
<script src="{{ asset('assets/scripts/index.js') }}"></script>
@include('layouts.partials.sweet-alert')

<!-- endinject-->
</body>

</html>
