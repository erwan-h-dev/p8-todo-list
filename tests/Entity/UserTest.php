<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    public function testUser()
    {
        $user = new User();

        $user->setUsername('testuser');

        $user->setEmail('test@example.com');

        $user->setPassword('testpassword');

        $this->assertEquals('testuser', $user->getUsername());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('testpassword', $user->getPassword());

        $this->assertEquals(['ROLE_USER'], $user->getRoles());

        $this->assertEquals('testuser', $user->getUserIdentifier());
    }

    
}
