<?php

namespace App\Form;

use App\Entity\Skills;
use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClientType extends AbstractType
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
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'adresse', 
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'maxLength' => '50',
                    ],
                    'label' => 'Adresse',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'cp', 
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '5',
                        'maxLength' => '5',
                    ],
                    'label' => 'Code postal',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]
            )
            ->add(
                'ville',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'maxLength' => '50',
                    ],
                    'label' => 'Ville',
                    'label_attr' => [
                        'class' => 'form-label mt-0'
                    ],
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'exactly'   => '10',
                    ],
                    'label' => 'Téléphone',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'tjm',
                MoneyType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'max' => 2000,
                        'type' => 'number'
                    ],
                    'currency' => 'EUR',
                    'label' => 'Taux journalier :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'cvFile',
                FileType::class,
                [
                    'required'  => true,
                    'mapped'    => false,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' =>' Déposer votre cv ',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                        ])
                    ]
                ],
            )
            // ->add('skills', EntityType::class, [
            //     'class' => Skills::class,
            //     'choice_label' => 'nom',
            //     'multiple' => true,
            //     'expanded' => true, //case à cocher
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'required' => false,
            //     'label' => 'Skills :',
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            // ])
            ->add(
                'isNewsletter',
                CheckboxType::class,
                [
                    'required' => false,
                    'empty_data' => '',
                    'attr' => [
                        'class' => 'form-check-input ',
                    ],
                    'label' => "S'inscrire à la newsletter ?",
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ],
                ]
            )
            // ->add('siren', TextType::class, [
                //     'mapped' => false,
                //     'attr' => [
                //         'class' => 'form-control',
                //         'disabled' => true,
                //     ],
                //     'label' => 'Numéro de siren',
                //     'label_attr' => [
                //         'class' => 'form-label'
                //     ]
            // ]) 
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
