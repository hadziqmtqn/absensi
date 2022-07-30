<script>
    if ($("#registration").length > 0) {
        $("#registration").validate({
            rules: {
                name: {
                    required: true,
                },
                short_name: {
                    required: true,
                },
                nik: {
                    minlength: 16,
                    maxlength: 16,
                }
                phone: {
                    required: true,
                    minlength: 11,
                    maxlength: 13,
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
                },
                short_name:{
                    required: "Nama Panggilan Wajib diisi",
                },
                nik: {
                    minlength: "NIK Harus 16 digit",
                    maxlength: "NIK Harus 16 digit",
                },
                phone: {
                    number: "No. Telp/HP Harus Berupa Angka",
                    minlength: "No. Telp/HP Harus Lebih dari 11 Digit",
                    maxlength: "No. Telp/HP Harus Kurang dari 13 Digit",
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