<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matiere = null;

    #[ORM\Column]
    private ?int $chapitre = null;

    #[ORM\Column]
    private ?float $bestg = null;

    #[ORM\Column]
    private ?int $difficulte = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\Column]
    private ?int $gain = null;

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

    public function getBestg(): ?float
    {
        return $this->bestg;
    }

    public function setBestg(float $bestg): static
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

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getGain(): ?int
    {
        return $this->gain;
    }

    public function setGain(int $gain): static
    {
        $this->gain = $gain;

        return $this;
    }
}
