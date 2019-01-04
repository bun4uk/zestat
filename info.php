<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-01-03
 * Time: 18:25
 */

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

$sql = "select * from zes.counter3 WHERE growth != value";
$res = $db->select($sql)->rows();

//print_r($res);

$rows = $res;

$array = [];
foreach ($rows as $row) {
    $array['labels'][] = $row['time'];
    $array['data'][] = (int)$row['growth'];
}

print_r(json_encode($array));
die;
