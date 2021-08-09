<?php

namespace App\Controller\Admin;

use App\Entity\Outings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class OutingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Outings::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')->onlyOnDetail(),
            AssociationField::new('author','Créateur'),
            TextField::new('nameOuting', 'Nom'),
            TextareaField::new('description', 'Description')->hideOnIndex(),
            DateField::new('dateHourOuting','Date de la sortie'),
            DateField::new('dateInscriptionLimit', 'Date limite d\'inscription'),
            IntegerField::new('spotNumber','Places'),
            AssociationField::new('members','Participants')->hideOnIndex(),
            IntegerField::new('duration','Durée')->hideOnIndex(),
            AssociationField::new('city','Villes'),
            AssociationField::new('status','Status'),
        ];
    }
}
