<?php

namespace App\Controller;

use App\Entity\FeedBack;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
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

    private function getTasks(): array
    {
        $userId = $this->getUser();
        $tasks = $this->entityManager->getRepository(Task::class)->findBy(['user_id' => $userId]);
        foreach ($tasks as $task) {
            $currentDate = new \DateTime();
            $endDate = $task->getEndDate();
            $difference = $currentDate->diff($endDate);
            if ($difference->days > 0 && $task->getStatus() == "Pending" && $endDate < $currentDate) {
                $task->setStatus("Overdue");
                $this->entityManager->persist($task);
                $this->entityManager->flush();
            }
        }
        return $tasks;
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

        $tasks = $this->getTasks();
        $tasksData = [];
        $completedTasks = 0;
        $totalTasks = count($tasks);
        foreach ($tasks as $task) {
            $taskData = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'endDate' => $task->getEndDate(),
            ];
            if ($task->getStatus() == "Finished") {
                $completedTasks++;
            }
            array_push($tasksData, $taskData);
        }
        return $this->render('user/main.html.twig', [
            'tasks' => $tasks,
            'user' => $this->getUser(),
            'total' => $totalTasks,
            'finished' => $completedTasks,
        ]);
    }

    #[Route('/task/generaterandom', name: 'app_user_task_add')]
    public function generateRandom(): Response
    {

        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task->setTitle($faker->sentence());
            $task->setDescription($faker->paragraph());
            $task->setStatus($faker->randomElement(['Overdue', 'Pending', 'Finished']));
            $task->setCreationDate(new \DateTime());
            $task->setEndDate(new \DateTime());
            $task->setUserId($this->getUser());
            $this->entityManager->persist($task);
        }

        $this->entityManager->flush();
        return $this->redirectToRoute('home_screen');
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
            'user' => $userData,
            "nochange" => false
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

    #[Route('/tasks', name: 'app_user_tasks')]
    public function tasks(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $tasks = $this->getTasks();
        $tasksToSend = [];
        foreach ($tasks as $task) {
            $taskData = [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'endDate' => $task->getEndDate(),
            ];
            array_push($tasksToSend, $taskData);
        }

        return $this->render('user/tasks.html.twig', [
            'tasks' => $tasksToSend,
        ]);
    }

    #[Route('/stats', name: 'app_user_stats')]
    public function stats(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $tasks = $this->getTasks();
        $todayTasks = ["finished" => 0, "failed" => 0, "total" => 0];
        $lastWeekTasks = ["finished" => 0, "failed" => 0, "total" => 0];
        $lastMonthTasks = ["finished" => 0, "failed" => 0, "total" => 0];
        $pendingTasks = 0;
        foreach ($tasks as $task) {
            $completionDate = $task->getCompletionDate();
            $endDate = $task->getEndDate();
            if ($completionDate) {
                $currentDate = new \DateTime();
                $interval = $currentDate->diff($completionDate);
                if ($interval->days < 1) {
                    $todayTasks["finished"]++;
                } elseif ($interval->days < 7) {
                    $lastWeekTasks["finished"]++;
                } elseif ($interval->days < 30) {
                    $lastMonthTasks["finished"]++;
                }
            } else {
                $currentDate = new \DateTime();
                $interval = $currentDate->diff($endDate);
                if ($task->getStatus() == "Pending") {
                    $pendingTasks++;
                } else if ($interval->days < 1) {
                    $todayTasks["failed"]++;
                } elseif ($interval->days < 7) {
                    $lastWeekTasks["failed"]++;
                } elseif ($interval->days < 30) {
                    $lastMonthTasks["failed"]++;
                }
            }

        }
        $todayTasks["total"] = $todayTasks["finished"] + $todayTasks["failed"];
        $lastWeekTasks["total"] = $lastWeekTasks["finished"] + $lastWeekTasks["failed"];
        $lastMonthTasks["total"] = $lastMonthTasks["finished"] + $lastMonthTasks["failed"];

        return $this->render('user/stats.html.twig', [
            'today' => $todayTasks,
            'lastWeek' => $lastWeekTasks,
            'lastMonth' => $lastMonthTasks,
            'pending' => $pendingTasks,
            'user' => $this->getUser(),
        ]);
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

    #[Route("/support/sendfeedback", name: 'app_user_support_send')]
    public function sendFeedback(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $feedbackMessage = $request->request->get('feedback');
        $feedback = new FeedBack();
        $feedback->setUserId($this->getUser());
        $feedback->setSubmissionText($feedbackMessage);
        $feedback->setDate(new \DateTime());
        $this->entityManager->persist($feedback);
        $this->entityManager->flush();
        return $this->json([
            'status' => 'success',
            'message' => 'Feedback sent successfully'
        ]);
    }

    #[Route('/user/task/add')]
    public function addTask(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $endDate = \DateTimeImmutable::createFromFormat('Y-m-d', $request->request->get('date'));
        $task = new Task();
        $task->setTitle($title);
        $task->setDescription($description);
        $task->setStatus("Pending");
        $task->setCreationDate(new \DateTime());
        $task->setEndDate($endDate);
        $task->setUserId($this->getUser());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return $this->json([
            'status' => 'success',
            'message' => 'Task added successfully'
        ]);
    }

    #[Route('/user/task/update', name: 'app_user_task_update')]
    public function updateTask(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $taskId = $request->request->get('id');
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if (!$task)
            return $this->json([
                'status' => 'error',
                'message' => 'Task not found'
            ]);
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $task->setTitle($title);
        $task->setDescription($description);
        $this->entityManager->flush();
        return $this->json([
            'status' => 'success',
            'message' => 'Task updated successfully'
        ]);
    }

    #[Route('/user/task/delete', name: 'app_user_task_delete')]
    public function deleteTask(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $taskId = $request->request->get('id');
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if (!$task)
            return $this->json([
                'status' => 'error',
                'message' => 'Task not found'
            ]);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return $this->json([
            'status' => 'success',
            'message' => 'Task deleted successfully'
        ]);
    }

    #[Route('/user/task/finish', name: 'app_user_task_finish')]
    public function finishTask(Request $request): Response
    {
        if (!$this->getUser() || $this->getUser()->getRole() == "admin" || !($request->isXmlHttpRequest()))
            return $this->redirectToRoute('home_screen');

        $taskId = $request->request->get('id');
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);
        if (!$task)
            return $this->json([
                'status' => 'error',
                'message' => 'Task not found'
            ]);
        $task->setStatus("Finished");
        $task->setCompletionDate(new \DateTime());
        $this->entityManager->flush();
        return $this->json([
            'status' => 'success',
            'message' => 'Task finished successfully'
        ]);
    }

    #[Route('/{value}')]
    public function value($value): Response
    {
        return $this->redirectToRoute('home_screen');
    }
}
