@extends('dashboard.layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
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
    <script type="text/javascript">
        $(function () {
        var table = $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            bLengthChange: false,
            scrollX: true,
            scrollCollapse: true,
            dom: 'lBfrtip',
            buttons: [
                'excel', 'csv', 'pdf', 'copy'
            ],
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            order: [[2,'asc']],
            ajax: {
                url: "{{ route('getjsonkaryawan') }}",
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
                {data: 'namakaryawan', name: 'namakaryawan'},
                {data: 'short_name', name: 'short_name'},
                {data: 'nik', name: 'nik'},
                {data: 'phone', name: 'phone'},
                {data: 'email', name: 'email'},
                {data: 'company_name', name: 'company_name'},
                {data: 'status_verifikasi', name: 'status_verifikasi'},
                {data: 'created_at', name: 'created_at'},
                {data: 'updated_at', name: 'updated_at'},
            ]
        });
    
        $(".filter").on('change',function(){
            is_verifikasi = $("#filter-verifikasi").val()
            table.ajax.reload(null,false)
        });
    });
    </script>
@endsection