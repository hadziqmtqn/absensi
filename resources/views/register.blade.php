@extends('layouts.master')
@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <div class="brand-logo">
                        <img src="@if(!empty($appName->logo)) {{ asset($appName->logo) }} @endif" alt="@if(!empty($appName->application_name)) {{ $appName->application_name }} @endif">
                    </div>
                    <h4>New here?</h4>
                    <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                    @include('dashboard.layouts.session')
                    <form class="pt-3" method="POST" action="{{ route('registration.store') }}" id="register">
                        @csrf
                        <div id="register">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" value="{{ old('name') }}" name="name" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" value="{{ old('short_name') }}" name="short_name" placeholder="Nama Panggilan">
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control form-control-lg" value="{{ old('phone') }}" name="phone" placeholder="No. Telp/HP">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" value="{{ old('company_name') }}" name="company_name" placeholder="Nama Asal  PT">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg" value="{{ old('email') }}" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="confirm_password" placeholder="Ulangi Password">
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">
                                Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- content-wrapper ends -->
</div>
@endsection

@section('scripts')
    @include('validation')
@endsection