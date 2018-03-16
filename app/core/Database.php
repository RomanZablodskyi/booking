<?php

class Database
{
    private $pdo;
    private $sth;
    protected static $_instance;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __construct(){
        $host = '127.0.0.1';
        $login = 'root';
        $pass = '';
        $db = 'Booking_service';
        $charset = 'utf8';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

        $this->pdo = new PDO($dsn, $login, $pass, $opt);
    }

    public function query($queryString, $queryParams = array()){
        $this->sth = $this->pdo->prepare($queryString);
        $this->sth->execute($queryParams);
        return $this->sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastId(){
        return $this->pdo->lastInsertId();
    }

}