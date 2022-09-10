$(function () {
    $('.btn_job').on('click',function(event){
        event.preventDefault();
        var id = $(this).attr('data-modal');
        // $('#modal-view').find('form').attr('action',url);
        $('#modal-job-'+id).modal();
    });
    $('.btn_status').on('click',function(event){
        event.preventDefault();
        var id = $(this).attr('data-modal');
        // $('#modal-view').find('form').attr('action',url);
        $('#modal-status-'+id).modal();
    });
    $('.absensi').click(function(){
        swal("Opps!", "Anda sudah mengisi absensi hari ini!", "warning");
    });
    $('.absensiout').click(function(){
        swal("Opps!", "Sekarang bukan waktu absensi!", "warning");
    });
})