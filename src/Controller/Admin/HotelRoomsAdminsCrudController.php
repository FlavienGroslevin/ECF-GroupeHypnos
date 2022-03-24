<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HotelRoomsAdminsCrudController extends AbstractCrudController
{
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['title'])
            ->setEntityLabelInSingular('Chambre')
            ->setEntityLabelInPlural('Chambres');

    }

    public static function getEntityFqcn(): string
    {
        return HotelRooms::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextareaField::new('description'),
            NumberField::new('price', 'prix par nuit')
                ->setNumDecimals(2),
            TextField::new('booking_link', 'URL booking'),
            AssociationField::new('hotels', 'Hôtel'),
            AssociationField::new('images'),
        ];
    }
}
