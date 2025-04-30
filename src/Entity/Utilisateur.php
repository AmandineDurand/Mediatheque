<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name: 'Utilisateur')]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: ['emailUtil'], message: 'Un compte avec cet email existe déjà. Veillez vous connecter.')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $nomUtil = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $prenomUtil = null;

    #[ORM\Column(length: 100, unique: true, nullable: false)]
    private ?string $emailUtil = null;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    private ?int $nbContentieux = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $motDePasse = null;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\ManyToMany(targetEntity: Document::class, inversedBy: 'utilisateursAimant')]
    #[ORM\JoinTable(name: 'Aime', 
    joinColumns: [new ORM\JoinColumn(name: 'idUtil', referencedColumnName: 'id')],
    inverseJoinColumns: [new ORM\JoinColumn(name: 'idDoc', referencedColumnName: 'id')]
    )]
    private Collection $documentsAimes;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\ManyToMany(targetEntity: Notification::class, mappedBy: 'utilisateurs')]
    private Collection $notifications;

    /**
     * @var Collection<int, Possede>
     */
    #[ORM\OneToMany(targetEntity: Possede::class, mappedBy: 'utilisateur')]
    private Collection $abonnements;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'utilisateur')]
    private Collection $commandes;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'utilisateur')]
    private Collection $avis;

    /**
     * @var Collection<int, DemandeAbonnement>
     */
    #[ORM\OneToMany(targetEntity: DemandeAbonnement::class, mappedBy: 'utilisateur')]
    private Collection $demandeAbonnements;

    public function __construct()
    {
        $this->documentsAimes = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->demandeAbonnements = new ArrayCollection();
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function getUserIdentifier(): string
    {
        return $this->emailUtil;
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des données temporaires sensibles, les nettoyer ici
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtil(): ?string
    {
        return $this->nomUtil;
    }

    public function setNomUtil(string $nomUtil): static
    {
        $this->nomUtil = $nomUtil;

        return $this;
    }

    public function getPrenomUtil(): ?string
    {
        return $this->prenomUtil;
    }

    public function setPrenomUtil(string $prenomUtil): static
    {
        $this->prenomUtil = $prenomUtil;

        return $this;
    }

    public function getEmailUtil(): ?string
    {
        return $this->emailUtil;
    }

    public function setEmailUtil(string $emailUtil): static
    {
        $this->emailUtil = $emailUtil;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // garanti que tout utilisateur a au moins le role basique
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getNbContentieux(): ?int
    {
        return $this->nbContentieux;
    }

    public function setNbContentieux(?int $nbContentieux): static
    {
        $this->nbContentieux = $nbContentieux;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocumentsAimes(): Collection
    {
        return $this->documentsAimes;
    }

    public function addDocumentsAime(Document $documentsAime): static
    {
        if (!$this->documentsAimes->contains($documentsAime)) {
            $this->documentsAimes->add($documentsAime);
            $documentsAime->addUtilisateursAimant($this);
        }

        return $this;
    }

    public function removeDocumentsAime(Document $documentsAime): static
    {
        if ($this->documentsAimes->removeElement($documentsAime)){
            $documentsAime->removeUtilisateursAimant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->addUtilisateur($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            $notification->removeUtilisateur($this);
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
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Possede>
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Possede $abonnement): static
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements->add($abonnement);
            $abonnement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAbonnement(Possede $abonnement): static
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getUtilisateur() === $this) {
                $abonnement->setUtilisateur(null);
            }
        }

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
            $avi->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUtilisateur() === $this) {
                $avi->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandeAbonnement>
     */
    public function getDemandeAbonnements(): Collection
    {
        return $this->demandeAbonnements;
    }

    public function addDemandeAbonnement(DemandeAbonnement $demandeAbonnement): static
    {
        if (!$this->demandeAbonnements->contains($demandeAbonnement)) {
            $this->demandeAbonnements->add($demandeAbonnement);
            $demandeAbonnement->setUtilisateur($this);
        }

        return $this;
    }

    public function removeDemandeAbonnement(DemandeAbonnement $demandeAbonnement): static
    {
        if ($this->demandeAbonnements->removeElement($demandeAbonnement)) {
            // set the owning side to null (unless already changed)
            if ($demandeAbonnement->getUtilisateur() === $this) {
                $demandeAbonnement->setUtilisateur(null);
            }
        }

        return $this;
    }
}

