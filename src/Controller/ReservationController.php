<?php

namespace App\Controller;

use App\Entity\ReservationRooms;
use App\Entity\Users;
use App\Form\ReservationType;
use App\Repository\ReservationRoomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
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
        $formAjax = $this->createForm(ReservationType::class, $reservationRooms);
        $formAjax->handleRequest($request);
        $data = null;
        $reservationExisting = "";
        if ($formAjax->isSubmitted() && $formAjax->isValid()) {

                /** @var  Users $users */
                $users = $this->getUser();

                $filter = $repository->findExistingReservation(
                    $formAjax->get('hotels')->getData(),
                    $formAjax->get('hotelRooms')->getData(),
                    $formAjax->get('start_date')->getData(),
                    $formAjax->get('end_date')->getData()
                );

                $reservationRooms->setUsers($users);

                if (empty($filter)){
                   $data = $formAjax->getData();
                   $this->requestStack->getSession()->set('reservation', $data);
                }

                if (!empty($filter)){
                    $reservationExisting = null;
                };
        }

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'form' => $formAjax->createView(),
            'data' => $data,
            'reservationExisting' => $reservationExisting,
        ]);
    }

    #[Route('/reservation/confirmation', name: 'app_reservation_confirm')]
    public function confirm(MailerInterface $mailer): Response
    {
        if ($this->requestStack->getSession()->get('reservation')){
            $reservation = new ReservationRooms();
            $data = $this->requestStack->getSession()->get('reservation');
            $reservation->setStartDate($data->getStartDate());
            $reservation->setEndDate($data->getEndDate());
            $reservation->setHotels($data->getHotels());
            $reservation->setHotelRooms($data->getHotelRooms());
            $reservation->setUsers($data->getUsers());

            $totalPrice = ($data->getStartDate()->diff($data->getEndDate())->format("%r%a"))* $data->getHotelRooms()->getPrice();
            $reservation->setTotalPrice($totalPrice);

            $this->entityManager->merge($reservation);
            $this->entityManager->flush();
            $this->requestStack->getSession()->remove('reservation');

            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@hypnos.com', $data->getHotels()->getName()))
                ->to($data->getUsers()->getEmail())
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Confirmation de votre réservation')
                ->htmlTemplate('emails/reservationConfirm.html.twig')
                ->context([
                    'data' => $data
                ]);
            $mailer->send($email);
        }
        else{
            return $this->redirectToRoute('app_reservation');
        };

        return $this->render('reservation/reservationConfirm.html.twig', [
            'data' => $data
        ]);
    }
}
