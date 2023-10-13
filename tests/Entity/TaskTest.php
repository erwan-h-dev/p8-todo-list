<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTask()
    {
        $date = new \DateTime();
        $task = new Task();

        $task->setTitle('Test Title');
        $task->setContent('Test Content');

        // compare the date stored in the task with the current date with a delta of 1 second
        $this->assertEqualsWithDelta($date, $task->getCreatedAt(), 1);

        $this->assertEquals('Test Title', $task->getTitle());
        $this->assertEquals('Test Content', $task->getContent());

        $this->assertFalse($task->isDone());
        $task->toggle(true);
        $this->assertTrue($task->isDone());

    }
}
