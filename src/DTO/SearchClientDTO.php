<?php

namespace App\DTO;

use App\Util\StringNormalizer;

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
        $this->lastName = StringNormalizer::normalize($this->lastName);
        $this->email = StringNormalizer::normalize($this->email);
    }
}
