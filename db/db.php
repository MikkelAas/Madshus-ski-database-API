<?php

class DB {

    protected PDO $pdo;

    public function __construct () {
        $config = require_once('config/config.php');

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true,
        ];

        $dsn = sprintf("mysql:host=%s;dbname=%s;port=%s;charset=%s", $config->db_host, $config->db_name, $config->db_port, $config->db_charset);

        try {
            $this->pdo = new PDO($dsn, $config->db_username, $config->db_password, $options);
        } catch (PDOException $e) {
            echo "Something went wrong connecting to database! Please contact system administrator if problem persists.";

            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPDO () : PDO {
        return $this->pdo;
    }

}