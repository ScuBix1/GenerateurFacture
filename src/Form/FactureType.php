<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Entreprise;
use App\Entity\Facture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', null, [
                'attr' => [
                    'class' => 'border border-solid border-2 border-black',
                ],
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'border border-[2px] border-black',
                ],
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nomSociete',
                'attr' => [
                    'class' => 'border border-[2px] border-black',
                ],
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomSociete',
                'attr' => [
                    'class' => 'border border-[2px] border-black',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'attr' => [
                'class' => 'flex justify-around',
                'style' => 'flex-wrap: wrap;'
            ],
        ]);
    }
}
