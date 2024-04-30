<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'home_screen')]
    public function home(): Response
    {
        /*$this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");*/
        return $this->render('home/index.html.twig');
    }
}
