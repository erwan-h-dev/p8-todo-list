<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    public function __construct(
        private TaskRepository $taskRepository
    ) {
    }

    #[Route('/tasks', name: 'admin_tasks_list')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $tasksQuery = $this->taskRepository->findAllQuery();

        $pagination = $paginator->paginate(
            $tasksQuery,
            $request->query->get('page', 1),
            9
        );

        return $this->render('task/list.html.twig', [
            'title' => 'Liste de toutes les tâches',
            'totalTasks' => $this->taskRepository->findBy(['auteur' => $this->getUser()]),
            'pagination' => $pagination
        ]);
    }
}
