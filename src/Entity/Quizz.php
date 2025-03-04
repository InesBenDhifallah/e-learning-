<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\validator\constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"vous devez choisir une matiere")]
    private ?string $matiere = null;

    #[ORM\Column]
    #[Assert\NotBlank (message:"vous devez choisir un chapitre")]
    private ?int $chapitre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $bestg = null;

    #[ORM\Column]
    #[Assert\NotBlank (message:"vous devez choisir le niveau de difficulte")]
    private ?int $difficulte = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $best = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gain = null;

    #[ORM\OneToMany(mappedBy: "quizz", targetEntity: Question::class, cascade: ["persist", "remove"])]
    private Collection $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(string $matiere): static
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getChapitre(): ?int
    {
        return $this->chapitre;
    }

    public function setChapitre(int $chapitre): static
    {
        $this->chapitre = $chapitre;
        return $this;
    }

    public function getBestg(): ?string
    {
        return $this->bestg;
    }

    public function setBestg(?string $bestg): static
    {
        $this->bestg = $bestg;
        return $this;
    }

    public function getDifficulte(): ?int
    {
        return $this->difficulte;
    }

    public function setDifficulte(int $difficulte): static
    {
        $this->difficulte = $difficulte;
        return $this;
    }

    public function getBest(): ?string
    {
        return $this->best;
    }

    public function setBest(string $best): static
    {
        $this->best = $best;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getGain(): ?string
    {
        return $this->gain;
    }

    public function setGain(string $gain): static
    {
        $this->gain = $gain;
        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuizz($this);
        }
        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            if ($question->getQuizz() === $this) {
                $question->setQuizz(null);
            }
        }
        return $this;
    }
}
