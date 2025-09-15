<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', 
            PasswordType::class, [
                'required' => true,
                'label' => 'Mot de passe actuel',
                'constraints' => [
                    new Assert\NotBlank(['message' => "Ce champ est obligatoire."]),
                    new UserPassword(['message' => "Le mot de passe courant est invalide."])
                ]
            ])
            ->add('plainPassword', 
            RepeatedType::class, 
            [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        // 'autocomplete' => 'Nouveau mot de passe',
                    ],
                ],
                'required' => true,
                'first_options' => [
                    // 'label' => '',
                ],
                'second_options' => [
                    // 'label' => 'Confirmez votre mot de passe',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => "Ce champ est obligatoire."]),
                    new Assert\Length([
                        'min' => 8,
                        'max' => 4096,
                        'minMessage' => 'Le mot de passe doit comporter plus de {{ limit }} caractères.',
                        'maxMessage' => 'Le mot de passe doit comporter au maximum de {{ limit }} caractères.',
                    ]),
                    new Regex(
                        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,20}$/",
                        "Veuillez respecter les conditions de validation du mot de passe."
                    )
                ],
                'invalid_message' => 'Les mots de passe doivent être identique.',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
