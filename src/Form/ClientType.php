<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints as Assert;

class ClientType extends AbstractType
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
                    'class' => 'form-label'
                ],
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('cp', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '5',
                    'maxlenght' => '5',
                ],
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('ville', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    // 'maxlenght' => '10',
                ],
                'label' => 'Télèphone',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('tjm', MoneyType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'max' => 2000,
                    'type' => 'number',
                    'placeholder' => ''
                ],
                'currency' => 'EUR',
                'label' => 'Taux journalier :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
            ])
            ->add('siret', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '9',
                    'maxlenght' => '9',
                ],
                'label' => 'Numéro de siret',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 9,
                        'max' => 9,
                        'minMessage' => 'Le SIRET doit comporter {{ limit }} caractères au minimum.',
                        'maxMessage' => 'Le SIRET doit comporter {{ limit }} caractères au maximum.',
                    ]),
                ]
            ]) 
            ->add('cvFile', FileType::class,[
                'required'  => false,
                'mapped'    => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' =>' Déposer votre cv ',
                'label_attr' => [
                    'class' => 'form-label'
                ],
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
