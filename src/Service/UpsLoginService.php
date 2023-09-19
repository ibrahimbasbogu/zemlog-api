<?php

namespace App\Service;

use App\Helper\UpsHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpsLoginService
{
    public static function login(): array
    {
        $authorizationToken = base64_encode($_ENV['UPS_CLIENT_ID'].':'.$_ENV['UPS_CLIENT_SECRET']);

        $authorizationToken = rtrim(strtr($authorizationToken, '+/', '-_'), '=');

        $headers = [
            'x-merchant-id' => $_ENV['UPS_CLIENT_ID'],
            'Authorization' => 'Basic '. $authorizationToken
        ];

        $formParams = [
            'grant_type' => 'client_credentials'
        ];

        $response = UpsHelper::post('/security/v1/oauth/token', [], [], $headers, $formParams);

        return json_decode($response->getContent(), true);
    }
}