<script>
    if ($("#datajob").length > 0) {
        $("#datajob").validate({
            rules: {
                nama_pelanggan: {
                    required: true,
                },
                no_hp: {
                    required: true,
                },
                alamat: {
                    required: true,
                },
                acuan_lokasi: {
                    required: true,
                },
            },
            messages: {
                nama_pelanggan: {
                    required: "Nama Pelanggan Harus diisi",
                },
                no_hp: {
                    required: "No. HP Harus diisi",
                    number: "No. HP Harus Berupa Angka",
                    minlength: "No. HP Harus Lebih dari 11 Digit",
                    maxlength: "No. HP Harus Kurang dari 13 Digit",
                },
                alamat: {
                    required: "Alamat Harus diisi",
                },
                acuan_lokasi: {
                    required: "Acuan Lokasi Harus diisi",
                },
            },
        })
    }
</script>
    