<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        body {
            background-color: #04133b;
        }

        .chartBody {
            background-color: rgba(255, 255, 255, 0.03);
            padding: 25px;
        }
    </style>
</head>
<body>
<div class="chartBody">
    <canvas id="myChart" style="height:50vh; width:80vw"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<script>
    var data;
    load();

    function load() {
        fetch('http://127.0.0.1:8080/info.php')
            .then((response) =>
                response.json()
            ).then((json) => {
            data = json;
            render();
            setTimeout(() => {
                load();
                render();
            }, 30000);
        });
    }


    function render() {
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.data,
                    borderWidth: 1.5,
                    label: 'Growth',
                    borderColor: '#2aabff',
                    backgroundColor: 'rgba(41,169,255,0.3)',
                    pointBackgroundColor: '#2aabff'

                }]
            },
            options: {
                animation: {
                    duration: 0
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function (value) {
                                return Number(value.toString());
                            }
                        },
                        type: 'logarithmic',
                        gridLines: {
                            color: 'rgba(41,169,255,0.2)'
                        }
                    }],
                    xAxes: [{
                        display: false
                    }]
                }
            }
        });
    }

</script>
</body>
</html>