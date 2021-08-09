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
            ->add('Campus',EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => '- - -'
            ])
            ->add('nameContains',TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
            ])
            ->add('dateStart', DateType::class, [
                'label' => 'Entre',
                'format' => 'dd / MM / yyyy',
                'data' => new \DateTime(),
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'et',
                'format' => 'dd / MM / yyyy',
                'data' => new \DateTime(),
            ])
            ->add('choiceAuthor', CheckboxType::class, [
                'required' => false,
            ])
            ->add('choiceRegister', CheckboxType::class, [
                'required' => false,
            ])
            ->add('choiceNotRegister', CheckboxType::class, [
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
