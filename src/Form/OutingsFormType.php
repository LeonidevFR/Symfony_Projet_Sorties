<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outings;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class OutingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameOuting', TextType::class, [
                'label' => 'Name de la sortie : ',
            ])
            ->add('dateHourOuting', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de la sortie : ',
                'data' => null,
                'attr' => ['class' => 'dateHourOuting'],
                'by_reference' => true,
            ])
            ->add('dateInscriptionLimit', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription : ',
                'attr' => ['class' => 'dateInscriptionLimit'],
                'by_reference' => true,
            ])
            ->add('spotNumber', NumberType::class, [
                'html5' => true,
                'label' => 'Nombre de place : ',
            ])
            ->add('duration', NumberType::class, [
                'html5' => true,
                'label' => 'Durée : ',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos : ',
                'attr' => ['rows' => 5, 'cols' => 33, 'wrap' => 'hard'],
            ])
            ->add('Campus', EntityType::class,[
                'class' =>Campus::class, 'choice_label'=> 'name',
                'label' => 'Campus : ',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        'data_class' => Outings::class
        ]);
    }
}
