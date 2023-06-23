<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Clairence\Database\Database;

$pdo = Database::getConnection();

echo "Koneksi db sukses";

$pdo = null;
