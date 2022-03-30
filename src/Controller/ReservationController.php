<?php

namespace App\Controller;

use App\Entity\ReservationRooms;
use App\Form\ReservationType;
use App\Repository\HotelsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    public RequestStack $requestStack;

    public function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, HotelsRepository $hotelsRepository): Response
    {
        if ($_GET == true) {
            $suite = $_GET['suite'];
            $hotel = $_GET['hotel'];
            $this->requestStack->getSession()->set('hotel', $hotel);
            $this->requestStack->getSession()->set('hotelRoom', $suite);
        }

        $reservationRooms = new ReservationRooms();
        $form = $this->createForm(ReservationType::class, $reservationRooms);
        $form->handleRequest($request);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'form' => $form->createView(),
        ]);
    }
}
