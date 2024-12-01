<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;

class MessageService
{
    private MessageRepository $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function addNewMessage(Message $message): void
    {
        try {
            $this->messageRepository->save($message);
            // jeżeli zapisze się do bazy prawidłowo to wysłać e-maila na biuro@smartheads.pl z treścią
            // jak nie wysle to też try catch i usunąć z bazy danych może ?
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


    }

    public function getAllMessages(): array
    {
        return $this->messageRepository->findAll();
    }

    public function getMessageById(int $id): Message
    {
        return $this->messageRepository->find($id);
    }
}
