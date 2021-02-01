<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class EntitySetDate
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $entity->setCreatedAt(new \DateTime());
        $entity->setUpdatedAt(new \DateTime());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $entity->setUpdatedAt(new \DateTime());
    }
}
