<?php

declare(strict_types=1);

use App\Entity\User;
use App\Factory\RoleFactory;
use App\Factory\UserFactory;

beforeEach(
    function () {
        UserFactory::createMany(
            4,
            [
                'role' => RoleFactory::findOrCreate(
                    [
                        'name' => 'user',
                        'code' => 'ROLE_USER'
                    ]
                )->object()
            ]
        );
    }
);

test(
    'GET requests',
    function (string $url) {
        $client = static::createClient();
        $client->request('GET', $url);
        $this->assertResponseIsSuccessful();
    }
)->with(
    static function (): ?Generator {
        yield ['/api/user'];
        yield ['/api/user/1'];
        yield ['/api/user/2'];
        yield ['/api/user/3'];
    }
);

test(
    'POST requests',
    function (string $url, array $content) {
        $client = static::createClient();
        $client->request(
            'POST',
            $url,
            [],
            [],
            ['Content-Type' => 'application/json'],
            json_encode($content)
        );

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        expect($response->getContent())->toBeJson();
    }
)->with(
    static function (): ?Generator {
        yield [
            '/api/user',
            [
                'username' => 'admin',
                'password' => 'test1234',
                'email' => 'admin@noreply.com',
                'role' => 'ROLE_ADMIN'
            ],
        ];
    }
);

test(
    'PUT requests',
    function (string $url, array $content) {
        $adminUser = UserFactory::new()->create(
            [
                'username' => 'admin',
                'email' => 'admin@noreply.local',
                'plainPassword' => 'test1234',
                'role' => RoleFactory::new()->create(
                    [
                        'name' => 'admin',
                        'code' => 'ROLE_ADMIN'
                    ]
                )->object()
            ]
        )->object();

        $client = static::createClient();
        $client->request('GET', $url . '/' . $adminUser->getId());

        $adminUser = json_decode($client->getResponse()->getContent());
        expect($adminUser->username)->toBe('admin');

        $client->request(
            'PUT',
            $url . '/' . $adminUser->id,
            [],
            [],
            ['Content-Type' => 'application/json'],
            json_encode($content)
        );

        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        expect($response->getContent())->toBeJson()->and(
            json_decode($response->getContent())->email
        )->toBe('test@noreply.com');
    }
)->with(
    static function (): ?Generator {
        yield [
            '/api/user',
            [
                'username' => 'admin',
                'password' => 'test1234',
                'email' => 'test@noreply.com',
                'role' => 'ROLE_ADMIN'
            ],
        ];
    }
);

test(
    'DELETE request',
    function () {
        $randomUser = UserFactory::random()->object();
        expect($randomUser)->toBeInstanceOf(User::class);

        $client = static::createClient();
        $client->request('DELETE', '/api/user/' . $randomUser->getId());

        $this->assertResponseIsSuccessful();
        expect(count(UserFactory::repository()->findAll()))->toBe(3);
    }
);
