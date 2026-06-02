<?php

namespace App\Form;

use App\Entity\Ride;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departureDate', null, [
                'widget' => 'single_text',
            ])
            ->add('departurePlace')
            ->add('arrivalDate', null, [
                'widget' => 'single_text',
            ])
            ->add('arrivalPlace')
            ->add('price')
            ->add('availableSeats')
            ->add('status')
            ->add('commission')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('driver', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('ride', EntityType::class, [
                'class' => Ride::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
        ]);
    }
}
