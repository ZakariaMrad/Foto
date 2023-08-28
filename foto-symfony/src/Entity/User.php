<?php

namespace App\Entity;

use App\Core\Constants;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idUser")]
    private ?int $idUser = null;

    #[ORM\Column(length: 180, unique: true, name: 'email')]
    #[Assert\Email(message: "Your email address ( {{ value }} ) is invalid")]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(name: 'lastName')]
    #[Assert\Length(min: 2, minMessage: "Your last name must contain at least 2 characters")]
    #[Assert\Length(max: 30, maxMessage: "Your last name must contain at most 30 characters")]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(name: 'firstName')]
    #[Assert\Length(min: 2, minMessage: "Your first name must contain at least 2 characters")]
    #[Assert\Length(max: 30, maxMessage: "Your first name must contain at most 30 characters")]
    #[Assert\NotBlank]
    private ?string $firstName = null;


    #[ORM\Column(name: 'userName')]
    #[Assert\Length(min: 1, minMessage: "Your username must contain at least 1 characters")]
    #[Assert\Length(max: 30, maxMessage: "Your username must contain at most 50 characters")]
    #[Assert\NotBlank]
    private ?string $userName = null;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
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

    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }
    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

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
}
