<?php

namespace App\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: "App\Repository\ActiviteRepository", collection: "activite")]
class Activite
{
    #[MongoDB\Id(strategy: "AUTO")]
    #[Groups(["activite", "artiste"])]
    protected ?string $id = null;

    #[MongoDB\Field(type: 'string', name: 'nom')]
    #[Groups(["activite", "artiste"])]
    protected ?string $nom = null;

    #[MongoDB\Field(type: 'date', name: 'date')]
    #[Groups(["activite", "artiste"])]
    protected ?\DateTime $date = null;

    #[MongoDB\Field(type: 'string', name: 'formattedDate')]
    #[Groups(["activite", "artiste"])]
    protected ?string $formattedDate = null;

    #[MongoDB\Field(type: 'string', name: 'type')]
    #[Groups(["activite", "artiste"])]
    protected ?string $type = null;

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["activite", "artiste"])]
    protected ?string $description = null;

    #[MongoDB\ReferenceOne(targetDocument: Marker::class)]
    #[Groups(["activite", "artiste"])]
    private ?Marker $marker = null;

    #[MongoDB\ReferenceMany(targetDocument: Artiste::class, cascade: 'persist')]
    #[Groups(["activite"])]
    #[MaxDepth(1)]
    private ?Collection $artistes = null;

    public function __construct()
    {
        $this->artistes = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        if ($date) {
            $utcDate = clone $date;
            $utcDate->setTimezone(new \DateTimeZone("UTC"));
            $this->date = $utcDate;
        } else {
            $this->date = null;
        }
        return $this;
    }

    public function getFormattedDate(): ?string
    {
        if (!$this->date) {
            return null;
        }

        $utcDate = clone $this->date;
        $utcDate->setTimezone(new \DateTimeZone("UTC"));

        return $utcDate->format('Y-m-d\TH:i');
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getMarker(): ?Marker
    {
        return $this->marker;
    }

    public function setMarker(?Marker $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function getArtistes(): Collection
    {
        return $this->artistes;
    }

    public function addArtiste(Artiste $artiste): self
    {
        if (!$this->artistes->contains($artiste)) {
            $this->artistes->add($artiste);
        }
        return $this;
    }

    public function removeArtiste(Artiste $artiste): self
    {
        if ($this->artistes->removeElement($artiste)) {
            $artiste->removeActivite($this); // Mise à jour bidirectionnelle
        }
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
