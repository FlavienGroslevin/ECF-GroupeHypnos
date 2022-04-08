<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ResetPasswordClientAreaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserInfoController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    #[Route('/espace-client/mes-informations', name: 'app_user_info')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        /** @var Users $user */
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordClientAreaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $password = $form->get('password')->getData();
            if ($userPasswordHasher->isPasswordValid($user, $password)){
                $newPassword = $form->get('newPassword')->getData();
                $hashPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $newPassword = $user->setPassword($hashPassword);

                $this->entityManager->persist($newPassword);
                $this->entityManager->flush();
                $this->addFlash('success_modify_password', 'Mot de passe modifié avec succès');
            }else{
                $this->addFlash('error_modify_password', 'Le mot de passe actuel est incorrect');
            }
        }
        return $this->render('user_info/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
