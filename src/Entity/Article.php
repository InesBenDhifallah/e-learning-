<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\OrderBy;






#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message: "Le titre est requis")
     * @Assert\Length(
     *      min: 3,
     *      max: 255,
     *      minMessage: "Le titre doit contenir au moins {{ limit }} caractères",
     *      maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Title should not be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Title should not exceed {{ limit }} characters."
    )]
    #[Groups(['article:read'])]
    private ?string $title = null;

    /**
     * @Assert\NotBlank(message: "Le contenu est requis")
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Content should not be blank.")]
    #[Assert\Length(
        min: 10,
        minMessage: "Content should be at least {{ limit }} characters long."
    )]
    #[Groups(['article:read'])]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Author should not be blank.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Author name should not exceed {{ limit }} characters."
    )]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Category should not be blank.")]
    private ?string $category = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', cascade: ['remove'], orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", nullable: false)]
    #[Assert\NotNull(message: "L'utilisateur est requis")]
    #[Groups(['article:read'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;
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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    public function getLastCommentDate(): ?\DateTimeImmutable
    {
        if ($this->comments->isEmpty()) {
            return null;
        }

        return $this->comments
            ->matching(Criteria::create()->orderBy(['createdAt' => 'DESC']))
            ->first()
            ->getCreatedAt();
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
}
