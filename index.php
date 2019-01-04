<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ZE stat</title>
    <style>
        body {
            background-color: #04133b;
            font-family: sans-serif;
        }

        .chartBody {
            background-color: rgba(255, 255, 255, 0.03);
            padding: 25px;
        }

        .center {
            text-align: center
        }
    </style>
</head>
<body>
<div class="center">
    <h1 style="color: #fff;">Статистика анкет на сайте Ze2019.com</h1>
    <h4 style="color: #ccc;">* обновляется раз в минуту</h4>
</div>
<div class="chartBody">
    <canvas id="myChart" style="height:50vh; width:80vw"></canvas>
    <canvas id="myChart2" style="height:50vh; width:80vw"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<script>
    var data = {
        growth: {
            data: [],
            labels: []
        },
        vals: {
            data: [],
            labels: []
        }
    };

    var options = {
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

    var ctx = document.getElementById("myChart").getContext('2d');
    var ctx2 = document.getElementById("myChart2").getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.growth.labels,
            datasets: [{
                data: data.growth.data,
                borderWidth: 1.5,
                label: 'Growth',
                borderColor: '#2aabff',
                backgroundColor: 'rgba(41,169,255,0.3)',
                pointBackgroundColor: '#2aabff',
                pointRadius: 2

            }]
        },
        options: options
    });

    var myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: data.vals.labels,
            datasets: [{
                data: data.vals.data,
                borderWidth: 1.5,
                label: 'Growth',
                borderColor: '#2aabff',
                backgroundColor: 'rgba(41,169,255,0.3)',
                pointBackgroundColor: '#2aabff',
                pointRadius: 2

            }]
        },
        options: options
    });

    load();

    function load() {
        fetch('/info.php')
            .then((response) => response.json())
            .then((json) => {
                myChart.data.labels = json.growth.labels;
                myChart.data.datasets[0].data = json.growth.data;
                myChart.update();

                myChart2.data.labels = json.vals.labels;
                myChart2.data.datasets[0].data = json.vals.data;
                myChart2.update();

                setTimeout(load, 30000);
            });
    }
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129298968-2"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129298968-2');
</script>


</body>
</html>
