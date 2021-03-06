<?php

//https://ze2019.com/storage/counters.json?rand=192434
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/vendor/autoload.php';
$dbname = 'zes';
$table = 'counter6';
$config = [
    'host' => '127.0.0.1',
    'port' => '8123',
    'username' => 'default',
    'password' => ''
];

$db = new ClickHouseDB\Client($config);
$db->database('zes');

$response = file_get_contents('https://ze2019.com/storage/counters.json?rand=' . rand());
$response = json_decode($response, 1);
$result = reset($response);

$growthSql = "select value from {$dbname}.{$table} order by time desc limit 1";

$prevValue = 0;
$growth = 0;

$prevValue = $db->select($growthSql)->fetchOne()['value'];

$growth = $result - $prevValue;

$now = (new \DateTime())->modify('+2 hours');

$res = $db->insert($table,
    [
        [$now->format('Y-m-d'), $now->format('Y-m-d H:i:s'), (string)$result, (string)$growth]
    ],
    ['date', 'time', 'value', 'growth']
);

echo "COUNTER: {$result}" . PHP_EOL;
echo "GROWTH: {$growth}" . PHP_EOL;
