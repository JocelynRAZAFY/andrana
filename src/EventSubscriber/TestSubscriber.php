<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\GenericEvent;

class TestSubscriber implements EventSubscriberInterface
{
    public function onTestEvent(GenericEvent $event)
    {
        sleep(2);
        dump($event);
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::TEST_EVENT => 'onTestEvent',
        ];
    }
}
