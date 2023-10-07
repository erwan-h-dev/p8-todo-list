<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private EntityManagerInterface|null $em = null;
    private UserRepository|null $userRepository = null;
    private User|null $user = null;
    private $urlGenerator = null;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function login(): void
    {
        // $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        // $this->user = $this->userRepository->findOneByEmail('test@test.fr');
        // $this->client->loginUser($this->user);
    }

    private function makeUser(string $methodName): User
    {
        $user = new User();
        $user->setUsername('Test title : ' . $methodName);
        $user->setEmail('test.'.$methodName.'@test.fr');
        $password = $this->get('security.password_encoder')->encodePassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($password);
        $this->em->persist($user);

        return $user;
    }

    public function testListAction(): void
    {

        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $this->assertSame(
            count($this->em->getRepository(User::class)->findAll()),
            // get the elements with the class thumbnail
            $crawler->filter('tbody tr')->count()
        );
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('user_create')
        );

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' =>         'test username',
            'user[password][first]' =>  'test password',
            'user[password][second]' => 'test username',
            'user[email]' =>            'test@mail.com'
        ]);

        $this->client->submit($form);
        
        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();
        
        $this->assertSelectorTextContains(
            'div.alert-success',
            "Superbe ! L'utilisateur a bien été ajouté."
        );
    }

    public function testEditAction()
    {
        $user = $this->makeUser('testEditAction');

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_edit', [
                'id' => $user->getId()
            ])
        );

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' =>         'test edit username',
            'user[password][first]' =>  'test edit password',
            'user[password][second]' => 'test edit username',
            'user[email]' =>            'test@mail.com'
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-success',
            "Superbe ! L'utilisateur a bien été ajouté."
        );
    }
}