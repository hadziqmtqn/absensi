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
                    <form class="forms-sample" method="POST" action="{{ route('api-key.update', $apiKey->id) }}" id="validasi">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="">Enkripsi</label>
                            <input type="text" class="form-control" name="enkripsi" value="{{ $apiKey->enkripsi }}" placeholder="Enkripsi">
                        </div>
                        <div class="form-group">
                            <label for="">Domain</label>
                            <input type="text" class="form-control" name="domain" value="{{ $apiKey->domain }}" placeholder="Domain">
                        </div>
                        <div class="form-group">
                            <label for="">Api Key</label>
                            <input type="text" class="form-control" name="api_key" placeholder="Api Key">
                            <span style="font-style: italic">API Key Terenkripsi</span>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection