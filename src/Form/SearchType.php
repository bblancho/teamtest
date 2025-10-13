<?php

namespace App\Form;

use App\Model\SearchModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'query',
                TextType::class,
                [
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'minLength' => '2',
                        'placeholder' => "Chercher une opportunité par mot clé...",
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'date_class' => SearchModel::class,
            "method" => "GET",
            // "allow_extra_fields" => true,
            'csrf_protection' => false
        ]);
    }

    // public function getBlockPrefix(): string
    // {
    //     return '';
    // }

}
