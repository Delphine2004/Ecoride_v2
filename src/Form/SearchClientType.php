<?php

namespace App\Form;

use App\DTO\SearchClientDTO;
use App\Enum\UserRole;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class SearchClientType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder
            ->add('id', TextType::class, [
                'label' => 'Référence client',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom du passager',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'Email du passager',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchClientDTO::class,
            'mode' => null,
            'user' => null,
            'csrf_protection' => false,
        ]);
    }
}
