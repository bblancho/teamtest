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
use Symfony\Component\Validator\Constraints\File;
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
            ->add(
                'nom',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'maxLength' => '50',
                    ],
                    'label' => 'Nom / Prénom',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ]
                ]
            )
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '180',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('adresse', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('cp', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '5',
                    'maxLength' => '5',
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '10',
                    'placehorder' => "0145859657",
                ],
                'label' => 'Télèphone',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]
            ])
            ->add('siren', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Numéro de siren / siret, 9 ou 14 caractères.',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => "Ce champ est obligatoire."]),
                    new Assert\Length(min:9, max:14)
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'label' => ' Mot de passe Azerty24@',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ],
                    'constraints' => [
                        new Assert\NotBlank(['message' => "Ce champ est obligatoire."])
                    ]
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
                        new Assert\NotBlank(['message' => "Ce champ est obligatoire."]),
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
            ->add('agreeTerms', CheckboxType::class, [
                'priority' => 1,
                'mapped' => false,
                'label' => 'J\'accepte les termes et conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
