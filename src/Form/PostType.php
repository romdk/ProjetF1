<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Grandprix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;   
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextType::class, [
                'attr' => ['class' => 'messageBox'] 
            ])
            // ->add('dateCreation', DateTimeType::class)
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id'
            // ])
            // ->add('grandprix', EntityType::class, [
            //     'class' => Grandprix::class,
            //     'choice_label' => 'id'
            // ])
            ->add('ajouter', SubmitType::class, [
                'attr' => ['class' => 'btnAjouter']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
