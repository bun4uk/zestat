<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-01-03
 * Time: 18:25
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ze";
$conn = new mysqli($servername, $username, $password, $dbname);


$sql = 'select * from counter';
$res = $conn->query($sql);

$rows = $res->fetch_all();

$array = [];
foreach ($rows as $row) {
    $array['labels'][] = $row[0];
    $array['data'][] = (int)$row[2];
}

print_r(json_encode($array));
die;