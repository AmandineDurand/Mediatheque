<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Fichier')]
#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: false)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 500, nullable: false)]
    private ?string $contenuFichier = null;

    #[ORM\ManyToOne(targetEntity:Abonnement::class, inversedBy: 'fichiers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Abonnement $abonnement = null;

    public function getIdfichier(): ?int
    {
        return $this->id;
    }

    public function getNomfichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomfichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getContenufichier(): ?string
    {
        return $this->contenuFichier;
    }

    public function setContenufichier(string $contenuFichier): static
    {
        $this->contenuFichier = $contenuFichier;

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
}
