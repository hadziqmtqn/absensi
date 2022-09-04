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
                    <div class="form-group">
                        <label for="exampleSelectGender">Pilih Status Verifikasi</label>
                        <select class="form-control filter" id="filter-verifikasi">
                            <option value="">Pilih Semua</option>
                            <option value="1">Sudah Diverifikasi</option>
                            <option value="0">Belum Diverifikasi</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <p class="card-description">
                        Semua ({{ $karyawanAll }}) | <a href="{{ route('karyawan.index') }}">Aktif ({{ $karyawanActive }})</a> | <a href="{{ route('karyawan.trashed') }}">Terhapus ({{ $karyawanTrashed }})</a>
                    </p>
                    @include('dashboard.layouts.session')
                    <div class="row">
                        <div class="col-12">
                            <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Aksi</th>
                                        <th>Photo</th>
                                        <th>Nama Lengkap</th>
                                        <th>Nama Panggilan</th>
                                        <th>NIK</th>
                                        <th>No. HP</th>
                                        <th>Email</th>
                                        <th>Dari PT</th>
                                        <th>Status</th>
                                        <th>Dibuat pada</th>
                                        <th>Diupdate pada</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@include('dashboard.setting.validation')
<script type="text/javascript" src="{{ asset('theme/template/js/dashboard/karyawan.js') }}"></script>
@endsection