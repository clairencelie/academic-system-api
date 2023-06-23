<?php

namespace Clairence\Entity;

class Student extends User
{
    public function __construct(
        private string $id,
        private string $name,
        private string $major,
        private string $concentration,
        private string $phone_number,
        private string $email,
        private string $address,
        private string $semester,
        private string $status,
        private string $batch_of,
        private string $role = "mahasiswa",
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
     * Get the value of major 
     */
    public function getMajor(): string
    {
        return $this->major;
    }

    /**
     * Set the value of major
     */
    public function setMajor(string $major): void
    {
        $this->major = $major;
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
     * Get the value of semester
     */
    public function getSemester(): string
    {
        return $this->semester;
    }

    /**
     * Set the value of semester
     */
    public function setSemester(string $semester): void
    {
        $this->semester = $semester;
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

    /**
     * Get the value of batch_of
     */
    public function getBatchOf(): string
    {
        return $this->batch_of;
    }

    /**
     * Set the value of batch_of
     */
    public function setBatchOf(string $batch_of): void
    {
        $this->batch_of = $batch_of;
    }

    public static function createStudent(array $data): Student
    {
        return new Student(
            $data["nim"],
            $data["nama"],
            $data["jurusan"],
            $data["konsentrasi"],
            $data["no_telp"],
            $data["email"],
            $data["alamat"],
            $data["semester"],
            $data["status"],
            $data["angkatan"],
        );
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    /**
     * Get the value of concentration
     */
    public function getConcentration(): string
    {
        return $this->concentration;
    }

    /**
     * Set the value of concentration
     */
    public function setConcentration(string $concentration): self
    {
        $this->concentration = $concentration;

        return $this;
    }
}
