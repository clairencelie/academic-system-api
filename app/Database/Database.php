<?php

namespace Clairence\Database;

use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $host = "localhost";
        $port = 3306;
        $database = "akademik_stmik_dp_new_2"; // akademik_stmik_dp_new
        $username = "root";
        $password = "";

        $dsn = "mysql:host=$host:$port;dbname=$database";

        return new PDO($dsn, $username, $password);
    }
}
