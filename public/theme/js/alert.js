$('.btn-confirm-status-pasang-baru').click(function(event) {
    var form =  $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: 'Peringatan?',
        text: "Apakah Anda Yakin Akan Mengubah Status Pasang Baru Ini?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ok. Yakin!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});