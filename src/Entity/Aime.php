<?php

namespace App\Entity;

use App\Repository\AimeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Aime')]
#[ORM\Index(name: 'idDoc', columns: ['idDoc'])]
#[ORM\Entity(repositoryClass: AimeRepository::class)]
class Aime
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idUtil = null;

    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idDoc = null;

    public function getIdutil(): ?int
    {
        return $this->idUtil;
    }

    public function setIdutil(int $idUtil): static
    {
        $this->idUtil = $idUtil;

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
