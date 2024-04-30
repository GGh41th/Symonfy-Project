<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FeedbackController extends AbstractController
{
    #[Route(path: '/feedback', name: 'feedback')]
    public function Feedback(AuthenticationUtils $authenticationUtils): Response
    {
       



        return $this->render('feedback/feedback.html.twig');
    }

    
   
}
