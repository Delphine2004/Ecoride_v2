<?php

namespace App\Entity;

use App\Repository\UserRepository;

use App\Utils\RegexPatterns;
use DateTimeImmutable;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[Assert\Length(min: 2, maxMessage: "Le prénom doit contenir au minimum 2 lettres.")]
    #[Assert\Length(max: 100, maxMessage: "Le prénom ne doit pas dépasser 100 lettres.")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstName = null;

    #[Assert\Regex(RegexPatterns::ONLY_TEXT_REGEX)]
    #[Assert\Length(min: 2, maxMessage: "Le nom doit contenir au minimum 2 lettres.")]
    #[Assert\Length(max: 100, maxMessage: "Le nom ne doit pas dépasser 100 lettres.")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastName = null;

    #[Assert\Regex(RegexPatterns::FREE_TEXT_REGEX)]
    #[Assert\Length(min: 8, maxMessage: "Le login doit contenir au minimum 8 lettres et/ou chiffres.")]
    #[Assert\Length(max: 25, maxMessage: "Le login ne doit pas dépasser 25 lettres et/ou chiffres.")]
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $login = null;


    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Email invalide")]
    #[Assert\Length(max: 255, maxMessage: "L'email ne doit pas dépasser 255 caractères.")]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    // Validation faite dans le type
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[Assert\NotNull(message: "Veuillez sélectionner un rôle.")]
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 100)]
    private ?string $credit = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Car>
     */
    #[ORM\OneToMany(targetEntity: Car::class, mappedBy: 'owner')]
    private Collection $cars;

    /**
     * @var Collection<int, Ride>
     */
    #[ORM\OneToMany(targetEntity: Ride::class, mappedBy: 'driver')]
    private Collection $rides;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->rides = new ArrayCollection();
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

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCredit(): ?string
    {
        return $this->credit;
    }

    public function setCredit(string $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles ?? [];

        $roles[] = 'ROLE_USER'; // rôle requis par symfony

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
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

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setOwner($this);
        }

        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            // set the owning side to null (unless already changed)
            if ($car->getOwner() === $this) {
                $car->setOwner(null);
            }
        }

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
            $ride->setDriver($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getDriver() === $this) {
                $ride->setDriver(null);
            }
        }

        return $this;
    }
}
