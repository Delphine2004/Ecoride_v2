<?php

namespace App\Entity;

use DateTimeImmutable;

use App\Repository\RideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $departureDate = null;

    #[ORM\Column(length: 100)]
    private ?string $departurePlace = null;

    #[ORM\Column]
    private ?DateTimeImmutable $arrivalDate = null;

    #[ORM\Column(length: 100)]
    private ?string $arrivalPlace = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $availableSeats = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $commission = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    private ?User $driver = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'bookings')]
    private ?self $ride = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'ride')]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartureDate(): ?DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function setDepartureDate(DateTimeImmutable $departureDate): static
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getDeparturePlace(): ?string
    {
        return $this->departurePlace;
    }

    public function setDeparturePlace(string $departurePlace): static
    {
        $this->departurePlace = $departurePlace;

        return $this;
    }

    public function getArrivalDate(): ?DateTimeImmutable
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(DateTimeImmutable $arrivalDate): static
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getArrivalPlace(): ?string
    {
        return $this->arrivalPlace;
    }

    public function setArrivalPlace(string $arrivalPlace): static
    {
        $this->arrivalPlace = $arrivalPlace;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCommission(): ?string
    {
        return $this->commission;
    }

    public function setCommission(string $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getRide(): ?self
    {
        return $this->ride;
    }

    public function setRide(?self $ride): static
    {
        $this->ride = $ride;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(self $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setRide($this);
        }

        return $this;
    }

    public function removeBooking(self $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getRide() === $this) {
                $booking->setRide(null);
            }
        }

        return $this;
    }
}
