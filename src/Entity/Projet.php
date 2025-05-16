<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column]
    private ?int $BudgetInitial = null;

    #[ORM\Column]
    private ?int $SeuilAlerte = null;

    #[ORM\Column]
    private array $ListeDiffusion = [];

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $IdClient = null;

    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Formateur $FormateurReferent = null;

    /**
     * @var Collection<int, SessionFormation>
     */
    #[ORM\OneToMany(targetEntity: SessionFormation::class, mappedBy: 'IdProjet')]
    private Collection $sessionFormations;

    /**
     * @var Collection<int, AppelDeFonds>
     */
    #[ORM\OneToMany(targetEntity: AppelDeFonds::class, mappedBy: 'IdProjet')]
    private Collection $appelDeFonds;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'projet')]
    private Collection $factures;

    public function __construct()
    {
        $this->sessionFormations = new ArrayCollection();
        $this->appelDeFonds = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getBudgetInitial(): ?string
    {
        return $this->BudgetInitial;
    }

    public function setBudgetInitial(string $BudgetInitial): static
    {
        $this->BudgetInitial = $BudgetInitial;

        return $this;
    }

    public function getSeuilAlerte(): ?int
    {
        return $this->SeuilAlerte;
    }

    public function setSeuilAlerte(int $SeuilAlerte): static
    {
        $this->SeuilAlerte = $SeuilAlerte;

        return $this;
    }

    public function getListeDiffusion(): array
    {
        return $this->ListeDiffusion;
    }

    public function setListeDiffusion(array $ListeDiffusion): static
    {
        $this->ListeDiffusion = $ListeDiffusion;

        return $this;
    }

    public function getIdClient(): ?Client
    {
        return $this->IdClient;
    }

    public function setIdClient(?Client $IdClient): static
    {
        $this->IdClient = $IdClient;

        return $this;
    }

    public function getFormateurReferent(): ?Formateur
    {
        return $this->FormateurReferent;
    }

    public function setFormateurReferent(?Formateur $FormateurReferent): static
    {
        $this->FormateurReferent = $FormateurReferent;

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
            $sessionFormation->setIdProjet($this);
        }

        return $this;
    }

    public function removeSessionFormation(SessionFormation $sessionFormation): static
    {
        if ($this->sessionFormations->removeElement($sessionFormation)) {
            // set the owning side to null (unless already changed)
            if ($sessionFormation->getIdProjet() === $this) {
                $sessionFormation->setIdProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AppelDeFonds>
     */
    public function getAppelDeFonds(): Collection
    {
        return $this->appelDeFonds;
    }

    public function addAppelDeFond(AppelDeFonds $appelDeFond): static
    {
        if (!$this->appelDeFonds->contains($appelDeFond)) {
            $this->appelDeFonds->add($appelDeFond);
            $appelDeFond->setIdProjet($this);
        }

        return $this;
    }

    public function removeAppelDeFond(AppelDeFonds $appelDeFond): static
    {
        if ($this->appelDeFonds->removeElement($appelDeFond)) {
            // set the owning side to null (unless already changed)
            if ($appelDeFond->getIdProjet() === $this) {
                $appelDeFond->setIdProjet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setProjet($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getProjet() === $this) {
                $facture->setProjet(null);
            }
        }

        return $this;
    }
}
