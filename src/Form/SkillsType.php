<?php

namespace App\Form;

use App\Entity\users;
use App\Entity\Offres;
use App\Entity\Skills;
use App\Entity\Clients;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use App\Service\FormListenerFactoryService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

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

            ->add('parent', EntityType::class, [
                'class' => Skills::class,
                'choice_label' => 'id',
            ])

            // ->addEventListener( FormEvents::PRE_SUBMIT, $this->listenerFactroy->autoSlug("nom") ) 
            // ->addEventListener( FormEvents::POST_SUBMIT, $this->listenerFactroy->timestamp() ) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skills::class,
        ]);
    }
}
