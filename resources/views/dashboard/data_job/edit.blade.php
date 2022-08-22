@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $data->kode }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Job</h4>
                <div class="form-group">
                    <select class="form-control" onchange="location = this.value;">
                        @foreach ($listJob as $job)
                        <option value="{{ route('data_job.edit',$job->id) }}" {{ ($data->id == $job->id) ? 'selected' : '' }}>{{ $job->kode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                <form class="forms-sample" method="POST" action="{{ route('data_job.update', $data->id) }}" enctype="multipart/form-data" id="datajob">
                    @csrf
                    {{ method_field('PUT') }}
                    <div id="datajob">
                        <div class="form-group">
                            <label for="">Kode Job</label>
                            <input type="text" class="form-control" name="kode" value="{{ $data->kode }}" placeholder="Kode Job">
                        </div>
                        <div class="form-group">
                            <label for="">Nama Pelanggan</label>
                            <input type="text" class="form-control" name="nama_pelanggan" value="{{ $data->nama_pelanggan }}" placeholder="Nama Pelanggan">
                        </div>
                        <div class="form-group">
                            <label for="">No. HP</label>
                            <input type="number" class="form-control" name="no_hp" value="{{ $data->no_hp }}" placeholder="No. HP">
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="alamat" class="form-control" placeholder="Alamat">{{ $data->alamat }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Acuan Lokasi</label>
                            <textarea name="acuan_lokasi" class="form-control" placeholder="Acuan Lokasi">{{ $data->acuan_lokasi }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Foto</label>
                            <input type="file" name="foto" accept=".jpg,.jpeg,.png" class="file-upload-default">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Foto">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('dashboard.data_job.validation')
@endsection