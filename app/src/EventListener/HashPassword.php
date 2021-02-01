<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPassword
{
    public function __construct(
        private UserPasswordEncoderInterface $passwordEncoder
    ) {
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $user = $args->getEntity();

        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $user = $args->getEntity();

        if (!$user instanceof User) {
            return;
        }

        $this->encodePassword($user);
    }

    public function encodePassword(User $user): void
    {
        if (!$user->getPlainPassword()) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        );

        $user->setPassword($encoded);

        $user->eraseCredentials();
    }
}
