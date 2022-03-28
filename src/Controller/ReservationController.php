<?php

namespace App\Controller;

use App\Entity\HotelRooms;
use App\Entity\ReservationRooms;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        $reservationRooms = new ReservationRooms();
        $form = $this->createForm(ReservationType::class, $reservationRooms);
        $form->handleRequest($request);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'form' => $form->createView()
        ]);
    }
}
