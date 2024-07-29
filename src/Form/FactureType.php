<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Entreprise;
use App\Entity\Facture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', TypeIntegerType::class, [])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nomSociete',
                'attr' => [
                    'class' => 'select_client',
                ],
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomSociete',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Séléctionner',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'attr' => [
                'class' => 'flex flex-wrap justify-around my-6',
            ],
        ]);
    }
}
