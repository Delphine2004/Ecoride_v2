<?php

namespace App\DTO;

use DateTimeImmutable;

class SearchRideDTO
{

    public ?int $rideId = null;
    public ?int $driverId = null;

    public ?string $departurePlace = null;
    public ?string $arrivalPlace = null;

    public array $statuses = [];

    public ?DateTimeImmutable $arrivalDate = null;
    public ?DateTimeImmutable $departureDate = null;
    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;



    public function getRideId(): ?int
    {
        return $this->rideId;
    }

    public function getDriverId(): ?int
    {
        return $this->driverId;
    }

    public function getStatuses(): ?array
    {
        return $this->statuses;
    }

    public function getDeparturePlace(): ?string
    {
        return $this->departurePlace;
    }

    public function getArrivalPlace(): ?string
    {
        return $this->arrivalPlace;
    }

    public function getArrivalDate(): ?DateTimeImmutable
    {
        return $this->arrivalDate;
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
}
