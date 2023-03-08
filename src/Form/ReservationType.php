<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Grandprix;
use App\Entity\Emplacement;
use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbPersonnes', IntegerType::class)
            ->add('session', TextType::class)
            ->add('user', EntityType::class, [
                'class' =>User::class
            ])
            ->add('grandprix', EntityType::class, [
                'class' =>Grandprix::class
            ])
            ->add('emplacement', EntityType::class, [
                'class' =>Emplacement::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
