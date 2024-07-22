<?php

namespace App\Document;

use App\Document\Artiste;
use App\Document\Emplacement;
use App\Document\TypeActivite;
use App\Repository\ActiviteRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Attribute\SerializedName;



#[MongoDB\Document(repositoryClass: ActiviteRepository::class, collection: 'activite')]
class Activite
{
    #[MongoDB\Id]
    #[Groups(["activite"])]
    private ?string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite"])]
    private ?string $nom = null;

    #[MongoDB\Field(type: "date")]
    #[Groups(["activite"])]
    private ?\DateTime $date = null;

    #[MongoDB\Field(type: "int", name:'duree_minutes')]
    #[Groups(["activite"])]
    private ?int $duree_min = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite"])]
    private ?string $type = null; // Peut être 'concert', 'dedicace', 'jeu divers'

    #[MongoDB\EmbedOne(targetDocument: Emplacement::class)]
    #[Groups(["activite"])]
    private ?Emplacement $emplacement = null;

    #[MongoDB\ReferenceOne(targetDocument: TypeActivite::class)]
    #[Groups(["activite"])]
    private ?TypeActivite $typeActivite = null;

    #[MongoDB\EmbedOne(targetDocument: Artiste::class)]
    #[Groups(["activite"])]
    private ?Artiste $artiste = null;

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["activite"])]
    protected string $description;

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

    public function setDate(?\DateTime $date): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}