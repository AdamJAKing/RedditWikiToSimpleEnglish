<?php

namespace WikiToSimple\Includes;

class Database
{
    private static $instance;

    /** @var mysqli $connection */
    private $connection;

    private function __construct()
    {
    }

    static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    function getConnection()
    {
        if (!$this->connection) {
            $this->connect();
        }

        return $this->connection;
    }

    function connect() {
        $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->connection) {
            echo "Failed to connect to database, check your settings\n";

            exit(1);
        }
    }
}
