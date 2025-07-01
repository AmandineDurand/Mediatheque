<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Document')]
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'livre' => Livre::class,
    'periodique' => Periodique::class,
    'sonore' => Sonore::class,
    'video' => Video::class,
])]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $url;

    // #[ORM\Column(type: Types::STRING, nullable: false)]
    // private ?string $typeDoc = null;

    #[ORM\Column(length: 100, nullable: false)]
    private string $titreDoc;

    // #[ORM\Column(type: Types::TEXT, nullable: false)]
    // private ?string $categorieDoc = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $anneeSortie = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $resumeDoc = null;

    // #[ORM\Column(nullable: false)]
    // private ?int $longueurDoc = null;

    #[ORM\Column(nullable: true)]
    private ?int $stockDoc = NULL;

    // #[ORM\Column(unique: true, nullable: false)]
    // private ?int $numDoc = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'documentsAimes')]
    private Collection $utilisateursAimant;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'documents')]
    private Collection $commandes;
    
    #[ORM\ManyToOne(targetEntity:Auteur::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Auteur $auteur = null;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'document', orphanRemoval: true, cascade: ['remove'])]
    private Collection $avis;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'documents')]
    #[ORM\JoinTable(name: 'Appartient', 
    joinColumns: [new ORM\JoinColumn(name: 'idDoc', referencedColumnName: 'id', onDelete: 'CASCADE')],
    inverseJoinColumns: [new ORM\JoinColumn(name: 'idCat', referencedColumnName: 'id')]
    )]
    private Collection $categories;

    public function __construct()
    {
        $this->utilisateursAimant = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getTypedoc(): ?string
    // {
    //     return $this->typeDoc;
    // }

    // public function setTypedoc(string $typeDoc): static
    // {
    //     $this->typeDoc = $typeDoc;

    //     return $this;
    // }

    public function getTitredoc(): ?string
    {
        return $this->titreDoc;
    }

    public function setTitredoc(string $titreDoc): static
    {
        $this->titreDoc = $titreDoc;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    // public function getCategoriedoc(): ?string
    // {
    //     return $this->categorieDoc;
    // }

    // public function setCategoriedoc(string $categorieDoc): static
    // {
    //     $this->categorieDoc = $categorieDoc;

    //     return $this;
    // }

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

    // public function getLongueurdoc(): ?int
    // {
    //     return $this->longueurDoc;
    // }

    // public function setLongueurdoc(int $longueurDoc): static
    // {
    //     $this->longueurDoc = $longueurDoc;

    //     return $this;
    // }

    public function getStockdoc(): ?int
    {
        return $this->stockDoc;
    }

    public function setStockdoc(?int $stockDoc): static
    {
        $this->stockDoc = $stockDoc;

        return $this;
    }

    // public function getNumdoc(): ?int
    // {
    //     return $this->numDoc;
    // }

    // public function setNumdoc(int $numDoc): static
    // {
    //     $this->numDoc = $numDoc;

    //     return $this;
    // }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateursAimant(): Collection
    {
        return $this->utilisateursAimant;
    }

    public function addUtilisateursAimant(Utilisateur $utilisateursAimant): static
    {
        if (!$this->utilisateursAimant->contains($utilisateursAimant)) {
            $this->utilisateursAimant->add($utilisateursAimant);
            $utilisateursAimant->addDocumentsAime($this);
        }

        return $this;
    }

    public function removeUtilisateursAimant(Utilisateur $utilisateursAimant): static
    {
        if ($this->utilisateursAimant->removeElement($utilisateursAimant)) {
            $utilisateursAimant->removeDocumentsAime($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addDocument($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeDocument($this);
        }

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setDocument($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getDocument() === $this) {
                $avi->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $categorie): static
    {
        if (!$this->categories->contains($categorie)) {
            $this->categories->add($categorie);
            $categorie->addDocument($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $categorie): static
    {
        if ($this->categories->removeElement($categorie)) {
            $categorie->removeDocument($this);
        }

        return $this;
    }
}