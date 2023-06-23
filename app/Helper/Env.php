<?php

namespace Clairence\Helper;

use Dotenv\Dotenv;

class Env
{
    static public function readImmutable(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../..");
        $dotenv->load();
    }
}
