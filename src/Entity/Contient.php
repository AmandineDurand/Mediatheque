<?php

namespace App\Entity;

use App\Repository\ContientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Contient')]
#[ORM\Index(name: 'idDoc', columns: ['idDoc'])]
#[ORM\Entity(repositoryClass: ContientRepository::class)]
class Contient
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idCom = null;

    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idDoc = null;

    public function getIdcom(): ?int
    {
        return $this->idCom;
    }

    public function setIdcom(int $idCom): static
    {
        $this->idCom = $idCom;

        return $this;
    }

    public function getIddoc(): ?int
    {
        return $this->idDoc;
    }

    public function setIddoc(int $idDoc): static
    {
        $this->idDoc = $idDoc;

        return $this;
    }
}
