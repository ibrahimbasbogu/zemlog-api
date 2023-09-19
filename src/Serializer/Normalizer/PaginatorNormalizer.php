<?php

namespace App\Serializer\Normalizer;

use App\Service\PaginatorService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PaginatorNormalizer implements NormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, string $format = null, array $context = array()): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        $data = [
            'data' => $data['result'],
            'meta' => [
                'pagination' => [
                    'current_page' => $object->getCurrentPage(),
                    'per_page' => $object->getPerPage(),
                    'total_pages' => $object->getLastPage(),
                    'total' => $object->getTotalItems(),
                ]
            ]
        ];

        return $data;
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof PaginatorService;
    }
}
