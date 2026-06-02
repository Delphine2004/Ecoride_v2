<?php

namespace App\Form;

use App\Entity\Ride;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RideType extends AbstractType
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
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date d\'arrivée',
                'required' => true,
            ])
            ->add('arrivalPlace', TextType::class, [
                'label' => 'Adresse d\'arrivée',
                'required' => true,
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency'    => 'EUR',
                'scale'       => 2,
                'required'    => true,
            ])
            ->add('availableSeats', IntegerType::class, [
                'label' => 'Nombre de place',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
            'csrf_protection' => true,
        ]);
    }
}
