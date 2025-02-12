<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomchapitre = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?Cours $cours = null;

    #[ORM\ManyToOne(inversedBy: 'chapitre')]
    private ?Cours $Chapitres = null;

    #[ORM\ManyToOne(inversedBy: 'chapitree')]
    private ?Cours $courses = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomchapitre(): ?string
    {
        return $this->nomchapitre;
    }

    public function setNomchapitre(string $nomchapitre): static
    {
        $this->nomchapitre = $nomchapitre;

        return $this;
    }

    public function getCours(): ?cours
    {
        return $this->cours;
    }

    public function setCours(?cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    public function getChapitres(): ?Cours
    {
        return $this->Chapitres;
    }

    public function setChapitres(?Cours $Chapitres): static
    {
        $this->Chapitres = $Chapitres;

        return $this;
    }

    public function getCourses(): ?Cours
    {
        return $this->courses;
    }

    public function setCourses(?Cours $courses): static
    {
        $this->courses = $courses;

        return $this;
    }
}
