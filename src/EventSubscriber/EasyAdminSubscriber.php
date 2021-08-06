<?php

namespace App\EventSubscriber;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

class EasyAdminSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return[
            BeforeEntityUpdatedEvent::class => ['beforeEntityUpdated'],
        ];
    }

    public function beforeEntityUpdated(BeforeEntityUpdatedEvent $event)
    {
    }
}