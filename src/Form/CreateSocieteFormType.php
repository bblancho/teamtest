<?php

namespace App\Form;

use App\Entity\Societes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Regex;

class CreateSocieteFormType extends AbstractType
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
                'label' => "Raison sociale",
                'label_attr' => [
                    'class' => 'form-label'
                ]
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
                    'class' => 'form-label '
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '180',
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label'
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
                    'class' => 'form-label'
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
                    'class' => 'form-label'
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
                    'class' => 'form-label '
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '10',
                    'placeholder' => "0145859657",
                ],
                'label' => 'Télèphone',
                'label_attr' => [
                    'class' => 'form-label '
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5,
                    'rows'=> 6
                ],
                'label' => 'Description de la société',
                'label_attr' => [
                    'class' => 'form-label '
                ]
            ])
            ->add('nomContact', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '50',
                ],
                'label' => 'Nom du contact',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('numContact', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '10',
                ],
                'label' => "Numéro du contact",
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('siret', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Numéro de siret',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societes::class,
        ]);
    }
}
