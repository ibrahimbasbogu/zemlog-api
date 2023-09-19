<?php

namespace App\Entity;

use App\Repository\UserShipmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserShipmentRepository::class)]
class UserShipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userShipments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private array $shipTo = [];

    #[ORM\Column(nullable: true)]
    private array $shipFrom = [];

    #[ORM\Column(nullable: true)]
    private array $package = [];

    #[ORM\Column(nullable: true)]
    private array $shipmentResult = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $trackingNumber = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getShipTo(): array
    {
        return $this->shipTo;
    }

    public function setShipTo(?array $shipTo): self
    {
        $this->shipTo = $shipTo;

        return $this;
    }

    public function getShipFrom(): array
    {
        return $this->shipFrom;
    }

    public function setShipFrom(?array $shipFrom): self
    {
        $this->shipFrom = $shipFrom;

        return $this;
    }

    public function getPackage(): array
    {
        return $this->package;
    }

    public function setPackage(?array $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getShipmentResult(): array
    {
        return $this->shipmentResult;
    }

    public function setShipmentResult(?array $shipmentResult): self
    {
        $this->shipmentResult = $shipmentResult;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTrackingNumber(): ?string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;

        return $this;
    }
}
