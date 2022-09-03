<script>
    if ($("#absensi").length > 0) {
        $("#absensi").validate({
            rules: {
                user_id: {
                    required: true,
                },
            },
            messages: {
                user_id: {
                    required: "Karyawan harus dipilih",
                },
            },
        })
    }
</script>
    