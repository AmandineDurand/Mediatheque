<?php

namespace App\Entity;

use App\Repository\ContentieuxRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\TypeCont;

#[ORM\Table(name: 'Contentieux')]
#[ORM\Entity(repositoryClass: ContentieuxRepository::class)]
class Contentieux
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $dateCont = null;

    #[ORM\Column(type: 'string', enumType: TypeCont::class)]
    private string $typeCont;

    #[ORM\Column(nullable: true)]
    private ?int $nbDoc = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infosCont = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'contentieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    public function getIdcont(): ?int
    {
        return $this->id;
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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
