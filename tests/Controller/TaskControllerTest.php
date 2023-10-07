<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private EntityManagerInterface|null $em = null;
    private UserRepository|null $userRepository = null;
    private User|null $user = null;
    private Task|null $task = null;
    private $urlGenerator = null;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function tearDown(): void
    {
        $tasks = $this->em->getRepository(Task::class)->findAll();

        foreach ($tasks as $task) {
            $this->em->remove($task);
        }

        $this->em->flush();
    }

    public function login(): void
    {
        // $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        // $this->user = $this->userRepository->findOneByEmail('test@test.fr');
        // $this->client->loginUser($this->user);
    }

    public function makeTask(string $methodName): Task
    {
        $task = new Task();
        $task->setTitle('Test title : ' . $methodName);
        $task->setContent('Test content : ' . $methodName);
        $task->setCreatedAt(new \DateTime());

        $this->em->persist($task);
        $this->em->flush();

        return $task;
    }

    public function testTasksList()
    {
        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('task_list')
        );
        
        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
        
        $this->assertSame(
            count($this->em->getRepository(Task::class)->findAll()),
            // get the elements with the class thumbnail
            $crawler->filter('div.thumbnail')->count()
        );
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'Test title : testCreateAction',
            'task[content]' => 'Test content : testCreateAction'
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

    public function testEditAction()
    {
        $task = $this->makeTask('testEditAction');

        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('task_edit', [
                'id' => $task->getId()
            ])
        );

        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Test title : testEditAction modifié',
            'task[content]' => 'Test content : testEditAction modifié'
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
    }

    public function testToggleTaskAction()
    {

        $task = $this->makeTask('testToggleTaskAction');
       
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
            'Superbe ! La tâche Test title : testToggleTaskAction a bien été marquée comme faite.'
        );
    }

    public function testDeleteTaskAction()
    {
        $task = $this->makeTask('testDeleteTaskAction');

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