<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Avis')]
#[ORM\Index(name: 'idDoc', columns: ['idDoc'])]
#[ORM\Index(name: 'idUtil', columns: ['idUtil'])]
#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAvis = null;

    #[ORM\Column(nullable: true, options: ["default" => NULL])]
    private ?int $note = NULL;

    #[ORM\Column(length: 255, nullable: true, options: ["default" => 'NULL'])]
    private ?string $commentaire = 'NULL';

    #[ORM\Column]
    private ?int $idDoc = null;

    #[ORM\Column]
    private ?int $idUtil = null;

    public function getIdavis(): ?int
    {
        return $this->idAvis;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

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
