<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idUser')]
    private ?int $idUser = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, name: 'picturePath')]
    private ?string $picturePath = '';

    #[ORM\Column(length: 255)]
    private ?string $location = '';

    #[ORM\Column(length: 1024)]
    private ?string $bio = '';

    #[ORM\Column(length: 50)]
    private ?string $name = '';

    #[ORM\Column(type: Types::DATE_MUTABLE, name: 'birthDate')]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'creationDate')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'collaborators', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'collaboratedAlbums',
        joinColumns: [new ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')]
    )]
    private Collection $collaboretedAlbums;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Album::class, cascade: ['persist'])]
    private Collection $ownedAlbums;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class, cascade: ['persist'])]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, cascade: ['persist'])]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'users_chats',
        joinColumns: [new ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idChat', referencedColumnName: 'idChat')]
    )]
    private Collection $chats;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idBanState', referencedColumnName: 'idBanState')]
    private ?BanState $banState = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Foto::class, cascade: ['persist'])]
    private Collection $fotos;

    #[ORM\ManyToMany(targetEntity: Album::class, mappedBy: 'spectators', cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'spectatedAlbums',
        joinColumns: [new ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idAlbum', referencedColumnName: 'idAlbum')]
    )]
    private Collection $spectatedAlbums;

    public function __construct()
    {
        $this->collaboretedAlbums = new ArrayCollection();
        $this->ownedAlbums = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->fotos = new ArrayCollection();
        $this->spectatedAlbums = new ArrayCollection();
    }

    public function getAll()
    {
        return [
            'idAccount'=> $this->idUser,
            'email' => $this->email,
            'roles' => $this->roles,
            'picturePath' => $this->picturePath,
            'location' => $this->location,
            'bio' => $this->bio,
            'name' => $this->name,
            'birthDate' => $this->birthDate,
            'creationDate' => $this->creationDate,
            'fotos' => $this->fotos

        ];
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }


    public function getPicturePath(): ?string
    {
        return $this->picturePath;
    }

    public function setPicturePath(string $picturePath): static
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): static
    {
        $this->bio = $bio;

        return $this;
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


    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getCollaboretedAlbums(): Collection
    {
        return $this->collaboretedAlbums;
    }

    public function addCollaboretedAlbum(Album $collaboretedAlbum): static
    {
        if (!$this->collaboretedAlbums->contains($collaboretedAlbum)) {
            $this->collaboretedAlbums->add($collaboretedAlbum);
            $collaboretedAlbum->addCollaborator($this);
        }

        return $this;
    }

    public function removeCollaboretedAlbum(Album $collaboretedAlbum): static
    {
        if ($this->collaboretedAlbums->removeElement($collaboretedAlbum)) {
            $collaboretedAlbum->removeCollaborator($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getOwnedAlbums(): Collection
    {
        return $this->ownedAlbums;
    }

    public function addOwnedAlbum(Album $ownedAlbum): static
    {
        if (!$this->ownedAlbums->contains($ownedAlbum)) {
            $this->ownedAlbums->add($ownedAlbum);
            $ownedAlbum->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedAlbum(Album $ownedAlbum): static
    {
        if ($this->ownedAlbums->removeElement($ownedAlbum)) {
            // set the owning side to null (unless already changed)
            if ($ownedAlbum->getOwner() === $this) {
                $ownedAlbum->setOwner(null);
            }
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
            $post->setOwner($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getOwner() === $this) {
                $post->setOwner(null);
            }
        }

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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->addUsers($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            $chat->removeUsers($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function getBanState(): ?BanState
    {
        return $this->banState;
    }

    public function setBanState(?BanState $banState): static
    {
        $this->banState = $banState;

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
            $foto->setUser($this);
        }

        return $this;
    }

    public function removeFoto(Foto $foto): static
    {
        if ($this->fotos->removeElement($foto)) {
            // set the owning side to null (unless already changed)
            if ($foto->getUser() === $this) {
                $foto->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Album>
     */
    public function getSpectatedAlbums(): Collection
    {
        return $this->spectatedAlbums;
    }

    public function addSpectatedAlbum(Album $spectatedAlbum): static
    {
        if (!$this->spectatedAlbums->contains($spectatedAlbum)) {
            $this->spectatedAlbums->add($spectatedAlbum);
            $spectatedAlbum->addSpectator($this);
        }

        return $this;
    }

    public function removeSpectatedAlbum(Album $spectatedAlbum): static
    {
        if ($this->spectatedAlbums->removeElement($spectatedAlbum)) {
            $spectatedAlbum->removeSpectator($this);
        }

        return $this;
    }
}
