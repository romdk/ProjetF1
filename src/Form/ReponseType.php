<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('message', TextareaType::class, [
            'label' => false,
            'attr' => ['class' => 'messageBox','Maxlength' => 255,]
        ])
        // ajoute au formulaire un champ caché qui correspond à l'id du post auquel on souhaite répondre
        ->add('postId', HiddenType::class, [
            'mapped' => false
        ])
        ->add('ajouter', SubmitType::class, [
            'label' => 'Répondre',
            'attr' => ['class' => 'btnAjouter']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
