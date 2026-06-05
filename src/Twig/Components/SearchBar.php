<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\Component\Form\FormView;

#[AsTwigComponent]
final class SearchBar
{
    public FormView $form;
}
