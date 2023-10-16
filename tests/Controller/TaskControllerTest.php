<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private EntityManagerInterface|null $em = null;
    private User|null $user = null;
    private EntityRepository $taskRepository;
    private $urlGenerator = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->taskRepository = $this->em->getRepository(Task::class);
    }

    public function login(string $username): void
    {
        $userRepository = $this->em->getRepository(User::class);
        $this->user = $userRepository->findOneBy(['username' => $username]);
        $this->client->loginUser($this->user);
    }

    public function testTasksList(): void
    {
        $this->login('user');

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_list')
        );

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $totalTask = count($this->em->getRepository(Task::class)->findBy(['auteur' => $this->user]));

        if ($totalTask > 9) {
            $totalTask = 9;
        }

        $this->assertSame(
            $totalTask,
            // get the elements with the class thumbnail
            $crawler->filter('div.rounded-3')->count()
        );
    }

    public function testTasksListAdmin(): void
    {
        $this->login('admin');

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('admin_tasks_list')
        );

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $totalTask = count($this->em->getRepository(Task::class)->findAll());

        if ($totalTask > 9) {
            $totalTask = 9;
        }

        $this->assertSame(
            $totalTask,
            // get the elements with the class thumbnail
            $crawler->filter('div.rounded-3')->count()
        );
    }

    public function testCreateTask(): void
    {
        $this->login('user');

        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('task_create')
        );

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test title : testCreateTask',
            'task[content]' => 'Test content : testCreateTask'
        ]);
        
        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-success',
            'Superbe ! La tâche a été bien été ajoutée.'
        );
    }

    public function testEditTask(): void
    {
        $this->login('user');
        
        $task = $this->taskRepository->findOneBy(['auteur' => $this->user]);
        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_edit', [
                'id' => $task->getId()
            ])
        );

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Test title : testEditTask modifié',
            'task[content]' => 'Test content : testEditTask modifié'
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-success',
            'Superbe ! La tâche a bien été modifiée.'
        );

        $task = $this->taskRepository->find($task->getId());

        // check if the task title is same of "Test title : testEditTask modifié"
        $this->assertSame(
            'Test title : testEditTask modifié',
            $task->getTitle()
        );
    }

    public function testToggleTask(): void
    {
        $this->login('user');

        $task = $this->taskRepository->findOneBy(['auteur' => $this->user]);

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_toggle', [
                'id' => $task->getId()
            ])
        );

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertTrue($task->isDone());

        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-success',
            'Superbe ! La tâche a bien été marquée comme faite.'
        );
    }

    public function testDeleteTask(): void
    {
        $this->login('user');

        $task = $this->taskRepository->findOneBy(['auteur' => $this->user]);

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('task_delete', [
                'id' => $task->getId()
            ])
        );

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-success',
            'Superbe ! La tâche a bien été supprimée.'
        );
    }
}