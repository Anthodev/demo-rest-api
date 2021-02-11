<?php

declare(strict_types=1);

use App\Factory\AnswerFactory;
use App\Factory\PollFactory;
use App\Factory\RoleFactory;
use App\Factory\UserFactory;

use function PHPUnit\Framework\assertContainsOnly;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    RoleFactory::new()->create([
        'name' => 'admin',
        'code' => 'ROLE_ADMIN'
    ]);

    RoleFactory::new()->create([
        'name' => 'user',
        'code' => 'ROLE_USER'
    ]);

    PollFactory::new()->createMany(7, [
        'owner' => UserFactory::new()->findOrCreate([
            'role' => RoleFactory::repository()->random()->object()
        ])->object(),
        'participants' => UserFactory::new()->many(5)->create([
            'role' => RoleFactory::repository()->random()->object()
        ]),
        'answers' => AnswerFactory::new()->many(4)
    ]);
});


test('find a poll in the database', fn () => PollFactory::repository()->assertExists(['title' => PollFactory::random()->getTitle()]));

test('poll has a title', fn () => expect(PollFactory::random()->getTitle())->not->toBeNull()->and(PollFactory::random()->getTitle())->toBeString());

test('poll has answers', fn () => assertContainsOnly(\App\Entity\Answer::class, PollFactory::random()->getAnswers()));

test('poll has a question and is a string', fn () => expect(PollFactory::random()->getQuestion())->not->toBeNull()->and(PollFactory::random()->getQuestion())->toBeString());

test('endDate is a datetime', function () {
    $endDate = PollFactory::random()->getEndDate();

    if (is_null($endDate)) {
        assertTrue(true);
    } else {
        assertInstanceOf(\DateTimeInterface::class, $endDate);
    }
});

test('doUsersMustBeConnected is a bool', fn () => expect(PollFactory::random()->getDoUsersMustBeConnected())->toBeBool());

test('poll has an owner', fn () => expect(PollFactory::random()->getOwner())->not->toBeNull());

test('poll has participants', fn () => assertContainsOnly(\App\Entity\User::class, PollFactory::random()->getParticipants()));

test('totalVotes is int', fn () => expect(PollFactory::random()->getTotalVotes())->toBeInt());
