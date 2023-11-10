<?php

namespace App\Entity;

use App\Repository\ComplaintSubjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintSubjectRepository::class)]
class ComplaintSubject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idComplaintSubject = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idPost', referencedColumnName: 'idPost', nullable: false)]
    private ?Post $Post = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum', nullable: false)]
    private ?Album $Album = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idFoto', referencedColumnName: 'idFoto', nullable: false)]
    private ?Foto $Foto = null;

    public function getIdComplaintSubject(): ?int
    {
        return $this->idComplaintSubject;
    }

    public function getPost(): ?Post
    {
        return $this->Post;
    }

    public function setPost(?Post $Post): static
    {
        $this->Post = $Post;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->Album;
    }

    public function setAlbum(?Album $Album): static
    {
        $this->Album = $Album;

        return $this;
    }

    public function getFoto(): ?Foto
    {
        return $this->Foto;
    }

    public function setFoto(?Foto $Foto): static
    {
        $this->Foto = $Foto;

        return $this;
    }
}
