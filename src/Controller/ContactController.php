<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer, Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success', 'Votre message a bien été envoyée, Nous vous répondrons dans les plus brefs délais.');
            $email = (new Email())
                ->from(new Address($form->get('email')->getData(), $form->get('firstname')->getData(). " ".$form->get('lastname')->getData()))
                ->to('groupehypnos@gmail.com')
                ->subject($form->get('subject')->getData())
                ->priority(Email::PRIORITY_HIGH)
                ->text($form->get('message')->getData());
            $mailer->send($email);
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
