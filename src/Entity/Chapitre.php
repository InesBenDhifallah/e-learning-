<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du chapitre est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÿ\s]+$/u", 
        message: "Le nom du chapitre ne doit contenir que des lettres et des espaces."
    )]
    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: "chapitres")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Veuillez sélectionner un module.")]
    private ?Module $module = null;

    #[ORM\OneToMany(mappedBy: "chapitre", targetEntity: Cours::class, cascade: ["remove"])]
    private Collection $cours;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description du chapitre est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÿ\s]+$/u", 
        message: "La description ne doit contenir que des lettres et des espaces."
    )]
    private ?string $description = null;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;
        return $this;
    }

    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCours(Cours $cours): static
    {
        if (!$this->cours->contains($cours)) {
            $this->cours->add($cours);
            $cours->setChapitre($this);
        }

        return $this;
    }

    public function removeCours(Cours $cours): static
    {
        if ($this->cours->removeElement($cours)) {
            if ($cours->getChapitre() === $this) {
                $cours->setChapitre(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
