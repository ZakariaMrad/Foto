<?php

namespace App\Entity;

use App\Repository\GifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GifRepository::class)]
class Gif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idGif')]
    private ?int $idGif = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    public function getIdGif(): ?int
    {
        return $this->idGif;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
