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

//include_once 'PDOlib/mysql.php';

require_once dirname(__FILE__).'/vendor/autoload.php';


$config = [
    'host' => '127.0.0.1',
    'port' => '8123',
    'username' => 'default',
    'password' => ''
];

$db = new ClickHouseDB\Client($config);
$db->database('zes');

//$sql = "select * from zes.counter3 WHERE growth != value";
$sql = "select sum(toInt16(growth)) as growth, toStartOfFiveMinute(time) as ttime 
 FROM zes.counter3
 GROUP BY ttime 
  HAVING (growth != -18908)
order by ttime ASC";

$res = $db->select($sql)->rows();


$sql = "select MAX(value) as value, toStartOfFiveMinute(time) as time
 FROM zes.counter3
 GROUP BY time
order by time ASC";
$res2 = $db->select($sql)->rows();

$rows = $res;

$array = [];
foreach ($rows as $row) {
    $array['labels'][] = date("Y-m-d H:i:s", strtotime($row['ttime']." + 2 hours"));
    $array['data'][] = (int)$row['growth'];
}

$array2 = [];
foreach ($res2 as $row) {
    $array2['labels'][] = date("Y-m-d H:i:s", strtotime($row['time']." + 2 hours"));
    $array2['data'][] = (int)$row['value'];
}

$result  = ['growth' => $array, 'vals' => $array2];
print_r(json_encode($result));
die;
