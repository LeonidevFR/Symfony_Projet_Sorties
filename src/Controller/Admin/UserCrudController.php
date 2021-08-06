<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    /*
     * Mettre une valeur par défaut pour le formulaire de création
    public function createEntity(string $entityFqcn): User
    {
        $user = new User();
        $user->setActive(true)
            ->setAvatar("imagedemerde.png");
        return $user;
    }
    */

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
            TextField::new('email', 'E-mail'),
            TextField::new('pseudo','Pseudo'),
            TextField::new('firstName','Prénom')->hideOnForm(),
            TextField::new('lastName','Nom')->hideOnForm(),
            TextField::new('phoneNumber','N° de téléphone')->hideOnIndex(),
            TextField::new('avatar','Avatar')->onlyWhenUpdating(),
            AssociationField::new('campus','Campus'),
            ChoiceField::new('roles', 'Roles')
                ->allowMultipleChoices()
                ->setChoices(['Admin' => 'ROLE_ADMIN'])
                ->onlyOnForms()
                ->setRequired(false),
            ChoiceField::new('roles', 'Roles')
                ->setChoices(['Admin' => 'ROLE_ADMIN', 'Utilisateurs' => 'ROLE_USER'])
                ->onlyOnIndex(),

        ];
    }
}
