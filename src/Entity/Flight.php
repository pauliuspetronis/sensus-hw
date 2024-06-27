<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\FlightTypeEnum;
use App\Repository\FlightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FlightRepository::class)]
class Flight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    #[Assert\NotBlank]
    private ?string $number = null;

    #[ORM\Column(enumType: FlightTypeEnum::class)]
    #[Groups(['api'])]
    #[Assert\NotBlank]
    private ?FlightTypeEnum $type = null;

    /**
     * @var Collection<int, Task>
     */
    #[Groups(['api'])]
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'flight', cascade: ['persist'], orphanRemoval: true)]
    private Collection $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getType(): ?FlightTypeEnum
    {
        return $this->type;
    }

    public function setType(FlightTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Collection<int, Task> $tasks
     */
    public function addTasks(Collection $tasks): static
    {
        foreach ($tasks as $task) {
            $this->addTask($task);
        }

        return $this;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setFlight($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getFlight() === $this) {
                $task->setFlight(null);
            }
        }

        return $this;
    }
}
