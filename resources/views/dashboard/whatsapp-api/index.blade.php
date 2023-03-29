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
                        <label for="" class="col-sm-3 col-form-label">Domain</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="domain" value="{{ $whatsappApi->domain }}" readonly placeholder="Domain">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Api Keys</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="api_keys" value="{{ $whatsappApi->api_keys }}" readonly placeholder="Api Keys">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">No. HP Penerima</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="no_hp_penerima" value="{{ $whatsappApi->no_hp_penerima }}" readonly placeholder="No. HP Penerima">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection