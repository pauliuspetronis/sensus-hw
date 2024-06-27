<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\GroundCrewMemberTask;

class FlightTaskCompleted
{
    public function __construct(
        private readonly GroundCrewMemberTask $task,
    ) {
    }

    public function getTask(): GroundCrewMemberTask
    {
        return $this->task;
    }
}
