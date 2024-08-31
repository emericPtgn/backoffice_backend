<?php

namespace App\Document;

use App\Repository\MarkerRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(repositoryClass: MarkerRepository::class, collection: 'marker')]
class Marker extends Commerce 
{
    #[MongoDB\Id]
    #[Groups(['marker', "commerce", "activite"])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker', 'artiste', 'commerce', 'activite'])]
    #[Assert\Unique]
    private ?string $nom = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker'])]
    private ?string $description = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker', 'commerce'])]
    private ?string $icone = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker', 'commerce'])]
    private ?string $type = null;

    #[MongoDB\Field(type: "float")]
    #[Groups(["marker", "commerce"])]
    private ?float $latitude = null;

    #[MongoDB\Field(type: "float")]
    #[Groups(["marker", "commerce"])]
    private ?float $longitude = null;

    #[MongoDB\Field(type: 'string')]
    #[Groups(["marker", "commerce"])]
    private ?string $groupe = null;

    #[MongoDB\Field(type: 'string')]
    #[Groups(["marker", "commerce"])]
    private ?string $sousGroupe = null;

    // Constructor with optional parameters
    public function __construct(?float $lat = null, ?float $lng = null, ?string $nom = null, ?string $type = null)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->nom = $nom;
        $this->type = $type;
    }

    // Getters and setters...

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom = null): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone = null): self
    {
        $this->icone = $icone;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type = null): self
    {
        $this->type = $type;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude = null): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude = null): self
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): self
    {
        $this->groupe = $groupe;
        return $this;
    }

    public function getSousGroupe(): ?string
    {
        return $this->sousGroupe;
    }

    public function setSousGroupe(?string $sousGroupe): self
    {
        $this->sousGroupe = $sousGroupe;
        return $this;
    }
}
