<?php

namespace App\Entity;

use App\Repository\RideRepository;

use App\Utils\RegexPatterns;
use DateTimeImmutable;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "La date de départ est obligatoire.")]
    #[Assert\LessThan(
        value: 'today',
        message: "La date doit être strictement supérieure à aujourd'hui."
    )]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $departureDate = null;

    #[Assert\NotBlank(message: "La ville d'arrivéee est obligatoire.")]
    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(length: 100)]
    private ?string $departurePlace = null;

    #[Assert\NotBlank(message: "La date de départ est obligatoire.")]
    #[Assert\LessThan(
        propertyPath: 'departureDate',
        message: "La date d'arrivée doit être postérieure à la date de départ."
    )]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $arrivalDate = null;

    #[Assert\NotBlank(message: "La ville d'arrivéee est obligatoire.")]
    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(length: 100)]
    private ?string $arrivalPlace = null;

    #[Assert\NotBlank(message: "Le prix est obligatoir. ")]
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[Assert\NotBlank(message: "Le nombre de place disponible est obligatoir.")]
    #[Assert\GreaterThan(value: 0)]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: "Le nombre de place disponible doit être compris entre {{ min }} et {{ max }}."
    )]
    #[ORM\Column]
    private ?int $availableSeats = null;

    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[Assert\NotBlank(message: "La commission est obligatoire.")]
    #[Assert\GreaterThan(value: 0)]
    #[Assert\Range(
        min: 1,
        max: 10,
        notInRangeMessage: "La commission doit être compris entre {{ min }} et {{ max }}."
    )]
    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $commission = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
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

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'ride')]
    private Collection $rideBookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->rideBookings = new ArrayCollection();
    }


    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
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

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getRideBookings(): Collection
    {
        return $this->rideBookings;
    }

    public function addRideBooking(Booking $rideBooking): static
    {
        if (!$this->rideBookings->contains($rideBooking)) {
            $this->rideBookings->add($rideBooking);
            $rideBooking->setRide($this);
        }

        return $this;
    }

    public function removeRideBooking(Booking $rideBooking): static
    {
        if ($this->rideBookings->removeElement($rideBooking)) {
            // set the owning side to null (unless already changed)
            if ($rideBooking->getRide() === $this) {
                $rideBooking->setRide(null);
            }
        }

        return $this;
    }
}
