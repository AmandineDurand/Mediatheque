<?php

namespace App\Entity;

use App\Repository\ContentieuxRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Contentieux')]
#[ORM\Index(name: 'idCom', columns: ['idCom'])]
#[ORM\Entity(repositoryClass: ContentieuxRepository::class)]
class Contentieux
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idCont = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCont = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $typeCont = null;

    #[ORM\Column]
    private ?int $nbDoc = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $infosCont = null;

    #[ORM\Column]
    private ?int $idCom = null;

    public function getIdcont(): ?int
    {
        return $this->idCont;
    }

    public function getDatecont(): ?\DateTimeInterface
    {
        return $this->dateCont;
    }

    public function setDatecont(\DateTimeInterface $dateCont): static
    {
        $this->dateCont = $dateCont;

        return $this;
    }

    public function getTypecont(): ?string
    {
        return $this->typeCont;
    }

    public function setTypecont(string $typeCont): static
    {
        $this->typeCont = $typeCont;

        return $this;
    }

    public function getNbdoc(): ?int
    {
        return $this->nbDoc;
    }

    public function setNbdoc(int $nbDoc): static
    {
        $this->nbDoc = $nbDoc;

        return $this;
    }

    public function getInfoscont(): ?string
    {
        return $this->infosCont;
    }

    public function setInfoscont(string $infosCont): static
    {
        $this->infosCont = $infosCont;

        return $this;
    }

    public function getIdcom(): ?int
    {
        return $this->idCom;
    }

    public function setIdcom(int $idCom): static
    {
        $this->idCom = $idCom;

        return $this;
    }
}
