<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\User;
use App\EventListener\HashPassword;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private ManagerRegistry $doctrine;
    private HashPassword $passwordEncoder;

    use FixturesTrait;

    public function setUp(): void
    {
        self::bootKernel();

        $this->doctrine = self::$container->get('doctrine');
        $this->passwordEncoder = self::$container->get('App\EventListener\HashPassword');
    }

    public function loadDb(): void
    {
        $this->loadFixtureFiles([
            __DIR__ . '/UserRepositoryTestFixtures.yml'
        ], false, null, 'doctrine', DoctrineOrmPurger::PURGE_MODE_TRUNCATE);
    }

    public function testLoadAFile(): void
    {
        $this->loadDb();

        $users = $this->doctrine->getRepository(User::class)->findAll();

        $this->assertEquals(5, count($users));
    }

    public function testFindAdminUser(): void
    {
        $this->loadDb();

        $adminUser = $this->doctrine->getRepository(User::class)->findOneBy(['username' => 'admin']);

        $this->assertSame('admin@noreply.local', $adminUser->getEmail());
        $this->assertEquals($this->passwordEncoder->encodePassword($adminUser, 'test1234'), $adminUser->getPassword());
        $this->assertSame('ROLE_ADMIN', $adminUser->getRole()->getCode());
        $this->assertEquals(new \DateTime() instanceof \DateTime, $adminUser->getCreatedAt() instanceof \DateTime);

        $this->tearDown();
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        self::$kernel->shutdown();
    }
}
