<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Notification')]
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: false)]
    private ?string $objetNotif = null;

    #[ORM\Column(length: 500, nullable: false)]
    private ?string $contenuNotif = null;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'notifications')]
    #[ORM\JoinTable(name: 'Recoit',
    joinColumns: [new ORM\JoinColumn(name: 'idNotif', referencedColumnName: 'id')],
    inverseJoinColumns: [new ORM\JoinColumn(name: 'idUtil', referencedColumnName: 'id')]
    )]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjetnotif(): ?string
    {
        return $this->objetNotif;
    }

    public function setObjetnotif(string $objetNotif): static
    {
        $this->objetNotif = $objetNotif;

        return $this;
    }

    public function getContenunotif(): ?string
    {
        return $this->contenuNotif;
    }

    public function setContenunotif(string $contenuNotif): static
    {
        $this->contenuNotif = $contenuNotif;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->addNotification($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeNotification($this);
        }

        return $this;
    }
}
