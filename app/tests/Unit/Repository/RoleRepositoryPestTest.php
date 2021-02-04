<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Factory\RoleFactory;
use App\Factory\UserFactory;

beforeEach(function () {
    $this->userAdmin = UserFactory::new()->create([
        'username' => 'admin',
        'email' => 'admin@noreply.local',
        'plainPassword' => 'test1234',
        'role' => RoleFactory::new()->create([
            'name' => 'admin',
            'code' => 'ROLE_ADMIN'
        ])->object()
    ])->object();

    $this->users = UserFactory::createMany(4, [
        'role' => RoleFactory::findOrCreate([
            'name' => 'user',
            'code' => 'ROLE_USER'
        ])->object()
    ]);
});

test('roles are in database', fn ($roleName) =>
RoleFactory::repository()->assertExists(['name' => $roleName]))
    ->with(['admin', 'user']);

test('role have the correct code', fn ($code) =>
expect(RoleFactory::repository()->findOneBy(['code' => $code]))->not->toBeNull())
    ->with(['ROLE_ADMIN', 'ROLE_USER']);

test('find at least one user with the role', fn ($roleName) =>
expect(UserFactory::random(['role' => RoleFactory::repository()->findOneBy(['name' => $roleName])])->object())->toBeInstanceOf(\App\Entity\User::class))
    ->with(['admin', 'user']);

afterEach(function () {
    UserFactory::repository()->truncate();
    RoleFactory::repository()->truncate();
});
