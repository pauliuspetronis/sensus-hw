<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GroundCrewMember;
use App\Entity\Task;
use App\Repository\GroundCrewMemberRepository;

class GroundCrewMemberProvider
{
    public function __construct(
        private readonly GroundCrewMemberRepository $groundCrewMemberRepository,
    ) {
    }


    /**
     * @return GroundCrewMember[]
     */
    public function getByTask(Task $task): array
    {
        $members = $this->groundCrewMemberRepository->findBySkills($task->getRequiredSkills());

        foreach ($members as $key => $member) {
            if (!$member->hasAllSkills($task->getRequiredSkills())) {
                unset($members[$key]);
            }
        }

        return array_values($members);
    }
}
