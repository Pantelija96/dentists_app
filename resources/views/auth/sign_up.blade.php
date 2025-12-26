<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ADN - Sign up</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- inject:css-->

    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/footable.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <!-- endinject -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/Slike/Logo.png') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconscout/unicons@4.0.8/css/line.min.css">
</head>

<body class="{{ session('theme', 'dark') === 'dark' ? 'layout-dark' : 'layout-light' }}">
<main class="main-content">

    <div class="admin">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">
                    <div class="edit-profile">
                        <div class="edit-profile__logos">
                            <a href="#">
                                <img class="dark" src="{{ asset('assets/Slike/Logo.png') }}" alt="Logo ADN">
                                <img class="light" src="{{ asset('assets/Slike/Logo.png') }}" alt="Logo ADN">
                            </a>
                        </div>
                        <div class="card border-0">
                            <div class="card-header">
                                <div class="edit-profile__title">
                                    <h6>Sign Up ADN</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{route('firstStepSignUp')}}" method="POST">
                                    @csrf()
                                    <div class="edit-profile__body">
                                        <div class="edit-profile__body">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="il-gray fs-14 fw-500 align-center mb-10">Type of profile</label>
                                                <select class="form-control px-15 select-2" id="exampleFormControlSelect1" name="type" placeholder="Select a type"> 
                                                    <option value="legal" {{ old('type') == 1 ? 'selected' : '' }}>Legal entity</option>
                                                    <option value="person" {{ old('type') == 2 ? 'selected' : '' }}>Person</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-20">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" id="name" placeholder="Name" name="first_name" value="{{ old('first_name') }}">
                                            </div>
                                            <div class="form-group mb-20">
                                                <label for="last_name">Last name</label>
                                                <input type="text" class="form-control" id="last_name" placeholder="Surname" name="last_name" value="{{ old('last_name') }}">
                                            </div>
                                            <div class="form-group mb-20" id="firmNameDiv">
                                                <label for="firmName">Firm name</label>
                                                <input type="text" class="form-control" id="firmName" placeholder="Firm Name" name="firm_name" value="{{ old('firm_name') }}">
                                            </div>
                                            <div class="form-group mb-20">
                                                <label for="email">Email Adress</label>
                                                <input type="text" class="form-control" id="email" placeholder="name@example.com" name="email" required value="{{ old('email') }}">
                                            </div>
                                            <div class="form-group mb-15">
                                                <label for="password-field">password</label>
                                                <div class="position-relative">
                                                    <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                                                </div>
                                            </div>
                                            <div class="form-group mb-15">
                                                <label for="repeat-password-field">repeat password</label>
                                                <div class="position-relative">
                                                    <input id="repeat-password-field" type="password" class="form-control" name="password_confirmation" placeholder="Please repeat your password" required>
                                                </div>
                                            </div>
                                            <div class="admin-condition">
                                                <div class="checkbox-theme-default custom-checkbox ">
                                                    <input class="checkbox" type="checkbox" id="admin-1" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                                    <label for="admin-1">
                                                        <span class="checkbox-text">Creating an account means you’re okay with our <a href="#" class="color-primary">Terms of Service</a> and <a href="#" class="color-primary">Privacy Policy</a></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="admin__button-group button-group d-flex pt-1 justify-content-md-start justify-content-center">
                                                <button type="submit" class="btn btn-primary btn-default w-100 btn-squared text-capitalize lh-normal px-50 signIn-createBtn ">
                                                    Continue
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- End: .card-body -->

                            <div class="admin-topbar">
                                <p class="mb-0">
                                    Already have an account?
                                    <a href="{{ route('login') }}" class="color-primary">
                                        Login
                                    </a>
                                </p>
                            </div><!-- End: .admin-topbar  -->
                        </div><!-- End: .card -->
                    </div><!-- End: .edit-profile -->
                </div><!-- End: .col-xl-5 -->
            </div>
        </div>
    </div><!-- End: .admin-element  -->

</main>
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
<div class="enable-dark-mode dark-trigger">
    <ul>
        <li>
            <a href="#">
                <i class="uil uil-moon"></i>
            </a>
        </li>
    </ul>
</div>
<!-- inject:js-->

<script src="{{ asset('assets/assets/vendor_assets/js/jquery/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/jquery/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/bootstrap/popper.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/assets/vendor_assets/js/select2.full.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/scripts/index.js') }}"></script>
<script src="{{ asset('assets/scripts/sing-up.js') }}"></script>
@include('layouts.partials.sweet-alert')


<!-- endinject-->
</body>

</html>
