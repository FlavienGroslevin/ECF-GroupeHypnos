<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{

    public function configureCrud(Crud $crud): Crud
    {

        return $crud
            ->setSearchFields(['firstname', 'lastname', 'email'])
            ->setEntityLabelInSingular('Gérant')
            ->setEntityLabelInPlural('Gérants');
    }
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            EmailField::new('email'),
            TextField::new('password', 'Mot de passe')
                ->onlyWhenCreating()
                ->setFormType(PasswordType::class),
            ChoiceField::new('roles', 'Rôle')
                ->setChoices([
                'Gérant' => 'ROLE_GERANT',
                'Admin' => 'ROLE_ADMIN',
            ])
                ->allowMultipleChoices()
                ->renderExpanded()
        ];
    }
}
