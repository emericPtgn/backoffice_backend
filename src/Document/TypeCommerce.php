<?php

namespace App\Document;
use App\Repository\TypeCommerceRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: TypeCommerceRepository::class, collection: 'type_commerce')]
class TypeCommerce
{
    #[MongoDB\Id]
    #[Groups(["commerce"])]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["commerce"])]
    #[Assert\Unique]
    private ?string $nom = null;

    #[MongoDB\Field(type:'string', name: 'icone_url')]
    #[Groups(["commerce"])]
    private ?string $icone = null;

    public function __construct(?string $nom = null, ?string $icone = null)
        {
            $this->nom = $nom;
            $this->icone = $icone;
        }



    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getIcone(): ?string
    {
        return $this->icone;
    }

    public function setIcone(?string $icone): self
    {
        $this->icone = $icone;
        return $this;
    }
}