<?php

namespace App\Document;

use App\Repository\DedicaceRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\SerializedName;

#[MongoDB\Document(repositoryClass: DedicaceRepository::class, collection: 'dedicace')]
class Dedicace extends Activite
{
    #[MongoDB\Id(strategy: "AUTO")]
    #[SerializedName("id")]
    protected string $id;
    
    #[MongoDB\ReferenceOne(targetDocument: Artiste::class)]
    private Artiste $artiste;

    // Getter pour obtenir le nom de l'artiste

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    public function getArtisteNom(): string
    {
        return $this->artiste->getNom();
    }

    // Setter pour définir l'artiste
    public function setArtiste(Artiste $artiste): self
    {
        $this->artiste = $artiste;
        return $this;
    }
}
