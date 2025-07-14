<?php

namespace App\Entity;

use App\Repository\RestaurantScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantScheduleRepository::class)]
class RestaurantSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $daysOfWeek = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $openTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $closeTime = null;

    #[ORM\Column(nullable: true)]
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

    public function getDoysOfWeek(): ?string
    {
        return $this->daysOfWeek;
    }

    public function setDoysOfWeek(string $doysOfWeek): static
    {
        $this->daysOfWeek = $doysOfWeek;

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
