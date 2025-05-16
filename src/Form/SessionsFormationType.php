<?php

namespace App\Form;

use App\Entity\SessionFormation;
use App\Entity\Formation;
use App\Entity\Projet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('DateFin', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('Cout', IntegerType::class, [
                'label' => 'Coût',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('IdFormation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'libelle',
                'label' => 'Formation',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('IdProjet', EntityType::class, [
                'class' => Projet::class,
                'choice_label' => 'Nom',
                'label' => 'Projet',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SessionFormation::class,
        ]);
    }
}
