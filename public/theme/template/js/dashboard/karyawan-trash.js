$(function () {
    var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [[3,'asc']],
        ajax: {
            url: "getjsonkaryawantrashed",
            type: "GET",
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