<?php

namespace App\Twig\Components;

use App\Entity\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsTwigComponent]
final class UserCard
{
    use DefaultActionTrait;

    public User $user;
}
