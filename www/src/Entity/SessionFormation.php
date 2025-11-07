<?php

namespace App\Entity;

use App\Repository\SessionFormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionFormationRepository::class)]
class SessionFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateFin = null;

    #[ORM\Column]
    private ?int $Cout = null;

    #[ORM\ManyToOne(inversedBy: 'sessionFormations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formation $IdFormation = null;

    #[ORM\ManyToOne(inversedBy: 'sessionFormations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $IdProjet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->DateDebut;
    }

    public function setDateDebut(\DateTimeInterface $DateDebut): static
    {
        $this->DateDebut = $DateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->DateFin;
    }

    public function setDateFin(\DateTimeInterface $DateFin): static
    {
        $this->DateFin = $DateFin;

        return $this;
    }

    public function getCout(): ?int
    {
        return $this->Cout;
    }

    public function setCout(int $Cout): static
    {
        $this->Cout = $Cout;

        return $this;
    }

    public function getIdFormation(): ?Formation
    {
        return $this->IdFormation;
    }

    public function setIdFormation(?Formation $IdFormation): static
    {
        $this->IdFormation = $IdFormation;

        return $this;
    }

    public function getIdProjet(): ?Projet
    {
        return $this->IdProjet;
    }

    public function setIdProjet(?Projet $IdProjet): static
    {
        $this->IdProjet = $IdProjet;

        return $this;
    }
}
