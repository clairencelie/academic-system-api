<?php

namespace Clairence\Helper;

class PasswordHash
{
    static public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    static public function verifyPassword(string $hash, string $password): bool
    {
        return password_verify($password, $hash);
    }
}
