<?php

namespace App\Document;

use App\Repository\ActiviteRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;



#[MongoDB\Document(repositoryClass: ActiviteRepository::class, collection: 'activite')]
class Activite
{
    #[MongoDB\Id]
    private string $id;

    #[MongoDB\Field(type: "string")]
    private string $nom;

    #[MongoDB\Field(type: "date")]
    private ?\DateTime $date;

    #[MongoDB\Field(type: "int", name:'duree_minutes')]
    private ?int $duree_min = null;

    #[MongoDB\Field(type: "string")]
    private string $type; // Peut être 'concert', 'dedicace', 'jeu divers'

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    private Emplacement $emplacement;

    #[MongoDB\ReferenceOne(targetDocument: TypeActivite::class)]
    private TypeActivite $typeActivite;

    #[MongoDB\EmbedOne(targetDocument: Artiste::class)]
    private Artiste $artiste;

    // Autres propriétés et méthodes communes...

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

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }
    public function getDuree(): int
    {
        return $this->duree_min;
    }

    public function setDuree(int $duree_min): self
    {
        $this->duree_min = $duree_min;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
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

    public function getTypeActivite(): TypeActivite
    {
        return $this->typeActivite;
    }

    public function setTypeActivite(TypeActivite $typeActivite): self
    {
        $this->typeActivite = $typeActivite;
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
