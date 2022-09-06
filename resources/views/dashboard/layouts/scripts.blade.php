<!-- plugins:js -->
<script src="{{ asset('theme/template/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('theme/template/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('theme/template/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('theme/template/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('theme/template/js/dataTables.select.min.js') }}"></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('theme/template/js/off-canvas.js') }}"></script>
<script src="{{ asset('theme/template/js/hoverable-collapse.js') }}"></script>
{{-- <script src="{{ asset('theme/template/js/template.js') }}"></script> --}}
<script src="{{ asset('theme/template/js/settings.js') }}"></script>
<script src="{{ asset('theme/template/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('theme/template/js/dashboard.js') }}"></script>
{{-- <script src="{{ asset('theme/template/js/jobs_ds.js') }}"></script> --}}
<script src="{{ asset('theme/template/js/Chart.roundedBarCharts.js') }}"></script>
<!-- End custom js for this page-->
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="{{ asset('theme/template/js/file-upload.js') }}"></script>
<script src="{{ asset('theme/template/js/typeahead.js') }}"></script>
<script src="{{ asset('theme/template/js/select2.js') }}"></script>

<script src="https://cdn.datatables.net/scroller/2.0.7/js/dataTables.scroller.min.js"></script>
{{-- toastr js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
{{-- sweetalert --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

{{-- highchart --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    $(function () {
        $(document).ready( function () {
            $('body').on('click','.btn-hapus',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#modal-hapus').find('form').attr('action',url);
                $('#modal-hapus').modal();
            });
        });
        $(document).ready( function () {
            $('body').on('click','.btn-restore',function(e){
                e.preventDefault();
                var url = $(this).attr('href');
                $('#modal-restore').find('form').attr('action',url);
                $('#modal-restore').modal();
            });
        });
        $(document).ready(function() {
        $('#myTable').DataTable( {
            scrollX:        true,
            scrollCollapse: true
        } );
    } );
    })
</script>