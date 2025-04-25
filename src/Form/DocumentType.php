<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Document;
use App\Entity\Livre;
use App\Entity\Periodique;
use App\Entity\Sonore;
use App\Entity\Video;
use App\Enum\FormatVid;  
use App\Enum\FormatSon;
use App\Enum\Frequence;    
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];
        $documentType = $options['document_type'];

        $builder
            ->add('url', UrlType::class, [
                'label' => 'Lien',
                'required' => true
            ])
            ->add('titreDoc', TextType::class, [
                'label' => 'Titre',
                'required' => true
            ])
            ->add('anneeSortie', null, [
                'label' => 'Année de sortie',
                'widget' => 'single_text',
                'required' => true
            ])
            ->add('resumeDoc', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('stockDoc', NumberType::class, [
                'label' => 'Stock disponible',
                'required' => true
            ])
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'choice_label' => 'nomAut',
                'label' => 'Auteur',
                'required' => true
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomcat',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Catégories'
            ]);

        if (!$isEdit) {
            $builder->add('type', ChoiceType::class, [
                'choices' => [
                    'Livre' => 'livre',
                    'Périodique' => 'periodique',
                    'Document sonore' => 'sonore',
                    'Vidéo' => 'video'
                ],
                'mapped' => false,
                'label' => 'Type de document'
            ]);

            $builder
                ->add('ISBN', TextType::class, ['required' => false, 'mapped' => false, 'label' => 'ISBN'])
                ->add('nbPages', NumberType::class, ['required' => false, 'mapped' => false, 'label' => 'Nombre de pages'])
                ->add('frequence', ChoiceType::class, [
                    'choices' => [
                        'Mensuel' => 'mensuel',
                        'Journalier' => 'journalier',
                        'Annuel' => 'annuel',
                        'Semestriel' => 'semestriel'
                    ],
                    'required' => false,
                    'mapped' => false,
                    'label' => 'Fréquence'
                ])
                ->add('numero', NumberType::class, ['required' => false, 'mapped' => false, 'label' => 'Numéro'])
                ->add('dureeSon', NumberType::class, ['required' => false, 'mapped' => false, 'label' => 'Durée sonore'])
                ->add('formatSon', ChoiceType::class, [
                    'choices' => [
                        'MP3' => 'MP3',
                        'WAV' => 'WAV'
                    ],
                    'required' => false,
                    'mapped' => false,
                    'label' => 'Format sonore'
                ])
                ->add('dureeVid', NumberType::class, ['required' => false, 'mapped' => false, 'label' => 'Durée de la vidéo'])
                ->add('formatVid', ChoiceType::class, [
                    'choices' => [
                        'MP4' => 'MP4',
                        'M4V' => 'M4V'
                    ],
                    'required' => false,
                    'mapped' => false,
                    'label' => 'Format de la vidéo'
                ]);
        } else {
            // En édition, ajouter directement les champs spécifiques au type
            switch ($documentType) {
                case 'livre':
                    $builder->add('ISBN', TextType::class, [
                        'label' => 'ISBN',
                        'required' => true
                    ])
                    ->add('nbPages', NumberType::class, [
                        'label' => 'Nombre de pages',
                        'required' => true
                    ]);
                    break;
                case 'periodique':
                    $builder->add('frequence', ChoiceType::class, [
                        'choices' => Frequence::cases(), // <- la liste des valeurs possibles
                        'choice_label' => fn(Frequence $choice) => $choice->name, // ce qui s'affiche dans le menu déroulant
                        'choice_value' => fn(?Frequence $choice) => $choice?->value, // <- la valeur envoyée dans le formulaire
                        'placeholder' => 'Choisissez une fréquence',
                        'required' => true, // ou false si le champ peut être vide
                        'label' => 'Fréquence',
                    ])
                    ->add('numero', NumberType::class, [
                        'label' => 'Numéro',
                        'required' => true
                    ]);
                    break;
                case 'sonore':
                    $builder->add('dureeSon', NumberType::class, [
                        'label' => 'Durée (minutes)',
                        'required' => true
                    ])
                    ->add('formatSon', ChoiceType::class, [
                        'choices' => FormatSon::cases(), // <- la liste des valeurs possibles
                        'choice_label' => fn(FormatSon $choice) => $choice->name, // ce qui s'affiche dans le menu déroulant
                        'choice_value' => fn(?FormatSon $choice) => $choice?->value, // <- la valeur envoyée dans le formulaire
                        'placeholder' => 'Choisissez un format',
                        'required' => true, // ou false si le champ peut être vide
                        'label' => 'Format Vidéo',
                    ]);
                    break;
                case 'video':
                    $builder->add('dureeVid', NumberType::class, [
                        'label' => 'Durée (minutes)',
                        'required' => true
                    ])
                    ->add('formatVid', ChoiceType::class, [
                        'choices' => FormatVid::cases(), // <- la liste des valeurs possibles
                        'choice_label' => fn(FormatVid $choice) => $choice->name, // ce qui s'affiche dans le menu déroulant
                        'choice_value' => fn(?FormatVid $choice) => $choice?->value, // <- la valeur envoyée dans le formulaire
                        'placeholder' => 'Choisissez un format',
                        'required' => true, // ou false si le champ peut être vide
                        'label' => 'Format Vidéo',
                    ]);
                    break;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'is_edit' => false,
            'document_type' => null
        ]);
    }
}
