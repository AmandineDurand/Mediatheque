<?php

namespace App\Entity;

use App\Repository\SonoreRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\FormatSon;

#[ORM\Entity(repositoryClass: SonoreRepository::class)]
class Sonore extends Document
{
    #[ORM\Column]
    private int $dureeSon;

    #[ORM\Column(type: 'string', enumType: FormatSon::class)]
    private FormatSon $formatSon;

    public function getDureeSon(): string
    {
        return $this->dureeSon;
    }

    public function setDureeSon(string $dureeSon): static
    {
        $this->dureeSon = $dureeSon;
        return $this;
    }

    public function getFormatSon(): FormatSon
    {
        return $this->formatSon;
    }

    public function setFormatSon(FormatSon $formatSon): static
    {
        $this->formatSon = $formatSon;
        return $this;
    }
}
