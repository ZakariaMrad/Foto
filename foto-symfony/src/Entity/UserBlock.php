<?php

namespace App\Entity;

use App\Repository\UserBlockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserBlockRepository::class)]
class UserBlock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idUserBlock')]
    private ?int $idUserBlock = null;

    #[ORM\Column(length: 255)]
    private ?string $reason = null;

    #[ORM\OneToOne(inversedBy: 'block', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser', nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $blockDateTime = null;
    public function getAll()
    {
        return [
            'idUserBlock' => $this->idUserBlock,
            'reason' => $this->reason,
            'account' => $this->user->getAll(),
            'blockDateTime' => $this->blockDateTime,
        ];
    }
    public function getIdUserBlock(): ?int
    {
        return $this->idUserBlock;
    }




    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getBlockDateTime(): ?\DateTimeInterface
    {
        return $this->blockDateTime;
    }

    public function setBlockDateTime(\DateTimeInterface $blockDateTime): static
    {
        $this->blockDateTime = $blockDateTime;

        return $this;
    }
}
