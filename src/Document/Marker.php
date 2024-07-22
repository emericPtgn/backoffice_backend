<?php

namespace App\Document;

use App\Repository\MarkerRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;

#[MongoDB\Document(repositoryClass: MarkerRepository::class, collection: 'marker')]
class Marker
{

    #[MongoDB\Id]
    #[Groups(['emplacement'])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(['emplacement'])]
    private string $nom;

    #[MongoDB\Field(type: "string")]
    #[Groups(['emplacement'])]
    private string $description;

    #[MongoDB\Field(type: "string")]
    #[Groups(['emplacement'])]
    private string $icone;

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    #[Groups(['emplacement'])]
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
