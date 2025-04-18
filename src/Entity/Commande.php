<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Commande')]
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRetrait = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRendu = null;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\ManyToMany(targetEntity: Document::class, inversedBy: 'commandes')]
    #[ORM\JoinTable(name: 'Contient', 
    joinColumns: [new ORM\JoinColumn(name: 'idCom', referencedColumnName: 'id')],
    inverseJoinColumns: [new ORM\JoinColumn(name: 'idDoc', referencedColumnName: 'id')]
    )]
    private Collection $documents;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    /**
     * @var Collection<int, Contentieux>
     */
    #[ORM\OneToMany(targetEntity: Contentieux::class, mappedBy: 'commande')]
    private Collection $contentieux;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->contentieux = new ArrayCollection();
    }

    public function getIdcom(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        $this->documents->removeElement($document);

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Contentieux>
     */
    public function getContentieux(): Collection
    {
        return $this->contentieux;
    }

    public function addContentieux(Contentieux $contentieux): static
    {
        if (!$this->contentieux->contains($contentieux)) {
            $this->contentieux->add($contentieux);
            $contentieux->setCommande($this);
        }

        return $this;
    }

    public function removeContentieux(Contentieux $contentieux): static
    {
        if ($this->contentieux->removeElement($contentieux)) {
            // set the owning side to null (unless already changed)
            if ($contentieux->getCommande() === $this) {
                $contentieux->setCommande(null);
            }
        }

        return $this;
    }
}
