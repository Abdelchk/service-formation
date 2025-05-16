<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateFacture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DatePaiement = null;

    #[ORM\Column]
    private ?float $Montant = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?projet $projet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->DateFacture;
    }

    public function setDateFacture(\DateTimeInterface $DateFacture): static
    {
        $this->DateFacture = $DateFacture;

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

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getProjet(): ?projet
    {
        return $this->projet;
    }

    public function setProjet(?projet $projet): static
    {
        $this->projet = $projet;

        return $this;
    }
}
