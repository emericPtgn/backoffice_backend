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
}
