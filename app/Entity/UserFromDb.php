<?php

namespace Clairence\Entity;

class UserFromDb
{
    public function __construct(
        private string $id,
        private string $password,
        private string $role,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
