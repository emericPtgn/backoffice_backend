<?php

namespace App\Document;

use App\Repository\ReseauSocialRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: ReseauSocialRepository::class, collection: 'reseauSocial')]

class ReseauSocial {

    #[MongoDB\Id(strategy:"AUTO")]
    private ?string $id = null; 

    #[MongoDB\Field(type: 'string', name: 'plateforme')]
    private ?string $plateforme = null;

    #[MongoDB\Field(type: 'string', name: 'url')]
    private ?string $url = null;
    
    #[MongoDB\Field(type: 'string', name: 'pseudo')]
    private ?string $pseudo = null;

    #[MongoDB\Field(type:'string', name: 'icone_url')]
    private ?string $icone = null;

    // Getter pour id
    public function getId(): string
    {
        return $this->id;
    }

    // Setter pour id
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    // Getter pour plateforme
    public function getPlateforme(): string
    {
        return $this->plateforme;
    }

    // Setter pour plateforme
    public function setPlateforme(string $plateforme): self
    {
        $this->plateforme = $plateforme;
        return $this;
    }

    // Getter pour url
    public function getUrl(): string
    {
        return $this->url;
    }

    // Setter pour url
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    // Getter pour pseudo
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    // Setter pour pseudo
    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getIcone(): string
    {
        return $this->icone;
    }

    public function setIcone(string $icone): self
    {
        $this->icone = $icone;
        return $this;
    }

}