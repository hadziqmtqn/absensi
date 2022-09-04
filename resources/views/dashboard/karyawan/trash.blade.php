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
                                        <th>Dihapus pada</th>
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
<script type="text/javascript">
$(function () {
    var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [[3,'asc']],
        ajax: {
            url: "{{ route('getjsonkaryawantrashed') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: function (d) {
                d.is_verifikasi = $('#filter-verifikasi').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'photo', name: 'photo', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'short_name', name: 'short_name'},
            {data: 'nik', name: 'nik'},
            {data: 'phone', name: 'phone'},
            {data: 'email', name: 'email'},
            {data: 'company_name', name: 'company_name'},
            {data: 'status_verifikasi', name: 'status_verifikasi'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {data: 'deleted_at', name: 'deleted_at'},
        ]
    });

    $(".filter").on('change',function(){
        is_verifikasi = $("#filter-verifikasi").val(),
        table.ajax.reload(null,false)
    });
});
</script>
@endsection