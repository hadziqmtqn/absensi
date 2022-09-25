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
                    @if ($cekWhatsappApi < 1)
                        <form class="forms-sample" method="POST" action="{{ route('whatsapp-api.store') }}" enctype="multipart/form-data" id="validasi">
                            @csrf
                            <div id="validasi">
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Domain</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="domain" value="{{ old('domain') }}" placeholder="Domain">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Api Keys</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="api_keys" value="{{ old('api_keys') }}" placeholder="Api Keys">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">No. HP Penerima</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="no_hp_penerima" value="{{ old('no_hp_penerima') }}" placeholder="No. HP Penerima">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        </form>
                    @else
                        <form class="forms-sample" method="POST" action="{{ route('whatsapp-api.update',$data->id) }}" enctype="multipart/form-data" id="validasi">
                            @csrf
                            {{ method_field('PUT') }}
                            <div id="validasi">
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Domain</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="domain" value="{{ $data->domain }}" placeholder="Domain">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Api Keys</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="api_keys" value="{{ $data->api_keys }}" placeholder="Api Keys">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">No. HP Penerima</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" name="no_hp_penerima" value="{{ $data->no_hp_penerima }}" placeholder="No. HP Penerima">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('dashboard.setting.validation')
@endsection