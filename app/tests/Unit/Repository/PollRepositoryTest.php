<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Answer;
use App\Entity\Poll;
use App\Entity\User;
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

    public function testFindPollTest(): void
    {
        $this->loadDb();

        $pollTest = $this->doctrine->getRepository(Poll::class)->findOneBy(['title' => 'Do my test poll works?']);

        $this->assertSame('This is just a test to see if the poll is inserted in the db', $pollTest->getQuestion());
        $this->assertEquals(new \DateTime() instanceof \DateTime, $pollTest->getCreatedAt() instanceof \DateTime);
        $this->assertNotFalse($pollTest->getDoUsersMustBeConnected());
        $this->assertSame('admin', $pollTest->getOwner()->getUsername());
        $this->assertContainsOnlyInstancesOf(User::class, $pollTest->getParticipants());
        $this->assertContainsOnlyInstancesOf(Answer::class, $pollTest->getAnswers());
        $this->assertSame(42, $pollTest->getTotalVotes());

        $this->tearDown();
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        self::$kernel->shutdown();
    }
}
