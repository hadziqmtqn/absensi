<script>
    if ($("#validasi").length > 0) {
        $("#validasi").validate({
            rules: {
                domain: {
                    required: true,
                },
                api_keys: {
                    required: true,
                },
                no_hp_penerima: {
                    required: true,
                },
            },
            messages: {
                domain: {
                    required: "Domain Wajib diisi",
                },
                api_keys: {
                    required: "API Keys Wajib diisi",
                },
                no_hp_penerima: {
                    required: "No. HP Wajib diisi",
                    number: "No. HP Harus Berupa Angka",
                },
            },
        })
    }
</script>
    