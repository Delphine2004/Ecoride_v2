<?php

namespace App\DTO;

use App\Enum\RideStatus;
use DateTimeImmutable;

class SearchRide
{

    public ?int $rideId = null;
    public ?int $driverId = null;

    public ?string $departurePlace = null;
    public ?string $arrivalPlace = null;

    public ?RideStatus $status = null;

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

    public function getStatus(): ?RideStatus
    {
        return $this->status;
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
