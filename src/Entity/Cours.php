<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomcours = null;

    #[ORM\Column(length: 255)]
    private ?string $matierecours = null;

    #[ORM\Column(length: 255)]
    private ?string $niveaucours = null;

    /**
     * @var Collection<int, Chapitre>
     */
    #[ORM\OneToMany(targetEntity: Chapitre::class, mappedBy: 'cours')]
    private Collection $cours;

    /**
     * @var Collection<int, chapitre>
     */
    #[ORM\OneToMany(targetEntity: chapitre::class, mappedBy: 'Chapitres')]
    private Collection $chapitre;

    /**
     * @var Collection<int, chapitre>
     */
    #[ORM\OneToMany(targetEntity: chapitre::class, mappedBy: 'courses')]
    private Collection $chapitree;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    private ?Module $module = null;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->chapitre = new ArrayCollection();
        $this->chapitree = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomcours(): ?string
    {
        return $this->nomcours;
    }

    public function setNomcours(string $nomcours): static
    {
        $this->nomcours = $nomcours;

        return $this;
    }

    public function getMatierecours(): ?string
    {
        return $this->matierecours;
    }

    public function setMatierecours(string $matierecours): static
    {
        $this->matierecours = $matierecours;

        return $this;
    }

    public function getNiveaucours(): ?string
    {
        return $this->niveaucours;
    }

    public function setNiveaucours(string $niveaucours): static
    {
        $this->niveaucours = $niveaucours;

        return $this;
    }

    /**
     * @return Collection<int, Chapitre>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Chapitre $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setCours($this);
        }

        return $this;
    }

    public function removeCour(Chapitre $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getCours() === $this) {
                $cour->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, chapitre>
     */
    public function getChapitre(): Collection
    {
        return $this->chapitre;
    }

    public function addChapitre(chapitre $chapitre): static
    {
        if (!$this->chapitre->contains($chapitre)) {
            $this->chapitre->add($chapitre);
            $chapitre->setChapitres($this);
        }

        return $this;
    }

    public function removeChapitre(chapitre $chapitre): static
    {
        if ($this->chapitre->removeElement($chapitre)) {
            // set the owning side to null (unless already changed)
            if ($chapitre->getChapitres() === $this) {
                $chapitre->setChapitres(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, chapitre>
     */
    public function getChapitree(): Collection
    {
        return $this->chapitree;
    }

    public function addChapitree(chapitre $chapitree): static
    {
        if (!$this->chapitree->contains($chapitree)) {
            $this->chapitree->add($chapitree);
            $chapitree->setCourses($this);
        }

        return $this;
    }

    public function removeChapitree(chapitre $chapitree): static
    {
        if ($this->chapitree->removeElement($chapitree)) {
            // set the owning side to null (unless already changed)
            if ($chapitree->getCourses() === $this) {
                $chapitree->setCourses(null);
            }
        }

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
}
