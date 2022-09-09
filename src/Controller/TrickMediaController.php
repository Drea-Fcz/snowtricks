<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickMediaController extends AbstractController
{
    #[Route('/trick/media', name: 'app_trick_media')]
    public function index(): Response
    {
        return $this->render('trick_media/index.html.twig', [
            'controller_name' => 'TrickMediaController',
        ]);
    }
}
