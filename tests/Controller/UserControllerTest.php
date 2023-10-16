<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private EntityManagerInterface|null $em = null;
    private User|null $user = null;
    private $urlGenerator = null;
    private UserPasswordHasherInterface|null $passwordHasher = null;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->passwordHasher = $this->client->getContainer()->get('security.password_hasher');
    }

    public function login(string $username): void
    {
        $userRepository = $this->em->getRepository(User::class);
        $this->user = $userRepository->findOneBy(['username' => $username]);
        $this->client->loginUser($this->user);
    }

    public function testUserListUser(): void
    {
        $this->login('user');

        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('user_list')
        );

        $this->assertSame(
            Response::HTTP_FORBIDDEN,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testAdminListUser(): void
    {
        $this->login('admin');

        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('user_list')
        );

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

    public function testCreateUser()
    {
        $this->login('admin');

        $crawler = $this->client->request(
            Request::METHOD_GET, 
            $this->urlGenerator->generate('user_create')
        );

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' =>         'testuser',
            'user[password][first]' =>  'password',
            'user[password][second]' => 'password',
            'user[email]' =>            'testuser@mail.com',
            'user[roles]' =>            false
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        // make assert on flash message

        $this->assertSelectorTextContains(
            'div.alert-success',
            "Superbe ! L'utilisateur a bien été ajouté."
        );
    }

    public function testEditUser()
    {
        $this->login('admin');

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'user']);
        
        $this->em->flush();

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

        $form = $crawler->selectButton('Modifier')->form([
            'user[username]' =>         'user',
            'user[password][first]' =>  'password',
            'user[password][second]' => 'password',
            'user[email]' =>            'user@test.fr',
            'user[roles]' =>            true
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $user = $this->em->getRepository(User::class)->findOneBy(['username' => 'user']);

        $this->assertSame(
            true,
            $user->isAdmin()
        );

        $this->assertSelectorTextContains(
            'div.alert-success',
            "Superbe ! L'utilisateur a bien été modifié"
        );
    }
}