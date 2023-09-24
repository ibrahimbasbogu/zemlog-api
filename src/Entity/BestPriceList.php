<?php

namespace App\Entity;

use App\Repository\BestPriceListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BestPriceListRepository::class)]
class BestPriceList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?array $datas = null;

    #[ORM\Column(nullable: true)]
    private ?float $ephValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $fuelSurcharge = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?array $rawData = null;

    #[ORM\Column]
    private ?bool $isCalculated = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDatas(): ?array
    {
        return $this->datas;
    }

    public function setDatas(?array $datas): static
    {
        $this->datas = $datas;

        return $this;
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRawData(): ?array
    {
        return $this->rawData;
    }

    public function setRawData(?array $rawData): static
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function isIsCalculated(): ?bool
    {
        return $this->isCalculated;
    }

    public function setIsCalculated(bool $isCalculated): static
    {
        $this->isCalculated = $isCalculated;

        return $this;
    }
}
