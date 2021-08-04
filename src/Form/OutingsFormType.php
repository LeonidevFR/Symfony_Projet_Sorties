<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Outings;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                'label' => 'Nom de la sortie : ',
            ])
            ->add('dateHourOuting', DateType::class, [
                'html5' => true,
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime(),
                'label' => 'Date et heure de la sortie : ',
            ])
            ->add('dateInscriptionLimit', DateType::class, [
                'html5' => true,
                'label' => 'Date limite d\'inscription : ',
            ])
            ->add('spotNumber', TextType::class, [
                'label' => 'Nombre de place : ',
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée : ',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos : ',
            ])
            ->add('Campus', EntityType::class,[
                'class' =>Campus::class, 'choice_label'=> 'name'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville : ',
            ])
            ->add('place', TextType::class, [
                'label' => 'Lieu : ',
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude : ',
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude : ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        'data_class' => Outings::class
        ]);
    }
}
