<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageFormType;
use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    #[Route('/kontakt', name: 'contact')]
    public function contact(
        Request $request,
        MessageService $messageService
    ): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageFormType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->get('name')->getData();
            $form->get('pesel')->getData();
            $form->get('email')->getData();
            $form->get('content')->getData();

            try {
                $messageService->addNewMessage($message);
                $this->addFlash('success', 'Twoja wiadomość została wysłana poprawnie!');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Podczas wysyłania Twojej wiadomości wystąpił błąd!');
            }

            return $this->redirectToRoute('contact');
        }



        return $this->render('contact/index.html.twig', [
            'title' => 'Kontakt | Smartheads - Aplikacja rekrutacyjna',
            'messageForm' => $form
        ]);
    }
}
