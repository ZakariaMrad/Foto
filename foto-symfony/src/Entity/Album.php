<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAlbum')]
    private ?int $idAlbum = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 1024)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'creationDate')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name: 'modificationDate')]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\ManyToMany(targetEntity: Foto::class, mappedBy: 'albums')]
    private Collection $fotos;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'collaboretedAlbums')]
    private Collection $collaborators;

    #[ORM\ManyToOne(inversedBy: 'ownedAlbums')]
    #[ORM\JoinColumn(name: 'idOwner', referencedColumnName: 'idUser')]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Post::class)]
    private Collection $posts;

    public function __construct()
    {
        $this->fotos = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getIdAlbum(): ?int
    {
        return $this->idAlbum;
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

    /**
     * @return Collection<int, Foto>
     */
    public function getFotos(): Collection
    {
        return $this->fotos;
    }

    public function addFoto(Foto $foto): static
    {
        if (!$this->fotos->contains($foto)) {
            $this->fotos->add($foto);
        }

        return $this;
    }

    public function removeFoto(Foto $foto): static
    {
        $this->fotos->removeElement($foto);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCollaborators(): Collection
    {
        return $this->collaborators;
    }

    public function addCollaborator(User $collaborator): static
    {
        if (!$this->collaborators->contains($collaborator)) {
            $this->collaborators->add($collaborator);
        }

        return $this;
    }

    public function removeCollaborator(User $collaborator): static
    {
        $this->collaborators->removeElement($collaborator);

        return $this;
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

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAlbum($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAlbum() === $this) {
                $post->setAlbum(null);
            }
        }

        return $this;
    }
}
