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
                    <div class="alert alert-warning alert-block">    
                        <strong>Edit Data Setting Melalui Aplikasi Offline</strong>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Nama Aplikasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="application_name" value="{{ $appName->application_name }}" readonly placeholder="Nama Aplikasi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Deskripsi Aplikasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="description" value="{{ $appName->description }}" readonly placeholder="Deskripsi Aplikasi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" name="email" value="{{ $appName->email }}" readonly placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">No. HP</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="no_hp" value="{{ $appName->no_hp }}" readonly placeholder="No. HP">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Awal Absensi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="awal_absensi" value="{{ $appName->awal_absensi }}" readonly placeholder="Awal Absensi">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Akhir Absensi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="akhir_absensi" value="{{ $appName->akhir_absensi }}" readonly placeholder="Akhir Absensi">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection