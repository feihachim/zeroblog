<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; ++$i) {
            $user = (new User())->setEmail("user$i@domain.fr")
                ->setPseudo("user$i")
                ->setPassword('0000');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
