<?php

namespace App\EventSubscriber;

use App\Entity\User;
//use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @return string[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
//            BeforeEntityPersistedEvent::class => ['hashUserPassword'],
        ];
    }

//    public function hashUserPassword(BeforeEntityPersistedEvent $event)
//    {
//        $entity = $event->getEntityInstance();
//
//        if (!($entity instanceof User)) {
//            return;
//        }
//        $entity->setPassword($this->passwordHasher->hashPassword($entity, $entity->getPassword()));
//    }
}