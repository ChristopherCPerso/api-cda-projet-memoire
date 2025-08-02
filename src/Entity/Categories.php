<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['category:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['category:item']],
        ),
        new Post(
            denormalizationContext: ['groups' => ['category:write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        ),
        new Patch(
            denormalizationContext: ['groups' => ['category:write']],
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        )
    ]
)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:list', 'category:item', 'restaurant:list', 'restaurant:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['category:list', 'category:item', 'restaurant:list', 'restaurant:item', 'category:write'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Restaurants>
     */
    #[ORM\ManyToMany(targetEntity: Restaurants::class, mappedBy: 'categories')]
    private Collection $restaurants;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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
            $restaurant->addCategory($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurants $restaurant): static
    {
        if ($this->restaurants->removeElement($restaurant)) {
            $restaurant->removeCategory($this);
        }

        return $this;
    }
}
