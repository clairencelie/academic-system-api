<?php

namespace Clairence\Service\Implement;

use Clairence\Entity\Academic;
use Clairence\Entity\Administrator;
use Clairence\Entity\Lecturer;
use Clairence\Entity\Student;
use Clairence\Entity\User;
use Clairence\Entity\UserLoginRequest;
use Clairence\Helper\Env;
use Clairence\Helper\PasswordHash;
use Clairence\Repository\Implement\UserRepositoryImpl;
use Clairence\Service\Interface\UserServiceInterface;
use Exception;
use Firebase\JWT\JWT;

class UserServiceImpl implements UserServiceInterface
{
    public function __construct(private UserRepositoryImpl $repository)
    {
    }

    public function auth(UserLoginRequest $userLoginRequest): array
    {
        $id = $userLoginRequest->getId();
        $request_password = $userLoginRequest->getPassword();

        $userFromDb = $this->repository->auth($id);

        if ($userFromDb == null) {
            http_response_code(404);
            throw new Exception("User not found");
        }

        $hash = $userFromDb->getPassword();

        if (!PasswordHash::verifyPassword($hash, $request_password)) {
            http_response_code(401);
            throw new Exception("Password invalid");
        }

        Env::readImmutable();

        $sub = $userFromDb->getId();
        $exp = time() + (15 * 60);

        $payload = [
            "sub" => $sub,
            "iat" => time(),
            "nbf" => time(),
            "exp" => $exp,
        ];

        $refresh_payload = [
            "id" => $sub,
            "iat" => time(),
            "nbf" => time(),
        ];

        $access_token = JWT::encode($payload, $_ENV["SECRET_KEY"], "HS256");

        $refresh_token = JWT::encode($refresh_payload, $_ENV["REFRESH_KEY"], "HS256");

        return [
            "access_token" => $access_token,
            "refresh_token" => $refresh_token,
        ];
    }

    public function findUserById(string $id): ?User
    {
        $user = $this->repository->getUserById($id);
        if ($user == null) {
            http_response_code(404);
            throw new Exception("User not found");
        }

        return $user;
    }

    public function findAllLecturer(): array
    {
        return $this->repository->getAllLecturer();
    }

    public function getMahasiswaAktif(): array
    {
        return $this->repository->getMahasiswaAktif();
    }
}
