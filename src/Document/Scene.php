<?php

namespace App\Document;

use App\Repository\SceneRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: SceneRepository::class, collection :'scene')]
class Scene
{
    #[MongoDB\Id]
    #[Groups(['scene', 'emplacement'])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(['scene', 'emplacement'])]
    private string $nom;

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    #[Groups(['scene', 'emplacement'])]
    private Emplacement $emplacement;

    #[MongoDB\EmbedOne(targetDocument: Marker::class)]
    #[Groups(['scene', 'marker'])]
    private Marker $marker;

    #[MongoDB\Field(type: 'collection')]
    #[Groups(['scene'])]
    private ?array $photos;

    // Getter and Setter methods

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

    public function getEmplacement(): Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getMarker(): Marker
    {
        return $this->marker;
    }

    public function setMarker(Marker $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function getPhotos() : ? array
    {
        return $this->photos;
    }

    public function setPhotos(?array $photos = null) : self
    {
        $this->photos = $photos;
        return $this;
    }


}
