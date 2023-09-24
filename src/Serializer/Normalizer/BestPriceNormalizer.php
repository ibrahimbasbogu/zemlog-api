<?php

namespace App\Serializer\Normalizer;

use App\Entity\BestPriceList;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BestPriceNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param BestPriceList $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'user' => $object->getUser() != null ? $object->getUser()->getId() : null,
            'eph' => $object->getEphValue(),
            'yakit' => $object->getFuelSurcharge(),
            'datas' => $object->getDatas(),
            'raw_data' => $object->getRawData()
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof BestPriceList;
    }
}
