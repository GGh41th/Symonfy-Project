<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user', name: 'app_user')]
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

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

        $user = $this->getUser();
        $userData = [
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'image' => $user->getProfileImage(),
            'birthday' => $user->getBirthDate(),
        ];

        return $this->render('user/profile.html.twig', [
            'user' => $userData
        ]);
    }

    #[Route('/profile/update/{attribute}', name: 'user_update')]
    public function updateUsername(Request $request, ManagerRegistry $managerRegistry, string $attribute): Response
    {
        if (!($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');


        $users = $managerRegistry->getRepository(User::class);
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
