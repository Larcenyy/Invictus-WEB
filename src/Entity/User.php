<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`accounts`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 55)]
    private ?string $username = null;

    #[ORM\Column(length: 55)]
    private ?string $discord = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var string|null
     */
    #[ORM\Column]
    private ?string $ip = null;

    /**
     * @var bool
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private bool $is_verified = false;

    /**
     * @var int
     */
    #[ORM\Column(type: "integer")]
    private int $number_account_ip = 1;

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getDiscord(): ?string
    {
        return $this->discord;
    }

    public function setDiscord(?string $discord): void
    {
        $this->discord = $discord;
    }

    /**
     * @return int
     */
    public function getNumberAccountIp(): int
    {
        return $this->number_account_ip;
    }

    /**
     * @param int $numberAccountIp
     * @return $this
     */
    public function setNumberAccountIp(int $numberAccountIp): static
    {
        $this->number_account_ip = $numberAccountIp;

        return $this;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->roles = [];
        $this->is_verified = false;
    }

    /**
     * @return bool
     */
    public function isIsVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * @param bool $is_verified
     * @return void
     */
    public function setIsVerified(bool $is_verified): void
    {
        $this->is_verified = $is_verified;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     * @return void
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
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
}
