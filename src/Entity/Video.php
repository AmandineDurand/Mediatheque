<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\FormatVid;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video extends Document
{
    #[ORM\Column]
    private int $dureeVid;

    #[ORM\Column(type: 'string', enumType: FormatVid::class)]
    private string $formatVid;

    public function getDureeVid(): string
    {
        return $this->dureeVid;
    }

    public function setDureeVid(string $dureeVid): static
    {
        $this->dureeVid = $dureeVid;
        return $this;
    }

    public function getFormatVid(): int
    {
        return $this->formatVid;
    }

    public function setFormatVid(int $formatVid): static
    {
        $this->formatVid = $formatVid;
        return $this;
    }
}
