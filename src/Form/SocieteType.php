<?php

namespace App\Form;

use App\Entity\Societes;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => 'Dénomination sociale',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
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
                    'class' => 'form-label mt-4'
                ],
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
                    'class' => 'form-label mt-4'
                ],
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
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '10',
                ],
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'rows'=> 6
                ],
                'label' => 'Description de la société',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('isNewsletter', CheckboxType::class, [
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-check-input ',
                ],
                'label' => "S'inscrire à la newsletter ?",
                'label_attr' => [
                    'class' => 'form-check-label '
                ],
            ]) 
            ->add('nomContact',TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control ',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => 'Nom du contact',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('phoneContact', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-2',
                    'minLength' => '2',
                ],
                'label' => 'Téléphone du contact:',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('secteurActivite', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => "Secteur d'activité",
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            // ->add('siret', TextType::class, [
                //     'required' => true,
                //     'attr' => [
                //         'class' => 'form-control',
                //         'minLength' => '14',
                //         'maxLength' => '14',
                //     ],
                //     'label' => 'Numéro de siret',
                //     'label_attr' => [
                //         'class' => 'form-label'
                //     ],
                // ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societes::class,
        ]);
    }
}
