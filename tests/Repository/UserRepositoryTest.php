<?php

namespace App\Tests\Repository;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Component\Config\Loader\LoaderInterface;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var AbstractDatabaseTool
     */
    protected $databaseTool;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    public function setUp(): void
    {
        parent::setUp();

        //$this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $this->loader = static::getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
    }

    public function testCount(): void
    {
        //$this->databaseTool->loadFixtures([UserFixtures::class]);
        $this->loader->load([__DIR__ . '/UserRepositoryTestFixtures.yaml']);
        // $users['user1']
        $users = $this->entityManager->getRepository(User::class)->findAll();

        self::assertCount(10, $users);
    }
}
