<?php

namespace App\Document;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'type_produit')]
class TypeProduit
{
    #[MongoDB\Id]
    #[Groups(["commerce"])]
    private string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(["commerce"])]
    private string $nom;

    #[MongoDB\Field(type:'string', name: 'icone_url')]
    #[Groups(["commerce"])]
    private string $icone;

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