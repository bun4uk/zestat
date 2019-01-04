<?php

class PdoLibPostgres
{

    public $db_con;
    public $db_name;
    public $server;
    public $port;
    public $user;
    public $pass;

    public function __construct($server, $port, $dbname, $user, $pass)
    {
        $this->db_name = $dbname;
        $this->server = $server;
        $this->port = $port;
        $this->db_name = $dbname;
        $this->user = $user;
        $this->pass = $pass;

        $this->_connect_db();
    }

    public function _connect_db()
    {
        $charset = 'utf8';

        $dsn = "pgsql:dbname=$this->db_name;host=$this->server";
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo = new \PDO($dsn, $this->user, $this->pass, $opt);
        $this->db_con = $pdo;
    }


    public function select($query = '', $params = array(), $reconnect = false)
    {
        if ($reconnect) {
            $this->_connect_db();
        }
        $stmt = $this->db_con->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function selectRow($query = '', $params = array(), $reconnect = false)
    {
        if ($reconnect) {
            $this->_connect_db();
        }
        $stmt = $this->db_con->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function query($query = '', $params = array(), $reconnect = false)
    {
        if ($reconnect) {
            $this->_connect_db();
        }
        $stmt = $this->db_con->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function selectCell($query = '', $params = array(), $reconnect = false)
    {
        if ($reconnect) {
            $this->_connect_db();
        }

        $stmt = $this->db_con->prepare($query);
        $stmt->execute($params);
        $res = $stmt->fetch();
        $result = false;
        if ($res) {
            foreach ($res as $key => $val) {
                $result = $val;
            }
        }
        return $result;
    }

    public function selectCol($query = '', $params = array())
    {
        $stmt = $this->db_con->prepare($query);
        $stmt->execute($params);
        $res = $stmt->fetchAll();
        $result = [];

        foreach ($res as $key => $val) {
            foreach ($val as $item) {
                $result[$item] = $item;
            }
        }
        return array_values($result);
    }

    public function transaction()
    {
        $this->db_con->beginTransaction();
    }

    public function commit()
    {
        $this->db_con->commit();
    }

    public function rollback()
    {
        $this->db_con->rollBack();
    }
}