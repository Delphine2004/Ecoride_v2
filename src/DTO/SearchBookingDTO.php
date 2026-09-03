<?php

namespace App\DTO;

use App\Enum\BookingStatus;
use App\Utils\Normalizer;

use DateTimeImmutable;

class SearchBookingDTO
{
    public ?int $id = null;
    public ?int $rideId = null;
    public ?int $passengerId = null;

    public ?string $lastName = null;
    public ?string $email = null;
    public ?string $departurePlace = null;
    public ?string $arrivalPlace = null;

    public ?BookingStatus $status = null;

    public ?DateTimeImmutable $departureDate = null;
    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;



    public function getBookingId(): ?int
    {
        return $this->id;
    }

    public function getRideId(): ?int
    {
        return $this->rideId;
    }

    public function getPassengerId(): ?int
    {
        return $this->passengerId;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getDeparturePlace(): ?string
    {
        return $this->departurePlace;
    }

    public function getArrivalPlace(): ?string
    {
        return $this->arrivalPlace;
    }

    public function getStatus(): ?BookingStatus
    {
        return $this->status;
    }

    public function getDepartureDate(): ?DateTimeImmutable
    {
        return $this->departureDate;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function normalize(): void
    {
        $this->lastName = Normalizer::normalizeString($this->lastName);
        $this->email = Normalizer::normalizeString($this->email);
        $this->departurePlace = Normalizer::normalizeString($this->departurePlace);
        $this->arrivalPlace = Normalizer::normalizeString($this->arrivalPlace);
        $this->departureDate = Normalizer::normalizeDate($this->departureDate);
    }
}
