<?php

namespace App\Form;

use App\Entity\users;
use App\Entity\Offres;
use App\Entity\Skills;
use App\Entity\Clients;
use App\Repository\SkillsRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Service\FormListenerFactoryService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SkillsType extends AbstractType
{
    public function __construct( private FormListenerFactoryService $listenerFactroy){
	
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minLength' => '2',
                    'maxLength' => '100',
                ],
                'required' => true,
                'label' => 'Nom de la compétence :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 100])
                ]
            ])
            ->add(
                'content',
                TextareaType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'min' => 5,
                        'rows'=> 6
                    ],
                    'required' => false,
                    'label' => 'Description :',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ]
            )
            ->add('slug', HiddenType::class, [
                'empty_data' => '',
            ])
            ->add('parent', EntityType::class, [
                'class' => Skills::class,
                'choice_label' => 'nom',
                'placeholder'  => "-- Pas de parent --",
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Parent :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'query_builder' => function(SkillsRepository $sk){
                    return $sk->createQueryBuilder('s')
                        ->orderBy('s.nom', 'ASC');
                }
            ])

            ->addEventListener( FormEvents::PRE_SUBMIT, $this->listenerFactroy->autoSlug("nom") ) 
            ->addEventListener( FormEvents::POST_SUBMIT, $this->listenerFactroy->timestamp() ) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skills::class,
        ]);
    }
}
