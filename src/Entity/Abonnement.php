<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\TypeAbo;

#[ORM\Table(name: 'Abonnement')]
#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: 'string', enumType: TypeAbo::class)]
    private TypeAbo $typeAbo;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true)]
    private ?string $prixAbo = 'NULL';

    /**
     * @var Collection<int, Possede>
     */
    #[ORM\OneToMany(targetEntity: Possede::class, mappedBy: 'abonnement')]
    private Collection $possedes;

    /**
     * @var Collection<int, Fichier>
     */
    #[ORM\OneToMany(targetEntity: Fichier::class, mappedBy: 'abonnement')]
    private Collection $fichiers;

    /**
     * @var Collection<int, DemandeAbonnement>
     */
    #[ORM\OneToMany(targetEntity: DemandeAbonnement::class, mappedBy: 'abonnement')]
    private Collection $demandeAbonnements;

    public function __construct()
    {
        $this->possedes = new ArrayCollection();
        $this->fichiers = new ArrayCollection();
        $this->demandeAbonnements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeabo(): TypeAbo
    {
        return $this->typeAbo;
    }

    public function setTypeabo(TypeAbo $typeAbo): static
    {
        $this->typeAbo = $typeAbo;

        return $this;
    }

    public function getPrixabo(): ?string
    {
        return $this->prixAbo;
    }

    public function setPrixabo(?string $prixAbo): static
    {
        $this->prixAbo = $prixAbo;

        return $this;
    }

    /**
     * @return Collection<int, Possede>
     */
    public function getPossedes(): Collection
    {
        return $this->possedes;
    }

    public function addPossede(Possede $possede): static
    {
        if (!$this->possedes->contains($possede)) {
            $this->possedes->add($possede);
            $possede->setAbonnement($this);
        }

        return $this;
    }

    public function removePossede(Possede $possede): static
    {
        if ($this->possedes->removeElement($possede)) {
            // set the owning side to null (unless already changed)
            if ($possede->getAbonnement() === $this) {
                $possede->setAbonnement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fichier>
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichier $fichier): static
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers->add($fichier);
            $fichier->setAbonnement($this);
        }

        return $this;
    }

    public function removeFichier(Fichier $fichier): static
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getAbonnement() === $this) {
                $fichier->setAbonnement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeAbonnement>
     */
    public function getDemandeAbonnements(): Collection
    {
        return $this->demandeAbonnements;
    }

    public function addDemandeAbonnement(DemandeAbonnement $demandeAbonnement): static
    {
        if (!$this->demandeAbonnements->contains($demandeAbonnement)) {
            $this->demandeAbonnements->add($demandeAbonnement);
            $demandeAbonnement->setAbonnement($this);
        }

        return $this;
    }

    public function removeDemandeAbonnement(DemandeAbonnement $demandeAbonnement): static
    {
        if ($this->demandeAbonnements->removeElement($demandeAbonnement)) {
            // set the owning side to null (unless already changed)
            if ($demandeAbonnement->getAbonnement() === $this) {
                $demandeAbonnement->setAbonnement(null);
            }
        }

        return $this;
    }
}
