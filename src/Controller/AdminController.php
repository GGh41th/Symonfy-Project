<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{
    #[Route('/')]
    public function azesqd(Request $request): Response
    {
        return $this->redirect('/home');
    }

    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/users.html.twig');
    }

    #[Route('/logout_page', name: 'app_admin_logout_page')]
    public function logout_page(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/logout_page.html.twig');
    }

    #[Route('/main', name: 'app_admin_main')]
    public function main(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/main.html.twig');
    }

    #[Route('/feedbacks', name: 'app_admin_feedbacks')]
    public function feedbacks(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/feedbacks.html.twig');
    }

    #[Route('/profile', name: 'app_admin_profile')]
    public function profile(Request $request): Response
    {
        if (!($this->getUser()) || $this->getUser()->getRole() != "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        /* Your controller's code goes here */

        return $this->render('admin/profile.html.twig');
    }

}
