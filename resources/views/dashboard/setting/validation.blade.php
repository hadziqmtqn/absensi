<script>
    if ($("#validasi").length > 0) {
        $("#validasi").validate({
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
                },
            },
        })
    }
</script>
    