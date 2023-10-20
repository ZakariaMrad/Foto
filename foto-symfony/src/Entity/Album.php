<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use JsonSerializable;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\Table(name: 'albums')]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idAlbum')]
    private ?int $idAlbum = null;

    #[ORM\Column(length: 1024)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'creationDate')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, name: 'modificationDate')]
    private ?\DateTimeInterface $modificationDate = null;

    // #[ORM\JoinTable(
    //     name: 'albumFotos',
    //     joinColumns: [new ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')],
    //     inverseJoinColumns: [new ORM\JoinColumn(name: 'idFoto', referencedColumnName: 'idFoto')]
    // )]
    #[ORM\JoinTable(name:'albumFotos')]
    #[ORM\JoinColumn(name:'idAlbum',referencedColumnName:'idAlbum')]
    #[ORM\InverseJoinColumn(name:'idFoto',referencedColumnName:'idFoto')]
    #[ORM\ManyToMany(targetEntity: Foto::class, inversedBy: 'albums', cascade: ['persist'])]
    private Collection $fotos;

    #[ORM\JoinTable(
        name: 'albumCollaborators',
        joinColumns: [new ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idCollaborator', referencedColumnName: 'idUser')]
    )]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'collaboretedAlbums', cascade: ['persist'])]
    private Collection $collaborators;

    #[ORM\ManyToOne(inversedBy: 'ownedAlbums', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idOwner', referencedColumnName: 'idUser')]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    #[ORM\Column( name: 'isPublic')]
    private ?bool $isPublic = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 10)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'spectatedAlbums', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'albumSpectators',
        joinColumns: [new ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idSpectator', referencedColumnName: 'idUser')]
    )]
    private Collection $spectators;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $grid = null;

    public function __construct()
    {
        $this->fotos = new ArrayCollection();
        $this->collaborators = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->spectators = new ArrayCollection();
    }

    public function getAll(){
        return [
            'idAlbum' => $this->idAlbum,
            'description' => $this->description,
            'notes'=> $this->notes,
            'creationDate' => $this->creationDate,
            'isPublic' => $this->isPublic,
            'title' => $this->title,
            'type' => $this->type,
            'grid' => $this->grid,
            'owner' => $this->owner->getAll(),
            'collaborators' => array_map(function ($collaborator) {
                return $collaborator->getAll();
            }, $this->collaborators->toArray()),
            'spectators' => array_map(function ($spectator) {
                return $spectator->getAll();
            }, $this->spectators->toArray()),
            'fotos' => array_map(function ($foto) {
                return $foto->getAll();
            }, $this->fotos->toArray()),
            

        ];
    }

    public function getIdAlbum(): ?int
    {
        return $this->idAlbum;
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

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getSpectators(): Collection
    {
        return $this->spectators;
    }

    public function addSpectator(User $spectator): static
    {
        if (!$this->spectators->contains($spectator)) {
            $this->spectators->add($spectator);
        }

        return $this;
    }

    public function removeSpectator(User $spectator): static
    {
        $this->spectators->removeElement($spectator);

        return $this;
    }

    public function getGrid(): ?array
    {
        return $this->grid;
    }

    public function setGrid(?array $grid): static
    {
        $this->grid = $grid;

        return $this;
    }
}
