<?php

namespace App\Entity;

use App\Repository\PriceCalculationSettingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceCalculationSettingsRepository::class)]
class PriceCalculationSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $ephValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $fuelSurcharge = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEphValue(): ?float
    {
        return $this->ephValue;
    }

    public function setEphValue(?float $ephValue): static
    {
        $this->ephValue = $ephValue;

        return $this;
    }

    public function getFuelSurcharge(): ?float
    {
        return $this->fuelSurcharge;
    }

    public function setFuelSurcharge(?float $fuelSurcharge): static
    {
        $this->fuelSurcharge = $fuelSurcharge;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
