<?php

//https://ze2019.com/storage/counters.json?rand=192434
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//include_once 'PDOlib/mysql.php';
//include_once 'clickhouse-php-client/src/Client.php';

require_once dirname(__FILE__).'/vendor/autoload.php';
$dbname = 'zes';
$table = 'counter3';
$config = [
    'host' => '127.0.0.1',
    'port' => '8123',
    'username' => 'default',
    'password' => ''
];

$db = new ClickHouseDB\Client($config);
$db->database('zes');
//$res = $db->showTables();

//print_r($res);
//die();

//while (1) {
$response = file_get_contents('https://ze2019.com/storage/counters.json?rand=' . rand());
$response = json_decode($response, 1);
$result = reset($response);

$growthSql = "select value from {$dbname}.{$table} order by time desc limit 1";

$prevValue = 0;
$growth = 0;

$prevValue = $db->select($growthSql)->fetchOne()['value'];

//echo 'counter:'.PHP_EOL;
//print_r($result).PHP_EOL;
//echo PHP_EOL;
//print_r($prevValue).PHP_EOL;
//
$growth = $result - $prevValue;
//
//print_r('GRO:'.$growth).PHP_EOL;
//die();

//$sql = "insert into counter(date, value, growth) values (NOW(), {$result}, {$growth})";

$res = $db->insert($table,
    [
        [date("Y-m-d"), date("Y-m-d H:i:s"), (string)$result, (string)$growth]
    ],
    ['date', 'time', 'value', 'growth']
);

echo "COUNTER: {$result}" . PHP_EOL;
echo "GROWTH: {$growth}" . PHP_EOL;
//    sleep(60);
//}
