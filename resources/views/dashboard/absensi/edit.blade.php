@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                <form class="forms-sample" method="POST" action="{{ route('absensi.update', $data->id) }}" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" value="{{ $data->user->name }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Sudah Absensi</option>
                                    <option value="2" {{ $data->status == '2' ? 'selected' : '' }}>Berhalangan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection