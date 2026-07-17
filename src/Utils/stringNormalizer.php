<?php

namespace App\Util;

final class StringNormalizer
{
    public static function normalize(?string $value): ?string
    {
        if ($value === null) {
            return null;
        } else {
            $value = trim(strtoupper($value));
            return $value;
        }
    }
}
