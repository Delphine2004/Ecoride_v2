<?php

namespace App\Form;

use App\Entity\User;

use App\Enum\CarBrand;
use App\Enum\CarColor;
use App\Enum\CarPower;

use App\Utils\RegexPatterns;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\File;


class UserType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $mode = $options['mode'];

        if ($mode === 'createUser') {
            $builder
                ->add('login', TextType::class, [
                    'label' => 'Nom utilisateur',
                    'required' => true,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => true,
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                    ],
                    'label' => false,
                    'required' => true,
                    'mapped' => false,
                    'constraints' => [
                        new Assert\NotBlank(message: 'Le mot de passe est obligatoire.'),
                        new Assert\Length(
                            max: 255,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
                        ),
                        new Assert\Regex(
                            pattern: RegexPatterns::PASSWORD,
                            message: 'Le mot de passe doit contenir au moins 12 caractères incluant une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ),
                    ],
                ])
            ;
        }

        if ($mode === 'registration') {
            $builder
                ->add('firstName', TextType::class, [
                    'label' => 'Prénom',
                    'required' => true,
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'Nom',
                    'required' => true,
                ])
                ->add('login', TextType::class, [
                    'label' => 'Nom utilisateur',
                    'required' => true,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => true,
                ])
                ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue(
                            message: 'You should agree to our terms.',
                        ),
                    ],
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                    ],
                    'label' => false,
                    'required' => true,
                    'mapped' => false, // n'est pas mappé avec la bd car il sera hashé
                    'constraints' => [
                        new Assert\NotBlank(message: 'Le mot de passe est obligatoire.'),
                        new Assert\Length(
                            max: 255,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                        ),
                        new Assert\Regex(
                            pattern: RegexPatterns::PASSWORD,
                            message: 'Le mot de passe doit contenir au moins 12 caractères incluant une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ),
                    ],
                ])
            ;
        }

        if ($mode === 'updateUser') {
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                    ],
                    'label' => false,
                    'required' => true,
                    'mapped' => false, // n'est pas mappé avec la bd car il sera hashé
                    'constraints' => [
                        new Assert\NotBlank(message: 'Le mot de passe est obligatoire.'),
                        new Assert\Length(
                            max: 255,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                        ),
                        new Assert\Regex(
                            pattern: RegexPatterns::PASSWORD,
                            message: 'Le mot de passe doit contenir au moins 12 caractères incluant une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ),
                    ],
                ]);
        }

        if ($mode === 'updateClient') {
            $builder
                ->add('firstName', TextType::class, [
                    'label' => 'Prénom',
                    'required' => false,
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'Nom',
                    'required' => false,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => false,
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                    ],
                    'label' => false,
                    'required' => false,
                    'mapped' => false, // n'est pas mappé avec la bd car il sera hashé
                    'constraints' => [
                        new Assert\NotBlank(message: 'Le mot de passe est obligatoire.'),
                        new Assert\Length(
                            max: 255,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                        ),
                        new Assert\Regex(
                            pattern: RegexPatterns::PASSWORD,
                            message: 'Le mot de passe doit contenir au moins 12 caractères incluant une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ),
                    ],
                ]);
        }

        if ($mode === 'updateAdmin') {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => false,
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'label' => 'Mot de passe',
                    ],
                    'second_options' => [
                        'label' => 'Confirmer le mot de passe',
                    ],
                    'label' => false,
                    'required' => true,
                    'mapped' => false, // n'est pas mappé avec la bd car il sera hashé
                    'constraints' => [
                        new Assert\NotBlank(message: 'Le mot de passe est obligatoire.'),
                        new Assert\Length(
                            max: 255,
                            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.',
                        ),
                        new Assert\Regex(
                            pattern: RegexPatterns::PASSWORD,
                            message: 'Le mot de passe doit contenir au moins 12 caractères incluant une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ),
                    ],
                ]);
        }

        if ($mode === 'updateClientByStaff') {
            $builder
                ->add('firstName', TextType::class, [
                    'label' => 'Prénom',
                    'required' => false,
                ])
                ->add('lastName', TextType::class, [
                    'label' => 'Nom',
                    'required' => false,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => false,
                ]);
        }

        if ($mode === 'updateUserByAdmin') {
            $builder
                ->add('login', TextType::class, [
                    'label' => 'Nom utilisateur',
                    'required' => false,
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'required' => false,
                ]);
        }

        if ($mode === 'updatePicture') {
            $builder
                ->add('picture', FileType::class, [
                    'label' => 'Photo',
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new File(
                            maxSize: '10M',
                            mimeTypes: [
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ],
                            mimeTypesMessage: 'Merci d’uploader une image valide (jpeg, png, webp)',
                        )
                    ],
                ]);
        }

        if ($mode === 'updateCredit') {
            $builder
                ->add('credit', MoneyType::class, [
                    'label' => 'Prix',
                    'currency'    => 'EUR',
                    'scale'       => 2,
                    'required'    => true,
                ]);
        }

        if ($mode === 'becomeDriver') {
            $builder
                ->add('licence', TextType::class, [
                    'label' => 'N° de permis',
                    'required' => true,
                ])
                ->add('cars', CollectionType::class, [
                    'entry_type' => CarType::class,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'mode' => null,
        ]);
    }
}
