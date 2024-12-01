<?php

namespace App\Controller;

use App\Service\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagesController extends AbstractController
{
    private MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    #[Route('/messages', name: 'messages')]
    public function getAllMessages(): Response
    {
        // DO ZROBIENIA PAGINACJA W PRZYPADKU DUŻEJ LICZBY WIADOMOŚCI
        $messages = $this->messageService->getAllMessages();

        return $this->render('messages/index.html.twig', [
            'messages' => $messages,
            'title' => 'Wiadomości | Smartheads - Aplikacja rekrutacyjna',
        ]);
    }
}