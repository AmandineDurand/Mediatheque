<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'Notification')]
#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idNotif = null;

    #[ORM\Column(length: 50)]
    private ?string $objetNotif = null;

    #[ORM\Column(length: 500)]
    private ?string $contenuNotif = null;

    public function getIdnotif(): ?int
    {
        return $this->idNotif;
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
}
