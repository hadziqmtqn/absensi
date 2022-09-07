<script type="text/javascript">
    var months = {!! json_encode($months) !!}
    var datajobpending = {!! json_encode($dataJobPending) !!}
    var datajobsuccess = {!! json_encode($dataJobSuccess) !!}
    var barChartData = {
        labels: months,
        datasets: [
            {
                label: 'Data Job Pending',
                backgroundColor: "#FFC100",
                data: datajobpending
            },
            {
                label: 'Data Job Success',
                backgroundColor: "#4747A1",
                data: datajobsuccess
            }
        ]
    };

    window.onload = function() {
        var ctx = document.getElementById("dataJob").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                elements: {
                    rectangle: {
                        // borderWidth: 2,
                        borderColor: 'transparant',
                        borderSkipped: 'bottom'
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    // text: 'Yearly User Joined'
                }
            }
        });
    };
</script>