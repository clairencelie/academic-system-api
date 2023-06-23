<?php

namespace Clairence\Middleware;

use Clairence\Helper\Env;
use Clairence\Middleware\Interface\Middleware;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware implements Middleware
{
    public function before(): void
    {
        $headers = getallheaders();

        /**
         *  Untuk Debugging menggunakan POSTMAN huruf depan pada header "Authorization" harus menggunakan huruf kapital.
         *  Untuk Debugging menggunakan Flutter (client app) huruf depan pada header "Authorization" harus menggunakan huruf kecil => menjadi "authorization".
         */
        if (array_key_exists('Authorization', $headers)) {

            $token = explode(" ", $headers["Authorization"])[1];

            try {
                Env::readImmutable();
                JWT::decode($token, new Key($_ENV['SECRET_KEY'], 'HS256'));
            } catch (\Exception $exception) {
                header("WWW-Authenticate: Bearer error=\"invalid token\"");
                http_response_code(401);
                echo json_encode([
                    "message" => $exception->getMessage()
                ]);
                exit();
            }
        } else {
            header("WWW-Authenticate: Bearer error=\"invalid token\"");
            http_response_code(401);
            throw new Exception("no token is sended");
            exit();
        }
    }
}
