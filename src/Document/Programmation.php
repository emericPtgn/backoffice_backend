<?php

namespace App\Document;

use App\Document\Activite;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: Programmation::class, collection: 'programmation')]
class Programmation {
    #[MongoDB\Id]
    #[Groups(["programmation"])]
    private ?string $id = null;

    #[MongoDB\Field(type: 'string', name: 'titre_programmation')]
    #[Groups(["programmation"])]
    private ?string $titre = null;

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["programmation"])]
    private ?string $description = null;

    #[MongoDB\Field(type: 'date', name: 'date_debut')]
    #[Groups(["programmation"])]
    private ?\DateTime $dateDebut = null;

    #[MongoDB\Field(type: 'date', name: 'date_fin')]
    #[Groups(["programmation"])]
    private ?\DateTime $dateFin = null;

    #[MongoDB\ReferenceMany(targetDocument: Activite::class)]
    #[Groups(["programmation"])]
    private Collection $activites;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?string {
        return $this->id;
    }

    public function getTitre(): ?string {
        return $this->titre;
    }

    public function setTitre(?string $titre): self {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getDateDebut(): ?\DateTime {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): self {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTime {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): self {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getActivites(): Collection
    {
        return $this->activites;
    }
    
    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
        }
        return $this;
    }
    
    public function removeActivite(Activite $activite): self
    {
        $this->activites->removeElement($activite);
        return $this;
    }
}