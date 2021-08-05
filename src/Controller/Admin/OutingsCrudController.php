<?php

namespace App\Controller\Admin;

use App\Entity\Outings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('nameOuting', 'Nom'),
            TextField::new('description','Description'),
            DateField::new('dateHourOuting','Date de la sortie'),
            DateField::new('dateInscriptionLimit', 'Date limite d\'inscription'),
            IntegerField::new('spotNumber','Places'),
            TextField::new('city.name','Ville')->onlyOnIndex(),
            IntegerField::new('duration','Durée'),
        ];
    }
}
