<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $CoutHt = null;

    #[ORM\Column]
    private ?int $TauxTva = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $DateFormation = null;

    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateur $Formateur = null;

    /**
     * @var Collection<int, SessionFormation>
     */
    #[ORM\OneToMany(targetEntity: SessionFormation::class, mappedBy: 'IdFormation')]
    private Collection $sessionFormations;

    public function __construct()
    {
        $this->sessionFormations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCoutHt(): ?float
    {
        return $this->CoutHt;
    }

    public function setCoutHt(float $CoutHt): static
    {
        $this->CoutHt = $CoutHt;

        return $this;
    }

    public function getTauxTva(): ?int
    {
        return $this->TauxTva;
    }

    public function setTauxTva(int $TauxTva): static
    {
        $this->TauxTva = $TauxTva;

        return $this;
    }

    public function getDateFormation(): ?\DateTimeInterface
    {
        return $this->DateFormation;
    }

    public function setDateFormation(\DateTimeInterface $DateFormation): static
    {
        $this->DateFormation = $DateFormation;

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->Formateur;
    }

    public function setFormateur(?Formateur $Formateur): static
    {
        $this->Formateur = $Formateur;

        return $this;
    }

    /**
     * @return Collection<int, SessionFormation>
     */
    public function getSessionFormations(): Collection
    {
        return $this->sessionFormations;
    }

    public function addSessionFormation(SessionFormation $sessionFormation): static
    {
        if (!$this->sessionFormations->contains($sessionFormation)) {
            $this->sessionFormations->add($sessionFormation);
            $sessionFormation->setIdFormation($this);
        }

        return $this;
    }

    public function removeSessionFormation(SessionFormation $sessionFormation): static
    {
        if ($this->sessionFormations->removeElement($sessionFormation)) {
            // set the owning side to null (unless already changed)
            if ($sessionFormation->getIdFormation() === $this) {
                $sessionFormation->setIdFormation(null);
            }
        }

        return $this;
    }
}
