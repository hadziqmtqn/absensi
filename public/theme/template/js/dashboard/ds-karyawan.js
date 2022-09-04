$('.btn_job').on('click',function(event){
    event.preventDefault();
    var id = $(this).attr('data-modal');
    // $('#modal-view').find('form').attr('action',url);
    $('#modal-job-'+id).modal();
});