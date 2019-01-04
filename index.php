<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Зеленский - счетчик анкет</title>
    <style>
        body {
            background-color: #04133b;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
        }

        .chartBody {
            background-color: rgba(255, 255, 255, 0.03);
            padding: 25px;
        }

        .center {
            text-align: center
        }

        h5 {
            margin: 5px;
        }

        p {
            color: #fff;
        }
    </style>
</head>
<body>
<div class="center">
    <h1 style="color: #fff;">Статистика анкет на сайте Ze2019.com</h1>
    <h5 style="color: #ccc;">* обновляется раз в минуту</h5>
    <h5 style="color: #ccc;">* треккинг не работал с 9:00 до 10:35, поэтому наблюдается скачок</h5>
</div>
<div class="">
    <p>Группировать по минутам</p>
    <select name="" id="minutes">
        <option value="1">1 минута</option>
        <option selected value="5">5 минут</option>
        <option value="15">15 минут</option>
        <option value="60">1 час</option>
    </select>
    <input type="date" id="date" data-date="" value="<?php echo date("d m Y")?>" data-date-format="DD MMMM YYYY">
    <hr>
    <p>Всего заполнено анкет за выбранный день</span>: <b id="total"></b></p>
</div>
<div class="chartBody">
    <canvas id="myChart" style="height:40vh; width:80vw"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>

<script>
    var el = document.getElementById('minutes');
    var minutes = el.value;
    var dateEl = document.getElementById('date');
    dateEl.valueAsDate = new Date();
    var date = dateEl.value;
    el.addEventListener('change', (e) => {
        minutes = e.target.value;
        load();
    });
    dateEl.addEventListener('change', (e) => {
        date = e.target.value;
        load();
    });

    var _toArray = function (arr) {
        return Array.isArray(arr) ? arr : [].slice.call(arr);
    };

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
        tooltips: {
            intersect: false,
            mode: 'x'
        },
        animation: {
            duration: 0
        },
        scales: {
            yAxes: [
                {
                    id: 'y-axis-1',
                    position: 'left',
                    ticks: {
                        beginAtZero: false,
                        callback: function (value) {
                            return Number(value.toString());
                        }
                    },
                    type: 'logarithmic',
                    gridLines: {
                        color: 'rgba(41,169,255,0.2)'
                    }
                },
                {
                    id: 'y-axis-2',
                    position: 'right',
                    ticks: {
                        beginAtZero: false,
                        callback: function (value) {
                            return Number(value.toString());
                        }
                    },
                    type: 'logarithmic',
                    gridLines: {
                        color: 'rgba(41,169,255,0.2)'
                    }
                }
            ],
            xAxes: [{
                display: false
            }]
        }
    };

    var ctx = document.getElementById("myChart").getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.growth.labels,
            datasets: [
                {
                    data: data.growth.data,
                    borderWidth: 1.5,
                    label: 'Прирост заполненных анкет',
                    borderColor: '#2aabff',
                    backgroundColor: 'rgba(41,169,255,0.3)',
                    pointBackgroundColor: '#2aabff',
                    pointRadius: 2,
                    yAxisID: 'y-axis-1'
                },
                {
                    data: data.vals.data,
                    borderWidth: 1.5,
                    label: 'Сумма заполненных анкет',
                    borderColor: '#df6c5a',
                    backgroundColor: 'rgba(223, 106, 88, 0.3)',
                    pointBackgroundColor: '#df6c5a',
                    pointRadius: 2,
                    yAxisID: 'y-axis-2'
                }]
        },
        options: options
    });

    load();

    function load() {
        fetch('/info.php?q=' + minutes + '&date=' + date)
            .then((response) => response.json())
            .then((json) => {
                myChart.data.labels = json.growth.labels;
                myChart.data.datasets[0].data = json.growth.data;
                myChart.data.datasets[1].data = json.vals.data;
                myChart.options.scales.yAxes[1].ticks.min = Math.min.apply(null, _toArray(json.vals.data));
                myChart.options.scales.yAxes[1].ticks.max = Math.max.apply(null, _toArray(json.vals.data));
                myChart.update();

                document.getElementById('total').innerHTML = json.total

                setTimeout(load, 30000);
            });
    }
</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129298968-2"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-129298968-2');
</script>


</body>
</html>
