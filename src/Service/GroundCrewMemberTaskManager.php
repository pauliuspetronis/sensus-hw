<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\GroundCrewMemberTask;
use App\Enum\TaskStatusEnum;
use App\Event\FlightTaskCompleted;
use App\Repository\GroundCrewMemberTaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class GroundCrewMemberTaskManager
{
    public function __construct(
        private readonly GroundCrewMemberTaskRepository $groundCrewMemberTaskRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function register(GroundCrewMemberTask $task): void
    {
        try {
            $activeTransaction = $this->entityManager->getConnection()->isTransactionActive();

            if (!$activeTransaction) {
                $this->entityManager->beginTransaction();
            }
            $this->groundCrewMemberTaskRepository->save($task, false);

            if (!$activeTransaction) {
                $this->entityManager->flush();
                $this->entityManager->commit();
            }
        } catch (\Throwable $e) {
            if ($this->entityManager->getConnection()->isTransactionActive()) {
                $this->entityManager->rollback();
            }

            throw $e;
        }
    }

    public function complete(GroundCrewMemberTask $task): void
    {
        try {
            $activeTransaction = $this->entityManager->getConnection()->isTransactionActive();

            if (!$activeTransaction) {
                $this->entityManager->beginTransaction();
            }
            $task->setStatus(TaskStatusEnum::completed);
            $this->groundCrewMemberTaskRepository->save($task, false);
            $this->eventDispatcher->dispatch(new FlightTaskCompleted($task));

            if (!$activeTransaction) {
                $this->entityManager->flush();
                $this->entityManager->commit();
            }
        } catch (\Throwable $e) {
            if ($this->entityManager->getConnection()->isTransactionActive()) {
                $this->entityManager->rollback();
            }

            throw $e;
        }
    }
}
