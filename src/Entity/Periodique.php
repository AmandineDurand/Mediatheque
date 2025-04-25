<?php

namespace App\Entity;

use App\Repository\PeriodiqueRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\Frequence;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PeriodiqueRepository::class)]
#[UniqueEntity(fields: ['numero'], message: 'Ce numéro existe déjà, veuillez en saisir un autre.')]
class Periodique extends Document
{
    #[ORM\Column(type: 'string', enumType: Frequence::class)]
    private Frequence $frequence;

    #[ORM\Column(unique: true)]
    private int $numero;

    public function getFrequence(): Frequence
    {
        return $this->frequence;
    }

    public function setFrequence(Frequence $frequence): static
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
