<?php

namespace App\Form;

use App\Entity\Skills;
use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class CompetencesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('skills', 
                EntityType::class, 
                [
                    'class' => Skills::class,
                    'choice_label' => 'nom',
                    'multiple' => true,
                    'expanded' => true, //case à cocher
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'required' => false,
                    'label' => 'Mes compétences :',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]
            )
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
