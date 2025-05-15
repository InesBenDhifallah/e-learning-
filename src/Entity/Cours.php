<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
#[Vich\Uploadable]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre du cours est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÿ\s]+$/u", 
        message: "Le titre ne doit contenir que des lettres et des espaces."
    )]
    private ?string $titre = null;

    #[Vich\UploadableField(mapping: "cours_files", fileNameProperty: "contenuFichier")]
    #[Assert\NotNull(message: "Veuillez télécharger un fichier pour ce cours.")]
    #[Assert\File(
        maxSize: "5M",
        mimeTypes: [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation"
        ],
        mimeTypesMessage: "Seuls les fichiers PDF, DOC, DOCX, PPT et PPTX sont autorisés."
    )]
    private ?File $contenuFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $contenuFichier = null;

    #[ORM\ManyToOne(targetEntity: Chapitre::class, inversedBy: "cours")]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Veuillez sélectionner un chapitre.")]
    private ?Chapitre $chapitre = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description du cours est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÿ\s]+$/u", 
        message: "La description ne doit contenir que des lettres et des espaces.")]
    private ?string $description = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
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
        $this->updatedAt = new \DateTimeImmutable();
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