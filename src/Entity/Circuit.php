<?php

namespace App\Entity;

use App\Repository\CircuitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'circuit', targetEntity: Emplacement::class, orphanRemoval: true)]
    private Collection $emplacements;

    public function __construct()
    {
        $this->emplacements = new ArrayCollection();
    }

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, Emplacement>
     */
    public function getEmplacements(): Collection
    {
        return $this->emplacements;
    }

    public function addEmplacement(Emplacement $emplacement): self
    {
        if (!$this->emplacements->contains($emplacement)) {
            $this->emplacements->add($emplacement);
            $emplacement->setCircuit($this);
        }

        return $this;
    }

    public function removeEmplacement(Emplacement $emplacement): self
    {
        if ($this->emplacements->removeElement($emplacement)) {
            // set the owning side to null (unless already changed)
            if ($emplacement->getCircuit() === $this) {
                $emplacement->setCircuit(null);
            }
        }

        return $this;
    }
}
