<?php

namespace App\Controller;

use App\Entity\ReservationRooms;
use App\Repository\ReservationRoomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAreaController extends AbstractController
{
    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/espace-client/mes-reservations', name: 'app_client_area')]
    public function index(ReservationRoomsRepository $repository): Response
    {
        $reservation = $repository->findBy(array('users'=> $this->getUser()), array('start_date' => 'DESC'));

        return $this->render('client_area/index.html.twig', [
                'reservation' => $reservation
        ]);
    }

    #[Route('/espace-client/reservation/supprimer/{id}', name: 'reservation_delete', methods: ['POST','DELETE'])]
    public function delete(Request $request, ReservationRooms $reservationRooms): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservationRooms->getId(), $request->request->get('_token'))) {
            $this->addFlash('success_delete', 'Votre réservation à bien été annulée..');
            $this->entityManager->remove($reservationRooms);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_client_area');
    }
}
