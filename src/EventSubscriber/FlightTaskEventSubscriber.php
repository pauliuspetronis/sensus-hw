<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\FlightTaskCompleted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FlightTaskEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FlightTaskCompleted::class => 'onFlightTaskCompleted',
        ];
    }

    public function onFlightTaskCompleted(FlightTaskCompleted $event): void
    {
        $event->getTask()->getGroundCrewMember()->markExpiredCertification();
    }
}
