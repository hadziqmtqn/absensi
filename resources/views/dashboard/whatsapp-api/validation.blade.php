<script>
    if ($("#setting").length > 0) {
        $("#setting").validate({
            rules: {
                application_name: {
                    required: true,
                },
                email: {
                    required: true,
                },
            },
            messages: {
                application_name: {
                    required: "Nama Aplikasi Wajib diisi",
                },
                email: {
                    required: "Email Wajib diisi",
                    email: "Email Harus Valid",
                },
                no_hp: {
                    number: "No. HP Harus Berupa Angka",
                    minlength: "No. HP Harus Lebih dari 11 Digit",
                    maxlength: "No. HP Harus Kurang dari 13 Digit",
                },
            },
        })
    }
</script>
    