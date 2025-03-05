<?php

namespace App\Entity;

use App\Repository\QuizzResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzResultRepository::class)]
class QuizzResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'quizzResults')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'quizzResults')]
    private ?Quizz $quizz = null;

    #[ORM\Column(nullable: true)]
    private ?float $score = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $quizztype = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getQuizz(): ?Quizz
    {
        return $this->quizz;
    }

    public function setQuizz(?Quizz $quizz): static
    {
        $this->quizz = $quizz;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getQuizztype(): ?string
    {
        return $this->quizztype;
    }

    public function setQuizztype(?string $quizztype): static
    {
        $this->quizztype = $quizztype;

        return $this;
    }
}
