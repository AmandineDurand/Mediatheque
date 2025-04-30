<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\DemandeAbonnement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeAbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('abonnement', EntityType::class, [
                'class' => Abonnement::class,
                'choice_label' => function (Abonnement $abonnement) {
                    return $abonnement->getTypeAbo()->toString() . ' - ' . $abonnement->getPrixAbo() . ' €';
                },
                'label' => 'Choisir un abonnement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeAbonnement::class,
        ]);
    }
}