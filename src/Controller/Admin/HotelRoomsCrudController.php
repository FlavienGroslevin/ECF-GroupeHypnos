<?php

namespace App\Controller\Admin;

use App\Entity\HotelRooms;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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
    protected EntityManagerInterface $entityManager;

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function __construct(EntityRepository $entityRepository) {
        $this->entityRepository = $entityRepository;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['title'])
            ->setEntityLabelInSingular('Chambre')
            ->setPageTitle('detail', fn (HotelRooms $hotels) => (string) $hotels->getTitle())
            ->setPaginatorPageSize(4)
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
