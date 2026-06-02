<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = "ROLE_ADMIN";
    case EMPLOYEE = "ROLE_EMPLOYEE";
    case PASSENGER = "ROLE_PASSENGER";
    case DRIVER = "ROLE_DRIVER";
    case ANONYMIZED = "ROLE_ANONYMIZED";
}
