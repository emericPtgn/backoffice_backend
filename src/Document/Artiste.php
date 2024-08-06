<?php

namespace App\Document;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[MongoDB\Document(repositoryClass: "App\Repository\ArtisteRepository", collection:'artiste')]
#[MongoDB\UniqueIndex(keys: ['nom' => 'asc'], options: ['unique' => true])]
class Artiste
{
    #[MongoDB\Id(strategy: "AUTO")]
    #[Groups(["artiste", "activite", "programmation"])]
    protected string $id;

    #[MongoDB\Field(type: 'string', name: 'nom')]
    #[Groups(["artiste", "activite", "programmation"])]
    protected string $nom;

    #[MongoDB\Field(type: 'string', name: 'style')]
    #[Groups(["artiste", "activite"])]
    protected string $style;

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[Groups(["artiste", "activite"])]
    protected string $description;

    #[MongoDB\EmbedMany(targetDocument: ReseauSocial::class)]
    #[Groups(["artiste", "social"])]
    protected Collection $reseauxSociaux;


    public function __construct()
    {
        $this->reseauxSociaux = new ArrayCollection();
    }
    
    public function __tostring() : string {
        return $this->nom;
    }
    public function getId(): ?string
    {
        return $this->id;
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

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;
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
    public function getReseauxSociaux(): Collection
    {
        return $this->reseauxSociaux;
    }

    public function addReseauSocial(ReseauSocial $reseauSocial): self
    {
        if (!$this->reseauxSociaux->contains($reseauSocial)) {
            $this->reseauxSociaux->add($reseauSocial);
        }

        return $this;
    }

    public function removeReseauSocial(ReseauSocial $reseauSocial): self
    {
        $this->reseauxSociaux->removeElement($reseauSocial);
        return $this;
    }

    
}
