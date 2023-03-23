<script>
    if ($("#validasi").length > 0) {
        $("#validasi").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 5,
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                }
            },
            messages: {
                name: {
                    required: "Nama Lengkap Wajib diisi",
                    minlength: "Nama Lengkap Minimal 5 huruf",
                },
                email: {
                    required: "Email Wajib diisi",
                    email: "Email Harus Valid",
                },
                password: {
                    required: "Password Wajib diisi",
                    minlength: "Password Wajib minimal 8 karakter unik",
                },
                confirm_password: {
                    required: "Ulangi Password Wajib diisi",
                    minlength: "Ulangi Password Wajib minimal 8 karakter unik",
                }
            },
        })
    }
</script>
    