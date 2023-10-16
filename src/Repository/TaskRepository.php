<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllQuery($user = null): Query
    {
        $qb = $this->createQueryBuilder('t');

        if(!is_null($user)){
            $qb->where('t.auteur = :user')
                ->setParameter('user', $user);
        }

        $qb->orderBy('t.createdAt', 'DESC');

        return $qb->getQuery();
    }
}
