<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Ride;

use App\Repository\CarRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RideType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $user = $options['user'];

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
            ->add('car', EntityType::class, [
                'class' => Car::class,
                'choice_label' => 'model',
                'placeholder' => 'Choisir',
                'label' => 'Voiture',
                'required' => true,
                'query_builder' => function (CarRepository $carRepository) use ($user) {
                    return $carRepository->createQueryBuilder('c')
                        ->where('c.owner = :user')
                        ->setParameter('user', $user);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
            'user' => null,
            'csrf_protection' => true,
        ]);
    }
}
