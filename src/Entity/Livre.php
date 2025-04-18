<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre extends Document
{
    #[ORM\Column(length: 50, nullable: false)]
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