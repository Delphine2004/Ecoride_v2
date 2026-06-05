<?php

namespace App\Form;

use App\DTO\SearchRide;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SearchRideType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date de départ',
                'required' => true,
            ])
            ->add('departurePlace', TextType::class, [
                'label' => 'Adresse de départ',
                'required' => true,
            ])
            ->add('arrivalPlace', TextType::class, [
                'label' => 'Adresse d\'arrivée',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchRide::class,
            'mode' => null
        ]);
    }
}
