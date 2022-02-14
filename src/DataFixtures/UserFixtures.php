<?php

declare(strict_types=1);

namespace Decarte\Shop\DataFixtures;

use Decarte\Shop\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@localhost');
        $password = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
