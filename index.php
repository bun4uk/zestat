<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta property="og:image"
          content="https://cdn.icon-icons.com/icons2/963/PNG/512/1477521928_10_icon-icons.com_74620.png">
    <meta property="og:image:type" content="image/png">
    <meta name="description" content="Статистика анкет на сайте ze2019.com">
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

        p, label, a {
            color: #fff;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/js-datepicker/dist/datepicker.min.css">
</head>
<body>
<div class="center">
    <h1 style="color: #fff;">Статистика анкет на сайте <a target="_blank" href="https://Ze2019.com">Ze2019.com</a></h1>
    <h5 style="color: #ccc;">* обновляется раз в минуту</h5>
</div>
<div class="">
    <label for="minutes">Группировать по минутам</label>
    <select name="" id="minutes">
        <option value="1">1 минута</option>
        <option selected value="5">5 минут</option>
        <option value="15">15 минут</option>
        <option value="60">1 час</option>
    </select>
    <!--        <input type="text" id="date">-->
    <label for="pickerStart">От</label>
    <input type="text" id="pickerStart">
    <label for="pickerEnd">До</label>
    <input type="text" id="pickerEnd">
    <input type="checkbox" id="logarithmic" name="logarithmic" onclick="logarithmicReload()">
    <label for="logarithmic">Логарифмическая шкала</label>
    <hr>
    <p>Всего заполнено анкет за выбранный день</span>: <b id="total"></b></p>
</div>
<div class="chartBody">
    <canvas id="myChart" style="height:40vh; width:80vw"></canvas>
</div>
<div class="footer">
    <p>Created by:
        <br>
        <a target="_blank" href="https://www.facebook.com/bun4uk">bun4uk</a>
        <br>
        <a target="_blank" href="https://www.facebook.com/khaMih">khaMih</a>
        <br>
    </p>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="https://unpkg.com/js-datepicker"></script>
<script>
    var nd = new Date(Date.now());
    var dateFrom = nd.getFullYear() + '-' + ('0' + (nd.getMonth() + 1)).slice(-2) + '-' + ('0' + nd.getDate()).slice(-2);
    var dateTo = nd.getFullYear() + '-' + ('0' + (nd.getMonth() + 1)).slice(-2) + '-' + ('0' + nd.getDate()).slice(-2);
    // var picker = datepicker('#date', {
    //     dateSelected: new Date(Date.now()),
    //     onSelect: function (instance, nd) {
    //         date = nd.getFullYear() + '-' + ('0' + (nd.getMonth() + 1)).slice(-2) + '-' + ('0' + nd.getDate()).slice(-2);
    //         load();
    //     }
    // });

    var start = datepicker('#pickerStart', {
        dateSelected: new Date(Date.now()),
        onSelect: function (instance, nd) {
            dateFrom = nd.getFullYear() + '-' + ('0' + (nd.getMonth() + 1)).slice(-2) + '-' + ('0' + nd.getDate()).slice(-2);
            load();
        }
    });
    start.setMin(new Date(2019, 0, 3));
    start.setMax(nd);

    var end = datepicker('#pickerEnd', {
        dateSelected: new Date(Date.now()),
        onSelect: function (instance, nd) {
            dateTo = nd.getFullYear() + '-' + ('0' + (nd.getMonth() + 1)).slice(-2) + '-' + ('0' + nd.getDate()).slice(-2);
            // Both instances will be set because they are linked by `id`.
            // instance.setMax(nd)
            load();
        }
    });
    end.setMin(new Date(2019, 0, 3));
    end.setMax(nd);


    var el = document.getElementById('minutes');
    var minutes = el.value;


    el.addEventListener('change', function (e) {
        minutes = e.target.value;
        load();
    });

    function logarithmicReload() {
        var logarithmicCheckboxEl = document.getElementById('logarithmic');
        if (logarithmicCheckboxEl.checked === true) {
            options.scales.yAxes[0].type = 'logarithmic';
            options.scales.yAxes[1].type = 'logarithmic';
            myChart.options = options;
            load();
        } else {
            options.scales.yAxes[0].type = 'linear';
            options.scales.yAxes[1].type = 'linear';
            myChart.options = options;
            load();
        }

    }

    // logarithmicCheckboxEl.addEventListener('bind', function (e) {
    //     minutes = e.target.value;
    //     load();
    // });

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
        },
        total: 0
    };

    var options = {
        tooltips: {
            intersect: false,
            mode: 'x',
            position: 'nearest'
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
                    // type: 'logarithmic',
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
                    // type: 'logarithmic',
                    // gridLines: {
                    //     color: 'rgba(41,169,255,0.2)'
                    // }
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
        var xhr = new XMLHttpRequest();
        xhr.open("GET", '/info.php?q=' + minutes + '&dateFrom=' + dateFrom + '&dateTo=' + dateTo, true);
        xhr.send();

        xhr.onreadystatechange = function () {
            if (xhr.readyState != 4) return;

            if (xhr.status != 200) {
                console.log(xhr.status + ': ' + xhr.statusText);
            } else {
                data = JSON.parse(xhr.responseText);

                myChart.data.labels = data.growth.labels;
                myChart.data.datasets[0].data = data.growth.data;
                myChart.data.datasets[1].data = data.vals.data;

                myChart.options.scales.yAxes[1].ticks.min = Math.min.apply(null, _toArray(data.vals.data));
                myChart.options.scales.yAxes[1].ticks.max = Math.max.apply(null, _toArray(data.vals.data));
                myChart.update();

                document.getElementById('total').innerHTML = data.total;
            }

            setTimeout(load, 30000);
        }
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
