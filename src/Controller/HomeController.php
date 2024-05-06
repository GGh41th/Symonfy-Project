<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class HomeController extends AbstractController
{
    #[Route('/home')]
    #[Route('/', name: 'home_screen')]
    public function home(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home_screen_user');
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/user/home', name: 'home_screen_user')]
    public function homeUser(): Response
    {
        if (!$this->getUser())
            return $this->redirectToRoute('home_screen');
        if ($this->getUser()->getRole() != "user")
            return $this->redirectToRoute('home_screen_admin');
        $user = $this->getUser();
        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'birthday' => $user->getBirthDate()->format('Y-m-d'), // 'Y-m-d H:i:s
            'image' => $user->getProfileImage(),
        ];
        return $this->render('user/home.html.twig', ['user' => $userData]);
    }

    #[Route('/admin/home', name: 'home_screen_admin')]
    public function adminScreen(): Response
    {

        if (!($this->getUser()))
            return $this->redirectToRoute('home_screen');

        if ($this->getUser()->getRole() != "admin")
            return $this->redirectToRoute('home_screen_user');

        $user = $this->getUser();
        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'birthday' => $user->getBirthDate()->format('Y-m-d'), // 'Y-m-d H:i:s
            'image' => $user->getProfileImage(),
        ];
        return $this->render('admin/home.html.twig', ['user' => $userData]);
    }
}
