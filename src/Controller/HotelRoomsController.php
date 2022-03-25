<?php

namespace App\Controller;

use App\Entity\HotelRooms;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelRoomsController extends AbstractController
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-suites/{id}', name: 'app_hotel_rooms')]
    public function index($id): Response
    {
        $hotelRooms = $this->entityManager->getRepository(HotelRooms::class)->findBy(array('hotels' => $id));
        $hotel = $this->entityManager->getRepository(HotelRooms::class)->findOneBy(array('hotels' => $id));
        return $this->render('hotel_rooms/index.html.twig', [
            'hotelrooms' => $hotelRooms,
            'hotel' => $hotel
        ]);
    }
}
