<?php

namespace App\Entity;

use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;

use App\Repository\CarRepository;

use App\Utils\RegexPatterns;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "La marque est obligatoire.")]
    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(type: Types::STRING, length: 100, enumType: CarBrand::class)]
    private ?CarBrand $brand = null;

    #[Assert\NotBlank(message: "Le modèle est obligatoire.")]
    #[Assert\Regex(RegexPatterns::FREE_TEXT_REGEX)]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $model = null;

    #[Assert\NotBlank(message: "La couleur est obligatoire.")]
    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(type: Types::STRING, length: 100, enumType: CarColor::class)]
    private ?CarColor $color = null;

    #[Assert\NotBlank(message: "L'année est obligatoire.")]
    #[Assert\Regex(RegexPatterns::YEAR_REGEX)]
    #[ORM\Column(type: Types::STRING, length: 4)]
    private ?string $year = null;

    #[Assert\NotBlank(message: "L'énergie est obligatoire.")]
    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[ORM\Column(type: Types::STRING, length: 50, enumType: CarPower::class)]
    private ?CarPower $power = null;

    #[Assert\NotBlank(message: "Le nombre de place est obligatoire (dont le conducteur.")]
    #[Assert\GreaterThan(value: 0)]
    #[Assert\Range(
        min: 1,
        max: 10,
        notInRangeMessage: "Le nombre de place doit être compris entre {{ min }} et {{ max }}."
    )]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $seats = null;

    #[Assert\NotBlank(message: "La plaque d'immatriculation est obligatoire.")]
    #[Assert\AtLeastOneOf([
        new Assert\Regex(pattern: RegexPatterns::OLD_REGISTRATION_NUMBER),
        new Assert\Regex(pattern: RegexPatterns::NEW_REGISTRATION_NUMBER)
    ], message: "Le numéro d'immatriculation n'est pas correct.")]
    #[ORM\Column(type: Types::STRING, length: 20)]
    private ?string $registrationNumber = null;

    #[Assert\NotBlank(message: "La date d'immatriculation est obligatoire.")]
    #[Assert\LessThan(
        value: 'today',
        message: "La date doit être strictement inférieure à aujourd'hui."
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $registrationDate = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt = null;


    /**
     * @var Collection<int, Ride>
     */
    #[ORM\OneToMany(targetEntity: Ride::class, mappedBy: 'car')]
    private Collection $rides;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;




    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?CarBrand
    {
        return $this->brand;
    }

    public function setBrand(CarBrand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?CarColor
    {
        return $this->color;
    }

    public function setColor(CarColor $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getPower(): ?CarPower
    {
        return $this->power;
    }

    public function setPower(CarPower $power): static
    {
        $this->power = $power;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;

        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTime
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTime $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): static
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setCar($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCar() === $this) {
                $ride->setCar(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
}
