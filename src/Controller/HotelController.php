<?php

namespace App\Controller;

use App\Entity\Hotels;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HotelController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public RequestStack $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack){
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    #[Route('/nos-hotels', name: 'app_hotel')]
    public function index(): Response
    {
        $this->requestStack->getSession()->set('hotel', '');
        $this->requestStack->getSession()->set('hotelRoom', '');
        $hotels = $this->entityManager->getRepository(Hotels::class)->findAll();
        return $this->render('hotel/index.html.twig', [
                'hotels' => $hotels,
        ]);
    }
}
