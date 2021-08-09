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
        $entity = $event ->getEntityInstance();
        if(!$entity instanceof User){
            return;
        }
        $array = $entity->getRoles();
        array_push($array,["ROLE_USER"]);
    }
}