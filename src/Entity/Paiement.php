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

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    private ?Abonnement $id_abonnement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_paiement = null;

    #[ORM\ManyToOne]
    private ?User $userid = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $StripeSessionId = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdAbonnement(): ?Abonnement
    {
        return $this->id_abonnement;
    }

    public function setIdAbonnement(?Abonnement $id_abonnement): static
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

    public function getStripeSessionId(): ?string
    {
        return $this->StripeSessionId;
    }

    public function setStripeSessionId(?string $StripeSessionId): static
    {
        $this->StripeSessionId = $StripeSessionId;
        return $this;
    }
}
