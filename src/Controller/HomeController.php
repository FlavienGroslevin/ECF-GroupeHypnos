<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public RequestStack $requestStack;

    public  function __construct(RequestStack $requestStack){
        $this->requestStack = $requestStack;
    }
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $this->requestStack->getSession()->set('hotel', '');
        $this->requestStack->getSession()->set('hotelRoom', '');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
