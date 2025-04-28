<?php

namespace App\Form;

use App\Entity\Notification;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objetNotif', TextType::class, [
                'label' => 'Objet',
                'required' => true
            ])
            ->add('contenuNotif', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => true,
            ])
            ->add('utilisateurs', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'prenomUtil',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Utilisateurs concernés'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notification::class,
        ]);
    }
}
