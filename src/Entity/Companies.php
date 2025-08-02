<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CompaniesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;

#[ORM\Entity(repositoryClass: CompaniesRepository::class)]

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['company:list']],
        ),
        new Get(
            normalizationContext: ['groups' => ['company:item']]
        ),
        new Post(
            denormalizationContext: ['groups' => ['company:write']],
            security: "is_granted('ROLE_USER')",
            securityMessage: "Vous n'avez pas les droits nécéssaires"
        ),
        new Put(
            denormalizationContext: ['groups' => ['company:write']],
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Vous n'avez pas les droits nécéssaires"
        )
    ]

)]
class Companies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['company:list', 'company:item', 'user:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['company:list', 'company:item', 'user:list', 'user:item', 'user:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['company:list', 'company:item', 'user:list', 'user:item', 'user:write'])]
    private ?string $domain = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(?string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }
}
