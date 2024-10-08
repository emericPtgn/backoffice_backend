<?php

namespace App\Document;

use App\Repository\UserRepository;
use App\Security\Roles;
use DateTime;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Validator\ValidRoles;

#[MongoDB\Document(repositoryClass: UserRepository::class, collection: 'users')]
#[MongoDB\UniqueIndex(keys: ['email' => 'asc'], options: ['unique' => true])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[MongoDB\Id]
    #[Groups(['user'])]
    private string $id;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Unique(fields:['email'], message: 'This email is already in use.')]
    #[Groups(['user'])]
    private ?string $email = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\Unique(fields: ['email'], message: 'new mail should be unique.')]
    #[Groups(['user'])]
    private ?string $newEmail = null;

    #[MongoDB\Field(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[MongoDB\Field(type: 'collection')]
    #[Groups(['user'])]
    private array $roles = [];

    #[MongoDB\Field(type: 'string')]
    #[Groups(['user'])]
    private ?string $nom = null;

    #[MongoDB\Field(type: 'collection')]
    #[Groups(['user'])]
    private ?array $groupes = [];

    #[MongoDB\Field(type: 'date')]
    #[Groups(['user'])]
    private ?DateTime $dateCreation = null;

    #[MongoDB\Field(type: 'date')]
    #[Groups(['user'])]
    private ?DateTime $dateModification = null;

    #[MongoDB\Field(type: 'boolean')]
    #[Groups(['user'])]
    private bool $isVerified = false;

    #[MongoDB\Field(type: 'string')]
    private ?string $verificationToken = null;

    #[MongoDB\Field(type: 'date')]
    private ?DateTime $verificationTokenExpiresAt = null;


    public function __construct()
    {
        $this->dateCreation = new DateTime();
        $this->dateModification = new DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        $this->dateModification = new DateTime();
        return $this;
    }

    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    public function setNewEmail(?string $newEmail): self
    {
        $this->newEmail = $newEmail;
        $this->dateModification = new DateTime();

        return $this;
    }


    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        $this->dateModification = new DateTime();
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->dateModification = new DateTime();
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        $this->dateModification = new DateTime();
        return $this;
    }

    public function getGroupes(): array
    {
        return $this->groupes;
    }

    public function setGroupes(array $groupes): self
    {
        $this->groupes = $groupes;
        $this->dateModification = new DateTime();
        return $this;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTime $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDateModification(): ?DateTime
    {
        return $this->dateModification;
    }

    public function setDateModification(DateTime $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function getVerificationTokenExpiresAt(): ?DateTime
    {
        return $this->verificationTokenExpiresAt;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;
        return $this;
    }

    public function setVerificationTokenExpiresAt(?DateTime $verificationTokenExpiresAt): self
    {
        $this->verificationTokenExpiresAt = $verificationTokenExpiresAt;
        return $this;
    }
}
