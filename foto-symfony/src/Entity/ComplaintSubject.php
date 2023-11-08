<?php

namespace App\Entity;

use App\Repository\ComplaintSubjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintSubjectRepository::class)]
#[ORM\Table(name: 'complaintSubject')]
class ComplaintSubject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idComplaintSubject')]
    private ?int $idComplaintSubject = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idPost', referencedColumnName: 'idPost', nullable: true)]
    private ?Post $Post = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum', nullable: true)]
    private ?Album $Album = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'idFoto', referencedColumnName: 'idFoto', nullable: true)]
    private ?Foto $Foto = null;

    public function getAll(){
        $arr =['idComplaintSubject' => $this->idComplaintSubject,];
        if($this->getFoto()) $arr['foto'] = $this->getFoto()->getAll();
        if($this->getPost()) $arr['post'] = $this->getPost()->getAll();
        if($this->getAlbum()) $arr['album'] = $this->getAlbum()->getAll();

        return $arr;
    }

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

    public function isEmpty(): bool
    {
        return ($this->Album == null && $this->Foto == null && $this->Post == null);
    }
}
