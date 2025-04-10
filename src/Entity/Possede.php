<?php

namespace App\Entity;

use App\Repository\PossedeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Possede')]
#[ORM\Index(name: 'idAbo', columns: ['idAbo'])]
#[ORM\Entity(repositoryClass: PossedeRepository::class)]
class Possede
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idUtil = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ["default" => 'NULL'])]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column]
    private ?int $idAbo = null;

    public function getIdutil(): ?int
    {
        return $this->idUtil;
    }

    public function setIdutil(int $idUtil): static
    {
        $this->idUtil = $idUtil;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDatedebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

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
