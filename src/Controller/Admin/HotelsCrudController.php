<?php

namespace App\Controller\Admin;

use App\Entity\Hotels;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HotelsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hotels::class;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setSearchFields(['name', 'city'])
            ->setPageTitle('detail', fn (Hotels $hotels) => (string) $hotels->getName())
            ->setEntityLabelInSingular('Hôtel')
            ->setEntityLabelInPlural('Hôtels');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', "Nom de l'établissement"),
            TextField::new('address', 'Adresse'),
            TextField::new('city', 'Ville'),
            TextareaField::new('description')
                ->onlyOnForms()
                ->onlyOnDetail(),
            AssociationField::new('users', 'Gérant')
                ->setRequired(true)
                ->setCustomOptions(['maxLi'=>10,'toDisplay'=>"fullname"])
                ->setHelp("Personne qui gère l'hôtel")
                ->renderAsNativeWidget(false)
                ->setQueryBuilder(function ($queryBuilder){
                    return $queryBuilder
                        ->andWhere('entity.roles LIKE :role')
                        ->setParameter('role', '%ROLE_GERANT%');
                })

        ];
    }
}
