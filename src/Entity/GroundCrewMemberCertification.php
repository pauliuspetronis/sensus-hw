<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroundCrewMemberCertificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GroundCrewMemberCertificationRepository::class)]
class GroundCrewMemberCertification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'groundCrewMemberCertifications')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?GroundCrewMember $groundCrewMember = null;

    #[ORM\ManyToOne(inversedBy: 'groundCrewMemberCertifications')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(['api'])]
    private ?Certification $certification = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['api'])]
    private ?\DateTimeInterface $expirationDate = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['api'])]
    private bool $expired = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroundCrewMember(): ?GroundCrewMember
    {
        return $this->groundCrewMember;
    }

    public function setGroundCrewMember(?GroundCrewMember $groundCrewMember): static
    {
        $this->groundCrewMember = $groundCrewMember;

        return $this;
    }

    public function getCertification(): ?Certification
    {
        return $this->certification;
    }

    public function setCertification(?Certification $certification): static
    {
        $this->certification = $certification;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTimeInterface $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): static
    {
        $this->expired = $expired;

        return $this;
    }

    public function markAsExpiredIfNecessary(): void
    {
        if ($this->expirationDate === null) {
            return;
        }

        $now = new \DateTimeImmutable();
        if ($this->expirationDate < $now) {
            $this->setExpired(true);
        }
    }
}
