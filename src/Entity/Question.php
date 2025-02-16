<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quizz $idq = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(length: 255)]
    private ?string $reponse = null;

    #[ORM\Column(length: 255)]
    private ?string $solution = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdq(): ?Quizz
    {
        return $this->idq;
    }

    public function setIdq(?Quizz $idq): static
    {
        $this->idq = $idq;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(string $solution): static
    {
        $this->solution = $solution;

        return $this;
    }
}
