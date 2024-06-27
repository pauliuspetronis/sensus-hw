<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Flight;
use App\Entity\GroundCrewMemberTask;

class FlightTaskGenerator
{
    public function __construct(
        private readonly GroundCrewMemberProvider $groundCrewMemberProvider,
        private readonly GroundCrewMemberTaskManager $groundCrewMemberTaskManager,
    ) {
    }

    public function generate(Flight $flight): void
    {
        $memberIdsWithFlightTasks = [];
        foreach ($flight->getTasks() as $task) {
            $groundCrewMembers = $this->groundCrewMemberProvider->getByTask($task);
            while (!empty($groundCrewMembers)) {
                $member = array_shift($groundCrewMembers);
                if (!in_array($member->getId(), $memberIdsWithFlightTasks, true)) {
                    $memberTask = new GroundCrewMemberTask();
                    $memberTask->setGroundCrewMember($member);
                    $memberTask->setTask($task);
                    $this->groundCrewMemberTaskManager->register($memberTask);
                    $memberIdsWithFlightTasks[] = $member->getId();

                    break;
                }
            }
        }
    }
}
