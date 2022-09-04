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
                                <label for="">Pilih Status</label>
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
                    <div class="row">
                        <div class="col-6 mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#pasang_baru">Tambah Baru</button>
                        </div>
                        <div class="col-12">
                            <table id="laravel_datatable" class="display expandable-table nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Aksi</th>
                                        <th>Status</th>
                                        <th>Kode</th>
                                        <th>Inet</th>
                                        <th>Nama Pelanggan</th>
                                        <th>No. HP</th>
                                        <th>Alamat</th>
                                        <th>Acuan Lokasi</th>
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
@include('dashboard.data_pasang_baru.validation')
<!-- Modal -->
<div class="modal fade" id="pasang_baru" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pasang Baru</h5>
            </div>
            <form class="forms-sample" method="POST" action="{{ route('data-pasang-baru.store') }}" enctype="multipart/form-data" id="pasang_baru">
                @csrf
                <div class="modal-body" id="pasang_baru">
                    <div class="form-group">
                        <label for="">Inet</label>
                        <input type="number" class="form-control" name="inet" value="{{ old('inet') }}" placeholder="No. Internet">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" placeholder="Nama Pelanggan">
                    </div>
                    <div class="form-group">
                        <label for="">No. HP</label>
                        <input type="number" class="form-control" name="no_hp" value="{{ old('no_hp') }}" placeholder="No. HP">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat">{{ old('alamat') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Acuan Lokasi</label>
                        <textarea name="acuan_lokasi" class="form-control" placeholder="Acuan Lokasi">{{ old('acuan_lokasi') }}</textarea>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse-secondary btn-fw" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- akhir modal --}}

<script type="text/javascript" src="{{ asset('theme/template/js/dashboard/pasang-baru.js') }}"></script>
@endsection