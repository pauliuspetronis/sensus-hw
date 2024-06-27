<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['api'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    /**
     * @var Collection<int, GroundCrewMemberCertification>
     */
    #[ORM\OneToMany(targetEntity: GroundCrewMemberCertification::class, mappedBy: 'certification', orphanRemoval: true)]
    private Collection $groundCrewMemberCertifications;

    public function __construct()
    {
        $this->groundCrewMemberCertifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
            $groundCrewMemberCertification->setCertification($this);
        }

        return $this;
    }

    public function removeGroundCrewMemberCertification(GroundCrewMemberCertification $groundCrewMemberCertification): static
    {
        if ($this->groundCrewMemberCertifications->removeElement($groundCrewMemberCertification)) {
            // set the owning side to null (unless already changed)
            if ($groundCrewMemberCertification->getCertification() === $this) {
                $groundCrewMemberCertification->setCertification(null);
            }
        }

        return $this;
    }
}
