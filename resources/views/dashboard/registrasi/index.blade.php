@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <form action="{{ route('registrasi.store') }}" method="POST" enctype="multipart/form-data" id="register">
                    @csrf
                    <div class="card-body">
                        <h4 class="card-title">{{ $title }}</h4>
                        @include('dashboard.layouts.session')
                        <div class="form-group">
                            <label for="">Nama Karyawan</label>
                            <input type="text" class="form-control form-control-lg" value="{{ old('name') }}" name="name" placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Panggilan</label>
                            <input type="text" class="form-control form-control-lg" value="{{ old('short_name') }}" name="short_name" placeholder="Nama Panggilan">
                        </div>
                        <div class="form-group">
                            <label for="">No. Whatsapp</label>
                            <input type="number" class="form-control form-control-lg" value="{{ old('phone') }}" name="phone" placeholder="No. Telp/HP">
                        </div>
                        <div class="form-group">
                            <label for="">Dari PT.</label>
                            <input type="text" class="form-control form-control-lg" value="{{ old('company_name') }}" name="company_name" placeholder="Nama Asal  PT">
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" class="form-control form-control-lg" value="{{ old('email') }}" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="">Ulangi Password</label>
                            <input type="password" class="form-control form-control-lg" name="confirm_password" placeholder="Ulangi Password">
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
{{--    @include('dashboard.registrasi.validation')--}}
@endsection
