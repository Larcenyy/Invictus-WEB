<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class,
        [
            'attr' => [
                'minlenght' => 3,
                'maxlenght' => 45
            ],
            'required' => true,
            'label' => 'Adresse email',
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
            ->add('roles')
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
