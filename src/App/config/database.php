<?php

namespace App\Config;

class Database
{
    private static $instance = null;
    
    private $connection;

    private function __construct()
    {
        // Parâmetros de conexão
        $servername = "localhost";
        $username = "root";
        $password = "123456";
        $dbname = "gestao_eventos";

        // Criando a conexão
        $this->connection = new \mysqli($servername, $username, $password, $dbname,3306);

        // Verificar a conexão
        if ($this->connection->connect_error) {
            die("Falha na conexão: " . $this->connection->connect_error);
        }
    }

    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->connection;
    }
}
