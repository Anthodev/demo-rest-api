<?php

declare(strict_types=1);

use App\Factory\AnswerFactory;
use App\Factory\PollFactory;
use App\Factory\RoleFactory;
use App\Factory\UserFactory;

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
        'answers' => AnswerFactory::new()->many(3)
    ]);
});

test('database has an answer', fn () => AnswerFactory::repository()->assertExists(['name' => AnswerFactory::random()->getName()]));

test('answer has a name and is string', fn () => expect(AnswerFactory::random()->getName())->toBeString());

test('answer has votes and is int', fn () => expect(AnswerFactory::random()->getVotes())->toBeInt());

test('answer has a poll', fn () => expect(AnswerFactory::random()->getPoll())->toBeInstanceOf(\App\Entity\Poll::class));
