<script>
    if ($("#password").length > 0) {
        $("#password").validate({
            rules: {
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
    