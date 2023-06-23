<?php

namespace Clairence\Service\Implement;

use Clairence\Helper\Env;
use Clairence\Service\Interface\TokenServiceInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenServiceImpl implements TokenServiceInterface
{
    public function refreshToken(string $expired_token, string $refresh_token): string
    {
        try {
            Env::readImmutable();
            JWT::decode($refresh_token, new Key($_ENV['REFRESH_KEY'], 'HS256'));

            list($headers, $payload, $signature) = explode(".", $expired_token);

            $expired_payload = json_decode(base64_decode($payload));

            $exp = time() + (15 * 60);

            $new_payload = [
                "sub" => $expired_payload->sub,
                "iat" => time(),
                "nbf" => time(),
                "exp" => $exp,
            ];

            return JWT::encode($new_payload, $_ENV['SECRET_KEY'], 'HS256');
        } catch (\Exception $exception) {
            http_response_code(401);
            echo json_encode([
                "message" => $exception->getMessage()
            ]);
            exit();
        }
    }
}
