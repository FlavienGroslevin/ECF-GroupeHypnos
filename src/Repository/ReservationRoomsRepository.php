<?php

namespace App\Repository;

use App\Entity\ReservationRooms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationRooms|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationRooms|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationRooms[]    findAll()
 * @method ReservationRooms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRoomsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationRooms::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReservationRooms $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ReservationRooms $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findExistingReservation($hotels, $hotelRooms, $start, $end)
    {
        $qb = $this->createQueryBuilder('b')
            ->innerJoin('b.hotels', 'h')
            ->innerJoin('b.hotelRooms', 'hr')
            ->where('h = :hotel')
            ->andWhere('hr = :hotelRooms')
            ->andWhere('
                (b.start_date BETWEEN :startDateFrom AND :startDateTo) OR 
                (b.end_date BETWEEN :endDateFrom AND :endDateTo) OR
                (b.start_date < :startDateFrom AND b.end_date > :endDateTo)
                ')
            ->setParameter('hotel', $hotels)
            ->setParameter('hotelRooms', $hotelRooms)
            ->setParameter('startDateFrom', $start)
            ->setParameter('startDateTo', $end)
            ->setParameter('endDateFrom', $start)
            ->setParameter('endDateTo', $end);
        return $qb->getQuery()->getResult();
     }
}
