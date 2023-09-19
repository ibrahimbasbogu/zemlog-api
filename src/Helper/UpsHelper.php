<?php

namespace App\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpsHelper
{
    const API_URL = 'https://wwwcie.ups.com';

    public static function get(string $path, array $body = [], array $query = [], array $headers = []): JsonResponse
    {
        $client = new Client();

        try {
            $response = $client->get(self::API_URL.$path, [
                RequestOptions::HEADERS => $headers,
                RequestOptions::QUERY => $query,
                RequestOptions::JSON => $body,
            ]);

            return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode(), [], true);

        } catch (BadResponseException $exception) {
            return new JsonResponse($exception->getResponse()->getBody()->getContents(), $exception->getResponse()->getStatusCode(), [], true);
        }
    }

    public static function post(string $path, array $body = [], array $query = [], array $headers = [], array $formParams = []): JsonResponse
    {
        $client = new Client([
            'timeout' => 5,
        ]);

        try {
            if (count($formParams) > 0){
                $response = $client->post(self::API_URL.$path, [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::QUERY => $query,
                    RequestOptions::FORM_PARAMS => $formParams
                ]);
            } else {
                $response = $client->post(self::API_URL . $path, [
                    RequestOptions::HEADERS => $headers,
                    RequestOptions::QUERY => $query,
                    RequestOptions::JSON => $body
                ]);
            }

            return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode(), [], true);

        } catch (BadResponseException $exception) {
            return new JsonResponse($exception->getResponse()->getBody()->getContents(), $exception->getResponse()->getStatusCode(), [], true);
        }
    }

    public static function delete(string $path, array $body = [], array $query = [], array $headers = []): JsonResponse
    {
        $client = new Client();

        try {
            $response = $client->delete(self::API_URL.$path, [
                RequestOptions::HEADERS => $headers,
                RequestOptions::QUERY => $query,
                RequestOptions::JSON => $body,
            ]);

            return new JsonResponse($response->getBody()->getContents(), $response->getStatusCode(), [], true);

        } catch (BadResponseException $exception) {
            return new JsonResponse($exception->getResponse()->getBody()->getContents(), $exception->getResponse()->getStatusCode(), [], true);
        }
    }
}