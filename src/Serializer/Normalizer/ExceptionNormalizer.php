<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ExceptionNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param ExceptionEvent $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = array()): array
    {
        if ($_ENV["APP_ENV"] == 'dev') {
            return [
                'message' => $object->getThrowable()->getMessage(),
                'errors' => null,
                'data' => [
                    'file' => $object->getThrowable()->getFile(),
                    'line' => $object->getThrowable()->getLine(),
                ],
            ];
        }

        if (!$object->getThrowable() instanceof HttpExceptionInterface) {
            return [
                'message' => "UNEXPECTED_ERROR_OCCURRED",
                'errors' => null,
                'data' => null,
            ];
        }

        return [
            'message' => $object->getThrowable()->getMessage(),
            'errors' => null,
            'data' => null,
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ExceptionEvent;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
