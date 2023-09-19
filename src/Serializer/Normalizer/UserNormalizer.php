<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
     * @param User $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $userAdressInfo = [];
        foreach ($object->getUserAddresses() as $userAddress) {
            $userAdressInfo = $userAddress->getAddressInfo();
        }

        return [
            'id' => $object->getId(),
            'email' => $object->getEmail(),
            'roles' => $object->getRoles(),
            'first_name' => $object->getFirstName(),
            'last_name' => $object->getLastName(),
            'address_info' => $userAdressInfo
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof User;
    }
}
