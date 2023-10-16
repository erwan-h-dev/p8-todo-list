<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        private TaskRepository $taskRepository,
        private UserRepository $userRepository
    ) {
    }

    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();

        for($i = 0; $i < 35; $i++) {

            $task = new Task();
            $task
                ->setTitle('Tâche n°' . $i)
                ->setContent('Contenu de la tâche n°' . $i);

            $n = rand(0, 2);
            if ($n != 2) {
                $task->setAuteur($users[$n]);
            }

            $manager->persist($task);
        }

        $manager->flush();
    }
}
