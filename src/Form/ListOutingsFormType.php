<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListOutingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus',EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('nameContains',TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
            ])
            ->add('dateStart', DateType::class, [
                'label' => 'Entre',
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                'label' => 'et',
                'data' => new \DateTime(),
            ])
            ->add('choiceAuthor', CheckboxType::class, [
                'required' => false,
            ])
            ->add('choiceRegistered', CheckboxType::class, [
                'required' => false,
            ])
            ->add('choiceNotRegistered', CheckboxType::class, [
                'required' => false,
            ])
            ->add('choiceFinished', CheckboxType::class, [
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
