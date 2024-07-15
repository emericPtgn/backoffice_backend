<?php

namespace App\Document;

use App\Repository\ConcertRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: ConcertRepository::class, collection: 'concert')]
class Concert extends Activite
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\EmbedOne(targetDocument: Artiste::class)]
    private Artiste $artiste;

    // Autres propriétés et méthodes spécifiques aux concerts...

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getArtiste(): Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(Artiste $artiste): self
    {
        $this->artiste = $artiste;
        return $this;
    }
}
