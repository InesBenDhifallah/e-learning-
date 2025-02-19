<?php

namespace App\Entity;

use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column]
    private bool $estCorrecte = false; // Initialisation par défaut

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'suggestion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;
    

    public function __construct()
    {
        $this->estCorrecte = false; // Valeur par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getEstCorrecte(): bool
    {
        return $this->estCorrecte;
    }

    public function setEstCorrecte(bool $estCorrecte): static
    {
        $this->estCorrecte = $estCorrecte;
        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }
    
    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }
    
}
