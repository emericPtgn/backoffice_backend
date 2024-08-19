<?php

namespace App\Document;

use App\Repository\CommerceRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: CommerceRepository::class, collection: 'commerce')]
class Commerce
{
    #[MongoDB\Id]
    #[Groups(["commerce"])]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["commerce"])]
    private ?string $nom = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["commerce"])]
    private ?string $description = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["commerce"])]
    private ?string $reseauSocial = null;

    #[MongoDB\ReferenceOne(targetDocument: Emplacement::class)]
    #[Groups(["commerce"])]
    private ?Emplacement $emplacement = null;

    #[MongoDB\ReferenceOne(targetDocument: Marker::class)]
    #[Groups(["commerce"])]
    private ?Marker $marker = null;

    #[MongoDB\ReferenceOne(targetDocument: TypeCommerce::class)]
    #[Groups(["commerce"])]
    private ?TypeCommerce $typeCommerce = null;

    #[MongoDB\ReferenceOne(targetDocument: TypeProduit::class)]
    #[Groups(["commerce"])]
    private ?TypeProduit $typeProduit = null;

    #[MongoDB\Field(type: 'collection')]
    #[Groups(["commerce"])]
    private ?array $photos = [];

    public function getId(): ?string
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

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getMarker(): ?Marker
    {
        return $this->marker;
    }

    public function setMarker(?Marker $marker): self
    {
        $this->marker = $marker;
        return $this;
    }

    public function getReseauSocial(): ?string
    {
        return $this->reseauSocial;
    }

    public function setReseauSocial(string $reseauSocial): self
    {
        $this->reseauSocial = $reseauSocial;
        return $this;
    }

    public function getTypeCommerce(): ?TypeCommerce
    {
        return $this->typeCommerce;
    }

    public function setTypeCommerce(TypeCommerce $typeCommerce): self
    {
        $this->typeCommerce = $typeCommerce;
        return $this;
    }

    public function getTypeProduit(): ?TypeProduit
    {
        return $this->typeProduit;
    }

    public function setTypeProduit(TypeProduit $typeProduit): self
    {
        $this->typeProduit = $typeProduit;
        return $this;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function setPhotos(?array $photos = null): self
    {
        $this->photos = $photos;
        return $this;
    }
}
