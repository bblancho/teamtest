<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'options' => [
                        'attr' => [
                            // 'autocomplete' => 'Nouveau mot de passe',
                        ],
                    ],
                    'first_options' => [
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Confirmez votre mot de passe',
                            ]),
                            new Length([
                                'min' => 8,
                                'minMessage' => 'Votre mot de passe doit comporter au moins  {{ limit }} caractères',
                                'max' => 4096,
                            ]),
                        ],
                        // 'label' => '',
                    ],
                    'second_options' => [
                        // 'label' => 'Confirmez votre mot de passe',
                    ],
                    'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                    'mapped' => false,
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
        $resolver->setDefaults([]);
    }
}
