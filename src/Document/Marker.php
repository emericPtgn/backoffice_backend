<?php

namespace App\Document;

use App\Repository\MarkerRepository;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[MongoDB\Document(repositoryClass: MarkerRepository::class, collection: 'marker')]

class Marker
{
    #[MongoDB\Id]
    #[Groups(['marker'])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker', 'artiste'])]
    #[Assert\Unique]
    private ?string $nom = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker'])]
    private ?string $description = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker'])]
    private ?string $icone = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(['marker'])]
    private ?string $type = null;

    #[MongoDB\Field(type: "float")]
    #[Groups(["marker"])]
    private ?float $latitude = null;

    #[MongoDB\Field(type: "float")]
    #[Groups(["marker"])]
    private ?float $longitude = null;

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

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getIcone(): string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;
        return $this;
    }
}
