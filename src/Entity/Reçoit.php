<?php

namespace App\Entity;

use App\Repository\ReçoitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Reçoit')]
#[ORM\Index(name: 'idNotif', columns: ['idNotif'])]
#[ORM\Entity(repositoryClass: ReçoitRepository::class)]
class Reçoit
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idUtil = null;

    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    private ?int $idNotif = null;

    public function getIdutil(): ?int
    {
        return $this->idUtil;
    }

    public function setIdutil(int $idUtil): static
    {
        $this->idUtil = $idUtil;

        return $this;
    }

    public function getIdnotif(): ?int
    {
        return $this->idNotif;
    }

    public function setIdnotif(int $idNotif): static
    {
        $this->idNotif = $idNotif;

        return $this;
    }
}
