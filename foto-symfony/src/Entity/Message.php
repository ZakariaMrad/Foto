<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'messages')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idMessage')]
    private ?int $idMessage = null;

    #[ORM\ManyToOne(inversedBy: 'messages', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idChat', referencedColumnName: 'idChat')]
    private ?chat $chat = null;

    #[ORM\ManyToOne(inversedBy: 'messages', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFile', referencedColumnName: 'idFile')]
    private ?File $file = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idGif', referencedColumnName: 'idGif')]
    private ?Gif $gif = null;

    #[ORM\ManyToMany(targetEntity: Foto::class, cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'messageFotos',
        joinColumns: [new ORM\JoinColumn(name: 'idMessage', referencedColumnName: 'idMessage')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idFoto', referencedColumnName: 'idFoto')]
    )]
    private Collection $foto;

    #[ORM\Column(length: 1024)]
    private ?string $text = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'sentDate')]
    private ?\DateTimeInterface $sentDate = null;

    #[ORM\Column(name: 'isDeleted')]
    private ?bool $isDeleted = null;

    public function __construct()
    {
        $this->foto = new ArrayCollection();
    }

    public function getIdMessage(): ?int
    {
        return $this->idMessage;
    }

    public function getChat(): ?chat
    {
        return $this->chat;
    }

    public function setChat(?chat $chats): static
    {
        $this->chat = $chats;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getgif(): ?Gif
    {
        return $this->gif;
    }

    public function setgif(?Gif $gif): static
    {
        $this->gif = $gif;

        return $this;
    }

    /**
     * @return Collection<int, Foto>
     */
    public function getFoto(): Collection
    {
        return $this->foto;
    }

    public function addFoto(Foto $foto): static
    {
        if (!$this->foto->contains($foto)) {
            $this->foto->add($foto);
        }

        return $this;
    }

    public function removeFoto(Foto $foto): static
    {
        $this->foto->removeElement($foto);

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getSentDate(): ?\DateTimeInterface
    {
        return $this->sentDate;
    }

    public function setSentDate(\DateTimeInterface $sentDate): static
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }
}
