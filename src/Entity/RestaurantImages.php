<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RestaurantImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;


#[ORM\Entity(repositoryClass: RestaurantImagesRepository::class)]

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['restaurantImages:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['restaurantImages:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['restaurantImages:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Vous devez être connecté pour créer une nouvelle fiche restaurant"
        ),
        new Put(
            denormalizationContext: ['groups' => ['restaurant:write']],
            security: "is_granted('ROLE_ADMIN') or object.getUser() == user",
            securityMessage: "Vous devez être admin pour créer un restaurant."
        ),
        new Delete()
    ]

)]
class RestaurantImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurantImages:list', 'restaurantImages:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write', 'restaurantImages:list', 'restaurantImages:item', 'restaurantImages:write'])]
    private ?string $link = null;

    #[ORM\ManyToOne(inversedBy: 'restaurantImages')]
    #[Groups(['restaurant:write'])]
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
