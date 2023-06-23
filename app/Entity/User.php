<?php

namespace Clairence\Entity;

class User
{
    public function __construct(private string $id)
    {
    }


    /**
     * Get the value of id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
