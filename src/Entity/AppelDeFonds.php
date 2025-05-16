<?php

namespace App\Entity;

use App\Repository\AppelDeFondsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelDeFondsRepository::class)]
class AppelDeFonds
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateEmission = null;

    #[ORM\Column]
    private ?int $Montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DatePaiement = null;

    #[ORM\ManyToOne(inversedBy: 'appelDeFonds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $IdProjet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmission(): ?\DateTimeInterface
    {
        return $this->DateEmission;
    }

    public function setDateEmission(\DateTimeInterface $DateEmission): static
    {
        $this->DateEmission = $DateEmission;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->Montant;
    }

    public function setMontant(int $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->DatePaiement;
    }

    public function setDatePaiement(\DateTimeInterface $DatePaiement): static
    {
        $this->DatePaiement = $DatePaiement;

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
