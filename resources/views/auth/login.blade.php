<!DOCTYPE html>
<html lang="en">

@php
    $appName = \App\Models\Setting::first();
@endphp

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login App | @if(!empty($appName->application_name)) {{ $appName->application_name }} @endif</title>
    @include('dashboard.layouts.head')
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="@if(!empty($appName->logo)) {{ asset($appName->logo) }} @endif" alt="@if(!empty($appName->application_name)) {{ $appName->application_name }} @endif">
                            </div>
                            <h4>{{ $appName->description }}</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            @include('dashboard.layouts.session')
                            <form class="pt-3" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="exampleInputEmail1" name="phone" placeholder="No. Telp/Email" value="{{ old('phone') }}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="exampleInputPassword1" name="password" placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                        <input type="checkbox" name="remember" class="form-check-input {{ old('remember') ? 'checked' : '' }}">
                                        Keep me signed in
                                        <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Don't have an account? <a href="{{ route('registration') }}" class="text-primary">Create</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    @include('dashboard.layouts.scripts')
</body>

</html>
