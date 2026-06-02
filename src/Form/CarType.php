<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand')
            ->add('model')
            ->add('color')
            ->add('year')
            ->add('power')
            ->add('seats')
            ->add('registrationNumber')
            ->add('registrationDate')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('owner', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
