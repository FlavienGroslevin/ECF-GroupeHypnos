<?php

namespace App\Controller;

use App\Entity\ReservationRooms;
use App\Entity\Users;
use App\Form\ReservationType;
use App\Repository\ReservationRoomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    public RequestStack $requestStack;
    public EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager){
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, ReservationRoomsRepository $repository): Response
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
        $data = null;
        $reservationExisting = "";
        if ($form->isSubmitted() && $form->isValid()) {
                /** @var  Users $users */
                $users = $this->getUser();
                $filter = $repository->findExistingReservation(
                    $form->get('hotels')->getData(),
                    $form->get('hotelRooms')->getData(),
                    $form->get('start_date')->getData(),
                    $form->get('end_date')->getData()
                );
                if (empty($filter)){
                   $data = $form->getData();
                };
                if (!empty($filter)){
                    $reservationExisting = null;
                };
                $reservationRooms->setUsers($users);
                $this->entityManager->persist($reservationRooms);

        }
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'form' => $form->createView(),
            'data' => $data,
            'reservationExisting' => $reservationExisting
        ]);
    }
}
