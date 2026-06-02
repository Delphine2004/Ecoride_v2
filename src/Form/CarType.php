<?php

namespace App\Form;

use App\Entity\Car;
use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CarType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('brand', EnumType::class, [
                'class' => CarBrand::class,
                'label' => 'Marque',
                'choice_label' => fn(CarBrand $choice) => $choice->value,
                'placeholder' => 'Choisir',
                'required' => true,
            ])
            ->add('model', TextType::class, [
                'label' => 'Modèle',
                'required' => true,
            ])
            ->add('color', EnumType::class, [
                'class' => CarColor::class,
                'label' => 'Couleur',
                'choice_label' => fn(CarColor $choice) => $choice->value,
                'placeholder' => 'Choisir',
                'required' => true,
            ])
            ->add('year', TextType::class, [
                'label' => 'Année',
                'required' => true,
            ])
            ->add('power', EnumType::class, [
                'class' => CarPower::class,
                'label' => 'Energie',
                'choice_label' => fn(CarPower $choice) => $choice->value,
                'placeholder' => 'Choisir',
                'required' => true,
            ])
            ->add('seats', IntegerType::class, [
                'label' => 'Nombre total de siége',
                'required' => true,
            ])
            ->add('registrationNumber', TextType::class, [
                'label' => 'Numéro d\'immatriculation',
                'required' => true,
            ])
            ->add('registrationDate', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date d\'immatriculation',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
            'csrf_protection' => true,
        ]);
    }
}
