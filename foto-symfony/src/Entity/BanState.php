<?php

namespace App\Entity;

use App\Repository\BanStateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BanStateRepository::class)]
#[ORM\Table(name: 'banStates')]
class BanState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idBanState')]
    private ?int $idBanState = null;

    #[ORM\Column(length: 255, name:'stateName')]
    private ?string $stateName = null;

    public function getIdBanState(): ?int
    {
        return $this->idBanState;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function setStateName(string $stateName): static
    {
        $this->stateName = $stateName;

        return $this;
    }
}
