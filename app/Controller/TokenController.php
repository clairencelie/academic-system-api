<?php

namespace Clairence\Controller;

use Clairence\Service\Implement\TokenServiceImpl;

class TokenController
{
    private TokenServiceImpl $service;

    public function __construct()
    {
        $this->service = new TokenServiceImpl;
    }

    public function refresh(): void
    {
        if (!isset($_POST["expired_token"]) || !isset($_POST["refresh_token"])) {
            http_response_code(400);
            exit();
        }
        $new_access_token = $this->service->refreshToken($_POST["expired_token"], $_POST["refresh_token"]);

        echo json_encode([
            "access_token" => $new_access_token,
        ]);
    }
}
