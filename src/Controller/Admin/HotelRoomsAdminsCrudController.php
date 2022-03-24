<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HotelRoomsAdminsCrudController extends AbstractCrudController
{
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['title', 'hotels.name'])
            ->setPageTitle('detail', fn (HotelRooms $hotels) => (string) $hotels->getTitle())
            ->setEntityLabelInSingular('Chambre')
            ->setPaginatorPageSize(4)
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
