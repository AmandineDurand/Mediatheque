<?php

namespace App\Entity;

use App\Repository\DemandeAbonnementRepository;
use App\Enum\StatutDemande;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeAbonnementRepository::class)]
class DemandeAbonnement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandeAbonnements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'demandeAbonnements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Abonnement $abonnement = null;

    #[ORM\Column(type: 'string', enumType: StatutDemande::class)]
    private StatutDemande $statut = StatutDemande::EnAttente;

    /**
     * @var Collection<int, Fichier>
     */
    #[ORM\OneToMany(targetEntity: Fichier::class, mappedBy: 'demandeAbonnement', cascade: ['persist'], orphanRemoval: true)]
    private Collection $fichier;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateSoumission = null;

    public function __construct()
    {
        $this->fichier = new ArrayCollection();
        $this->dateSoumission = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(?Abonnement $abonnement): static
    {
        $this->abonnement = $abonnement;

        return $this;
    }

    public function getStatut(): StatutDemande
    {
        return $this->statut;
    }

    public function setStatut(StatutDemande $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, Fichier>
     */
    public function getFichier(): Collection
    {
        return $this->fichier;
    }

    public function addFichier(Fichier $fichier): static
    {
        if (!$this->fichier->contains($fichier)) {
            $this->fichier->add($fichier);
            $fichier->setDemandeAbonnement($this);
        }

        return $this;
    }

    public function removeFichier(Fichier $fichier): static
    {
        if ($this->fichier->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getDemandeAbonnement() === $this) {
                $fichier->setDemandeAbonnement(null);
            }
        }

        return $this;
    }

    public function getDateSoumission(): ?\DateTimeInterface
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(\DateTimeInterface $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }
}
