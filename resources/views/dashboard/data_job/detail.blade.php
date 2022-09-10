@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} {{ $data->dataPasangBaru->kode }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Kode</h4>
                <div class="form-group">
                    <select class="form-control" onchange="location = this.value;">
                        @foreach ($listDataJob as $job)
                        <option value="{{ route('data-job.detail',$job->id) }}" {{ ($data->id == $job->id) ? 'selected' : '' }}>{{ $job->dataPasangBaru->kode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $title }}</h4>
                <div class="table-responsive">
                    <table class="table-borderless nowrap" style="width: 100%">
                        <tbody>
                            <tr>
                                <th style="width: 150px">Kode</th>
                                <td>{{ $data->dataPasangBaru->kode }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Nama Pelanggan</th>
                                <td>{{ $data->dataPasangBaru->nama_pelanggan }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">No. HP</th>
                                <td>{{ $data->dataPasangBaru->no_hp }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Alamat</th>
                                <td>{{ $data->dataPasangBaru->alamat }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Acuan Lokasi</th>
                                <td>{{ $data->dataPasangBaru->acuan_lokasi }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Nama Teknisi</th>
                                <td>{{ $data->karyawan->name }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Status</th>
                                <td><label class="badge {{ $badge }}">{{ $status }}</label></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- foto --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Foto {{ $title }}</h4>
                @if(!empty($data->dataPasangBaru->foto))
                <img src="{{ asset($data->dataPasangBaru->foto) }}" alt="{{ $data->dataPasangBaru->kode }}" style="width: 100%">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    
@endsection