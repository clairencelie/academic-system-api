<?php

namespace Clairence\Entity;

class UserLoginRequest
{
    public function __construct(
        private string $id,
        private string $password,
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
}
