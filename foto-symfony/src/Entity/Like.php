<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`likes`')]
#[ORM\UniqueConstraint(name: 'like_unique_idx', fields: ['user', 'post'])]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idLike')]
    private ?int $idLike = null;

    #[ORM\ManyToOne(inversedBy: 'likes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'likes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idPost', referencedColumnName: 'idPost')]
    private ?Post $post = null;

    public function getAll(){
        return [
            "idLike" => $this->idLike,
            "user" => $this->user->getAll()
        ];
    }

    public function getIdLike(): ?int
    {
        return $this->idLike;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }
}
