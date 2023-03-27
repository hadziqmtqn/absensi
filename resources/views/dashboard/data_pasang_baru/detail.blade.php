@extends('dashboard.layouts.master')
@section('title')
    {{ $title }} - {{ $dataPasangBaru->kode }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
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
        {{-- update status --}}
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">Update Status Pasang Baru</h4>
                <form action="{{ route('data-pasang-baru.update-status', $dataPasangBaru->id) }}" method="post">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="">Pilih Status</label>
                        <select name="status" class="form-control" required>
                            <option value="">Pilih</option>
                            <option value="0" {{ $dataPasangBaru->status == '0' ? 'selected' : '' }}>Waiting</option>
                            <option value="1" {{ $dataPasangBaru->status == '1' ? 'selected' : '' }}>In Progress</option>
                            <option value="2" {{ $dataPasangBaru->status == '2' ? 'selected' : '' }}>Pending</option>
                            <option value="3" {{ $dataPasangBaru->status == '3' ? 'selected' : '' }}>Success</option>
                        </select>
                    </div>
                    <button type="{{ $dataPasangBaru->status != '3' ? 'submit' : 'button' }}" class="btn btn-primary {{ $dataPasangBaru->status == '3' ? 'disabled' : '' }}">Submit</button>
                </form>
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