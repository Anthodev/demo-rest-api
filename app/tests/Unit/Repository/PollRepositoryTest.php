<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Poll;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PollRepositoryTest extends KernelTestCase
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
            __DIR__ . '/PollRepositoryTestFixtures.yml'
        ], false, null, 'doctrine', DoctrineOrmPurger::PURGE_MODE_TRUNCATE);
    }

    public function testLoadAFile(): void
    {
        $this->loadDb();

        $polls = $this->doctrine->getRepository(Poll::class)->findAll();

        $this->assertEquals(10, count($polls));
    }

    public function testFindAdminPoll(): void
    {
        $this->loadDb();

        // $adminPoll = $this->doctrine->getRepository(Poll::class)->findOneBy(['pollname' => 'admin']);

        // $this->assertSame('admin@noreply.local', $adminPoll->getEmail());
        // $this->assertSame('test1234', $adminPoll->getPassword());
        // $this->assertSame('ROLE_ADMIN', $adminPoll->getRole()->getCode());
        // $this->assertEquals(new \DateTime() instanceof \DateTime, $adminPoll->getCreatedAt() instanceof \DateTime);

        $this->tearDown();
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        self::$kernel->shutdown();
    }
}
