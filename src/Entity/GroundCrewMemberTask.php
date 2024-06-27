<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TaskStatusEnum;
use App\Repository\GroundCrewMemberTaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GroundCrewMemberTaskRepository::class)]
class GroundCrewMemberTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groundCrewMemberTasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private GroundCrewMember $groundCrewMember;

    #[ORM\ManyToOne(inversedBy: 'groundCrewMemberTasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['api'])]
    private ?Task $task = null;

    #[ORM\Column(enumType: TaskStatusEnum::class, options: ['default' => TaskStatusEnum::pending])]
    #[Groups(['api'])]
    private TaskStatusEnum $status = TaskStatusEnum::pending;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroundCrewMember(): GroundCrewMember
    {
        return $this->groundCrewMember;
    }

    public function setGroundCrewMember(GroundCrewMember $groundCrewMember): static
    {
        $this->groundCrewMember = $groundCrewMember;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getStatus(): TaskStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TaskStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }
}
