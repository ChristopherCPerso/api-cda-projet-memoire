<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['user:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['user:item']],
        ),
        new Post(
            processor: UserPasswordHasher::class,
            normalizationContext: ['groups' => ['user:item']],
            denormalizationContext: ['groups' => ['user:write']],
        ),
        new Patch(
            denormalizationContext: ['groups' => ['user:write']],
            security: "is_granted('ROLE_ADMIN') or object === user",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN') or object === user",
            securityMessage: "Vous n'avez pas les droit nécéssaire pour effectuer cette opération."
        )
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:list', 'user:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:list', 'user:item', 'user:write', 'restaurant:list', 'restaurant:item', 'review:list', 'review:item'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:list', 'user:item', 'user:write', 'restaurant:list', 'restaurant:item', 'review:list', 'review:item'])]
    private ?string $lastname = null;

    #[ORM\Column(unique: true, length: 75)]
    #[Groups(['user:list', 'user:item', 'user:write', 'restaurant:list', 'restaurant:item'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:write', 'user:update'])]
    private ?string $plainPassword = null;

    #[ORM\Column]
    #[Groups(['user:list', 'user:item', 'user:write'])]
    private ?bool $isAdmin = null;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:list', 'user:item', 'user:write'])]
    private array $roles = ['ROLE_USER'];

    #[ORM\Column(nullable: true)]
    #[Groups(['user:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Companies::class, cascade: ["persist"])]
    #[Groups(['user:list', 'user:item', 'user:write'])]
    private ?Companies $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompany(): ?Companies
    {
        return $this->company;
    }

    public function setCompany(?Companies $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        // S'assurer que ROLE_USER est toujours présent et sans doublon
        $roles[] = 'ROLE_USER';
        $this->roles = array_values(array_unique($roles));

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles temporaires, efface-les ici
    }
}
