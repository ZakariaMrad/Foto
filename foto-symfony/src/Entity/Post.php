<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: 'posts')]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idPost')]
    private ?int $idPost = null;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idOwner', referencedColumnName: 'idUser')]
    private ?User $owner = null;

    #[ORM\Column(length: 1024)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,name:'creationDate')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true,name:'modificationDate')]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')]
    private ?Album $album = null;

    #[ORM\ManyToOne(inversedBy: 'posts', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idFoto', referencedColumnName: 'idFoto')]
    private ?Foto $foto = null;

    #[ORM\Column(name:'isPublic')]
    private ?bool $isPublic = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Like::class, cascade: ['persist'])]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, cascade: ['persist'])]
    private Collection $comments;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getAll(){
        return [
            "idPost" => $this->idPost,
            "owner" => $this->owner->getAll(),
            "description" => $this->description,
            "creationDate" => $this->creationDate,
            "foto" => $this->foto ? $this->foto->getAll() : null,
            "album" => $this->album ? $this->album->getAll() : null,
            "comments" => array_map(function ($comment) {
                return $comment->getAll();
            }, $this->comments->toArray()),
            "likes" => array_map(function ($like) {
                return $like->getAll();
            }, $this->likes->toArray())
        ];
    }

    public function getIdPost(): ?int
    {
        return $this->idPost;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): static
    {
        $this->album = $album;

        return $this;
    }

    public function getFoto(): ?foto
    {
        return $this->foto;
    }

    public function setFoto(?foto $foto): static
    {
        $this->foto = $foto;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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
