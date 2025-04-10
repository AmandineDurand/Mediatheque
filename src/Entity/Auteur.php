<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Auteur')]
#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAut = null;

    #[ORM\Column(length: 50)]
    private ?string $nomAut = null;

    public function getIdaut(): ?int
    {
        return $this->idAut;
    }

    public function getNomaut(): ?string
    {
        return $this->nomAut;
    }

    public function setNomaut(string $nomAut): static
    {
        $this->nomAut = $nomAut;

        return $this;
    }
}
