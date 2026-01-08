<?php

namespace App\Entity\User;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\Auth\RegisterController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\Table(name: '"user"')] // Cudzysłów jest niezbędny dla PostgreSQL
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            controller: RegisterController::class,
            normalizationContext: ['groups' => ['user:read']],
            denormalizationContext: ['groups' => ['user:write']],
            read: false,
            deserialize: false,
            name: 'api_register',
        ),
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_PLAYER = 'ROLE_PLAYER';
    public const ROLE_PARENT = 'ROLE_PARENT';
    public const ROLE_ADMIN  = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $username = null;

    /** @var string[] */
    #[ORM\Column(type: 'json')]
    #[Groups(['user:read', 'user:write'])]
    private array $roles = [];

    #[ORM\Column(name: 'password', type: 'string', length: 255)]
    private string $password = '';

    public function __construct()
    {
        $this->roles = [self::ROLE_PLAYER];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) ($this->username ?? '');
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array(self::ROLE_PLAYER, $roles, true)) {
            $roles[] = self::ROLE_PLAYER;
        }
        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $hashedPassword): self
    {
        $this->password = $hashedPassword;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}