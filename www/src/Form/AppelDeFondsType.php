<?php

namespace App\Form;

use App\Entity\AppelDeFonds;
use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppelDeFondsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateEmission', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'émission',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Montant', IntegerType::class, [
                'label' => 'Montant',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('DatePaiement', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de paiement (facultative)',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('IdProjet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'nom',
                'label' => 'Projet associé',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AppelDeFonds::class,
        ]);
    }
}
