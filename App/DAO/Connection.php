<?php
namespace App\DAO;

abstract class Connection {
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct() {
        $host     = getenv('MYSQL_HOST');
        $dbname   = getenv('MYSQL_DB_NAME');
        $user     = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');
        $port     = getenv('MYSQL_PORT');

        $dsn = "mysql:host={$host};dbname={$dbname};port={$port}";

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );
    }
}
