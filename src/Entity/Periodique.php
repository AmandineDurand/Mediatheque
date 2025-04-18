<?php

namespace App\Entity;

use App\Repository\PeriodiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\Frequence;

#[ORM\Entity(repositoryClass: PeriodiqueRepository::class)]
class Periodique extends Document
{
    #[ORM\Column(type: 'string', enumType: Frequence::class)]
    private string $frequence;

    #[ORM\Column]
    private int $numero;

    public function getFrequence(): string
    {
        return $this->frequence;
    }

    public function setFrequence(string $frequence): static
    {
        $this->frequence = $frequence;
        return $this;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;
        return $this;
    }
}
