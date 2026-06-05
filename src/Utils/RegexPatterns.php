<?php

namespace App\Utils;

class RegexPatterns
{
    public const ONLY_TEXT_REGEX = '/^[a-zA-ZÀ-ÿ\s\'-]{1,100}$/u';
    public const FREE_TEXT_REGEX = '/^[a-zA-ZÀ-ÿ0-9\s\'".,;:!?()-]{1,255}$/u';

    public const YEAR_REGEX = '/^(19|20)\d{2}$/u';

    public const OLD_REGISTRATION_NUMBER = '/^[1-9]\d{0,3}\s?[A-Z]{1,3}\s?(?:0[1-9]|[1-8]\d|9[0-5]|2[AB])$/';
    public const NEW_REGISTRATION_NUMBER = '/^[A-Z]{2}\d{3}[A-Z]{2}$/';

    public const OLD_LICENCE_NUMBER = '/^[0-9]{8,12}$/';
    public const NEW_LICENCE_NUMBER = '/^[0-9A-Z]{13}$/';

    public const LOGIN = '/^[a-zA-Z0-9\s\-]{8,25}$/u';
    public const PASSWORD = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{12,}$/';
}
