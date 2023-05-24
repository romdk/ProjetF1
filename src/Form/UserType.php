<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('isVerified')
            ->add('pseudonyme',TextType::class, [
                'label' => "Pseudonyme",
                'attr' => ['class' => 'formInput', 'placeholder' => "Saisir un nom d'utilisateur"]
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de profil',
                'attr' => ['class' => 'formInput', 'placeholder' => "Saisir un nom d'utilisateur"],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Upload un fichier valide'
                    ])
                ],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'formBtn'],
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
