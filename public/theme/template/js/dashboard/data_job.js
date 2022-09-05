$(function () {
    var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [[3,'desc']],
        ajax: {
            url: "getjsondatajob",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: function (d) {
                d.status = $('#filter-status').val(),
                d.created_at = $('#filter-tanggal').val(),
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
            {data: 'created_at', name: 'created_at'},
        ]
    });

    $(".filter").on('change',function(){
        status = $("#filter-status").val(),
        created_at = $("#filter-tanggal").val(),
        table.ajax.reload(null,false)
    });
});