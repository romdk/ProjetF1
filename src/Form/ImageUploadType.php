<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImageUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // choix de la categorie de l'image
            ->add('categorie', ChoiceType::class, [
                'label' => "Categorie de l'image",
                'required' => false,
                "choices"  => [
                    'circuits' => 'circuits',
                    'ecuries' => 'ecuries',
                    'grandprix' => 'grandprix',
                    'pilotes' => 'pilotes',
                    'voitures' => 'voitures',
                ]
            ])

            // upload image circuit
            ->add('circuitName',TextType::class, [
                'label' => "Nom du circuit",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: yas_marina"]
            ])
            ->add('imageCircuitType', ChoiceType::class, [
                'label' => "Type d'image",
                'required' => false,
                "choices"  => [
                    'layout' => 'layout',
                    'map' => 'map',
                ]
            ])

            // upload image ecurie
            ->add('ecurieName',TextType::class, [
                'label' => "Nom d'écurie",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: aston_martin"]
            ])
            ->add('ecurieImageType', ChoiceType::class, [
                'label' => "Type d'image",
                'required' => false,
                "choices"  => [
                    'Portrait' => true,
                    'Paysage' => 'Logo_ecurie_',
                ]
            ])

            // upload image grandprix
            ->add('grandprixYear',TextType::class, [
                'label' => "Saison",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: 2023"]
            ])
            ->add('grandprixRound',TextType::class, [
                'label' => "Round",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: 12"]
            ])
            ->add('grandprixImageType', ChoiceType::class, [
                'label' => "Type d'image",
                'required' => false,
                "choices"  => [
                    'bannière' => '_header',
                    'poster' => true,
                ]
            ])

            // upload image pilote
            ->add('driverName',TextType::class, [
                'label' => "Nom",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: alonso"]
            ])
            ->add('driverImageType', ChoiceType::class, [
                'label' => "Type d'image",
                'required' => false,
                "choices"  => [
                    'casque' => '_casque',
                    'portrait' => '_portrait',
                    'numero' => true,
                ]
            ])
            ->add('driverNumber',TextType::class, [
                'label' => "Numéro",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: 12"]
            ])

            // upload image voiture
            ->add('teamName',TextType::class, [
                'label' => "Ecurie",
                'required' => false,
                'attr' => ['class' => 'formInput', 'placeholder' => "Exemple: red_bull"]
            ])

            // choix du fichier à uploader
            ->add('image', FileType::class, [
                'label' => 'Image',
                'attr' => ['class' => 'formInput'],
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
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
            // Configure your form options here
        ]);
    }
}
