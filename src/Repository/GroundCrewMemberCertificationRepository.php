<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroundCrewMemberCertification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroundCrewMemberCertification>
 */
class GroundCrewMemberCertificationRepository extends ServiceEntityRepository
{
    use RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroundCrewMemberCertification::class);
    }
}
