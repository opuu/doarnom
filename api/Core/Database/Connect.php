<?php

namespace Opu\Core\Database;

use Opu\App\Configs\Config;

class Connect
{
    private $dsn;
    private $pdo;

    public function connect()
    {
        /**
         * Try to connect to the database
         */
        try {

            $this->dsn = 'mysql:host=' . Config::$db_host . ';dbname=' . Config::$db_name . ';charset=utf8mb4' . ';port=' . Config::$db_port;
            $this->pdo = new \PDO($this->dsn, Config::$db_username, Config::$db_password);

            /**
             * set the PDO error mode to exception
             */
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);

            return $this->pdo;
        } catch (\PDOException $e) {
            if (Config::$app_debug) {
                echo "Connection failed: " . $e->getMessage();
            } else {
                echo 'Something went wrong please retry sometimes later';
            }
            die();
        }
    }
}
