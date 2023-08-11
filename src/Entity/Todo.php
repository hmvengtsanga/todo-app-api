<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\StatusEnum;
use App\Repository\TodoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TodoRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['todo:read']],
    denormalizationContext: ['groups' => ['todo:write', 'todo:patch']],
)]
class Todo
{
    // updates createdAt, updatedAt fields
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['todo:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['todo:read', 'todo:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['todo:read', 'todo:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, enumType: StatusEnum::class)]
    #[Assert\NotBlank]
    #[Groups(['todo:read', 'todo:patch'])]
    private StatusEnum $status = StatusEnum::IN_PROGRESS;

    #[ORM\Column]
    #[Groups(['todo:read', 'todo:write'])]
    private bool $public = false;

    #[ORM\ManyToOne(inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['todo:read'])]
    private ?User $owner = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    #[Groups(['todo:read'])]
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    #[Groups(['todo:read'])]
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
