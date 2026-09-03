<?php

namespace App\DTO;

use App\Utils\Normalizer;

class SearchClientDTO
{

    public ?int $id = null;
    public ?string $lastName = null;
    public ?string $email = null;

    public function getUserId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function normalize(): void
    {
        $this->lastName = Normalizer::normalizeString($this->lastName);
        $this->email = Normalizer::normalizeString($this->email);
    }
}
