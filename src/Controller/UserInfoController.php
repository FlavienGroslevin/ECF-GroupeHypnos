<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserInfoController extends AbstractController
{
    #[Route('/espace-client/mes-informations', name: 'app_user_info')]
    public function index(): Response
    {
        return $this->render('user_info/index.html.twig', [
            'controller_name' => 'UserInfoController',
        ]);
    }
}
