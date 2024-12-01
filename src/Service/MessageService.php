<?php

    namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MessageService
{
    private MessageRepository $messageRepository;
    private MailerInterface $mailer;

    public function __construct(
        MessageRepository $messageRepository,
        MailerInterface $mailer
    )
    {
        $this->messageRepository = $messageRepository;
        $this->mailer = $mailer;
    }

    public function addNewMessage(Message $message): void
    {
        $email = (new Email())
            ->from('no-reply@apkarekrutacyjna.pl')
            ->to('adrian.florczak92@gmail.com')
            ->subject('Nowa wiadomość | apkarekrutacyjna.pl')
            ->html(
                'Nadawca<br/>'.
                $message->getName().'<br/>'.
                $message->getPesel().'<br/>'.
                '<a href="'.$message->getEmail().'">'.$message->getEmail().'</a><br/>'.
                '<hr/>'.
                'Wiadomość:<br/>'.
                '<blockquote>'.
                $message->getContent().
                '</blockquote>'
            );

        try {
            $this->messageRepository->save($message);

            try {
                $this->mailer->send($email);
            }catch (TransportExceptionInterface $e) {
                // Tutaj powinna być podjęta akcja w przypadku błędu wysłania emaila.
                // Możemy tutaj usunąć wiadomość z bazy danych i dać znać klientowi że wystapił błąd.
                // Możemy też dodać pole "wysłane" w bazie danych i cyklicznie ponawiać próby wysłania.
                throw new \Exception($e->getMessage());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getAllMessages(): array
    {
        return $this->messageRepository->findAll();
    }

    public function getMessageById(int $id): Message|null
    {
        return $this->messageRepository->find($id);
    }
}
