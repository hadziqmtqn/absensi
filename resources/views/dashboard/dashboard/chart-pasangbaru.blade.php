<script type="text/javascript">
    var labels =  {!! json_encode($months) !!}
    var pasangbaru =  {!! json_encode($pasangBaru) !!}

    const data = {
        labels: labels,
        datasets: [{
            label: 'Pelanggan Baru',
            backgroundColor: '#4747A1',
            borderColor: 'transparent',
            data: pasangbaru,
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {}
    };

    const myChart = new Chart(
        document.getElementById('pasangBaru'),
        config
    );
</script>