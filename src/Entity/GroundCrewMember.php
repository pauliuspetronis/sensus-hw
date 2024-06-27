<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroundCrewMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GroundCrewMemberRepository::class)]
class GroundCrewMember
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    private ?string $fullName = null;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'groundCrewMembers')]
    #[Groups(['api'])]
    private Collection $skills;

    /**
     * @var Collection<int, GroundCrewMemberCertification>
     */
    #[ORM\OneToMany(targetEntity: GroundCrewMemberCertification::class, mappedBy: 'groundCrewMember', cascade: ['persist'], orphanRemoval: true)]
    #[Groups(['api'])]
    private Collection $groundCrewMemberCertifications;

    /**
     * @var Collection<int, GroundCrewMemberTask>
     */
    #[Groups(['api'])]
    #[ORM\OneToMany(targetEntity: GroundCrewMemberTask::class, mappedBy: 'groundCrewMember', orphanRemoval: true)]
    private Collection $groundCrewMemberTasks;


    public function __construct()
    {
        $this->skills = new ArrayCollection();
        $this->groundCrewMemberCertifications = new ArrayCollection();
        $this->groundCrewMemberTasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    /**
     * @param Collection<int, Skill> $skills
     */
    public function addSkills(Collection $skills): static
    {
        foreach ($skills as $skill) {
            $this->addSkill($skill);
        }

        return $this;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skills->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, GroundCrewMemberCertification>
     */
    public function getGroundCrewMemberCertifications(): Collection
    {
        return $this->groundCrewMemberCertifications;
    }

    public function addGroundCrewMemberCertification(GroundCrewMemberCertification $groundCrewMemberCertification): static
    {
        if (!$this->groundCrewMemberCertifications->contains($groundCrewMemberCertification)) {
            $this->groundCrewMemberCertifications->add($groundCrewMemberCertification);
            $groundCrewMemberCertification->setGroundCrewMember($this);
        }

        return $this;
    }

    public function removeGroundCrewMemberCertification(GroundCrewMemberCertification $groundCrewMemberCertification): static
    {
        if ($this->groundCrewMemberCertifications->removeElement($groundCrewMemberCertification)) {
            // set the owning side to null (unless already changed)
            if ($groundCrewMemberCertification->getGroundCrewMember() === $this) {
                $groundCrewMemberCertification->setGroundCrewMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, GroundCrewMemberTask>
     */
    public function getGroundCrewMemberTasks(): Collection
    {
        return $this->groundCrewMemberTasks;
    }

    /**
     * @param Collection<int, Skill> $skills
     */
    public function hasAllSkills(Collection $skills): bool
    {
        return $skills->forAll(fn (int $key, Skill $value) => $this->skills->contains($value));
    }

    public function markExpiredCertification(): void
    {
        foreach ($this->getGroundCrewMemberCertifications() as $certification) {
            assert($certification instanceof GroundCrewMemberCertification);
            $certification->markAsExpiredIfNecessary();
        }
    }
}
