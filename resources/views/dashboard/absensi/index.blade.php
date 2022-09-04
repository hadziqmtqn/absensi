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
                                <label for="">Pilih Tanggal Absensi</label>
                                <input type="date" id="filter-waktuabsen" class="form-control filter" placeholder="Masukkan Tanggal">
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
                    <div class="mb-3">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#data_job">Tambah Baru</button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="data_job" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Absensi Baru</h5>
                                </div>
                                <form class="forms-sample" method="POST" action="{{ route('absensi.store') }}" id="absensi">
                                    @csrf
                                    <div id="absensi">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="">Pilih Nama Karyawan</label>
                                                <select name="user_id" class="form-control">
                                                    <option value="">Pilih</option>
                                                    @foreach ($listKaryawan as $karyawan)
                                                        <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-inverse-secondary btn-fw" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- akhir modal --}}
                    <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Lengkap</th>
                                <th>Absen Pada</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('theme/template/js/dashboard/absensi.js') }}"></script>
@include('dashboard.absensi.validation')
@endsection