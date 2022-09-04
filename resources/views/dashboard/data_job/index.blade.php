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
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    @include('dashboard.layouts.session')
                    <div class="mb-3">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#data_job">Tambah Baru</button>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="data_job" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Job Baru</h5>
                                </div>
                                <form class="forms-sample" method="POST" action="{{ route('data-job.store') }}" enctype="multipart/form-data" id="datajob">
                                    @csrf
                                    <div id="datajob">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="">Kode Pasang Baru</label>
                                                <select name="kode_pasang_baru" class="form-control">
                                                    <option value="">Pilih</option>
                                                    @foreach ($listPasangBaru as $pasang)
                                                        <option value="{{ $pasang->id }}" {{ old('kode_pasang_baru') == $pasang->id ? 'selected' : '' }}>{{ $pasang->kode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Nama Karyawan</label>
                                                <select name="user_id" class="form-control">
                                                    <option value="">Pilih</option>
                                                    @foreach ($listKaryawan as $karyawan)
                                                        <option value="{{ $karyawan->id }}" {{ old('user_id') == $karyawan->id ? 'selected' : '' }}>{{ $karyawan->name }}</option>
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
                                <th>Aksi</th>
                                <th>Status</th>
                                <th>Kode</th>
                                <th>Nama Teknisi</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Alamat Pasang Baru</th>
                                <th>Acuan Lokasi</th>
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
<script type="text/javascript">
    $(function () {
    var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [[3,'desc']],
        ajax: {
            url: "{{ route('getjsondatajob') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: function (d) {
                d.status = $('#filter-status').val(),
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'kode', name: 'kode'},
            {data: 'karyawan', name: 'karyawan'},
            {data: 'nama_pelanggan', name: 'nama_pelanggan'},
            {data: 'no_hp', name: 'no_hp'},
            {data: 'alamat', name: 'alamat'},
            {data: 'acuan_lokasi', name: 'acuan_lokasi'},
            {data: 'created_at', name: 'created_at'},
        ]
    });

    $(".filter").on('change',function(){
        status = $("#filter-status").val(),
        table.ajax.reload(null,false)
    });
});
</script>

@include('dashboard.data_pasang_baru.validation')
@endsection