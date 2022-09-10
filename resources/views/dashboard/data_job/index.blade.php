@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Filter</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleSelectGender">Pilih Status</label>
                                <select class="form-control filter" id="filter-status">
                                    <option value="">Pilih Semua</option>
                                    <option value="0">Waiting</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Pending</option>
                                    <option value="3">Success</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Pilih Tanggal</label>
                                <input type="date" class="form-control filter" id="filter-tanggal">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    @include('dashboard.layouts.session')
                    <p class="card-description">
                        <a href="{{ route('teknisi-cadangan.index') }}">Teknisi Cadangan ({{ $teknisiCadangan }})</a> | <a href="{{ route('teknisi-non-job.index') }}">Teknisi Non Job ({{ $teknisiNonJob }})</a>
                    </p>
                    <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Aksi</th>
                                <th>Status</th>
                                <th>Kode</th>
                                <th>Nama Teknisi</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat Pasang Baru</th>
                                <th>Dibuat pada</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('theme/template/js/dashboard/data_job.js') }}"></script>
@include('dashboard.data_pasang_baru.validation')
@endsection