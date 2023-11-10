<?php

namespace App\Entity;

use App\Repository\ComplaintStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintStatusRepository::class)]
class ComplaintStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idComplaintStatus = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function getIdComplaintStatus(): ?int
    {
        return $this->idComplaintStatus;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
