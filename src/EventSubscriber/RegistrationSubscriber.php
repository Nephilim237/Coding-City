<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class RegistrationSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->setUserCreatedDate($args);
    }

    private function setUserCreatedDate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }

        $date = new \DateTimeImmutable();
        $entity->setCreatedAt($date);
    }
}