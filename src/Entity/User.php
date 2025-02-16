<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: "user")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", unique: true)]
    private $email;

    #[ORM\Column(type: "string")]
    private $nom;

    #[ORM\Column(type: "string", nullable: true)]
    private $phonenumber;

    #[ORM\Column(type: "string", nullable: true)]
    private $matiere;

    #[ORM\Column(type: "integer", nullable: true)]
    private $experience;

    #[ORM\Column(type: "string", nullable: true)]
    private $reason;

    #[ORM\Column(type: "string")]
    private $password;

    #[ORM\Column(type: "json")]
    private $roles = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $work = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pref = null;

    #[ORM\Column(type: "boolean")]
    private ?bool $isActive = false; // Set default value to false

    public function __construct() {
        $this->isActive = false; // Default value set to false
    }

    // Getters and Setters...
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

    public function setNom(string $nom): self
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
        return null;  // Not needed for modern password hashing algorithms
    }

    public function eraseCredentials(): void
    {
        // If you have temporary sensitive data, clear it here
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
}

