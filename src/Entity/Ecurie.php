<?php

namespace App\Entity;

use App\Repository\EcurieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcurieRepository::class)]
class Ecurie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $idApi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 50)]
    private ?string $localisation = null;

    #[ORM\Column(length: 50)]
    private ?string $directeur = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column(length: 50)]
    private ?string $nomVoiture = null;

    #[ORM\Column(length: 4)]
    private ?string $puissanceVoiture = null;

    #[ORM\Column(length: 50)]
    private ?string $moteurVoiture = null;

    #[ORM\Column(length: 255)]
    private ?string $imageVoiture = null;

    #[ORM\Column(length: 255)]
    private ?string $miniatureVoiture = null;

    #[ORM\Column(length: 10)]
    private ?string $poidsVoiture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdApi(): ?string
    {
        return $this->idApi;
    }

    public function setIdApi(string $idApi): self
    {
        $this->idApi = $idApi;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDirecteur(): ?string
    {
        return $this->directeur;
    }

    public function setDirecteur(string $directeur): self
    {
        $this->directeur = $directeur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getNomVoiture(): ?string
    {
        return $this->nomVoiture;
    }

    public function setNomVoiture(string $nomVoiture): self
    {
        $this->nomVoiture = $nomVoiture;

        return $this;
    }

    public function getPuissanceVoiture(): ?string
    {
        return $this->puissanceVoiture;
    }

    public function setPuissanceVoiture(string $puissanceVoiture): self
    {
        $this->puissanceVoiture = $puissanceVoiture;

        return $this;
    }

    public function getMoteurVoiture(): ?string
    {
        return $this->moteurVoiture;
    }

    public function setMoteurVoiture(string $moteurVoiture): self
    {
        $this->moteurVoiture = $moteurVoiture;

        return $this;
    }

    public function getImageVoiture(): ?string
    {
        return $this->imageVoiture;
    }

    public function setImageVoiture(string $imageVoiture): self
    {
        $this->imageVoiture = $imageVoiture;

        return $this;
    }

    public function getMiniatureVoiture(): ?string
    {
        return $this->miniatureVoiture;
    }

    public function setMiniatureVoiture(string $miniatureVoiture): self
    {
        $this->miniatureVoiture = $miniatureVoiture;

        return $this;
    }

    public function getPoidsVoiture(): ?string
    {
        return $this->poidsVoiture;
    }

    public function setPoidsVoiture(string $poidsVoiture): self
    {
        $this->poidsVoiture = $poidsVoiture;

        return $this;
    }
}
