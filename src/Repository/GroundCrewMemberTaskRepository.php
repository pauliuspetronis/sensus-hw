<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroundCrewMemberTask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroundCrewMemberTask>
 */
class GroundCrewMemberTaskRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroundCrewMemberTask::class);
    }
}
