<?php

namespace App\DTO;

use DateTimeImmutable;

class SearchBookingDTO
{
    public ?int $bookingId = null;
    public ?int $passengerId = null;

    public ?string $lastName = null;
    public ?string $email = null;

    public array $statuses = [];

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

    public function getStatuses(): ?array
    {
        return $this->statuses;
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
