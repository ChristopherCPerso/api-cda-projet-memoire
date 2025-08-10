<?php

namespace App\Entity;

use App\Repository\RestaurantScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\State\RestaurantScheduleFindOrCreateProcessor;

#[ORM\Entity(repositoryClass: RestaurantScheduleRepository::class)]

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['restaurantSchedule:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['restaurantSchedule:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['restaurantSchedule:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Vous devez être connecté pour créer une nouvelle fiche restaurant",
            processor: RestaurantScheduleFindOrCreateProcessor::class
        ),
        new Put(
            denormalizationContext: ['groups' => ['restaurantSchedule:write']],
            security: "is_granted('ROLE_ADMIN') or object.getUser() == user",
            securityMessage: "Vous devez être admin pour créer un restaurant."
        ),
        new Delete()
    ]

)]
class RestaurantSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['restaurantSchedule:item', 'restaurantSchedule:list', 'restaurantSchedule:write', 'restaurant:list', 'restaurant:item'])]

    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['restaurantSchedule:item', 'restaurantSchedule:list', 'restaurantSchedule:write', 'restaurant:list', 'restaurant:item'])]
    private ?string $daysOfWeek = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['restaurantSchedule:item', 'restaurantSchedule:list', 'restaurantSchedule:write', 'restaurant:list', 'restaurant:item'])]
    private ?\DateTime $openTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['restaurantSchedule:item', 'restaurantSchedule:list', 'restaurantSchedule:write', 'restaurant:list', 'restaurant:item'])]
    private ?\DateTime $closeTime = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['restaurantSchedule:item', 'restaurantSchedule:list', 'restaurantSchedule:write', 'restaurant:list', 'restaurant:item'])]
    private ?bool $isClosed = null;

    /**
     * @var Collection<int, Restaurants>
     */
    #[ORM\ManyToMany(targetEntity: Restaurants::class, mappedBy: 'openingHours')]
    private Collection $restaurants;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDaysOfWeek(): ?string
    {
        return $this->daysOfWeek;
    }

    public function setDaysOfWeek(string $daysOfWeek): static
    {
        $this->daysOfWeek = $daysOfWeek;

        return $this;
    }

    public function getOpenTime(): ?\DateTime
    {
        return $this->openTime;
    }

    public function setOpenTime(\DateTime $openTime): static
    {
        $this->openTime = $openTime;

        return $this;
    }

    public function getCloseTime(): ?\DateTime
    {
        return $this->closeTime;
    }

    public function setCloseTime(\DateTime $closeTime): static
    {
        $this->closeTime = $closeTime;

        return $this;
    }

    public function isClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(?bool $isClosed): static
    {
        $this->isClosed = $isClosed;

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
            $restaurant->addOpeningHour($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurants $restaurant): static
    {
        if ($this->restaurants->removeElement($restaurant)) {
            $restaurant->removeOpeningHour($this);
        }

        return $this;
    }
}
