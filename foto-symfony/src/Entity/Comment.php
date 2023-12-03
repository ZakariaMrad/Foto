<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idComment')]
    private ?int $idComment = null;

    #[ORM\ManyToOne(inversedBy: 'comments', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idPost', referencedColumnName: 'idPost')]
    private ?post $post = null;

    #[ORM\Column(length: 1024)]
    private ?string $text = null;

    public function getAll(){
        return [
            "idComment" => $this->idComment,
            "user" => $this->user->getAll(),
            "text" => $this->text,
        ];
    }

    public function getIdComment(): ?int
    {
        return $this->idComment;
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

    public function getPost(): ?post
    {
        return $this->post;
    }

    public function setPost(?post $post): static
    {
        $this->post = $post;

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
}
