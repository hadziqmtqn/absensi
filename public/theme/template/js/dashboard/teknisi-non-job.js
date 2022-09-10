$(function () {
    var table = $('#laravel_datatable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollCollapse: true,
        lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        order: [[1,'asc']],
        ajax: {
            url: "getjsonteknisinonjob",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            data: function (d) {
                d.search = $('input[type="search"]').val()
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
});