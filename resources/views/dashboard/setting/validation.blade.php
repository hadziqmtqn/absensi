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
                    required: "Api Keys Wajib diisi",
                },
                no_hp_penerima: {
                    required: "No. HP Penerima Wajib diisi",
                    number: "No. HP Penerima Harus Berupa Angka",
                    minlength: "No. HP Penerima Harus Lebih dari 11 Digit",
                    maxlength: "No. HP Penerima Harus Kurang dari 13 Digit",
                },
            },
        })
    }
</script>
    