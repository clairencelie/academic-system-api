<?php

namespace Clairence\Controller;

use Clairence\Database\Database;
use Clairence\Entity\Lecturer;
use Clairence\Entity\UserLoginRequest;
use Clairence\Helper\JsonSerializer;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Implement\UserServiceImpl;

class UserController
{

    private UserServiceImpl $service;

    public function __construct()
    {
        $this->service = new UserServiceImpl(new UserRepositoryImpl(Database::getConnection()));
    }

    public function login(): void
    {
        if (!isset($_POST["id"]) || !isset($_POST["password"])) {
            http_response_code(400);
            exit();
        }

        $userLoginRequest = new UserLoginRequest(
            id: $_POST["id"],
            password: $_POST["password"],
        );

        $tokens = $this->service->auth($userLoginRequest);

        echo json_encode([
            "message" => "login success",
            "access_token" => $tokens["access_token"],
            "refresh_token" => $tokens["refresh_token"],
        ]);
    }

    public function getUser(string $id): void
    {
        $user = $this->service->findUserById($id);

        $array = $user->jsonSerialize();

        echo json_encode($array);
    }
    
    public function getAllLecturer(): void
    {
        $lecturers = $this->service->findAllLecturer();

        echo json_encode($lecturers);
    }

    public function getMahasiswaAktif(): void
    {
        $mahasiswaAktif = $this->service->getMahasiswaAktif();

        echo json_encode($mahasiswaAktif);
    }
}
