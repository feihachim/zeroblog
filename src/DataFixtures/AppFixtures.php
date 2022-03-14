<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setEmail('abc@xyz.com');
        $user->setPseudo('omega');
        $plaintextPassword = "test123";

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);

        //$user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);


        $manager->flush();
    }
}
