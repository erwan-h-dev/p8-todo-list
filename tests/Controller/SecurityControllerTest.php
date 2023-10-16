<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase 
{
    private KernelBrowser|null $client = null;
    private User|null $user = null;    
    private $urlGenerator = null;
    private EntityManagerInterface|null $em = null;
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

    }
    public function login(string $username): void
    {
        $userRepository = $this->em->getRepository(User::class);
        $this->user = $userRepository->findOneBy(['username' => $username]);
        $this->client->loginUser($this->user);
    }

    public function testLogin(): void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('login')
        );

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => 'password'
        ]);

        $this->client->submit($form);
        
        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertSelectorExists(
            'h1',
            'Bienvenue sur Todo List'
        );
    }
    public function testLoginFail(): void
    {

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('login')
        );

        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'bad_user',
            '_password' => 'bad_password'
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $this->client->followRedirect();

        $this->assertSelectorTextContains(
            'div.alert-danger',
            'Invalid credentials.'
        );
    }

    public function testLogout(): void
    {
        $this->login('admin');

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('logout')
        );

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $crawler = $this->client->followRedirect();

        $this->assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
        
        $this->assertSelectorExists('a.btn-outline-primary');
    }
}