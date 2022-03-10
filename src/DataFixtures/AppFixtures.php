<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $password = 'test123';
        $user = $manager->getRepository(User::class)->find(5);
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);


        $manager->flush();
    }
}
