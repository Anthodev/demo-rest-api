<?php

declare(strict_types=1);

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

    UserFactory::createMany(4, [
        'role' => RoleFactory::findOrCreate([
            'name' => 'user',
            'code' => 'ROLE_USER'
        ])->object()
    ]);
});

test('verify admin user is in database', fn ($user) =>
UserFactory::repository()->assertExists(['username' => $user]))
    ->with(['admin']);

test('verify admin email is valid', fn () =>
expect($this->userAdmin->getEmail())->not->toBeNull()
    ->and($this->userAdmin->getEmail())->toMatch('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i'));

test('verify the admin has the plainPassword field to null', fn () => expect($this->userAdmin->getPlainPassword())->toBeNull());

test('verify the admin user password', fn () =>
$this->assertSamePassword($this->userAdmin, 'test1234'));

test('verify user admin role', function () {
    expect($this->userAdmin->getRole())->toBeInstanceOf(\App\Entity\Role::class);
    expect($this->userAdmin->getRole()->getCode())->toBe('ROLE_ADMIN');
});

it('check if all the users are in the database', function () {
    UserFactory::repository()->assertCount(5);
});
