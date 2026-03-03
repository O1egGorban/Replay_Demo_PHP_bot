<?php 
namespace Bot\Database;


class DatabaseConnect {
    private $host;
    private $database;
    private $username;
    private $pasword;

    public function __construct($host, $database, $username, $pasword) {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->pasword = $pasword;
    }

    public function connect() {
        try {
            $dsn = "mysql:host={$this->host};port=4000;dbname={$this->database};charset=utf8mb4";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt',
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false 
            ];
            $pdo = new \PDO($dsn, $this->username, $this->pasword, $options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \Exception("Ошибка БД: " . $e->getMessage());
        }
    }
}


?>