<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les mot de passes doivent correspondre.',
            'label' => false,
            'mapped' => false,
            'required' => true,
            'first_options'  => [
                'label' => false,
                'attr' => ['autocomplete' => 'new-password','class' => 'formInput', 'placeholder' => 'Saisir un mot de passe'],
                'constraints' => [  
                    new Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/', 'Le mot de passe doit contenir au minimum 12 caractères, une majuscule, une minuscule, un chiffre, un caractère spécial')
                ],
            ],
            'second_options' => [
                'label' => false,   
                'attr' => ['autocomplete' => 'new-password','class' => 'formInput', 'placeholder' => 'Répéter le mot de passe'],
            ],
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
