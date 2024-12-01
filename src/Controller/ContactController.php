<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    #[Route('/kontakt', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('contact/index.html.twig', [
            'title' => 'Kontakt | Smartheads - Aplikacja rekrutacyjna'
        ]);
    }
}
