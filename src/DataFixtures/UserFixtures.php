<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setUserName('user')
            ->setEmail('user@mail.fr')
            ->setPassword($this->hasher->hashPassword($user, 'password'));

        $admin = new User();
        $admin
            ->setUserName('admin')
            ->setEmail('admin@mail.fr')
            ->setPassword($this->hasher->hashPassword($admin, 'password'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }
}
