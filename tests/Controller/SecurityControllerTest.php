<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase 
{
    private KernelBrowser|null $client = null;
    private UserRepository|null $userRepository = null;
    private User|null $user = null;
    private $urlGenerator = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testLogin(): void
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('login')
        );

        $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'user@mail.fr',
            'password' => 'password'
        ]);

        $this->client->submit($form);

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );

        $this->client->followRedirect();

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('logout')
        );

        $this->assertEquals(
            Response::HTTP_FOUND,
            $this->client->getResponse()->getStatusCode()
        );
    }
}