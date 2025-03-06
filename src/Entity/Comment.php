<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(
        message: 'Le commentaire ne peut pas être vide'
    )]
    #[Assert\Length(
        min: 2,
        max: 1000,
        minMessage: 'Le commentaire doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le commentaire ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Type(
        type: 'string',
        message: 'Le contenu doit être une chaîne de caractères'
    )]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull(
        message: 'La date de création est obligatoire'
    )]
    #[Assert\Type(
        type: \DateTimeImmutable::class,
        message: 'La date doit être valide'
    )]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(
        message: 'L\'article est obligatoire'
    )]
    #[Assert\Valid]
    private ?Article $article = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(
        message: 'L\'utilisateur est obligatoire'
    )]
    #[Assert\Valid]
    private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
