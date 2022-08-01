@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                @include('dashboard.layouts.session')
                <form class="forms-sample" method="POST" action="{{ route('karyawan.password',$karyawan->id) }}" enctype="multipart/form-data" id="password">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="password">
                        <div class="form-group">
                            <label for="">Nama Karyawan</label>
                            <input type="text" class="form-control" value="{{ $karyawan->name }}" placeholder="Nama Karyawan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="">Ulangi Password</label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        <a href="{{ route('karyawan',$karyawan->username) }}" class="btn btn-inverse-danger btn-fw">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('dashboard.karyawan.validation')
@endsection