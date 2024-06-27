<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Flight;
use App\Event\FlightRegistered;
use App\Repository\FlightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class FlightManager
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly FlightRepository $flightRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function register(Flight $flight): void
    {
        try {
            $activeTransaction = $this->entityManager->getConnection()->isTransactionActive();

            if (!$activeTransaction) {
                $this->entityManager->beginTransaction();
            }
            $this->flightRepository->save($flight, false);

            $this->eventDispatcher->dispatch(new FlightRegistered($flight));

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
