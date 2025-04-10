<?php

namespace App\Entity;

use App\Repository\FichierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Fichier')]
#[ORM\Index(name: 'idAbo', columns: ['idAbo'])]
#[ORM\Entity(repositoryClass: FichierRepository::class)]
class Fichier
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idFichier = null;

    #[ORM\Column(length: 100)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 500)]
    private ?string $contenuFichier = null;

    #[ORM\Column]
    private ?int $idAbo = null;

    public function getIdfichier(): ?int
    {
        return $this->idFichier;
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

    public function getIdabo(): ?int
    {
        return $this->idAbo;
    }

    public function setIdabo(int $idAbo): static
    {
        $this->idAbo = $idAbo;

        return $this;
    }
}
