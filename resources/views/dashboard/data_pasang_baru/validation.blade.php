<script>
    if ($("#validasi").length > 0) {
        $("#validasi").validate({
            rules: {
                kode: {
                    required: true,
                },
                inet: {
                    required: true,
                },
                nama_pelanggan: {
                    required: true,
                },
                no_hp: {
                    required: true,
                    minlength: 10,
                    maxlength: 13,
                },
                alamat: {
                    required: true,
                },
                acuan_lokasi: {
                    required: true,
                },
            },
            messages: {
                kode: {
                    required: "Kode Harus diisi",
                },
                inet: {
                    required: "No. Internet Harus diisi",
                    number: "No. Internet Harus Berupa Angka",
                },
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
    