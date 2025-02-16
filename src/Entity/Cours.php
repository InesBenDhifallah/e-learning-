<?php


namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[Vich\Uploadable]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[Vich\UploadableField(mapping: "cours_files", fileNameProperty: "contenuFichier")]
    private ?File $contenuFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $contenuFichier = null;

    #[ORM\ManyToOne(targetEntity: Chapitre::class, inversedBy: "cours")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chapitre $chapitre = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;


    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenuFile(): ?File
    {
        return $this->contenuFile;
    }

    public function setContenuFile(?File $contenuFile): void
    {
        $this->contenuFile = $contenuFile;
        if ($contenuFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        
    }

    public function getContenuFichier(): ?string
    {
        return $this->contenuFichier;
    }

    public function setContenuFichier(?string $contenuFichier): static
    {
        $this->contenuFichier = $contenuFichier;
        return $this;
    }

    public function getChapitre(): ?Chapitre
    {
        return $this->chapitre;
    }

    public function setChapitre(?Chapitre $chapitre): static
    {
        $this->chapitre = $chapitre;
        return $this;
    }
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new \DateTime();
    }
}

