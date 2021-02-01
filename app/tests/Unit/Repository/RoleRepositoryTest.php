<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Role;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RoleRepositoryTest extends KernelTestCase
{
    private ManagerRegistry $doctrine;

    use FixturesTrait;

    public function setUp(): void
    {
        self::bootKernel();

        $this->doctrine = self::$kernel->getContainer()->get('doctrine');
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

        $roles = $this->doctrine->getRepository(Role::class)->findAll();

        $this->assertEquals(2, count($roles));
    }

    public function testFindAdminRole(): void
    {
        $this->loadDb();

        $roleAdmin = $this->doctrine->getRepository(Role::class)->findOneBy(['name' => 'admin']);

        $this->assertSame('ROLE_ADMIN', $roleAdmin->getCode());
        $this->assertEquals(new \DateTime() instanceof \DateTime, $roleAdmin->getCreatedAt() instanceof \DateTime);

        $this->tearDown();
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        self::$kernel->shutdown();
    }
}
