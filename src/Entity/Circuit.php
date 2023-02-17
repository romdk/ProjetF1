<?php

namespace App\Entity;

use App\Repository\CircuitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CircuitRepository::class)]
class Circuit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $idApi = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 3)]
    private ?string $nbVirages = null;

    #[ORM\Column(length: 10)]
    private ?string $longueur = null;

    #[ORM\Column(length: 20)]
    private ?string $recordTour = null;

    #[ORM\Column(length: 50)]
    private ?string $typeCircuit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $header = null;

    #[ORM\Column(length: 255)]
    private ?string $layout = null;

    #[ORM\Column(length: 255)]
    private ?string $map = null;

    #[ORM\ManyToOne(inversedBy: 'circuits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pays $pays = null;

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

    public function getNbVirages(): ?string
    {
        return $this->nbVirages;
    }

    public function setNbVirages(string $nbVirages): self
    {
        $this->nbVirages = $nbVirages;

        return $this;
    }

    public function getLongueur(): ?string
    {
        return $this->longueur;
    }

    public function setLongueur(string $longueur): self
    {
        $this->longueur = $longueur;

        return $this;
    }

    public function getRecordTour(): ?string
    {
        return $this->recordTour;
    }

    public function setRecordTour(string $recordTour): self
    {
        $this->recordTour = $recordTour;

        return $this;
    }

    public function getTypeCircuit(): ?string
    {
        return $this->typeCircuit;
    }

    public function setTypeCircuit(string $typeCircuit): self
    {
        $this->typeCircuit = $typeCircuit;

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

    public function getHeader(): ?string
    {
        return $this->header;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getLayout(): ?string
    {
        return $this->layout;
    }

    public function setLayout(string $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function getMap(): ?string
    {
        return $this->map;
    }

    public function setMap(string $map): self
    {
        $this->map = $map;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): self
    {
        $this->pays = $pays;

        return $this;
    }
}
