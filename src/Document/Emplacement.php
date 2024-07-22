<?php

namespace App\Document;

use App\Repository\EmplacementRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'emplacement', repositoryClass: EmplacementRepository::class)]
class Emplacement
{
    #[MongoDB\Id]
    #[Groups(["activite", "commerce", "emplacement"])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite", "commerce", "emplacement"])]
    private string $nom;

    #[MongoDB\Field(type: "float")]
    #[Groups(["activite", "commerce", "emplacement"])]
    private float $latitude;

    #[MongoDB\Field(type: "float")]
    #[Groups(["activite", "commerce", "emplacement"])]
    private float $longitude;

    // Getter and Setter methods

    public function __construct(string $nom = '', float $lat = 0.0, float $long = 0.0)
    {
        $this->nom = $nom;
        $this->latitude = $lat;
        $this->longitude = $long;
    }


    public function getId(): string
    {
        return $this->id;
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

    public function initialize(string $nom, float $lat, float $long): self
    {
        $this->nom = $nom;
        $this->latitude = $lat;
        $this->longitude = $long;
        return $this;
    }

}