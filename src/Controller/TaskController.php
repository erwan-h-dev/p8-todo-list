<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tasks')]
#[IsGranted('ROLE_USER')]
class TaskController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private TaskRepository $taskRepository
    ){ }
    
    #[Route('/', name: 'task_list')]
    public function listTask(PaginatorInterface $paginator, Request $request): Response
    {
        $tasksQuery = $this->taskRepository->findAllQuery($this->getUser());

        $pagination = $paginator->paginate(
            $tasksQuery,
            $request->query->get('page', 1),
            9
        ); 

        return $this->render('task/list.html.twig', [
            'title' => 'Liste des tâches',
            'totalTasks' => $this->taskRepository->findBy(['auteur' => $this->getUser()]),
            'pagination' => $pagination
        ]);
    }

    #[Route('/create', name: 'task_create')]
    public function createTask(Request $request)
    {
        $task = new Task();
        $task->setAuteur($this->getUser());
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/form.html.twig', [
            'title' => 'Créer une tâche',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit')]
    #[IsGranted('EDIT', subject: 'task')]
    public function editTask(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/form.html.twig', [
            'title' => 'Modifier une tâche',
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/toggle', name: 'task_toggle')]
    #[IsGranted('EDIT', subject: 'task')]
    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->em->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/{id}/delete', name: 'task_delete')]
    #[IsGranted('DELETE', subject: 'task')]
    public function deleteTask(Task $task)
    {
        $this->em->remove($task);
        $this->em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
