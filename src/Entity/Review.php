<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['review:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['review:item']],
        ),
        new Post(
            denormalizationContext: ['groups' => ['review:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Il faut être connecter pour envoyer un commentaire"
        )
    ]
)]

class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['review:list', 'review:item',  'restaurant:item'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['review:list', 'review:item', 'review:write', 'restaurant:item'])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review:list', 'review:item', 'review:write', 'restaurant:item'])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(['review:list', 'review:item', 'review:write', 'restaurant:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[Groups(['review:list', 'review:item', 'review:write'])]
    private ?Restaurants $restaurant = null;

    #[ORM\ManyToOne]
    #[Groups(['review:list', 'review:item', 'review:write', 'user:list', 'user:item'])]
    private ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
