<?php

namespace App\Entity;

use App\Repository\AbonnementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Abonnement')]
#[ORM\Entity(repositoryClass: AbonnementRepository::class)]
class Abonnement
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAbo = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $typeAbo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 2, nullable: true, options: ["default" => NULL])]
    private ?string $prixAbo = 'NULL';

    public function getIdabo(): ?int
    {
        return $this->idAbo;
    }

    public function getTypeabo(): ?string
    {
        return $this->typeAbo;
    }

    public function setTypeabo(string $typeAbo): static
    {
        $this->typeAbo = $typeAbo;

        return $this;
    }

    public function getPrixabo(): ?string
    {
        return $this->prixAbo;
    }

    public function setPrixabo(?string $prixAbo): static
    {
        $this->prixAbo = $prixAbo;

        return $this;
    }
}
