<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Answer;
use Doctrine\Common\DataFixtures\Purger\ORMPurger as DoctrineOrmPurger;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AnswerRepositoryTest extends KernelTestCase
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

        $answers = $this->doctrine->getRepository(Answer::class)->findAll();

        $this->assertEquals(25, count($answers));
    }

    public function testFindAnswerTest(): void
    {
        $this->loadDb();

        $answerTest = $this->doctrine->getRepository(Answer::class)->findOneBy(['name' => 'Test Answer']);

        $this->assertEquals(new \DateTime() instanceof \DateTime, $answerTest->getCreatedAt() instanceof \DateTime);
        $this->assertEquals('Do my test poll works?', $answerTest->getPoll()->getTitle());
        $this->assertSame(13, $answerTest->getVotes());

        $this->tearDown();
    }

    public function tearDown(): void
    {
        $purger = new DoctrineOrmPurger($this->doctrine->getManager());
        $purger->purge();

        self::$kernel->shutdown();
    }
}
