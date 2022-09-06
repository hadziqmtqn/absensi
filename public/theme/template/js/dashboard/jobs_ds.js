$(document).ready(function() {
    $('#job').each(function() {
        
        var areaData = {
            labels: ["10","","","20","","","30","","","40","","", "50","","", "60","","","70"],
            datasets: [
                {
                    data: [200, 480, 700, 600, 620, 350, 380, 350, 850, "600", "650", "350", "590", "350", "620", "500", "990", "780", "650"],
                    borderColor: [
                    '#4747A1'
                    ],
                    borderWidth: 2,
                    fill: false,
                    label: "Orders"
                },
                {
                    data: [400, 450, 410, 500, 480, 600, 450, 550, 460, "560", "450", "700", "450", "640", "550", "650", "400", "850", "800"],
                    borderColor: [
                    '#F09397'
                    ],
                    borderWidth: 2,
                    fill: false,
                    label: "Downloads"
                }
            ]
        };
        var areaOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                filler: {
                    propagate: false
                }
            },
            scales: {
                xAxes: [{
                    display: true,
                    ticks: {
                        display: true,
                        padding: 10,
                        fontColor:"#6C7383"
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false,
                        color: 'transparent',
                        zeroLineColor: '#eeeeee'
                    }
                }],
                yAxes: [{
                    display: true,
                    ticks: {
                        display: true,
                        autoSkip: false,
                        maxRotation: 0,
                        stepSize: 200,
                        min: 200,
                        max: 1200,
                        padding: 18,
                        fontColor:"#6C7383"
                    },
                    gridLines: {
                        display: true,
                        color:"#f2f2f2",
                        drawBorder: false
                    }
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                enabled: true
            },
            elements: {
                line: {
                    tension: .35
                },
                point: {
                    radius: 0
                }
            }
        }
        var revenueChartCanvas = $("#job").get(0).getContext("2d");
        var revenueChart = new Chart(revenueChartCanvas, {
            type: 'line',
            data: areaData,
            options: areaOptions
        });
    });
});