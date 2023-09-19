<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Entity\UserShipment;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserShipmentsNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
     * @param UserShipment $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $shipmentCharges = $object->getShipmentResult()['ShipmentCharges'];

        return [
            'id' => $object->getId(),
            'user' => [
                'firstName' => $object->getUser()->getFirstName(),
                'lastName' => $object->getUser()->getLastName(),
                'email' => $object->getUser()->getEmail(),
                'phoneNumber' => $object->getUser()->getPhoneNumber()
            ],
            'totalCharges' => $shipmentCharges['TotalCharges'],
            'shipmentDescription' => $object->getShipmentResult()['ShipmentDescription'] ?? '',
            'trackingNumber' => $object->getTrackingNumber()
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof UserShipment;
    }
}
