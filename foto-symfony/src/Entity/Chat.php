<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name: 'chats')]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'idChat')]
    private ?int $idChat = null;

    #[ORM\OneToMany(mappedBy: 'chats', targetEntity: Message::class, cascade: ['persist'])]
    private Collection $messages;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'chats', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'chats_users',
        joinColumns: [new ORM\JoinColumn(name: 'idChat', referencedColumnName: 'idChat')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'idUser', referencedColumnName: 'idUser')])]
    private Collection $users;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: 'creationDate')]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getIdChat(): ?int
    {
        return $this->idChat;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUsers(User $users): static
    {
        if (!$this->users->contains($users)) {
            $this->users->add($users);
        }

        return $this;
    }

    public function removeUsers(User $users): static
    {
        $this->users->removeElement($users);

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
            $message->setChat($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChat() === $this) {
                $message->setChat(null);
            }
        }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
