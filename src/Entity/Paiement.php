<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse e-mail valide.")]
    private ?string $email = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez selectionner le type de votre carte.")]
    private ?string $type_carte = null;

    #[Assert\Regex(
        pattern: "/^\d{16}$/",  // Validation pour accepter uniquement 16 chiffres
        message: "Le numéro de carte doit être un Visa ou Mastercard valide de 16 chiffres."
    )]
    #[Assert\NotBlank(message: "Veuillez selectionner le numéro de votre carte.")]
    #[ORM\Column(length: 16)]  // Gardez le type `string` avec une longueur maximale de 16 caractères
    private ?string $num_carte = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date d'expiration est obligatoire.")]
    #[Assert\GreaterThan("today", message: "Votre carte a été expirée")]
    private ?\DateTimeInterface $date_expiration = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le CVV est obligatoire.")]
    #[Assert\Regex(
    pattern: "/^\d{3}$/",
    message: "Le CVV doit contenir exactement 3 chiffres."
)]
    private ?int $cvv = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?Abonnement $id_abonnement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_paiement = null;

    #[ORM\ManyToOne]
    private ?User $userid = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTypeCarte(): ?string
    {
        return $this->type_carte;
    }

    public function setTypeCarte(string $type_carte): static
    {
        $this->type_carte = $type_carte;

        return $this;
    }

    public function getNumCarte(): ?string
    {
        return $this->num_carte;
    }

    public function setNumCarte(string $num_carte): static
    {
        $this->num_carte = $num_carte;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(?\DateTimeInterface $date_expiration): static
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }



    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getIdAbonnement(): ?abonnement
    {
        return $this->id_abonnement;
    }

    public function setIdAbonnement(?abonnement $id_abonnement): static
    {
        $this->id_abonnement = $id_abonnement;

        return $this;
    }

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->date_paiement;
    }

    public function setDatePaiement(\DateTimeInterface $date_paiement): static
    {
        $this->date_paiement = $date_paiement;

        return $this;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): static
    {
        $this->userid = $userid;

        return $this;
    }
}
