<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
#[UniqueEntity(fields: ['ISBN'], message: 'Cet ISBN existe déjà, veuillez en saisir un autre.')]
class Livre extends Document
{
    #[ORM\Column(length: 50, nullable: false, unique: true)]
    private string $ISBN;

    #[ORM\Column(nullable: false)]
    private int $nbPages;

    public function getISBN(): string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): static
    {
        $this->ISBN = $ISBN;
        return $this;
    }

    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    public function setNbPages(int $nbPages): static
    {
        $this->nbPages = $nbPages;
        return $this;
    }
}