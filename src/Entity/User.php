<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "user")]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'adresse e-mail est obligatoire.")]
    #[Assert\Email(message: "L'adresse e-mail '{{ value }}' n'est pas valide.")]
    #[Groups(['article:read'])]
    private ?string $email = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-zÀ-ÿ\s]+$/u", 
        message: "Le nom ne doit contenir que des lettres et des espaces."
    )]
    #[Groups(['article:read'])]
    private $nom;

    #[ORM\Column(type: "string", nullable: true)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    private $phonenumber;

    #[ORM\Column(type: "string", nullable: true)]
    #[Assert\NotBlank(message: "Le choix de matiére est obligatoire.")]
    private $matiere;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Assert\NotBlank(message: "L'ecperience est obligatoire même si 0.")]
    private $experience;

    #[ORM\Column(type: "string", nullable: true)]
    #[Assert\NotBlank(message: "Veuillez donnez une raison")]
    private $reason;

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank(message: "Veuillez entrez un mot de passe")]
    private $password;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Veuillez entrez la profession")]
    private ?string $work = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Veuillez entrez votre adresse")]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pref = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $isActive = false;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Module $idmatiere = null; // Set default value to false

    public function __construct() {

        $this->isActive = false; // Default value set to false
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
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

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(?string $matiere): self
    {
        $this->matiere = $matiere;
        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(?int $experience): self
    {
        $this->experience = $experience;
        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null;  
    }

    public function eraseCredentials(): void
    {
      
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;  // The unique identifier (email)
    }

    public function getWork(): ?string
    {
        return $this->work;
    }

    public function setWork(?string $work): static
    {
        $this->work = $work;
        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;
        return $this;
    }

    public function getPref(): ?string
    {
        return $this->pref;
    }

    public function setPref(?string $pref): static
    {
        $this->pref = $pref;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getIdmatiere(): ?Module
    {
        return $this->idmatiere;
    }

    public function setIdmatiere(?Module $idmatiere): static
    {
        $this->idmatiere = $idmatiere;

        return $this;
    }

    /**
     * @return Collection<int, QuizzResult>
     */
    public function getQuizzResults(): Collection
    {
        return $this->quizzResults;
    }

    public function addQuizzResult(QuizzResult $quizzResult): static
    {
        if (!$this->quizzResults->contains($quizzResult)) {
            $this->quizzResults->add($quizzResult);
            $quizzResult->setUser($this);
        }

        return $this;
    }

    public function removeQuizzResult(QuizzResult $quizzResult): static
    {
        if ($this->quizzResults->removeElement($quizzResult)) {
            // set the owning side to null (unless already changed)
            if ($quizzResult->getUser() === $this) {
                $quizzResult->setUser(null);
            }
        }

        return $this;
    }

    
}

