<?php

namespace App\Form;

use App\Entity\Contentieux;
use App\Entity\Document;
use App\Enum\TypeCont;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentieuxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeCont', ChoiceType::class, [
                'choices' => [
                    'Dégradation' => TypeCont::Degradation,
                ],
                'disabled' => true,
            ])
            ->add('nbDoc', IntegerType::class, [
                'label' => 'Nombre de documents dégradés',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'id' => 'nbDoc',
                    'min' => 1,
                    'max' => $options['nbDocMax'],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contentieux::class,
            'nbDocMax' => 6,
        ]);
    }
}
