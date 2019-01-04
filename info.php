<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-01-03
 * Time: 18:25
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/vendor/autoload.php';


$config = [
    'host' => '127.0.0.1',
    'port' => '8123',
    'username' => 'default',
    'password' => ''
];

$db = new ClickHouseDB\Client($config);
$db->database('zes');

$quantor = $_GET['q'] ?? 5;
$date = $_GET['date'] ?? (new \DateTimeImmutable())->format('Y-m-d');
$availableQuantors = [
    1,
    5,
    15,
    60
];
if (!in_array($quantor, $availableQuantors)) {
    $quantor = 5;
}


switch ($quantor) {
    case 1:
        $quantorFunction = 'time';
        break;
    case 5:
        $quantorFunction = 'toStartOfFiveMinute(time)';
        break;
    case 15:
        $quantorFunction = 'toStartOfFifteenMinutes(time)';
        break;
    case 60:
        $quantorFunction = 'toStartOfHour(time)';
        break;
}

$sql = "select sum(toInt16(growth)) as growth, {$quantorFunction} as time 
 FROM zes.counter3
 WHERE toDate(time) = '{$date}'
 GROUP BY time 
  order by time ASC";

$res = $db->select($sql)->rows();


$sql = "select MAX(value) as value, {$quantorFunction} as time
 FROM zes.counter3
 WHERE toDate(time) = '{$date}'
 GROUP BY time
order by time ASC";
$res2 = $db->select($sql)->rows();

$rows = $res;
$array = [];
$total = 0;
foreach ($rows as $row) {
    $array['labels'][] = date("Y-m-d H:i:s", strtotime($row['time'] . " + 2 hours"));
    $array['data'][] = (int)$row['growth'];
}

$array2 = [];
foreach ($res2 as $row) {
    $array2['labels'][] = date("Y-m-d H:i:s", strtotime($row['time'] . " + 2 hours"));
    $array2['data'][] = (int)$row['value'];
}

$sqlTotal = "select 
              sum(toInt32(growth)) as total 
            from counter3 
            where time <> '2019-01-03 21:16:07' 
            and toDate(time) = '{$date}'";
$total = $db->select($sqlTotal)->fetchOne();

$result = ['growth' => $array, 'vals' => $array2, 'total' => $total['total']];
echo json_encode($result);
die;
