<?php

namespace Clairence\Service\Interface;

use Clairence\Entity\Lecturer;
use Clairence\Entity\User;
use Clairence\Entity\UserLoginRequest;

interface UserServiceInterface
{
    public function auth(UserLoginRequest $userLoginRequest): array;

    public function findUserById(string $id): ?User;

    public function findAllLecturer(): array;

    public function getMahasiswaAktif(): array;
}
