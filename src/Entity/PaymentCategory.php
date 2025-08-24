<?php

namespace App\Entity;

use App\Repository\PaymentCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\ApiProperty;

#[ORM\Entity(repositoryClass: PaymentCategoryRepository::class)]

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['payment:list']]
        ),
        new Get(
            normalizationContext: ['groups' => ['payment:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['payment:write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous de vez être adminsitrateur pour créer un nouveau type de paiement"
        ),
        new Put(
            denormalizationContext: ['groups' => ['payment:write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous de vez être adminsitrateur pour modifier un type de paiement"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        )
    ]
)]

class PaymentCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['payment:list', 'payment:item', 'payment:write', 'restaurant:list', 'restaurant:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['payment:list', 'payment:item', 'payment:write', 'restaurant:list', 'restaurant:item'])]
    private ?string $type = null;

    /**
     * @var Collection<int, Restaurants>
     */
    #[ORM\ManyToMany(targetEntity: Restaurants::class, inversedBy: 'paymentCategories')]
    private Collection $restaurants;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Restaurants>
     */
    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurants $restaurant): static
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants->add($restaurant);
        }

        return $this;
    }

    public function removeRestaurant(Restaurants $restaurant): static
    {
        $this->restaurants->removeElement($restaurant);

        return $this;
    }
}
