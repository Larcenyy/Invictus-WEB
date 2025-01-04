<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('registerEmail', EmailType::class,
            [
                'attr' => [
                    'minlenght' => 3,
                    'maxlenght' => 45,
                    'placeholder' => 'Votre adresse email',
                ],
                'label' => false,
                'required' => true,
                'constraints' => [
                    new Assert\Email([
                        'message' => 'Veuillez entrer une adresse email valide.',
                    ]),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 45,
                        'minMessage' => 'L\'adresse email doit contenir au minimum {{ limit }} caractères.',
                        'maxMessage' => 'L\'adresse email ne peut pas dépasser {{ limit }} caractères.'
                    ]),
                ]
            ])
            ->add('registerUsername', TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'id' => 'username-first-login',
                        'placeholder' => "Nom d'utilisateur (va servir pour vous connectez sur MTA)",
                    ],
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 2,
                            'max' => 30,
                            'minMessage' => 'Le prénom doit contenir au minimum {{ limit }} caractères.',
                            'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
                        ]),
                    ]
                ])
            ->add('registerPassword', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'label' => false,
                    'invalid_message' => 'Les deux mots de passe doivent être identiques.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'first_options'  => [
                        'label' => false,
                        'attr' => ['placeholder' => 'Mot de passe']
                    ],
                    'second_options' =>
                    [
                        'label' => false,
                        'attr' => ['placeholder' => 'Confirmer le mot de passe', 'minlenght' => 8, 'maxlenght' => 60]
                    ],
                    'required' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères',
                            'max' => 50,
                            'min' => 8,
                        ]),
                        new Regex([
                            'pattern' => '/(?=.*[A-Z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).+/',
                            'message' => 'Le mot de passe doit contenir au minimum une majuscule, un chiffre et un caractère spécial'
                        ])
                    ]
                ])
            ->add('registerDiscord', TextType::class,
                [
                    'label' => false,
                    'attr' => [
                        'placeholder' => "Discord ID (Nécessaire pour la suite)",
                    ],
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 2,
                            'max' => 30,
                            'minMessage' => 'Le prénom doit contenir au minimum {{ limit }} caractères.',
                            'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.'
                        ]),
                    ]
                ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon inscriptions'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
