<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

        $user = $this->getUser();
        $userData = [
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'image' => $user->getProfileImage(),
            'birthday' => $user->getBirthDate(),
        ];

        return $this->render('admin/profile.html.twig', [
            'user' => $userData
        ]);

    }

    #[Route('/profile/update/{attribute}', name: 'user_update')]
    public function updateUsername(Request $request, string $attribute): Response
    {
        if (!($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');


        $users = $this->entityManager->getRepository(User::class);
        if ($attribute == 'username') {
            $newUsername = $request->request->get('username');
            $existingUser = $users->findOneBy(['username' => $newUsername]);
            if ($existingUser)
                return $this->json([
                    'status' => 'error',
                    'message' => 'Username already exists'
                ]);
            $user = $this->getUser();
            $user->setUsername($newUsername);
            $this->entityManager->flush();
            return $this->json([
                'status' => 'success',
                'message' => 'Username updated successfully'
            ]);
        } elseif ($attribute == 'email') {
            $newEmail = $request->request->get('email');
            $existingUser = $users->findOneBy(['email' => $newEmail]);
            if ($existingUser)
                return $this->json([
                    'status' => 'error',
                    'message' => 'Email already exists'
                ]);
            $user = $this->getUser();
            $user->setEmail($newEmail);
            $this->entityManager->flush();
            return $this->json([
                'status' => 'success',
                'message' => 'Email updated successfully'
            ]);
        } elseif ($attribute == 'birthdate') {
            $newBirthDate = \DateTimeImmutable::createFromFormat('d-m-Y', $request->request->get('birthday'));
            if (($newBirthDate) instanceof \DateTimeImmutable) {
                $user = $this->getUser();
                $user->setBirthDate($newBirthDate);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->json([
                    'status' => 'success',
                    'message' => 'Birthday updated successfully',
                ]);
            } else {
                return $this->json([
                    'status' => 'error',
                    'message' => 'Invalid date format',
                    'birthdate' => $request->request->get('birthday')
                ]);
            }
        }
        return $this->json([
            'status' => 'error',
            'message' => 'Invalid attribute'
        ]);
    }
}

