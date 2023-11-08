<?php

namespace App\Entity;

use App\Repository\FotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: FotoRepository::class)]
#[ORM\Table(name: 'fotos')]
class Foto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idFoto')]
    private ?int $idFoto = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 1024, nullable:true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255,name:'originalPath',nullable:true)]
    private ?string $originalPath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable:true,name:'modificationDate')]
    private ?\DateTimeInterface $modificationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,name:'uploadDate')]
    private ?\DateTimeInterface $uploadDate = null;

    #[ORM\Column(name:'isNSFW')]
    private ?bool $isNSFW = null;


    #[ORM\JoinTable(name:'albumFotos')]
    #[ORM\JoinColumn(name:'idFoto',referencedColumnName:'idFoto')]
    #[ORM\InverseJoinColumn(name:'idAlbum',referencedColumnName:'idAlbum')]
    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'fotos', cascade: ['persist'])]
    private Collection $albums;

    #[ORM\OneToMany(mappedBy: 'foto', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    #[ORM\ManyToOne(inversedBy: 'fotos', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')]
    private ?User $user = null;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    public function getAll(){
        return [
            'idFoto'=>$this->idFoto,
            'name' => $this->name,
            'description' => $this->description,
            'path' => $this->path,
            'modificationDate' => $this->modificationDate,
            'uploadDate' => $this->uploadDate,
            'isNSFW' => $this->isNSFW,
        ];
    }

    public function getIdFoto(): ?int
    {
        return $this->idFoto;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getOriginalPath(): ?string
    {
        return $this->originalPath;
    }

    public function setOriginalPath(string $originalPath): static
    {
        $this->originalPath = $originalPath;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(\DateTimeInterface $modificationDate): static
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->uploadDate;
    }

    public function setUploadDate(\DateTimeInterface $uploadDate): static
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    public function isIsNSFW(): ?bool
    {
        return $this->isNSFW;
    }

    public function setIsNSFW(bool $isNSFW): static
    {
        $this->isNSFW = $isNSFW;

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): static
    {
        if (!$this->albums->contains($album)) {
            $this->albums->add($album);
            $album->addFoto($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): static
    {
        if ($this->albums->removeElement($album)) {
            $album->removeFoto($this);
        }

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
            $post->setFoto($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getFoto() === $this) {
                $post->setFoto(null);
            }
        }

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
}
