<?php

class PdoLib {

    public function __construct()
    {
    }


    public function _db_connect()
    {
        $host = Msqlconfig::$_server;
        $db = Msqlconfig::$_dbname;
        $user = Msqlconfig::$_user;
        $pass = Msqlconfig::$_pass;
        $charset = 'utf8';
        $dbh = new \PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
        $this->mysql = $dbh;

//        return;
//
//        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//        $opt = [
//            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
//            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
//            \PDO::ATTR_EMULATE_PREPARES   => false,
//        ];
//        $pdo = new \PDO($dsn, $user, $pass, $opt);
//        $this->mysql = $pdo;
    }


    public function select()
    {

    }
}