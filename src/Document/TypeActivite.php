<?php

namespace App\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\Groups;


#[MongoDB\Document(collection: 'type_activite')]
class TypeActivite
{
    #[MongoDB\Id]
    #[Groups(["activite"])]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite"])]
    private ?string $nom = null;

    #[MongoDB\Field(type:'string', name: 'icone_url')]
    #[Groups(["activite"])]
    private ?string $icone = null;

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
    public function getIcone(): string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;
        return $this;
    }

}