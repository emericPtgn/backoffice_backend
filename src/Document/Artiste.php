<?php

namespace App\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(repositoryClass: "App\Repository\ArtisteRepository", collection: 'artiste')]
#[MongoDB\UniqueIndex(keys: ['nom' => 'asc'], options: ['unique' => true])]
class Artiste
{
    #[MongoDB\Id(strategy: "AUTO")]
    #[Groups(["artiste"])]
    protected ?string $id = null;

    #[MongoDB\Field(type: 'string', name: 'nom')]
    #[Groups(["artiste", "marker"])]
    #[Assert\Unique]
    protected ?string $nom = null;

    #[MongoDB\Field(type: 'collection', name: 'style')]
    #[Groups(["artiste"])]
    protected ?array $styles = null; // Changed type to array

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["artiste"])]
    protected ?string $description = null;

    #[MongoDB\EmbedMany(targetDocument: ReseauSocial::class)]
    #[Groups(["artiste"])]
    protected ?Collection $reseauxSociaux;

    #[MongoDB\ReferenceMany(targetDocument: Activite::class, cascade:'persist')]
    #[Groups(["artiste"])]
    #[MaxDepth(1)]
    private ?Collection $activities = null;

    public function __construct()
    {
        $this->styles = []; // Changed to empty array
        $this->reseauxSociaux = new ArrayCollection();
        $this->activities = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getStyles(): ?array
    {
        return $this->styles;
    }

    public function setStyles(array $styles): self
    {
        $this->styles = $styles;
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

    public function getReseauxSociaux(): Collection
    {
        return $this->reseauxSociaux;
    }

    public function addReseauSocial(ReseauSocial $reseauSocial): self
    {
        if (!$this->reseauxSociaux->contains($reseauSocial)) {
            $this->reseauxSociaux->add($reseauSocial);
        }
        return $this;
    }

    public function removeReseauSocial(ReseauSocial $reseauSocial): self
    {
        $this->reseauxSociaux->removeElement($reseauSocial);
        return $this;
    }

    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activities->contains($activite)) {
            $this->activities->add($activite);
        }
        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activities->removeElement($activite)) {
            $activite->removeArtiste($this); // Mise à jour bidirectionnelle
        }
        return $this;
    }
}
