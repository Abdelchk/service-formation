<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; 

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Siren = null;

    #[ORM\Column(length: 255)]
    private ?string $Iban = null;

    #[ORM\Column(nullable: true)]
    private ?float $Comission = null;

    #[ORM\OneToOne(inversedBy: 'Client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adresse $idAdresse = null;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'IdClient', orphanRemoval: true)]
    private Collection $projets;

    #[ORM\Column(length: 255)]
    private ?string $Pwd = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->sessionFormations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?string
    {
        return $this->idClient;
    }

    public function setIdClient(?string $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->Siren;
    }

    public function setSiren(string $Siren): static
    {
        $this->Siren = $Siren;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->Iban;
    }

    public function setIban(string $Iban): static
    {
        $this->Iban = $Iban;

        return $this;
    }

    public function getComission(): ?float
    {
        return $this->Comission;
    }

    public function setComission(?float $Comission): static
    {
        $this->Comission = $Comission;

        return $this;
    }

    public function getIdAdresse(): ?Adresse
    {
        return $this->idAdresse;
    }

    public function setIdAdresse(Adresse $idAdresse): static
    {
        $this->idAdresse = $idAdresse;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setIdClient($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getIdClient() === $this) {
                $projet->setIdClient(null);
            }
        }

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->Pwd;
    }

    public function setPwd(string $Pwd): static
    {
        $this->Pwd = $Pwd;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Symfony demande cette méthode mais elle est vide ici car on n'a pas de données sensibles à effacer
    }

    public function getPassword(): ?string
    {
        return $this->Pwd;
    }
}
