<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Document')]
#[ORM\UniqueConstraint(name: 'numDoc', columns: ['numDoc'])]
#[ORM\Index(name: 'idAut', columns: ['idAut'])]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idDoc = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $typeDoc = null;

    #[ORM\Column(length: 100)]
    private ?string $titreDoc = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $categorieDoc = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $anneeSortie = null;

    #[ORM\Column(length: 500)]
    private ?string $resumeDoc = null;

    #[ORM\Column]
    private ?int $longueurDoc = null;

    #[ORM\Column(nullable: true, options: ["default" => NULL])]
    private ?int $stockDoc = NULL;

    #[ORM\Column]
    private ?int $numDoc = null;

    #[ORM\Column]
    private ?int $idAut = null;

    public function getIddoc(): ?int
    {
        return $this->idDoc;
    }

    public function getTypedoc(): ?string
    {
        return $this->typeDoc;
    }

    public function setTypedoc(string $typeDoc): static
    {
        $this->typeDoc = $typeDoc;

        return $this;
    }

    public function getTitredoc(): ?string
    {
        return $this->titreDoc;
    }

    public function setTitredoc(string $titreDoc): static
    {
        $this->titreDoc = $titreDoc;

        return $this;
    }

    public function getCategoriedoc(): ?string
    {
        return $this->categorieDoc;
    }

    public function setCategoriedoc(string $categorieDoc): static
    {
        $this->categorieDoc = $categorieDoc;

        return $this;
    }

    public function getAnneesortie(): ?\DateTimeInterface
    {
        return $this->anneeSortie;
    }

    public function setAnneesortie(\DateTimeInterface $anneeSortie): static
    {
        $this->anneeSortie = $anneeSortie;

        return $this;
    }

    public function getResumedoc(): ?string
    {
        return $this->resumeDoc;
    }

    public function setResumedoc(string $resumeDoc): static
    {
        $this->resumeDoc = $resumeDoc;

        return $this;
    }

    public function getLongueurdoc(): ?int
    {
        return $this->longueurDoc;
    }

    public function setLongueurdoc(int $longueurDoc): static
    {
        $this->longueurDoc = $longueurDoc;

        return $this;
    }

    public function getStockdoc(): ?int
    {
        return $this->stockDoc;
    }

    public function setStockdoc(?int $stockDoc): static
    {
        $this->stockDoc = $stockDoc;

        return $this;
    }

    public function getNumdoc(): ?int
    {
        return $this->numDoc;
    }

    public function setNumdoc(int $numDoc): static
    {
        $this->numDoc = $numDoc;

        return $this;
    }

    public function getIdaut(): ?int
    {
        return $this->idAut;
    }

    public function setIdaut(int $idAut): static
    {
        $this->idAut = $idAut;

        return $this;
    }
}
