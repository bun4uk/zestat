<?php

//https://ze2019.com/storage/counters.json?rand=192434


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ze";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully \n";


while (1) {
    $response = file_get_contents('https://ze2019.com/storage/counters.json?rand=' . rand());
    $response = json_decode($response, 1);
    $result = reset($response);


    $growthSql = 'select value from ze.counter order by date desc limit 1';
    $res = $conn->query($growthSql);
    $row = $res->fetch_assoc();

    $prevValue = $row['value'];
    $growth = $result - $prevValue;


    $sql = "insert into ze.counter values (NOW(), {$result}, {$growth})";
    if ($conn->query($sql) === true) {
        echo "COUNTER: {$result}" . PHP_EOL;
        echo "GROWTH: {$growth}" . PHP_EOL;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    sleep(60);
}
