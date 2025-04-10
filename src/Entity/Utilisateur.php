<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Utilisateur')]
#[ORM\UniqueConstraint(name: 'emailUtil', columns: ['emailUtil'])]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idUtil = null;

    #[ORM\Column(length: 50)]
    private ?string $nomUtil = null;

    #[ORM\Column(length: 50)]
    private ?string $prenomUtil = null;

    #[ORM\Column(length: 100)]
    private ?string $emailUtil = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $roles = null;

    #[ORM\Column(nullable: true, options: ["default" => NULL])]
    private ?int $nbContentieux = NULL;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    public function getIdutil(): ?int
    {
        return $this->idUtil;
    }

    public function getNomutil(): ?string
    {
        return $this->nomUtil;
    }

    public function setNomutil(string $nomUtil): static
    {
        $this->nomUtil = $nomUtil;

        return $this;
    }

    public function getPrenomutil(): ?string
    {
        return $this->prenomUtil;
    }

    public function setPrenomutil(string $prenomUtil): static
    {
        $this->prenomUtil = $prenomUtil;

        return $this;
    }

    public function getEmailutil(): ?string
    {
        return $this->emailUtil;
    }

    public function setEmailutil(string $emailUtil): static
    {
        $this->emailUtil = $emailUtil;

        return $this;
    }

    public function getRoles(): ?string
    {
        return $this->roles;
    }

    public function setRoles(string $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getNbcontentieux(): ?int
    {
        return $this->nbContentieux;
    }

    public function setNbcontentieux(?int $nbContentieux): static
    {
        $this->nbContentieux = $nbContentieux;

        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotdepasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }
}
