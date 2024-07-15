<?php

namespace App\Document;

use App\Repository\MarkerRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: MarkerRepository::class, collection: 'marker')]
class Marker
{

    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $nom;

    #[MongoDB\Field(type: "string")]
    private string $description;

    #[MongoDB\Field(type: "string")]
    private string $icone;

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    private Emplacement $emplacement;

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

    public function getEmplacement(): Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }
}
