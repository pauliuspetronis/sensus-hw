<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Flight;

class FlightRegistered
{
    public function __construct(
        private readonly Flight $flight
    ) {
    }

    public function getFlight(): Flight
    {
        return $this->flight;
    }
}
