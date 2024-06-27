<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\FlightRegistered;
use App\Service\FlightTaskGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FlightEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FlightTaskGenerator $flightTaskGenerator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FlightRegistered::class => 'onFlightRegistered',
        ];
    }

    public function onFlightRegistered(FlightRegistered $event): void
    {
        $this->flightTaskGenerator->generate($event->getFlight());
    }
}
