<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

trait PasswordSameAssertion
{
    private function assertSamePassword($user, $password): void
    {
        self::bootKernel();

        $container = self::$container;

        $this->assertNotNull($user->getPassword());
        $this->assertIsString($user->getPassword());
        $this->assertTrue($container->get('security.password_encoder')->isPasswordValid($user, $password));
    }
}
