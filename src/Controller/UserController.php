<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    #[Route('/')]
    #[Route('/home')]
    public function index(): Response
    {
        return $this->redirectToRoute('home_screen_user');
    }

    #[Route('/main', name: 'app_user_main')]
    public function main(Request $request): Response
    {

        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Change whatever you want here  */


        return $this->render('user/main.html.twig');
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Change whatever you want here  */


        return $this->render('user/profile.html.twig');
    }

    #[Route('/tasks', name: 'app_user_tasks')]
    public function tasks(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Change whatever you want here  */

        return $this->render('user/tasks.html.twig');
    }

    #[Route('/stats', name: 'app_user_stats')]
    public function stats(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');


        /* Change whatever you want here  */


        return $this->render('user/stats.html.twig');
    }

    #[Route('/support', name: 'app_user_support')]
    public function support(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Change whatever you want here  */


        return $this->render('user/support.html.twig');
    }

    #[Route('/logout_page', name: 'app_user_logout_page')]
    public function logoutPage(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Change whatever you want here  */

        return $this->render('user/logout_page.html.twig');
    }

    #[Route('/{value}')]
    public function value($value): Response
    {
        return $this->redirectToRoute('home_screen');
    }
}
