<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ZE stat</title>
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
<div style="text-align: center">
    <h1 style="color: #fff;">Статистика анкет на сайте Ze2019.com</h1>
    <h4 style="color: #ccc;">* обновляется раз в минуту</h4>
</div>
<div class="chartBody">
    <canvas id="myChart" style="height:50vh; width:80vw"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<script>
    var data;
    load();

    function load() {
        fetch('/info.php')
            .then((response) => response.json())
            .then((json) => {
                data = json;
                render();

                setTimeout(load, 30000);
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
                    pointBackgroundColor: '#2aabff',
                    pointRadius: 2

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
