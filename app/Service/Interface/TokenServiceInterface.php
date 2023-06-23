<?php

namespace Clairence\Service\Interface;

interface TokenServiceInterface
{
    public function refreshToken(string $expired_token, string $refresh_token): string;
}
