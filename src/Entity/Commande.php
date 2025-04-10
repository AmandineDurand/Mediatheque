<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Commande')]
#[ORM\Index(name: 'idUtil', columns: ['idUtil'])]
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idCom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ["default" => 'NULL'])]
    private ?\DateTimeInterface $dateCom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ["default" => 'NULL'])]
    private ?\DateTimeInterface $dateRetrait = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true, options: ["default" => 'NULL'])]
    private ?\DateTimeInterface $dateRendu = null;

    #[ORM\Column]
    private ?int $idUtil = null;

    public function getIdcom(): ?int
    {
        return $this->idCom;
    }

    public function getDatecom(): ?\DateTimeInterface
    {
        return $this->dateCom;
    }

    public function setDatecom(?\DateTimeInterface $dateCom): static
    {
        $this->dateCom = $dateCom;

        return $this;
    }

    public function getDateretrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateretrait(?\DateTimeInterface $dateRetrait): static
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getDaterendu(): ?\DateTimeInterface
    {
        return $this->dateRendu;
    }

    public function setDaterendu(?\DateTimeInterface $dateRendu): static
    {
        $this->dateRendu = $dateRendu;

        return $this;
    }

    public function getIdutil(): ?int
    {
        return $this->idUtil;
    }

    public function setIdutil(int $idUtil): static
    {
        $this->idUtil = $idUtil;

        return $this;
    }
}
