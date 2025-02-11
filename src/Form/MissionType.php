<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\Missions;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Service\FormListenerFactoryService;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MissionType extends AbstractType
{
    public function __construct( private FormListenerFactoryService $listenerFactroy){
	
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                ],
                'required' => true,
                'label' => 'Titre de la mission :',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add('slug', HiddenType::class, [
                'empty_data' => '',
            ])
            ->add('refMission', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Ref annonce :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 5,
                    'rows'=> 6
                ],
                'required' => true,
                'label' => 'Description de la mission :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('lieuMission', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '50',
                ],
                'label' => 'Localisation :',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('tarif', MoneyType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'max' => 2000,
                    'type' => 'number',
                    'placeholder' => '0'
                ],
                'label' => 'Budget de la mission :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(2000)
                ]
            ])
            ->add('duree', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 24
                ],
                'label' => 'Durée de la mission en mois :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(24)
                ]
            ])
            ->add('contraintes', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 5,
                    'rows'=> 6
                ],
                'label' => "Contraintes de la mission  :",
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('profil', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 5,
                    'rows'=> 6
                ],
                'label' => 'Profil recherché :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('experience', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 15
                ],
                'label' => "Nombre d'année d'expérience minimum :",
                'label_attr' => [
                    'class' => 'form-label '
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(15)
                ]
            ])
            ->add('startDateAT', null, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Date de début de mission :',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'widget' => 'single_text',
            ])
            ->add('isActive', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Publier ?',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])

            ->addEventListener( FormEvents::PRE_SUBMIT, $this->listenerFactroy->autoSlug("nom") ) 
            ->addEventListener( FormEvents::POST_SUBMIT, $this->listenerFactroy->timestamp() ) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offres::class,
        ]);
    }
}
