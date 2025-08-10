<?php

namespace App\Entity;

use App\Repository\RestaurantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

#[ORM\Entity(repositoryClass: RestaurantsRepository::class)]

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['restaurant:list', 'restaurantImages:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['restaurant:item', 'restaurantImages:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['restaurant:write', 'restaurantImages:write', 'restaurantSchedule:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Vous devez être connecté pour créer une nouvelle fiche restaurant"
        ),
        new Put(
            denormalizationContext: ['groups' => ['restaurant:write']],
            security: "is_granted('ROLE_ADMIN') or object.getUser() == user",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour modifier une fiche restaurant"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        )
    ]

)]
class Restaurants
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write', 'review:list', 'review:item'])]
    #[Assert\NotBlank(message: "Le nom du restaurant est obligatoire")]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom du restaurant est trop court (2 caractères minimum)", maxMessage: "Le nom du restaurant est trop long (100 caractères maximum)")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    #[Assert\NotBlank(message: "L'adresse du restaurant est obligatoire'")]
    private ?string $address = null;

    #[ORM\Column]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    private ?int $postalCode = null;

    #[ORM\Column(length: 100)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['restaurant:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['restaurant:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: "SET NULL", nullable: true)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\ManyToMany(targetEntity: Categories::class, inversedBy: 'restaurants')]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write', 'category:list', 'category:item'])]
    private Collection $categories;

    /**
     * @var Collection<int, RestaurantSchedule>
     */
    #[ORM\ManyToMany(targetEntity: RestaurantSchedule::class, inversedBy: 'restaurants', cascade: ['persist'])]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write', 'restaurantSchedule:write'])]
    private Collection $openingHours;

    /**
     * @var Collection<int, RestaurantImages>
     */
    #[ORM\OneToMany(targetEntity: RestaurantImages::class, mappedBy: 'restaurant', cascade: ['persist', 'remove'])]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write', 'restaurantImages:write'])]
    #[ApiProperty(fetchEager: false, readableLink: true)]
    private Collection $restaurantImages;
    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'restaurant')]
    #[Groups(['restaurant:list', 'restaurant:item', 'review:list', 'review:item'])]
    #[ApiProperty(fetchEager: false)]
    private Collection $reviews;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['restaurant:list', 'restaurant:item', 'restaurant:write'])]
    private ?string $description = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->openingHours = new ArrayCollection();
        $this->restaurantImages = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
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

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, RestaurantSchedule>
     */
    public function getOpeningHours(): Collection
    {
        return $this->openingHours;
    }

    public function addOpeningHour(RestaurantSchedule $openingHour): static
    {
        if (!$this->openingHours->contains($openingHour)) {
            $this->openingHours->add($openingHour);
        }

        return $this;
    }

    public function removeOpeningHour(RestaurantSchedule $openingHour): static
    {
        $this->openingHours->removeElement($openingHour);

        return $this;
    }

    /**
     * @return Collection<int, RestaurantImages>
     */
    public function getRestaurantImages(): Collection
    {
        return $this->restaurantImages;
    }

    public function addRestaurantImage(RestaurantImages $restaurantImage): static
    {
        if (!$this->restaurantImages->contains($restaurantImage)) {
            $this->restaurantImages->add($restaurantImage);
            $restaurantImage->setRestaurant($this);
        }

        return $this;
    }

    public function removeRestaurantImage(RestaurantImages $restaurantImage): static
    {
        if ($this->restaurantImages->removeElement($restaurantImage)) {
            // set the owning side to null (unless already changed)
            if ($restaurantImage->getRestaurant() === $this) {
                $restaurantImage->setRestaurant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setRestaurant($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getRestaurant() === $this) {
                $review->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
