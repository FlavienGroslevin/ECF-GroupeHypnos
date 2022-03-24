<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use App\Entity\Users;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class HotelRoomsCrudController extends AbstractCrudController
{
    protected EntityRepository $entityRepository;

    public function __construct(EntityRepository $entityRepository) {
        $this->entityRepository = $entityRepository;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['title'])
            ->setEntityLabelInSingular('Chambre')
            ->setEntityLabelInPlural('Chambres');

    }
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        /**
         * @var Users $user
         */
        $user = $this->getUser();
        $hotels = $user->getHotels();
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response
            ->andWhere('entity.hotels = :name')
            ->setParameter('name', $hotels);
        return $response;
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
