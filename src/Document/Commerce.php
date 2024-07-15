<?php

namespace App\Document;

use App\Repository\CommerceRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: CommerceRepository::class, collection: 'commerce')]
class Commerce
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $nom;

    #[MongoDB\Field(type: "string")]
    private string $description;

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    private Emplacement $emplacement;

    #[MongoDB\Field(type: "string")]
    private string $reseauSocial;

    #[MongoDB\ReferenceOne(targetDocument: TypeCommerce::class)]
    private TypeCommerce $typeCommerce;

    #[MongoDB\ReferenceOne(targetDocument: TypeProduit::class)]
    private TypeProduit $typeProduit;

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

    public function getEmplacement(): Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getReseauSocial(): string
    {
        return $this->reseauSocial;
    }

    public function setReseauSocial(string $reseauSocial): self
    {
        $this->reseauSocial = $reseauSocial;
        return $this;
    }

    public function getTypeCommerce(): TypeCommerce
    {
        return $this->typeCommerce;
    }

    public function setTypeCommerce(TypeCommerce $typeCommerce): self
    {
        $this->typeCommerce = $typeCommerce;
        return $this;
    }

    public function getTypeProduit(): TypeProduit
    {
        return $this->typeProduit;
    }

    public function setTypeProduit(TypeProduit $typeProduit): self
    {
        $this->typeProduit = $typeProduit;
        return $this;
    }
}
