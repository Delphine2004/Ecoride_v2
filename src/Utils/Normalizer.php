<?php

namespace App\Utils;

use DateTimeInterface;

final class Normalizer
{
    public static function normalizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        } else {
            $value = trim(strtoupper($value));
            return $value;
        }
    }


    public static function normalizeDate(?DateTimeInterface $value): ?\DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeImmutable) {
            return $value;
        }

        return \DateTimeImmutable::createFromMutable($value);
    }
}
