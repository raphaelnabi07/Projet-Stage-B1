<?php

namespace App\Form;

use App\Entity\Conge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('date_fin', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('type', ChoiceType::class, [
            'choices' => [
                'Courants' => [
                    'Congé Annuel' => 'Congé Annuel',
                    'RTT' => 'RTT',
                    'Repos Compensateur' => 'Repos Compensateur',
                ],
                'Santé' => [
                    'Maladie Ordinaire' => 'Maladie Ordinaire',
                    'Longue Maladie / Longue Durée' => 'CLM_CLD',
                    'Accident de Service' => 'Accident de Service',
                ],
                'Famille / Naissance' => [
                    'Maternité / Paternité' => 'Naissance',
                    'Enfant Malade' => 'Enfant Malade',
                    'Congé Parental' => 'Congé Parental',
                ],

            ],
            'placeholder' => 'Choisir le motif...',
            'attr' => ['class' => 'fr-select']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
