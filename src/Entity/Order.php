<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $client;

    #[ORM\Column(type: 'json')]
    private $content = [];

    #[ORM\Column(type: 'string', length: 255)]
    private $streetDelivery;

    #[ORM\Column(type: 'string', length: 255)]
    private $cityDelivery;

    #[ORM\Column(type: 'string', length: 255)]
    private $postalCodeDelivery;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStreetDelivery(): ?string
    {
        return $this->streetDelivery;
    }

    public function setStreetDelivery(string $streetDelivery): self
    {
        $this->streetDelivery = $streetDelivery;

        return $this;
    }

    public function getCityDelivery(): ?string
    {
        return $this->cityDelivery;
    }

    public function setCityDelivery(string $cityDelivery): self
    {
        $this->cityDelivery = $cityDelivery;

        return $this;
    }

    public function getPostalCodeDelivery(): ?string
    {
        return $this->postalCodeDelivery;
    }

    public function setPostalCodeDelivery(string $postalCodeDelivery): self
    {
        $this->postalCodeDelivery = $postalCodeDelivery;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
