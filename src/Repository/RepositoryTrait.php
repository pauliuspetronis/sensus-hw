<?php

declare(strict_types=1);

namespace App\Repository;

trait RepositoryTrait
{
    public function save(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        $flush && $this->getEntityManager()->flush();
    }

    public function remove(object $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        $flush && $this->getEntityManager()->flush();
    }
}
