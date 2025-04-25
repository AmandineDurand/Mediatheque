<?php

namespace App\Form;

use App\Entity\Abonnement;

use App\Enum\TypeAbo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeAbo', ChoiceType::class, [
                'label' => 'Type d’abonnement',
                'choices' => [
                    'Mensuel' => TypeAbo::Mensuel,
                    'Annuel' => TypeAbo::Annuel,
                ],
                'choice_label' => fn ($choice) => $choice->toString(), 
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('prixAbo', NumberType::class, [
                'label' => 'Prix de l’abonnement (€)',
                'scale' => 2,
                'required' => false,
                'html5' => true,
                'attr' => [
                    'placeholder' => 'ex : 19,99',
                    'step' => '0.01',
                    'min' => 0,
                ],
            ]);
        
        $builder->get('prixAbo')->addModelTransformer(new CallbackTransformer(
            fn ($value) => $value !== null ? (float)$value : null,     // DB → Form
            fn ($value) => $value !== null ? number_format($value, 2, '.', '') : null // Form → DB
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
