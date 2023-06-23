<?php

namespace Clairence\Repository\Interface;

use Clairence\Entity\Student;
use Clairence\Entity\User;
use Clairence\Entity\UserFromDb;

interface UserRepositoryInterface
{
    public function auth(string $id): ?UserFromDb;

    public function getUserById(string $id): ?User;

    public function getAllLecturer(): array;

    public function getMahasiswaAktif(): array;

    public function getMahasiswaByNim(string $nim): Student;
}
