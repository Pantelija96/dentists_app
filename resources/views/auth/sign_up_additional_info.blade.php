<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign up- additional information</title>

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
            <div class="justify-content-center">
                <div class="row">
                    <div class="edit-profile__logos mt-30">
                        <a href="#">
                            <img class="dark" src="{{ asset('assets/Slike/Logo.png') }}" alt="Logo ADN">
                            <img class="light" src="{{ asset('assets/Slike/Logo.png') }}" alt="Logo ADN">
                        </a>
                    </div>
                    <div class="col-lg-12 mt-50">
                        <div class="card card-default card-md mb-3 mx-5">
                            <div class="card-header">
                                <h6>Sign Up ADN</h6>
                            </div>
                            <div class="card-body py-md-25">
                                <form action="{{ route('secondStepSignUp') }}" method="POST">
                                    @csrf()
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="country" class="il-gray fs-14 fw-500 align-center mb-10">Select Your Country</label>
                                                <select class="form-control px-15 select-2" id="country" name="country" placeholder="Select your country">
                                                    <option value="Serbia" {{ old('country') == "Serbia" ? 'selected' : '' }}>Serbia</option>
                                                    <option value="Croatia" {{ old('country') == "Croatia" ? 'selected' : '' }}>Croatia</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="region_id" class="il-gray fs-14 fw-500 align-center mb-10">Select Your Region</label>
                                                <select class="form-control px-15 select-2" id="region_id" name="region_id">
                                                    @foreach($regions as $region)
                                                        <option value="{{ $region->id }}" {{ old('region') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="city" class="il-gray fs-14 fw-500 align-center mb-10">City</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="city" name="city" placeholder="City" value="{{ old('city') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="language" class="il-gray fs-14 fw-500 align-center mb-10">Select Your Language</label>
                                                <select class="form-control px-15 select-2" id="language" name="language" placeholder="Select language">
                                                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
{{--                                                    <option value="sr" {{ old('language') == 'sr' ? 'selected' : '' }}>Serbian</option>--}}
{{--                                                    <option value="cr" {{ old('language') == 'cr' ? 'selected' : '' }}>Croatian</option>--}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="phone_number" class="il-gray fs-14 fw-500 align-center mb-10">Phone number</label>
                                                <input type="text" pattern="+[0-9]{10}" class="form-control ih-medium ip-light radius-xs b-light px-15" id="phone_number" name="phone_number" placeholder="+xxx-xxx-xxxx" value="{{ old('phone_number') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="postal_code" class="il-gray fs-14 fw-500 align-center mb-10">Postal Code</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="postal_code" name="postal_code" placeholder="Postal Code" value="{{ old('postal_code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address1" class="il-gray fs-14 fw-500 align-center mb-10">Address 1</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="address1" name="address1" placeholder="Address 1" value="{{ old('address1') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="address2" class="il-gray fs-14 fw-500 align-center mb-10">Address 2</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="address2" name="address2" placeholder="Address 2" value="{{ old('address2') }}">
                                            </div>
                                        </div>
                                        {{--                                        <div class="col-md-3">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <label for="a4" class="il-gray fs-14 fw-500 align-center mb-10">Card Number*</label>--}}
                                        {{--                                                <input type="text" pattern="[0-9]{16}" maxlength="16" class="form-control ih-medium ip-light radius-xs b-light px-15" id="a4" placeholder="Enter 16 digit card number whitout spaces">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        <div class="col-md-3">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <label for="datepicker8" class="il-gray fs-14 fw-500 align-left mb-10">Expiry date*</label>--}}
                                        {{--                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="date" placeholder="01/10/2021">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        <div class="col-md-3">--}}
                                        {{--                                            <div class="form-group">--}}
                                        {{--                                                <label for="a6" class="il-gray fs-14 fw-500 align-center mb-10">CVV*</label>--}}
                                        {{--                                                <input type="text" pattern="[0-9]{3}" class="form-control ih-medium ip-light radius-xs b-light px-15" id="a6" placeholder="Enter 3 digit CVV">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        {{--                                        <div class="col-md-3">--}}
                                        {{--                                            <div class="form-group" style="display: none;">--}}
                                        {{--                                                <label for="a6" class="il-gray fs-14 fw-500 align-center mb-10">Province</label>--}}
                                        {{--                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light px-15" id="a6" placeholder="Province">--}}
                                        {{--                                            </div>--}}
                                        {{--                                        </div>--}}
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary btn-default btn-squared fw-600 btn-block">Sign Up</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- ends: .card -->
                    </div>
                </div>
            </div>
        </div>
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
<script src="{{ asset('assets/scripts/additionalInformation.js') }}"></script>
@include('layouts.partials.sweet-alert')

<!-- endinject-->
</body>

</html>
