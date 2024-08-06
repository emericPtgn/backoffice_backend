<?php

namespace App\Document;

use App\Document\Artiste;
use App\Document\Emplacement;
use App\Repository\ActiviteRepository;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: ActiviteRepository::class, collection: 'activite')]
class Activite
{
    #[MongoDB\Id]
    #[Groups(["activite", "programmation"])]
    private ?string $id;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite", "programmation"])]
    private ?string $nom = null;

    #[MongoDB\Field(type: "date")]
    #[Groups(["activite", "programmation"])]
    private ?\DateTime $date = null;

    #[MongoDB\Field(type: "string")]  // Changer le type en "string"
    #[Groups(["activite", "programmation"])]
    private ?string $formattedDate = null;

    #[MongoDB\Field(type: "int", name: 'duree_minutes')]
    #[Groups(["activite"])]
    private ?int $duree_min = null;

    #[MongoDB\Field(type: "string")]
    #[Groups(["activite", "programmation"])]
    private ?string $type = null; // Peut être 'concert', 'dedicace', 'jeu divers'

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["activite"])]
    protected ?string $description = null;

    #[MongoDB\ReferenceOne(targetDocument: Emplacement::class)]
    #[Groups(["activite"])]
    private ?Emplacement $emplacement = null;

    #[MongoDB\ReferenceOne(targetDocument: Artiste::class)]
    #[Groups(["activite", "programmation"])]
    private ?Artiste $artiste = null;

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

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        if ($date) {
            // Convertir en UTC avant de stocker
            $utcDate = clone $date;
            $utcDate->setTimezone(new \DateTimeZone("UTC"));
            $this->date = $utcDate;
        } else {
            $this->date = null;
        }
        return $this;
    }
    

    public function getFormattedDate(): ?string
    {
        if (!$this->date) {
            return null;
        }
    
        // Convertir la date en UTC pour le stockage
        $utcDate = clone $this->date;
        $utcDate->setTimezone(new \DateTimeZone("UTC"));
    
        // Formater la date pour le champ datetime-local
        return $utcDate->format('Y-m-d\TH:i'); // Format ISO 8601 attendu par datetime-local
    }
    
    

    private function formatDate(?\DateTime $date): ?string
    {
        if (!$date) {
            return null;
        }
    
        // Convertir la date UTC en date locale (Europe/Paris)
        $localDate = clone $date;
        $localDate->setTimezone(new \DateTimeZone("Europe/Paris")); // Convertir en fuseau horaire local
    
        // Formater la date comme souhaité
        return $localDate->format('Y-m-d\TH:i'); // Utiliser le format ISO 8601 attendu
    }
    

    public function getDuree(): ?int
    {
        return $this->duree_min;
    }

    public function setDuree(?int $duree_min): self
    {
        $this->duree_min = $duree_min;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getEmplacement(): ?Emplacement
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Emplacement $emplacement): self
    {
        $this->emplacement = $emplacement;
        return $this;
    }

    public function getArtiste(): ?Artiste
    {
        return $this->artiste;
    }

    public function setArtiste(?Artiste $artiste): self
    {
        $this->artiste = $artiste;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
