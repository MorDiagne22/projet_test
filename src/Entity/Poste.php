<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\PosteController;
use App\Repository\PosteRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(
            name: 'post',
            uriTemplate: '/postes',
            controller: PosteController::class,
            normalizationContext: ['groups' => ['poste:read']],
            denormalizationContext: ['groups' => ['poste:create']],
            security: "is_granted('ROLE_ADMIN')", //Seule les utilisateurs qui ont le role ROLE_ADMIN peuvent accéder a cette ressource
            securityMessage: "Désolé, vous n'avez pas accés à cette ressource.",
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN')",
            securityMessage: "Désolé, vous n'avez pas accés à cette ressource.",
            normalizationContext: ['groups' => ['poste:read']],
        )
    ],
)]
#[ORM\Entity(repositoryClass: PosteRepository::class)]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['poste:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['poste:read', 'poste:create', 'poste:update'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['poste:read', 'poste:create', 'poste:update'])]
    private ?string $content = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}