<?php

namespace App\DTO;

use App\Enum\BookingStatus;
use DateTimeImmutable;

class SearchBooking
{
    public ?int $bookingId = null;
    public ?int $passengerId = null;

    public ?string $lastName = null;
    public ?string $email = null;

    public ?BookingStatus $status = null;

    public ?DateTimeImmutable $createdAt = null;
    public ?DateTimeImmutable $updatedAt = null;



    public function getBookingId(): ?int
    {
        return $this->bookingId;
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

    public function getStatus(): ?BookingStatus
    {
        return $this->status;
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
