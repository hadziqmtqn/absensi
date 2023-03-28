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
                    <form class="forms-sample" method="POST" action="{{ route('setting.update', $appName->id) }}" enctype="multipart/form-data" id="validasi">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Nama Aplikasi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="application_name" value="{{ $appName->application_name }}" placeholder="Nama Aplikasi">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Deskripsi Aplikasi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="description" value="{{ $appName->description }}" placeholder="Deskripsi Aplikasi">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="email" value="{{ $appName->email }}" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">No. HP</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="no_hp" value="{{ $appName->no_hp }}" placeholder="No. HP">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Awal Absensi</label>
                            <div class="col-sm-9">
                                <input type="time" class="form-control" name="awal_absensi" value="{{ $appName->awal_absensi }}" placeholder="Awal Absensi">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-3 col-form-label">Akhir Absensi</label>
                            <div class="col-sm-9">
                                <input type="time" class="form-control" name="akhir_absensi" value="{{ $appName->akhir_absensi }}" placeholder="Akhir Absensi">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Logo</label>
                            <div class="col-sm-9">
                                <input type="file" name="logo" accept=".jpg,.jpeg,.png,.svg" class="file-upload-default">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Logo">
                                    <span class="input-group-append">
                                        <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('dashboard.setting.validation')
@endsection