@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $dataPasangBaru->kode }}
@endsection
@section('content')
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
                                <td>{{ $dataPasangBaru->kode }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Inet</th>
                                <td>{{ $dataPasangBaru->inet }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Nama Pelanggan</th>
                                <td>{{ $dataPasangBaru->nama_pelanggan }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">No. HP</th>
                                <td>{{ $dataPasangBaru->no_hp }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Alamat</th>
                                <td>{{ $dataPasangBaru->alamat }}</td>
                            </tr>
                            <tr>
                                <th style="width: 150px">Acuan Lokasi</th>
                                <td>{{ $dataPasangBaru->acuan_lokasi }}</td>
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
                @if(!empty($dataPasangBaru->foto))
                <img src="{{ asset($dataPasangBaru->foto) }}" alt="{{ $dataPasangBaru->kode }}" style="width: 100%">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection