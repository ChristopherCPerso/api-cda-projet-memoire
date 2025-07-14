<?php

namespace App\Entity;

use App\Repository\RestaurantImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantImagesRepository::class)]
class RestaurantImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'restaurantImages')]
    private ?Restaurants $restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getRestaurant(): ?Restaurants
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurants $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
