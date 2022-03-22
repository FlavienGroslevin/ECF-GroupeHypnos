<?php

namespace App\Controller\Admin;

use App\Entity\Hotels;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class HotelsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hotels::class;
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setSearchFields(['name', 'city'])
            ->setEntityLabelInSingular('Hôtel')
            ->setEntityLabelInPlural('Hôtels');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', "Nom de l'établissement"),
            TextField::new('address', 'Adresse'),
            TextField::new('city', 'Ville'),
            TextareaField::new('description')->onlyOnForms()
        ];
    }
}
