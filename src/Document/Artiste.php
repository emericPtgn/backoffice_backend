<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[MongoDB\Document(repositoryClass: "App\Repository\ArtisteRepository", collection:'artiste')]
class Artiste
{
    #[MongoDB\Id(strategy: "AUTO")]
    #[SerializedName("id")]
    protected string $id;

    #[MongoDB\Field(type: 'string', name: 'nom')]
    #[SerializedName("nom")]
    protected string $nom;

    #[MongoDB\Field(type: 'string', name: 'style')]
    #[SerializedName("style")]
    protected string $style;

    #[MongoDB\Field(type: 'string', name: 'description')]
    #[SerializedName("description")]
    protected string $description;

    #[MongoDB\EmbedMany(targetDocument: ReseauSocial::class)]
    protected Collection $reseauxSociaux;

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
