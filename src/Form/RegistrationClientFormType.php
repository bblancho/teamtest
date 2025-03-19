<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '180',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('adresse', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('cp', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '5',
                    'maxlenght' => '5',
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\Length(exactly: 5)
                ]
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '10',
                ],
                'label' => 'Télèphone',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\Length(exactly: 10)
                ]
            ])
            ->add('siren', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Numéro de siren',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' =>9,
                        'max'=> 9,
                        'exactMessage'=> 'Le numéro de siren doit faire {{ limit }} caractères.'
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => ' Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ],
                    'constraints' => [new Assert\NotBlank()]
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                    ]
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => "Ce champ est obligatoire."]),
                    new Length(
                        [
                        'min' => 6, 
                        'max' => 4096,
                        'minMessage' => 'Le mot de passe doit comporter plus de {{ limit }} caractères.',
                        'maxMessage' => 'Le mot de passe doit comporter au maximum de {{ limit }} caractères.',
                        ]
                    ),
                    new Regex(                                      
                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,20}$/",
                        "Votre mot de passe doit faire au minimum 8 et au maximum 20 caractères est contenir: 
                            Au moins une majuscule 
                            Au moins une minuscule 
                            Au moins un chiffre
                            Au moins un caractère spécial : #?!@$%^&*-
                        "
                    )
                ],
                'invalid_message' => 'Les mots de passe doivent être identique.',
            ])
            // ->add('cvFile', VichFileType::class,[
            //     'required'  => false,
            //     'mapped'    => false,
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'label' => 'Cv',
            //     'label_attr' => [
            //         'class' => 'form-label  mt-4'
            //     ],
            // ])
            // ->add('tjm', MoneyType::class, [
            //     'required' => false,
            //     'attr' => [
            //         'class' => 'form-control',
            //         'max' => 2000,
            //         'type' => 'number',
            //         'placeholder' => '0.00'
            //     ],
            //     'currency' => 'EUR',
            //     'required' => true,
            //     'label' => 'Taux journalier :',
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            //     'constraints' => [
            //         new Assert\Positive(),
            //         new Assert\LessThan(2000)
            //     ]
            // ])
            // ->add('dispo', CheckboxType::class, [
            //     'attr' => [
            //         'class' => 'form-check-input',
            //     ],
            //     'required' => false,
            //     'label' => "Disponible immédiatement",
            //     'label_attr' => [
            //         'class' => 'form-check-label'
            //     ],
            //     'constraints' => [
            //         new Assert\NotNull()
            //     ]
            // ])
            // ->add('dateDispoAt', null, [
            //        'required' => false,
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'required' => true,
            //     'label' => 'Date de disponibilité :',
            //     'widget' => 'single_text',
            // ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
