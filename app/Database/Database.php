<?php

namespace Clairence\Database;

use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $host = "localhost";
        $port = 3306;
        $database = "sistem_akademik"; // akademik_stmik_dp_new
        $username = "root";
        $password = "";

        $dsn = "mysql:host=$host:$port;dbname=$database";

        return new PDO($dsn, $username, $password);
    }
}
