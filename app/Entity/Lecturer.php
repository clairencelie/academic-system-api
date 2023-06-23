<?php

namespace Clairence\Entity;

class Lecturer extends User
{
    public function __construct(
        private string $id,
        private string $name,
        private string $phone_number,
        private string $email,
        private string $address,
        private string $status,
        private string $role = "dosen",
    ) {
        parent::__construct($id);
    }

    /**
     * Get the value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the value of phone_number
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * Set the value of phone_number
     */
    public function setPhoneNumber(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the value of address
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Set the value of address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public static function createLecturer(array $data): Lecturer
    {
        return new Lecturer(
            $data["nip"],
            $data["nama"],
            $data["no_telp"],
            $data["email"],
            $data["alamat"],
            $data["status"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}
