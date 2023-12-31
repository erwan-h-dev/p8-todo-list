<?php

namespace App\Tests\Entity;

use App\Entity\Task;
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

        $task = new Task();

        $user->addTask($task);

        $this->assertEquals(
            1, 
            count($user->getTasks())
        );
        
        $this->assertEquals('testuser', $user->getUsername());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('testpassword', $user->getPassword());

        $this->assertEquals([ 0 => 'ROLE_USER'], $user->getRoles());
       
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());

        $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }
}
