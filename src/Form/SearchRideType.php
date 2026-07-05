<?php

namespace App\Form;

use App\Enum\RideStatus;
use App\DTO\SearchRideDTO;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class SearchRideType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $mode = $options['mode'];

        if ($mode === 'searchByClient') {
            $builder
                ->add('departureDate', DateType::class, [
                    'widget' => 'single_text',
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

        if ($mode === 'searchByStaff') {
            $builder
                ->add('id', TextType::class, [
                    'label' => 'Référence trajet',
                    'required' => false,
                ])
                ->add('status', EnumType::class, [
                    'class' => RideStatus::class,
                    'label' => 'Statut',
                    'choice_label' => fn(RideStatus $choice) => $choice->value,
                    'placeholder' => 'Choisir un statut',
                    'required' => false,
                ])
                ->add('departureDate', DateType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date de départ',
                    'required' => true,
                ])
                ->add('arrivalDate', DateType::class, [
                    'widget' => 'single_text',
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
                ->add('createdAt', DateType::class, [
                    'widget' => 'single_text',
                    'input' => 'datetime_immutable',
                    'label' => 'Date de création',
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchRideDTO::class,
            'mode' => null,
            'csrf_protection' => false,
        ]);
    }
}
