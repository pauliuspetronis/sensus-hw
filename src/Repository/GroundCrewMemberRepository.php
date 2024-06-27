<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\GroundCrewMember;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroundCrewMember>
 */
class GroundCrewMemberRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroundCrewMember::class);
    }

    /**
     * @param Collection<int, Skill> $skills
     *
     * @return GroundCrewMember[]
     */
    public function findBySkills(Collection $skills): array
    {
        return $this->createQueryBuilder('gcm')
            ->andWhere(':skills MEMBER OF gcm.skills')
            ->setParameter('skills', $skills)
            ->getQuery()
            ->getResult();
    }
}
