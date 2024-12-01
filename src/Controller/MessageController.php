<?php

namespace App\Controller;

use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }
    #[Route('/messages/{id}', name: 'message')]
    public function getMessage(string $id): Response
    {
        $message = $this->messageService->getMessageById($id);

        if ($message)
        {
            return $this->render('messages/message.html.twig', [
                'message' => $message,
                'title' => 'Wiadomość o id: ' . $message->getId() . ' | Smartheads - Aplikacja rekrutacyjna',
            ]);
        } else {
            return $this->render('messages/message.html.twig', [
                'message' => null,
                'title' => 'Brak wiadomości o podanym id | Smartheads - Aplikacja rekrutacyjna',
            ]);
        }
    }
}