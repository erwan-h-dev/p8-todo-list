<?php
// tests/Service/NewsletterGeneratorTest.php
namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
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

    public function testHomepage()
    {
        $request = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('homepage'));

        $this->assertSame(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );

        // check if the response contains the string $neddle in the h1 tag 
        $neddle = "Bienvenue sur Todo List";
        
        $this->assertSelectorTextContains('h1', $neddle);
        
        // check if the response is 200

        // count number of images with the class "slide-image"
        $this->assertSame(1, $request->filter('img.slide-image')->count());
    }

}