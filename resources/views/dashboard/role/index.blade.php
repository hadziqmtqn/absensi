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
                                        <th>Nama</th>
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
    <script type="text/javascript">
        $(function () {
        var table = $('#laravel_datatable').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            scrollCollapse: true,
            lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
            order: [[2,'asc']],
            ajax: {
                url: "{{ route('getjsonrole') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
            ]
        });
    });
    </script>
@endsection