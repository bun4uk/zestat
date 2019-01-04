<?php

//https://ze2019.com/storage/counters.json?rand=192434
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'PDOlib/mysql.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zes";


$mysql = new PdoLibMysql($servername, '3306', $dbname, $username, $password);

//$res = $mysql->selectCell('select NOW()');


//while (1) {
$response = file_get_contents('https://ze2019.com/storage/counters.json?rand=' . rand());
$response = json_decode($response, 1);
$result = reset($response);


$growthSql = 'select value from ' . $dbname . '.counter order by date desc limit 1';

$prevValue = 0;
$growth = 0;

$prevValue = $mysql->selectCell($growthSql);

$growth = $result - $prevValue;


$sql = "insert into counter(date, value, growth) values (NOW(), {$result}, {$growth})";

$res = $mysql->query($sql);

echo "COUNTER: {$result}" . PHP_EOL;
echo "GROWTH: {$growth}" . PHP_EOL;
//    sleep(60);
//}
