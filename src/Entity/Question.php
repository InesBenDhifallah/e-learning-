<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\validator\constraints as Assert;
use App\Entity\Quizz;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Quizz::class, inversedBy: "questions")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Quizz $quizz = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\OneToMany(mappedBy: "question", targetEntity: Suggestion::class, cascade: ["persist", "remove"])]
    private Collection $suggestions;

    public function __construct()
    {
        $this->suggestions = new ArrayCollection();
    }

    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;
        return $this;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setQuestion($this);
        }
        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            if ($suggestion->getQuestion() === $this) {
                $suggestion->setQuestion(null);
            }
        }
        return $this;
    }
    
}
