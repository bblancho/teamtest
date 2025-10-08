<?php

namespace App\Form;

use App\Entity\Offres;
use App\Entity\Skills;
use App\Entity\Societes;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Service\FormListenerFactoryService;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OffreType extends AbstractType
{
    public function __construct( private FormListenerFactoryService $listenerFactroy){
	
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'maxLength' => '100',
                    ],
                    'required' => true,
                    'label' => 'Titre de la mission :',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ],
                ]
            )
            ->add('skills', EntityType::class, [
                'class' => Skills::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true, //case à cocher
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Skills :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('slug', HiddenType::class, [
                'empty_data' => '',
            ])
            ->add(
                'description',
                TextareaType::class,
                [
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
                ]
            )
            ->add(
                'lieuMission',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'maxLength' => '50',
                    ],
                    'label' => 'Localisation :',
                    'label_attr' => [
                        'class' => 'form-label  mt-4'
                    ],
                ]
            )
            ->add(
                'tarif',
                MoneyType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'max' => 2000,
                        'type' => 'number',
                        'placeholder' => '0'
                    ],
                    'label' => 'TJM :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'currency' => 'EUR',
                ]
            )
            ->add(
                'duree',
                IntegerType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 1,
                        'max' => 24
                    ],
                    'label' => 'Durée en mois :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]
            )
            ->add(
                'contraintes',
                TextareaType::class,
                [
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
                ]
            )
            ->add(
                'profil',
                TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 5,
                        'rows'=> 6
                    ],
                    'label' => 'Profil recherché :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add(
                'experience',
                IntegerType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 1,
                        'max' => 15
                    ],
                    'label' => "Année d'expérience minimum :",
                    'label_attr' => [
                        'class' => 'form-label '
                    ],
                ]
            )
            ->add('startDateAT',
                DateType::class,
                [
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'Démarrage le :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-check-input',
                    ],
                    'label' => 'Publier ?',
                    'label_attr' => [
                        'class' => 'form-check-label'
                    ],
                ]
            )

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
