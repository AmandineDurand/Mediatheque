<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Table(name: 'Fichier')]
#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'fichier_utilisateur', fileNameProperty: 'nomFichier')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\ManyToOne(inversedBy: 'fichier')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DemandeAbonnement $demandeAbonnement = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity:Abonnement::class, inversedBy: 'fichiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Abonnement $abonnement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
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

    public function getDemandeAbonnement(): ?DemandeAbonnement
    {
        return $this->demandeAbonnement;
    }

    public function setDemandeAbonnement(?DemandeAbonnement $demandeAbonnement): static
    {
        $this->demandeAbonnement = $demandeAbonnement;

        return $this;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if ($file !== null) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
